<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><p>
		<?php echo esc_html__( 'Main headline', 'webinarignition' ); ?>
	</p>
	<?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php esc_html_e($webinarId); ?>" block="reg_main_headline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
	</p>
