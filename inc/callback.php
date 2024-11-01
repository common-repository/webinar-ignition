<?php

defined( 'ABSPATH' ) || exit;

// TODO - Separate Backend and Frontend callbacks

// ADD NEW LEAD
add_action( 'wp_ajax_nopriv_webinarignition_add_lead', 'webinarignition_add_lead_callback' );
add_action( 'wp_ajax_webinarignition_add_lead', 'webinarignition_add_lead_callback' );
function webinarignition_add_lead_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$post_input = array();

	$post_input['name']         = filter_input( INPUT_POST, 'name', FILTER_UNSAFE_RAW );
	$post_input['firstName']    = filter_input( INPUT_POST, 'firstName', FILTER_UNSAFE_RAW );
	$post_input['email']        = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
	$post_input['phone']        = filter_input( INPUT_POST, 'phone', FILTER_UNSAFE_RAW );
	$post_input['source']       = filter_input( INPUT_POST, 'source', FILTER_UNSAFE_RAW, array( 'options' => array( 'default' => 'Optin' ) ) );
	$post_input['gdpr_data']    = filter_input( INPUT_POST, 'gdpr_data', FILTER_UNSAFE_RAW );
	$post_input['ip']           = filter_input( INPUT_POST, 'ip', FILTER_UNSAFE_RAW );
	$post_input['id']           = filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW );
	$post_input['id']           = ( empty( $post_input['id'] ) && ! empty( filter_input( INPUT_POST, 'campaignID', FILTER_UNSAFE_RAW ) ) ) ? filter_input( INPUT_POST, 'campaignID', FILTER_UNSAFE_RAW ) : $post_input['id'];
	$webinar_data               = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		$applang = $webinar_data->webinar_lang;
		switch_to_locale( $applang );
		unload_textdomain( 'webinarignition' );
		load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $applang . '.mo' );
	}

	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format ) ) {
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
	$time_format                = $webinar_data->time_format;
	$is_lead_protected          = ! empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;

	global $wpdb;
	$is_ajax = false;

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$is_ajax = true;
	}

	$table_db_name = $wpdb->prefix . 'webinarignition_leads';

	if ( $is_lead_protected ) {
		$sql = $wpdb->prepare("SELECT hash_ID AS ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $post_input['email'], $post_input['id']);
	} else {
		$sql = $wpdb->prepare("SELECT ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $post_input['email'], $post_input['id']);
	}

	$lead = $wpdb->get_row($sql);
	if ( $lead ) {
		wp_send_json( $lead->ID );
	}

	$data = array(
		'app_id'    => intval($post_input['id']),
		'name'      => $post_input['name'],
		'email'     => $post_input['email'],
		'phone'     => $post_input['phone'],
		'trk1'      => $post_input['source'],
		'trk3'      => $post_input['ip'],
		'event'     => 'No',
		'replay'    => 'No',
		'created'   => gmdate('F j, Y'),
		'gdpr_data' => $post_input['gdpr_data'],
	);

	$wpdb->insert($table_db_name, $data);

	$out = $wpdb->insert_id;

	$hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );

	$wpdb->update( $table_db_name, array( 'hash_ID' => $hash_ID ), array( 'ID' => $out ) );

	$wiRegForm_data = ! empty( filter_input( INPUT_POST, 'wiRegForm', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) ) ? filter_input( INPUT_POST, 'wiRegForm', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) : array();

	$lead_meta = array();

	foreach ( $wiRegForm_data as $field_name => $field ) {
		$field_label = rtrim( sanitize_text_field( $field['label'] ), '*' );
		$field_value = sanitize_text_field( $field['value'] );

		$lead_meta[ $field_name ] = array(
			'label' => $field_label,
			'value' => $field_value,
		);
	}

	if ( ! empty( $lead_meta ) ) {
		$lead_meta = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta );
		WebinarignitionLeadsManager::webinarignition_update_lead_meta( $out, 'wiRegForm', serialize( $lead_meta ), 'live' );
		WebinarignitionLeadsManager::webinarignition_update_lead_meta( $out, 'wiRegForm_' . $post_input['id'], serialize( $lead_meta ), 'live' );

		/**
		 * Action Hook: webinarignition_live_lead_added
		 *
		 * @param int $webinar_id Webinar ID for which the lead was added
		 * @param int $lead_id Lead ID which was added
		 * @param array $lead_metadata Associated lead metadata
		 */
		$webhook_lead_data = array();
		foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
			if ( is_array( $lead_meta_value ) ) {
				$webhook_lead_data[ $lead_meta_key ] = $lead_meta_value['value'];
			}
		}

		do_action( 'webinarignition_lead_added', absint( $post_input['id'] ), $out, $webhook_lead_data );
		do_action( 'webinarignition_live_lead_added', absint( $post_input['id'] ), $out, $webhook_lead_data );
		do_action( 'webinarignition_lead_status_changed', 'attended', $out, absint( $post_input['id'] ) );
	} //end if

	do_action( 'webinarignition_lead_created', $out, $table_db_name );

	$lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";

	if ( isset( $post_input['phone'] ) && 'undefined' !== $post_input['phone'] ) {
		$lead_details_string .= "Phone: {$post_input['phone']}";
	}

	// registration email has been disabled in notification settings
	if ( 'off' === $webinar_data->email_signup ) {
		WebinarIgnition_Logs::add(
			__( 'New Lead Added', 'webinarignition' ) . "\n$lead_details_string\n\n" . __( 'Not sending registration email (DISABLED)', 'webinarignition' ),
			$post_input['id'],
			WebinarIgnition_Logs::LIVE_EMAIL
		);

		if ( $is_lead_protected ) {
			echo esc_html( $hash_ID );
		} else {
			echo esc_attr( $out );
		}
		die();
	}

	WebinarIgnition_Logs::add(
		__( 'New Lead Added', 'webinarignition' ) . "\n$$lead_details_string\n\n" . __( 'Firing registration email', 'webinarignition' ),
		$post_input['id'],
		WebinarIgnition_Logs::LIVE_EMAIL
	);

	if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( 'yes' === $webinar_data->use_new_email_signup_template ) ) ) {
		// use new templates
		$webinar_data->emailheading     = $webinar_data->email_signup_heading;
		$webinar_data->emailpreview     = $webinar_data->email_signup_preview;
		$webinar_data->bodyContent      = $webinar_data->email_signup_body;
		$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ) ? $webinar_data->local_email_signup_footer : '';

		$email      = new WI_Emails();
		$emailBody  = $email->webinarignition_build_email( $webinar_data );
	} else {
		// This is an old webinar, created before this version
		$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
		$emailBody = $emailHead;
		$emailBody .= $webinar_data->email_signup_body;
		$emailBody .= '</html>';
	}

	$emailBody = str_replace( '{LEAD_NAME}', ( ! empty( $post_input['firstName'] ) ? sanitize_text_field( $post_input['firstName'] ) : $post_input['name'] ), $emailBody );
	$emailBody = str_replace( '{FIRSTNAME}', ( ! empty( $post_input['firstName'] ) ? sanitize_text_field( $post_input['firstName'] ) : $post_input['name'] ), $emailBody );

	$localized_date = webinarignition_get_localized_date( $webinar_data );

	$timeonly  = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
	// Replace
	$emailBody = str_replace( '{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz( $webinar_data->webinar_start_time, $time_format, $webinar_data->webinar_timezone, false, $timeonly ), $emailBody );

	$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $emailBody );

	$email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
	);

	webinarignition_test_smtp_options();

	try {
		if ( ! wp_mail( $post_input['email'], $email_signup_sbj, $emailBody, $headers ) ) {
			WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$post_input['email']}", WebinarIgnition_Logs::LIVE_EMAIL );
		} else {
			WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
		}
	} catch ( Exception $e ) {
		WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$post_input['email']}", WebinarIgnition_Logs::LIVE_EMAIL );
	}

	if ( ! empty( $webinar_data->get_registration_notices_state ) && ( 'show' === $webinar_data->get_registration_notices_state ) && ( ! empty( $webinar_data->registration_notice_email ) ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {

		$subj         = __( 'New Registration For', 'webinarignition' ) . ' ' . $webinar_data->webinar_desc . ' ' . __( 'By', 'webinarignition' ) . ' ' . $post_input['name'];
		$attendeeName = $post_input['name'];

		$emailBody = $emailHead;

		if ( ! empty( $lead_meta ) ) {
			foreach ( $lead_meta as $lead_field_key => $lead_field_data ) {
				if ( 'optName' === $lead_field_key && '#firstlast#' === $lead_field_data['value'] ) {
					continue; // Skip first last tag
				}

				$emailBody .= "<br><br>{$lead_field_data['label']}: {$lead_field_data['value']}";
			}
		}

		$emailBody .= '</html>';
		try {
			wp_mail( $webinar_data->registration_notice_email, $subj, $emailBody, $headers );
		} catch ( Exception $e ) {
			echo esc_html( $e->getMessage() );
		}
	} //end if

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		restore_previous_locale();
	}

	if ( false !== $is_ajax ) {
		if ( $is_lead_protected ) {
			echo esc_html( $hash_ID );
		} else {
			echo esc_attr( $out );
		}
		die();
	}

	if ( $is_lead_protected ) {
		return $hash_ID;
	} else {
		return $out;
	}
}


// ADD NEW EVERGREEN (auto) LEAD
add_action( 'wp_ajax_nopriv_webinarignition_get_lead_auto', 'webinarignition_get_lead_auto_callback' );
add_action( 'wp_ajax_webinarignition_get_lead_auto', 'webinarignition_get_lead_auto_callback' );
function webinarignition_get_lead_auto_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	// Only get the required input values
	$lead_id = intval(filter_input(INPUT_GET, 'lid', FILTER_SANITIZE_NUMBER_INT));

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
	$table_db_name = esc_sql($table_db_name);

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT `app_id`, `name`, `email`, `phone`, `date_picked_and_live`, `lead_timezone` 
		FROM {$table_db_name} 
		WHERE ID = %d", 
		$lead_id
	);

	$lead = $wpdb->get_row($query, OBJECT);
	if ( empty( $lead ) ) {
		// Sanitize input values
		$hash_id = sanitize_text_field(filter_input(INPUT_GET, 'lid', FILTER_UNSAFE_RAW));

		// Prepare and execute the query
		$query = $wpdb->prepare(
			"SELECT `app_id`, `name`, `email`, `phone`, `date_picked_and_live`, `lead_timezone` 
			FROM {$table_db_name} 
			WHERE hash_ID = %s", 
			$hash_id
		);

		$lead = $wpdb->get_row($query, OBJECT);
	}

	if ( is_object( $lead ) ) {

		if ( ! isset( $lead->lname ) && strrpos( $lead->name, ' ' ) ) {
			$lead->lname = explode( ' ', $lead->name, 2 );
			$lead->name  = $lead->lname[0];
			$lead->lname = $lead->lname[1];
		}

		$webinar                           = WebinarignitionManager::webinarignition_get_webinar_data( $lead->app_id );
		$arCustomDateFormat                = isset( $webinar->ar_custom_date_format ) ? $webinar->ar_custom_date_format : 'not-set';
		$webinarignition_webinar_timestamp = strtotime( $lead->date_picked_and_live );
		$arWebinarDate                     = webinarignition_format_date_for_ar_service( $arCustomDateFormat, $webinarignition_webinar_timestamp );
		$lead->webinar_date                = $arWebinarDate;
		$lead->webinar_time                = gmdate( 'g:i A', strtotime( $lead->date_picked_and_live ) );

		$lead->lead_timezone = $lead->lead_timezone . ' (UTC' . webinarignition_get_timezone_offset_by_name( $lead->lead_timezone ) . ')';

		echo wp_json_encode( $lead );
		exit;
	}

	$object          = new stdClass();
	$object->message = 'lead not found';

	echo wp_json_encode( $object );
	exit;
}

