<?php
/**
 * @var $webinar_data
 * @var $uid
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="eventDate fixed-type <?php echo esc_attr( $uid ); ?>">
	<?php
	$dateTime     = webinarignition_make_delayed_date( $webinar_data );
	$tz_abbr      = $dateTime->format( 'T' );
	$date_format  = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';
	$delayed_date = webinarignition_get_translated_date( $dateTime->format( 'm-d-Y' ), 'm-d-Y', $date_format );
	?>
	<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/date_crystal.png' ); ?>"/>
	<span style="font-size: 18px"><?php echo esc_html( $delayed_date ); ?></span>
	<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/clock.png' ); ?>"/>
	<span style="font-size: 18px">
		<?php
		echo esc_html( $webinar_data->auto_time_delayed ) . ' ';
		if ( 'user_specific' !== $webinar_data->delayed_timezone_type ) {
			echo esc_html( $tz_abbr );
		} else {
			?>
			<div class="user_specific_timezone_name" style="display: inline-block;font-size: 10px">
				<?php
				if ( $webinar_data->auto_timezone_user_specific_name ) {
					echo esc_html( $webinar_data->auto_timezone_user_specific_name );
				} else {
					?>
					<?php esc_attr_e( 'YOUR<br/>TIMEZONE', 'webinarignition' ); ?>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</span>

	<input type="hidden" id="webinar_start_date" value="<?php echo esc_html( $dateTime->format( 'Y-m-d' ) ); ?>"/>
	<input type="hidden" id="webinar_start_time" value="<?php echo esc_html( $webinar_data->auto_time_delayed ); ?>"/>
	<input type="hidden" id="timezone_user"
			value="
			<?php
			if ( 'user_specific' !== $webinar_data->delayed_timezone_type ) {
				echo esc_html( $webinar_data->auto_timezone_delayed );
			}
			?>
					">
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
	<br clear="left"/>
</div>
