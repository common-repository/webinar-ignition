<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// func :: webinarIgnition_dbug
// --------------------------------------------------------------------------------------
function webinarIgnition_dbug() {
	$cvs = phpversion();                                  // current version string
	$rvs = '5.4.9';                                       // required version string

	if ( version_compare( $cvs, $rvs, '<' ) ) {
		echo '<br>
               <div class="error" style="display:inline-block; padding:12px; margin-left:2px; margin-top:20px"><b>' . esc_html__( 'WARNING !!', 'webinarignition' ) . '</b><br>' . esc_html__( 'This plugin requires at least PHP version', 'webinarignition' ) . ' ' . esc_html( $rvs ) . ' ' . esc_html__( "but this server's installed version is older:", 'webinarignition' ) . ' ' . esc_html( $cvs ) . '<br><br>'
			. esc_html__( 'It is <strong>strongly</strong> recommended that you contact your hosting provider to upgrade your PHP installation to the required version or better.<br> If you ignore this, your software will throw errors or cause unwanted problems.', 'webinarignition' )
			. '</div>';
	}
}

function wi_date_difference( $date_1, $date_2, $difference_in = 'days' ) {

	switch ( $difference_in ) {

		case 'seconds':
			$difference_format = '%R%s';
			break;
		case 'minutes':
			$difference_format = '%R%i';
			break;
		case 'hours':
			$difference_format = '%R%h';
			break;
		case 'days':
			$difference_format = '%R%d';
			break;
		default:
			$difference_format = '%R%a';
			break;
	}

	$datetime1 = date_create( $date_1 );
	$datetime2 = date_create( $date_2 );

	$interval = date_diff( $datetime1, $datetime2 );

	$seconds = intval( $interval->format( '%R%s' ) );
	$minutes = intval( $interval->format( '%R%i' ) );
	$hours   = intval( $interval->format( '%R%h' ) );
	$days    = intval( $interval->days );

	if ( 'days' == $difference_in ) {
		return $days;
	} elseif ( 'hours' == $difference_in ) {
		return ( $days * 24 ) + $hours;
	} elseif ( 'minutes' == $difference_in ) {
		return ( $days * 24 * 60 ) + ( $hours * 60 ) + $minutes;
	} elseif ( 'seconds' == $difference_in ) {
		return ( $days * 24 * 60 ) + ( $hours * 60 ) + ( $minutes * 60 ) + $seconds;
	}

	return $interval->format( $difference_format );
}
// --------------------------------------------------------------------------------------