add_action( 'wp_ajax_nopriv_webinarignition_add_lead_auto', 'webinarignition_add_lead_auto_callback' );
add_action( 'wp_ajax_webinarignition_add_lead_auto', 'webinarignition_add_lead_auto_callback' );
function webinarignition_add_lead_auto_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$post_input = array();
	$post_input['name']         = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null;
	$post_input['email']        = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null;
	$post_input['phone']        = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : null;
	$post_input['id']           = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : null;
	$post_input['timezone']     = isset( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : null;
	$post_input['date']         = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : null;
	$post_input['time']         = isset( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : null;
	$post_input['gdpr_data']    = isset( $_POST['gdpr_data'] ) ? sanitize_text_field( $_POST['gdpr_data'] ) : null;

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );

	// Delete existing lead
	if ( ! empty( $webinar_data ) ) {
		$delete_lead_id = webinarignition_existing_lead_id( $post_input['id'], $post_input['email'] );

		if ( ! empty( $delete_lead_id ) ) {
			webinarignition_delete_lead_by_id( $delete_lead_id );
		}
	}

	$applang = $webinar_data->webinar_lang;

	if ( $applang ) {
		switch_to_locale( $applang );
		unload_textdomain( 'webinarignition' );
		load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $applang . '.mo' );
	}

	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format ) ) {
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}

	$time_format    = $webinar_data->time_format;
	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';

	if ( ! empty( $post_input['timezone'] ) ) {
		$lead_timezone = new DateTimeZone( $post_input['timezone'] );
	} else {
		$lead_timezone = get_option( 'timezone_string' );
	}

	// Get info
	$webinarLength   = $webinar_data->auto_video_length;
	$setCheckInstant = '';
	$instant         = 'no';

	if ( 'instant_access' === $post_input['date'] ) {
		$current_time = new DateTime( 'now', $lead_timezone );
		$todaysDate   = $current_time->format( 'Y-m-d' );
		$todaysTime   = $current_time->format( 'H:i' );

		// They choose to watch replay
		$time               = gmdate( 'H:i', strtotime( $todaysTime . '+0 hours' ) );
		$post_input['date'] = $todaysDate;
		$post_input['time'] = $time;

		$instant         = 'yes';
	}

	$is_ty_page_skipped = false;
	if ( 'yes' === $instant ) {
		$is_ty_page_skipped = ( isset( $webinar_data->skip_instant_acces_confirm_page ) && 'yes' === $webinar_data->skip_instant_acces_confirm_page );
	}

	// Get & Set Dates For Emails...
	$dpl = $post_input['date'] . ' ' . $post_input['time'];
	$fmt = 'Y-m-d H:i';

	$date_picked_and_live = gmdate( $fmt, strtotime( $dpl ) );
	$date_1_day_before    = gmdate( $fmt, strtotime( $dpl . ' -1 days' ) );
	$date_1_hour_before   = gmdate( $fmt, strtotime( $dpl . ' -1 hours' ) );
	$date_after_live      = gmdate( $fmt, strtotime( $dpl . ' +$webinarLength minutes' ) );
	$date_1_day_after     = gmdate( $fmt, strtotime( $dpl . ' +1 days' ) );

	$wiRegForm_data = ! empty( $post_input['wiRegForm'] ) ? $post_input['wiRegForm'] : array();

	$lead_meta = array();

	foreach ( $wiRegForm_data as $field_name => $field ) {
		$field_label = rtrim( sanitize_text_field( $field['label'] ), '*' );
		$field_value = sanitize_text_field( $field['value'] );

		$lead_meta[ $field_name ] = array(
			'label' => $field_label,
			'value' => $field_value,
		);
	}

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';

	$d = array(
		'app_id'                     => $post_input['id'],
		'name'                       => $post_input['name'],
		'email'                      => $post_input['email'],
		'phone'                      => ! empty( $post_input['phone'] ) ? $post_input['phone'] : '',
		'lead_timezone'              => ! empty( $post_input['timezone'] ) ? $post_input['timezone'] : '',
		'trk1'                       => 'Optin',
		'trk3'                       => ! empty( $post_input['ip'] ) ? $post_input['ip'] : '',
		'trk8'                       => $instant,
		'event'                      => ( 'yes' === $instant && $is_ty_page_skipped ) ? 'Yes' : 'No', // Set attended "Yes" for instant leads only when user get redirected to webinar page directly, skipping ty page setting
		'replay'                     => ( 'yes' === $instant && $is_ty_page_skipped ) ? 'Yes' : 'No',
		'created'                    => gmdate( 'F j, Y' ),
		'date_picked_and_live'       => $date_picked_and_live,
		'date_1_day_before'          => $date_1_day_before,
		'date_1_hour_before'         => $date_1_hour_before,
		'date_after_live'            => $date_after_live,
		'date_1_day_after'           => $date_1_day_after,
		'date_picked_and_live_check' => $setCheckInstant,
		'date_1_day_before_check'    => $setCheckInstant,
		'date_1_hour_before_check'   => $setCheckInstant,
		'date_after_live_check'      => $setCheckInstant,
		'gdpr_data'                  => ! empty( $post_input['gdpr_data'] ) ? $post_input['gdpr_data'] : '',
	);

	$wpdb->insert(
		$table_db_name,
		$d
	);

	$out     = $wpdb->insert_id;
	$hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );


	$wpdb->query( $wpdb->prepare(
		"UPDATE $table_db_name SET hash_ID = %s WHERE ID = %d",
		$hash_ID,
		$out
	) );

	if ( ! empty( $lead_meta ) ) {
		$lead_meta = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta );
		WebinarignitionLeadsManager::webinarignition_update_lead_meta( $out, 'wiRegForm', serialize( $lead_meta ), 'evergreen' );
		WebinarignitionLeadsManager::webinarignition_update_lead_meta( $out, 'wiRegForm_' . $post_input['id'], serialize( $lead_meta ), 'evergreen' );

		/**
		 * Action Hook: webinarignition_lead_added
		 *
		 * @param int $webinar_id Webinar ID for which the lead was added
		 * @param int $lead_id Lead ID which was added
		 * @param array $lead_metadata Associated lead metadata
		 */
		$webhook_lead_data = array();
		foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
			if ( is_array( $lead_meta_value ) ) {
				$webhook_lead_data[ $lead_meta_key ] = $lead_meta_value['value'];
			}
		}

		do_action( 'webinarignition_lead_added', absint( $post_input['id'] ), $out, $webhook_lead_data );
		do_action( 'webinarignition_live_lead_added', absint( $post_input['id'] ), $out, $webhook_lead_data );

		if ( 'yes' === $instant ) { // Trigger status change hooks
			do_action( 'webinarignition_lead_status_changed', 'attended', $out, absint( $post_input['id'] ) );
		}
	}//end if

	$cookieID = $out;
	do_action( 'webinarignition_lead_created', $out, $table_db_name );
	$lead_id = $out;

	$is_lead_protected = ! empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
	if ( $is_lead_protected ) {
		$lead_id = $hash_ID;
	}

	echo esc_attr( $lead_id );

	$lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";

	if ( ! empty( $post_input['phone'] ) ) {
		$lead_details_string .= "Phone: {$post_input['phone']}";
	}

	$send_signup_user_notification  = isset( $webinar_data->email_signup ) && 'off' !== $webinar_data->email_signup;
	$send_signup_admin_notification = isset( $webinar_data->get_registration_notices_state ) && 'show' === $webinar_data->get_registration_notices_state;

	WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
	WebinarIgnition_Logs::add( $lead_details_string, $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );

	/*
	|-------------------------------------------------------------------------------------------
	|  EMAIL SENDING`
	|-------------------------------------------------------------------------------------------
	*/

	// Send sign-up email to user
	if ( ! $send_signup_user_notification || ( 'yes' === $instant && $is_ty_page_skipped ) ) {
		WebinarIgnition_Logs::add( __( 'Not sending user sign-up email', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
	} else {
		WebinarIgnition_Logs::add( __( 'Sending user sign-up email', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );

		if ( ! empty( $webinar_data->templates_version ) || ( isset( $webinar_data->use_new_email_signup_template ) && 'yes' === $webinar_data->use_new_email_signup_template ) ) {
			// Use new templates
			$webinar_data->emailheading     = $webinar_data->email_signup_heading;
			$webinar_data->emailpreview     = $webinar_data->email_signup_preview;
			$webinar_data->bodyContent      = $webinar_data->email_signup_body;
			$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ) ? $webinar_data->local_email_signup_footer : '';

			$email      = new WI_Emails();
			$emailBody  = $email->webinarignition_build_email( $webinar_data );
		} else {
			$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
			$emailBody = $emailHead;
			$emailBody .= $webinar_data->email_signup_body;
		}

		$email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );

		$name = '';
		if ( isset( $post_input['name'] ) && ! empty( $post_input['name'] ) ) {
			$name = sanitize_text_field( $post_input['name'] );
		}

		if ( isset( $post_input['firstName'] ) && ! empty( $post_input['firstName'] ) ) {
			$name = sanitize_text_field( $post_input['firstName'] );
		}

		$emailBody = str_replace( '{LEAD_NAME}', $name, $emailBody );
		$emailBody = str_replace( '{FIRSTNAME}', $name, $emailBody );

		if ( ! isset( $webinar_data->webinar_permalink ) ) {
			$webinar_data->webinar_permalink = WebinarignitionManager::get_permalink( $post_input['id'], 'webinar' );
		}

		$translated_date = '';
		if ( isset( $post_input['date'] ) && ! empty( $post_input['date'] ) ) {
			$translated_date = webinarignition_get_translated_date( sanitize_text_field( $post_input['date'] ), 'Y-m-d', $date_format );
		}

		// Replace
		if ( 'yes' === $instant ) {
			if ( empty( $webinar_data->auto_translate_instant ) ) {
				$emailBody = str_replace( '{DATE}', 'Watch Replay', $emailBody );
			} else {
				$emailBody = str_replace( '{DATE}', $webinar_data->auto_translate_instant, $emailBody );
			}
		} else {
			$timeonly  = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
			$emailBody = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( $post_input['time'], $time_format, $post_input['timezone'], false, $timeonly ), $emailBody );
		}

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
		);

		webinarignition_test_smtp_options();

		$watch_type = 'live';
		$additional_email_query_params = 'event=OI3shBXlqsw';
		$additional_email_query_params .= "&watch_type={$watch_type}";

		$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $emailBody, $additional_email_query_params );

		try {
			if ( ! wp_mail( $post_input['email'], $email_signup_sbj, $emailBody, $headers ) ) {
				WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$post_input['email']}", $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
			} else {
				WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
			}
		} catch ( Exception $e ) {
			WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$post_input['email']}", $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
		}
	} //end if

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		restore_previous_locale();
	}

	// Send new user sign-up notification email to admin
	if ( $send_signup_admin_notification && ( isset( $webinar_data->registration_notice_email ) && ! empty( $webinar_data->registration_notice_email ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) ) {

        WebinarIgnition_Logs::add( __( 'Sending new user sign-up notification email to admin', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );

        $headers   = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
        );
        $subj      = __( 'New Registration For', 'webinarignition' ) . ' ' . $webinar_data->webinar_desc . ' ' . __( 'By', 'webinarignition' ) . ' ' . $post_input['name'];
        $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
        $emailBody = $emailHead;

        if ( ! empty( $lead_meta ) ) {
            foreach ( $lead_meta as $lead_field_key => $lead_field_data ) {
                if ( 'optName' === $lead_field_key && '#firstlast#' === $lead_field_data['value'] ) {
                    continue; // Skip firstlast tag
                }
                $emailBody .= "<br><br>{$lead_field_data['label']}: {$lead_field_data['value']}";
            }
        }

        $emailBody .= '</html>';

        try {
            wp_mail( $webinar_data->registration_notice_email, $subj, $emailBody, $headers );
        } catch ( Exception $e ) {
            echo esc_attr( $e->getMessage() );
        }
    } else {
        WebinarIgnition_Logs::add( __( 'Not sending new user sign-up notification email to admin', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
    } //end if

    die();
}


// ADD NEW LEAD
add_action( 'wp_ajax_nopriv_webinarignition_add_lead_auto_reg', 'webinarignition_add_lead_auto_reg_callback' );
add_action( 'wp_ajax_webinarignition_add_lead_auto_reg', 'webinarignition_add_lead_auto_reg_callback' );
function webinarignition_add_lead_auto_reg_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$post_input = array();

	$post_input['name']         = filter_input( INPUT_POST, 'name', FILTER_UNSAFE_RAW );
	$post_input['firstName']    = filter_input( INPUT_POST, 'firstName', FILTER_UNSAFE_RAW );
	$post_input['email']        = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
	$post_input['phone']        = filter_input( INPUT_POST, 'phone', FILTER_UNSAFE_RAW );
	$post_input['id']           = filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW );
	$post_input['source']       = filter_input( INPUT_POST, 'source', FILTER_UNSAFE_RAW, array( 'options' => array( 'default' => 'Optin' ) ) );
	$post_input['gdpr_data']    = filter_input( INPUT_POST, 'gdpr_data', FILTER_UNSAFE_RAW );
	$post_input['ip']           = filter_input( INPUT_POST, 'ip', FILTER_UNSAFE_RAW );

	if ( empty( $post_input['email'] ) || empty( $post_input['id'] ) ) {
		WebinarignitionAjax::error_response(array(
			'message' => __( 'Error', 'webinarignition' ) . ': ' . __( 'Cheating, huh!!!.1', 'webinarignition' ),
		));
	}

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );
	$applang = $webinar_data->webinar_lang;

	if ( $applang ) {
		switch_to_locale( $applang );
		unload_textdomain( 'webinarignition' );
		load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $applang . '.mo' );
	}

	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format ) ) {
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}

	$time_format       = $webinar_data->time_format;
	$date_format       = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';
	$is_lead_protected = ! empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';

	// Check if lead with such email exists in database
	$email = sanitize_email($post_input['email']);
	$app_id = intval($post_input['id']);

	if ($is_lead_protected) {
		// Prepare and execute the query for protected leads
		$query = $wpdb->prepare(
			"SELECT hash_ID AS ID FROM {$table_db_name} WHERE email = %s AND app_id = %d",
			$email,
			$app_id
		);
	} else {
		// Prepare and execute the query for non-protected leads
		$query = $wpdb->prepare(
			"SELECT ID FROM {$table_db_name} WHERE email = %s AND app_id = %d",
			$email,
			$app_id
		);
	}

	$lead = $wpdb->get_row($query);

	// If the lead exists, return success response
	if ($lead) {
		$response = array(
			'success' => 1,
			'lid'     => $lead->ID,
		);
		echo wp_json_encode($response);
		wp_die();
	}

	// Lead does not exist, insert new lead

	// Sanitize input values
	$name   = sanitize_text_field($post_input['name']);
	$source = !empty($post_input['source']) ? sanitize_text_field($post_input['source']) : 'Optin';
	$ip     = sanitize_text_field($post_input['ip']);

	// Convert date to MySQL datetime format
	$created = gmdate('Y-m-d H:i:s');

	$data = array(
		'app_id'  => $app_id,
		'name'    => $name,
		'email'   => $email,
		'trk1'    => $source,
		'trk3'    => $ip,
		'event'   => 'No',
		'replay'  => 'No',
		'created' => $created,
	);

	$format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

	// Correct usage of wpdb->insert
	$db_lead_created = $wpdb->insert($table_db_name, $data, $format);

	// Get the ID of the inserted row
	$out = $wpdb->insert_id;

	$hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );

	$wpdb->query( $wpdb->prepare(
		"UPDATE $table_db_name SET hash_ID = %s WHERE ID = %d",
		$hash_ID,
		$out
	) );

	$lead_meta = $post_input;
	$lead_meta['hash_ID'] = $hash_ID;
	$webinar_type = strtolower( trim( $webinar_data->webinar_date ) ) !== 'auto' ? 'evergreen' : 'live';
	/**
	 * Action Hook: webinarignition_lead_added
	 *
	 * @param int $webinar_id Webinar ID for which the lead was added
	 * @param int $lead_id Lead ID which was added
	 * @param array $lead_metadata Associated lead metadata
	 */
	$webhook_lead_data = array();
	foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
		if ( is_array( $lead_meta_value ) ) {
			$webhook_lead_data[ $lead_meta_key ] = $lead_meta_value['value'];
		}
	}
	do_action( 'webinarignition_lead_added', absint( $post_input['id'] ), $out, $webhook_lead_data );
	do_action( 'webinarignition_live_lead_added', absint( $post_input['id'] ), $out, $webhook_lead_data );

	do_action( 'webinarignition_lead_created', $out, $table_db_name );
	$lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";
	WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinarignition' ) . "\n$lead_details_string\n\n" . __( 'Firing registration email', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );

	// ADD TO MAILING LIST
	$emailBody = $webinar_data->email_signup_body;
	$emailBody = str_replace( '{LEAD_NAME}', ( ! empty( $post_input['firstName'] ) ? $post_input['firstName'] : $post_input['name'] ), $emailBody );
	$emailBody = str_replace( '{FIRSTNAME}', ( ! empty( $post_input['firstName'] ) ? $post_input['firstName'] : $post_input['name'] ), $emailBody );

	// NB: date format for Live webinars always saved in DB as m-d-Y
	$translated_date = webinarignition_get_translated_date( $webinar_data->webinar_date, 'm-d-Y', $date_format );

	$timeonly  = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
	// Replace
	$emailBody = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( isset( $webinar_data->webinar_start_time ) ? $webinar_data->webinar_start_time : '', $time_format, isset( $webinar_data->webinar_timezone ) ? $webinar_data->webinar_timezone : '', false, $timeonly ), $emailBody );

	$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $emailBody );

	$email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
	$headers          = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
	);

	webinarignition_test_smtp_options();

	try {
		if ( ! wp_mail( $post_input['email'], $email_signup_sbj, $emailBody, $headers ) ) {
			WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$post_input['email']}", $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
		} else {
			WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinarignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
		}
	} catch ( Exception $e ) {
		WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$post_input['email']}", $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
	}

	if ( ( 'show' === $webinar_data->get_registration_notices_state ) && ( ! empty( $webinar_data->registration_notice_email ) ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {

		$subj         = 'New Registration For Webinar ' . $webinar_data->webinar_desc;
		$attendeeName = $post_input['name'];

		$emailBody = $attendeeName . ' (' . $post_input['email'] . ') ' . __( 'has just registered for your webinar', 'webinarignition' ) . ' ' . $webinar_data->webinar_desc;

		try {
			wp_mail( $webinar_data->registration_notice_email, $subj, $emailBody, $headers );
		} catch ( Exception $e ) {
			echo esc_attr( $e->getMessage() );
		}
	}

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		restore_previous_locale();
	}

	if ( $is_lead_protected ) {
		$response = array(
			'success' => 1,
			'lid' => $hash_ID,
		);
	} else {
		$response = array(
			'success' => 1,
			'lid' => $out,
		);
	}

	echo wp_json_encode( $response );
	wp_die();
}

