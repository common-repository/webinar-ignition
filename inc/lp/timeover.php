<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html>
<head>

	<!-- META INFO -->
	<title><?php webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinarignition' ) ); ?></title>
	<meta name="description" content="
	<?php
	webinarignition_display(
		$webinar_data->webinar_desc,
		__( 'Join this amazing webinar May the 4th, and discover industry trade secrets!', 'webinarignition' )
	);
	?>
	">
	<!-- SOCIAL INFO -->
	<meta property="og:title" content="<?php webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinarignition' ) ); ?>"/>
	<meta property="og:image" content="<?php webinarignition_display( $webinar_data->ty_share_image, '' ); ?>"/>

	<?php require 'css/webinar_css.php'; ?>

	<!-- CUSTOM JS -->
	<script>
		<?php webinarignition_display( $webinar_data->custom_replay_js, '' ); ?>
	</script>
	<!-- CUSTOM CSS -->
	<style>
		<?php webinarignition_display( $webinar_data->custom_replay_css, '' ); ?>
	</style>

	<?php wp_head(); ?>

</head>
<body class="webinar_closed">

<div class="topArea">
	<div class="bannerTop">
		<?php
		if ( ! empty( $webinar_data->webinar_banner_image ) ) {
			printf( '<image src="%s" alt="" />', esc_url( $webinar_data->webinar_banner_image ) );
		}

		if ( isset( $_GET['preview'] ) && current_user_can( 'edit_posts' ) ) { //phpcs:ignore
			update_option( 'wi_lead_watch_time_[lead_id]', 0 );
		}
		?>
	</div>
</div>

<div class="mainWrapper">

	<!-- WEBINAR WRAPPER -->
	<div class="webinarWrapper container">
		<!-- CLOSED WEBINAR -->
		<div id="closed" class="webinarExtraBlock2">
			<?php
			$watch_time_limit_string = __( '45 minutes', 'webinarignition' );
			$statusCheck             = WebinarignitionLicense::webinarignition_get_license_level();
			if ( 'ultimate_powerup_tier1a' === $statusCheck->name ) {
				$watch_time_limit_string = __( '45 Minutes', 'webinarignition' );
			}

			webinarignition_display( $webinar_data->replay_closed, '<h1>' . sprintf( 
				/* translators: %s: Watch time limit */
				esc_html__( 'Webinar watch time is limited to %s only.', 'webinarignition' ), $watch_time_limit_string ) . '</h1>' );
			?>
			<br>
			<?php webinarignition_display( $webinar_data->replay_closed, '<h3>' . esc_html__( 'Contact site administrator for more details.', 'webinarignition' ) . '</h3>' ); ?>
		</div>

	</div>

</div>

<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>


<div id="fb-root"></div>
<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php'; ?>
<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php'; ?>


<!--Extra code-->
<?php echo esc_html( $webinar_data->footer_code ); ?>

<?php wp_footer(); ?>
</body>
</html>
