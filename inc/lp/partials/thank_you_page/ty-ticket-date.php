<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $instantTest
 * @var $autoDate_format
 * @var $autoTime
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 */
?>

<div class="eventDate" <?php echo esc_attr( $instantTest ); ?>>
	<div class="dateIcon">
		<div class="dateMonth">
		<?php echo esc_html( webinarignition_get_localized_week_day( $webinar_data, $lead ) ); ?> 
		</div>
		<div class="dateDay">
			<?php
				echo esc_html( webinarignition_get_live_date_day( $webinar_data, $lead ) );
				echo ( substr( get_locale(), 0, 2 ) === 'en' ) ? '' : '.';
			?>
		</div>
		
		<div class="dateDayWeek">
			<?php echo esc_html( webinarignition_get_locale_month( $webinar_data, $lead ) ); ?>
		</div>        
		
	</div>

	<div class="dateInfo">
		<div class="dateHeadline">
			<?php echo esc_html( webinarignition_get_localized_date( $webinar_data, $lead ) ); ?>
		</div>
		<div class="dateSubHeadline">
			<?php echo esc_html( $webinar_data->lp_webinar_subheadline ? $webinar_data->lp_webinar_subheadline : esc_html__( 'At', 'webinarignition' ) . ' ' . esc_html( $autoTime ) ); ?>
		</div>
	</div>

	<br clear="left">
</div>
