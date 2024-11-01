<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$license_version = empty( $statusCheck->switch ) ? 'free' : $statusCheck->switch;
$is_trial        = ( isset( $statusCheck->is_trial ) && ! empty( $statusCheck->is_trial ) ) ? $statusCheck->is_trial : false;

?>

<div class="unlockTitle<?php echo ! isset( $input_get['id'] ) && ! isset( $input_get['create'] ) ? '' : ' noBorder'; ?>">

	<?php
	if ( ! isset( $input_get['id'] ) && ! isset( $input_get['create'] ) ) {
		?>
		<div class="unlockTitle3 old-license-table-container">
			<table class="old_license_table license_registration_table <?php echo esc_attr( $license_version ); ?>">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Status', 'webinarignition' ); ?></th>
						<th><?php esc_html_e( 'Total Registrations', 'webinarignition' ); ?></th>
						<th><?php esc_html_e( 'Requirements', 'webinarignition' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo 'enterprise_powerup' === $license_version ? 'active' : 'inactive'; ?>" <?php echo checked( 'enterprise_powerup', $license_version ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>∞</td>
						<td>
							<?php esc_html_e( 'Paid Ultimate', 'webinarignition' ); ?>-
							<a class="wi-dashboard-link" href="<?php echo esc_attr( $statusCheck->upgrade_url ); ?>"><?php esc_html_e( 'get plan here', 'webinarignition' ); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo true === $statusCheck->is_free ? 'active' : 'inactive'; ?>" <?php echo checked( true, $statusCheck->is_free ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>5</td>
						<td>
							<?php esc_html_e( 'By activating the plugin', 'webinarignition' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo wp_validate_boolean( $statusCheck->is_registered ) ? 'active' : 'inactive'; ?>" <?php echo checked( true, $statusCheck->is_registered ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>25</td>
						<td>
							<?php esc_html_e( 'By Opt in and confirm email', 'webinarignition' ); ?>
							<?php if ( ! $statusCheck->is_registered ) : ?>
								<a class="wi-dashboard-link" href="<?php echo esc_url( $statusCheck->reconnect_url ); ?>">
									<?php esc_html_e( 'Click here', 'webinarignition' ); ?>
								</a>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo 'basic' === $license_version && $statusCheck->is_registered ? 'active' : 'inactive'; ?>" disabled <?php echo checked( 'basic', $license_version ); ?>>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>100</td>
						<td>
							<?php esc_html_e( 'By Opt in and activating your BASIC license', 'webinarignition' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo 'pro' === $license_version && $statusCheck->is_registered ? 'active' : 'inactive'; ?>" <?php echo checked( 'pro', $license_version ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>200</td>
						<td>
							<?php esc_html_e( 'By Opt in and activating your PREMIUM/ENTERPRISE license', 'webinarignition' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo ! empty( $is_trial ) ? 'active' : 'inactive'; ?>" <?php echo checked( true, ! empty( $is_trial ) ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>250</td>
						<td>
							<?php esc_html_e( 'By Opt-in and start 14 days trial', 'webinarignition' ); ?>-
							<a class="wi-dashboard-link" href="<?php echo esc_attr( $statusCheck->trial_url ); ?>&plan_name=ultimate_powerup&checkout=true"><?php esc_html_e( 'start here', 'webinarignition' ); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo WebinarignitionLicense::webinarignition_is_branding_enable_and_valid() ? 'active' : 'inactive'; ?>" <?php echo checked( true, WebinarignitionLicense::webinarignition_is_branding_enable_and_valid() ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>500</td>
						<td>
							<?php esc_html_e( 'By Opt-in and showing branding', 'webinarignition' ); ?>-
							<a class="wi-dashboard-link" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&action=toggle_branding' ) ); ?>"><?php esc_html_e( 'Enable/Disable here', 'webinarignition' ); ?></a>
							<a target="_blank" class="wi-dashboard-link" href="https://webinarignition.tawk.help/article/save-earn-with-a-branded-webinarignition-plugin">( <?php esc_html_e( 'Earn money', 'webinarignition' ); ?>)</a>
						</td>
					</tr>
				</tbody>
			</table>
			<?php if ( ! isset( $statusCheck->is_registered ) || ! $statusCheck->is_registered ) : ?>
				<div class="opt-in-popup">
					<a class="btn btn-primary btn-orange popup-btn-opt-in" href="<?php echo esc_url( $statusCheck->reconnect_url ); ?>">
						<span class="dashicons dashicons-arrow-right-alt"></span>
						Opt-In
					</a>
					<p>
						<?php esc_html_e( 'For more free registrations, License Options', 'webinarignition' ); ?>
					</p>
				</div>
			<?php endif; ?>
			<?php if ( version_compare( WEBINARIGNITION_VERSION, 3.0, '>=' ) ) : ?>
				<div class="formar_license_form_message" style="color: white; padding: 15px; text-align: center;">
					<div class="webinarignition_message" style="font-size:16px;">
						<?php
						$crm_link = 'https://webinarignition.com/?fluentcrm=1&route=smart_url&slug=bdtsr27';

						$previous_version = sprintf( '<a class="wi-dashboard-link" href="%s" target="_blank">%s</a>', $crm_link, esc_html__( 'install this version', 'webinarignition' ) );

						$version = sprintf( '<a class="wi-dashboard-link" href="%s" target="_blank">%s</a>', $crm_link, esc_html__( 'version', 'webinarignition' ) );

						printf( ( '<strong>If you like to go back to unlimited registrations and downgrade to bought license level features, %s</strong>. Limited support on versions 1.x / 2.x maintained 2013 until April 2023! ' ), esc_attr( $previous_version ) );
						?>
					</div>
				</div>
			<?php endif; ?>
			<div class="former-license-download-message" style="text-align:center; color: white; padding: 15px;">
				<span class="message"><?php esc_html_e( 'Some licenses got unlimited activations/keys. If you run out of keys,', 'webinarignition' ); ?></span>
				<a class="wi-dashboard-link" target="_blank" href="mailto:support@webinarignition.com?subject=Generate more license keys please&body=Please add your name and the email address you bought the license"><?php esc_html_e( 'write us.', 'webinarignition' ); ?></a>
			</div>
		</div>
		<?php
	}//end if
	?>
</div>
<?php
if ( ! isset( $input_get['id'] ) && ! isset( $input_get['create'] ) && 'free' === $license_version ) {
	webinarignition_display_manage_license_form( $statusCheck );
}