<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
?>

<?php require WEBINARIGNITION_PATH . 'inc/lp/partials/main-cta.php'; ?>
<?php
// Include this file only for classic template
$statusCheck = WebinarignitionLicense::webinarignition_get_license_level();
$webinar_template = ! empty( $webinar_data->webinar_template ) ? $webinar_data->webinar_template : 'classic';
if ( ! in_array( $statusCheck->switch, array( 'pro', 'basic' ), true ) && 'classic' === $webinar_template ) {
	include WEBINARIGNITION_PATH . 'inc/lp/partials/additional-cta.php';
}

