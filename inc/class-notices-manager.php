<?php
/**
 * Responsible managing notices conditions to display.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Webinar_Ignition_Notices_Manager' ) ) {

	/**
	 * Plugin license based notices manager.
	 *
	 * @since 3.08.1
	 */
	class Webinar_Ignition_Notices_Manager {

		/**
		 * Development mode to make license
		 * checking optional if needed.
		 *
		 * @since 3.08.1
		 */
		const DEV_MODE = false;

		/**
		 * Available pro license levels.
		 *
		 * @since 3.08.1
		 */
		const AVAILABLE_PRO_LICENSE_LEVELS = array(
			'ultimate_powerup',
			'ultimate_powerup_tier2a',
			'ultimate_powerup_tier3a',
		);

		/**
		 * Pro version slug after generated from freemius.
		 *
		 * @since 3.08.1
		 */
		const PRO_SLUG = 'webinarignition-premium/webinarignition.php';

		/**
		 * Free version slug after generated from freemius.
		 *
		 * @since 3.08.1
		 */
		const FREE_SLUG               = 'webinar-ignition/webinarignition.php';
		const SINGLE_PLUGIN_FREE_SLUG = 'webinarignition/webinarignition.php';

		/**
		 * Class constructor.
		 *
		 * @since 3.08.1
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'webinarignition_notice_assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'webinarignition_notice_assets' ) );
		}

		/**
		 * Get activated webinarignition plugin current license data.
		 *
		 * @since 3.08.1
		 * @return object The freemius license data object.
		 */
		private static function webinarignition_get_license_data() {
			return WebinarignitionLicense::webinarignition_get_license_level();
		}

		/**
		 * Get activated webinarignition plugin current license status.
		 *
		 * @since 3.08.1
		 * @return string The freemius license switch status.
		 */
		private static function webinarignition_get_license_status() {
			return isset( self::webinarignition_get_license_data()->switch ) ? self::webinarignition_get_license_data()->switch : 'free';
		}

		/**
		 * Checking if the activated webinarignition plugin folder name contains pro slug.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_is_pro_slug() {
			return self::PRO_SLUG === WEBINARIGNITION_PLUGIN_BASENAME;
		}

		/**
		 * Checking if the activated webinarignition plugin is free version.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_is_free() {
			return ( self::SINGLE_PLUGIN_FREE_SLUG === WEBINARIGNITION_PLUGIN_BASENAME || self::FREE_SLUG === WEBINARIGNITION_PLUGIN_BASENAME ) && 'free' === self::webinarignition_get_license_status();
		}

		/**
		 * Checking if the activated webinarignition plugin is pro version.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_is_pro() {
			return self::webinarignition_is_pro_slug() && ( 'pro' === self::webinarignition_get_license_status() || 'enterprise_powerup' === self::webinarignition_get_license_status() );
			// return self::webinarignition_is_pro_slug() && ( 'pro' === self::webinarignition_get_license_status() || 'enterprise_powerup' === self::webinarignition_get_license_status() );
		}

		/**
		 * Checking if the activated pro plugin doesn't have license activated.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_is_essential() {
			return 'pro' === self::webinarignition_get_license_data()->switch && 'ultimate_powerup_tier1a' === self::webinarignition_get_license_data()->name;
		}

		/**
		 * Checking if the activated using legacy/former license.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_is_former_license_holder() {
			return ! empty( self::webinarignition_get_license_status()->keyused ) && 'basic' === self::webinarignition_get_license_status();
		}

		/**
		 * Checking if the activated pro plugin doesn't have license activated.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_is_pro_user_but_license_not_activated() {
			return self::webinarignition_is_pro_slug() && 'free' === self::webinarignition_get_license_status();
		}

		public static function webinarignition_is_free_plugin_but_license_activated() {
			return ! self::webinarignition_is_pro_slug() && in_array( self::webinarignition_get_license_data()->name, self::AVAILABLE_PRO_LICENSE_LEVELS, true );
		}

		/**
		 * Responsible for pro feature splitting.
		 *
		 * @param string|null $context The specific context for particular condition.
		 *
		 * @since 3.08.1
		 * @return bool
		 */
		public static function webinarignition_only_pro_users_can_use( $context = null ) {
			$freemius_split = webinarignition_fs()->is__premium_only() && webinarignition_fs()->can_use_premium_code();
			$pro_condition  = in_array( self::webinarignition_get_license_data()->name, self::AVAILABLE_PRO_LICENSE_LEVELS, true ) && self::webinarignition_is_pro();

			if ( 'lead' === $context || 'cta-alignment' === $context ) {
				return ( $freemius_split && self::webinarignition_is_essential() ) || ( $freemius_split && $pro_condition ) || self::DEV_MODE;
			}
			if ( 'license-pro' === $context || 'license-ultimate' === $context ) {
				$license_update_plan = ( $freemius_split && self::webinarignition_is_essential() ) || ( $freemius_split && $pro_condition ) || self::DEV_MODE;
			}

			return ( $freemius_split && $pro_condition ) || self::DEV_MODE;
		}

		/**
		 * Display notices based on license levels.
		 *
		 * @since 3.08.1
		 * @return void
		 */
		public static function webinarignition_display_pro_notice() {
			$license_level     = WebinarignitionLicense::webinarignition_get_license_level();
			$account_page_link = admin_url( 'admin.php?page=webinarignition-dashboard-account' );
			$pricing_page_link = admin_url( 'admin.php?page=webinarignition-dashboard-pricing' );
			$upgrade_link      = isset( $license_level->is_registered ) && wp_validate_boolean( $license_level->is_registered )
								? $account_page_link
								: $pricing_page_link;

			switch ( true ) {
				case self::webinarignition_is_former_license_holder():
					include WEBINARIGNITION_PATH . 'templates/notices/former-license-holder-notice.php';
					break;
				case self::webinarignition_is_pro_user_but_license_not_activated():
					include WEBINARIGNITION_PATH . 'templates/notices/license-deactivated-notice.php';
					break;
				case self::webinarignition_is_essential():
					include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
					break;
				case self::webinarignition_is_free_plugin_but_license_activated():
					include WEBINARIGNITION_PATH . 'templates/notices/license-activated-but-free-plugin.php';
					break;
				case self::webinarignition_is_free():
					include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
					break;
				default:
					// nothing.
			}
		}

		/**
		 * Enqueue license assets.
		 *
		 * @since 3.08.1
		 * @return void
		 */
		public function webinarignition_notice_assets() {
			wp_enqueue_style( 'wi-notice-assets', WEBINARIGNITION_URL . 'css/notices-style.css', false, WEBINARIGNITION_VERSION, 'all' );
		}
	}
	new Webinar_Ignition_Notices_Manager();
}//end if