/**
 * TODO: This function might not be in used, need to check further before removing it.
 *
 * @param int    $ID The lead id.
 * @param string $name The lead name.
 * @param string $email The lead email.
 * @param string $IP The lead ip address.
 */
function webinarignition_add_lead_fb( $ID, $name, $email, $IP ) {
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
	$applang = $webinar_data->webinar_lang;

	if ( $applang ) {
		switch_to_locale( $applang );
		unload_textdomain( 'webinarignition' );
		load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $applang . '.mo' );
	}

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';

	$ID    = sanitize_text_field( $ID );
	$name  = sanitize_text_field( $name );
	$email = sanitize_email( $email );

	$wpdb->prepare(
		"INSERT INTO $table_db_name
		(app_id, name, email, trk1, trk3, created)
		VALUES (%s, %s, %s, %s, %s, %s)",
		$ID,
		$name,
		$email,
		'FB',
		$IP,
		gmdate( 'F j, Y' )
	);

	$wpdb->query( $wpdb->prepare );

	$get_lead_id = $wpdb->insert_id;
	$hash_ID   = sha1( $ID . $email . $get_lead_id );

	$wpdb->prepare(
		"UPDATE $table_db_name SET hash_ID = %s WHERE ID = %d",
		$hash_ID,
		$get_lead_id
	);
	$wpdb->query( $wpdb->prepare );

	echo esc_attr( $get_lead_id );

	$lead_details_string = "Name: {$name}\nEmail: {$email}\n";
	WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinarignition' ) . "\n$lead_details_string\n\n" . __( 'Firing registration email', 'webinarignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );

	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format ) ) {
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
	$time_format    = $webinar_data->time_format;
	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : ( ( 'AUTO' === $webinar_data->webinar_date ) ? 'l, F j, Y' : get_option( 'date_format' ) );

	$emailBody = $webinar_data->email_signup_body;

	// NB: date format for Live webinars always saved in DB as m-d-Y
	$translated_date = webinarignition_get_translated_date( $webinar_data->webinar_date, 'm-d-Y', $date_format );

	$timeonly = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
	// Replace
	$emailBody = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( $webinar_data->webinar_start_time, $time_format, $webinar_data->webinar_timezone, false, $timeonly ), $emailBody );

	$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $get_lead_id, $emailBody );

	$email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
	);

	webinarignition_test_smtp_options();

	try {
		if ( ! wp_mail( $email, $email_signup_sbj, $emailBody, $headers ) ) {
			WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$email}", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
			exit;
		} else {
			WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinarignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );
		}
	} catch ( Exception $e ) {
		WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$email}", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
		exit;
	}

	if ( ( 'show' === $webinar_data->get_registration_notices_state ) && ( ! empty( $webinar_data->registration_notice_email ) ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {

		$subj = __( 'New Registration For Webinar', 'webinarignition' ) . ' ' . $webinar_data->webinar_desc;

		$emailBody = $name . ' ' . __( 'has just registered for your webinar', 'webinarignition' ) . ' ' . $webinar_data->webinar_desc;

		try {
			wp_mail( $webinar_data->registration_notice_email, $subj, $emailBody, $headers );
		} catch ( Exception $e ) {
			echo esc_attr( $e->getMessage() );
		}
	}

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		restore_previous_locale();
	}
}

function webinarignition_get_fb_id( $ID, $email ) {
	// Get ID for the FB Lead
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';
	$query = $wpdb->prepare(
		"SELECT * FROM $table_db_name WHERE app_id = %s AND email = %s",
		$ID,
		$email
	);
	$findstat = $wpdb->get_row($query, OBJECT);

	return $findstat->ID;
}

// Track View - LANDING PAGE
add_action( 'wp_ajax_nopriv_webinarignition_track_lp_view', 'webinarignition_track_lp_view_callback' );
add_action( 'wp_ajax_webinarignition_track_lp_view', 'webinarignition_track_lp_view_callback' );
function webinarignition_track_lp_view_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';

	// Sanitize input value
	$ID = intval($ID);

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM {$table_db_name} WHERE id = %d",
		$ID
	);

	$findstat = $wpdb->get_row($query, OBJECT);

	// Prepare the update query
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$table_db_name} SET total_views = total_views + 1 WHERE id = %d",
			$ID
		)
	);
}

// ADD NEW QUESTION
add_action( 'wp_ajax_nopriv_webinarignition_submit_question', 'webinarignition_submit_question_callback' );
add_action( 'wp_ajax_webinarignition_submit_question', 'webinarignition_submit_question_callback' );
function webinarignition_submit_question_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$post_input = array(
		'name' => filter_input( INPUT_POST, 'name', FILTER_UNSAFE_RAW ),
		'email' => filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ),
		'id' => filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ),
		'question' => filter_input( INPUT_POST, 'question', FILTER_UNSAFE_RAW ),
		'lead' => filter_input( INPUT_POST, 'lead', FILTER_UNSAFE_RAW ),
		'webinar_type' => filter_input( INPUT_POST, 'webinar_type', FILTER_UNSAFE_RAW ),
		'webinarTime' => filter_input( INPUT_POST, 'webinarTime', FILTER_UNSAFE_RAW ),
		'is_first_question' => wp_validate_boolean( filter_input( INPUT_POST, 'is_first_question' ) )
	);

	$timezone_string = get_option( 'timezone_string' );

	$created = gmdate( 'Y-m-d h:i:sa' );

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_questions';

	$post_input['name']         = isset( $post_input['name'] ) ? sanitize_text_field( $post_input['name'] ) : null;
	$post_input['email']        = isset( $post_input['email'] ) ? sanitize_email( $post_input['email'] ) : null;
	$post_input['id']           = isset( $post_input['id'] ) ? sanitize_text_field( $post_input['id'] ) : null;
	$post_input['question']     = isset( $post_input['question'] ) ? sanitize_text_field( $post_input['question'] ) : null;
	$post_input['lead']         = isset( $post_input['lead'] ) ? sanitize_text_field( $post_input['lead'] ) : null;
	$post_input['webinar_type']      = isset( $post_input['webinar_type'] ) ? sanitize_text_field( $post_input['webinar_type'] ) : null;
	$post_input['webinarTime']       = isset( $post_input['webinarTime'] ) ? sanitize_text_field( $post_input['webinarTime'] ) : null;
	$post_input['is_first_question'] = wp_validate_boolean( $post_input['is_first_question'] );

	$data = array(
		'app_id' => $post_input['id'],
		'name' => $post_input['name'],
		'email' => $post_input['email'],
		'question' => $post_input['question'],
		'type' => 'question',
		'status' => 'live',
		'created' => current_time( 'mysql' ),
		'webinarTime' => $post_input['webinarTime'],
	);

	$id = WebinarignitionQA::webinarignition_create_question( $data );

	$data['webinar_type'] = $post_input['webinar_type'];
	$data['is_first_question'] = $post_input['is_first_question'];

	do_action( 'webinarignition_question_asked', $data );

	wp_send_json( $id );
}


add_action( 'webinarignition_question_asked', 'webinarignition_send_after_question_live_support_request' );

function webinarignition_send_after_question_live_support_request( $supportData ) {

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $supportData['app_id'] );
	$applang = $webinar_data->webinar_lang;

	if ( $applang ) {
		switch_to_locale( $applang );
		unload_textdomain( 'webinarignition' );
		load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $applang . '.mo' );
	}

	if ( 'AUTO' === $webinar_data->webinar_date || ! WebinarignitionPowerups::webinarignition_is_multiple_support_enabled( $webinar_data ) ) {
		return;
	}

	$send_question_notification = false;

	if ( isset( $webinar_data->enable_first_question_notification ) && ( 'yes' === $webinar_data->enable_first_question_notification ) && ( 'no' === $webinar_data->first_question_notification_sent ) ) {
		$send_question_notification  = true;
	}

	if ( $send_question_notification && isset( $webinar_data->support_staff_count ) && ( ! empty( $webinar_data->support_staff_count ) ) ) {
		for ( $x = 1; $x <= $webinar_data->support_staff_count; $x++ ) {

			$member_email = 'member_email_' . $x;

			if ( property_exists( $webinar_data, $member_email ) ) {
				$qstn_notification_email_body   = $webinar_data->qstn_notification_email_body;
				$emailSubj                      = $webinar_data->qstn_notification_email_sbj;
				$member                         = get_user_by( 'email', $webinar_data->{'member_email_' . $x} );

				if ( is_object( $member ) ) {
					$email_data                     = new stdClass();
					$_wi_support_token              = get_user_meta( $member->ID, '_wi_support_token', true );
					$support_link                   = $webinar_data->webinar_permalink . '?console&_wi_support_token=' . $_wi_support_token . '#/questions';

					$replacement                    = array( $member->first_name, $supportData['name'], $webinar_data->webinar_desc, $support_link );
					$replace                        = array( '{support}', '{attendee}', '{webinarTitle}', '{link}' );
					$email_data->bodyContent        = str_replace( $replace, $replacement, $qstn_notification_email_body );
					$email_data->footerContent      = ( ! empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && ( 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ) ) ? $webinar_data->qstn_answer_email_footer : '';

					$email_data->email_subject      = __( 'Questions From Your Webinar', 'webinarignition' );
					$email_data->emailheading       = __( 'Questions From Your Webinar', 'webinarignition' );
					$email_data->emailpreview       = __( 'Questions From Your Webinar', 'webinarignition' );

					$email                          = new WI_Emails();
					$emailBody                      = $email->webinarignition_build_email( $email_data );

					$headers                = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

					try {
						if ( ! wp_mail( $member->user_email, $emailSubj, $emailBody, $headers ) ) {
							WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinarignition' ) . " {$member->email}", WebinarIgnition_Logs::LIVE_EMAIL );
						} elseif ( property_exists( $webinar_data, 'first_question_notification_sent' ) && ( 'no' === $webinar_data->first_question_notification_sent ) ) {
							$webinar_data->first_question_notification_sent = 'yes';
							update_option( 'webinarignition_campaign_' . $supportData['app_id'], $webinar_data );
							WebinarIgnition_Logs::add( __( 'Support request has been sent.', 'webinarignition' ), $supportData['app_id'], WebinarIgnition_Logs::LIVE_EMAIL );
						}
					} catch ( Exception $e ) {
						WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinarignition' ) . " {$member->user_email}", WebinarIgnition_Logs::LIVE_EMAIL );
					}
				} //end if
			} //end if
		} //end for
	} //end if

	if ( $send_question_notification && isset( $webinar_data->send_host_questions_notifications ) && ( 'yes' === $webinar_data->send_host_questions_notifications ) && isset( $webinar_data->host_questions_notifications_email ) ) {
		if ( filter_var( $webinar_data->host_questions_notifications_email, FILTER_VALIDATE_EMAIL ) ) {
			$qstn_notification_email_body   = $webinar_data->qstn_notification_email_body;
			$emailSubj                      = $webinar_data->qstn_notification_email_sbj;
			$support_link                   = $webinar_data->webinar_permalink . '/?console#/questions';

			$replacement                    = array( $webinar_data->webinar_host, $supportData['name'], $webinar_data->webinar_desc, $support_link );
			$replace                        = array( '{support}', '{attendee}', '{webinarTitle}', '{link}' );

			$email_data->bodyContent        = str_replace( $replace, $replacement, $qstn_notification_email_body );
			$email_data->footerContent      = ( ! empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && ( 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ) ) ? $webinar_data->qstn_answer_email_footer : '';
			$email_data->email_subject      = __( 'Questions From Your Webinar', 'webinarignition' );
			$email_data->emailheading       = __( 'Questions From Your Webinar', 'webinarignition' );
			$email_data->emailpreview       = __( 'Questions From Your Webinar', 'webinarignition' );

			$wi_emails                      = new WI_Emails();
			$emailBody                      = $email->webinarignition_build_email( $email_data );

			$headers                = array(
				'Content-Type: text/html; charset=UTF-8',
				'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
			);

			try {
				if ( ! wp_mail( $webinar_data->host_questions_notifications_email, $emailSubj, $emailBody, $headers ) ) {
					WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent', 'webinarignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
				}
			} catch ( Exception $e ) {
				WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent.', 'webinarignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
			}
		} //end if
	} //end if

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		restore_previous_locale();
	}
}

add_action( 'webinarignition_question_asked', 'webinarignition_send_after_question_auto_support_request' );

