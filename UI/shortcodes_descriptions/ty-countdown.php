<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><p><?php esc_html_e( 'Countdown section', 'webinarignition' ); ?></p><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php esc_html_e($webinarId); ?>" block="ty_countdown"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
