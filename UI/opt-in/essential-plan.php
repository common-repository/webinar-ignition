<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if (! isset($statusCheck->is_pending_activation) || (! $statusCheck->is_registered &&  ! wp_validate_boolean($statusCheck->is_pending_activation))) : ?>

	<div class="opt-in-popup" id="opt-in-box">
		<div class=" popup-btn-opt-in wi-optin-wrap">


			<div class="wi-content-wrap">
				<img src="<?php echo esc_url(WEBINARIGNITION_URL . 'images/optin.png'); ?>" />

				<div class="wi-desc-wrap">
					<h4><?php esc_attr_e('Opt-in 4 success', 'webinarignition'); ?></h4>


					<?php
					$current_user_id = get_current_user_id();
					$user_data       = get_userdata($current_user_id);
					$profile_email   = $user_data->user_email;

					$profile_email_link = '<a href="' . esc_url($statusCheck->reconnect_url) . '">' . esc_attr($profile_email) . '</a>';
					$change_mail_link = '<a href="' . esc_url(admin_url('profile.php')) . '">' . esc_attr__('Change Mail', 'webinarignition') . '</a>';




					?>




					<p>
						<?php
						echo sprintf(
							/* translators: %1$s: Profile email link, %2$s: Change email link */
							esc_html__('Subscribe now and be the first to find out about our latest offers and features. You will be opted in with %1$s or %2$s.', 'webinarignition'),
							esc_url($profile_email_link),
							esc_url($change_mail_link)
						);
						?>
					</p>


					<div class="wi-btn-wrap">
						<a href="<?php echo esc_url($statusCheck->reconnect_url); ?>">
							<?php echo esc_attr($profile_email); ?>
						</a>


					</div>


				</div>
			</div>



		</div>
	</div>
<?php endif; ?>

<?php if (isset($statusCheck->is_pending_activation) && wp_validate_boolean($statusCheck->is_pending_activation)) : ?>
	<?php if (get_option('wi_optin_confirmed', false) === false) : ?>
		<?php
		$current_dash_user = wp_get_current_user();
		$admin_email  = ($current_dash_user instanceof WP_User) ? $current_dash_user->user_email : null;
		?>
		<div class="opt-in-popup" id="opt-in-box">
			<a class="btn popup-btn-opt-in wi_non_clickable wi-confirmation-msg red-notification">
				<span class="dashicons dashicons-arrow-right-alt"></span>


				<?php
				$admin_email = esc_html($admin_email);

				$message = sprintf(
					/* translators: %s: Admin email address */
					__('Thanks! You should receive a confirmation email for <b>WebinarIgnition</b> to your mailbox at %s. Please make sure you click the button in that email to complete the opt-in.', 'webinarignition'),
					$admin_email
				);

				echo wp_kses_post($message);
				?>
			</a>

			<p class="btn btn-orange popup-btn-opt-in wi-i-confirmed">
				<a href="<?php echo esc_attr(add_query_arg('i_confirmed', 'true', admin_url('admin.php?page=webinarignition-dashboard'))); ?>">
					<!-- work77 -->
					<?php esc_attr_e('Skip Confirmation', 'webinarignition'); ?>
				</a>
			</p>
		</div>
	<?php endif; ?>

	<?php if (get_option('wi_optin_confirmed', false) === true && get_option('wi_support_confirmed', false) === false) : ?>
		<div class="opt-in-popup" id="opt-in-box">
			<a class="btn popup-btn-opt-in wi_non_clickable wi-confirmation-msg wi-blue-bg">
				<span class="dashicons dashicons-arrow-right-alt"></span>
				<?php esc_attr_e('Please add Support@webinarignition.com to your whitelist and address book so the success tutorials will reach you safely.', 'webinarignition'); ?>
			</a>

			<p class="btn btn-orange popup-btn-opt-in wi-i-confirmed">
				<a href="<?php echo esc_attr(add_query_arg('wi_support_confirmed', 'true', admin_url('admin.php?page=webinarignition-dashboard'))); ?>">
					<!-- work77 -->
					<?php esc_attr_e('Done', 'webinarignition'); ?>
				</a>
			</p>
		</div>
	<?php endif; ?>

<?php endif; ?>