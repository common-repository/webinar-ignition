<?php
/**
 * Admin View: Email Template Preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
		<tr>
			<td class="bg_white">
				<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td class="bg_white">
							<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td>
										<div class="heading-section">
											<p><?php echo sprintf( esc_html__( 'Hi %s.', 'webinarignition' ), '{FIRSTNAME}' ); ?></p>

											<p><?php esc_html_e( '%%INTRO%%', 'webinarignition' ); ?></p>

											<p><?php echo esc_html( sprintf( __( 'Date: Join us live on %s', 'webinarignition' ), '{DATE}' ) ); ?></p>

											<p><?php echo esc_html( sprintf( __( 'Webinar Topic: %s', 'webinarignition' ), '{TITLE}' ) ); ?></p>

											<p><?php echo esc_html( sprintf( __( 'Hosts: %s', 'webinarignition' ), '{HOST}' ) ); ?></p>

											<p><strong><?php esc_html_e( 'How To Join The Webinar', 'webinarignition' ); ?></strong></p>

											<p><?php esc_html_e( 'Click the following link to join.', 'webinarignition' ); ?></p>

											<p style="text-align:center;"><a target="_blank" href="/"><?php esc_html_e( 'Join the webinar', 'webinarignition' ); ?></a></p>

											<p><?php esc_html_e( 'You will be connected to video via your browser using your computer, tablet, or mobile phone\'s microphone and speakers. A headset is recommended.', 'webinarignition' ); ?></p>

											<p><strong><?php esc_html_e( 'Webinar Requirements', 'webinarignition' ); ?></strong></p>

											<p><?php esc_html_e( 'A recent browser version of Mozilla Firefox, Google Chrome, Apple Safari, Microsoft Edge or Opera.', 'webinarignition' ); ?></p>

											<p><?php esc_html_e( 'You can join the webinar on mobile, tablet or desktop.', 'webinarignition' ); ?></p>

										</div>
									</td>
								</tr>

							</table>

						</td>
					</tr>

				</table>

			</td>
		</tr>

	</table>
</div>    

