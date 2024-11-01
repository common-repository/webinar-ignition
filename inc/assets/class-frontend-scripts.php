<?php
/**
 * Responsible plugin frontend assets.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Webinar_Ignition_Frontend_Scripts' ) ) {

	/**
	 * Plugin common assets manager.
	 *
	 * @since 3.08.1
	 */
	class Webinar_Ignition_Frontend_Scripts extends Webinar_Ignition_Common_Scripts {

		private static $instance;

		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			add_action( 'wp_enqueue_scripts', array( self::$instance, 'scripts' ) );

			return self::$instance;
		}

		public function scripts() {
			$webinar_page = get_query_var( 'webinarignition_page' );
			if ( empty( $webinar_page ) && ! webinarignition_is_webinar_common_page() ) {
				return;
			}

			do_action( 'webinarignition_before_frontend_scripts' );

			$this->webinarignition_register_frontend_scripts();

			$localizeable_data = array(
				'translations' => array(
					'verify_email'             => esc_html__( 'Please enter a valid email and try again...', 'webinarignition' ),
					'answer_sent_successfully' => esc_html__( 'Your answer was successfully sent', 'webinarignition' ),
					'something_went_wrong'     => esc_html__( 'Something went wrong', 'webinarignition' ),
					'text_saving'              => esc_html__( 'Saving...', 'webinarignition' ),
					'save_broadcast'           => esc_html__( 'Saved Broadcast Message Settings', 'webinarignition' ),
					'save_on_air'              => esc_html__( 'Save On Air Settings', 'webinarignition' ),
					'empty_table'              => esc_html__( 'No data available in table', 'webinarignition' ),
					'info'                     => esc_html__( 'Showing _START_ to _END_ of _TOTAL_ entries', 'webinarignition' ),
					'info_empty'               => esc_html__( 'Showing 0 to 0 of 0 entries', 'webinarignition' ),
					'length_menu'              => esc_html__( 'Show _MENU_ entries', 'webinarignition' ),
					'loading_records'          => esc_html__( 'Loading...', 'webinarignition' ),
					'text_processing'          => esc_html__( 'Processing...', 'webinarignition' ),
					'search'                   => esc_html__( 'Search:', 'webinarignition' ),
					'zero_records'             => esc_html__( 'No matching records found', 'webinarignition' ),
					'paginate_first'           => esc_html__( 'First', 'webinarignition' ),
					'paginate_last'            => esc_html__( 'Last', 'webinarignition' ),
					'paginate_next'            => esc_html__( 'Next', 'webinarignition' ),
					'paginate_previous'        => esc_html__( 'Previous', 'webinarignition' ),
					'search_your_leads'        => esc_html__( 'Search Through Your Leads Here...', 'webinarignition' ),
					'del_lead_confirmation'    => esc_html__( 'Are You Sure You Want To Delete This Lead?', 'webinarignition' ),
					'paste_iframe_code'        => esc_html__(
						'Paste This iFrame Code On Your Download Page:',
						'webinarignition'
					),
					
				),
				'assets'      => array(
					'watermark' => esc_url( WEBINARIGNITION_URL . 'images/watermark-webinar.png' ),
				),
				'ip' => esc_html( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) ), 
				'current_time' => current_time('mysql')
			);

				$webinar_id = absint( get_query_var( 'webinar_id' ) );
				$webinar    = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

				if ( $webinar ) {
					$webinar_record                      = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $webinar_id );
					$localizeable_data['webinar_record'] = $webinar_record;
					$localizeable_data['webinar']        = $webinar;
					$localizeable_data['webinar_type']   = webinarignition_is_auto( $webinar ) ? 'evergreen' : 'live';
					$localizeable_data['lid'] = webinarignition_getLid( $webinar_id );

					// Leads to order id.
					$order_id                      = WebinarignitionManager::webinarignition_is_paid_webinar( $webinar ) && WebinarignitionManager::webinarignition_get_paid_webinar_type( $webinar ) === 'woocommerce' && WebinarignitionManager::webinarignition_url_has_valid_wc_order_id();
					$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
					
					$localizeable_data['order_id'] = $order_id;

					// Webinar user role.
					if ( is_user_logged_in() ) {
						$webinar_user_role_manager = new Webinar_User_Role_Manager( $webinar );
						$localizeable_data['webinar_user_roles'] = array(
							'is_support' => $webinar_user_role_manager->webinarignition_is_support(),
							'is_host'    => $webinar_user_role_manager->webinarignition_is_host(),
							'is_admin'   => $webinar_user_role_manager->webinarignition_is_admin(),
						);

						$lead_id = ! empty( $_GET['lid'] ) ? sanitize_text_field( $_GET['lid'] ) : '';

						if ( ! empty( $lead_id ) ) {
							$lead_info = webinarignition_get_lead_info( $lead_id, $webinar, false );

							$localizeable_data['lead_id'] = $lead_id;
							$localizeable_data['lead_info'] = $lead_info;
						}

						$is_auto_login_enabled = absint(get_option( 'webinarignition_registration_auto_login', 1 )) == 1;
						$is_user_registered  = $is_auto_login_enabled && ( Webinar_Ignition_Helper::webinarignition_is_user_registered_on_this_webinar( $webinar->id, $lead_id ) || is_user_logged_in() );
						$is_webinar_page     = get_query_var( 'webinarignition_page' ) === 'webinar';
						$is_alt_webinar_page = isset( $webinarignition_page ) && 'webinar' === $webinarignition_page;
						$localizeable_data[ 'is_auto_login_enabled'] = $is_auto_login_enabled;
						$localizeable_data[ 'is_user_registered'] = $is_user_registered;
						$localizeable_data[ 'is_webinar_page'] = $is_webinar_page;
						$localizeable_data[ 'is_alt_webinar_page'] = $is_alt_webinar_page;


						$webinar_page = get_query_var( 'webinarignition_page' );

						if ( ! empty( $webinar_page ) ) {
							$localizeable_data['webinar_page'] = $webinar_page;
						}
					}
				}

				if ( isset($webinar->webinar_ld_share) && 'on' === $webinar->webinar_ld_share ) {
					wp_enqueue_script( 'linkedin-platform', '//platform.linkedin.com/in.js', array(), WEBINARIGNITION_VERSION, false );
				}

				// if ( 'disabled' !== $webinar->live_stats ) {
				// 	wp_enqueue_script(
				// 		'webinarignition-live-counter',
				// 		WEBINARIGNITION_URL . 'inc/lp/livecounter.php',
				// 		array( 'jquery' ),
				// 		WEBINARIGNITION_VERSION
				// 	);
				// }

				if ( $webinar && 'AUTO' !== $webinar->webinar_date && 'paid' === $webinar->paid_status ) {
					$localizeable_data['paid_code'] = array( 'code' => $webinar->paid_code );
				}

				$frontend_dependencies = wp_script_is( 'linkedin-platform', 'enqueued' ) ? array( 'jquery', 'linkedin-platform' ) : array( 'jquery' );
				global $pagenow;
				$screen =  $pagenow;
				
				if ( isset( $_GET['console'] ) ) {
					wp_enqueue_script(
						'webinarignition-admin-dashboard',
						WEBINARIGNITION_URL . 'assets/webinarignition-admin-dashboard.js',
						array( 'jquery', 'webinarignition_tz_js' ),
						WEBINARIGNITION_VERSION,
						array( 'in_footer' => true )
					);
				}
				wp_register_script(
					'webinarignition-frontend',
					WEBINARIGNITION_URL . 'assets/webinarignition-frontend.js',
					$frontend_dependencies,
					WEBINARIGNITION_VERSION,
					true
				);

				wp_register_script(
					'webinarignition-webinar-inline',
					WEBINARIGNITION_URL . 'assets/webinarignition-webinar-inline.js',
					array('jquery'),
					WEBINARIGNITION_VERSION,
					true
				);


				wp_register_script(
					'webinarignition-frontend-countdown',
					WEBINARIGNITION_URL . 'assets/webinarignition-frontend-countdown.js',
					array( 'jquery' ),
					WEBINARIGNITION_VERSION,
					true
				);

			wp_localize_script(
				'webinarignition-frontend',
				'WEBINARIGNITION',
				apply_filters(
					'webinarignition_frontend_localizeable_scripts',
					array_merge(
						$this->webinarignition_get_default_localizeable(),
						$localizeable_data
					)
				)
			);
			wp_localize_script(
				'webinarignition_registration_js',
				'WEBINARIGNITION',
				apply_filters(
					'webinarignition_frontend_localizeable_scripts',
					array_merge(
						$this->webinarignition_get_default_localizeable(),
						$localizeable_data
					)
				)
			);
			wp_localize_script(
				'webinarignition-webinar-inline',
				'WEBINARIGNITION',
				apply_filters(
					'webinarignition_frontend_localizeable_scripts',
					array_merge(
						$this->webinarignition_get_default_localizeable(),
						$localizeable_data
					)
				)
			);
			global $post;

			if ( 'auto_register' === get_query_var( 'webinarignition_page' ) || webinarignition_is_webinar_common_page() ) {
				
				wp_enqueue_script(
					'webinarignition-auto-register',
					WEBINARIGNITION_URL . 'assets/webinarignition-auto-register.js',
					array( 'jquery' ),
					WEBINARIGNITION_VERSION,
					false
				);
				wp_localize_script(
					'webinarignition-auto-register',
					'WEBINARIGNITION',
					apply_filters(
						'webinarignition_auto_reg_localizeable_scripts',
						array_merge(
							$this->webinarignition_get_default_localizeable(),
							$localizeable_data
						)
					)
				);
			}

			wp_enqueue_script( 'webinarignition-frontend' );
			if((isset($_GET['webinar']) && isset($_GET['lid'])) || ( isset($_GET['live']) && isset($_GET['lid']) )){
				wp_enqueue_script( 'webinarignition-webinar-inline' );
			}

			$this->webinarignition_load_webinar_page_scripts();

			do_action( 'webinarignition_after_frontend_scripts' );
		}

		public function webinarignition_load_registration_page_scripts( $webinar_data, $template_number, $dynamically_generated_css ) {
			// <head> css
			wp_enqueue_style( 'webinarignition_bootstrap' );
			wp_enqueue_style( 'webinarignition_foundation' );
			wp_enqueue_style( 'webinarignition_intlTelInput' );
			wp_enqueue_style( 'webinarignition_main' );
			wp_enqueue_style( 'webinarignition_font-awesome' );
			wp_enqueue_style( 'webinarignition_css_utils' );
			wp_enqueue_style( 'webinarignition_ss' );

			if ( '02' === $template_number || '03' === $template_number ) {
				wp_enqueue_style( 'webinarignition_ss' );
			}

			if ( ( empty( $webinar_data->lp_cta_type ) || 'video' === $webinar_data->lp_cta_type ) && ! empty( $webinar_data->lp_cta_video_url ) ) {
				wp_enqueue_style( 'webinarignition_video_css' );
			}

			// <head> js
			wp_enqueue_script( 'moment' );
			wp_enqueue_script( 'webinarignition_js_utils' );
			wp_enqueue_script( 'webinarignition_cookie_js' );

			// footer scripts
			if ( ( empty( $webinar_data->lp_cta_type ) || $webinar_data->lp_cta_type == 'video' ) && ! empty( $webinar_data->lp_cta_video_url ) ) {
				wp_enqueue_script( 'webinarignition_video_js' );
			}

			wp_enqueue_script( 'webinarignition_intlTelInput_js' );
			wp_enqueue_script( 'webinarignition_frontend_js' );
			wp_enqueue_script( 'webinarignition_tz_js' );
			wp_enqueue_script( 'webinarignition_luxon_js' );

			if ( ! empty( $dynamically_generated_css ) ) :
				wp_add_inline_style( 'webinarignition_main', esc_html( $dynamically_generated_css ) );
			endif;

			if ( ! empty( $webinar_data->custom_lp_css ) ) :
				wp_add_inline_style( 'webinarignition_main', esc_html( $webinar_data->custom_lp_css ) );
			endif;

			if ( ! empty( $webinar_data->custom_lp_js ) ) :
				wp_add_inline_script( 'wi_js_utils', $webinar_data->custom_lp_js );
			endif;

			if ( ! empty( $webinar_data->stripe_publishable_key ) ) :
				wp_enqueue_script( 'webinarignition_stripe_js' );
				$setPublishableKey = 'Stripe.setPublishableKey("' . $webinar_data->stripe_publishable_key . '")';
				wp_add_inline_script( 'webinarignition_stripe_js', $setPublishableKey );
			endif;

			if ( ! empty( $webinar_data->paid_status ) && ( $webinar_data->paid_status == 'paid' ) ) :
				$paid_js_code = "var paid_code = {code: $webinar_data->paid_code}";
				wp_add_inline_script( 'wi_js_utils', $paid_js_code );
			endif;

			$wi_parsed         = webinarignition_parse_registration_page_data( get_query_var( 'webinar_id' ), $webinar_data );
			$isSigningUpWithFB = false;
			$fbUserData        = array();

			if ( ! empty( $webinar_data->fb_id ) && ! empty( $webinar_data->fb_secret ) && isset( $_GET['code'] ) ) :
				include 'lp/fbaccess.php';

				/**
				 * @var $user_info
				 */
				$isSigningUpWithFB   = true;
				$fbUserData['name']  = $user_info['name'];
				$fbUserData['email'] = $user_info['email'];
			endif;

			$wi_parsed['isSigningUpWithFB'] = $isSigningUpWithFB;
			$wi_parsed['fbUserData']        = $fbUserData;
			$window_webinarignition         = 'window.webinarignition = ' . wp_json_encode( $wi_parsed, JSON_HEX_APOS ) . ';';

			wp_enqueue_script( 'webinarignition_registration_js' );
			wp_add_inline_script( 'webinarignition_registration_js', $window_webinarignition, 'before' );
		}

		public function webinarignition_load_webinar_closed_page_header_scripts( $webinar_data ) {
			wp_register_script(
				'webinarignition-webinar-closed',
				WEBINARIGNITION_URL . 'assets/webinarignition-webinar-closed.js',
				array( 'jquery' ),
				WEBINARIGNITION_VERSION,
				true
			);

			wp_register_style(
				'webinarignition-webinar-closed',
				WEBINARIGNITION_URL . 'assets/webinarignition-webinar-closed.css',
				'',
				WEBINARIGNITION_VERSION,
				'all'
			);

			wp_add_inline_script( 'webinarignition-webinar-closed', $webinar_data->custom_replay_js );
			wp_add_inline_style( 'webinarignition-webinar-closed', $webinar_data->custom_replay_css );

			wp_enqueue_script( 'webinarignition-webinar-closed' );
		}

		public function webinarignition_load_thank_you_page_inline_scripts() {
			wp_enqueue_script(
				'webinarignition-thank-you-page',
				WEBINARIGNITION_URL . 'assets/webinarignition-thank-you-page.js',
				array( 'jquery' ),
				WEBINARIGNITION_VERSION,
				true
			);
		}

		public function webinarignition_load_order_track_from_leads_script() {
			$webinar_id = absint( get_query_var( 'webinar_id' ) );
			$webinar_id = ! empty( $_GET['trkorder'] ) ? trim( $_GET['trkorder'] ) : $webinar_id;

			if ( ! $webinar_id ) {
				return;
			}

			wp_enqueue_script(
				'webinarignition-order-track',
				WEBINARIGNITION_URL . 'assets/webinarignition-order-track.js',
				array( 'jquery' ),
				WEBINARIGNITION_VERSION,
				true
			);
		}

		public function webinarignition_load_thankyou_cp_preview_scripts( $webinar ) {
			if ( ! empty( $webinar->custom_ty_js ) ) {
				wp_add_inline_script( 'webinarignition-frontend', $webinar->custom_ty_js );
			}

			if ( ! empty( $webinar->custom_ty_css ) ) {
				wp_add_inline_style( 'webinarignition-frontend', $webinar->custom_ty_css );
			}
		}

		public function webinarignition_load_webinar_page_scripts() {
			$webinar_page = get_query_var( 'webinarignition_page' );

			add_action( 'webinarignition_webinar_closed_before_head', array( $this, 'webinarignition_load_webinar_closed_page_header_scripts' ) );
			add_action( 'webinarignition_order_track_from_leads', array( $this, 'webinarignition_load_order_track_from_leads_script' ) );
			add_action( 'webinarignition_thankyou_cp_page_header', array( $this, 'webinarignition_load_thankyou_cp_preview_scripts' ) );

			switch ( $webinar_page ) {
				case 'registration':
					add_action( 'webinarignition/webinar_page_header', array( $this, 'webinarignition_load_registration_page_scripts' ), 10, 3 );
					break;
				case 'auto_register':
					break;

				case 'preview_auto_thankyou':
					$this->webinarignition_load_thank_you_page_inline_scripts();
					break;

				default:
			}
		}
	}
}//end if
