<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?>
	<p>
	<?php esc_html_e( 'Main Headline and Ticket Sub Headline', 'webinarignition' ); ?>:
	</p>

	<div class="wi-congrats" ><h2 ><?php webinarignition_display( $webinar_data->ty_ticket_headline, __( 'Congrats - You Are All Signed Up!', 'webinarignition' ) ); ?></h2>
	<h3 style=""><?php webinarignition_display( $webinar_data->ty_ticket_subheadline, __( 'Below is all the information you need for the webinar...', 'webinarignition' ) ); ?></h3></div><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php esc_html_e($webinarId); ?>" block="ty_headline"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
<?php
if ( ! empty( $is_list ) ) {
	?>
	<hr><?php
} else {
	?><?php
}
?>
