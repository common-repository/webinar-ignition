<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
?>

<div class="innerHeadline addedArrow" style="background-color: <?php echo esc_attr($webinar_data->lp_sales_headline_color ? $webinar_data->lp_sales_headline_color : '#0496AC'); ?>;">
	<span>
		<?php webinarignition_display( $webinar_data->lp_sales_headline, __( 'What You Will Learn On The Webinar...', 'webinarignition' ) ); ?>
	</span>
</div>