function webinarignition_send_after_question_auto_support_request( $supportData ) {

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $supportData['app_id'] );
	$applang = $webinar_data->webinar_lang;

	if ( $applang ) {
		switch_to_locale( $applang );
		unload_textdomain( 'webinarignition' );
		load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $applang . '.mo' );
	}

	if ( ! WebinarignitionPowerups::webinarignition_is_multiple_support_enabled( $webinar_data ) || ( 'AUTO' === $webinar_data->webinar_date && ! $supportData['is_first_question'] ) ) {
		return;
	}

	$send_question_notification = false;

	if ( isset( $webinar_data->enable_first_question_notification ) && ( 'yes' === $webinar_data->enable_first_question_notification ) ) {
		$send_question_notification  = true;
	}

	if ( $send_question_notification && isset( $webinar_data->support_staff_count ) && ( ! empty( $webinar_data->support_staff_count ) ) ) {
		for ( $x = 1; $x <= $webinar_data->support_staff_count; $x++ ) {

			$member_email = 'member_email_' . $x;

			if ( property_exists( $webinar_data, $member_email ) ) {

				$qstn_notification_email_body   = $webinar_data->qstn_notification_email_body;
				$emailSubj                      = $webinar_data->qstn_notification_email_sbj;
				$member                         = get_user_by( 'email', $webinar_data->{'member_email_' . $x} );

				if ( is_object( $member ) ) {

					$_wi_support_token      = get_user_meta( $member->ID, '_wi_support_token', true );
					$support_link           = $webinar_data->webinar_permalink . '?console&_wi_support_token=' . $_wi_support_token . '#/questions';

					$replacement            = array( $member->first_name, $supportData['name'], $webinar_data->webinar_desc, $support_link );
					$replace                = array( '{support}', '{attendee}', '{webinarTitle}', '{link}' );

					$email_data                     = new stdClass();
					$email_data->bodyContent        = str_replace( $replace, $replacement, $qstn_notification_email_body );
					$email_data->footerContent      = ( ! empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && ( 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ) ) ? $webinar_data->qstn_answer_email_footer : '';
					$email_data->email_subject      = $webinar_data->qstn_notification_email_sbj;
					$email                          = new WI_Emails();
					$emailBody                      = $email->webinarignition_build_email( $email_data );

					$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

					try {
						if ( ! wp_mail( $member->user_email, $emailSubj, $emailBody, $headers ) ) {
							WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinarignition' ) . " {$member->email}", WebinarIgnition_Logs::LIVE_EMAIL );
						} elseif ( property_exists( $webinar_data, 'first_question_notification_sent' ) && ( 'no' === $webinar_data->first_question_notification_sent ) ) {
							$webinar_data->first_question_notification_sent = 'yes';
							update_option( 'webinarignition_campaign_' . $supportData['app_id'], $webinar_data );
							WebinarIgnition_Logs::add( __( 'Support request has been sent.', 'webinarignition' ), $supportData['app_id'], WebinarIgnition_Logs::LIVE_EMAIL );
						}
					} catch ( Exception $e ) {
						WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinarignition' ) . " {$member->user_email}", WebinarIgnition_Logs::LIVE_EMAIL );
					}
				} //end if
			} //end if
		} //end for
	} //end if

	if ( $send_question_notification && isset( $webinar_data->send_host_questions_notifications ) && ( 'yes' === $webinar_data->send_host_questions_notifications ) && isset( $webinar_data->host_questions_notifications_email ) ) {

		if ( filter_var( $webinar_data->host_questions_notifications_email, FILTER_VALIDATE_EMAIL ) ) {

			$qstn_notification_email_body   = $webinar_data->qstn_notification_email_body;
			$emailSubj                      = $webinar_data->qstn_notification_email_sbj;
			$support_link                   = $webinar_data->webinar_permalink . '/?console#/questions';

			$replacement            = array( $webinar_data->webinar_host, $supportData['name'], $webinar_data->webinar_desc, $support_link );
			$replace                = array( '{support}', '{attendee}', '{webinarTitle}', '{link}' );

			$email_data                     = new stdClass();
			$email_data->bodyContent        = str_replace( $replace, $replacement, $qstn_notification_email_body );
			$email_data->footerContent      = ( ! empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && ( 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ) ) ? $webinar_data->qstn_answer_email_footer : '';
			$email_data->email_subject      = $webinar_data->qstn_notification_email_sbj;
			$email                          = new WI_Emails();
			$emailBody                      = $email->webinarignition_build_email( $email_data );

			$headers                = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

			try {
				if ( ! wp_mail( $webinar_data->host_questions_notifications_email, $emailSubj, $emailBody, $headers ) ) {
					WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent', 'webinarignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
				}
			} catch ( Exception $e ) {
				WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent.', 'webinarignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
			}
		} //end if
	} //end if

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		restore_previous_locale();
	}
}

add_action( 'wp_ajax_webinarignition_delete_question', 'webinarignition_delete_question_callback' );
function webinarignition_delete_question_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	global $wpdb;
	$data = array(
		'ID' => $ID,
		'status' => 'deleted',
	);

	$result = WebinarignitionQA::webinarignition_create_question( $data );

	if ( $result ) {
		WebinarignitionQA::webinarignition_delete_answers( $ID );
		$message = __( 'Question successfully deleted', 'webinarignition' );
		wp_send_json_success(array(
			'success' => true,
			'message' => $message,
		));
	}
}

add_action( 'wp_ajax_webinarignition_delete_lead', 'webinarignition_delete_lead_callback' );
function webinarignition_delete_lead_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';
	$table_meta_db_name = $wpdb->prefix . 'webinarignition_leadmeta';

	if ( $wpdb->delete( $table_db_name, array( 'ID' => $ID ) ) ) {
		$message = 'lead ' . $ID . ' deleted';
		
		$wpdb->query( $wpdb->prepare( "DELETE FROM $table_meta_db_name WHERE lead_id = %d", $ID ) );
		wp_send_json_success(array(
			'success' => true,
			'message' => $message,
		));
	}
}

add_action( 'wp_ajax_webinarignition_delete_lead_auto', 'webinarignition_delete_lead_auto_callback' );
function webinarignition_delete_lead_auto_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
	$table_meta_db_name = $wpdb->prefix . 'webinarignition_lead_evergreenmeta';
	$wpdb->query( $wpdb->prepare( "DELETE FROM $table_db_name WHERE id = %d", $ID ) ); 
	$wpdb->query( $wpdb->prepare( "DELETE FROM $table_meta_db_name WHERE lead_id = %d", $ID ) ); 
}

add_action( 'wp_ajax_webinarignition_reset_stats', 'webinarignition_reset_stats_callback' );
function webinarignition_reset_stats_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';

	$wpdb->query( $wpdb->prepare(
		"UPDATE $table_db_name SET
		total_lp = %s,
		total_ty = %s,
		total_live = %s,
		total_replay = %s
		WHERE id = %d",
		'0%%0',
		'0%%0',
		'0%%0',
		'0%%0',
		$ID
	) );
}

// COUNTDOWN - EXPIRE -- UPDATE TO LIVE
add_action( 'wp_ajax_nopriv_webinarignition_update_to_live', 'webinarignition_update_to_live_callback' );
add_action( 'wp_ajax_webinarignition_update_to_live', 'webinarignition_update_to_live_callback' );
function webinarignition_update_to_live_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	$results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
	// update status
	$results->webinar_switch = 'live';
	// save
	update_option( 'webinarignition_campaign_' . $ID, $results );
}


add_action( 'wp_ajax_nopriv_webinarignition_get_master_switch_status', 'webinarignition_get_master_switch_status_callback' );
add_action( 'wp_ajax_webinarignition_get_master_switch_status', 'webinarignition_get_master_switch_status_callback' );

function webinarignition_get_master_switch_status_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

	wp_send_json(array(
		'webinar_switch_status' => $webinar_data->webinar_switch,
	));
}

// TRACK VIEW
add_action( 'wp_ajax_nopriv_webinarignition_track_view', 'webinarignition_track_view_callback' );
add_action( 'wp_ajax_webinarignition_track_view', 'webinarignition_track_view_callback' );
function webinarignition_track_view_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Campaign ID
	$ID   = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	$PAGE = sanitize_text_field( filter_input( INPUT_POST, 'page' ) );

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';
	$findstat      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_db_name WHERE id = %d", $ID ), OBJECT );

	if ( 'lp' === $PAGE ) {
		// LANDING PAGE
		$getData   = $findstat->total_lp;
		$getData   = explode( '%%', $getData );
		$getUnique = (int) $getData[0] + 1;
		$getTotal  = $getData[1];
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_lp = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} elseif ( 'ty' === $PAGE ) {
		// THANK YOU PAGE
		$getData   = $findstat->total_ty;
		$getData   = explode( '%%', $getData );
		$getUnique = (int) $getData[0] + 1;
		$getTotal  = $getData[1];
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_ty = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} elseif ( 'live' === $PAGE ) {
		// LIVE
		$getData   = $findstat->total_live;
		$getData   = explode( '%%', $getData );
		$getUnique = (int) $getData[0] + 1;
		$getTotal  = $getData[1];
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_live = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} elseif ( 'replay' === $PAGE ) {
		// REPLAY
		$getData   = $findstat->total_replay;
		$getData   = explode( '%%', $getData );
		$getUnique = (int) $getData[0] + 1;
		$getTotal  = $getData[1];
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_replay = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} //end if
}

// TRACK VIEW
add_action( 'wp_ajax_nopriv_webinarignition_track_view_total', 'webinarignition_track_view_total_callback' );
add_action( 'wp_ajax_webinarignition_track_view_total', 'webinarignition_track_view_total_callback' );
function webinarignition_track_view_total_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Campaign ID
	$ID   = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	$PAGE = sanitize_text_field( filter_input( INPUT_POST, 'page' ) );

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';
	$findstat      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_db_name WHERE id = %d", $ID ), OBJECT ); 

	if ( 'lp' === $PAGE ) {
		// LANDING PAGE
		$getData   = $findstat->total_lp;
		$getData   = explode( '%%', $getData );
		$getUnique = $getData[0];
		$current_visitors = $getData[1];
		$getTotal  = (int) $current_visitors + 1;
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_lp = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} elseif ( 'ty' === $PAGE ) {
		// THANK YOU PAGE
		$getData   = $findstat->total_ty;
		$getData   = explode( '%%', $getData );
		$getUnique = $getData[0];
		$current_visitors = $getData[1];
		$getTotal  = (int) $current_visitors + 1;
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_ty = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} elseif ( 'live' === $PAGE ) {
		// LIVE
		$getData   = $findstat->total_live;
		$getData   = explode( '%%', $getData );
		$getUnique = $getData[0];
		$current_visitors = $getData[1];
		$getTotal  = (int) $current_visitors + 1;
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_live = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} elseif ( 'replay' === $PAGE ) {
		// REPLAY
		$getData   = $findstat->total_replay;
		$getData   = explode( '%%', $getData );
		$getUnique = $getData[0];
		$current_visitors = $getData[1];
		$getTotal  = (int) $current_visitors + 1;
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_db_name SET total_replay = %s WHERE id = %d",
				$getUnique . '%%' . $getTotal,
				$ID
			)
		);
	} //end if
}

// TRACK LIVE ATTEND
add_action( 'wp_ajax_nopriv_webinarignition_update_view_status', 'webinarignition_update_view_status_callback' );
add_action( 'wp_ajax_webinarignition_update_view_status', 'webinarignition_update_view_status_callback' );
function webinarignition_update_view_status_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$lead_id      = sanitize_text_field( filter_input(INPUT_POST, 'lead_id', FILTER_UNSAFE_RAW) );
	$webinar_id   = absint( filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW) );
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

	$webinar_started = ( webinarignition_should_use_videojs( $webinar_data ) && isset( $_COOKIE[ "videoResumeTime-{$lead_id}" ] ) ) || ! webinarignition_should_use_videojs( $webinar_data );
	$updated = false;
	if ( ! empty( $lead_id ) && ! empty( $webinar_data ) && $webinar_started ) {
		$updated = webinarignition_update_webinar_lead_status( $webinar_data->webinar_date, $lead_id );
	}

	wp_send_json_success( array( 'message' => __( 'Data updated successfully', 'webinarignition' ) ) );
}

// GET QA -- NAME AND EMAIL
add_action( 'wp_ajax_nopriv_webinarignition_get_qa_name_email', 'webinarignition_get_qa_name_email_callback' );
add_action( 'wp_ajax_webinarignition_get_qa_name_email', 'webinarignition_get_qa_name_email_callback' );
function webinarignition_get_qa_name_email_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Get Variables
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';
	$cookieStatus  = sanitize_text_field( filter_input(INPUT_POST, 'cookie', FILTER_UNSAFE_RAW) );
	$IP            = sanitize_text_field( filter_input(INPUT_POST, 'ip', FILTER_UNSAFE_RAW) );

	if ( empty( $cookieStatus ) ) {
		// No Cookie Found -- Try IP

		// Prepare the query
		$query = $wpdb->prepare(
			"SELECT * FROM {$table_db_name} WHERE trk3 = %s",
			$IP
		);

		$data = $wpdb->get_row($query, OBJECT);
		if ( empty( $data ) ) { // TODO: Improve the codes.
			// No IP Found - Do Nothing...

		} else {
			// IP Found - GET NAME / EMAIL
			echo esc_attr( $data->name . '//' . $data->email . '//' . $data->ID );
		}
	} else {
		// Cookie Was Found - Get Info
		// Assuming $cookieStatus is an ID and should be an integer
		$id = intval($cookieStatus);

		// Prepare the query
		$query = $wpdb->prepare(
			"SELECT * FROM {$table_db_name} WHERE id = %d",
			$id
		);

		$data = $wpdb->get_row($query, OBJECT);
		if ( is_object( $data ) ) {
			echo esc_attr( $data->name . '//' . $data->email . '//' . $data->ID );
		} //end if
	}

	die();
}

// GET QA -- NAME AND EMAIL AUTO
add_action( 'wp_ajax_nopriv_webinarignition_get_qa_name_email2', 'webinarignition_get_qa_name_email2_callback' );
add_action( 'wp_ajax_webinarignition_get_qa_name_email2', 'webinarignition_get_qa_name_email2_callback' );
function webinarignition_get_qa_name_email2_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Get Variables
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
	$cookieStatus  = sanitize_text_field( filter_input(INPUT_POST, 'cookie', FILTER_UNSAFE_RAW) );

	if ( ! empty( $cookieStatus ) ) {
		$query = $wpdb->prepare(
			"SELECT * FROM $table_db_name WHERE id = %d",
			intval($cookieStatus)
		);
		$data = $wpdb->get_row($query, OBJECT);
	}

	if ( is_object( $data ) ) {
		echo esc_attr( $data->name . '//' . $data->email . '//' . $data->ID );
	}

	die();
}

// add_action('wp_ajax_nopriv_webinarignition_update_master_switch', 'webinarignition_update_master_switch_callback');
add_action( 'wp_ajax_webinarignition_update_master_switch', 'webinarignition_update_master_switch_callback' );
function webinarignition_update_master_switch_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID         = sanitize_text_field( filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW) );
	$status     = sanitize_text_field( filter_input(INPUT_POST, 'status', FILTER_UNSAFE_RAW) );

	// Return Option Object:
	$results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
	$results->webinar_switch = $status;

	update_option( 'webinarignition_campaign_' . $ID, $results );
}

// SAVE AIR MESSAGE
add_action( 'wp_ajax_nopriv_webinarignition_save_air', 'webinarignition_save_air_callback' );
add_action( 'wp_ajax_webinarignition_save_air', 'webinarignition_save_air_callback' );

