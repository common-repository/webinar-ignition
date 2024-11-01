<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="tabber" id="tab8" style="display: none;">

<?php

$input_get = array(
	'id' => filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW)
); 
$show_webinarignition_footer_logo = get_option( 'show_webinarignition_footer_logo' );
$default_local_email_footer       = '<table border="0" cellpadding="10" cellspacing="0" width="700" id="template_footer"><tr><td valign="top"><table border="0" cellpadding="10" cellspacing="0" width="100%">';
if ( get_option( 'webinarignition_show_footer_branding' ) ) {
	$default_local_email_footer .= '<tr> <td colspan="2" valign="middle" class="credit"><a href="';
	$default_local_email_footer .= get_option( 'webinarignition_affiliate_link' );
	$default_local_email_footer .= '<p>';
	$default_local_email_footer .= get_option( 'webinarignition_branding_copy' );
	$default_local_email_footer .= '</p>';

	if ( ( 'yes' === $show_webinarignition_footer_logo ) || wp_validate_boolean( $show_webinarignition_footer_logo ) ) {
		$default_local_email_footer .= '<img border="0" class="welogo" src="';
		$default_local_email_footer .= WEBINARIGNITION_URL . 'images/wi-logo.png" ';
		$default_local_email_footer .= 'width="284">';
	}

	$default_local_email_footer .= ' </a> </td></tr>';

}

$default_local_email_footer .= '</table></td></tr></table>';

global $current_user;

