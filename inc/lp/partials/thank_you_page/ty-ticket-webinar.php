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

$prefix = 'tyTicketWebinar-';
$uid = wp_unique_id( $prefix );
?>

<div id="<?php echo esc_attr( $uid ); ?>" class="tyTicketWebinar tyTicketWebinar-<?php echo esc_attr( $webinarId ); ?> ticketSection ticketSectionNew">
	<!-- <i class="icon-desktop"></i> -->
	<?php
	if ( 'custom' === $webinar_data->ty_ticket_webinar_option ) {
		?>
		<div class="tyTicketInfoContainer tyTicketInfoContainerWebinar">
			<div class="tyTicketInfoCopy">
				<b><?php webinarignition_display( $webinar_data->ty_ticket_webinar, __( 'Webinar', 'webinarignition' ) ); ?></b>
				<div class="tyTicketInfoNewHeadline">
					<?php webinarignition_display( $webinar_data->ty_webinar_option_custom_title, __( 'Webinar Event Title', 'webinarignition' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="tyTicketInfoContainer tyTicketInfoContainerWebinar">
			<div class="tyTicketInfoCopy">
				<b><?php esc_html_e( 'Webinar:', 'webinarignition' ); ?></b>
				<div class="tyTicketInfoNewHeadline">
					<?php webinarignition_display( $webinar_data->webinar_desc, __( 'Webinar Event Title', 'webinarignition' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}//end if
	?>
</div>
