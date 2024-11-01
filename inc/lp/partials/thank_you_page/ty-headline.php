<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinarId
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $instantTest
 * @var $autoDate_format
 * @var $autoTime
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 */

$prefix = 'tyHeadlineContainer-';
$uid = wp_unique_id( $prefix );
?>
<div id="<?php echo esc_attr( $uid ); ?>" class="tyHeadlineContainer tyHeadlineContainer-<?php echo esc_attr( $webinarId ); ?>">
	<div class="tyHeadlineCopy">
		<div class="optinHeadline1 wiOptinHeadline1">
			<?php webinarignition_display( $webinar_data->ty_ticket_headline, __( 'Congrats - You Are All Signed Up!', 'webinarignition' ) ); ?>
		</div>

		<div class="optinHeadline2 wiOptinHeadline2">
			<?php webinarignition_display( $webinar_data->ty_ticket_subheadline, __( 'Below is all the information you need for the webinar...', 'webinarignition' ) ); ?>
		</div>
	</div>
</div>
