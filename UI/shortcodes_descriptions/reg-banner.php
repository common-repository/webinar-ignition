<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = esc_attr( $webinar_data->id );

if ( ! empty( $is_list ) ) {
	?><p>
	<?php echo esc_html__( 'This shortcode display webinar banner image', 'webinarignition' ); ?>
	</p><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr( $webinarId ); ?>" block="reg_banner"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
<?php
if ( ! empty( $is_list ) ) {
	?>
	<hr><?php
} else {
	?><?php
}
?>