function webinarignition_dashboard() {
	$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$create = filter_input( INPUT_GET, 'create', FILTER_UNSAFE_RAW );
	$webinars = filter_input( INPUT_GET, 'webinars', FILTER_UNSAFE_RAW );

	// fix :: notice on outdated PHP version
	// --------------------------------------------------------------------------------------
	webinarIgnition_dbug();
	// --------------------------------------------------------------------------------------

	// Universal Variables

	$sitePath = WEBINARIGNITION_URL;

	// UI FRAMEWORK
	include 'ui-core.php';
	include 'ui-com2.php';
	// The whole code of js-core.php is transferred to webinarignition-admin-dashboard.php File
	//include 'js-core.php';
	update_option( 'webinarignition_activated', 0 );

	// Acitvation Look Up ::
	global $wpdb;
	$statusCheck = WebinarignitionLicense::webinarignition_get_license_level();

	$license_title = ! empty( $statusCheck->title ) ? $statusCheck->title : __( 'Free', 'webinarignition' );
	$license_name = ! empty( $statusCheck->name ) ? $statusCheck->name : __( 'Free', 'webinarignition' );

	$promo_expired = ( time() > 1672549199 );
	$is_basic_pro = ( in_array( $statusCheck->switch, array( 'pro', 'basic' ) ) && ! in_array( $statusCheck->name, array( 'ultimate_powerup_tier2a', 'ultimate_powerup_tier3a' ) ) );
	$webinarignition_dashboard_link = admin_url( '?page=webinarignition-dashboard' );
	$optin_button_text = __( 'Opt-in for More Free Registrations, License Options', 'webinarignition' );

	$limit_settings_decoded = WebinarignitionLicense::webinarignition_get_limitation_settings();
	$limit_users            = isset( $limit_settings_decoded['limit_users'] ) ? $limit_settings_decoded['limit_users'] : '5';
	$user_count             = WebinarignitionLicense::webinarignition_get_limit_counter();
	$user_left              = absint( $limit_users ) - absint( $user_count );
	$user_left              = absint( $user_left );

	$progress = ( 0 !== absint( $limit_users ) ) ? ( absint( $user_count ) / absint( $limit_users ) ) * 100 : 0;

	$bg_color = 0;

	$total_number_of_days = 30;

	$act_now_text = __( 'Act Now!', 'webinarignition' );
	if ( $progress >= 93 ) {
		$act_now_text = __( 'Act Now to open!', 'webinarignition' );
		$bg_color = 'rgb(255 71 45)';
	} elseif ( $progress >= 80 ) {
		$bg_color = 'rgb(194 163 43)';
	}

	$limit_count_timeout = (int) get_option( 'webinarignition_limit_timeout' );

	if ( empty( $limit_count_timeout ) ) {
		$limit_count_timeout = strtotime( '+30 days' );
	}

	$starting_time = strtotime( '-29 days', $limit_count_timeout );
	$ending_time   = $limit_count_timeout;
	$current_time  = time();

	$starting_date = date( 'y-m-d', $starting_time );
	$current_date  = date( 'y-m-d', $current_time );

	$current_number_of_days = absint( wi_date_difference( $starting_date, $current_date, 'days' ) );

	$total_count_ratio   = ( $limit_users > 0 && $total_number_of_days > 0 ) ? $limit_users / $total_number_of_days : 0;
	$current_count_ratio = ( $user_count > 0 && $current_number_of_days > 0 ) ? $user_count / $current_number_of_days : 0;

	$reset_date = date( get_option( 'date_format' ), $limit_count_timeout );

	$wi_db_url = add_query_arg( 'page', 'webinarignition-dashboard', admin_url( 'admin.php' ) );
	if ( $statusCheck->name == 'ultimate_powerup_tier1a' ) {
		$wi_db_url = $statusCheck->trial_url;
	}

	$act_now_link = sprintf( '<a class="wi-dashboard-link" href="%s" target="_blank"><strong>%s</strong></a>', $wi_db_url, $act_now_text );

	/* translators: %s: Date when the reset will occur */
	$reset_date_message = sprintf( esc_html__( 'Reset on %s', 'webinarignition' ), $reset_date );
	if ( isset( $statusCheck->is_trial ) && $statusCheck->is_trial ) {
		$reset_date_message = '';
	}

	?>
	<div id="mWrapper">
		<div id="mHeader" style="background-color: #353337;">
			<div id="mLogo">
				<div class="webinar_logo_license_cont">
					<div class="mLogoIMG">
						<?php if ( ! $is_basic_pro && $statusCheck->switch !== 'free' ) : ?>
							<a href="<?php echo esc_url($webinarignition_dashboard_link); ?>"><img class="welogo" style="padding-top: 17px;" src="<?php echo esc_url(WEBINARIGNITION_URL); ?>images/webinarignition.png" width="284" alt="" border="0"></a>
						<?php else : ?>
						<img class="welogo" style="padding-top: 10px;" src="<?php echo esc_url(WEBINARIGNITION_URL); ?>images/webinarignition-white-grey.png" width="284" alt="" border="0">
									<?php endif; ?>
					</div>

					<h3 id="licenseTitle"><?php echo esc_html( $license_title ); ?></h3>
				</div>
				<?php
				if (

					empty( $statusCheck ) ||
					$statusCheck->switch == '' ||
					! empty( $statusCheck->is_fs ) ||
					empty( $statusCheck->keyused )
				) {

				} else {

					$is_freemius_not_registered = ( ! empty( $statusCheck->reconnect_url ) && ! $statusCheck->is_registered );

					if ( $is_freemius_not_registered ) {
						?>
				<style>
					.WIheaderRight {
						width: 80%;
						float: right;
						position: relative;
						display: table;
						padding: 0 14px;
						line-height: 47px;
					}
					.mSupport {
						margin-top: 0px;
						margin-right: 0px;
					}
					.mSupport:last-child {
						margin-right: 0px;
					}
				</style>
				<?php } ?>
					<div class="WIheaderRight">
						<button
								data-toggle="collapse"
								data-target="#unlockFormsContainer"
								aria-expanded="false"
								aria-controls="unlockFormsContainer"
								class="btn btn-primary mSupport"
								title="<?php esc_html_e( 'License bought before 01/2021', 'webinarignition' ); ?>"
						>
							<i class="icon-key" style="margin-right: 5px;"></i>
							<?php esc_html_e( 'Manage license', 'webinarignition' ); ?>
						</button>
						<?php
				}//end if


?>
				<div class="wi-head-btns-wrap">

				<?php

				if ( ! empty( $statusCheck->account_url ) ) {
					?>
					<a href="<?php echo esc_url( $statusCheck->account_url ); ?>" class="btn btn-primary mSupport" title="<?php esc_html_e( 'License bought after 01/2021', 'webinarignition' ); ?>">
						<i class="icon-user" style="margin-right: 5px;"></i>
						Freemius <?php esc_html_e( 'Account', 'webinarignition' ); ?>
					</a>
					<?php
				}
				?>

				<a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=webinarignition_support' ); ?>" class="btn btn-primary mSupport"><i class="icon-question-sign" style="margin-right: 5px;"></i> <?php esc_html_e( 'Solution Center', 'webinarignition' ); ?></a>
				</div>
			</div>
		</div>

		<div id="container">
			<?php 
			// Edit App
			if ( isset( $id ) ) {
				include 'editapp.php';
			} elseif ( isset( $create ) ) {
				include 'create.php';
			} elseif ( isset( $webinars ) ) {
				include 'webinars-list.php';
			} else {
				include 'dash.php';
			}
			?>

		</div>
	</div>
	<?php
	// END
}
