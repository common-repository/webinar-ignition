<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Webinar_Optin' ) ) {
	class Webinar_Optin {
		public static function webinarignition_is_free( $license ) {
			return ( 'enterprise_powerup' !== $license->switch && ! in_array( $license->name, array( 'ultimate_powerup_tier2a', 'ultimate_powerup_tier3a' ), true ) );
		}

		public static function webinarignition_is_essential( $license ) {
			return empty( $license->switch ) || 'ultimate_powerup_tier1a' === $license->name;
		}

		public static function webinarignition_is_pro( $license ) {
			return 'pro' === $license->switch && in_array( $license->name, array( 'ultimate_powerup_tier2a', 'ultimate_powerup_tier3a' ), true );
		}
	}
}
