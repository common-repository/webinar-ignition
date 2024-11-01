<?php
/**
 * Responsible plugin admin assets.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Webinar_Ignition_Admin_Scripts' ) ) {

	/**
	 * Plugin common assets manager.
	 *
	 * @since 3.08.1
	 */
	class Webinar_Ignition_Admin_Scripts extends Webinar_Ignition_Common_Scripts {

		private static $instance;

		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			add_action( 'admin_enqueue_scripts', array( self::$instance, 'scripts' ) );

			return self::$instance;
		}

		public function scripts() {
			$pluginName = 'webinarignition';
			$screen = get_current_screen();
			// TODO: add limit screen asset loading.

			wp_register_script(
				'webinarignition-admin',
				WEBINARIGNITION_URL . 'assets/webinarignition-admin.js',
				array( 'jquery' ),
				WEBINARIGNITION_VERSION,
				false
			);

			$localizeable_data = array(
				'settings' => array(
					'general_settings'  => array(
						'affiliate_button_text'  => esc_attr__( 'Your affiliate link should be to freemius!', 'webinarignition' ),
						'powered_by_text_alert'  => esc_attr__( 'Your branding copy should contain "Webinar Powered By WebinarIgnition"!', 'webinarignition' ),
						'powered_by_button_text' => esc_attr__( 'Powered By WebinarIgnition', 'webinarignition' ),
					),
					'email_settings'    => array(
						'media_upload_title' => esc_attr__( 'Insert image', 'webinarignition' ),
						'media_update_title' => esc_attr__( 'Use this image', 'webinarignition' ),
					),
					'webhooks_settings' => array(
						'confirm_delete' => esc_attr__( 'Are you sure you want to delete?', 'webinarignition' ),
					),
				),
			);

			$localizeable_data['translations'] = array(
				'member_email'                    => esc_html__( 'Member Email', 'webinarignition' ),
				'member_email_help'               => esc_html__( 'This is the email address of the additional host member', 'webinarignition' ),
				'host_email'                      => esc_html__( 'host_member_email@example.com', 'webinarignition' ),
				'host_first_name'                 => esc_html__( 'Host Member First Name', 'webinarignition' ),
				'host_last_name'                  => esc_html__( 'Host Member Last Name', 'webinarignition' ),
				'member_first_name'               => esc_html__( 'John', 'webinarignition' ),
				'member_last_name'                => esc_html__( 'Doe', 'webinarignition' ),
				'delete_member'                   => esc_html__( 'Delete Member', 'webinarignition' ),
				'send_notifications'              => esc_html__( 'Send User Notification', 'webinarignition' ),
				'delete_additional_host'          => esc_html__( 'Delete Additional Host', 'webinarignition' ),
				'support_staff_email_label'       => esc_html__( 'Support Staff Email', 'webinarignition' ),
				'support_staff_last_name'         => esc_html__( 'Support Staff Last Name', 'webinarignition' ),
				'support_staff_email_placeholder' => esc_html__( 'This is the email address of the support staff', 'webinarignition' ),
				'member_email_placeholder'        => esc_html__( 'supportmember@example.com', 'webinarignition' ),
				'support_staff_first_name'        => esc_html__( 'Support Staff First Name', 'webinarignition' ),
				'provide_phone_number'            => esc_html__( 'Provide a phone number to send the SMS to.', 'webinarignition' ),
				'sms_sent'                        => esc_html__( 'SMS has been sent.', 'webinarignition' ),
				'saving' => esc_html__( 'Saving...', 'webinarignition' ),
				'ar_integration_test' => esc_html__( 'AR Integration Test', 'webinarignition' ),
				'in_order_to_test_ar' => esc_html__( 'In order to test your AR integration setup, these steps may help:', 'webinarignition' ),
				'click_the_button' => __( 'Click the <strong>test button</strong> below.', 'webinarignition' ),
				'in_the_new_window' => __( 'In the new window, fill in the registration form with dummy info for testing, then click <strong>register</strong>.', 'webinarignition' ),
				'if_all_went_well' => esc_html__( 'If all went well, the data should be in your autoresponder list. Check your autoresponder list to confirm.', 'webinarignition' ),
				'test' => esc_html__( 'Test', 'webinarignition' ),
				'integration_tutorial' => esc_html__( 'Integration Tutorials', 'webinarignition' ),
				'done' => esc_html__( 'Done', 'webinarignition' ),
				'delete_campaign_confirm' => esc_html__( 'Are You Sure You Want To Delete This Campaign?', 'webinarignition' ),

				'delete_lead_confirm' => esc_html__( 'Are You Sure You Want To Delete This Lead?', 'webinarignition' ),

				'reset_campaign_stats_confirm' => esc_html__( 'Are You Sure You Want To Reset ALL The View Stats For This Campaign?', 'webinarignition' ),

				'changes_not_saved_warning' => esc_html__( 'Your changes are not saved!', 'webinarignition' ),

				'save_and_update' => esc_html__( 'Save & Update', 'webinarignition' ),

				'search_leads_placeholder' => esc_html__( 'Search Leads Here...', 'webinarignition' ),
			);

			$localizeable_data['images'] = array(
				'ajax_loader' => WEBINARIGNITION_URL . 'images/ajax-loader.gif',
			);

			$localizeable_data['url'] = array(
				'dashboard' => site_url() . "/wp-admin/?page=$pluginName-dashboard&id=",
				'admin_page' => site_url() . "/wp-admin/admin.php?page=$pluginName-dashboard",
				'page_dashboard' => site_url(). "/wp-admin/admin.php?page=$pluginName-dashboard&id=",
			);

			if ( 'toplevel_page_webinarignition-dashboard' === $screen->id && ! empty( $_GET['id'] ) ) {
				$webinar_id     = absint( sanitize_text_field($_GET['id'] ));
				$webinar_record = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $webinar_id );
				$webinar   = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
				$lead_timezone   = isset($webinar->lead_timezone) ? $webinar->lead_timezone : '';
				$webinar_timezone= webinarignition_get_webinar_timezone($webinar, null);
				$autoTZ_org = trim($lead_timezone);
				if ($autoTZ_org === '') {
					$autoTZ_org = $webinar_timezone;
					if ($autoTZ_org === '') {
							$autoTZ_org = wp_timezone_string();
					}
				}
				$dtz           = new DateTimeZone( $autoTZ_org );
				$time_in_sofia = new DateTime( 'now', $dtz );
				$offset        = $dtz->getOffset( $time_in_sofia ) / 3600;
				if ( ! empty( $offset ) ) {
					$localizeable_data['autoTZ'] = $offset;
				}

				if ( ! empty( $webinar_record ) ) {
					$localizeable_data['webinar_record'] = $webinar_record;
				}
				
				if ( ! empty( $webinar ) ) {
					$localizeable_data['webinar'] = $webinar;
				}
				if ( ! empty( $webinar ) ) {
					$localizeable_data['webinar_date_js_format'] = webinarignition_convert_wp_to_js_date_format($webinar->id);
				}
			}

			wp_localize_script(
				'webinarignition-admin',
				'WEBINARIGNITION',
				apply_filters(
					'webinarignition_admin_localizeable_scripts',
					array_merge(
						$this->webinarignition_get_default_localizeable(),
						$localizeable_data
					)
				)
			);
			if ( 'webinarignition_page_webinarignition_settings' === $screen->id ) {

				wp_enqueue_script(
					'webinarignition-settings',
					WEBINARIGNITION_URL . 'assets/webinarignition-settings.js',
					array( 'jquery' ),
					WEBINARIGNITION_VERSION,
					array( true )
				);
			}
			
			if ( 'toplevel_page_webinarignition-dashboard' === $screen->id ) {
				wp_enqueue_script(
					'webinarignition-webinar',
					WEBINARIGNITION_URL . 'assets/webinarignition-webinar.js',
					array( 'jquery' ),
					WEBINARIGNITION_VERSION,
					array( 'in_footer' => true )
				);

				wp_enqueue_script(
					'webinarignition-admin-dashboard',
					WEBINARIGNITION_URL . 'assets/webinarignition-admin-dashboard.js',
					array( 'jquery' ),
					WEBINARIGNITION_VERSION,
					array( 'in_footer' => true )
				);

				// // Localize the script with new data Should remove this code if not used anywhere

				// wp_localize_script('webinarignition-admin-dashboard', 'webinarignition_ajax', array(

				// 	'ajax_url' => admin_url('admin-ajax.php'),

				// 	'nonce'    => wp_create_nonce('webinarignition_ajax_nonce'),

				// ));

				
			}

			wp_enqueue_script( 'webinarignition-admin' );
		}
	}
}//end if
