<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><hr><p>
		<?php esc_html_e( 'Main Sales Copy', 'webinarignition' ); ?>
	</p>

	<div style="border: 1px solid #ddd; background-color: #eee;padding: 5px 10px;margin-bottom: 10px;">
		<?php
		webinarignition_display(
			do_shortcode( $webinar_data->lp_sales_copy ),
			'<p>' .esc_html_e( 'Your Amazing sales copy for your webinar would show up here...', 'webinarignition' ) . '</p>'
		);
		?>
	</div>
	<?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php esc_html_e($webinarId); ?>" block="reg_sales_copy"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
