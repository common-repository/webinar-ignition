<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



add_action( 'admin_menu', 'webinarignition_admin_menu' );

function webinarignition_admin_menu() {
	$statusCheck = WebinarignitionLicense::webinarignition_get_license_level();
	$is_basic_pro = ( in_array( $statusCheck->switch, array( 'pro', 'basic' ), true ) && ! in_array( $statusCheck->name, array( 'ultimate_powerup_tier2a', 'ultimate_powerup_tier3a' ), true ) );

	$icon_image = 'data:image/svg+xml;base64,' . base64_encode('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve">  <image id="image0" x="0" y="0"
			href="data:image/png;base64,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" />
		</svg>');

	if ( ! $is_basic_pro && 'free' !== $statusCheck->switch ) {
		$icon_image = 'data:image/svg+xml;base64,' . base64_encode('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve">  <image id="image0" x="0" y="0"
			href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAALGPC/xhBQAAACBjSFJN
		AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAADAFBMVEVHcEz/KAD/MwD/NwD/
		NwD5SAD/QQD/NQD/PQD/RwD/VAD/AAD/PwD/OAD1cAD9WgD/QgD/PQD/QgDugAD7VQD/QgD/OQD/
		PwD/UAD/aQD/aQDtkQD2cwD/XQD/RQD/RgD7awD+ggD+lwD2vwD+mgD+egD/MwD/SgD/lAD80gD7
		7AD+qAD5hAD1bwD/PQD/QAD/PgD3kgD6pAD9vwD83gD+qgD7rAD9jwD/PwD/jgD71AD/+QD72QD/
		XQD/VAD6fgD9lwD87wD/dwD/bAD/VwD4UgD/NwD8rwD87gD8yQD/cgD9dAD/RwD9wQD94wD6xwD9
		vAD7mwD/XQD9gwD82QD7uAD9bAD/cwD9igD86AD80AD/fgD/TwD/XgD9egD9oAD+8QD/dAD92gD9
		sgD9xAD/twD7jADzkQD/AAD/KAD5iQD7mgD/0AD/1AD/kwD/jQD/iQD+ogD/3QD94AD/QwD/QgD/
		WwD8wwD/igD/bQD/WwD/aAD/cAD/2gD/TQD/YwD/WAD9rQD/+gD/pAD/ZgD/awD9mQD/YgD/lwD+
		qQD/YAD/3QD/cAD/ZAD/XQD6zwD71AD8ngD/VwD/awD/pAD/ogD9sAD/bAD72AD/cQD9gwD/2gD/
		ZQD/XgD/YQD/4AD/wwD/XgD/YQD/MwD/kwD/WwD9VQD/cAD/0QD/YAD/TgD/XQD/RwD+eQD/RAD/
		VwD/UgD/ZQD/SgD/YAD/pAD/OAD/QwD/UwD/TgD/VAD/fQD/qwD/ngD/SAD/NgD/OwD/NwD/PwD/
		cAD/sAD/OwD/UwD/xAD/4gD/hAD/iwD/rgD/aQD/TgD/RwD/OgD/TwD/OgD/OgD/SAD/uAD/1gD/
		vwD/ewD/RAD/SgD/PwD/NwD/PgD/iAD/sQD/ZwD/RQD/PgD/JAD/VwD/TAD/JwD/MQD/SAD/RAD/
		MgD/MQD/QwD+9gD//AD//wD/+QD/9QD//gD/+wD97AD/8gD/+AD/7QD/6gD/5gD/7wD/6AD/0gD/
		ygD/4wD/3AD/2AD///+dN55pAAAA63RSTlMAAgUICgcDDRQgHwEbJFk2Di5BnU4YBAwrT2/YfEoG
		IlJ/qPamdw8esuX9z45SHRATkMjc99HQgjqY8P7zYi94ufyZZ0MoCdb98KGFMuv7993TZp/82pRz
		wPrwvT5IlNz+s/rz9uvUrDUylODz++bX2ez9+UUXk+/q1MPG2f6dTKHl/vDW0OLZ6uin+uLKzvj4
		6c7J4vbrqPm55PuqRqL999rBxtyviZ/7Ppbnvs+3lYPgl4L34rSonNHx7vjp2s7K3vDwuZL9/vT1
		+thhq7JqJzRy/P7+67+BPKrU7vzkYIux2tjEoFt3mZxP9TLk4wAAAAFiS0dE/6UH8sUAAAAHdElN
		RQfmBg8XOTDq3r8qAAACzElEQVQ4y2NgQABGJmYWFlY2BpyAiZ2Dk4udlRunAhYeXj5+HgFG7LLc
		3GwcgkLCIoKiYozYzOBmExOXkJSSlpGVEJdjw6KCUU5AXkFRSVlFVUFejRmLAjZxdT5FDU0tbSUd
		XT19MUYMa8QMBA2NjF+/MTE1M7dQZ2GVE2NEs0FP0tJK67X1GxsbFVt+PR59FjFUA9jt7B1s3lq/
		s37vaOrk7KLrasCK4gc3HkkVd48374DA2lPby1vYRZ4FRYG+j7277zsw+ODnHxBoFMSvj+RQbkYe
		hWArv9dgBR/fh7z7FBqmwMkiB4sWbjZmifCIyA/W7969ASn4bP0mKlo1hkucCe4Fjti4+ISPQMlP
		78DueJcQ7yTDrw9zJxsLr32i7wfrL++SklM+gR36NjUtPUNUDmaCWmZWds4X6/e5efkFhUVfvgCt
		KvZ3KimFBQW3gEtZeYL1x6KKyqrqmora928+vn7rW1dvAAtMbrXYhsY3TV+aW1rbWmraO75+e9/0
		pbOrXh8eDAL1XZ3vPnT39Pb1T5g4afKUqd/fvJnWVc8OVyAuO73z/duU9qoWkzczertmzpr94V3n
		nHpxhIK586bNf7tg4aLF5X5Lli5bXrPi7fvC6bGIwGZeuart/dui1X1r1q5dt37DnI2z33zv3LTS
		DRGXdpsnzX7zbsvWSWu2bd+xdOeuH9YrWjfYycEVMKoHrdv9/v23PXv37T9w4OChFQmfDx8JUkdK
		MmouRyuPHf/689ieE7tOnqr9+u3X6VUlakipTo73zNme39+Pf//zt/bvnz+1vyrOnb/AhJAHhuXF
		S9t6Lv/7d+Vfbe2/v1evXb9xUx8l2bJxWFy6dfvOnru/Lx+7OvXekRslosgGAAETx8X7Dx4eeHTv
		3r3HT55eEuLByKJsApw3nz1/8OLliweXXglyyGHmHW4xllJeH4sYCx95DjdEGQAAr6Uo+YUUh98A
		AAAldEVYdGRhdGU6Y3JlYXRlADIwMjItMDYtMTVUMjA6NTc6NDgrMDM6MDCVGi7RAAAAJXRFWHRk
		YXRlOm1vZGlmeQAyMDIyLTA2LTE1VDIwOjU3OjQ4KzAzOjAw5EeWbQAAAABJRU5ErkJggg==" />
		</svg>');
	}//end if

	add_menu_page(
		'WebinarIgnition',
		'WebinarIgnition',
		'manage_options',
		'webinarignition-dashboard',
		'webinarignition_dashboard',
		$icon_image,
		2
	);

	add_submenu_page(
		'webinarignition-dashboard',
		__( 'Webinars', 'webinarignition' ),
		__( 'Webinars', 'webinarignition' ),
		'manage_options',
		'webinarignition-dashboard&webinars',
		'webinarignition_dashboard'
	);

	add_submenu_page(
		'webinarignition-dashboard',
		__( 'Create Webinar', 'webinarignition' ),
		__( 'Create Webinar', 'webinarignition' ),
		'manage_options',
		esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&create' ) )
	);

	add_submenu_page(
		'webinarignition-dashboard',
		__( 'Webinarignition Settings', 'webinarignition' ),
		__( 'Settings', 'webinarignition' ),
		'manage_options',
		'webinarignition_settings',
		'webinarignition_settings_submenu_page'
	);

	if ( ! wp_validate_boolean( $statusCheck->is_registered ) && false === wp_validate_boolean( get_option( 'wi_optin_confirmed' ) ) ) {
		if ( Webinar_Optin::webinarignition_is_free( $statusCheck ) || Webinar_Optin::webinarignition_is_essential( $statusCheck ) ) {
			global $submenu;

			$submenu['webinarignition-dashboard'][] = array(//phpcs:ignore
				sprintf( '<span class="opt-in-menu-item">%s</span>', __( 'Opt-in 4 success', 'webinarignition' ) ),
				'manage_options',
				'admin.php?page=webinarignition-dashboard#opt-in-box',
			);
		}
	}

	add_submenu_page(
		'webinarignition-dashboard',
		__(
			'Solution & Support',
			'webinarignition'
		),
		__( 'Support', 'webinarignition' ),
		'manage_options',
		'webinarignition_support',
		'webinarignition_support_submenu_page'
	);

	add_submenu_page(
		'webinarignition-dashboard',
		__( 'Webinarignition Changelog', 'webinarignition' ),
		__( 'Changelog', 'webinarignition' ),
		'manage_options',
		'webinarignition_changelog',
		'webinarignition_changelog_submenu_page'
	);
}

function webinarignition_settings_submenu_page() {

	$tab        = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS );
	$active_tab = isset( $tab ) ? $tab : 'general';

	if ( 'smtp-settings' === $active_tab ) {
		return webinarignition_display_smtp_settings_tab();
	}

	if ( 'spam-test' === $active_tab ) {
		return webinarignition_display_spam_test_tab();
	}

	if ( 'email-templates' === $active_tab ) {
		return webinarignition_display_email_templates_tab();
	}

	if ( ( ! defined( 'WEBINAR_IGNITION_DISABLE_WEBHOOKS' ) || WEBINAR_IGNITION_DISABLE_WEBHOOKS === false ) && WebinarignitionPowerups::webinarignition_is_ultimate() ) {
		if ( 'webhooks' === $active_tab ) {
			return webinarignition_display_webhooks_tab();
		}
	}

	if ( 'general' === $active_tab ) {
		return webinarignition_display_general_settings_tab();
	}
}

/**
 * Table list output.
 */
function webinar_ignition_table_list_output() {
	$wiAdminWebhooksListTable = new WebinarIgnition_Admin_Webhooks_List_Table();
	$wiAdminWebhooksListTable->prepare_items();
	$wiAdminWebhooksListTable->display();
}
function webinar_ignition_table_list_form() {
	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/webhooks_form.php';
}

function webinarignition_display_webhooks_tab() {
	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/webhooks.php';
}

function webinarignition_display_smtp_settings_tab() {

	$protocols = array( 'http://', 'https://', 'http://www.', 'https://www.', 'www.' );
	$site_domain = str_replace( $protocols, '', site_url() );

	if ( isset( $_POST['submit-webinarignition-settings'] ) && check_admin_referer( 'webinarignition-settings-submenu-save', 'webinarignition-settings-submenu-save-nonce' ) ) {

		$webinarignition_smtp_host             = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_host' ) );
		$webinarignition_smtp_port             = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_port' ) );
		$webinarignition_smtp_protocol         = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_protocol' ) );
		$webinarignition_smtp_user             = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_user' ) );
		$webinarignition_smtp_pass             = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_pass' ) );
		$webinarignition_smtp_name             = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_name' ) );
		$webinarignition_smtp_name             = empty( $webinarignition_smtp_name ) ? get_option( 'webinarignition_smtp_name', get_bloginfo( 'name' ) ) : $webinarignition_smtp_name;
		$webinarignition_smtp_email            = sanitize_email( filter_input( INPUT_POST, 'webinarignition_smtp_email' ) );
		$webinarignition_smtp_email            = empty( $webinarignition_smtp_email ) ? 'webinar@' . $site_domain : $webinarignition_smtp_email;
		$webinarignition_reply_to_email        = sanitize_email( filter_input( INPUT_POST, 'webinarignition_reply_to_email' ) );
		$webinarignition_smtp_connect          = (int) sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_connect' ) );
		$webinarignition_smtp_settings_global  = (int) sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_settings_global' ) );

		update_option( 'webinarignition_smtp_host', $webinarignition_smtp_host );
		update_option( 'webinarignition_smtp_port', $webinarignition_smtp_port );
		update_option( 'webinarignition_smtp_protocol', $webinarignition_smtp_protocol );
		update_option( 'webinarignition_smtp_user', $webinarignition_smtp_user );
		update_option( 'webinarignition_smtp_pass', $webinarignition_smtp_pass );
		update_option( 'webinarignition_smtp_name', $webinarignition_smtp_name );
		update_option( 'webinarignition_smtp_email', $webinarignition_smtp_email );
		update_option( 'webinarignition_reply_to_email', $webinarignition_reply_to_email );
		update_option( 'webinarignition_smtp_connect', $webinarignition_smtp_connect );
		update_option( 'webinarignition_smtp_settings_global', $webinarignition_smtp_settings_global );

		if ( ! empty( $webinarignition_smtp_connect ) ) {

			$smtp_test_results_array = webinarignition_test_smtp_phpmailer( $webinarignition_smtp_host, $webinarignition_smtp_port, $webinarignition_smtp_user, $webinarignition_smtp_pass );

			if ( ( 0 === (int) $smtp_test_results_array['status'] ) || ( empty( $webinarignition_smtp_connect ) ) ) {
				update_option( 'webinarignition_smtp_connect', 0 );
			} else {
				update_option( 'webinarignition_smtp_connect', 1 );
			}
		}
	}//end if

	$webinarignition_smtp_host                = get_option( 'webinarignition_smtp_host' );
	$webinarignition_smtp_port                = get_option( 'webinarignition_smtp_port' );
	$webinarignition_smtp_protocol            = get_option( 'webinarignition_smtp_protocol' );
	$webinarignition_smtp_user                = get_option( 'webinarignition_smtp_user' );
	$webinarignition_smtp_pass                = get_option( 'webinarignition_smtp_pass' );
	$webinarignition_smtp_name                = get_option( 'webinarignition_smtp_name' );
	$webinarignition_smtp_name                = empty( get_option( 'webinarignition_smtp_name' ) ) ? get_bloginfo( 'name' ) : $webinarignition_smtp_name;
	$webinarignition_smtp_email               = get_option( 'webinarignition_smtp_email' );
	$webinarignition_smtp_email               = empty( $webinarignition_smtp_email ) ? 'webinar@' . $site_domain : $webinarignition_smtp_email;
	$webinarignition_reply_to_email           = get_option( 'webinarignition_reply_to_email', 'webinar@' . $site_domain );
	$webinarignition_smtp_connect             = get_option( 'webinarignition_smtp_connect', 0 );
	$webinarignition_smtp_settings_global     = get_option( 'webinarignition_smtp_settings_global', 0 );

	$is_from_email_disabled                   = ! empty( $webinarignition_smtp_connect ) ? 'disabled' : '';

	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/smtp.php';
}


function webinarignition_display_spam_test_tab() {

	if ( isset( $_POST['webinarignition_spam_test_email'] ) ) {

		check_admin_referer( 'webinarignition-spam-test-save', 'webinarignition-spam-test-save-nonce' );

		$spam_test_email_address        = sanitize_email( filter_input( INPUT_POST, 'webinarignition_spam_test_email' ) );

		$email_data                   = new stdClass();
		$email_data->email_subject    = __( 'WebinarIgnition Spammyness Test', 'webinarignition' );
		$email_data->emailheading     = __( 'This Is The Message Heading', 'webinarignition' );
		$email_data->emailpreview     = __( 'This is the preview text', 'webinarignition' );

		ob_start();
		include_once WEBINARIGNITION_PATH . 'templates/emails/html-email-template-preview.php';
		$email_data->bodyContent      = ob_get_clean();

		$email                          = new WI_Emails();
		$email_data->bodyContent        = $email->webinarignition_build_email( $email_data );

		$email_data->bodyContent        = str_replace( '{YEAR}', gmdate( 'Y' ), $email_data->bodyContent );
		$headers                        = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>' );

		$emailSent                      = wp_mail( $spam_test_email_address, $email_data->email_subject, $email_data->bodyContent, $headers );
	}//end if

	$locale = substr( get_locale(), 0, 2 );

	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/spam-test.php';
}


function webinarignition_display_email_templates_tab() {

	if ( isset( $_POST['submit-webinarignition-email-templ-settings'] ) && check_admin_referer( 'webinarignition-template-settings-save', 'webinarignition-template-settings-save-nonce' ) ) {

		update_option( 'webinarignition_show_email_header_img', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_show_email_header_img' ) ) );
		update_option( 'webinarignition_email_logo_url', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_logo_url' ) ) );
		update_option( 'header_img_algnmnt', sanitize_text_field( filter_input( INPUT_POST, 'header_img_algnmnt' ) ) );
		update_option( 'webinarignition_enable_header_img_max_width', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_enable_header_img_max_width' ) ) );
		update_option( 'webinarignition_email_logo_max_width', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_logo_max_width' ) ) );

		update_option( 'webinarignition_email_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_background_color' ) ) );
		update_option( 'webinarignition_email_body_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_body_background_color' ) ) );
		update_option( 'webinarignition_email_text_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_text_color' ) ) );
		update_option( 'webinarignition_body_text_line_height', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_body_text_line_height' ) ) );

		update_option( 'webinarignition_email_templates_from_name', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_templates_from_name' ) ) );
		update_option( 'webinarignition_email_templates_from_email', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_templates_from_email' ) ) );

		update_option( 'webinarignition_headings_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_headings_color' ) ) );

		update_option( 'webinarignition_headings_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_headings_color' ) ) );

		update_option( 'webinarignition_email_font_size', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_font_size' ) ) );

		update_option( 'webinarignition_heading_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_heading_background_color' ) ) );
		update_option( 'webinarignition_heading_text_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_heading_text_color' ) ) );

		update_option( 'webinarignition_email_signature', filter_input( INPUT_POST, 'webinarignition_email_signature' ) );
	}//end if

	$default_webinarignition_email_logo_url             = WEBINARIGNITION_URL . 'images/wi-email-design-logo.png';
	$webinarignition_show_email_header_img              = get_option( 'webinarignition_show_email_header_img' );
	$webinarignition_email_logo_url                     = get_option( 'webinarignition_email_logo_url' );
	$header_img_algnmnt                                 = get_option( 'header_img_algnmnt' );
	$webinarignition_enable_header_img_max_width        = get_option( 'webinarignition_enable_header_img_max_width', 'yes' );
	$webinarignition_email_logo_max_width               = get_option( 'webinarignition_email_logo_max_width', 265 );

	$webinarignition_email_background_color             = get_option( 'webinarignition_email_background_color', '#ffffff' );
	$webinarignition_email_body_background_color        = get_option( 'webinarignition_email_body_background_color', '#ededed' );
	$webinarignition_email_text_color                   = get_option( 'webinarignition_email_text_color', '#3f3f3f' );

	$webinarignition_email_font_size                    = get_option( 'webinarignition_email_font_size' );
	$webinarignition_body_text_line_height              = get_option( 'webinarignition_body_text_line_height', 'normal' );

	$webinarignition_email_templates_from_name              = get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) );
	$webinarignition_email_templates_from_email              = get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) );

	$webinarignition_headings_color                     = get_option( 'webinarignition_headings_color', '#ffffff' );

	$webinarignition_heading_background_color           = get_option( 'webinarignition_heading_background_color', '#000' );
	$webinarignition_heading_text_color                 = get_option( 'webinarignition_heading_text_color', '#fff' );

	$webinarignition_email_signature                    = get_option( 'webinarignition_email_signature', '' );
	$statusCheck                                        = WebinarignitionLicense::webinarignition_get_license_level();

	$wp_editor_settings = array(
		'wpautop' => true,
		'textarea_name' => 'webinarignition_email_signature',
		'tinymce' => array(
			'height' => '250', // the height of the editor
		),
	);

	wp_enqueue_script( 'wp-color-picker' );

	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/email-templates.php';
}

