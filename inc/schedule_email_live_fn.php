<?php
use Twilio\Rest\Client;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ####################################
//
// Check If Current Is Within Range Of Email Date
//
// ####################################


function webinarignition_dt_check( $start_date, $end_date, $date_from_db ) {
	// Convert to timestamp
	$start_ts = strtotime( $start_date );
	$end_ts = strtotime( $end_date );
	$user_ts = strtotime( $date_from_db );

	// Check that user date is between start & end
	if ( ( $user_ts >= $start_ts ) && ( $user_ts <= $end_ts ) ) {
		return 'yes';
	} else {
		return 'no';
	}
}

// ####################################
//
// Send Live-Webinar Email Notification
//
// ####################################
// --------------------------------------------------------------------------------------
function webinarignition_send_email( $ID, $num, $webinar_data ) {

	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format' );
	if ( ! empty( $webinar_data->time_format ) && ( $webinar_data->time_format == '12hour' || $webinar_data->time_format == '24hour' ) ) { // old formats
			$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
		$time_format    = $webinar_data->time_format;

		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition_leads';

		$list = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM %i WHERE app_id = %s",
				$table_db_name,
				$ID
			),
			OBJECT
		);
		$body = '';

	$Subject  = $webinar_data->{'email_notiff_sbj_' . $num};
	$Subject  = str_replace( '{TITLE}', $webinar_data->webinar_desc, $Subject );

	if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( $webinar_data->use_new_email_signup_template == 'yes' ) ) ) {

		// use new templates
		$webinar_data->emailheading     = $webinar_data->{'email_notiff_' . $num . '_heading'};
		$webinar_data->emailpreview     = $webinar_data->{'email_notiff_' . $num . '_preview'};
		$webinar_data->bodyContent      = $webinar_data->{'email_notiff_body_' . $num};
		$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_notiff_' . $num . '_footer' ) && $webinar_data->{'show_or_hide_local_notiff_' . $num . '_footer'} == 'show' ) ? $webinar_data->{'local_notiff_' . $num . '_footer'} : '';

		$email                     = new WI_Emails();
		$getBodyEmail              = $email->webinarignition_build_email( $webinar_data );

	} else {

		$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
		$getBodyEmail = $emailHead;
		$getBodyEmail .= $webinar_data->{'email_notiff_body_' . $num};
		$getBodyEmail .= '</html>';

	}

		$translated_date      = webinarignition_get_translated_date( $webinar_data->webinar_date, 'm-d-Y', $date_format );
		$timeonly             = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) ) ? false : true;
		$body                 = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( $webinar_data->webinar_start_time, $time_format, $webinar_data->webinar_timezone, false, $timeonly ), $getBodyEmail );
		$errs                 = 0;
		$mesg                 = '';

		webinarignition_test_smtp_options();

	foreach ( $list as $lead ) {
		$bdy = $body;
		$bdy = str_replace( '{FIRSTNAME}', $lead->name, $bdy );
		$additional_params = ( ( $webinar_data->paid_status == 'paid' ) ? md5( $webinar_data->paid_code ) : '' );
		$bdy = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead->ID, $bdy, $additional_params );
		$mesg = "Added {$lead->name} ({$lead->email}) to email recipient list\n";

		WebinarIgnition_Logs::add( $mesg, $ID, WebinarIgnition_Logs::LIVE_EMAIL );

		$headers          = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

		try {
			if ( ! wp_mail( $lead->email, $Subject, $bdy, $headers ) ) {
				WebinarIgnition_Logs::add( "ERROR:: Email could not be sent to {$lead->email}.", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
			} else {
				WebinarIgnition_Logs::add( __( 'Mail Sent.', 'webinarignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );
			}
		} catch ( Exception $e ) {
			WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinarignition' ) . " {$lead->email}.", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
		}
	}//end foreach

		return true;
}
// --------------------------------------------------------------------------------------




// ####################################
//
// Send TXT Notification
//
// ####################################
function webinarignition_send_txt( $results ) {
	// LOOP THROUGH EMAILS HERE ::
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';
	$leads = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM %i WHERE app_id = %d",
			$table_db_name,
			$results->id
		),
		OBJECT
	);
	// Loop Through Each Lead & Send ::
	// Send TXT Messages
	$AccountSid = $results->twilio_id;
	$AuthToken  = $results->twilio_token;

	$client = new Client( $AccountSid, $AuthToken );

	$MSG = $results->twilio_msg;
	// Shortcode {LINK}
	$txt_sent = false;

	foreach ( $leads as $lead ) {
		if ( $lead->phone == 'undefined' || $lead->phone == '' ) {

		} else {
			$txt_sent = true;
			try {
				$additional_params = ( ( $results->paid_status == 'paid' ) ? md5( $results->paid_code ) : '' );

				$MSG = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $results, $lead->ID, $MSG, $additional_params, array( 'is_sms' => true ) );

				$client->messages->create(
					sanitize_text_field( trim( $lead->phone ) ),
					array(
						'from' => $results->twilio_number,
						'body' => $MSG,
					)
				);

				WebinarIgnition_Logs::add( "TXT Sent to {$lead->name} ({$lead->phone})", $results->id, WebinarIgnition_Logs::LIVE_SMS );
				// echo 'TXT Sent :: ' . $leads->phone;
				// echo "<br>";
			} catch ( Exception $e ) {
				// Error On Phone Number - Do Nothing
				// echo 'Error: ' . $e->getMessage();
				WebinarIgnition_Logs::add( __( 'Error sending TXT to', 'webinarignition' ) . " {$lead->name} ({$lead->phone}): " . $e->getMessage(), $results->id, WebinarIgnition_Logs::LIVE_SMS );
			}//end try
		}//end if
	}//end foreach
	if ( ! $txt_sent ) {
		WebinarIgnition_Logs::add( __( 'No leads to send TXT to.', 'webinarignition' ), $results->id, WebinarIgnition_Logs::LIVE_SMS );
	}
}
// --------------------------------------------------------------------------------------




