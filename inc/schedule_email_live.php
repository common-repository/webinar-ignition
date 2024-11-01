<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $campaignID ) ) {
	require_once 'schedule_notifications.php';
} else {
	// Get Results
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $campaignID );
	if ( ! empty( $webinar_data->time_format ) && ( $webinar_data->time_format == '12hour' || $webinar_data->time_format == '24hour' ) ) { // old formats
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
	$time_format = $webinar_data->time_format;

	// SETUP :: Core Time Settings
	$TZID = webinarignition_convert_utc_to_tzid( $webinar_data->webinar_timezone );


	

	$date_and_time = date( 'Y-m-d H:i' );
	$date_only     = date( 'Y-m-d' );
	$time_only     = date( 'H:i' );
	$time_only_e   = explode( ':', $time_only );

	// SETUP :: Buffer Zone
	$time         = strtotime( $time_only );
	$startTime    = date( 'H:i', strtotime( '-30 minutes', $time ) );
	$endTime      = date( 'H:i', strtotime( '+30 minutes', $time ) );
	$time_buffer  = $time_only_e[1] - 10;
	$time_buffer2 = $time_only_e[1] + 10;
	$dt_buffer_n  = $date_only . ' ' . $startTime;
	$dt_buffer_p  = $date_only . ' ' . $endTime;

	// #####################################
	//
	// ### Schedule Checks - Match Time/Date
	//
	// #####################################
	//
	// NOTIFICATION EMAIL #1
	//
	// #####################################
	//
	$time        = time();
	$timeonly    = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) ) ? false : true;
	$webinar_utc = trim( webinarignition_get_time_tz( $time, $time_format, $webinar_data->webinar_timezone, true, $timeonly ) );
	$date        = date( 'Y-m-d', $time );
	$today       = date( 'Y-m-d' );

	for ( $num = 5; $num > 0; $num-- ) {

		if ( isset( $webinar_data->{"email_notiff_date_{$num}"} ) && isset( $webinar_data->{"email_notiff_time_{$num}"} ) ) {
			$notification_date = webinarignition_build_time( $webinar_data->{"email_notiff_date_{$num}"}, $webinar_data->{"email_notiff_time_{$num}"} );
		} else {
			$notification_date = '';
		}

		if ( isset( $webinar_data->{'email_notiff_' . $num} ) && $webinar_data->{'email_notiff_' . $num} != 'off' && isset( $webinar_data->{"email_notiff_status_{$num}"} ) && $webinar_data->{"email_notiff_status_{$num}"} != 'sent' && ( $time - strtotime( $notification_date ) ) >= 0 ) {

			$dateInWebinarTz = new DateTime( 'now', new DateTimeZone( $webinar_data->webinar_timezone ) );
			$dateInWebinarTz->setTime( 0, 0 ); // Set the time to the start of the day (midnight)
			$formattedDate = $dateInWebinarTz->format( 'm-d-Y' );

			// if this is the day-before notification "WEBINAR REMINDER :: Goes Live Tomorrow" and the webinar is today, do not send.
			if ( ( 1 == $num ) && ( $formattedDate == $webinar_data->webinar_date ) ) {
				$webinar_data->{"email_notiff_status_{$num}"} = 'sent';
				update_option( 'webinarignition_campaign_' . $campaignID, $webinar_data );
				continue;
			}

			if ( isset( $webinar_data->webinar_date ) ) {
				WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( $num ) . " ($notification_date) " . __( 'triggered for webinar starting on', 'webinarignition' ) . " {$webinar_data->webinar_date} @ {$webinar_data->webinar_start_time} ($webinar_utc)", $campaignID, WebinarIgnition_Logs::LIVE_EMAIL );
			}

			$webinar_data->{"email_notiff_status_{$num}"} = 'sent';
			update_option( 'webinarignition_campaign_' . $campaignID, $webinar_data );
			webinarignition_send_email( $campaignID, $num, $webinar_data );

		}//end if
	}//end for

	//
	// #####################################
	//
	// NOTIFICATION TXT
	//
	// #####################################
	//

	if ( isset( $webinar_data->email_twilio ) && $webinar_data->email_twilio != 'off' ) {

		$notification_date = webinarignition_build_time( $webinar_data->email_twilio_date, $webinar_data->email_twilio_time );
		if ( isset( $webinar_data->email_twilio_status ) && $webinar_data->email_twilio_status != 'sent' && ( $time - strtotime( $notification_date ) ) >= 0 ) {

			WebinarIgnition_Logs::add( "TXT notification ($notification_date) " . __( 'triggered for webinar starting on', 'webinarignition' ) . " {$webinar_data->webinar_date} @ {$webinar_data->webinar_start_time} ($webinar_utc)", $campaignID, WebinarIgnition_Logs::LIVE_SMS );
			if ( ! empty( $webinar_data->twilio_id ) && ! empty( $webinar_data->twilio_token ) ) {
				webinarignition_send_txt( $webinar_data );
				$webinar_data->email_twilio_status = 'sent';
			}

			update_option( 'webinarignition_campaign_' . $campaignID, $webinar_data );
		}
	}
}//end if
