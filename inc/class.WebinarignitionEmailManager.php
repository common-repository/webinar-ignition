<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WebinarignitionEmailManager {

	public static function webinarignition_replace_email_body_placeholders( $webinar_data, $lead_id, $email_body, $additional_params = '', $args = array() ) {
		WebinarignitionManager::webinarignition_set_locale( $webinar_data );
		$lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
		$lead_id_url = ! empty( $lead ) ? $lead->hash_ID : '';

		if ( ! empty( $webinar_data->footer_branding ) && 'show' === $webinar_data->footer_branding ) {
			$email_body = str_replace( '{AFFILIATE}', $webinar_data->footer_branding_url, $email_body );
		}

		$name = ! empty( $lead->name ) ? $lead->name : '';

		$email_body = str_replace( '{LEAD_NAME}', $name, $email_body );
		$email_body = str_replace( '{NAME}', $name, $email_body );
		$email_body = str_replace( '{TITLE}', $webinar_data->webinar_desc, $email_body );
		$email_body = str_replace( '{HOST}', $webinar_data->webinar_host, $email_body );
		$email_body = str_replace( '{YEAR}', gmdate( 'Y' ), $email_body );
		$email_body = str_replace( '{EMAIL}', ! empty( $lead->email ) ? $lead->email : '', $email_body );

		$default_webinar_link  = $webinar_data->webinar_permalink . ( strstr( $webinar_data->webinar_permalink, '?' ) ? '&' : '?' );
		$default_webinar_link .= "live=1&lid={$lead_id_url}";
		$webinar_landing_page = $default_webinar_link; // Setting default link from object, no sure if correct always

		$webinar_post_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_data->id );

		if ( isset( $webinar_data->custom_webinar_page ) && ! empty( $webinar_data->custom_webinar_page ) ) { // Check if custom webinar page is set
			$webinar_post_id = absint( $webinar_data->custom_webinar_page );
		}

		if ( ! empty( $webinar_post_id ) ) {
			$webinar_post_status = get_post_status( $webinar_post_id );
			if ( wp_validate_boolean( $webinar_post_status ) ) { // post exists
				$webinar_landing_page = get_the_permalink( $webinar_post_id );
			}
		}

		if ( ! empty( $lead_id_url ) ) {
			$webinar_landing_page = add_query_arg( 'confirmed', '', $webinar_landing_page );
			$webinar_landing_page = add_query_arg( 'lid', $lead_id_url, $webinar_landing_page );
		}

		if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
			$webinar_landing_page = add_query_arg( $webinar_data->paid_code, '', $webinar_landing_page );
		}

		if ( ! empty( $additional_params ) ) {
			$webinar_landing_page = add_query_arg( $additional_params, '', $webinar_landing_page );
		}

		if ( empty( $args['is_sms'] ) ) {
			$webinar_landing_page = sprintf( '<a target="_blank" href="%s">%s</a>', $webinar_landing_page, __( 'Join the webinar!', 'webinarignition' ) );
		}

		$email_body = str_replace( '{LINK}', $webinar_landing_page, $email_body );

		WebinarignitionManager::webinarignition_restore_locale( $webinar_data );

		return $email_body;
	}

	public static function webinarignition_get_email_head() {
		ob_start();
		?><!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="x-apple-disable-message-reformatting">
			<style>

				html,
				body {
					margin: 0 auto !important;
					padding: 0 !important;
					height: 100% !important;
					width: 100% !important;
					background: #f1f1f1;
				}

				* {
					-ms-text-size-adjust: 100%;
					-webkit-text-size-adjust: 100%;
				}

				/* What it does: Centers email on Android 4.4 */
				div[style*="margin: 16px 0"] {
					margin: 0 !important;
				}

				table,
				td {
					mso-table-lspace: 0pt !important;
					mso-table-rspace: 0pt !important;
				}


				table {
					border-spacing: 0 !important;
					border-collapse: collapse !important;
					table-layout: fixed !important;
					margin: 0 auto !important;
				}


				img {
					-ms-interpolation-mode:bicubic;
				}


				a {
					text-decoration: none;
				}

				*[x-apple-data-detectors],  /* iOS */
				.unstyle-auto-detected-links *,
				.aBn {
					border-bottom: 0 !important;
					cursor: default !important;
					color: inherit !important;
					text-decoration: none !important;
					font-size: inherit !important;
					font-family: inherit !important;
					font-weight: inherit !important;
					line-height: inherit !important;
				}

				.a6S {
					display: none !important;
					opacity: 0.01 !important;
				}

				.im {
					color: inherit !important;
				}

				img.g-img + div {
					display: none !important;
				}

				/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
				@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
					u ~ div .email-container {
						min-width: 320px !important;
					}
				}
				/* iPhone 6, 6S, 7, 8, and X */
				@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
					u ~ div .email-container {
						min-width: 375px !important;
					}
				}
				/* iPhone 6+, 7+, and 8+ */
				@media only screen and (min-device-width: 414px) {
					u ~ div .email-container {
						min-width: 414px !important;
					}
				}

				.bg_white{
					background: #ffffff;
				}
				.bg_light{
					background: #fafafa;
				}
				.bg_black{
					background: #000000;
				}
				.bg_dark{
					background: rgba(0,0,0,.8);
				}
				.email-section{
					padding:2.5em;
				}

				h1,h2,h3,h4,h5,h6{
					font-family: "Nunito Sans", sans-serif;
					color: #000000;
					margin-top: 0;
				}
				a{
					color: #f5564e;
				}
			</style>
		</head>
		<?php
		return ob_get_clean();
	}
}