<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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

<h4>
	<?php echo esc_html__( 'Webinar info', 'webinarignition' ); ?>
</h4>

<p>
	<?php echo esc_html__( 'Webinar Title', 'webinarignition' ); ?>: <strong><?php echo ! empty( $webinar_data->webinar_desc ) ? esc_attr( $webinar_data->webinar_desc ) : ''; ?></strong>
</p>
<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr( $webinarId ); ?>" block="global_webinar_title"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<hr>

<p>
	<?php echo esc_html__( 'Webinar Host Name', 'webinarignition' ); ?>: <strong><?php echo ! empty( $webinar_data->webinar_host ) ? esc_html( $webinar_data->webinar_host ) : ''; ?></strong>
</p>
<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr( $webinarId ); ?>" block="global_host_name"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<hr>

<p>
	<?php echo esc_html__( 'Webinar Giveaway section content', 'webinarignition' ); ?>:
</p>
<div style="border: 1px solid #ddd; background-color: #eee;padding: 5px 10px;margin-bottom: 10px;">
	<?php webinarignition_display( $webinar_data->webinar_giveaway, '<h4>' . __( 'Your Awesome Free Gift</h4><p>You can download this awesome report made you...', 'webinarignition' ) . '</p><p>[ ' . __( 'DOWNLOAD HERE', 'webinarignition' ) . ' ]</p>' ); ?>
</div>
<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr( $webinarId ); ?>" block="global_webinar_giveaway"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<hr> 

<h4>
	<?php echo esc_html__( 'Lead info', 'webinarignition' ); ?>
</h4>

<p>
	<?php echo esc_html__( 'Lead info could be only get after registration. So you should not use shortcodes below it on registration pages.', 'webinarignition' ); ?>
</p>

<p>
	<?php echo esc_html__( 'Lead Name', 'webinarignition' ); ?>: <strong><?php echo esc_html__( 'John Doe', 'webinarignition' ); ?></strong>
</p>
<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html( $webinarId ); ?>" block="global_lead_name"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<hr>

<p>
	<?php echo esc_html__( 'Lead Email', 'webinarignition' ); ?>: <strong><?php echo esc_html__( 'john.doe@maildomain.com', 'webinarignition' ); ?></strong>
</p>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html( $webinarId ); ?>" block="global_lead_email"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>
<p>
	<?php echo esc_html__( 'Footer Shortcode', 'webinarignition' ); ?>
</p>

<p class="code-example">
	<span class="code-example-value">[webinarignition_footer]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>