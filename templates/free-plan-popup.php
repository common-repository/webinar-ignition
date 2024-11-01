<?php defined( 'ABSPATH' ) || exit; ?>

<div class="webinarignition-free-plan-popup-on-saving" id="webinarignition-free-plan-popup-on-saving">
	<div class="webinarignition-free-plan-popup-wrap">
		<h2><?php esc_html_e( 'If you save, you will lose your lead send settings/connection!', 'webinarignition' ); ?></h2>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing' ) ); ?>">
			<?php esc_html_e( 'Upgrade license and download premium code to continue with all features!', 'webinarignition' ); ?>
		</a>
		<div class="webinarignition-popup-buttons">
			<button id="cancel-plan"><?php esc_html_e( 'Cancel Saving', 'webinarignition' ); ?></button>
			<button id="go-ahead-with-free-plan"><?php esc_html_e( 'Continue Saving', 'webinarignition' ); ?></button>
		</div>
	</div>
</div>