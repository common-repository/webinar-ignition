<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $campaignID ) ) {
	require_once 'schedule_notifications.php';
} else {
	// Get ALL Leads
	global $wpdb;
	$table_db_name          = $wpdb->prefix . 'webinarignition_leads_evergreen';
	$query                  = $wpdb->prepare( "SELECT * FROM $table_db_name WHERE app_id = %s", $campaignID );
	$results                = $wpdb->get_results( $query, OBJECT );
	$timezone_string_option = get_option( 'timezone_string' );

	if ( ! empty( $results ) ) {
		foreach ( $results as $result ) {
			// LOOP START ##################
			// GET DATE -------------------
			// Get Date
			// Set Timezone:

			$tzstring = empty( $result->lead_timezone ) ? $timezone_string_option : $result->lead_timezone;

			$date_and_time = gmdate( 'Y-m-d H:i' );
			$date_only     = gmdate( 'Y-m-d' );
			$time_only     = gmdate( 'H:i' );
			$time_only_e   = explode( ':', $time_only );

			$time      = strtotime( $time_only );
			$startTime = gmdate( 'H:i', strtotime( '-30 minutes', $time ) );
			$endTime   = gmdate( 'H:i', strtotime( '+30 minutes', $time ) );

			$time_buffer                   = $time_only_e[1] - 10;
			$time_buffer2                  = $time_only_e[1] + 10;
			$date_and_time_buffer_negative = $date_only . ' ' . $startTime;
			$date_and_time_buffer_plus     = $date_only . ' ' . $endTime;

			// Check If Lead is Complete - Ignore
			if (in_array($result->lead_status, array('complete', 'attending', 'watched'), true)) { //phpcs:ignore
				// IGNORE - done sequence
			} else {
				// ####################################
				//
				// Check 1 Day After
				//
				// ####################################
				if ( 'sent' !== $result->date_1_day_after_check && ( $time - strtotime( $result->date_1_day_after ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: 1 DAY AFTER :: ". $result->email ."</b>";
					WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 5 ) . " ({$result->date_1_day_after}) " . __( 'triggered for', 'webinarignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinarignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
					webinarignition_cron_email( $campaignID, $result->ID, 5, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
					// Update In DB
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE $table_db_name SET 
							date_1_day_after_check = %s,
							date_after_live_check = %s,
							date_picked_and_live_check = %s,
							date_1_day_before_check = %s,
							date_1_hour_before_check = %s,
							lead_status = %s
							WHERE id = %d",
							'sent',
							'sent',
							'sent',
							'sent',
							'sent',
							'complete',
							$result->ID
						)
					);

					continue;
				}

				// ####################################
				//
				// Check After Live Is Over
				//
				// ####################################
				if ( 'sent' !== $result->date_after_live_check && ( $time - strtotime( $result->date_after_live ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: 1 HOUR AFTER :: ". $result->email ."</b>";
					WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 4 ) . " ({$result->date_after_live}) " . __( 'triggered for', 'webinarignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinarignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
					webinarignition_cron_email( $campaignID, $result->ID, 4, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
					// Update In DB
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE $table_db_name SET 
							date_after_live_check = %s,
							date_picked_and_live_check = %s,
							date_1_day_before_check = %s,
							date_1_hour_before_check = %s
							WHERE id = %d",
							'sent',
							'sent',
							'sent',
							'sent',
							$result->ID
						)
					);
					continue;
				}

				// ####################################
				//
				// Check LIVE Webinar
				//
				// ####################################
				if ( 'sent' !== $result->date_picked_and_live_check && ( $time - strtotime( $result->date_picked_and_live ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: EVENT LIVE :: ". $result->email ."</b>";
					WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 3 ) . " ({$result->date_picked_and_live}) " . __( 'triggered for', 'webinarignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinarignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
					webinarignition_cron_email( $campaignID, $result->ID, 3, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
					// Update In DB
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE $table_db_name SET 
							date_picked_and_live_check = %s,
							date_1_day_before_check = %s,
							date_1_hour_before_check = %s
							WHERE id = %d",
							'sent',
							'sent',
							'sent',
							$result->ID
						)
					);

					continue;
				}

				// ####################################
				//
				// Check 1 Hour Before
				//
				// ####################################

				if ( 'sent' !== $result->date_1_hour_before_check && ( $time - strtotime( $result->date_1_hour_before ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: 1 HOUR BEFORE :: ". $result->email ."</b>";
					WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 2 ) . " ({$result->date_1_hour_before}) " . __( 'triggered for', 'webinarignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinarignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );

					webinarignition_cron_email( $campaignID, $result->ID, 2, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );

					if ( ! empty( $result->phone ) ) {
						WebinarIgnition_Logs::add( "TXT notification ({$result->date_1_hour_before}) " . __( 'triggered for', 'webinarignition' ) . " {$result->name} ({$result->phone}) - " . __( 'chosen starting date:', 'webinarignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_SMS );
						webinarignition_send_txt_auto( $campaignID, $result->phone, $result->ID );
					}

					// Update In DB
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE $table_db_name SET 
							date_1_hour_before_check = %s,
							date_1_day_before_check = %s
							WHERE id = %d",
							'sent',
							'sent',
							$result->ID
						)
					);

					continue;
				}

				// start if loop
				// ####################################
				//
				// Check 1 Day Before
				//
				// ####################################

				if ( 'sent' !== $result->date_1_day_before_check && ( $time - strtotime( $result->date_1_day_before ) >= 0 ) ) {

					// Create a DateTime object from the given date and timezone
					$date_picked = DateTime::createFromFormat( 'Y-m-d H:i', $result->date_picked_and_live, new DateTimeZone( $result->lead_timezone ) );

					// Format the date as 'm-d-Y'
					$date_picked_formatted = $date_picked->format( 'm-d-Y' );

					// Create a DateTime object for 'today' in the specified timezone
					$today = new DateTime( 'now', new DateTimeZone( $result->lead_timezone ) );

					// Set the time to the start of the day (midnight)
					$today->setTime( 0, 0 );

					// Format today's date as 'm-d-Y'
					$today_formatted = $today->format( 'm-d-Y' );

					if ( $today_formatted !== $date_picked_formatted ) { // don't send tomorrow-reminders on the day.
						WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 1 ) . " ({$result->date_1_day_before}) " . __( 'triggered for', 'webinarignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinarignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
						webinarignition_cron_email( $campaignID, $result->ID, 1, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
						// Update In DB
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table_db_name SET date_1_day_before_check = %s WHERE id = %d",
								'sent',
								$result->ID
							)
						);
					}

					continue;
				}
				// end if loop
			} //end if
		} //end foreach
	} //end if
}//end if
