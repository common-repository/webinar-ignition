<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$license_version = empty( $statusCheck->switch ) ? 'free' : $statusCheck->switch;
$is_trial        = empty( $statusCheck->is_trial ) ? false : $statusCheck->is_trial;
$plugin_basename = WebinarIgnition::webinarignition_get_plugin_basename();

$updates                = get_plugin_updates();
$update_message_visible = isset( $updates[ WebinarIgnition::webinarignition_get_plugin_basename() ] ) ? true : false;
$opacity_class          = ! isset( $statusCheck->is_registered ) || ! $statusCheck->is_registered ? 'opacity-50' : 'opacity-100';
?>

<div class="unlockTitle">
	<div class="unlockTitle3 paid-license-table-container">
		<?php if ( ! isset( $input_get['id'] ) && ! isset( $input_get['create'] ) ) { ?>
			<div style="text-align:center;">
				<h3><?php esc_html_e( 'Welcome back to WebinarIgnition', 'webinarignition' ); ?></h3>
				<?php if ( in_array( $statusCheck->switch, array( 'basic', 'pro' ), true ) && version_compare( WEBINARIGNITION_VERSION, WI_PREVIOUS_VERSION, '<=' ) && $update_message_visible ) : ?>
					<p><span class="former-license-update-message" style="text-align:center; color:white; font-size: 1rem;">
							<span><?php esc_html_e( 'Major update available.', 'webinarignition' ); ?></span>
							<a class="wi-dashboard-link" href="<?php echo esc_url( admin_url( 'plugins.php?s=' . $plugin_basename . '&plugin_status=upgrade' ) ); ?>">
								<?php esc_html_e( 'click here to update!', 'webinarignition' ); ?>
							</a>
						</span>
					</p>
				<?php endif; ?>
				<?php if ( version_compare( WEBINARIGNITION_VERSION, 3.0, '>=' ) ) : ?>
					<p>
						<?php
						$comparison_link = 'https://webinarignition.com/old-vs-new-compare/?ppt=ff21af3ca1e7609fb305e5fef6f23cf8';
						printf(
							/* translators: %1$s: version number, %2$s: comparison link URL */
							esc_html__( 'From version <strong>%1$s</strong> you get all features for unlimited time and support. <br>The only limit is the webinar registrations over all webinars per month. <a href="%2$s" target="_blank" class="wi-dashboard-link">(Details)</a>', 'webinarignition' ),
							esc_html( '3.0' ),
							esc_url( $comparison_link )
						);
						?>
					</p>
				<?php endif; ?>
				<br>
			</div>
			<table class="old_license_table license_registration_table <?php echo esc_attr( $license_version ); ?>">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Status', 'webinarignition' ); ?></th>
						<th><?php esc_html_e( 'Total Registrations', 'webinarignition' ); ?></th>
						<?php if ( 'free' === $license_version ) : ?>
							<th><?php esc_html_e( 'Attendees Webinar Time', 'webinarignition' ); ?></th>
						<?php endif; ?>
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
						<?php if ( 'free' === $license_version ) : ?>
							<td>∞</td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'Paid Ultimate', 'webinarignition' ); ?> -
							<a class="wi-dashboard-link" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing' ) ); ?>">
								<?php esc_html_e( 'get plan here', 'webinarignition' ); ?>
							</a>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="active" <?php echo checked( true, true ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>5</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>45 <?php esc_html_e( 'minutes', 'webinarignition' ); ?></td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'By activating the plugin', 'webinarignition' ); ?>
						</td>
					</tr>
					<tr class="<?php echo esc_attr( $opacity_class ); ?>">
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo true === $statusCheck->is_registered ? 'active' : 'inactive'; ?>" <?php echo checked( true, $statusCheck->is_registered ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>25</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>45 <?php esc_html_e( 'minutes', 'webinarignition' ); ?></td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'By Opt in and confirm email', 'webinarignition' ); ?>
							<?php if ( ! $statusCheck->is_registered ) : ?>

								<a class="wi-dashboard-link" href="<?php echo esc_url( $statusCheck->reconnect_url ); ?>">
									<?php esc_html_e( 'Click here', 'webinarignition' ); ?>
								</a>
							<?php endif; ?>
						</td>
					</tr>
					<tr class="<?php echo esc_attr( $opacity_class ); ?>">
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo 'basic' === $license_version && $statusCheck->is_registered ? 'active' : 'inactive'; ?>" disabled <?php echo checked( true, 'basic' === $license_version && $statusCheck->is_registered ); ?>>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>100</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>∞</td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'By Opt in and activating your BASIC license', 'webinarignition' ); ?>
							<?php if ( 'free' === $license_version ) : ?>
								<a class="wi-dashboard-link" href="<?php echo esc_attr( $statusCheck->upgrade_url ); ?>">
									<?php esc_html_e( 'Activate here', 'webinarignition' ); ?>
								</a>
							<?php endif; ?>
						</td>
					</tr>
					<tr class="<?php echo esc_attr( $opacity_class ); ?>">
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo 'pro' === $license_version && $statusCheck->is_registered ? 'active' : 'inactive'; ?>" <?php echo checked( true, 'pro' === $license_version && $statusCheck->is_registered ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>200</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>∞</td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'By Opt in and activating your PREMIUM/ENTERPRISE license', 'webinarignition' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo true === $is_trial ? 'active' : 'inactive'; ?>" <?php echo checked( true, $is_trial ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>500</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>45 <?php esc_html_e( 'minutes', 'webinarignition' ); ?></td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'By start 14 days trial', 'webinarignition' ); ?>
							<?php if ( ! isset( $statusCheck->is_trial ) || false === $statusCheck->is_trial ) : ?>
								<a class="wi-dashboard-link" href="<?php echo esc_attr( $statusCheck->trial_url ); ?>&plan_name=ultimate_powerup&checkout=true"><?php esc_html_e( 'Start here', 'webinarignition' ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
					<tr class="<?php echo esc_attr( $opacity_class ); ?>">
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo WebinarignitionLicense::webinarignition_is_branding_enable_and_valid() ? 'active' : 'inactive'; ?>" <?php echo checked( true, WebinarignitionLicense::webinarignition_is_branding_enable_and_valid() ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>500</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>45 <?php esc_html_e( 'minutes', 'webinarignition' ); ?></td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'By Opt-in and showing branding', 'webinarignition' ); ?>-
							<a class="wi-dashboard-link" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&action=toggle_branding' ) ); ?>"><?php esc_html_e( 'Enable/Disable here', 'webinarignition' ); ?></a>
							<a target="_blank" class="wi-dashboard-link" href="https://webinarignition.tawk.help/article/save-earn-with-a-branded-webinarignition-plugin">( <?php esc_html_e( 'Earn money', 'webinarignition' ); ?>)</a>
						</td>
					</tr>
				</tbody>
			</table>
			<?php require WEBINARIGNITION_PATH . 'UI/opt-in/essential-plan.php'; ?>	
			<?php if ( version_compare( WEBINARIGNITION_VERSION, 3.0, '>=' ) ) : ?>
				<div class="formar_license_form_message" style="color: white; padding: 15px; text-align: center;">
					<div class="webinarignition_message" style="font-size:16px;">
						<?php
						$crm_link = 'https://webinarignition.com/?fluentcrm=1&route=smart_url&slug=bdtsr27';

						$previous_version = sprintf(
							'<a class="wi-dashboard-link" href="%1$s" target="_blank">%2$s</a>',
							esc_url( $crm_link ),
							esc_html__( 'install this version', 'webinarignition' )
						);

						$version = sprintf( '<a class="wi-dashboard-link" href="%s" target="_blank">%s</a>', $crm_link, esc_html__( 'version', 'webinarignition' ) );

						printf(
							wp_kses(
								/* translators: %s: link to install previous version */
								__( '<strong>If you like to go back to unlimited registrations and downgrade to bought license level features, %s</strong>. Limited support on versions 1.x / 2.x maintained 2013 until April 2023!', 'webinarignition' ),
								array( 'strong' => array() )
							),
							wp_kses( $previous_version, array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) ) )
						);
						?>
					</div>
				</div>
			<?php endif; ?>
			<div class="former-license-download-message" style="text-align:center; color: white; padding: 15px;">
				<span class="message"><?php esc_html_e( 'Some licenses got unlimited activations/keys. If you run out of keys,', 'webinarignition' ); ?></span>
				<a class="wi-dashboard-link" target="_blank" href="mailto:support@webinarignition.com?subject=Generate more license keys please&body=Please add your name and the email address you bought the license"><?php esc_html_e( 'write us.', 'webinarignition' ); ?></a>
			</div>
			<?php
		}//end if
		?>
		<?php webinarignition_display_manage_license_form( $statusCheck ); ?>
	</div>
</div>