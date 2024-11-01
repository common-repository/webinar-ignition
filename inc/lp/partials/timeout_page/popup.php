<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$watch_time_limit_string = __( '45 Minutes', 'webinarignition' );
$statusCheck = WebinarignitionLicense::webinarignition_get_license_level();
if ( 'ultimate_powerup_tier1a' === $statusCheck->name ) {
	$watch_time_limit_string = __( '45 minutes', 'webinarignition' );
}
?>
<style type="text/css">
.wi-timeout-overlay {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: rgba(0, 0, 0, 0.7);
	transition: opacity 500ms;
	visibility: visible;
	opacity: 1;
}
.wi-timeout-overlay:target {
	visibility: visible;
	opacity: 1;
}

.wi-timeout-popup {
	margin: 70px auto;
	padding: 20px;
	background: #fff;
	border-radius: 5px;
	width: 30%;
	position: relative;
	transition: all 5s ease-in-out;
}

.wi-timeout-popup h2 {
	margin-top: 0;
	color: #333;
	font-family: Tahoma, Arial, sans-serif;
}
.wi-timeout-popup .close {
	position: absolute;
	top: 20px;
	right: 30px;
	transition: all 200ms;
	font-size: 30px;
	font-weight: bold;
	text-decoration: none;
	color: #333;
}
.wi-timeout-popup .close:hover {
	color: #06d85f;
}
.wi-timeout-popup .content {
	max-height: 30%;
	overflow: auto;
}

@media screen and (max-width: 700px) {
	.wi-timeout-box {
	width: 70%;
	}
	.wi-timeout-popup {
	width: 70%;
	}
}
</style>
<div id="wi-timeout-popup1" class="wi-timeout-overlay">
	<div class="wi-timeout-popup">
		<h2><?php esc_html_e( 'Webinar is closed', 'webinarignition' ); ?></h2>
		<a class="close" href="#">&times;</a>
		<div class="content">
			<?php
			printf(
				/* translators: %s: Watch time limit */
				esc_html__( 'Webinar watch time is limited to %s only. Contact to site administrator for more details.', 'webinarignition' ),
				esc_html( $watch_time_limit_string )
			);
			?>
		</div>
	</div>
</div>