function webinarignition_save_air_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$ID         = sanitize_text_field( filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW) );
	$toggle     = sanitize_text_field( filter_input(INPUT_POST, 'toggle', FILTER_UNSAFE_RAW) );
	$toggleAmelia = sanitize_text_field( filter_input(INPUT_POST, 'toggleAmelia', FILTER_UNSAFE_RAW) );
	$btncopy    = sanitize_text_field( filter_input(INPUT_POST, 'btncopy', FILTER_UNSAFE_RAW) );
	$btnurl     = sanitize_text_field( filter_input(INPUT_POST, 'btnurl', FILTER_UNSAFE_RAW) );
	$btncolor   = sanitize_text_field( filter_input(INPUT_POST, 'btncolor', FILTER_UNSAFE_RAW) );
	$airBroadcastMessageWidth = sanitize_text_field( filter_input(INPUT_POST, 'airBroadcastMessageWidth', FILTER_UNSAFE_RAW) );
	$airBroadcastMessageAlignment = sanitize_text_field( filter_input(INPUT_POST, 'airBroadcastMessageAlignment', FILTER_UNSAFE_RAW) );
	$air_html      = filter_input(INPUT_POST, 'html', FILTER_UNSAFE_RAW);

	// Return Option Object:
	$results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
	$results->air_toggle    = $toggle;
	$results->air_amelia_toggle = $toggleAmelia;
	$results->air_btn_copy  = $btncopy;
	$results->air_btn_url   = $btnurl;
	$results->air_btn_color = $btncolor;
	$results->air_broadcast_message_width = $airBroadcastMessageWidth;
	$results->live_webinar_ctas_alignment_radios = $airBroadcastMessageAlignment;
	$results->air_html      = $air_html;

	update_option( 'webinarignition_campaign_' . $ID, $results );
}

add_action( 'wp_ajax_nopriv_webinarignition_track_order', 'webinarignition_track_order_callback' );
add_action( 'wp_ajax_webinarignition_track_order', 'webinarignition_track_order_callback' );
function webinarignition_track_order_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	global $wpdb;
	$ID         = sanitize_text_field( filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW) );
	$lead       = sanitize_text_field( filter_input(INPUT_POST, 'lead', FILTER_UNSAFE_RAW) );

	if ( empty( $ID ) || empty( $lead ) ) {
		wp_send_json( 'invalid webinar or lead id' );
	}

	$webinarData = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
	if ( empty( $webinarData ) ) {
		wp_send_json( 'webinar not found: ' . $ID );
	}

	$table_db_name = webinarignition_is_auto( $webinarData ) ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads';

	$updated = $wpdb->update(
		$table_db_name,
		array( 'trk2' => 'Yes' ),
		array( 'id' => $wpdb->prepare( '%d', $lead ) ),
		array( '%s' ),
		array( '%d' )
	);

	if ( ! empty( $updated ) ) {
		do_action( 'webinarignition_lead_purchased', $lead, $ID );
	}

	wp_send_json( 'tracked lead' );
}

// Store New / Add Phone Number webinarignition_store_phone
add_action( 'wp_ajax_nopriv_webinarignition_store_phone', 'webinarignition_store_phone_callback' );
add_action( 'wp_ajax_webinarignition_store_phone', 'webinarignition_store_phone_callback' );
function webinarignition_store_phone_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Get Variables
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';

	$ID    = sanitize_text_field( filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW) );
	$PHONE = sanitize_text_field( filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW) );

	$ID = intval($ID); // Sanitize the ID to ensure it's an integer

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
		$ID
	);

	$lead = $wpdb->get_row($query, OBJECT);

	if ( empty( $lead ) ) {
		$sql = $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `hash_ID` = %d", $ID );
		$lead = $wpdb->get_row( $sql, OBJECT );
	}

	if ( ! empty( $lead ) ) {
		$ID = $lead->ID;
	}

	// Set Phone Number
	$wpdb->update(
		$table_db_name,
		array(
			'phone' => $PHONE,
		),
		array( 'id' => $ID )
	);
}

// Store New / Add Phone Number webinarignition_store_phone
add_action( 'wp_ajax_nopriv_webinarignition_store_phone_auto', 'webinarignition_store_phone_auto_callback' );
add_action( 'wp_ajax_webinarignition_store_phone_auto', 'webinarignition_store_phone_auto_callback' );
function webinarignition_store_phone_auto_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Get Variables
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';

	$ID    = sanitize_text_field( filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW) );
	$PHONE = sanitize_text_field( filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW) );

	$ID = intval($ID); // Sanitize the ID to ensure it's an integer

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
		$ID
	);

	$lead = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $ID), OBJECT);

	if ( empty( $lead ) ) {
		// Prepare and execute the query
		$query = $wpdb->prepare(
			"SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
			$ID
		);

		$lead = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $ID), OBJECT);
	}

	if ( ! empty( $lead ) ) {
		$ID = $lead->ID;
	}

	// Set Phone Number
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE `{$table_db_name}` SET `phone` = %s WHERE `id` = %d",
			$PHONE,
			$ID
		)
	);
}

// Get Timezone & Local Time For Users
add_action( 'wp_ajax_nopriv_webinarignition_get_local_tz', 'webinarignition_get_local_tz_callback' );
add_action( 'wp_ajax_webinarignition_get_local_tz', 'webinarignition_get_local_tz_callback' );
function webinarignition_get_local_tz_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Get Olson Time ::
	$timezone = sanitize_text_field( filter_input(INPUT_POST, 'tz', FILTER_UNSAFE_RAW) );

	$dtz           = new DateTimeZone( $timezone );
	$time_in_sofia = new DateTime( 'now', $dtz );
	$offset        = $dtz->getOffset( $time_in_sofia ) / 3600;

	echo "<i class='icon-globe' style='margin-right: 10px;' ></i> <b>UTC</b> :: " . ( $offset < 0 ? esc_attr( $offset ) : '+' . esc_attr( $offset ) ) . "<i class='icon-time' style='margin-left: 10px; margin-right:10px;' ></i><b>" . esc_html__( 'Local Time', 'webinarignition' ) . '</b> :: ' . esc_attr( date( 'g:i A' ) );
	die();
}

// Get Timezone & Local Time For Users
add_action( 'wp_ajax_nopriv_webinarignition_get_local_tz_set', 'webinarignition_get_local_tz_set_callback' );
add_action( 'wp_ajax_webinarignition_get_local_tz_set', 'webinarignition_get_local_tz_set_callback' );
function webinarignition_get_local_tz_set_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	// Get Olson Time ::
	$timezone = sanitize_text_field( filter_input( INPUT_POST, 'tz', FILTER_UNSAFE_RAW ) );
	$dtz           = new DateTimeZone( $timezone );
	$time_in_sofia = new DateTime( 'now', $dtz );
	$offset        = $dtz->getOffset( $time_in_sofia ) / 3600;

	$set = ( $offset < 0 ? $offset : '+' . $offset );
	// ReFormat UTC - GMT and half'rs
	if ( '+0' === $set ) {
		$set = '0';
	} elseif ( '-9.5' === $set ) {
		$set = '-930';
	} elseif ( '-4.5' === $set ) {
		$set = '-430';
	} elseif ( '+5.5' === $set ) {
		$set = '+530';
	} elseif ( '+5.75' === $set ) {
		$set = '+545';
	} elseif ( '+6.5' === $set ) {
		$set = '+630';
	} elseif ( '+9.5' === $set ) {
		$set = '+930';
	}

	echo esc_html( $set );
	die();
}

// UNLOCK
add_action( 'wp_ajax_nopriv_webinarignition_unlock', 'webinarignition_unlock_callback' );
add_action( 'wp_ajax_webinarignition_unlock', 'webinarignition_unlock_callback' );
function webinarignition_unlock_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$username    = sanitize_text_field( filter_input( INPUT_POST, 'username' ) );
	$license_key = sanitize_text_field( filter_input( INPUT_POST, 'key' ) );

	if ( ( 'dks' === $username ) && ( 'seropt4n0zxdfkv' === $license_key ) ) {
		WebinarignitionLicense::webinarignition_activate( $license_key );

		$return = array(
			'message' => __( 'Activation Successful', 'webinarignition' ),
			'status'  => 1,
			'success' => true,
		);

		wp_send_json_success( $return );
	}

	$dk_activation_url = WebinarignitionLicense::webinarignnition_get_activation_url() . "?username={$username}&key={$license_key}";

	$resp = wp_remote_get($dk_activation_url, array(
		'user-agent' => 'WI',
		'timeout'    => 60,
	));

	$resp = json_decode( $resp['body'] );

	if ( is_object( $resp ) && ( 'KeyFound' === $resp->result ) ) {
		WebinarignitionLicense::webinarignition_activate( $license_key, $resp );

		$return = array(
			'message' => __( 'Activation Successful', 'webinarignition' ),
			'status'  => 1,
			'success' => true,
		);

		wp_send_json_success( $return );
	} else {
		$return = array(
			'message' => $resp->result,
			'status'  => 1,
			'success' => false,
		);

		wp_send_json( $return );
	}
}

// Reh csv upload
// Add CSV Lead
add_action( 'wp_ajax_nopriv_reh_wi_handle_csv_upload', 'webinarignition_reh_wi_handle_csv_upload_callback' );
add_action( 'wp_ajax_reh_wi_handle_csv_upload', 'webinarignition_reh_wi_handle_csv_upload_callback' );
if ( ! function_exists( 'webinarignition_reh_wi_handle_csv_upload_callback' ) ) {
	// function webinarignition_reh_wi_handle_csv_upload_callback() {
	// 	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	// 	global $wpdb;
	// 	$app_id = (int) sanitize_text_field( $_POST['id'] );

	// 	$table_db_name = $wpdb->prefix . 'webinarignition_leads';

	// 	$csv_array = array();
	// 	if ( isset( $_FILES['csv_file'] ) ) {

	// 		if ( isset( $_FILES['csv_file']['tmp_name'] ) ) {

	// 			$whole_csv_file = $_FILES['csv_file']['tmp_name'];

	// 			$file_info = pathinfo( $whole_csv_file );

	// 			if ( isset( $_FILES['csv_file']['type'] ) && 'text/csv' !== $_FILES['csv_file']['type'] ) {
	// 				wp_send_json_error( esc_html_e( 'Invalid file format. Only CSV files are allowed.', 'webinarignition' ) );
	// 			}

	// 			$upload_dir = wp_upload_dir();
	// 			$target_dir = $upload_dir['basedir'] . '/csv-files/';

	// 			if ( ! is_dir( $target_dir ) ) {
	// 				wp_mkdir_p( $target_dir );
	// 			}

	// 			$target_filename = uniqid( 'csv_', true ) . '.txt';
	// 			$target_path     = $target_dir . $target_filename;
	// 			$webinar_data    = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );

	// 			$time_format    = $webinar_data->time_format;
	// 			$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';

	// 			if ( move_uploaded_file( $whole_csv_file, $target_path ) ) {
	// 				$csv_data = file_get_contents( $target_path );
	// 				$lines = explode( "\n", $csv_data );
	// 				$csv_array = array();
	// 				$current_new_user_id = false;

	// 				foreach ( $lines as $line ) {
	// 					$row = str_getcsv( $line );

	// 					$csv_array[] = $row;
	// 					$name  = trim( $row[0] );
	// 					$email = trim( $row[1] );
	// 					$phone = trim( $row[2] );

	// 					if ( ( empty( str_replace( ' ', '', $name ) ) && empty( str_replace( ' ', '', $email ) ) ) || ( 'name' === strtolower( str_replace( ' ', '', $name ) ) ) ) {
	// 						continue;
	// 					}

	// 					$lead = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $table_db_name WHERE email = %s AND app_id = %d", $email, $app_id ) );

	// 					if ( $lead ) {
	// 						$current_new_user_id = $lead->ID;
	// 					} else {
	// 						$wpdb->insert($table_db_name, array(
	// 							'app_id'  => sanitize_text_field( $app_id ),
	// 							'name'    => sanitize_text_field( $name ),
	// 							'email'   => sanitize_email( $email ),
	// 							'phone'   => sanitize_text_field( $phone ),
	// 							'trk1'    => 'import',
	// 							'trk3'    => '-',
	// 							'event'   => 'No',
	// 							'replay'  => 'No',
	// 							'created' => gmdate( 'F j, Y' ),
	// 						));

	// 						$new_lead_id = $wpdb->insert_id;
	// 						$hash_ID     = sha1( $app_id . $email . $new_lead_id );

	// 						$wpdb->update(
	// 							$table_db_name,
	// 							array( 'hash_ID' => $hash_ID ),
	// 							array( 'ID' => $new_lead_id )
	// 						);

	// 						if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( 'yes' === $webinar_data->use_new_email_signup_template ) ) ) {
	// 							// Use new templates
	// 							$webinar_data->emailheading     = $webinar_data->email_signup_heading;
	// 							$webinar_data->emailpreview     = $webinar_data->email_signup_preview;
	// 							$webinar_data->bodyContent      = $webinar_data->email_signup_body;
	// 							$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ) ? $webinar_data->local_email_signup_footer : '';

	// 							$wi_emails  = new WI_Emails();
	// 							$emailBody  = $wi_emails->webinarignition_build_email( $webinar_data );
	// 						} else {
	// 							// This is an old webinar, created before this version
	// 							$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
	// 							$emailBody = $emailHead;
	// 							$emailBody .= $webinar_data->email_signup_body;
	// 							$emailBody .= '</html>';
	// 						}

	// 						$emailBody = str_replace( '{LEAD_NAME}', ( ! empty( $name ) ? sanitize_text_field( $name ) : '' ), $emailBody );
	// 						$emailBody = str_replace( '{FIRSTNAME}', ( ! empty( $name ) ? sanitize_text_field( $name ) : '' ), $emailBody );

	// 						$localized_date = webinarignition_get_localized_date( $webinar_data );

	// 						$timeonly  = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
	// 						// Replace
	// 						$emailBody = str_replace( '{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz( $webinar_data->webinar_start_time, $time_format, $webinar_data->webinar_timezone, false, $timeonly ), $emailBody );

	// 						$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $new_lead_id, $emailBody );

	// 						$email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
	// 						$headers          = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

	// 						webinarignition_test_smtp_options();

	// 						try {
	// 							if ( ! wp_mail( $email, $email_signup_sbj, $emailBody, $headers ) ) {
	// 								WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL );
	// 							} else {
	// 								WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinarignition' ), $new_lead_id, WebinarIgnition_Logs::LIVE_EMAIL );
	// 							}
	// 						} catch ( Exception $e ) {
	// 							WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL );
	// 						}
	// 					} //end if
	// 				} //end foreach
	// 			} else {
	// 				wp_send_json_error( esc_html_e( 'Failed to save the CSV file.', 'webinarignition' ) );
	// 			} //end if

	// 			if ( file_exists( $target_path ) ) {
	// 				wp_delete_file( $target_path );
	// 			}

	// 			wp_send_json(array(
	// 				'status' => true,
	// 				'data' => $csv_array,
	// 			));
	// 		} //end if
	// 	} //end if

	// 	wp_send_json( array( 'status' => false ) );
	// }
	function webinarignition_reh_wi_handle_csv_upload_callback() {
		check_ajax_referer('webinarignition_ajax_nonce', 'security');
	
		global $wpdb;
		$app_id = (int) sanitize_text_field($_POST['id']);
		$table_db_name = $wpdb->prefix . 'webinarignition_leads';
		$csv_array = array();
	
		if (isset($_FILES['csv_file'])) {
			// Use wp_handle_upload to handle the file upload
			$uploadedfile = $_FILES['csv_file'];
			$upload_overrides = array('test_form' => false); // Disable form validation
	
			// Handle the upload
			$movefile = wp_handle_upload($uploadedfile, $upload_overrides);
	
			if ($movefile && !isset($movefile['error'])) {
				$target_path = $movefile['file']; // Get the path of the uploaded file
				$csv_data = file_get_contents($target_path);
				$lines = explode("\n", $csv_data);
				$csv_array = array();
				$current_new_user_id = false;
	
				foreach ($lines as $line) {
					$row = str_getcsv($line);
					$csv_array[] = $row;
					$name  = trim($row[0]);
					$email = trim($row[1]);
					$phone = trim($row[2]);
	
					if (empty(str_replace(' ', '', $name)) && empty(str_replace(' ', '', $email)) || 'name' === strtolower(str_replace(' ', '', $name))) {
						continue;
					}
	
					$lead = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $table_db_name WHERE email = %s AND app_id = %d", $email, $app_id));
	
					if ($lead) {
						$current_new_user_id = $lead->ID;
					} else {
						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO $table_db_name
								(app_id, name, email, phone, trk1, trk3, event, replay, created)
								VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s)",
								$app_id,
								sanitize_text_field($name),
								sanitize_email($email),
								sanitize_text_field($phone),
								'import',
								'-',
								'No',
								'No',
								gmdate('F j, Y')
							)
						);
						$new_lead_id = $wpdb->insert_id;
						$hash_ID     = sha1($app_id . $email . $new_lead_id);
	
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table_db_name SET hash_ID = %s WHERE ID = %d",
								$hash_ID,
								$new_lead_id
							)
						);
	
						// Email handling logic
						$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data($app_id);
						if (!empty($webinar_data->templates_version) || (!empty($webinar_data->use_new_email_signup_template) && ('yes' === $webinar_data->use_new_email_signup_template))) {
							// Use new templates
							$webinar_data->emailheading     = $webinar_data->email_signup_heading;
							$webinar_data->emailpreview     = $webinar_data->email_signup_preview;
							$webinar_data->bodyContent      = $webinar_data->email_signup_body;
							$webinar_data->footerContent    = (property_exists($webinar_data, 'show_or_hide_local_email_signup_footer') && 'show' === $webinar_data->show_or_hide_local_email_signup_footer) ? $webinar_data->local_email_signup_footer : '';
	
							$wi_emails  = new WI_Emails();
							$emailBody  = $wi_emails->webinarignition_build_email($webinar_data);
						} else {
							// This is an old webinar, created before this version
							$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
							$emailBody = $emailHead;
							$emailBody .= $webinar_data->email_signup_body;
							$emailBody .= '</html>';
						}
	
						$emailBody = str_replace('{LEAD_NAME}', (!empty($name) ? sanitize_text_field($name) : ''), $emailBody);
						$emailBody = str_replace('{FIRSTNAME}', (!empty($name) ? sanitize_text_field($name) : ''), $emailBody);
	
						$localized_date = webinarignition_get_localized_date($webinar_data);
	
						$timeonly  = (empty($webinar_data->display_tz) || (!empty($webinar_data->display_tz) && ('yes' === $webinar_data->display_tz))) ? false : true;
						// Replace
						$emailBody = str_replace('{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz($webinar_data->webinar_start_time, $time_format, $webinar_data->webinar_timezone, false, $timeonly), $emailBody);
	
						$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders($webinar_data, $new_lead_id, $emailBody);
	
						$email_signup_sbj = str_replace('{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj);
						$headers          = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option('webinarignition_email_templates_from_name', get_option('blogname')) . ' <' . get_option('webinarignition_email_templates_from_email', get_option('admin_email')) . '>');
	
						webinarignition_test_smtp_options();
	
						try {
							if (!wp_mail($email, $email_signup_sbj, $emailBody, $headers)) {
								WebinarIgnition_Logs::add(__('Registration email could not be sent to', 'webinarignition') . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL);
							} else {
								WebinarIgnition_Logs::add(__('Registration email has been sent.', 'webinarignition'), $new_lead_id, WebinarIgnition_Logs::LIVE_EMAIL);
							}
						} catch (Exception $e) {
							WebinarIgnition_Logs::add(__('Registration email could not be sent to', 'webinarignition') . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL);
						}
					} //end if
				} //end foreach
	
				// Optionally delete the uploaded file
				if (file_exists($target_path)) {
					wp_delete_file($target_path);
				}
	
				wp_send_json(array(
					'status' => true,
					'data' => $csv_array,
				));
			} else {
				wp_send_json_error(esc_html_e('Failed to save the CSV file.', 'webinarignition'));
			}
		} // end if
	
		wp_send_json(array('status' => false));
	}	
} //end if

