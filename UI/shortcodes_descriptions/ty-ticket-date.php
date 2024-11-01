<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><h4><?php echo esc_html__( 'Selected Date / Time', 'webinarignition' ); ?></h4><?php
} else {
	?><?php
}
if ( ! empty( $is_list ) ) {
	?><p><?php echo esc_html__( 'Selected Date / Time section', 'webinarignition' ); ?></p><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_ticket_date"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<?php
if ( ! empty( $is_list ) ) {
	?><p><?php echo esc_html__( 'InlineSelected Date', 'webinarignition' ); ?></p><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_date_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<?php
if ( ! empty( $is_list ) ) {
	?><p><?php echo esc_html__( 'InlineSelected Time', 'webinarignition' ); ?></p><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_time_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
<?php
if ( $webinar_data->webinar_date !== 'AUTO' ) {
	if ( ! empty( $is_list ) ) {
		?><p><?php echo esc_html__( 'InlineSelected Timezone (works only for live webinars)', 'webinarignition' ); ?></p><?php
	} else {
		?><?php
	}
	?>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="ty_timezone_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
	</p>
	<?php
}

if ( ! empty( $is_list ) ) {
	?>
	<hr><?php
} else {
	?><?php
}
?>
