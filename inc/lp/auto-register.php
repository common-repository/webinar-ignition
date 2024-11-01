<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo esc_html( $webinar_data->webinar_desc ); ?></title>
	<?php wp_head(); ?>
</head>
<?php
// make the thank you page url
if ( 'show' === $webinar_data->custom_ty_url_state && ! empty( $webinar_data->custom_ty_url ) ) {
	$thank_you_page_url = esc_url_raw($webinar_data->custom_ty_url);
} else {
	$request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw($_SERVER['REQUEST_URI']) : '';
	$thank_you_page_url = ( isset($webinar_data->webinar_switch) && 'live' === $webinar_data->webinar_switch ) 
        ? wp_parse_url($request_uri, PHP_URL_PATH) . '?live' 
        : wp_parse_url($request_uri, PHP_URL_PATH) . '?confirmed';
	if ( 'paid' === $webinar_data->paid_status ) {
		$paid_code = isset($webinar_data->paid_code) ? sanitize_text_field($webinar_data->paid_code) : '';
		$thank_you_page_url .= '&' . rawurlencode( $paid_code );
	}
}

$readonly            = isset( $_GET['readonly'] ) ? '&readonly=' . $_GET['readonly'] : '';//phpcs:ignore
$login               = isset( $_GET['login'] ) ? '&login=' . $_GET['login'] : '';//phpcs:ignore
$thank_you_page_url .= '&first=' . $name . '&email=' . $email . $readonly . $login;//phpcs:ignore
$name 				 = isset( $_GET['n'] ) ? htmlspecialchars( $_GET['n'] ) : null; // phpcs:ignore

$email               = isset( $_GET['e'] ) ? htmlspecialchars( $_GET['e'] ) : null;//phpcs:ignore
$plain_email         = $email;
$email               = is_email( $email ) ? $email : base64_decode( $email );//phpcs:ignore
$ip                  = esc_url(sanitize_text_field( $_SERVER['REMOTE_ADDR'] ));
?>
<body
	id="auto-register"
	style="text-align: center;"
	data-webinar-id="<?php echo absint( $webinar_id ); ?>"
	data-name="<?php echo esc_attr( $name ); ?>"
	data-email="<?php echo esc_attr( $email ); ?>"
	data-ip="<?php echo esc_attr( $ip ); ?>"
	data-thank-you-page-url="<?php echo esc_url( $thank_you_page_url ); ?>"
	data-webinar-type="<?php echo 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live'; ?>"
	data-email-verification-setting="<?php echo esc_html( $webinar_data->email_verification ); ?>"
	data-email-verification-enabled="<?php echo wp_json_encode( filter_var( get_option( 'webinarignition_email_verification', 0 ), FILTER_VALIDATE_BOOLEAN ) ); ?>"
	data-plain-email="<?php echo esc_attr( $plain_email ); ?>"
>

<div class="informationBox">
	<h2 style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #b3b3b3;"><?php echo esc_html( $webinar_data->webinar_desc ); ?></h2>
	<h4 style="font-weight:normal;"><?php echo esc_html( $webinar_data->webinar_host ); ?></h4>
</div>

<div class="loaderBox">
	<i class="fa fa-spinner fa-spin fa-4x"></i>
</div>


<!-- AR OPTIN INTEGRATION -->
<div class="arintegration" style="display:none;">
	<?php require WEBINARIGNITION_PATH . 'inc/lp/ar_form.php'; ?>
</div>
</body>
</html>
