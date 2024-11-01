<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="webinar_welcome_user_video_parent_container">
	<div class="webinar_welcome_left_container">
		<div class="webinar_welcome_user_container webinar_dashboard_box_design">
			<?php
			$current_user_data = wp_get_current_user();
			$current_username  = $current_user_data->display_name;
			?>
			<p class="webinar_welcom_user_text"><?php echo esc_html__( 'Hello', 'webinarignition' ) . ' ' . esc_html( $current_username ); ?>,</p>
			<h2><?php esc_html_e( 'Welcome to WebinarIgnition', 'webinarignition' ); ?></h2>
			<p><?php esc_html_e( 'WebinarIgnition offers a variety of pre-built webinar room designs that are modern, professional, and engaging. These designs are easy to customize with your own branding, and they can be used for a variety of different types of webinars, including lead generation webinars, customer education webinars, and product demos.', 'webinarignition' ); ?></p>
			<a class="blue-btn-2 btn newWebinarBTN" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&create' ) ); ?>">
				<i class="icon-plus-sign" style="margin-right: 5px;"></i>
				<?php esc_html_e( 'Create a New Webinar', 'webinarignition' ); ?>
			</a>
		</div>
		<div class="webinar_dashboard_box_design wi_kb_links_container">
			<h3 class="benefits_heading"><?php esc_html_e( 'Benefits, KB link', 'webinarignition' ); ?></h3>
			<div class="webinar_sent_leads_main_container">
				<div class="webinar_sent_leads">
					<div class="webinar_leads_description">
						<h4><?php esc_html_e( 'Auto Webinar - Setting Up An Evergreen Webinar', 'webinarignition' ); ?></h4>

						<p><?php esc_html_e( 'This video will show you how to setup an auto webinar and how it differs from a live webinar...', 'webinarignition' ); ?></p>

						<a href="https://webinarignition.tawk.help/article/auto-webinar-setting-up-an-evergreen-webinar" target="_blank"><?php esc_html_e( 'Knowledge base', 'webinarignition' ); ?></a>
					</div>
					<div class="webinar_leads_description_video">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/8aPPvmEa2Gs?si=b-S_zz4OH1PNBUV5" title="<?php esc_attr_e( 'YouTube video player', 'webinarignition' ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
					</div>
				</div>
				<div class="webinar_sent_leads">
					<div class="webinar_leads_description">
						<h4><?php esc_html_e( 'Dashboard - Getting Familiar With The Core Dashboard', 'webinarignition' ); ?></h4>

						<p><?php esc_html_e( 'This video will cover all the information of what the dashboard tells you about the webinar campaign...', 'webinarignition' ); ?></p>

						<a href="https://webinarignition.tawk.help/article/dashboard-getting-familiar-with-the-core-dashboard" target="_blank"><?php esc_html_e( 'Knowledge base', 'webinarignition' ); ?></a>
					</div>
					<div class="webinar_leads_description_video">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/eMIpO1P_jJI?si=M2tsx4KEGseWXGg7" title="<?php esc_attr_e( 'YouTube video player', 'webinarignition' ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
					</div>
				</div>
				<div class="webinar_sent_leads">
					<div class="webinar_leads_description">
						<h4><?php esc_html_e( 'Auto Webinar Video Format & reduce file size', 'webinarignition' ); ?></h4>

						<p><?php esc_html_e( 'If you would like to convert your youtube video to a file, try clipconverter.', 'webinarignition' ); ?></p>

						<a href="https://webinarignition.tawk.help/article/auto-webinar-video-format-html5-video-formats" target="_blank"><?php esc_html_e( 'Knowledge base', 'webinarignition' ); ?></a>
					</div>
					<div class="webinar_leads_description_video">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/RW0QNpAXEUE?si=PZ50IXVFS627AWe4" title="<?php esc_attr_e( 'YouTube video player', 'webinarignition' ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
					</div>
				</div>
				<div class="webinar_sent_leads">
					<div class="webinar_leads_description">
						<h4><?php esc_html_e( 'Click Registration (Live webinars)', 'webinarignition' ); ?></h4>

						<p><?php esc_html_e( 'This video will show you how to allow users to register for your webinar with one-click of your link. No need to enter name and email details from the registration page.', 'webinarignition' ); ?></p>

						<a href="https://webinarignition.tawk.help/article/1-click-registration"><?php esc_html_e( 'Knowledge base', 'webinarignition' ); ?></a>
					</div>
					<div class="webinar_leads_description_video">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/zp7YH26g3Fo?si=GJPYg2HLVHTSJBcB" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			</div>

		</div>
		<?php if ( ! Webinar_Optin::webinarignition_is_pro( $statusCheck ) ) : ?>
			<div class="webinar_comparison_chart webinar_dashboard_box_design wi-comparison-chart">
				<div class="wi-content">
					<h2><?php esc_html_e( 'WebinarIgnition Comparison Chart', 'webinarignition' ); ?></h2>
					<div class="wi-table-container">
						<table>
							<thead>
								<tr>
									<th><?php esc_html_e( 'Price Plans', 'webinarignition' ); ?></th>
									<th><?php esc_html_e( 'Free', 'webinarignition' ); ?> <span>$0</span></th>
									<th><?php esc_html_e( 'Essential', 'webinarignition' ); ?> <span>$9.99</span></th>
									<th><?php esc_html_e( 'Unlimited', 'webinarignition' ); ?> <span>$48.99</span></th>
									<th><?php esc_html_e( 'Unlimited Plus', 'webinarignition' ); ?> <span>$96.99</span></th>
								</tr>
							</thead>
							<tbody>
								
								<tr>
									<td class="webinar_table_col_one"><?php esc_html_e( 'Webhooks', 'webinarignition' ); ?></td>
								
									<td>✗</td>

									<td>✗</td>
									<td>✔</td>

									<td>✔</td>

								
								</tr>
								<tr>
									<td class="webinar_table_col_one"><?php esc_html_e( 'support', 'webinarignition' ); ?></td>


									<td><?php esc_html_e( 'ticket / email', 'webinarignition' ); ?></td>
									<td><?php esc_html_e( 'ticket / email', 'webinarignition' ); ?></td>

									<td><?php esc_html_e( 'Consultation', 'webinarignition' ); ?></td>
									<td><?php esc_html_e( '1:1 support', 'webinarignition' ); ?></td>
									

								</tr>
								<tr>
									<td class="webinar_table_col_one"><?php esc_html_e( 'leads', 'webinarignition' ); ?></td>

									<td>✗</td>
									<td>✔</td>

									<td>✔</td>
									<td>✔</td>

								</tr>
								<tr>
									<td class="webinar_table_col_one"><?php esc_html_e( 'Paid Webinar', 'webinarignition' ); ?></td>
									<td>✗</td>
									<td>✗</td>

									<td>✔</td>
									<td>✔</td>
								</tr>
								<tr>
									<td class="webinar_table_col_one"><?php esc_html_e( 'CTA alignment', 'webinarignition' ); ?></td>
									<td>✗</td>
									<td>✔</td>
									<td>✔</td>
									<td>✔</td>
								</tr>
								<tr>
									<td class="webinar_table_col_one"><?php esc_html_e( 'Licenses', 'webinarignition' ); ?></td>
									<td>1</td>
									<td>1</td>
									<td>1</td>
									<td>3</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td>
										<a class="webinarignition-buy-now-button" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing' ) ); ?>"><?php esc_html_e( 'Buy Now', 'webinarignition' ); ?></a>
									</td>
									<td>
										<a class="webinarignition-buy-now-button" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing' ) ); ?>"><?php esc_html_e( 'Buy Now', 'webinarignition' ); ?></a>
									</td>
									<td>
										<a class="webinarignition-buy-now-button" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing' ) ); ?>"><?php esc_html_e( 'Buy Now', 'webinarignition' ); ?></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="webinar_welcome_right_container">
		<div class="webinar_dashboard_box_design wi_video">
			<iframe width="100%" height="300px" src="https://www.youtube.com/embed/7IDiVQXnwZI?si=76TDj9zrlYR8u_OJ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
		<div class="webinar_dashboard_box_design wi_video">
			<iframe width="100%" height="300px" src="https://www.youtube.com/embed/L12pNLZUfSI?si=v7J554IDZFamuGz1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>

		<!-- Display pro/essential optin -->
		<?php if ( ! isset( $statusCheck->is_registered ) || ! $statusCheck->is_registered ) : ?>
			<?php if ( Webinar_Optin::webinarignition_is_essential( $statusCheck ) ) : ?>
				<div class="webinar_dashboard_box_design">
					<?php require WEBINARIGNITION_PATH . 'UI/opt-in/essential-plan.php'; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Display free opt in -->
		<?php if ( Webinar_Optin::webinarignition_is_free( $statusCheck ) ) : ?>
			<?php
			ob_start();
			require WEBINARIGNITION_PATH . 'UI/opt-in/essential-plan.php';
			$optin_html = ob_get_clean();
			?>
			<?php if ( ! empty( trim( $optin_html ) ) ) : ?>
				<div class="webinar_dashboard_box_design">
					<?php echo $optin_html; //phpcs:ignore 
					?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Display plan boxes -->
		<?php if ( Webinar_Optin::webinarignition_is_essential( $statusCheck ) ) : ?>
			<div class="webinar_dashboard_box_design wi_licence_box">
				<?php
				require_once WEBINARIGNITION_PATH . 'UI/opt-in/dash.php';
				require_once WEBINARIGNITION_PATH . 'admin/messages/free-license.php';
				?>
			</div>
		<?php endif; ?>
	</div>
</div>