?>
	<div class="titleBar">
		<h2><?php esc_html_e( 'Webinar Notification Setting:', 'webinarignition' ); ?></h2>

		<p><?php esc_html_e( 'Here you can manage the notification emails & txt for the webinar...', 'webinarignition' ); ?></p>
	</div>

	<?php
	webinarignition_display_edit_toggle(
		'envelope',
		esc_html__( 'On Sign Up Email - First Email', 'webinarignition' ),
		'we_edit_email_signup',
		esc_html__( 'This is the email copy that is sent out when they first sign up...', 'webinarignition' )
	);
	?>

	<div id="we_edit_email_signup" class="we_edit_area">
		<?php
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->email_signup,
			esc_html__( 'Sign Up Email', 'webinarignition' ),
			'email_signup',
			esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
			esc_html__( 'Enable Sign Up Email', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable Sign Up Email', 'webinarignition' ) . ' [off]'
		);
		?>

			<div class="email_signup" id="email_signup_on">

				<?php

					webinarignition_display_field(
						$input_get['id'],
						$webinar_data->email_signup_sbj,
						esc_html__( 'Sign Up Email Subject', 'webinarignition' ),
						'email_signup_sbj',
						esc_html__( 'This is the sign up email subject line...', 'webinarignition' ),
						''
					);
					webinarignition_display_wpeditor(
						$input_get['id'],
						$webinar_data->email_signup_body,
						esc_html__( 'Email Body Copy', 'webinarignition' ),
						'email_signup_body',
						esc_html__( 'This your email body copy...', 'webinarignition' )
					);

					if ( ! property_exists( $webinar_data, 'templates_version' ) ) {

						$use_new_email_signup_template = property_exists( $webinar_data, 'use_new_email_signup_template' ) ? $webinar_data->use_new_email_signup_template : 'no';

						webinarignition_display_option(
							$input_get['id'],
							$use_new_email_signup_template,
							esc_html__( 'Use New Email-template?', 'webinarignition' ),
							'use_new_email_signup_template',
							esc_html__( 'You can choose to use the new template options. NB: Using the new templates on old webinars may break your html, so be careful.', 'webinarignition' ),
							esc_html__( 'Use New Email-template', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Use Legacy Email-template', 'webinarignition' ) . ' [no]'
						);

					}

					if ( property_exists( $webinar_data, 'templates_version' ) ) {

						webinarignition_display_field_hidden( 'templates_version', $webinar_data->templates_version );
					}

					?>

				<div class="use_new_email_signup_template" id="use_new_email_signup_template_yes">

				<?php

					$email_signup_heading = property_exists( $webinar_data, 'email_signup_heading' ) ? $webinar_data->email_signup_heading : esc_html__( 'Information On The Webinar', 'webinarignition' );
					webinarignition_display_field(
						$input_get['id'],
						$email_signup_heading,
						esc_html__( 'Sign Up Email Heading Text', 'webinarignition' ),
						'email_signup_heading',
						esc_html__( 'This is the text shown in the email header.', 'webinarignition' ),
						''
					);

					$email_signup_preview = property_exists( $webinar_data, 'email_signup_preview' ) ? $webinar_data->email_signup_preview : esc_html__( "Here's info on the webinar you've signed up for...", 'webinarignition' );
					webinarignition_display_field(
						$input_get['id'],
						$email_signup_preview,
						esc_html__( 'Sign Up Email Preview Text', 'webinarignition' ),
						'email_signup_preview',
						esc_html__( 'This is the bit of text below or next to an emailâ€™s subject line in the inbox. Leave empty if you would like to use the header text above instead. ', 'webinarignition' ),
						''
					);

					$show_or_hide_local_email_signup_footer = property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) ? $webinar_data->show_or_hide_local_email_signup_footer : 'hide';

					webinarignition_display_option(
						$input_get['id'],
						$show_or_hide_local_email_signup_footer,
						esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
						'show_or_hide_local_email_signup_footer',
						esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >' . esc_html__( "WebnarIgnition's Settings Page", 'webinarignition' ) . '</a>',
						esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
					);

					?>

					<div class="show_or_hide_local_email_signup_footer" id="show_or_hide_local_email_signup_footer_show">

					<?php


							$local_email_signup_footer = property_exists( $webinar_data, 'local_email_signup_footer' ) ? $webinar_data->local_email_signup_footer : $default_local_email_footer;

							webinarignition_display_wpeditor(
								$input_get['id'],
								$local_email_signup_footer,
								esc_html__( 'Sign Up Email Footer', 'webinarignition' ),
								'local_email_signup_footer',
								esc_html__( 'Your footer for this email only...', 'webinarignition' )
							);

							?>

					</div>

				</div>

				<?php

					webinarignition_display_option(
						$input_get['id'],
						'hide',
						esc_html__( 'Sign-up Email Test', 'webinarignition' ),
						'send_signup_email_test',
						esc_html__( 'Test the email template you have created.', 'webinarignition' ),
						esc_html__( 'Enable Email Test', 'webinarignition' ) . ' [show],' . esc_html__( 'Disable', 'webinarignition' ) . ' [hide]'
					);

					?>
				<div class="send_signup_email_test" id="send_signup_email_test_show" style="display: none;">

						<?php

						webinarignition_display_field(
							$input_get['id'],
							$current_user->user_email,
							esc_html__( 'Notification Email Address', 'webinarignition' ),
							'test_notice_email_address',
							esc_html__( 'Specify the email address to which the test email should be sent', 'webinarignition' ),
							esc_html__( 'name@example.com', 'webinarignition' )
						);

						?>

					<div class="editSection">
							<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp"><?php esc_html_e( 'Click to send the email test.', 'webinarignition' ); ?></div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<a href="#" class="send_email_test btn btn-primary" data-use_new_templatefieldid="use_new_email_signup_template" data-emailheadingfieldid="email_signup_heading" data-emailpreviewfieldid="email_signup_preview" data-emailsubjectfieldid = "email_signup_sbj" data-bodyeditorid="wp-email_signup_body-wrap" data-footereditorid="wp-local_email_signup_footer-wrap" data-showhideinputid="show_or_hide_local_email_signup_footer" data-emailfieldid="test_notice_email_address"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></a>
							</div>
							<br clear="left">
					</div>

			</div>

				<?php

					webinarignition_display_info(
						esc_html__( 'Supported Email Shortcodes:', 'webinarignition' ),
						'{EMAIL}: ' . esc_html__( 'Lead Email, ', 'webinarignition' ) . ' {LINK} : ' . esc_html__( 'Webinar Link, ', 'webinarignition' ) . ' {DATE}:  ' . esc_html__( 'Date', 'webinarignition' ) . ' {TITLE}: - ' . esc_html__( 'Title, ', 'webinarignition' ) .
						' {HOST}: -  ' . esc_html__( 'Webinar Host:', 'webinarignition' )
					);

					?>

		</div>
	</div>



	<?php
	webinarignition_display_edit_toggle(
		'envelope-alt',
		esc_html__( 'Email Notification #1 - Day Before Webinar', 'webinarignition' ),
		'we_edit_email_signup_1',
		esc_html__( 'This email should be sent out 1 day before the webinar...', 'webinarignition' )
	);
	?>

	<div id="we_edit_email_signup_1" class="we_edit_area">

		<?php
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->email_notiff_1,
			esc_html__( 'Toggle Email Notification #1', 'webinarignition' ),
			'email_notiff_1',
			esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
			esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
		);
		?>
		<div class="email_notiff_1" id="email_notiff_1_on">

			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->email_notiff_sbj_1,
				esc_html__( 'Email Subject', 'webinarignition' ),
				'email_notiff_sbj_1',
				esc_html__( 'This is the email subject line...', 'webinarignition' ),
				esc_html__( 'Email Subject Line...', 'webinarignition' )
			);
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->email_notiff_body_1,
				esc_html__( 'Email Body Copy', 'webinarignition' ),
				'email_notiff_body_1',
				esc_html__( 'This your email that is sent out. Formatted with HTML...', 'webinarignition' )
			);


			if ( ! property_exists( $webinar_data, 'templates_version' ) ) {

				$use_new_email_notiff_1_template = property_exists( $webinar_data, 'use_new_email_notiff_1_template' ) ? $webinar_data->use_new_email_notiff_1_template : 'no';

				webinarignition_display_option(
					$input_get['id'],
					$use_new_email_notiff_1_template,
					esc_html__( 'Use New Email-template?', 'webinarignition' ),
					'use_new_notiff_1_template_options',
					esc_html__( 'You can choose to use the new template options. NB: Using the new templates on old webinars may break your html, so be careful.', 'webinarignition' ),
					esc_html__( 'Use New Email-template', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Use Legacy Email-template', 'webinarignition' ) . ' [no]'
				);

			}

			?>


			<div class="use_new_notiff_1_template_options" id="use_new_notiff_1_template_options_yes">

				<?php

					$email_notiff_1_heading = property_exists( $webinar_data, 'email_notiff_1_heading' ) ? $webinar_data->email_notiff_1_heading : esc_html__( "Information On Tomorrow's Webinar", 'webinarignition' );
					webinarignition_display_field(
						$input_get['id'],
						$email_notiff_1_heading,
						esc_html__( 'Email Heading Text', 'webinarignition' ),
						'email_notiff_1_heading',
						esc_html__( 'This is the text shown in the email header.', 'webinarignition' ),
						''
					);

					$email_notiff_1_preview = property_exists( $webinar_data, 'email_notiff_1_preview' ) ? $webinar_data->email_notiff_1_preview : esc_html__( "Here's info on tomorrow's webinar...", 'webinarignition' );
					webinarignition_display_field(
						$input_get['id'],
						$email_notiff_1_preview,
						esc_html__( 'Email Preview Text', 'webinarignition' ),
						'email_notiff_1_preview',
						esc_html__( 'This is the bit of text below or next to an emailâ€™s subject line in the inbox. Leave empty if you would like to use the header text above instead. ', 'webinarignition' ),
						''
					);


					$show_or_hide_local_notiff_1_footer = property_exists( $webinar_data, 'show_or_hide_local_notiff_1_footer' ) ? $webinar_data->show_or_hide_local_notiff_1_footer : 'hide';

					webinarignition_display_option(
						$input_get['id'],
						$show_or_hide_local_notiff_1_footer,
						esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
						'show_or_hide_local_notiff_1_footer',
						esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to ', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >WebnarIgnition\'s Settings Page</a>',
						esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
					);

					?>

				<div class="show_or_hide_local_notiff_1_footer" id="show_or_hide_local_notiff_1_footer_show">

					<?php

						$local_notiff_1_footer = property_exists( $webinar_data, 'local_notiff_1_footer' ) ? $webinar_data->local_notiff_1_footer : $default_local_email_footer;

						webinarignition_display_wpeditor(
							$input_get['id'],
							$local_notiff_1_footer,
							esc_html__( 'Email Notification #1 Footer', 'webinarignition' ),
							'local_notiff_1_footer',
							esc_html__( 'Your footer for this email only...', 'webinarignition' )
						);

						?>

				</div>

			</div>

			<?php

				webinarignition_display_option(
					$input_get['id'],
					'hide',
					esc_html__( 'Email Notification #1 Email Test', 'webinarignition' ),
					'notification_1_email_test',
					esc_html__( 'Test the email template you have created.', 'webinarignition' ),
					esc_html__( 'Enable Email Test', 'webinarignition' ) . ' [show],' . esc_html__( 'Disable', 'webinarignition' ) . ' [hide]'
				);

				?>
				<div class="notification_1_email_test" id="notification_1_email_test_show" style="display: none;">

						<?php

						webinarignition_display_field(
							$input_get['id'],
							$current_user->user_email,
							esc_html__( 'Notification Email Address', 'webinarignition' ),
							'test_notice_1_email_address',
							esc_html__( 'Specify the email address to which the test email should be sent', 'webinarignition' ),
							esc_html__( 'name@example.com', 'webinarignition' )
						);

						?>

					<div class="editSection">
							<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp"><?php esc_html_e( 'Click to send the email test.', 'webinarignition' ); ?></div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<a href="#" class="send_email_test btn btn-primary" data-use_new_templatefieldid="use_new_notiff_1_template_options" data-emailheadingfieldid="email_notiff_1_heading"  data-emailpreviewfieldid="email_notiff_1_preview" data-emailsubjectfieldid = "email_notiff_sbj_1" data-bodyeditorid="wp-email_notiff_body_1-wrap" data-footereditorid="wp-local_notiff_1_footer-wrap" data-showhideinputid="show_or_hide_local_notiff_1_footer" data-emailfieldid="test_notice_1_email_address"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></a>

							</div>
							<br clear="left">
					</div>

			</div>

			<?php


			webinarignition_display_info(
				esc_html__( 'Supported Email Shortcodes:', 'webinarignition' ),
				esc_html__( 'Lead Email:', 'webinarignition' ) . ' {EMAIL} - ' . esc_html__( 'Webinar Link:', 'webinarignition' ) . ' {LINK} - ' . esc_html__( 'Date:', 'webinarignition' ) . ' {DATE} - ' . esc_html__( 'Title:', 'webinarignition' ) .
				' {TITLE} -  ' . esc_html__( 'HOST:', 'webinarignition' ) . ' {HOST}'
			);

			if ( 'AUTO' !== $webinar_data->webinar_date ) {

				webinarignition_display_date_picker(
					$input_get['id'],
					$webinar_data->email_notiff_date_1,
					'm-d-Y',
					esc_html__( 'Scheduled Date', 'webinarignition' ),
					'email_notiff_date_1',
					esc_html__( 'This is the date on which this email is out sent out...', 'webinarignition' ),
					esc_html__( 'Scheduled Date...', 'webinarignition' ),
					$webinar_date_format
				);

				webinarignition_display_time_picker(
					$input_get['id'],
					$webinar_data->email_notiff_time_1,
					esc_html__( 'Scheduled Time', 'webinarignition' ),
					'email_notiff_time_1',
					esc_html__( 'This is the time that the will be sent out on the date (above)...', 'webinarignition' )
				);

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->email_notiff_status_1,
					esc_html__( 'Status Of Email', 'webinarignition' ),
					'email_notiff_status_1',
					esc_html__( 'This will tell if this email was sent out or not.', 'webinarignition' ),
					esc_html__( 'Email Queued', 'webinarignition' ) . ' [queued], ' . esc_html__( 'Email Has Been Sent', 'webinarignition' ) . ' [sent]'
				);
			}//end if

			?>
		</div>
	</div>

	<?php
	webinarignition_display_edit_toggle(
		'envelope-alt',
		esc_html__( 'Email Notification #2 - 1 Hour Before Webinar', 'webinarignition' ),
		'we_edit_email_signup_2',
		esc_html__( 'This email should be sent out 1 hour before the webinar...', 'webinarignition' )
	);
	?>

	<div id="we_edit_email_signup_2" class="we_edit_area">
		<?php
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->email_notiff_2,
			esc_html__( 'Toggle Email Notification #2', 'webinarignition' ),
			'email_notiff_2',
			esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
			esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
		);
		?>
		<div class="email_notiff_2" id="email_notiff_2_on">

			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->email_notiff_sbj_2,
				esc_html__( 'Sign Up Email Subject', 'webinarignition' ),
				'email_notiff_sbj_2',
				esc_html__( 'This is the email subject line...', 'webinarignition' ),
				esc_html__( 'Email Subject Line...', 'webinarignition' )
			);
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->email_notiff_body_2,
				esc_html__( 'Email Body Copy', 'webinarignition' ),
				'email_notiff_body_2',
				esc_html__( 'This your email that is sent out. Formatted with HTML...', 'webinarignition' )
			);

			if ( ! property_exists( $webinar_data, 'templates_version' ) ) {

				$use_new_email_notiff_2_template = property_exists( $webinar_data, 'use_new_email_notiff_2_template' ) ? $webinar_data->use_new_email_notiff_2_template : 'no';

				webinarignition_display_option(
					$input_get['id'],
					$use_new_email_notiff_2_template,
					esc_html__( 'Use New Email-template?', 'webinarignition' ),
					'use_new_notiff_2_template_options',
					esc_html__( 'You can choose to use the new template options. NB: Using the new templates on old webinars may break your html, so be careful.', 'webinarignition' ),
					esc_html__( 'Use New Email-template', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Use Legacy Email-template', 'webinarignition' ) . ' [no]'
				);

			}

			?>

			<div class="use_new_notiff_2_template_options" id="use_new_notiff_2_template_options_yes">

				<?php

					$email_notiff_2_heading = property_exists( $webinar_data, 'email_notiff_2_heading' ) ? $webinar_data->email_notiff_2_heading : esc_html__( 'Information On Your Webinar', 'webinarignition' );
					webinarignition_display_field(
						$input_get['id'],
						$email_notiff_2_heading,
						esc_html__( 'Email Heading Text', 'webinarignition' ),
						'email_notiff_2_heading',
						esc_html__( 'This is the text shown in the email header.', 'webinarignition' ),
						''
					);

					$email_notiff_2_preview = property_exists( $webinar_data, 'email_notiff_2_preview' ) ? $webinar_data->email_notiff_2_preview : esc_html__( "Here's info on today's webinar...", 'webinarignition' );
					webinarignition_display_field(
						$input_get['id'],
						$email_notiff_2_preview,
						esc_html__( 'Email Preview Text', 'webinarignition' ),
						'email_notiff_2_preview',
						esc_html__( 'This is the bit of text below or next to an emailâ€™s subject line in the inbox. Leave empty if you would like to use the header text above instead. ', 'webinarignition' ),
						''
					);


					$show_or_hide_local_notiff_2_footer = property_exists( $webinar_data, 'show_or_hide_local_notiff_2_footer' ) ? $webinar_data->show_or_hide_local_notiff_2_footer : 'hide';

					webinarignition_display_option(
						$input_get['id'],
						$show_or_hide_local_notiff_2_footer,
						esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
						'show_or_hide_local_notiff_2_footer',
						esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to ', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >WebnarIgnition\'s Settings Page</a>',
						esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
					);


					?>


				<div class="show_or_hide_local_notiff_2_footer" id="show_or_hide_local_notiff_2_footer_show">

					<?php

						$local_notiff_2_footer = property_exists( $webinar_data, 'local_notiff_2_footer' ) ? $webinar_data->local_notiff_2_footer : $default_local_email_footer;

						webinarignition_display_wpeditor(
							$input_get['id'],
							$local_notiff_2_footer,
							esc_html__( 'Email Notification #2 Footer', 'webinarignition' ),
							'local_notiff_2_footer',
							esc_html__( 'Your footer for this email only...', 'webinarignition' )
						);

						?>

				</div>


			</div>

				<?php

					webinarignition_display_option(
						$input_get['id'],
						'hide',
						esc_html__( 'Email Notification #2 Email Test', 'webinarignition' ),
						'notification_2_email_test',
						esc_html__( 'Test the email template you have created.', 'webinarignition' ),
						esc_html__( 'Enable Email Test', 'webinarignition' ) . ' [show],' . esc_html__( 'Disable', 'webinarignition' ) . ' [hide]'
					);

					?>

				<div class="notification_2_email_test" id="notification_2_email_test_show" style="display: none;">

						<?php

						webinarignition_display_field(
							$input_get['id'],
							$current_user->user_email,
							esc_html__( 'Notification Email Address', 'webinarignition' ),
							'test_notice_2_email_address',
							esc_html__( 'Specify the email address to which the test email should be sent', 'webinarignition' ),
							esc_html__( 'name@example.com', 'webinarignition' )
						);

						?>

					<div class="editSection">
							<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp"><?php esc_html_e( 'Click to send the email test.', 'webinarignition' ); ?></div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<a href="#" class="send_email_test btn btn-primary" data-use_new_templatefieldid="use_new_notiff_2_template_options" data-emailheadingfieldid="email_notiff_2_heading"  data-emailpreviewfieldid="email_notiff_2_preview" data-emailsubjectfieldid = "email_notiff_sbj_2" data-bodyeditorid="wp-email_notiff_body_2-wrap" data-footereditorid="wp-local_notiff_2_footer-wrap" data-showhideinputid="show_or_hide_local_notiff_2_footer_show" data-emailfieldid="test_notice_2_email_address" ><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></a>
							</div>
							<br clear="left">
					</div>

				</div>

			<?php

			webinarignition_display_info(
				esc_html__( 'Supported Email Shortcodes:', 'webinarignition' ),
				esc_html__( 'Lead Email:', 'webinarignition' ) . ' {EMAIL} - ' . esc_html__( 'Webinar Link:', 'webinarignition' ) . ' {LINK} - ' . esc_html__( 'Date:', 'webinarignition' ) . ' {DATE} - ' . esc_html__( 'Title:', 'webinarignition' ) .
				' {TITLE} -  ' . esc_html__( 'HOST:', 'webinarignition' ) . ' {HOST}'
			);

			if ( 'AUTO' !== $webinar_data->webinar_date ) {

				webinarignition_display_date_picker(
					$input_get['id'],
					$webinar_data->email_notiff_date_2,
					'm-d-Y',
					esc_html__( 'Scheduled Date', 'webinarignition' ),
					'email_notiff_date_2',
					esc_html__( 'This is the date on which this email is out sent out...', 'webinarignition' ),
					esc_html__( 'Scheduled Date...', 'webinarignition' ),
					$webinar_date_format
				);

				webinarignition_display_time_picker(
					$input_get['id'],
					$webinar_data->email_notiff_time_2,
					esc_html__( 'Scheduled Time', 'webinarignition' ),
					'email_notiff_time_2',
					esc_html__( 'This is the time that the will be sent out on the date (above)...', 'webinarignition' )
				);

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->email_notiff_status_2,
					esc_html__( 'Status Of Email', 'webinarignition' ),
					'email_notiff_status_2',
					esc_html__( 'This will tell if this email was sent out or not. If it was sent, and you want to change the date, remember to change this back to not sent...', 'webinarignition' ),
					esc_html__( 'Email Queued', 'webinarignition' ) . ' [queued], ' . esc_html__( 'Email Has Been Sent', 'webinarignition' ) . ' [sent]'
				);
			}//end if

			?>
		</div>
	</div>

	<?php
			webinarignition_display_edit_toggle(
				'envelope-alt',
				esc_html__( 'Email Notification #3 - Live Webinar', 'webinarignition' ),
				'we_edit_email_signup_3',
				esc_html__( 'This email should be sent out when the webinar is live...', 'webinarignition' )
			);
			?>

	<div id="we_edit_email_signup_3" class="we_edit_area">
		<?php
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->email_notiff_3,
			esc_html__( 'Toggle Email Notification #3', 'webinarignition' ),
			'email_notiff_3',
			esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
			esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
		);
		?>
		<div class="email_notiff_3" id="email_notiff_3_on">

			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->email_notiff_sbj_3,
				esc_html__( 'Email Subject', 'webinarignition' ),
				'email_notiff_sbj_3',
				esc_html__( 'This is the email subject line...', 'webinarignition' ),
				esc_html__( 'Email Subject Line...', 'webinarignition' )
			);
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->email_notiff_body_3,
				esc_html__( 'Email Body Copy', 'webinarignition' ),
				'email_notiff_body_3',
				esc_html__( 'This your email that is sent out. Formatted with HTML...', 'webinarignition' )
			);

			if ( ! property_exists( $webinar_data, 'templates_version' ) ) {

				$use_new_email_notiff_3_template = property_exists( $webinar_data, 'use_new_email_notiff_3_template' ) ? $webinar_data->use_new_email_notiff_3_template : 'no';

				webinarignition_display_option(
					$input_get['id'],
					$use_new_email_notiff_3_template,
					esc_html__( 'Use New Email-template?', 'webinarignition' ),
					'use_new_notiff_3_template_options',
					esc_html__( 'You can choose to use the new template options. NB: Using the new templates on old webinars may break your html, so be careful.', 'webinarignition' ),
					esc_html__( 'Use New Email-template', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Use Legacy Email-template', 'webinarignition' ) . ' [no]'
				);

			}

			?>

			<div class="use_new_notiff_3_template_options" id="use_new_notiff_3_template_options_yes">

				<?php

				$email_notiff_3_heading = property_exists( $webinar_data, 'email_notiff_3_heading' ) ? $webinar_data->email_notiff_3_heading : esc_html__( 'Information On Your Webinar', 'webinarignition' );
				webinarignition_display_field(
					$input_get['id'],
					$email_notiff_3_heading,
					esc_html__( 'Email Heading Text', 'webinarignition' ),
					'email_notiff_3_heading',
					esc_html__( 'This is the text shown in the email header.', 'webinarignition' ),
					''
				);

				$email_notiff_3_preview = property_exists( $webinar_data, 'email_notiff_3_preview' ) ? $webinar_data->email_notiff_3_preview : esc_html__( 'The webinar is live...', 'webinarignition' );
				webinarignition_display_field(
					$input_get['id'],
					$email_notiff_3_preview,
					esc_html__( 'Email Preview Text', 'webinarignition' ),
					'email_notiff_3_preview',
					esc_html__( 'This is the bit of text below or next to an emailâ€™s subject line in the inbox. Leave empty if you would like to use the header text above instead. ', 'webinarignition' ),
					''
				);

				$show_or_hide_local_notiff_3_footer = property_exists( $webinar_data, 'show_or_hide_local_notiff_3_footer' ) ? $webinar_data->show_or_hide_local_notiff_3_footer : 'hide';

				webinarignition_display_option(
					$input_get['id'],
					$show_or_hide_local_notiff_3_footer,
					esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
					'show_or_hide_local_notiff_3_footer',
					esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to ', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >WebnarIgnition\'s Settings Page</a>',
					esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
				);

				?>

				<div class="show_or_hide_local_notiff_3_footer" id="show_or_hide_local_notiff_3_footer_show">

					<?php

						$local_notiff_3_footer = property_exists( $webinar_data, 'local_notiff_3_footer' ) ? $webinar_data->local_notiff_3_footer : $default_local_email_footer;

						webinarignition_display_wpeditor(
							$input_get['id'],
							$local_notiff_3_footer,
							esc_html__( 'Email Notification #3 Footer', 'webinarignition' ),
							'local_notiff_3_footer',
							esc_html__( 'Your footer for this email only...', 'webinarignition' )
						);

						?>

				</div>

			</div>


				<?php

					webinarignition_display_option(
						$input_get['id'],
						'hide',
						esc_html__( 'Email Notification #3 Email Test', 'webinarignition' ),
						'notification_3_email_test',
						esc_html__( 'Test the email template you have created.', 'webinarignition' ),
						esc_html__( 'Enable Email Test', 'webinarignition' ) . ' [show],' . esc_html__( 'Disable', 'webinarignition' ) . ' [hide]'
					);

					?>

				<div class="notification_3_email_test" id="notification_3_email_test_show" style="display: none;">

						<?php

						webinarignition_display_field(
							$input_get['id'],
							$current_user->user_email,
							esc_html__( 'Notification Email Address', 'webinarignition' ),
							'test_notice_3_email_address',
							esc_html__( 'Specify the email address to which the test email should be sent', 'webinarignition' ),
							esc_html__( 'name@example.com', 'webinarignition' )
						);

						?>

					<div class="editSection">
							<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp"><?php esc_html_e( 'Click to send the email test.', 'webinarignition' ); ?></div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<a href="#" class="send_email_test btn btn-primary" data-use_new_templatefieldid="use_new_notiff_3_template_options" data-emailheadingfieldid="email_notiff_3_heading"  data-emailpreviewfieldid="email_notiff_3_preview" data-emailsubjectfieldid = "email_notiff_sbj_3" data-bodyeditorid="wp-email_notiff_body_3-wrap" data-footereditorid="wp-local_notiff_3_footer-wrap" data-showhideinputid="show_or_hide_local_notiff_3_footer_show" data-emailfieldid="test_notice_2_email_address" ><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></a>
							</div>
							<br clear="left">
					</div>

				</div>

			<?php


			webinarignition_display_info(
				esc_html__( 'Supported Email Shortcodes:', 'webinarignition' ),
				esc_html__( 'Lead Email:', 'webinarignition' ) . ' {EMAIL} - ' . esc_html__( 'Webinar Link:', 'webinarignition' ) . ' {LINK} - ' . esc_html__( 'Date:', 'webinarignition' ) . ' {DATE} - ' . esc_html__( 'Title:', 'webinarignition' ) .
				' {TITLE} -  ' . esc_html__( 'HOST:', 'webinarignition' ) . ' {HOST}'
			);

			if ( 'AUTO' !== $webinar_data->webinar_date ) {

				webinarignition_display_date_picker(
					$input_get['id'],
					$webinar_data->email_notiff_date_3,
					'm-d-Y',
					esc_html__( 'Scheduled Date', 'webinarignition' ),
					'email_notiff_date_3',
					esc_html__( 'This is the date on which this email is out sent out...', 'webinarignition' ),
					esc_html__( 'Scheduled Date...', 'webinarignition' ),
					$webinar_date_format
				);

				webinarignition_display_time_picker(
					$input_get['id'],
					$webinar_data->email_notiff_time_3,
					esc_html__( 'Scheduled Time', 'webinarignition' ),
					'email_notiff_time_3',
					esc_html__( 'This is the time that the will be sent out on the date (above)...', 'webinarignition' )
				);

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->email_notiff_status_3,
					esc_html__( 'Status Of Email', 'webinarignition' ),
					'email_notiff_status_3',
					esc_html__( 'This will tell if this email was sent out or not. If it was sent, and you want to change the date, remember to change this back to not sent...', 'webinarignition' ),
					esc_html__( 'Email Queued', 'webinarignition' ) . ' [queued], ' . esc_html__( 'Email Has Been Sent', 'webinarignition' ) . ' [sent]'
				);
			}//end if

			?>
		</div>
	</div>

	<?php
	webinarignition_display_edit_toggle(
		'envelope-alt',
		esc_html__( 'Email Notification #4 - 1 Hour After Webinar', 'webinarignition' ),
		'we_edit_email_signup_4',
		esc_html__( 'This email should be sent out 1 hour after the webinar...', 'webinarignition' )
	);
	?>

	<div id="we_edit_email_signup_4" class="we_edit_area">
		<?php
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->email_notiff_4,
			esc_html__( 'Toggle Email Notification #4', 'webinarignition' ),
			'email_notiff_4',
			esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
			esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
		);
		?>
		<div class="email_notiff_4" id="email_notiff_4_on">

			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->email_notiff_sbj_4,
				esc_html__( 'Email Subject', 'webinarignition' ),
				'email_notiff_sbj_4',
				esc_html__( 'This is the email subject line...', 'webinarignition' ),
				esc_html__( 'Email Subject Line...', 'webinarignition' )
			);
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->email_notiff_body_4,
				esc_html__( 'Email Body Copy', 'webinarignition' ),
				'email_notiff_body_4',
				esc_html__( 'This your email that is sent out. Formatted with HTML...', 'webinarignition' )
			);


			if ( ! property_exists( $webinar_data, 'templates_version' ) ) {

				$use_new_email_notiff_4_template = property_exists( $webinar_data, 'use_new_email_notiff_4_template' ) ? $webinar_data->use_new_email_notiff_4_template : 'no';

				webinarignition_display_option(
					$input_get['id'],
					$use_new_email_notiff_4_template,
					esc_html__( 'Use New Email-template?', 'webinarignition' ),
					'use_new_notiff_4_template_options',
					esc_html__( 'You can choose to use the new template options. NB: Using the new templates on old webinars may break your html, so be careful.', 'webinarignition' ),
					esc_html__( 'Use New Email-template', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Use Legacy Email-template', 'webinarignition' ) . ' [no]'
				);

			}

			?>

			<div class="use_new_notiff_4_template_options" id="use_new_notiff_4_template_options_yes">

				<?php

				$email_notiff_4_heading = property_exists( $webinar_data, 'email_notiff_4_heading' ) ? $webinar_data->email_notiff_4_heading : esc_html__( 'Replay is live!', 'webinarignition' );
				webinarignition_display_field(
					$input_get['id'],
					$email_notiff_4_heading,
					esc_html__( 'Email Heading Text', 'webinarignition' ),
					'email_notiff_4_heading',
					esc_html__( 'This is the text shown in the email header.', 'webinarignition' ),
					''
				);

				$email_notiff_4_preview = property_exists( $webinar_data, 'email_notiff_4_preview' ) ? $webinar_data->email_notiff_4_preview : esc_html__( 'The webinar replay is live...', 'webinarignition' );
				webinarignition_display_field(
					$input_get['id'],
					$email_notiff_4_preview,
					esc_html__( 'Email Preview Text', 'webinarignition' ),
					'email_notiff_4_preview',
					esc_html__( 'This is the bit of text below or next to an emailâ€™s subject line in the inbox. Leave empty if you would like to use the header text above instead. ', 'webinarignition' ),
					''
				);


				$show_or_hide_local_notiff_4_footer = property_exists( $webinar_data, 'show_or_hide_local_notiff_4_footer' ) ? $webinar_data->show_or_hide_local_notiff_4_footer : 'hide';

				webinarignition_display_option(
					$input_get['id'],
					$show_or_hide_local_notiff_4_footer,
					esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
					'show_or_hide_local_notiff_4_footer',
					esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to ', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >WebnarIgnition\'s Settings Page</a>',
					esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
				);

				?>

				<div class="show_or_hide_local_notiff_4_footer" id="show_or_hide_local_notiff_4_footer_show">

					<?php

						$local_notiff_4_footer = property_exists( $webinar_data, 'local_notiff_4_footer' ) ? $webinar_data->local_notiff_4_footer : $default_local_email_footer;

						webinarignition_display_wpeditor(
							$input_get['id'],
							$local_notiff_4_footer,
							esc_html__( 'Email Notification #4 Footer', 'webinarignition' ),
							'local_notiff_4_footer',
							esc_html__( 'Your footer for this email only...', 'webinarignition' )
						);

						?>

				</div>

			</div>

					<?php

					webinarignition_display_option(
						$input_get['id'],
						'hide',
						esc_html__( 'Email Notification #4 Email Test', 'webinarignition' ),
						'notification_4_email_test',
						esc_html__( 'Test the email template you have created.', 'webinarignition' ),
						esc_html__( 'Enable Email Test', 'webinarignition' ) . ' [show],' . esc_html__( 'Disable', 'webinarignition' ) . ' [hide]'
					);

					?>
				<div class="notification_4_email_test" id="notification_4_email_test_show" style="display: none;">

					<?php

					webinarignition_display_field(
						$input_get['id'],
						$current_user->user_email,
						esc_html__( 'Notification Email Address', 'webinarignition' ),
						'test_notice_4_email_address',
						esc_html__( 'Specify the email address to which the test email should be sent', 'webinarignition' ),
						esc_html__( 'name@example.com', 'webinarignition' )
					);

					?>

					<div class="editSection">
							<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp"><?php esc_html_e( 'Click to send the email test.', 'webinarignition' ); ?></div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<a href="#" class="send_email_test btn btn-primary" data-use_new_templatefieldid="use_new_notiff_4_template_options" data-emailheadingfieldid="email_notiff_4_heading"  data-emailpreviewfieldid="email_notiff_4_preview" data-emailsubjectfieldid = "email_notiff_sbj_4" data-bodyeditorid="wp-email_notiff_body_4-wrap" data-footereditorid="wp-local_notiff_4_footer-wrap" data-showhideinputid="show_or_hide_local_notiff_4_footer_show" data-emailfieldid="test_notice_4_email_address" ><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></a>
							</div>
							<br clear="left">
					</div>

				</div>


			<?php

			webinarignition_display_info(
				esc_html__( 'Supported Email Shortcodes:', 'webinarignition' ),
				esc_html__( 'Lead Email:', 'webinarignition' ) . ' {EMAIL} - ' . esc_html__( 'Webinar Link:', 'webinarignition' ) . ' {LINK} - ' . esc_html__( 'Date:', 'webinarignition' ) . ' {DATE} - ' . esc_html__( 'Title:', 'webinarignition' ) .
				' {TITLE} -  ' . esc_html__( 'HOST:', 'webinarignition' ) . ' {HOST}'
			);


			if ( 'AUTO' !== $webinar_data->webinar_date ) {

				webinarignition_display_date_picker(
					$input_get['id'],
					$webinar_data->email_notiff_date_4,
					'm-d-Y',
					esc_html__( 'Scheduled Date', 'webinarignition' ),
					'email_notiff_date_4',
					esc_html__( 'This is the date on which this email is out sent out...', 'webinarignition' ),
					esc_html__( 'Scheduled Date...', 'webinarignition' ),
					$webinar_date_format
				);

				webinarignition_display_time_picker(
					$input_get['id'],
					$webinar_data->email_notiff_time_4,
					esc_html__( 'Scheduled Time', 'webinarignition' ),
					'email_notiff_time_4',
					esc_html__( 'This is the time that the will be sent out on the date (above)...', 'webinarignition' )
				);

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->email_notiff_status_4,
					esc_html__( 'Status Of Email', 'webinarignition' ),
					'email_notiff_status_4',
					esc_html__( 'This will tell if this email was sent out or not. If it was sent, and you want to change the date, remember to change this back to not sent...', 'webinarignition' ),
					esc_html__( 'Email Queued', 'webinarignition' ) . ' [queued], ' . esc_html__( 'Email Has Been Sent', 'webinarignition' ) . ' [sent]'
				);
			}//end if

			?>
		</div>
	</div>

	<?php
	webinarignition_display_edit_toggle(
		'envelope-alt',
		esc_html__( 'Email Notification #5 - 1 Day After Webinar', 'webinarignition' ),
		'we_edit_email_signup_5',
		esc_html__( 'This email should be sent out 1 day after the webinar...', 'webinarignition' )
	);
	?>

	<div id="we_edit_email_signup_5" class="we_edit_area">
		<?php
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->email_notiff_5,
			esc_html__( 'Toggle Email Notification #5', 'webinarignition' ),
			'email_notiff_5',
			esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
			esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
		);
		?>
		<div class="email_notiff_5" id="email_notiff_5_on">

			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->email_notiff_sbj_5,
				esc_html__( 'Email Subject', 'webinarignition' ),
				'email_notiff_sbj_5',
				esc_html__( 'This is the email subject line...', 'webinarignition' ),
				esc_html__( 'Email Subject Line...', 'webinarignition' )
			);
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->email_notiff_body_5,
				esc_html__( 'Email Body Copy', 'webinarignition' ),
				'email_notiff_body_5',
				esc_html__( 'This your email that is sent out. Formatted with HTML...', 'webinarignition' )
			);



			if ( ! property_exists( $webinar_data, 'templates_version' ) ) {

				$use_new_email_notiff_5_template = property_exists( $webinar_data, 'use_new_email_notiff_5_template' ) ? $webinar_data->use_new_email_notiff_5_template : 'no';

				webinarignition_display_option(
					$input_get['id'],
					$use_new_email_notiff_5_template,
					esc_html__( 'Use New Email-template?', 'webinarignition' ),
					'use_new_notiff_5_template_options',
					esc_html__( 'You can choose to use the new template options. NB: Using the new templates on old webinars may break your html, so be careful.', 'webinarignition' ),
					esc_html__( 'Use New Email-template', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Use Legacy Email-template', 'webinarignition' ) . ' [no]'
				);

			}

			?>

			<div class="use_new_notiff_5_template_options" id="use_new_notiff_5_template_options_yes">

				<?php

				$email_notiff_5_heading = property_exists( $webinar_data, 'email_notiff_5_heading' ) ? $webinar_data->email_notiff_5_heading : esc_html__( 'Webinar replay is going down soon!', 'webinarignition' );
				webinarignition_display_field(
					$input_get['id'],
					$email_notiff_5_heading,
					esc_html__( 'Email Heading Text', 'webinarignition' ),
					'email_notiff_5_heading',
					esc_html__( 'This is the text shown in the email header.', 'webinarignition' ),
					''
				);

				$email_notiff_5_preview = property_exists( $webinar_data, 'email_notiff_5_preview' ) ? $webinar_data->email_notiff_5_preview : esc_html__( 'The webinar replay is going down soon...', 'webinarignition' );
				webinarignition_display_field(
					$input_get['id'],
					$email_notiff_5_preview,
					esc_html__( 'Email Preview Text', 'webinarignition' ),
					'email_notiff_5_preview',
					esc_html__( 'This is the bit of text below or next to an emailâ€™s subject line in the inbox. Leave empty if you would like to use the header text above instead. ', 'webinarignition' ),
					''
				);

				$show_or_hide_local_notiff_5_footer = property_exists( $webinar_data, 'show_or_hide_local_notiff_5_footer' ) ? $webinar_data->show_or_hide_local_notiff_5_footer : 'hide';

				webinarignition_display_option(
					$input_get['id'],
					$show_or_hide_local_notiff_5_footer,
					esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
					'show_or_hide_local_notiff_5_footer',
					esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to ', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >WebnarIgnition\'s Settings Page</a>',
					esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
				);

				?>

				<div class="show_or_hide_local_notiff_5_footer" id="show_or_hide_local_notiff_5_footer_show">

					<?php

						$local_notiff_5_footer = property_exists( $webinar_data, 'local_notiff_5_footer' ) ? $webinar_data->local_notiff_5_footer : $default_local_email_footer;

						webinarignition_display_wpeditor(
							$input_get['id'],
							$local_notiff_5_footer,
							esc_html__( 'Email Notification #5 Footer', 'webinarignition' ),
							'local_notiff_5_footer',
							esc_html__( 'Your footer for this email only...', 'webinarignition' )
						);

						?>

				</div>

			</div>

				<?php

					webinarignition_display_option(
						$input_get['id'],
						'hide',
						esc_html__( 'Email Notification #5 Email Test', 'webinarignition' ),
						'notification_5_email_test',
						esc_html__( 'Test the email template you have created.', 'webinarignition' ),
						esc_html__( 'Enable Email Test', 'webinarignition' ) . ' [show],' . esc_html__( 'Disable', 'webinarignition' ) . ' [hide]'
					);

					?>
				<div class="notification_5_email_test" id="notification_5_email_test_show" style="display: none;">

						<?php

						webinarignition_display_field(
							$input_get['id'],
							$current_user->user_email,
							esc_html__( 'Notification Email Address', 'webinarignition' ),
							'test_notice_5_email_address',
							esc_html__( 'Specify the email address to which the test email should be sent', 'webinarignition' ),
							esc_html__( 'name@example.com', 'webinarignition' )
						);

						?>

					<div class="editSection">
							<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp"><?php esc_html_e( 'Click to send the email test.', 'webinarignition' ); ?></div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<a href="#" class="send_email_test btn btn-primary" data-use_new_templatefieldid="use_new_notiff_5_template_options" data-emailheadingfieldid="email_notiff_5_heading"  data-emailpreviewfieldid="email_notiff_5_preview" data-emailsubjectfieldid = "email_notiff_sbj_5" data-bodyeditorid="wp-email_notiff_body_5-wrap" data-footereditorid="wp-local_notiff_5_footer-wrap" data-showhideinputid="show_or_hide_local_notiff_5_footer_show" data-emailfieldid="test_notice_5_email_address" ><?php esc_html_e( 'Send Email Test', 'webinarignition' ); ?></a>
							</div>
							<br clear="left">
					</div>

				</div>

			<?php


			webinarignition_display_info(
				esc_html__( 'Supported Email Shortcodes:', 'webinarignition' ),
				esc_html__( 'Lead Email:', 'webinarignition' ) . ' {EMAIL} - ' . esc_html__( 'Webinar Link:', 'webinarignition' ) . ' {LINK} - ' . esc_html__( 'Date:', 'webinarignition' ) . ' {DATE} - ' . esc_html__( 'Title:', 'webinarignition' ) .
				' {TITLE} -  ' . esc_html__( 'HOST:', 'webinarignition' ) . ' {HOST}'
			);

			if ( 'AUTO' !== $webinar_data->webinar_date ) {

				webinarignition_display_date_picker(
					$input_get['id'],
					$webinar_data->email_notiff_date_5,
					'm-d-Y',
					esc_html__( 'Scheduled Date', 'webinarignition' ),
					'email_notiff_date_5',
					esc_html__( 'This is the date on which this email is out sent out...', 'webinarignition' ),
					esc_html__( 'Scheduled Date...', 'webinarignition' ),
					$webinar_date_format
				);

				webinarignition_display_time_picker(
					$input_get['id'],
					$webinar_data->email_notiff_time_5,
					esc_html__( 'Scheduled Time', 'webinarignition' ),
					'email_notiff_time_5',
					esc_html__( 'This is the time that the will be sent out on the date (above)...', 'webinarignition' )
				);

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->email_notiff_status_5,
					esc_html__( 'Status Of Email', 'webinarignition' ),
					'email_notiff_status_5',
					esc_html__( 'This will tell if this email was sent out or not. If it was sent, and you want to change the date, remember to change this back to not sent...', 'webinarignition' ),
					esc_html__( 'Email Queued', 'webinarignition' ) . ' [queued], ' . esc_html__( 'Email Has Been Sent', 'webinarignition' ) . ' [sent]'
				);
			}//end if

			?>
		</div>
	</div>

	<?php

		webinarignition_display_edit_toggle(
			'comments',
			esc_html__( 'Live Console Q&A', 'webinarignition' ),
			'console-q-and-a',
			esc_html__( 'Settings for your console Q&A', 'webinarignition' )
		);

		?>

	<div id="console-q-and-a" class="we_edit_area">

		<?php

			$csv_key = ! empty( $webinar_data->csv_key ) ? $webinar_data->csv_key : wp_generate_password( 16, false );

			webinarignition_display_field(
				$input_get['id'],
				$csv_key,
				esc_html__( 'CSV Download Key', 'webinarignition' ),
				'csv_key',
				esc_html__( 'This is the csv download link key. Append it to the webinar url to download the questions csv file.', 'webinarignition' ),
				''
			);

			$console_q_notifications = property_exists( $webinar_data, 'console_q_notifications' ) ? $webinar_data->console_q_notifications : 'no';

			webinarignition_display_option(
				$input_get['id'],
				$console_q_notifications,
				esc_html__( 'Enable Question Notifications', 'webinarignition' ),
				'console_q_notifications',
				esc_html__( 'You can allow support staff to receive questions and answer them for you', 'webinarignition' ),
				esc_html__( 'Disable', 'webinarignition' ) . ' [no],' . esc_html__( 'Enable', 'webinarignition' ) . ' [yes]'
			);

			?>

		<div class="console_q_notifications" id="console_q_notifications_yes">

			<?php

					$enable_first_question_notification = property_exists( $webinar_data, 'enable_first_question_notification' ) ? $webinar_data->enable_first_question_notification : 'no';

					webinarignition_display_option(
						$input_get['id'],
						$enable_first_question_notification,
						esc_html__( 'Send Questions Notifications After The First Question', 'webinarignition' ),
						'enable_first_question_notification',
						esc_html__( 'You can choose the question notification to be sent immediately after the first question.', 'webinarignition' ),
						esc_html__( 'Enable', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [no]'
					);


					if ( 'AUTO' !== $webinar_data->webinar_date ) {

						$first_question_notification_sent = property_exists( $webinar_data, 'first_question_notification_sent' ) ? $webinar_data->first_question_notification_sent : 'no';

						webinarignition_display_option(
							$input_get['id'],
							$first_question_notification_sent,
							esc_html__( 'After-First-Question Email Notification Status', 'webinarignition' ),
							'first_question_notification_sent',
							esc_html__( 'You can choose to requeue this notification if it has been sent already.', 'webinarignition' ),
							esc_html__( 'Sent', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Queued', 'webinarignition' ) . ' [no]'
						);

					}

					if ( 'AUTO' !== $webinar_data->webinar_date ) {

						$enable_after_webinar_question_notification = property_exists( $webinar_data, 'enable_after_webinar_question_notification' ) ? $webinar_data->enable_after_webinar_question_notification : 'no';

						webinarignition_display_option(
							$input_get['id'],
							$enable_after_webinar_question_notification,
							esc_html__( 'Send Questions Notifications After The Webinar Has Closed', 'webinarignition' ),
							'enable_after_webinar_question_notification',
							esc_html__( 'You can choose the question notification to be sent as soon as the webinar has ended.', 'webinarignition' ),
							esc_html__( 'Enable', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [no]'
						);

					} else {

						$enable_after_webinar_question_notification = property_exists( $webinar_data, 'enable_after_webinar_question_notification' ) ? $webinar_data->enable_after_webinar_question_notification : 'no';

						webinarignition_display_option(
							$input_get['id'],
							$enable_after_webinar_question_notification,
							esc_html__( 'Send Questions Notifications After The Webinar Has Ended', 'webinarignition' ),
							'enable_after_webinar_question_notification',
							esc_html__( 'You can choose the question notification to be sent as soon as the webinar has ended.', 'webinarignition' ),
							esc_html__( 'Enable', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [no]'
						);

					}//end if

					if ( 'AUTO' !== $webinar_data->webinar_date ) {

						$after_webinar_questions_notification_sent = property_exists( $webinar_data, 'after_webinar_questions_notification_sent' ) ? $webinar_data->after_webinar_questions_notification_sent : 'no';

						webinarignition_display_option(
							$input_get['id'],
							$after_webinar_questions_notification_sent,
							esc_html__( 'After-Webinar-Question Email Notification Status', 'webinarignition' ),
							'after_webinar_questions_notification_sent',
							esc_html__( 'You can choose to requeue this notification if it has been sent already.', 'webinarignition' ),
							esc_html__( 'Sent', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Queued', 'webinarignition' ) . ' [no]'
						);

					}

					$send_host_questions_notifications = property_exists( $webinar_data, 'send_host_questions_notifications' ) ? $webinar_data->send_host_questions_notifications : 'no';

					webinarignition_display_option(
						$input_get['id'],
						$send_host_questions_notifications,
						esc_html__( 'Send Questions Notifications To Host', 'webinarignition' ),
						'send_host_questions_notifications',
						esc_html__( 'You can choose to send the questions notifications to the webinar host (in addition to support staff).', 'webinarignition' ),
						esc_html__( 'Disable', 'webinarignition' ) . ' [no],' . esc_html__( 'Enable', 'webinarignition' ) . ' [yes]'
					);

					?>

						<div class="send_host_questions_notifications" id="send_host_questions_notifications_yes">

							<?php

								$host_questions_notifications_email = ! empty( $webinar_data->host_questions_notifications_email ) ? $webinar_data->host_questions_notifications_email : '';

								webinarignition_display_field(
									$input_get['id'],
									$host_questions_notifications_email,
									esc_html__( 'Host Email Address', 'webinarignition' ),
									'host_questions_notifications_email',
									esc_html__( 'This is the email address to send the question notifications to', 'webinarignition' ),
									esc_html__( 'host@example.com', 'webinarignition' ),
									'email'
								);

								?>

						</div>

					<div class="editSection">

							<div class="inputTitle" style="width: auto; border: none;">
									<div class="inputTitleCopy" ><?php esc_html_e( 'Question Notifications', 'webinarignition' ); ?></div>
									<div class="inputTitleHelp" ><?php esc_html_e( 'Sent to support staff & host.', 'webinarignition' ); ?></div>
							</div>
							<br clear="left" >
					</div>

					<?php

					$qstn_notification_email_sbj = property_exists( $webinar_data, 'qstn_notification_email_sbj' ) ? $webinar_data->qstn_notification_email_sbj : esc_html__( 'You have new support questions for webinar ', 'webinarignition' ) . $webinar_data->webinar_desc;

					webinarignition_display_field(
						$input_get['id'],
						$qstn_notification_email_sbj,
						esc_html__( 'Email Subject', 'webinarignition' ),
						'qstn_notification_email_sbj',
						esc_html__( 'This is the subject line for notifications sent to support staff', 'webinarignition' ),
						''
					);

					$qstn_notification_email_body = property_exists( $webinar_data, 'qstn_notification_email_body' ) ? $webinar_data->qstn_notification_email_body : esc_html__( 'Hi', 'webinarignition' ) . ' {support}, {attendee} ' . esc_html__( 'has asked a question in the', 'webinarignition' ) . ' {webinarTitle} ' . esc_html__( 'webinar and needs an answer. Click', 'webinarignition' ) . ' {link} ' . esc_html__( 'to answer this question now.', 'webinarignition' );

					webinarignition_display_wpeditor(
						$input_get['id'],
						$qstn_notification_email_body,
						esc_html__( 'Email Body', 'webinarignition' ),
						'qstn_notification_email_body',
						esc_html__( 'Notification email body copy...', 'webinarignition' )
					);

					$default_answer_email_body = '<p>' . esc_html__( 'Hi', 'webinarignition' ) . ' {ATTENDEE},</p><p>' . esc_html__( 'The answer to your question:', 'webinarignition' ) . '</p><p>"{QUESTION}"</p><p>{ANSWER} </p><p>' . esc_html__( 'Thank you and best regards,', 'webinarignition' ) . '</p><p>{SUPPORTNAME}</p>';

					$qstn_answer_email_body = ! empty( $webinar_data->qstn_answer_email_body ) ? $webinar_data->qstn_answer_email_body : $default_answer_email_body;

					webinarignition_display_wpeditor(
						$input_get['id'],
						$qstn_answer_email_body,
						esc_html__( 'Answer Email Body Copy', 'webinarignition' ),
						'qstn_answer_email_body',
						esc_html__( 'Answer email body copy...', 'webinarignition' )
					);



					$show_or_hide_local_qstn_answer_email_footer = property_exists( $webinar_data, 'show_or_hide_local_qstn_answer_email_footer' ) ? $webinar_data->show_or_hide_local_qstn_answer_email_footer : 'hide';

					webinarignition_display_option(
						$input_get['id'],
						$show_or_hide_local_qstn_answer_email_footer,
						esc_html__( 'Webinar-Specific Footer', 'webinarignition' ),
						'show_or_hide_local_qstn_answer_email_footer',
						esc_html__( 'You can show footer styling for just this webinar, or use global settings for all webinars. To configure the global footer, go to ', 'webinarignition' ) . ' <a target="_blank" href="' . home_url() . '/wp-admin/admin.php?page=webinarignition_settings&tab=email-templates" >WebnarIgnition\'s Settings Page</a>',
						esc_html__( 'Use Webinar-Specific Footer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Use Global Footer', 'webinarignition' ) . ' [hide]'
					);

					?>

				<div class="show_or_hide_local_qstn_answer_email_footer" id="show_or_hide_local_qstn_answer_email_footer_show">

					<?php

						$default_answer_email_footer = '<table style="margin: auto;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"> '
								. '<tbody> <tr> <td class="bg_black footer email-section" style="text-align: center; background: #000000; padding: 2.5em; padding-top: 0; color: rgba(255,255,255,.5);" valign="middle">'
								. ' <table style="margin: 0 auto;"> <tbody> <tr> <td valign="top" width="100%"> <table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"> '
								. '<tbody> <tr> <td style="text-align: center; padding-right: 10px;"> '
								. '<p style="color: rgba(255,255,255,.5);">' . esc_html__( 'Powered by', 'webinarignition' ) . '</p></td></tr><tr> <td style="text-align: center; padding-right: 10px;"> <div class="mLogoIMG">'
								. '<a href="{AFFILIATE}" target="_blank" rel="noopener"><img class="welogo" src="' . WEBINARIGNITION_URL . 'images/wi-logo.png" alt="" width="284" border="0"/></a></div></td></tr><tr> '
								. '<td style="color: rgba(255,255,255,.5); text-align: center; padding-right: 10px;"> <p style="color: rgba(255,255,255,.5);">WebinarIgnition | ' . esc_html__( 'The Most Powerful Webinar Platform for Live &amp; Automated Webinars', 'webinarignition' ) . '</p></td></tr><tr> '
								. '<td style="color: rgba(255,255,255,.5); text-align: center; padding-right: 10px;"> <p style="color: rgba(255,255,255,.5);">Â©{YEAR}WebinarIgnition. ' . esc_html__( 'All Rights Reserved', 'webinarignition' ) . '</p></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table>';
						$qstn_answer_email_footer    = ! empty( $webinar_data->qstn_answer_email_footer ) ? $webinar_data->qstn_answer_email_footer : $default_answer_email_footer;

						webinarignition_display_wpeditor(
							$input_get['id'],
							$qstn_answer_email_footer,
							esc_html__( 'Answer Email Footer Copy', 'webinarignition' ),
							'qstn_answer_email_footer',
							esc_html__( 'Answer email footer copy...', 'webinarignition' )
						);

						?>

				</div>

			<?php

					$enable_support = property_exists( $webinar_data, 'enable_support' ) ? $webinar_data->enable_support : 'no';

					webinarignition_display_option(
						$input_get['id'],
						$enable_support,
						esc_html__( 'Enable Support Staff', 'webinarignition' ),
						'enable_support',
						esc_html__( 'You can choose to have support to answer questions on your behalf', 'webinarignition' ),
						esc_html__( 'Disable', 'webinarignition' ) . ' [no],' . esc_html__( 'Enable', 'webinarignition' ) . ' [yes]'
					);

					?>

			<div class="enable_support" id="enable_support_yes">

				<div class="editSection">

						<div class="inputTitle" style="width: auto; border: none;">
								<div class="inputTitleCopy" ><?php esc_html_e( 'Add Support Members', 'webinarignition' ); ?></div>
								<div class="inputTitleHelp" ><?php esc_html_e( 'You can choose users who will receive email notifications.', 'webinarignition' ); ?></div>
						</div>

						<br clear="left" >

				</div>

				<?php $support_staff_count = ( property_exists( $webinar_data, 'support_staff_count' ) && (int) $webinar_data->support_staff_count > 0 ) ? $webinar_data->support_staff_count : '0'; ?>

				<input type="hidden" name="support_staff_count" id="support_staff_count" value="<?php echo esc_attr( $support_staff_count ); ?>">

				<?php if ( ! empty( $support_staff_count ) ) : ?>

					<?php

					for ( $x = 1; $x <= $support_staff_count; $x++ ) {

						$member_email_str      = 'member_email_' . $x;
						$member_first_name_str = 'member_first_name_' . $x;
						$member_last_name_str  = 'member_last_name_' . $x;

						if ( isset( $webinar_data->{'member_email_' . $x} ) && isset( $webinar_data->{'member_first_name_' . $x} ) && isset( $webinar_data->{'member_last_name_' . $x} ) ) {

							$member = get_user_by( 'email', $webinar_data->{'member_email_' . $x} );

							if ( $member && property_exists( $webinar_data, $member_email_str ) && property_exists( $webinar_data, $member_first_name_str ) && property_exists( $webinar_data, $member_last_name_str ) ) :

								$member_email      = $webinar_data->{$member_email_str};
								$member_first_name = $webinar_data->{$member_first_name_str};
								$member_last_name  = $webinar_data->{$member_last_name_str};

								?>

								<div class="newMember">
									<div class="editSection" style="border-bottom:none;">
										<div class="inputTitle">
										<div class="inputTitleCopy"><?php esc_html_e( 'Support Staff Email', 'webinarignition' ); ?></div>
										<div class="inputTitleHelp"><?php esc_html_e( 'This is the email address of the support staff member', 'webinarignition' ); ?></div>
										</div>
										<div class="inputSection">
										<input class="inputField elem member_email" placeholder="<?php esc_html_e( 'supportmember@website.com', 'webinarignition' ); ?>" type="email" value="<?php echo esc_attr( $member_email ); ?>" name="member_email_<?php echo esc_attr( $x ); ?>">
										</div>
										<br clear="left" >
									</div>
									<div class="editSection" style="border-bottom:none;">
										<div class="inputTitle">
										<div class="inputTitleCopy"><?php esc_html_e( 'Support Staff First Name', 'webinarignition' ); ?></div>
										</div>
										<div class="inputSection">
										<input class="inputField elem member_first_name" placeholder="<?php esc_html_e( 'John', 'webinarignition' ); ?>" type="text" value="<?php echo esc_attr( $member_first_name ); ?>" name="member_first_name_<?php echo esc_attr( $x ); ?>">
										</div>
										<br clear="left">
									</div>
									<div class="editSection">
										<div class="inputTitle">
										<div class="inputTitleCopy"><?php esc_html_e( 'Support Staff Last Name', 'webinarignition' ); ?></div>
										</div>
										<div class="inputSection">
										<input class="inputField elem member_last_name" placeholder="<?php esc_html_e( 'Doe', 'webinarignition' ); ?>" type="text" value="<?php echo esc_attr( $member_last_name ); ?>" name="member_last_name_<?php echo esc_attr( $x ); ?>">
										</div>
										<br clear="left" >
										<div class="deleteMember">
										<button type="button" class="btn btn-danger"><?php esc_html_e( 'Delete Support Staff Member', 'webinarignition' ); ?></button>
										</div>
									</div>
								</div>

								<?php


							endif;

						}//end if
					}//end for

					?>


				<?php endif; ?>

				<div class="editSection" id="addMemberButtonContainer">
						<div class="inputTitle">
								<div class="inputTitleCopy"><?php esc_html_e( 'Add Support Member', 'webinarignition' ); ?></div>
								<div class="inputTitleHelp"><?php esc_html_e( 'You can choose to add another support staff member. Doing so will create a new WordPress user.', 'webinarignition' ); ?></div>
						</div>
						<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
								<a href="#" id="add_support_member" class="opts-grp-send_host_questions_file optionSelector" data-total=""><i class="icon-plus iconOpts "></i> <?php esc_html_e( 'Add Support Member', 'webinarignition' ); ?></a>
						</div>
						<br clear="left">
				</div>

			</div>



		</div>

			<?php

					$enable_multiple_hosts = property_exists( $webinar_data, 'enable_multiple_hosts' ) ? $webinar_data->enable_multiple_hosts : 'no';

					webinarignition_display_option(
						$input_get['id'],
						$enable_multiple_hosts,
						esc_html__( 'Enable Multiple Hosts', 'webinarignition' ),
						'enable_multiple_hosts',
						esc_html__( 'You can choose to have more than one host', 'webinarignition' ),
						esc_html__( 'Disable', 'webinarignition' ) . ' [no],' . esc_html__( 'Enable', 'webinarignition' ) . ' [yes]'
					);

					?>

			<div class="enable_multiple_hosts" id="enable_multiple_hosts_yes">

				<div class="editSection">

						<div class="inputTitle" style="width: auto; border: none;">
								<div class="inputTitleCopy" ><?php esc_html_e( 'Add Host Members', 'webinarignition' ); ?></div>
								<div class="inputTitleHelp" ><?php esc_html_e( 'You can choose to add multiple hosts.', 'webinarignition' ); ?></div>
						</div>

						<br clear="left" >

				</div>

				<?php $host_member_count = ( isset( $webinar_data->host_member_count ) && (int) $webinar_data->host_member_count > 0 ) ? $webinar_data->host_member_count : '0'; ?>

				<input type="hidden" name="host_member_count" id="host_member_count" value="<?php echo esc_attr( $host_member_count ); ?>">

				<?php if ( ! empty( $host_member_count ) ) : ?>

					<?php

					for ( $x = 1; $x <= $host_member_count; $x++ ) {

						$host_member_email_str      = 'host_member_email_' . $x;
						$host_member_first_name_str = 'host_member_first_name_' . $x;
						$host_member_last_name_str  = 'host_member_last_name_' . $x;


						if ( isset( $webinar_data->{'host_member_email_' . $x} ) && isset( $webinar_data->{'host_member_first_name_' . $x} ) && isset( $webinar_data->{'host_member_last_name_' . $x} ) ) {

							$host_member = get_user_by( 'email', $webinar_data->{'host_member_email_' . $x} );

							if ( $host_member && property_exists( $webinar_data, $host_member_email_str ) && property_exists( $webinar_data, $host_member_first_name_str ) && property_exists( $webinar_data, $host_member_last_name_str ) ) :

								$host_member_email      = $webinar_data->{$host_member_email_str};
								$host_member_first_name = $webinar_data->{$host_member_first_name_str};
								$host_member_last_name  = $webinar_data->{$host_member_last_name_str};

								?>

								<div class="newMember">
									<div class="editSection" style="border-bottom:none;">
										<div class="inputTitle">
										<div class="inputTitleCopy"><?php esc_html_e( 'Member Email', 'webinarignition' ); ?></div>
										<div class="inputTitleHelp"><?php esc_html_e( 'This is the email address of the host staff host_member', 'webinarignition' ); ?></div>
										</div>
										<div class="inputSection">
										<input class="inputField elem host_member_email" placeholder="<?php esc_html_e( 'host_member_email@example.com', 'webinarignition' ); ?>" type="email" value="<?php echo esc_attr( $host_member_email ); ?>" name="host_member_email_<?php echo esc_attr( $x ); ?>">
										</div>
										<br clear="left" >
									</div>
									<div class="editSection" style="border-bottom:none;">
										<div class="inputTitle">
										<div class="inputTitleCopy"><?php esc_html_e( 'Member First Name', 'webinarignition' ); ?></div>
										</div>
										<div class="inputSection">
										<input class="inputField elem host_member_first_name" placeholder="<?php esc_html_e( 'John', 'webinarignition' ); ?>" type="text" value="<?php echo esc_attr( $host_member_first_name ); ?>" name="host_member_first_name_<?php echo esc_attr( $x ); ?>">
										</div>
										<br clear="left">
									</div>
									<div class="editSection">
										<div class="inputTitle">
										<div class="inputTitleCopy"><?php esc_html_e( 'Member Last Name', 'webinarignition' ); ?></div>
										</div>
										<div class="inputSection">
										<input class="inputField elem host_member_last_name" placeholder="<?php esc_html_e( 'Doe', 'webinarignition' ); ?>" type="text" value="<?php echo esc_attr( $host_member_last_name ); ?>" name="host_member_last_name_<?php echo esc_attr( $x ); ?>">
										</div>
										<br clear="left">
									</div>
									<div class="editSection">
										<div class="deleteMember">
										<button type="button" class="btn btn-danger"><?php esc_html_e( 'Delete Additional Host', 'webinarignition' ); ?></button>
										</div>
									</div>
								</div>

								<?php


							endif;

						}//end if
					}//end for

					?>

				<?php endif; ?>

				<div class="editSection" id="addHostMemberButtonContainer">
						<div class="inputTitle">
								<div class="inputTitleCopy"><?php esc_html_e( 'Add Host Member', 'webinarignition' ); ?></div>
								<div class="inputTitleHelp"><?php esc_html_e( 'You can choose to add another host. Doing so will create a new WordPress user with the same privileges as the main host.', 'webinarignition' ); ?></div>
						</div>
						<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
								<a href="#" id="add_host_member" class="opts-grp-send_host_questions_file optionSelector" data-total=""><i class="icon-plus iconOpts"></i> <?php esc_html_e( 'Add Host Member', 'webinarignition' ); ?></a>
						</div>
						<br clear="left">
				</div>

			</div>


	</div>

	<?php

	// if ( 'AUTO' === $webinar_data->webinar_date ) {

	// 	webinarignition_display_edit_toggle(
	// 		'comments',
	// 		esc_html__( 'TXT Reminder - Send out TXT MSG 1 Hour Before Live...', 'webinarignition' ),
	// 		'we_edit_twilio',
	// 		esc_html__( 'This is a txt msg that is sent out 1 hour before live...', 'webinarignition' )
	// 	);

	// } else {

	// 	webinarignition_display_edit_toggle(
	// 		'comments',
	// 		esc_html__( 'TXT Reminder - Send out TXT MSG Before Live...', 'webinarignition' ),
	// 		'we_edit_twilio',
	// 		esc_html__( 'This is a txt msg that is sent out before live...', 'webinarignition' )
	// 	);


	// }

	?>

	<!-- <div id="we_edit_twilio" class="we_edit_area">
		<?php
		// webinarignition_display_option(
		// 	$input_get['id'],
		// 	$webinar_data->email_twilio,
		// 	esc_html__( 'Toggle TXT Notification', 'webinarignition' ),
		// 	'email_twilio',
		// 	esc_html__( 'You can have this notification sent out or not...', 'webinarignition' ),
		// 	esc_html__( 'Enable TXT Notification', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable TXT Notification', 'webinarignition' ) . ' [off]'
		// );
		?>
		<div class="email_twilio" id="email_twilio_on">
			<?php
			// webinarignition_display_field(
			// 	$input_get['id'],
			// 	$webinar_data->twilio_id,
			// 	esc_html__( 'Twilio Account ID', 'webinarignition' ),
			// 	'twilio_id',
			// 	__( "This is your twilio account ID... <br><b><a href='https://www.twilio.com/' target='_blank'>Create Twilio Account</a></b>", 'webinarignition' ),
			// 	esc_html__( 'Twilio Account SID', 'webinarignition' )
			// );
			// webinarignition_display_field(
			// 	$input_get['id'],
			// 	$webinar_data->twilio_token,
			// 	esc_html__( 'Twilio Account Token', 'webinarignition' ),
			// 	'twilio_token',
			// 	esc_html__( 'This is your account token...', 'webinarignition' ),
			// 	esc_html__( 'Twilio Account Token', 'webinarignition' ),
			// 	'password'
			// );
			// webinarignition_display_field(
			// 	$input_get['id'],
			// 	$webinar_data->twilio_number,
			// 	esc_html__( 'Twilio Phone Number', 'webinarignition' ),
			// 	'twilio_number',
			// 	__( 'This is your twilio number that you want the txt msg to be from...<br><b>Example: +19253456789</b>', 'webinarignition' ),
			// 	'+1XXXXXXXXXX'
			// );
			// webinarignition_display_textarea(
			// 	$input_get['id'],
			// 	$webinar_data->twilio_msg,
			// 	esc_html__( 'Txt Message', 'webinarignition' ),
			// 	'twilio_msg',
			// 	esc_html__( 'This is the txt message that is sent out, shortcode with {LINK} for the URL, but we suggest you creating a tinyURL...', 'webinarignition' ),
			// 	esc_html__( 'TXT MSG here...', 'webinarignition' )
			// );

			// webinarignition_display_info(
			// 	esc_html__( 'Send Test SMS:', 'webinarignition' ),
			// 	esc_html__( 'Send a test text message to check your Twilio configuration.', 'webinarignition' )
			// 	. '<div>'
			// 	. '<div style="color: #FF0038">' . esc_html__( 'NOTE: You MUST Save & Update your settings before testing.', 'webinarignition' ) . '</div>'
			// 	. '<input type="text" id="webinarignition_test_sms_number" class="inputField" style="width: 200px !important; height: 40px !important; margin-top: 7px; line-height:inherit !important;" /> <div style="margin-top:6px;display: inline-block" id="webinarignition_test_sms" class="grey-btn">' . esc_html__( 'Send SMS', 'webinarignition' ) . '</div>'
			// 	. '</div>'
			// );
			// ?>
			// <?php
			// 	webinarignition_display_date_picker(
			// 		$input_get['id'],
			// 		$webinar_data->email_twilio_date,
			// 		'm-d-Y',
			// 		esc_html__( 'Scheduled Date', 'webinarignition' ),
			// 		'email_twilio_date',
			// 		esc_html__( 'This is the date on which this txt message is out sent out...', 'webinarignition' ),
			// 		esc_html__( 'Scheduled Date...', 'webinarignition' ),
			// 		$webinar_date_format
			// 	);

			// 	webinarignition_display_time_picker(
			// 		$input_get['id'],
			// 		$webinar_data->email_twilio_time,
			// 		esc_html__( 'Scheduled Time', 'webinarignition' ),
			// 		'email_twilio_time',
			// 		esc_html__( 'This is the time that the will be sent out on the date (above)...', 'webinarignition' )
			// 	);

			// 	webinarignition_display_option(
			// 		$input_get['id'],
			// 		$webinar_data->email_twilio_status,
			// 		esc_html__( 'Status Of TXT MSG', 'webinarignition' ),
			// 		'email_twilio_status',
			// 		esc_html__( 'This will tell if this TXT MSG was sent out or not.', 'webinarignition' ),
			// 		esc_html__( 'TXT MSG Queued', 'webinarignition' ) . ' [queued], ' . esc_html__( 'TXT MSG Has Been Sent', 'webinarignition' ) . ' []'
			// 	);
				?>
		</div>
	</div> -->
	<?php
	webinarignition_display_edit_toggle(
		'file-alt',
		esc_html__( 'Logs', 'webinarignition' ),
		'we_view_log',
		esc_html__( 'View the notification transmission logs', 'webinarignition' )
	);

	$log_types = array( WebinarIgnition_Logs::LIVE_EMAIL, WebinarIgnition_Logs::LIVE_SMS );

	if ( 'AUTO' === $webinar_data->webinar_date ) {
		$log_types                      = array( WebinarIgnition_Logs::AUTO_EMAIL, WebinarIgnition_Logs::AUTO_SMS );
		$webinar_data->webinar_timezone = false;
	}
	?>
	<div id="we_view_log" class="we_edit_area" data-campaign-id="<?php echo absint( $input_get['id'] ); ?>" data-webinar-id="<?php echo absint( $webinar_data->id ); ?>">
	<?php 
		$webinar_timezone = isset($webinar_data->webinar_timezone) ? $webinar_data->webinar_timezone : false; 
		webinarignition_show_logs( $input_get['id'], $log_types, 1, $webinar_timezone ); 
	?>
	</div>

	<div class="bottomSaveArea">
		<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php esc_html_e( 'Save & Update', 'webinarignition' ); ?></a>
	</div>

</div>