// Add CSV Lead
add_action( 'wp_ajax_nopriv_webinarignition_import_csv_leads', 'webinarignition_import_csv_leads_callback' );
add_action( 'wp_ajax_webinarignition_import_csv_leads', 'webinarignition_import_csv_leads_callback' );
function webinarignition_import_csv_leads_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$post_input = array(
		'id' => filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ),
		'csv' => filter_input( INPUT_POST, 'csv', FILTER_UNSAFE_RAW ),
	);
	global $wpdb;
	$app_id = (int) sanitize_text_field( $post_input['id'] );
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );

	$time_format    = $webinar_data->time_format;
	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';

	$lines = explode( PHP_EOL, $post_input['csv'] );
	$leads = array();
	foreach ( $lines as $line ) {
		$leads[] = str_getcsv( $line );
	}

	$table_db_name = $wpdb->prefix . 'webinarignition_leads';

	foreach ( $leads as $key => $lead ) {

		$name  = trim( $lead[0] );
		$email = trim( $lead[1] );
		$phone = trim( $lead[2] );

		if ( 'email' === strtolower( $email ) ) {
			continue;
		}

		$lead = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $table_db_name WHERE email = %s AND app_id = %d", $email, $app_id ) );

		if ( $lead ) {
			echo esc_attr( $lead->ID );
		} else {
			$wpdb->prepare(
				"INSERT INTO $table_db_name
				(app_id, name, email, phone, trk1, trk3, event, replay, created)
				VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s)",
				intval($app_id),
				sanitize_text_field($name),
				sanitize_email($email),
				sanitize_text_field($phone),
				'import',
				'-',
				'No',
				'No',
				gmdate('F j, Y')
			);
			$wpdb->query($wpdb->last_query);

			$new_lead_id = $wpdb->insert_id;
			$hash_ID     = sha1( $app_id . $email . $new_lead_id );

			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $table_db_name SET hash_ID = %s WHERE ID = %d",
					$hash_ID,
					$new_lead_id
				)
			);

			if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( 'yes' === $webinar_data->use_new_email_signup_template ) ) ) {
				// Use new templates
				$webinar_data->emailheading     = $webinar_data->email_signup_heading;
				$webinar_data->emailpreview     = $webinar_data->email_signup_preview;
				$webinar_data->bodyContent      = $webinar_data->email_signup_body;
				$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ) ? $webinar_data->local_email_signup_footer : '';

				$wi_emails  = new WI_Emails();
				$emailBody  = $wi_emails->webinarignition_build_email( $webinar_data );
			} else {
				// This is an old webinar, created before this version
				$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
				$emailBody = $emailHead;
				$emailBody .= $webinar_data->email_signup_body;
				$emailBody .= '</html>';
			}

			$emailBody = str_replace( '{LEAD_NAME}', ( ! empty( $name ) ? sanitize_text_field( $name ) : '' ), $emailBody );
			$emailBody = str_replace( '{FIRSTNAME}', ( ! empty( $name ) ? sanitize_text_field( $name ) : '' ), $emailBody );

			$localized_date = webinarignition_get_localized_date( $webinar_data );

			$timeonly  = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
			// Replace
			$emailBody = str_replace( '{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz( $webinar_data->webinar_start_time, $time_format, $webinar_data->webinar_timezone, false, $timeonly ), $emailBody );

			$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $new_lead_id, $emailBody );

			$email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );

			$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

			webinarignition_test_smtp_options();

			try {
				if ( ! wp_mail( $email, $email_signup_sbj, $emailBody, $headers ) ) {
					WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL );
				} else {
					WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinarignition' ), $new_lead_id, WebinarIgnition_Logs::LIVE_EMAIL );
				}
			} catch ( Exception $e ) {
				WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinarignition' ) . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL );
			}
		} //end if
	} //end foreach
	die();
}

add_action( 'wp_ajax_nopriv_wi_show_logs_get', 'webinarignition_ajax_show_logs' );
add_action( 'wp_ajax_wi_show_logs_get', 'webinarignition_ajax_show_logs' );
function webinarignition_ajax_show_logs() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$campaign_id = sanitize_text_field( filter_input( INPUT_POST, 'campaign_id' ) );
	$page = sanitize_text_field( filter_input( INPUT_POST, 'page' ) );

	$webinar = WebinarignitionManager::webinarignition_get_webinar_data( $campaign_id );

	$log_types = array( WebinarIgnition_Logs::LIVE_EMAIL, WebinarIgnition_Logs::LIVE_SMS );
	if ( 'AUTO' === $webinar->webinar_date ) {
		$log_types                 = array( WebinarIgnition_Logs::AUTO_EMAIL, WebinarIgnition_Logs::AUTO_SMS );
		$webinar->webinar_timezone = false;
	}

	webinarignition_show_logs( $webinar->id, $log_types, $page, $webinar->timezone );
	die();
}

add_action( 'wp_ajax_nopriv_wi_delete_logs', 'webinarignition_ajax_delete_logs' );
add_action( 'wp_ajax_wi_delete_logs', 'webinarignition_ajax_delete_logs' );
function webinarignition_ajax_delete_logs() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$campaign_id = sanitize_text_field( filter_input( INPUT_POST, 'campaign_id' ) );

	$logs = WebinarIgnition_Logs::webinarignition_deleteCampaignLogs( $campaign_id );

	return $logs;
}

function webinarignition_show_logs( $id, $log_types, $page, $timezone = false ) {
	$logs = WebinarIgnition_Logs::webinarignition_getLogs( $id, $log_types, $page, $timezone );
	?>
	<table>
		<tr>
			<th>Date</th>
			<th>Message</th>
		</tr>
		<?php foreach ( $logs as $log ) { ?>
			<tr>
				<td><?php echo esc_html( $log->date ); ?></td>
				<td><?php echo nl2br( esc_attr( $log->message ) ); ?></td>
			</tr>
		<?php } ?>
	</table>
	<?php WebinarIgnition_Logs::webinarignition_pagination( $id ); ?>
	<?php
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		die();
	}
}

add_action( 'wp_ajax_nopriv_webinarignition_broadcast_msg_poll_callback', 'webinarignition_broadcast_msg_poll_callback' );
add_action( 'wp_ajax_webinarignition_broadcast_msg_poll_callback', 'webinarignition_broadcast_msg_poll_callback' );
function webinarignition_broadcast_msg_poll_callback() {
	// ! TODO: Use nonce verification if possible.
	if ( !isset($_GET['security']) || !wp_verify_nonce($_GET['security'], 'webinarignition_ajax_nonce') ) {
		wp_send_json_error( array('message' => 'Invalid nonce') );
		wp_die(); // terminate the script if nonce is invalid
	}
	$ID        = sanitize_text_field( $_GET['id'] );
	$IP        = sanitize_text_field( $_GET['ip'] );
	$LEAD_ID   = isset($_GET['lead_id']) ? sanitize_text_field( $_GET['lead_id'] ) : '';

	// Count User As Online -- User Tracking...
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_users_online';
	// Sanitize input values
	$ID = intval($ID);
	$IP = sanitize_text_field($IP); // Assuming $IP is a text field. Use appropriate sanitization if it's an IP address.
	$LEAD_ID = intval($LEAD_ID);

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `ip` = %s AND `lead_id` = %d",
		$ID,
		$IP,
		$LEAD_ID
	);

	$lookUpIP = $wpdb->get_row($wpdb->prepare(
		"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `ip` = %s AND `lead_id` = %d",
		$ID,
		$IP,
		$LEAD_ID
	), OBJECT);
	if ( empty( $lookUpIP ) ) {
		// Not Found -- Add Users
		$wpdb->query( $wpdb->prepare(
			"INSERT INTO $table_db_name (app_id, ip, lead_id, dt) VALUES (%d, %s, %d, %s)",
			$ID,
			$IP,
			$LEAD_ID,
			date( 'Y-m-d H:i:s' )
		) );
	} else {
		// Found -- Update Time
		$wpdb->query( $wpdb->prepare(
			"UPDATE $table_db_name SET dt = %s WHERE id = %d",
			date( 'Y-m-d H:i:s' ),
			$lookUpIP->ID
		) );
	}
	// Purge All Who Havent been updated in 5 minutes...
	// $currentTime = date("Y-m-d H:i:s");
	// $currentTime = strtotime($currentTime);
	// $minus5Minutes = date("Y-m-d H:i:s", strtotime('-5 minutes', $currentTime));
	// $wpdb->query("DELETE FROM $table_db_name WHERE dt < '$minus5Minutes' ");
	// Return Option Object:
	$results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

	// Check If Message is ON, if not, do nothing...
	if ( ! property_exists( $results, 'air_toggle' ) || empty( $results->air_toggle ) || 'off' === $results->air_toggle ) {
		// Air Message Not On
		wp_send_json(array(
			'air_toggle' => 'OFF',
			'hash' => '',
		));
	} else {
		// Air Message On, show Message::
		$showHTML = $results->air_html;
		$showHTML = str_replace( '<!DOCTYPE html><html><head></head><body>', '', $showHTML );
		$showHTML = str_replace( '</body></html>', '', $showHTML );
		$showHTML = stripcslashes( wpautop( $showHTML ) );
		$bg_color = empty( $results->air_btn_color ) ? '#6BBA40' : $results->air_btn_color;
		
		if ( ! property_exists( $results, 'air_amelia_toggle' ) || empty( $results->air_amelia_toggle ) || 'off' === $results->air_amelia_toggle ) {
			$air_amelia_toggle = 'off';
		} else {
			$air_amelia_toggle = 'on';
		}

		// Iframe should not work if amelia shortcodes option is disabled
		$air_broadcast_message_width = isset( $results->air_broadcast_message_width ) ? $results->air_broadcast_message_width : '';
		$live_webinar_ctas_alignment_radios = isset( $results->live_webinar_ctas_alignment_radios ) ? $results->live_webinar_ctas_alignment_radios : '';
		$hash = wp_hash( $showHTML . $air_amelia_toggle . $air_broadcast_message_width . $live_webinar_ctas_alignment_radios .$bg_color .$results->air_btn_url .$results->air_btn_copy);

		if ( 'off' !== $air_amelia_toggle && class_exists( 'advancediFrame' ) ) {
			$advance_iframe_sc = $showHTML . webinarignition_get_cta_aiframe_sc( $ID, '3', '' );
			$showHTML = apply_filters( 'ai_handle_temp_pages', $advance_iframe_sc );
		}

		wp_send_json(array(
			'air_toggle' => 'ON',
			'button_color' => $bg_color,
			'button_url' => $results->air_btn_url,
			'button_text' => $results->air_btn_copy,
			'response' => do_shortcode( $showHTML ),
			'hash' => $hash,
			'air_amelia_toggle' => $air_amelia_toggle,
			'box_width' => isset( $results->air_broadcast_message_width ) ? $results->air_broadcast_message_width : null,
			'box_alignment' => isset( $results->live_webinar_ctas_alignment_radios ) ? $results->live_webinar_ctas_alignment_radios : null,
		));
	} //end if
	die();
}

