<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $leadID
 * @var $leadId
 * @var $leadinfo
 */

// Get Results
$results = $webinar_data;
$data    = $leadinfo;

// Webinar Info
$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinarignition' );
$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinarignition' );
$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinarignition' );

if ( 'custom' === $results->ty_webinar_url ) {
	$url = $results->ty_werbinar_custom_url;
} else {
	$url = get_permalink( $data->postID ) . '?live&lid=' . $leadId;
}

if ( ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
	$timezone = '+' . $timezone;
}
$date = DateTime::createFromFormat( 'Y-m-d H:i', $data->date_picked_and_live, new DateTimeZone( $data->lead_timezone ) );
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
	'UID:' . esc_html( $date->getTimestamp() ) . '@' . esc_html( $leadId ) . "\r\n" .
	'DTSTAMP:' . esc_html( gmdate( WEBINARIGNITION_DATE_FORMAT ) ) . 'Z' . "\r\n" .
	'SUMMARY:' . esc_html( $webinar_title ) . "\r\n" .
	'DESCRIPTION:' . esc_html( $desc ) . '. Visit ' . esc_url( $url ) . "\r\n" .
	'URL;VALUE=URI:' . esc_url( $url ) . "\r\n" .
	'END:VEVENT' . "\r\n" .
	'END:VCALENDAR';
