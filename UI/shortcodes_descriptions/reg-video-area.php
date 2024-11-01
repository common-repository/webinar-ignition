<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><hr><p>
		<?php esc_html_e( 'CTA Area - Video / Image Settings', 'webinarignition' ); ?>
	</p><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php esc_html_e($webinarId); ?>" block="reg_video_area" custom_video_url="" border="false"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
