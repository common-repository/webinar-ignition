<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$license_version = empty( $statusCheck->switch ) ? 'free' : $statusCheck->switch;
$is_trial        = ( isset( $statusCheck->is_trial ) && ! empty( $statusCheck->is_trial ) ) ? $statusCheck->is_trial : false;
$opacity_class   = ! isset( $statusCheck->is_registered ) || ! $statusCheck->is_registered ? 'opacity-50' : 'opacity-100';
?>

<div class="unlockTitle<?php echo ! isset( $input_get['id'] ) && ! isset( $input_get['create'] ) ? '' : ' noBorder'; ?>">
	<?php if ( ! isset( $input_get['id'] ) && ! isset( $input_get['create'] ) ) : ?>
		<div class="unlockTitle3 free-license-table-container">
			<br>
			<table class="free_license_table license_registration_table <?php echo esc_attr( $license_version ); ?>">
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
								<input type="checkbox" class="<?php echo ! empty( $is_trial ) ? 'active' : 'inactive'; ?>" <?php echo checked( true, ! empty( $is_trial ) ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>250</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>45 <?php esc_html_e( 'minutes', 'webinarignition' ); ?></td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'Start 14 days FREE trial ', 'webinarignition' ); ?>-
							<a class="wi-dashboard-link"  href="<?php echo esc_attr( $statusCheck->trial_url ); ?>" ><?php esc_html_e( 'start here', 'webinarignition' ); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<label class="license-table-checkbox-wrap">
								<input type="checkbox" class="<?php echo 'enterprise_powerup' === $license_version ? 'active' : 'inactive'; ?>" value="enterprise_powerup" <?php echo checked( 'enterprise_powerup', $license_version ); ?> disabled>
								<span class="geekmark"></span>
							</label>
						</td>
						<td>∞</td>
						<?php if ( 'free' === $license_version ) : ?>
							<td>∞</td>
						<?php endif; ?>
						<td>
							<?php esc_html_e( 'Paid Ultimate', 'webinarignition' ); ?>-
							<a class="wi-dashboard-link" href="<?php echo esc_attr( $statusCheck->upgrade_url ); ?>">
								<?php esc_html_e( 'get plan here', 'webinarignition' ); ?>
							</a>
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
