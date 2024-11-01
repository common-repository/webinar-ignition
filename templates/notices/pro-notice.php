<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ultimate-container">
	<div class="ultimate-below-sec">
		<div class="ultimate-graphic">
			<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/logo-avatar.png' ); ?>" alt="Unlock potential">
			<div class="ultimate-below-container">
				<div class="ultimate-lock-text-cont"><img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/padlock.png' ); ?>" /></div>
				<div class="ultimate-text-container"><?php esc_html_e( 'Ultimate', 'webinarignition' ); ?></div>
			</div>
		</div>
		<div class="ultimate-content">
			<h2><?php esc_html_e( 'Upgrade to Ultimate Version and Unleash Your Potential', 'webinarignition' ); ?></h2>
			<p><?php esc_html_e( 'Get the features you are missing to collect, see, send leads, cut alignment and to create paid webinars and much more!', 'webinarignition' ); ?></p>
			</br>
			<a href="<?php echo esc_url( $upgrade_link ); ?>" class="ultimate-button"><?php echo esc_html__( 'Go Ultimate', 'webinarignition' ); ?></a>
		</div>
	</div>
</div>