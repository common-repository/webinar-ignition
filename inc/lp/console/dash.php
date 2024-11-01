<?php

/**
 * @var $is_host
 * @var $is_support
 * @var $webinar_id
 * @var $webinar_data
 * @var $post
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- DASHBOARD AREA -->
<div id="dashboardTab" class="consoleTabs">

	<div class="statsDashbord">

		<div class="statsTitle statsTitle-Dassh">

			<div class="statsTitleIcon">
				<i class="icon-cogs icon-2x"></i>
			</div>

			<div class="statsTitleCopy">
				<?php
				if ( 'AUTO' === $webinar_data->webinar_date ) {
					echo '<h2>Auto Webinar Console Dashboard</h2>';
				} else {
					?>
					<h2><?php esc_html_e( 'Live Console Dashboard', 'webinarignition' ); ?></h2>
				<?php } ?>
				<p><?php esc_html_e( 'Overview of your webinar campaign...', 'webinarignition' ); ?></p>
			</div>

			<div class="statsTitleEvent">
				<span class="infoLabel"><?php esc_html_e( 'Webinar Title', 'webinarignition' ); ?>:</span>
				<span class="infoLabelInner"><?php echo esc_attr( $data->appname ); ?></span>
			</div>

			<br clear="all"/>

		</div>

	</div>

	<div class="innerOuterContainer">

		<div class="innerContainer">
			
		<?php if ( ( current_user_can( 'manage_options' ) ) && ( 'AUTO' !== $webinar_data->webinar_date ) ) { ?>   
			
			<div class="dash-wrapper-left">
				<ul class="webinarStatus">
					<li><a href="#" class="webinarStatus <?php echo ( 'countdown' === $webinar_data->webinar_switch || empty( $webinar_data->webinar_switch ) ) ? 'webinarStatusSelected' : ''; ?>" data="countdown"><i class="icon-time"></i>    <?php esc_html_e( 'Countdown', 'webinarignition' ); ?></a></li>
					<li><a href="#" class="webinarStatus <?php echo ( 'live' === $webinar_data->webinar_switch ) ? 'webinarStatusSelected' : ''; ?>" data="live"><i class="icon-microphone"></i>   <?php esc_html_e( 'Live', 'webinarignition' ); ?></a></li>
					<li><a href="#" class="webinarStatus <?php echo ( 'replay' === $webinar_data->webinar_switch ) ? 'webinarStatusSelected' : ''; ?>" data="replay"><i class="icon-refresh"></i>   <?php esc_html_e( 'Replay', 'webinarignition' ); ?></a></li>
					<li><a href="#" class="webinarStatus <?php echo ( 'closed' === $webinar_data->webinar_switch ) ? 'webinarStatusSelected' : ''; ?>" data="closed"><i class="icon-lock"></i>      <?php esc_html_e( 'Closed', 'webinarignition' ); ?></a></li>   
				</ul>
			</div>         
			
		<?php } ?>    

			<div class="dash-wrapper-left">

				<div class="dash-stat-block dash-block-1" 
				<?php
				if ( 'AUTO' === $webinar_data->webinar_date ) {
					echo 'style="display:none;"';  }
				?>
				>
					<div class="dash-stat-number" id="usersOnlineCount">0</div>
					<div class="dash-stat-label"><?php esc_html_e( 'Live Viewers On Webinar', 'webinarignition' ); ?></div>
				</div>

				<div class="dash-stat-block dash-block-2">
					<div class="dash-stat-number"><?php echo esc_html( $totalLeads ); ?></div>
					<div class="dash-stat-label"><?php esc_html_e( 'Total Registrants', 'webinarignition' ); ?></div>
				</div>

				<div class="dash-stat-block dash-block-5">
					<div class="dash-stat-number"><?php echo esc_html( $totalOrders ); ?></div>
					<div class="dash-stat-label"><?php esc_html_e( 'Total Orders', 'webinarignition' ); ?></div>
				</div>

				<div class="dash-stat-block dash-block-3">
					<div class="dash-stat-number" id="dashTotalQ"><?php echo esc_html( $totalQuestions ); ?></div>
					<div class="dash-stat-label"><?php esc_html_e( 'Total Questions', 'webinarignition' ); ?></div>
				</div>

				<div class="dash-stat-block dash-block-4" 
				<?php
				if ( 'AUTO' === $webinar_data->webinar_date ) {
					echo 'style="display:none;"';
				}
				?>
				>
					<div class="dash-stat-number" id="dashTotalActiveQ"><?php echo esc_html( $totalQuestionsActive ); ?></div>
					<div class="dash-stat-label"><?php esc_html_e( 'Total Active Questions', 'webinarignition' ); ?></div>
				</div>

				<div class="dash-stat-block dash-block-6" 
				<?php
				if ( 'AUTO' === $webinar_data->webinar_date || $is_host ) {
					echo 'style="display:none;"'; }
				?>
				>
					<?php
					$go_to_dash_url      = 'https://www.youtube.com/live_dashboard';
					$go_to_dash_btn_text = __( 'Go to Youtube Live', 'webinarignition' );

					if ( ! empty( $webinar_data->live_dash_url ) ) {
						$go_to_dash_url = $webinar_data->live_dash_url;
					}

					if ( ! empty( $webinar_data->live_dash_btn_text ) ) {
						$go_to_dash_btn_text = $webinar_data->live_dash_btn_text;
					}
					?>
					<div class="dash-stat-label" style="padding-bottom: 20px">
							<a
								id="youtube-live-button"
								href="<?php echo esc_url( $go_to_dash_url ); ?>" target="_blank">
								<i class="icon-youtube-play"></i>
								<?php echo esc_html( $go_to_dash_btn_text ); ?>
							</a>

					</div>
				</div>

				<br clear="left"/>

			</div>

		</div>
	</div>

</div>

<?php if ( 'AUTO' !== $webinar_data->webinar_date ) { ?>	
<?php }//end if ?>