add_action( 'wp_ajax_nopriv_webinarignition_delete_smtp_updated_status', 'webinarignition_delete_smtp_updated_status' );
add_action( 'wp_ajax_webinarignition_delete_smtp_updated_status', 'webinarignition_delete_smtp_updated_status' );
function webinarignition_delete_smtp_updated_status() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$option_deleted = delete_option( 'webinarignition_upgraded_smtp' );
	wp_send_json( array( 'result' => $option_deleted ) );
}


add_action( 'admin_notices', 'webinarignition_smtp_credentials_failed_notice' );
function webinarignition_smtp_credentials_failed_notice() {
	$webinarignition_smtp_credentials_failed     = get_option( 'webinarignition_smtp_credentials_failed' );

	if ( 1 === $webinarignition_smtp_credentials_failed ) { ?>
		<div id="webinarignition-smtp-failed-notice" class="notice notice-warning is-dismissible">
			<p><?php esc_html_e( 'Your WebinarIgnition SMTP settings failed in the last attempt to use them. Webinarignition will not try using them from now on.', 'webinarignition' ); ?></p>
		</div>
		<?php
	}
}


add_action( 'wp_ajax_nopriv_webinarignition_delete_smtp_failed_notice', 'webinarignition_delete_smtp_failed_notice' );
add_action( 'wp_ajax_webinarignition_delete_smtp_failed_notice', 'webinarignition_delete_smtp_failed_notice' );
function webinarignition_delete_smtp_failed_notice() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$option_deleted = delete_option( 'webinarignition_smtp_credentials_failed' );
	wp_send_json( array( 'result' => $option_deleted ) );
}

add_action( 'wp_ajax_nopriv_webinarignition_get_support_users', 'webinarignition_get_support_users' );
add_action( 'wp_ajax_webinarignition_get_support_users', 'webinarignition_get_support_users' );
function webinarignition_get_support_users() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$users = get_users();

	wp_send_json_success( $users );
}

add_action( 'wp_ajax_nopriv_webinarignition_check_if_q_and_a_enabled', 'webinarignition_check_if_q_and_a_enabled' );
add_action( 'wp_ajax_webinarignition_check_if_q_and_a_enabled', 'webinarignition_check_if_q_and_a_enabled' );


function webinarignition_check_if_q_and_a_enabled() {
	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'webinarignition_ajax_nonce') ) {
		wp_send_json_error( array('message' => 'Invalid nonce') );
		wp_die(); // terminate the script if nonce is invalid
	}
	$webinar_id = $_POST['webinar_id'];
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

	if ( isset( $webinar_data->enable_qa ) && ( 'yes' !== $webinar_data->enable_qa ) ) {
		return wp_send_json_success( array( 'enable_qa' => 'no' ) );
	}

	wp_send_json_success( array( 'enable_qa' => 'yes' ) );
}

add_action( 'wp_ajax_nopriv_webinarignition_set_q_a_status', 'webinarignition_set_q_a_status' );
add_action( 'wp_ajax_webinarignition_set_q_a_status', 'webinarignition_set_q_a_status' );


function webinarignition_set_q_a_status() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$webinar_id = filter_input(INPUT_POST, 'webinarId', FILTER_SANITIZE_NUMBER_INT);
	$status = filter_input(INPUT_POST, 'status', FILTER_UNSAFE_RAW);

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data($webinar_id);

	if ( 'hide' === $status ) {
		$webinar_data->enable_qa = 'no';

		update_option('webinarignition_campaign_' . $webinar_id, $webinar_data);
		wp_send_json_success(array(
			'webinar_qa' => '1849',
			'status' => $webinar_data->enable_qa,
		));
	} else {
		$webinar_data->enable_qa = 'yes';

		update_option('webinarignition_campaign_' . $webinar_id, $webinar_data);

		wp_send_json_success(array(
			'webinar_qa' => '1853',
			'status' => $webinar_data->enable_qa,
		));
	}
}



add_action( 'wp_ajax_nopriv_webinarignition_answer_attendee_question', 'webinarignition_answer_attendee_question' );
add_action( 'wp_ajax_webinarignition_answer_attendee_question', 'webinarignition_answer_attendee_question' );


function webinarignition_answer_attendee_question() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$webinarId = filter_input(INPUT_POST, 'webinarId', FILTER_SANITIZE_NUMBER_INT);
	$attendeeEmail = filter_var(filter_input(INPUT_POST, 'attendeeEmail', FILTER_SANITIZE_EMAIL), FILTER_SANITIZE_EMAIL);
	$emailAnswer = filter_input(INPUT_POST, 'answer', FILTER_UNSAFE_RAW);
	$attendeeQuestion = filter_input(INPUT_POST, 'attendeeQuestion', FILTER_UNSAFE_RAW);
	$subject = sanitize_text_field(filter_input(INPUT_POST, 'subject'));
	$answerText = filter_input(INPUT_POST, 'answerText', FILTER_UNSAFE_RAW);
	$questionId = sanitize_text_field(filter_input(INPUT_POST, 'questionId'));
	$supportId = sanitize_text_field(filter_input(INPUT_POST, 'supportId'));
	$supportName = sanitize_text_field(filter_input(INPUT_POST, 'supportName'));
	$emailQAEnabled = filter_input(INPUT_POST, 'emailQAEnabled', FILTER_UNSAFE_RAW);

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_questions';

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data($webinarId);

	$result = $wpdb->update(
		$table_db_name,
		array(
			'status' => 'done',
			'attr2' => $supportId,
			'attr3' => $supportName,
			'attr4' => '',
			'attr5' => '',
			'answer' => $emailAnswer,
			'answer_text' => $answerText,
		),
		array( 'id' => $questionId )
	);

	$parent = WebinarignitionQA::webinarigntion_get_question($questionId);

	if (!empty($parent)) {
		unset($parent['ID']);

		$parent['type'] = 'answer';
		$parent['status'] = 'answer';
		$parent['created'] = current_time('mysql');
		$parent['parent_id'] = $questionId;

		$answer_id = WebinarignitionQA::webinarignition_create_question($parent);
	}

	if (empty($emailQAEnabled) || 'off' !== $emailQAEnabled) {
		$email_data                     = new stdClass();
		$email_data->bodyContent        = $emailAnswer;
		$email_data->email_subject      = $subject;
		$email_data->footerContent      = (!empty($webinar_data->show_or_hide_local_qstn_answer_email_footer) && ('show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer)) ? $webinar_data->qstn_answer_email_footer : '';

		if (!empty($webinar_data->show_or_hide_local_qstn_answer_email_footer) && ('show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer)) {
			$email_data->footerContent          = str_replace('{YEAR}', gmdate('Y'), $email_data->footerContent);
		}

		$email_data->emailheading       = $subject;
		$email_data->emailpreview       = $subject;

		$email                          = new WI_Emails();
		$emailBody                      = $email->webinarignition_build_email($email_data);
		$headers            = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option('webinarignition_email_templates_from_name', get_option('blogname')) . ' <' . get_option('webinarignition_email_templates_from_email', get_option('admin_email')) . '>');

		if (!wp_mail($attendeeEmail, $subject, $emailBody, $headers)) {
			WebinarIgnition_Logs::add(__('Support answer email could not be sent to', 'webinarignition') . " {$attendeeEmail}", WebinarIgnition_Logs::LIVE_EMAIL);
		}
	} //end if

	wp_send_json_success();
}

add_action( 'wp_ajax_nopriv_webinarignition_hold_or_release_console_question', 'webinarignition_hold_or_release_console_question' );
add_action( 'wp_ajax_webinarignition_hold_or_release_console_question', 'webinarignition_hold_or_release_console_question' );


function webinarignition_hold_or_release_console_question() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$questionId         = sanitize_text_field( filter_input( INPUT_POST, 'questionId' ) );
	$supportName        = sanitize_text_field( filter_input( INPUT_POST, 'supportName' ) );
	$webinarId          = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );
	$supportId          = sanitize_text_field( filter_input( INPUT_POST, 'supportId' ) );

	global $wpdb;
	$table_db_name      = $wpdb->prefix . 'webinarignition_questions';

	// Release other questions first
	// Sanitize the input value
	$supportId = intval($supportId); // Assuming $supportId is an integer

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM `{$table_db_name}` WHERE `attr2` = %d",
		$supportId
	);

	$questions = $wpdb->get_results($query, ARRAY_A);
	foreach ( $questions as $question ) {
		if ( 'hod' === $question['attr4'] ) {
			$wpdb->update(
				$table_db_name,
				array(
					'attr2' => '',
					'attr3' => '',
					'attr4' => '',
					'attr5' => '',
				),
				array( 'ID' => $question['ID'] ),
			);
		}
	}

	if ( wp_validate_boolean( filter_input( INPUT_POST, 'hold' ) ) ) {
		$wpdb->update(
			$table_db_name,
			array(
				'attr2' => $supportId,
				'attr3' => $supportName,
				'attr4' => 'hold',
				'attr5' => $supportName,
			),
			array( 'id' => $questionId ),
		);
	} else {
		$wpdb->update(
			$table_db_name,
			array(
				'attr2' => '',
				'attr3' => '',
				'attr4' => '',
				'attr5' => '',
			),
			array( 'id' => $questionId ),
		);
	} //end if

	wp_send_json_success();
}

add_action( 'wp_ajax_nopriv_webinarignition_release_unanswered_questions', 'webinarignition_release_unanswered_questions' );
add_action( 'wp_ajax_webinarignition_release_unanswered_questions', 'webinarignition_release_unanswered_questions' );


function webinarignition_release_unanswered_questions() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$webinarId = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );
	$supportId = sanitize_text_field( filter_input( INPUT_POST, 'supportId' ) );

	// Sanitize the input values
	$webinarId = intval($webinarId); // Assuming $webinarId is an integer
	$supportId = intval($supportId); // Assuming $supportId is an integer

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_questions';

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `attr2` = %d",
		$webinarId,
		$supportId
	);

	$questions = $wpdb->get_results($query, ARRAY_A);
	foreach ( $questions as $question ) {
		if ( 'hold' === $question->attr4 ) {
			$wpdb->update(
				$table_db_name,
				array(
					'attr2' => '',
					'attr3' => '',
					'attr4' => '',
					'attr5' => '',
				),
				array( 'ID' => $question->ID ),
			);
		}
	}

	wp_send_json_success();
}


add_action( 'wp_ajax_nopriv_webinarignition_get_answer_template', 'webinarignition_get_answer_template' );
add_action( 'wp_ajax_webinarignition_get_answer_template', 'webinarignition_get_answer_template' );


function webinarignition_get_answer_template() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$webinarId = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );

	$webinar_data       = WebinarignitionManager::webinarignition_get_webinar_data( $webinarId );
	$emailBody          = $webinar_data->qstn_answer_email_body;

	$return = array(
		'template' => $emailBody,
	);

	wp_send_json_success( $return );
}


add_action( 'wp_ajax_webinarignition_send_test_email', 'webinarignition_send_test_email_callback' );
function webinarignition_send_test_email_callback() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );

	$required_fields = array(
		'subject',
		'showLocalFooter',
		'emailheadingval',
		'emailpreviewval',
		'bodyContent',
		'footerContent',
		'webinarid',
		'templates_version',
		'use_new_template',
	);

	$post_input = array();
	foreach ( $required_fields as $field ) {
		$post_input[$field] = filter_input( INPUT_POST, $field );
	}

	$email_data                   = new stdClass();
	$email_data->email_subject    = $post_input['subject'];
	$email_data->showLocalFooter  = $post_input['showLocalFooter'];
	$email_data->emailheading     = $post_input['emailheadingval'];
	$email_data->emailpreview     = $post_input['emailpreviewval'];
	$email_data->bodyContent      = $post_input['bodyContent'];
	$email_data->footerContent    = $post_input['footerContent'];
	$email_data->webinarid        = $post_input['webinarid'];

	$email_data->templates_version      = isset( $post_input['templates_version'] ) ? $post_input['templates_version'] : '';
	$email_data->use_new_template       = isset( $post_input['use_new_template'] ) ? $post_input['use_new_template'] : '';

	if ( ( 'yes' === $email_data->use_new_template ) || ! empty( $email_data->templates_version ) ) {
		$email      = new WI_Emails();
		$emailBody  = $email->webinarignition_build_email( $email_data );
	} else {
		$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
		$emailBody = $emailHead;
		$emailBody .= $email_data->bodyContent;
	}

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['webinarid'] );

	$headers    = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );
	$response   = array();

	if ( ! wp_mail( $post_input['email'], $post_input['subject'], $emailBody, $headers ) ) {
		$response['status']  = 0;
		$response['message'] = __( 'Sorry; email could not be sent.', 'webinarignition' );
	} else {
		$response['status']  = 1;
		$response['message'] = __( 'Email was successfully sent.', 'webinarignition' );
	}

	echo wp_json_encode( $response );

	die;
}

add_action( 'wp_ajax_nopriv_webinarignition_update_webinar_status', 'webinarignition_update_webinar_status' );
add_action( 'wp_ajax_webinarignition_update_webinar_status', 'webinarignition_update_webinar_status' );

function webinarignition_update_webinar_status() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$webinarId = filter_input( INPUT_POST, 'webinarId' );
	$webinar_switch = filter_input( INPUT_POST, 'webinar_switch' );
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinarId );
	$webinar_data->webinar_switch = $webinar_switch;
	update_option( 'webinarignition_campaign_' . $webinarId, $webinar_data );

	wp_send_json_success();
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_get_localized_time', 'webinarignition_ajax_get_localized_time' );
add_action( 'wp_ajax_webinarignition_ajax_get_localized_time', 'webinarignition_ajax_get_localized_time' );

