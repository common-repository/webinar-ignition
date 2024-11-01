<?php
/**
 * @var $webinar_data
 * @var $uid
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="eventDate fixed-type <?php echo esc_attr( $uid ); ?>">
	<?php
	$dateTime = new DateTime();
	$dateTime->setTimeZone( new DateTimeZone( $webinar_data->auto_timezone_fixed ) );
	$tz_abbr     = $dateTime->format( 'T' );
	$split_date  = explode( '-', $webinar_data->auto_date_fixed );
	$date_format = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';
	$auto_date   = webinarignition_get_translated_date( $webinar_data->auto_date_fixed, 'Y-m-d', $date_format );
	?>
	<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/date_crystal.png' ); ?>"/>
	<span style="font-size: 18px"><?php echo esc_html( $auto_date ); ?></span>
	<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/clock.png' ); ?>"/>
	<span style="font-size: 18px"><?php echo esc_html( $webinar_data->auto_time_fixed . ' ' . $tz_abbr ); ?></span>
	<input type="hidden" id="webinar_start_date"
			value="<?php echo esc_html( $split_date[2] ), '-', esc_html( $split_date[0] ), '-', esc_html( $split_date[1] ); ?>"/>
	<input type="hidden" id="webinar_start_time" value="<?php echo esc_html( $webinar_data->auto_time_fixed ); ?>"/>
	<input type="hidden" id="timezone_user" value="<?php echo esc_html( $webinar_data->auto_timezone_fixed ); ?>">
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
	<br clear="left"/>
</div>
