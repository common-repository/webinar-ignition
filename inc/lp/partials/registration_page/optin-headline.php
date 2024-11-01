<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
$prefix = 'optinHeadline-';
$uid = wp_unique_id( $prefix );

// Only get the required input values
$input_get = [
    'payment' => filter_input(INPUT_GET, 'payment', FILTER_UNSAFE_RAW),
];
?>
<div class="optinHeadline wiOptinHeadline <?php echo esc_attr( $uid ); ?>">
		<?php

		ob_start();

		if ( ( ! empty( $webinar_data->latecomer ) ) && ( ! empty( $webinar_data->latecomer_registration_copy ) ) ) { ?>
		<div id="latecomer_copy"><?php echo wp_kses_post( $webinar_data->latecomer_registration_copy ); ?></div>
		<?php } else { ?>
		<div class="optinHeadline1 wiOptinHeadline1"><?php echo esc_html__( 'RESERVE YOUR SPOT!', 'webinarignition' ); ?></div>
		<div class="optinHeadline2 wiOptinHeadline2"><?php echo esc_html__( 'WEBINAR REGISTRATION', 'webinarignition' ); ?></div>
		<?php }

		if ( isset( $input_get['payment'] ) && 'success' === $input_get['payment'] ) {
			?>
			<div class="optinHeadline2 wiOptinHeadline2"><?php echo esc_html__( 'Payment Success!', 'webinarignition' ); ?></div>
			<p>
				<?php echo esc_html__( 'Please finalize your registration by filling out the form below:', 'webinarignition' ); ?>
			</p>
			<?php
		}

		$displayReserveSpot = ob_get_clean();

		webinarignition_display(
			$webinar_data->lp_optin_headline,
			$displayReserveSpot
		);
		?>
	</div>