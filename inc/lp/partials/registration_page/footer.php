<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registration page footer template
 *
 * @var $template_number
 * @var $webinarId
 * @var $webinar_data
 * @var $assets
 * @var $user_info
 */
?>

<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>
<?php wp_footer(); ?>
<?php echo esc_html( $webinar_data->footer_code ); ?>
</body>
</html>
