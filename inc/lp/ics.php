<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get Results
$results = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

// Get DB Info
global $wpdb;
$table_db_name = $wpdb->prefix . $pluginName;
// Sanitize input values
$webinar_id = intval($webinar_id); // Ensure $webinar_id is an integer

// Prepare and execute the query
$data = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
        $webinar_id
    ),
    OBJECT
);
// Webinar Info
$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinarignition' );
$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinarignition' );
$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinarignition' );

$lid = filter_input( INPUT_GET, 'lid', FILTER_SANITIZE_URL );

if ( 'custom' === $results->ty_webinar_url ) {
	$url = $results->ty_werbinar_custom_url;
} else {
	$url = get_permalink( $data->postID ) . '?live&lid=' . $lid;
}


$results->webinar_start_time = gmdate( 'H:i', strtotime( $results->webinar_start_time ) );


$timezone = $results->webinar_timezone;
if ( ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
	$timezone = '+' . $timezone;
}
$timezone_sign   = $timezone[0];
$timezone_offset = str_pad( str_replace( '0', '', substr( $timezone, 1 ) ), 4, '0', STR_PAD_BOTH );
$date            = DateTime::createFromFormat( 'm-d-Y H:i:s', $results->webinar_date . ' ' . $results->webinar_start_time . ':00', new DateTimeZone( $timezone_offset ) );
$date->setTimezone( new DateTimeZone( 'UTC' ) );

define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );

header( 'Content-type: application/text' );
header( 'Content-Disposition: attachment; filename=webinar-date.ics' );
header( 'Pragma: no-cache' );
header( 'Expires: 0' );

echo 'BEGIN:VCALENDAR' . "\r\n" .
	'VERSION:2.0' . "\r\n" .
	'PRODID:-//project/author//NONSGML v1.0//EN' . "\r\n" .
	'CALSCALE:GREGORIAN' . "\r\n" .
	'METHOD:PUBLISH' . "\r\n" .
	'BEGIN:VTIMEZONE' . "\r\n" .
	'TZID:GMT' . "\r\n" .
	'BEGIN:STANDARD' . "\r\n" .
	'DTSTART:20071028T010000' . "\r\n" .
	'TZOFFSETTO:+0000' . "\r\n" .
	'TZOFFSETFROM:+0000' . "\r\n" .
	'END:STANDARD' . "\r\n" .
	'END:VTIMEZONE' . "\r\n" .
	'BEGIN:VEVENT' . "\r\n" .
	'DTSTART;TZID=GMT:' . esc_html( $date->format( WEBINARIGNITION_DATE_FORMAT ) ) . "\r\n" .
	'DTEND;TZID=GMT:' . esc_html( $date->modify( '+1 hour' )->format( WEBINARIGNITION_DATE_FORMAT ) ) . "\r\n" .
	'UID:' . esc_html( $date->getTimestamp() ) . '@' . esc_html( $webinar_id ) . "\r\n" .
	'DTSTAMP:' . esc_html( gmdate( WEBINARIGNITION_DATE_FORMAT ) ) . 'Z' . "\r\n" .
	'SUMMARY:' . esc_html( $webinar_title ) . "\r\n" .
	'DESCRIPTION:' . esc_html( $desc ) . '. Visit ' . esc_url( $url ) . "\r\n" .
	'URL;VALUE=URI:' . esc_url( $url ) . "\r\n" .
	'END:VEVENT' . "\r\n" .
	'END:VCALENDAR';
