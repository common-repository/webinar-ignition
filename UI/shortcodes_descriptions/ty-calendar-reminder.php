<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_calendar_reminder"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<p>
	<?php echo esc_html__( 'You can use Google caland Outlook cal reminder separately using shortcodes below', 'webinarignition' ); ?>
</p>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_calendar_reminder_google_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_calendar_reminder_outlook_inline"]</span><!--
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
