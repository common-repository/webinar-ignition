<?php

/**
 * Plugin Name: WebinarIgnition
 * Description: WebinarIgnition is a premium webinar solution that allows you to create, run and manage webinars. Build and fully customize, professional webinar registration, confirmation, live webinar and replay pages with ease.
 * Version: 4.00.0-rc.4
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.2.5
 * Author: Saleswonder Team
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: webinarignition
 * Domain Path: /languages
 * Plugin URI: https://webinarignition.com
 * Prefix: wi
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
if ( function_exists( 'webinarignition_fs' ) ) {
    webinarignition_fs()->set_basename( false, __FILE__ );
} else {
    if ( !defined( 'WEBINARIGNITION_PREVIOUS_VERSION' ) ) {
        define( 'WEBINARIGNITION_PREVIOUS_VERSION', '2.15.2' );
    }
    if ( !defined( 'WEBINARIGNITION_VERSION' ) ) {
        define( 'WEBINARIGNITION_VERSION', '4.00.0-rc.4' );
    }
    if ( !defined( 'WEBINARIGNITION_BRANCH' ) ) {
        define( 'WEBINARIGNITION_BRANCH', '4.00.0-rc.1 2024.09.24 5e30227 https://bitbucket.org/WP-Leads-Plugins/webinarignition/commits/' );
    }
    if ( !defined( 'WEBINARIGNITION_URL' ) ) {
        define( 'WEBINARIGNITION_URL', plugins_url( '/', __FILE__ ) );
    }
    if ( !defined( 'WEBINARIGNITION_PATH' ) ) {
        define( 'WEBINARIGNITION_PATH', plugin_dir_path( __FILE__ ) );
    }
    if ( !defined( 'WEBINARIGNITION_PLUGIN_FILE' ) ) {
        define( 'WEBINARIGNITION_PLUGIN_FILE', __FILE__ );
    }
    if ( !defined( 'WEBINARIGNITION_DB_VERSION' ) ) {
        define( 'WEBINARIGNITION_DB_VERSION', 12 );
    }
    if ( !defined( 'WEBINARIGNITION_PLUGIN_NAME' ) ) {
        define( 'WEBINARIGNITION_PLUGIN_NAME', "webinarignition" );
    }
    if ( !defined( 'WEBINARIGNITION_PLUGIN_BASENAME' ) ) {
        define( 'WEBINARIGNITION_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    }
    // Plugin dev mode.
    define( 'WEBINARIGNITION_DEV_MODE', false );
    if ( !function_exists( 'webinarignition_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         * DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
         *
         * @since 3.08.1
         */
        function webinarignition_fs() {
            global $webinarignition_fs;
            if ( !isset( $webinarignition_fs ) ) {
                if ( !defined( 'WP_FS__PRODUCT_7606_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_7606_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once __DIR__ . '/freemius/start.php';
                $webinarignition_fs = fs_dynamic_init( array(
                    'id'               => '7606',
                    'slug'             => 'webinar-ignition',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_78db77544c037d3e892f673cf65d4',
                    'is_premium'       => false,
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'is_org_compliant' => true,
                    'trial'            => array(
                        'days'               => 14,
                        'is_require_payment' => true,
                    ),
                    'has_affiliation'  => 'selected',
                    'menu'             => array(
                        'slug'       => 'webinarignition-dashboard',
                        'support'    => false,
                        'contact'    => false,
                        'first-path' => 'admin.php?page=webinarignition-dashboard',
                    ),
                    'is_live'          => true,
                ) );
            }
            //end if
            return $webinarignition_fs;
        }

        webinarignition_fs();
        do_action( 'webinarignition_fs_loaded' );
    }
    //end if
    // Include the main WebinarIgnition class.
    if ( !class_exists( 'WebinarIgnition' ) ) {
        include_once __DIR__ . '/inc/class-webinarignition.php';
    }
    if ( !class_exists( 'WI_Install' ) ) {
        include_once __DIR__ . '/inc/class.WebinarignitionManager.php';
        include_once __DIR__ . '/inc/class-wi-install.php';
        register_activation_hook( WEBINARIGNITION_PLUGIN_FILE, array('WI_Install', 'install') );
    }
    register_activation_hook( __FILE__, 'webinarignition_activate' );
    register_deactivation_hook( __FILE__, 'webinarignition_deactivate' );
    if ( !function_exists( 'webinarignition_activate' ) ) {
        function webinarignition_activate() {
            webinarignition_deactivate_previous();
            // Deactivate any previously active instance of the plugin, before activating new one
            include_once WEBINARIGNITION_PATH . 'inc/wi-activation.php';
            webinarignition_installer();
            // TODO: Separate the code for setting up smtp after plugin installation. line:130-146
            if ( isset( $_REQUEST['action'] ) && 'activate-selected' === $_REQUEST['action'] && (isset( $_POST['checked'] ) && count( $_POST['checked'] ) > 1) ) {
                // phpcs:ignore
                return;
            }
            if ( empty( intval( get_option( 'wi_first_install' ) ) ) ) {
                add_option( 'wi_redirect_after_installation', wp_get_current_user()->ID );
                update_option( 'webinarignition_smtp_name', get_bloginfo( 'name' ) );
                $protocols = array(
                    'http://',
                    'https://',
                    'http://www.',
                    'https://www.',
                    'www.'
                );
                $site_domain = str_replace( $protocols, '', site_url() );
                update_option( 'webinarignition_smtp_email', 'webinar@' . $site_domain );
            }
            update_option( 'webinarignition_activated', 1 );
            update_option( 'webinarignition_branding_copy', 'Webinar powered by webinarignition' );
            do_action( 'webinarignition_activated' );
        }

    }
    //end if
    if ( !function_exists( 'webinarignition_deactivate' ) ) {
        function webinarignition_deactivate() {
            $timestamp = wp_next_scheduled( 'webinarignition_cron_hook' );
            wp_unschedule_event( $timestamp, 'webinarignition_cron_hook' );
        }

    }
    if ( !function_exists( 'webinarignition_deactivate_previous' ) ) {
        /**
         * Deactivate any previously active instance of the plugin
         */
        function webinarignition_deactivate_previous() {
            if ( current_user_can( 'activate_plugins' ) && class_exists( 'WebinarIgnition' ) && defined( 'WEBINARIGNITION_PLUGIN_FILE' ) ) {
                if ( !function_exists( 'is_plugin_active_for_network' ) ) {
                    include_once ABSPATH . '/wp-admin/includes/plugin.php';
                }
                deactivate_plugins( plugin_basename( WEBINARIGNITION_PLUGIN_FILE ), true );
            }
        }

    }
    if ( !function_exists( 'WebinarIgnition' ) ) {
        /**
         * Returns the main instance of WebinarIgnition.
         *
         * @return WebinarIgnition|null
         */
        function webinar_ignition() {
            return WebinarIgnition::instance();
        }

        add_action( 'plugins_loaded', 'webinar_ignition' );
    }
}
//end if
// 3790ae2