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

<hr>
<h4>
	<?php echo esc_html__( 'Registration form', 'webinarignition' ); ?>
</h4>
<p>
	<?php echo esc_html__( 'This shortcode will show optin section with webinar dates selection and registration form together in one column', 'webinarignition' ); ?>
</p>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_optin_section" readonly="false"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<p>
	<?php echo esc_html__( 'If you need to show dates selection and registration form in different page blocks, you need to use both shortcodes below', 'webinarignition' ); ?>
</p>

<p><?php echo esc_html__( 'Webinar dates selection', 'webinarignition' ); ?></p>
<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_optin_dates"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<p><?php echo esc_html__( 'Optin form fields', 'webinarignition' ); ?></p>
<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_optin_form" readonly="false"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<p>
	<?php echo esc_html__( 'If you want to show dates selection and optin fields without heading, you can use compact views below', 'webinarignition' ); ?>
</p>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_optin_dates_compact"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_optin_form_compact" readonly="false"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
</p>


<?php
if ( $webinar_data->webinar_date !== 'AUTO' ) {
	?>
	<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_date_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
	</p>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_time_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
	</p>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html__( $webinarId ); ?>" block="reg_timezone_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'Copy', 'webinarignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinarignition' ); ?></span>
	</p>
	<?php
}//end if
?>