function webinarignition_display_general_settings_tab() {
	if ( isset( $_POST['submit-webinarignition-general-settings'] ) && check_admin_referer( 'webinarignition-general-settings-save', 'webinarignition-general-settings-save-nonce' ) ) {
		update_option( 'webinarignition_show_footer_branding', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_show_footer_branding' ) ) );
		update_option( 'webinarignition_branding_copy', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_branding_copy' ) ) );
		update_option( 'webinarignition_affiliate_link', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_affiliate_link' ) ) );
		update_option( 'show_webinarignition_footer_logo', sanitize_text_field( filter_input( INPUT_POST, 'show_webinarignition_footer_logo' ) ) );
		update_option( 'webinarignition_branding_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_branding_background_color' ) ) );
		update_option( 'webinarignition_auto_clean_log_db', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_auto_clean_log_db' ) ) );

		$email_verification = isset($_POST['webinarignition_email_verification']) ? sanitize_text_field( $_POST['webinarignition_email_verification'] ) : '';
		$email_verification = $email_verification ? absint($email_verification) : 0;
		update_option( 'webinarignition_email_verification', absint($email_verification) );
		update_option( 'webinarignition_email_verification_template', filter_input( INPUT_POST, 'webinarignition_email_verification_template' ) );

		$post_webinarignition_registration_auto_login = sanitize_text_field( $_POST['webinarignition_registration_auto_login'] );
		$post_webinarignition_registration_auto_login = $post_webinarignition_registration_auto_login ? absint($post_webinarignition_registration_auto_login) : 0;
		update_option( 'webinarignition_registration_auto_login', $post_webinarignition_registration_auto_login );

		$post_webinarignition_auto_login_password_email = absint( filter_input( INPUT_POST, 'webinarignition_auto_login_password_email' ) );
		if ( 0 === $post_webinarignition_registration_auto_login ) {
			$post_webinarignition_auto_login_password_email = 0;
		}
		update_option( 'webinarignition_auto_login_password_email', $post_webinarignition_auto_login_password_email );

		$post_webinarignition_hide_top_admin_bar = absint( filter_input( INPUT_POST, 'webinarignition_hide_top_admin_bar' ) );
		update_option( 'webinarignition_hide_top_admin_bar', $post_webinarignition_hide_top_admin_bar );

		update_option( 'webinarignition_footer_text', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_footer_text' ) ) );
		update_option( 'webinarignition_footer_text_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_footer_text_color' ) ) );
	}//end if

	$webinarignition_show_footer_branding           = get_option( 'webinarignition_show_footer_branding' );
	$show_webinarignition_footer_logo               = get_option( 'show_webinarignition_footer_logo' );
	$webinarignition_branding_copy                  = get_option( 'webinarignition_branding_copy' );
	$webinarignition_affiliate_link                 = get_option( 'webinarignition_affiliate_link' );

	$webinarignition_branding_background_color      = get_option( 'webinarignition_branding_background_color', '#000' );
	$webinarignition_auto_clean_log_db              = get_option( 'webinarignition_auto_clean_log_db', 'no' );

	$statusCheck                                    = WebinarignitionLicense::webinarignition_get_license_level();

	$webinarignition_registration_auto_login        = absint(get_option( 'webinarignition_registration_auto_login', 1 ));
	$webinarignition_email_verification             = get_option( 'webinarignition_email_verification', 0 );
	$webinarignition_email_verification_template    = WebinarignitionManager::webinarignition_get_webinarignition_email_verification_template();
	$webinarignition_auto_login_password_email      = absint( get_option( 'webinarignition_auto_login_password_email', 0 ) );
	$webinarignition_hide_top_admin_bar             = absint( get_option( 'webinarignition_hide_top_admin_bar', 1 ) );

	$webinarignition_footer_text                    = get_option( 'webinarignition_footer_text', '' );
	$webinarignition_footer_text_color              = get_option( 'webinarignition_footer_text_color', '#3f3f3f' );

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';
	$webinars = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM %i",
			$table_db_name
		),
		ARRAY_A
	);

	if ( is_array( $webinars ) && ! empty( $webinars ) ) {

		$all_webinars                   = array_reverse( $webinars );
		$latest_webinar_id              = $all_webinars[0]['ID'];
		$latest_webinar_data            = WebinarignitionManager::webinarignition_get_webinar_data( $latest_webinar_id );
		if ( $latest_webinar_data && ! isset( $latest_webinar_data->webinar_permalink ) ) {
			$latest_webinar_data->webinar_permalink = WebinarignitionManager::get_permalink( $latest_webinar_data, 'webinar' );
		}
		$latest_webinar_permalink       = $latest_webinar_data->webinar_permalink;
		$latest_webinar_permalink       = add_query_arg( 'preview', 'true', $latest_webinar_permalink );
	}

	wp_enqueue_script( 'wp-color-picker' );

	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/general.php';
}



function webinarignition_support_submenu_page() {

	$lang = get_locale();
	if ( strlen( $lang ) > 0 ) {
		$lang = explode( '_', $lang )[0];
	}

	$support_link = ( 'en' === $lang ) ? 'https://webinarignition.tawk.help/' : 'https://webinarignition.tawk.help/' . $lang;

	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/support.php';
}


function webinarignition_changelog_submenu_page() {

	$changelog_link = get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=webinar-ignition&section=changelog';

	include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/changelog.php';
}
