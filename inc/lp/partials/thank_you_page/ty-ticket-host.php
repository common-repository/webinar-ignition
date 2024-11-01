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

$prefix = 'tyTicketHost-';
$uid    = wp_unique_id( $prefix );
?>

<div id="<?php echo esc_attr( $uid ); ?>" class="tyTicketHost tyTicketHost-<?php echo esc_attr( $webinarId ); ?> ticketSection ticketSectionNew">
	<?php
	if ( 'custom' === $webinar_data->ty_ticket_host_option ) {
		?>
		<div class="tyTicketInfoContainer tyTicketInfoContainerHost">
			<div class="tyTicketInfoCopy">
				<b><?php webinarignition_display( $webinar_data->ty_ticket_host, __( 'Host', 'webinarignition' ) ); ?></b>
				<div class="tyTicketInfoNewHeadline">
					<?php webinarignition_display( $webinar_data->ty_webinar_option_custom_host, __( 'Your Name Here', 'webinarignition' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="tyTicketInfoContainer tyTicketInfoContainerHost">
			<div class="tyTicketInfoCopy">
				<b><?php esc_html_e( 'Host', 'webinarignition' ); ?>:</b>
				<div class="tyTicketInfoNewHeadline">
					<?php webinarignition_display( $webinar_data->webinar_host, __( 'Host name', 'webinarignition' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}//end if
	?>
</div>