// AUTO
// --------------------------------------------------------------------------------------
// Send Out AUTO Emails
function webinarignition_cron_email( $ID, $LEADID, $num, $NAME, $EMAIL, $DATE, $TIMEZONE ) {
	// Setup Info
	$webinar_data   = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

	$is_instant_lead = false;
	$is_watched_lead = false;
	if ( ! empty( $LEADID ) ) {
		$lead = webinarignition_get_lead_info( $LEADID, $webinar_data, false );
		if ( ! empty( $lead ) ) {
			if ( isset( $lead->trk8 ) && 'yes' === $lead->trk8 ) {
				$is_instant_lead = true;
			}

			if ( isset( $lead->lead_status ) && 'watched' === $lead->lead_status ) {
				$is_watched_lead = true;
			}
		}
	}

	if ( $is_instant_lead || $is_watched_lead ) {
		return; // Disable email notifications for instant/watched leads
	}

	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || $webinar_data->time_format == '24hour' ) ) { // old formats
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
	$time_format    = $webinar_data->time_format;
	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format' );

	// check if notification is disabled, and halt sending it
	if ( $webinar_data->{'email_notiff_' . $num} == 'off' ) {
		WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( $num ) . ' disabled - aborting!', $ID, WebinarIgnition_Logs::AUTO_EMAIL );
		// return true so that it can be marked as sent (else the logs table ends up with millions of useless entries)
		return true;
	}

	// Preprocess Email w/ Shortcodes
	$getBody = 'email_notiff_body_' . $num;

	if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( $webinar_data->use_new_email_signup_template == 'yes' ) ) ) {

			// use new templates
			$webinar_data->emailheading     = $webinar_data->{'email_notiff_' . $num . '_heading'};
			$webinar_data->emailpreview     = $webinar_data->{'email_notiff_' . $num . '_preview'};
			$webinar_data->bodyContent      = $webinar_data->{'email_notiff_body_' . $num};
			$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_notiff_' . $num . '_footer' ) && $webinar_data->{'show_or_hide_local_notiff_' . $num . '_footer'} == 'show' ) ? $webinar_data->{'local_notiff_' . $num . '_footer'} : '';

			$email                      = new WI_Emails();
			$getBodyEmail               = $email->webinarignition_build_email( $webinar_data );

	} else {

			$emailHead          = WebinarignitionEmailManager::webinarignition_get_email_head();
			$getBodyEmail       = $emailHead;
			$getBodyEmail       .= $webinar_data->$getBody;
			$getBodyEmail       .= '</html>';

	}

	$autoDate_info      = explode( ' ', $DATE );
	$translated_date    = webinarignition_get_translated_date( $autoDate_info[0], 'Y-m-d', $date_format );

	// Replace
	$timeonly     = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) ) ? false : true;
	$getBodyEmail = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( $autoDate_info[1], $time_format, $TIMEZONE, false, $timeonly ), $getBodyEmail );
	$getBodyEmail = str_replace( '{FIRSTNAME}', $NAME, $getBodyEmail );

	$getSBJ   = 'email_notiff_sbj_' . $num;
	$Subject  = $webinar_data->$getSBJ;
	$Subject  = str_replace( '{TITLE}', $webinar_data->webinar_desc, $Subject );
	$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

	$additional_params = 'event=OI3shBXlqsw';
	$watch_type = 'live';
	if ( $num === 3 || $num === 5 ) {
		$watch_type = 'replay';
	}
	$additional_params .= "&watch_type={$watch_type}";
	$additional_params .= ( ( $webinar_data->paid_status == 'paid' ) ? '&' . md5( $webinar_data->paid_code ) : '' );

	$getBodyEmail = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $LEADID, $getBodyEmail, $additional_params );

	try {
		if ( ! wp_mail( $EMAIL, $Subject, $getBodyEmail, $headers ) ) {
			// echo 'Mailer Error: ' . $mail->ErrorInfo;
			WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinarignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
			return false;
		} else {
			// echo 'Email Sent :: ' . $EMAIL;
			// echo "<br>";
			WebinarIgnition_Logs::add( __( 'Mail Sent.', 'webinarignition' ), $ID, WebinarIgnition_Logs::AUTO_EMAIL );
			return true;
		}
	} catch ( Exception $e ) {
		WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinarignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
		return false;
	}
}

