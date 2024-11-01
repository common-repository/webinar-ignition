<?php
/**
 * @var $webinar_data
 * @var $uid
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 * @var $autoDate_format
 * @var $autoTime
 * @var $is_compact
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="eventDate <?php echo esc_attr( $uid ); ?>">
	<div class="dateIcon">
		<div class="dateMonth">
			<?php echo esc_html( $localized_month ); ?>
		</div>
		<div class="dateDay">
			<?php
			echo esc_html( $webinarDateObject->format( 'd' ) );
			echo ( 'en' === substr( get_locale(), 0, 2 ) ) ? '' : '.';
			?>
		</div>

			<div class="dateDayWeek">
				<?php echo esc_html( $localized_week_day ); ?>
			</div>
	</div>

	<?php
	if ( ! $is_compact ) {
		?>
		<div class="dateInfo">
			<div class="dateHeadline"><?php echo esc_html( $localized_date ); ?></div>
			<div class="dateSubHeadline">
				<?php
				if ( $webinar_data->lp_webinar_subheadline ) {
					echo esc_html( $webinar_data->lp_webinar_subheadline );
				} else {
					echo esc_html__( 'At', 'webinarignition' ) . ' ' . esc_html( gmdate( $time_format, strtotime( $webinar_data->webinar_start_time ) ) );
				}
				?>
			</div>
		</div>
		<?php
	}
	?>
	<br clear="left"/>
</div>