function webinarignition_ajax_get_localized_time() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$time           = filter_input( INPUT_POST, 'time' );

	echo esc_attr( webinarignition_get_localized_time( $time ) );
	die;
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_get_date_format', 'webinarignition_ajax_get_date_format' );
add_action( 'wp_ajax_webinarignition_ajax_get_date_format', 'webinarignition_ajax_get_date_format' );

function webinarignition_ajax_get_date_format() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$locale = filter_input( INPUT_POST, 'locale' );
	$format = filter_input( INPUT_POST, 'format' );

	switch_to_locale( $locale );

	echo esc_attr( date_i18n( $format ) );

	restore_previous_locale();

	wp_die();
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_get_date_in_chosen_language', 'webinarignition_ajax_get_date_in_chosen_language' );
add_action( 'wp_ajax_webinarignition_ajax_get_date_in_chosen_language', 'webinarignition_ajax_get_date_in_chosen_language' );
/**
 * Retrieves the date in localized format, based on the format and language provided.
 */
function webinarignition_ajax_get_date_in_chosen_language() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$selected_lng   = filter_input( INPUT_POST, 'locale' );

	require_once ABSPATH . 'wp-admin/includes/translation-install.php';

	$available_languages    = webinarignition_get_available_languages();
	$wp_available_languages = get_available_languages();

	if ( get_locale() !== $selected_lng && in_array( $selected_lng, $available_languages, true ) && ! in_array( $selected_lng, $wp_available_languages, true ) ) {
		$downloaded = wp_download_language_pack( $selected_lng );

		if ( $downloaded ) {
			wp_send_json_success( 'downloaded' );
		}
	} else {
		$response = array();
		$switched_locale = switch_to_locale( $selected_lng );

		$date_format                       = 'F j, Y';
		$response['date_in_chosen_locale'] = date_i18n( $date_format );
		$response['date_in_chosen_day_D_locale'] = date_i18n( 'D' );
		$response['date_in_chosen_day_l_locale'] = date_i18n( 'l' );
		$response['monthsFull']            = WiDateHelpers::webinarignition_get_locale_months();
		$response['weekdaysFull']          = WiDateHelpers::webinarignition_get_locale_days();
		$response['weekdaysShort']         = WiDateHelpers::webinarignition_get_locale_weekday_abbrev();
		$response['js_date_format']        = webinarignition_convert_php_to_js_date_format( $date_format );
		$response['php_date_format']       = $date_format;

		$time_format                       = 'g:i a';
		$response['php_time_format']       = $time_format;
		$response['time_in_chosen_locale'] = date_i18n( $time_format );
		$response['js_time_format']        = webinarignition_convert_wp_to_js_time_format( $time_format );
		$response['preview_text']          = __( 'Preview:', 'webinarignition' );
		$response['custom_text']          = __( 'Custom:', 'webinarignition' );

		restore_previous_locale();
		wp_send_json_success( $response );
	} //end if
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_convert_php_to_js_date_format', 'webinarignition_ajax_convert_php_to_js_date_format' );
add_action( 'wp_ajax_webinarignition_ajax_convert_php_to_js_date_format', 'webinarignition_ajax_convert_php_to_js_date_format' );
function webinarignition_ajax_convert_php_to_js_date_format() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$date_format = filter_input( INPUT_POST, 'date_format' );

	$response       = array();
	$response['date_format']  = webinarignition_convert_php_to_js_date_format( $date_format );

	wp_send_json_success( $response );
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_convert_wp_to_js_time_format', 'webinarignition_ajax_convert_wp_to_js_time_format' );
add_action( 'wp_ajax_webinarignition_ajax_convert_wp_to_js_time_format', 'webinarignition_ajax_convert_wp_to_js_time_format' );
function webinarignition_ajax_convert_wp_to_js_time_format() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );
	$time_format = filter_input( INPUT_POST, 'time_format' );

	$response = array();
	$response['time_format'] = webinarignition_convert_wp_to_js_time_format( $time_format );

	wp_send_json_success( $response );
}

// TODO: Need to check how not to duplicate this function, after reviewing the whole plugin structure
if ( ! function_exists( 'webinarignition_get_available_languages' ) ) {
	function webinarignition_get_available_languages() {

		$webinarignition_languages = get_available_languages( WEBINARIGNITION_PATH . '/languages/' );
		$loco_translate_languages  = get_available_languages( WP_CONTENT_DIR . '/languages/loco/plugins/' );
		$system_languages          = get_available_languages( WP_CONTENT_DIR . '/languages/plugins/' );
		$all_languages             = array_merge( $loco_translate_languages, $system_languages, $webinarignition_languages );
		$available_languages       = array();
		$all_languages_count       = count( $all_languages );
		$available_languages_count = count( $available_languages );

		for ( $i = 0; $i < $all_languages_count; $i++ ) {
			if ( ( strpos( $all_languages[ $i ], 'webinarignition' ) !== false ) || ( strpos( $all_languages[ $i ], 'webinar-ignition' ) !== false ) ) {
				$available_languages[] = $all_languages[ $i ];
			}
		}

		for ( $i = 0; $i < $available_languages_count; $i++ ) {
			if ( ( strpos( $available_languages[ $i ], 'webinarignition-' ) !== false ) ) {
				$available_languages[ $i ] = substr( $available_languages[ $i ], 16 );
			}

			if ( ( strpos( $available_languages[ $i ], 'webinar-ignition-' ) !== false ) ) {
				$available_languages[ $i ] = substr( $available_languages[ $i ], 17 );
			}
		}

		return array_unique( $available_languages );
	}
} //end if

function webinarignition_get_lead_table( $webinar_type ) {
	global $wpdb;

	$table = "{$wpdb->prefix}webinarignition_leads";
	$webinar_type = trim( strtolower( $webinar_type ) );

	if ( 'auto' === $webinar_type ) {
		$table = "{$table}_evergreen";
	}

	return $table;
}

function webinarignition_update_webinar_lead_status( $webinar_type, $lead_id ) {
	global $wpdb;

	$table_name = webinarignition_get_lead_table( $webinar_type );
	$id_column = 'ID';
	if ( ! is_numeric( $lead_id ) ) {
		$id_column = 'hash_ID';
	}

	$id_column = esc_sql($id_column); // Escape column name if necessary

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT * FROM `{$table_name}` L WHERE L.`{$id_column}` = %s",
		$lead_id
	);

	$data = $wpdb->get_row($query, OBJECT);
	if ( ! empty( $data ) ) {
		$attended = trim( strtolower( $data->event ) );
		$watched_replay = trim( strtolower( $data->replay ) );
		$status_column_value = 'Yes';

		if ( 'yes' !== $attended ) {
			$status_column = 'event';
		} elseif ( 'yes' !== $watched_replay ) {
			$status_column = 'replay';
		} else {
			$status_column = false;
		}

		if ( ! wp_validate_boolean( $status_column ) ) {

			$lead_status = 'attended'; // Give more logical names to lead status
			if ( 'replay' === $status_column ) {
				$lead_status = 'watched_replay';
			}

			$updated = $wpdb->update( $table_name, array( $status_column => $status_column_value ), array( $id_column => $lead_id ) );
			do_action( 'webinarignition_lead_updated', $data->ID );
			do_action( 'webinarignition_lead_status_changed', $lead_status, $lead_id, $data->app_id );

			return ! empty( $updated );
		}
	} //end if

	return false;
}

/**
 * Check if current logged in user has existing un-attempted lead for the given webinar ID
 *
 * Returns 0 if no lead found, numeric lead ID otherwise
 *
 * @param int    $webinar_id The webinar id.
 * @param string $user_email The webinar associated email.
 * @param string $webinar_type The webinar type.
 *
 * @return int
 */
function webinarignition_existing_lead_id( $webinar_id, $user_email, $webinar_type = 'auto' ) {
	$webinar_id = absint( $webinar_id );

	if ( empty( $webinar_id ) || empty( $webinar_type ) || empty( $user_email ) ) {
		return 0;
	}

	global $wpdb;

	$table_lead = 'auto' === $webinar_type ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads';	
	// Escape the table name
	$table_lead = esc_sql($table_lead);

	// Prepare and execute the query
	$query = $wpdb->prepare(
		"SELECT L.ID FROM `{$table_lead}` L WHERE L.app_id = %d AND L.email = %s",
		$webinar_id,
		$user_email
	);

	$lead_id = $wpdb->get_var($query);


	$lead_id = absint( $lead_id );

	return $lead_id;
}

/**
 * Delete lead by ID and webinar type
 *
 * @param int    $lead_id The lead id.
 * @param string $webinar_type The webinar type.
 */
function webinarignition_delete_lead_by_id( $lead_id, $webinar_type = 'auto' ) {
	global $wpdb;

	if ( 'auto' === $webinar_type ) {
		$table_lead      = $wpdb->prefix . 'webinarignition_leads_evergreen';
		$table_lead_meta = $wpdb->prefix . 'webinarignition_lead_evergreenmeta';
	} else {
		$table_lead      = $wpdb->prefix . 'webinarignition_leads';
		$table_lead_meta = $wpdb->prefix . 'webinarignition_leadmeta';
	}

	$lead_id = absint( $lead_id );

	$lead_deleted = $wpdb->delete(
		$table_lead,
		array( 'ID' => $lead_id ),
		array( '%d' )
	);

	if ( $lead_deleted ) {
		$wpdb->delete(
			$table_lead_meta,
			array( 'lead_id' => $lead_id ),
			array( '%d' )
		);
	}
}

/**
 * @param obj    $webinar_data The webinar data.
 * @param obj    $lead The lead data..
 * @param string $status The lead status.
 */
function webinarignition_mark_lead_status( $webinar_data, $lead, $status ) {
	if ( ! empty( $webinar_data ) && ! empty( $lead ) ) {

		if ( 'attending' === $status ) {
			$webinar_timezone = webinarignition_get_webinar_timezone( $webinar_data, null, $lead );
			$webinar_timezone = Webinar_Ignition_Helper::webinarignition_getValidTimezoneId( $webinar_timezone );
			$lead_live_datetime = isset( $lead->date_picked_and_live ) && ! empty( $lead->date_picked_and_live ) ? $lead->date_picked_and_live : gmdate( 'Y-m-d H:i:s' );

			$datetime_now = new DateTime( 'now', new DateTimeZone( $webinar_timezone ) );

			// Create a new datetime object with today's date for comparison with max time slot, and assign webinar timezone
			$datetime_compare = new DateTime( gmdate( 'Y-m-d H:i:s', strtotime( $lead_live_datetime ) ), new DateTimeZone( $webinar_timezone ) );

			// Convert current datetime from webinar timezone to UTC for comparison, and to avoid daylight saving differences
			$datetime_now->setTimezone( new DateTimeZone( 'UTC' ) );

			// Convert compare datetime from webinar timezone to UTC
			$datetime_compare->setTimezone( new DateTimeZone( 'UTC' ) );

			// If current time is less than lead time, then consider lead is not yet started/available
			if ( $datetime_now->getTimestamp() < $datetime_compare->getTimestamp() ) {
				return false;
			}
		} //end if

		$is_auto = webinarignition_is_auto( $webinar_data );

		global $wpdb;

		$leads_table = "{$wpdb->prefix}webinarignition_leads";
		if ( $is_auto ) {
			$leads_table .= '_evergreen';
		}

		$wpdb->update(
			$leads_table,
			array( 'lead_status' => $status ),
			array(
				'ID'     => $lead->ID,
				'app_id' => $webinar_data->id,
			)
		);

		do_action( 'webinarignition_lead_status_changed', $status, $lead->ID, $webinar_data->id );

		return true;
	} //end if

	return false;
}

function webinarignition_mark_lead_watched() {
	check_admin_referer( 'webinarignition_mark_lead_status', 'nonce' );

	if ( ! wp_doing_ajax() ) {
		return;
	}

	$response_type = 'error';

	if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {

		if ( isset( $_POST['is_preview_page'] ) && wp_validate_boolean( $_POST['is_preview_page'] ) ) {
			$response_type = 'success'; // Return success always for preview page
		} else {
			$webinar_id = absint( $_POST['webinar_id'] );
			$lead_id    = absint( $_POST['lead_id'] );

			$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
			$lead         = webinarignition_get_lead_info( $lead_id, $webinar_data, false );

			if ( 'watched' !== $lead->lead_status ) {
				if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'watched' ) ) {
					$response_type = 'success';
				}
			}
		}
	}

	call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_watched', 'webinarignition_mark_lead_watched' );
add_action( 'wp_ajax_webinarignition_lead_mark_watched', 'webinarignition_mark_lead_watched' );

function webinarignition_mark_lead_attended() {
	check_admin_referer( 'webinarignition_mark_lead_status', 'nonce' );

	if ( ! wp_doing_ajax() ) {
		return;
	}

	$response_type = 'error';

	if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {

		if ( isset( $_POST['is_preview_page'] ) && wp_validate_boolean( $_POST['is_preview_page'] ) ) {
			$response_type = 'success'; // Return success always for preview page
		} else {
			$webinar_id = absint( $_POST['webinar_id'] );
			$lead_id    = absint( $_POST['lead_id'] );

			$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
			$lead         = webinarignition_get_lead_info( $lead_id, $webinar_data, false );

			if ( 'attended' !== $lead->lead_status ) {
				if ( empty( $lead->lead_status ) ) {
					if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'attended' ) ) {
						$response_type = 'success';
					}
				}
			}
		}
	}

	call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_attended', 'webinarignition_mark_lead_attended' );
add_action( 'wp_ajax_webinarignition_lead_mark_attended', 'webinarignition_mark_lead_attended' );

function webinarignition_mark_lead_attending() {
	check_admin_referer( 'webinarignition_mark_lead_status', 'nonce' );

	if ( ! wp_doing_ajax() ) {
		return;
	}

	$response_type = 'error';

	if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {

		$webinar_id = absint( $_POST['webinar_id'] );
		$lead_id    = absint( $_POST['lead_id'] );

		$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
		$lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );

		if ( isset( $lead->lead_status ) && 'watched' !== $lead->lead_status ) {
			if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'attending' ) ) {
				$response_type = 'success';
			}
		}
	}

	call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_attending', 'webinarignition_mark_lead_attending' );
add_action( 'wp_ajax_webinarignition_lead_mark_attending', 'webinarignition_mark_lead_attending' );

function webinarignition_mark_lead_complete() {
	check_admin_referer( 'webinarignition_mark_lead_status', 'nonce' );

	if ( ! wp_doing_ajax() ) {
		return;
	}

	$response_type = 'error';

	if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {
		$webinar_id = absint( $_POST['webinar_id'] );
		$lead_id    = absint( $_POST['lead_id'] );

		$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
		$lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );

		if (isset($lead)  && 'watched' !== $lead->lead_status ) {
			if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'complete' ) ) {
				$response_type = 'success';
			}
		}
	}

	call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_complete', 'webinarignition_mark_lead_complete' );
add_action( 'wp_ajax_webinarignition_lead_mark_complete', 'webinarignition_mark_lead_complete' );