// ####################################
//
// Send TXT Notification
//
// ####################################
function webinarignition_send_txt_auto( $ID, $PHONE, $LEADID ) {

	// Get Results
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

	$is_instant_lead = false;
	if ( ! empty( $LEADID ) ) {
		$lead = webinarignition_get_lead_info( $LEADID, $webinar_data, false );
		if ( ! empty( $lead ) && isset( $lead->trk8 ) && $lead->trk8 === 'yes' ) {
			$is_instant_lead = true;
		}
	}

	if ( $is_instant_lead ) {
		return; // Disable SMS notification for instant leads
	}

	if ( ! empty( $webinar_data->twilio_id ) && ! empty( $webinar_data->twilio_token ) ) {

		$AccountSid = $webinar_data->twilio_id;
		$AuthToken  = $webinar_data->twilio_token;
		$client     = new Client( $AccountSid, $AuthToken );

		$MSG = $webinar_data->twilio_msg;

		$additional_params = 'event=OI3shBXlqsw';
		$additional_params .= ( ( $webinar_data->paid_status == 'paid' ) ? '&' . md5( $webinar_data->paid_code ) : '' );

		$MSG = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $LEADID, $MSG, $additional_params, array( 'is_sms' => true ) );

		try {

			$client->messages->create(
				sanitize_text_field( trim( $PHONE ) ),
				array(
					'from' => $webinar_data->twilio_number,
					'body' => $MSG,
				)
			);

			WebinarIgnition_Logs::add( __( 'TXT notification Sent.', 'webinarignition' ), $ID, WebinarIgnition_Logs::AUTO_SMS );
		} catch ( Exception $e ) {

			WebinarIgnition_Logs::add( __( 'Error sending TXT to', 'webinarignition' ) . " {$PHONE}: " . $e->getMessage(), $ID, WebinarIgnition_Logs::AUTO_SMS );
		}
	} else {

		WebinarIgnition_Logs::add( __( 'Error sending TXT to', 'webinarignition' ) . " {$PHONE}: Credentials are required to create a Client.", $ID, WebinarIgnition_Logs::AUTO_SMS );

	}//end if
}
