<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- META INFO -->
	<title>
		<?php
		if (empty($webinar_data->meta_site_title_ty)) {
			webinarignition_display($webinar_data->lp_metashare_title, __('Amazing Webinar', 'webinarignition'));
		} else {
			echo esc_html($webinar_data->meta_site_title_ty);
		}
		?>
	</title>
	<meta name="description" content="
	<?php
	if (empty($webinar_data->meta_desc_ty)) {
		webinarignition_display($webinar_data->lp_metashare_desc, __('Join this amazing webinar, and discover industry trade secrets!', 'webinarignition'));
	} else {
		echo esc_html($webinar_data->meta_desc_ty);
	}
	?>
	">

	<?php
	if (!empty($webinar_data->ty_share_image)) {
	?>
		<meta property="og:image" content="<?php webinarignition_display($webinar_data->ty_share_image, ''); ?>" /><?php } ?>

	<?php wp_head(); ?>

	<?php require 'css/ty_css.php'; ?>

	<?php
	/**
	 * @var obj $webinar_data The webinar_data.
	 */
	do_action('webinarignition_thankyou_cp_page_header', $webinar_data);
	?>
</head>

<body class="thankyou_cp_preview" id="webinarignition">

	<!-- TOP AREA -->
	<div class="topArea">
		<div class="bannerTop container">
			<?php
			if (!empty($webinar_data->lp_banner_image)) {
				printf('<img src="%s" />', esc_url($webinar_data->lp_banner_image));
			}
			?>
		</div>
	</div>

	<!-- Main Area -->
	<div class="mainWrapper">
		<!-- HEADLINE AREAA -->
		<div class="headlineArea">
			<div class="wiContainer container">
				<div class="tyHeadlineIcon">
					<i class="icon-check-sign icon-4x" style="color: #6a9f37;"></i>
				</div>

				<div class="tyHeadlineCopy">
					<div class="optinHeadline1 wiOptinHeadline1">
						<?php
						webinarignition_display(
							$webinar_data->ty_ticket_headline,
							__('Congrats - You Are All Signed Up!', 'webinarignition')
						);
						?>
					</div>
					<div class="optinHeadline2 wiOptinHeadline2">
						<?php
						webinarignition_display(
							$webinar_data->ty_ticket_subheadline,
							__('Below is all the information you need for the webinar...', 'webinarignition')
						)
						?>
					</div>
				</div>

				<br clear="left" />

			</div>
			<!-- /.headlineArea .container-->
		</div>
		<!-- /.headlineArea -->

		<!-- MAIN AREA -->
		<div class="cpWrapperWrapper">
			<div class="wiContainer container">
				<div class="row">
					<div class="cpWrapper">
						<div class="cpLeftSide col-md-6">
							<div class="ticketWrapper">
								<div class="eventDate">


									<div class="dateIcon">
										<div class="dateMonth">MONTH</div>
										<div class="dateDay">DAY</div>
									</div>

									<div class="dateInfo">
										<div class="dateHeadline"><?php esc_html_e('Date Chosen Will Be Here', 'webinarignition'); ?></div>
										<div class="dateSubHeadline"><?php esc_html_e('@ Time Chosen local time ', 'webinarignition'); ?></div>
									</div>

									<br clear="left">
								</div>

								<div class="ticketInfo">

									<div class="ticketInfoNew">

										<div class="ticketSection ticketSectionNew ts">
											<!-- <i class="icon-desktop"></i> -->
											<?php
											if ('custom' === $webinar_data->ty_ticket_webinar_option) {
											?>
												<div class="ticketInfoIcon">
													<i class="icon-desktop icon-3x"></i>
												</div>
												<div class="ticketInfoCopy">
													<b><?php webinarignition_display($webinar_data->ty_ticket_webinar, __('Webinar', 'webinarignition')); ?></b>

													<div class="ticketInfoNewHeadline">
														<?php
														webinarignition_display(
															$webinar_data->ty_webinar_option_custom_title,
															__('Webinar Event Title', 'webinarignition')
														);
														?>
													</div>
												</div>
												<br clear="left" />
											<?php
											} else {
											?>
												<div class="ticketInfoIcon">
													 <img src="<?php echo esc_url($assets . 'images/webinar-icon.png'); ?>" />
												</div>
												<div class="ticketInfoCopy">
													<p><?php esc_html_e('Webinar:', 'webinarignition'); ?></p>

													<div class="ticketInfoNewHeadline">
														<?php
														webinarignition_display(
															$webinar_data->webinar_desc,
															__('Webinar Event Title', 'webinarignition')
														);
														?>
													</div>
												</div>
												<br clear="left" />
											<?php } //end if 
											?>
										</div>

										<div class="ticketSection ticketSectionNew ts">
											<!-- <i class="icon-bullhorn"></i>  -->
											<?php
											if ('custom' === $webinar_data->ty_ticket_host_option) {
											?>
												<div class="ticketInfoIcon2">
													<i class="icon-microphone icon-3x"></i>
												</div>
												<div class="ticketInfoCopy2">
													<b><?php webinarignition_display($webinar_data->ty_ticket_host, 'Host'); ?></b>

													<div class="ticketInfoNewHeadline"><?php webinarignition_display($webinar_data->ty_webinar_option_custom_host, __('Your Name Here', 'webinarignition')); ?></div>
												</div>
												<br clear="left" />
											<?php
											} else {
											?>
												<div class="ticketInfoIcon2">
												<img src="<?php echo esc_url($assets . 'images/host-mic.png'); ?>" />

												</div>
												<div class="ticketInfoCopy2">
													<p><?php esc_html_e('Host', 'webinarignition'); ?>:</p>

													<div class="ticketInfoNewHeadline"><?php webinarignition_display($webinar_data->webinar_host, __('Host name', 'webinarignition')); ?></div>
												</div>
												<br clear="left" />
											<?php } //end if 
											?>
										</div>

										<div class="ticketCDArea ticketSection ticketSectionNew">

											<a href="<?php echo esc_html(webinarignition_fixPerma($data->postID) . 'live'); ?>" class="ticketCDAreaBTN button alert radius disabled addedArrow" id="webinarBTNNN">
												<?php esc_html_e('Example Countdown button', 'webinarignition'); ?>
											</a>

										</div>


									</div>


									<div class="webinarURLArea">

										<div class="webinarURLHeadline">
											<i class="icon-bookmark" style="margin-right: 10px; color: #878787;"></i>
											<?php
											webinarignition_display(
												$webinar_data->ty_webinar_headline,
												__('Here Is Your Webinar Event URL...', 'webinarignition')
											);
											?>
										</div>

										<div class="webinarURLHeadline2">
											<?php
											webinarignition_display(
												$webinar_data->ty_webinar_subheadline,
												__('Save and bookmark this URL so you can get access to the live webinar and webinar replay...', 'webinarignition')
											);
											?>
										</div>
									</div>

								</div>

							</div>


						</div>

						<div class="cpRightSide col-md-6">
							<!-- VIDEO / CTA BLOCK AREA HERE -->
							<div class="ctaArea" <?php
													if ('html' === $webinar_data->ty_cta_type) {
														echo 'style="background-color:#FFF;"';
													}
													?>>



								<?php
								if ('video' === $webinar_data->ty_cta_type) {
									if (isset($webinar_data->ty_cta_video_url) && !empty($webinar_data->ty_cta_video_url)) {

										$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
								?>
										<style>
											#wi_ctaVideo {
												position: relative;
												width: 100%;
											}

											#wi_ctaVideoPlayer {
												height: 100%;
												overflow: hidden;
												border-radius: 10px;
											}

											#wi_ctaVideo>.wi_videoPlayerUnmute {
												position: absolute;
												width: 124px;

												margin-top: -22px;
												right: 10px;
												bottom: 10px;
												margin-left: -62px;
												z-index: 9999;
												display: none;
											}






											#wi_ctaVideo>.wi_videoPlayerMute {
												background: no-repeat;
												border: none;
												width: 10%;
												padding: 0 2% 1% 2%;
												position: absolute;
												bottom: 5px;
												right: 0;
												display: none;
												-webkit-box-shadow: none;
												box-shadow: none;
												-webkit-transition: none;
												-moz-transition: none;
												transition: none;
												z-index: 9999;
												cursor: pointer;
											}
										</style>

										<div id="wi_ctaVideo">
											<button class="wi_arrow_button button wiButton wiButton-block wiButton-lg addedArrow wi_videoPlayerUnmute"><?php echo esc_html(apply_filters('wi_cta_video_unmute_text', esc_html__('Unmute', 'webinarignition'))); ?></button>
											<video id="wi_ctaVideoPlayer" class="video-js vjs-default-skin wi_videoPlayer" disablePictureInPicture oncontextmenu="return false;">
												<source src="<?php echo esc_url($webinar_data->ty_cta_video_url); ?>" type='video/mp4' />
											</video>
											<button class="wi_videoPlayerMute"><img src="<?php echo esc_url($assets . 'images/mute-red.svg'); ?>" /></button>
										</div>

										<div class="preview">
											<p>
												<?php esc_html_e('This is just a preview. The Real Thank You Page Depends On User Submited Dates - Do a Fake Optin For Real The Experience', 'webinarignition'); ?>
											</p>
										</div>


								<?php
									} else {
										webinarignition_display(
											do_shortcode($webinar_data->ty_cta_video_code),
											'<img src="' . $assets . 'images/novideo.png" />'
										);
									} //end if
								} elseif ('html' === $webinar_data->ty_cta_type || empty($webinar_data->ty_cta_type)) {
									webinarignition_display(
										$webinar_data->ty_cta_html,
										'<h3>' . __('Looking Forward To Seeing You', 'webinarignition') . '<br/> ' . __('On The Webinar!', 'webinarignition') . '</h3><p>' . __('An email is being sent to you with all the information. If you want more reminders for the event add the event date to your calendar...', 'webinarignition') . '</p>'
									);
								} elseif ('image' === $webinar_data->ty_cta_type) {
									echo "<img src='";
									webinarignition_display($webinar_data->ty_cta_image, $assets . 'images/noctaimage.png');
									echo "' height='215' width='287' />";
								} //end if
								?>
							</div>

							<div class="remindersBlock">

								<?php $wi_calendarOption = !empty($webinar_data->ty_add_to_calendar_option) ? $webinar_data->ty_add_to_calendar_option : 'enable'; ?>
								<?php if ('enable' === $wi_calendarOption) : ?>
									<div class="ticketSection ticketCalendarArea">
										<div class="optinHeadline12 wiOptinHeadline2">
											<?php
											webinarignition_display(
												$webinar_data->ty_calendar_headline,
												__('Add To Your Calendar', 'webinarignition')
											);
											?>
										</div>

										<!-- AUTO CODE BLOCK AREA -->
										<?php if ('AUTO' === $webinar_data->webinar_date) { ?>
											<!-- AUTO DATE -->
											<div class="wi-btns-wrap">
												<a href="?googlecalendarA" class="small button" target="_blank">
													<i class="icon-google-plus"></i>
													<?php
													webinarignition_display(
														$webinar_data->ty_calendar_google,
														__('Google Calendar', 'webinarignition')
													);
													?>
												</a>
												<a href="?icsA" class="small button" target="_blank">
													<i class="icon-calendar"></i> <?php webinarignition_display($webinar_data->ty_calendar_ical, __('iCal / Outlook', 'webinarignition')); ?>
												</a>
											</div>
										<?php } else { ?>
											<a href="?googlecalendar" class="small button" target="_blank">
												<i class="icon-google-plus"></i>
												<?php
												webinarignition_display(
													$webinar_data->ty_calendar_google,
													__('Google Calendar', 'webinarignition')
												);
												?>
											</a>
											<a href="?ics" class="small button" target="_blank">
												<i class="icon-calendar"></i> <?php webinarignition_display($webinar_data->ty_calendar_ical, __('iCal / Outlook', 'webinarignition')); ?>
											</a>
										<?php } //end if 
										?>
										<!-- END OF AUTO CODE BLOCK -->

									</div>
								<?php endif; ?>

							</div>


						</div>

						<br clear="both" />


						<div class="cpUnderHeadline" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? webinarignition_display($webinar_data->ty_share_toggle, 'none') : 'none'; ?>;">
							<?php
							webinarignition_display(
								isset($webinar_data->ty_step2_headline) ? $webinar_data->ty_step2_headline : '',
								__('Step #2: Share & Unlock Reward...', 'webinarignition')
							);
							?>
						</div>

						<div class="cpUnderCopy" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? webinarignition_display($webinar_data->ty_share_toggle, 'none') : 'none'; ?>;">

							<div class="cpCopyArea">
								<!-- SHARE BLOCK -->
								<div class="shareBlock wi-block--sharing" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? webinarignition_display($webinar_data->ty_share_toggle, 'none') : 'none'; ?>;">

									<?php
									if (isset($webinar_data->ty_fb_share) && 'off' !== $webinar_data->ty_fb_share) {
									?>
										<div class="socialShare">
											<!-- <div class="fb-like" data-href="<?php // echo esc_url(get_permalink($data->postID)); ?>" data-send="false" data-layout="box_count" data-width="48" data-show-faces="false" data-font="arial"></div> -->
										</div>
										<div class="socialDivider"></div>
									<?php } ?>

									<br clear="left" />

								</div>

								<!-- SHARE REWARD - UNLOCK -->
								<div class="shareReward" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? webinarignition_display($webinar_data->ty_share_toggle, 'none') : 'none'; ?>;">
									<div class="sharePRE">
										<?php
										webinarignition_display(
											isset($webinar_data->ty_share_intro) ? $webinar_data->ty_share_intro : '',
											'<h4>' . __('Share This Webinar & Unlock Free Report', 'webinarignition') . '</h4>
							<p>' . __('Simply share the webinar on any of the social networks above, and you will get instant access to this reporcss..', 'webinarignition') . '</p>'
										);
										?>
									</div>
									<div class="shareREVEAL" style="display: none;">
										<?php
										webinarignition_display(
											isset($webinar_data->ty_share_reveal) ? $webinar_data->ty_share_reveal : '',
											'<h4>' . __('Congrats! Reward Unlocked', 'webinarignition') . '</h4>
							<p>' . __('Here is the text that would be shown when they unlock a reward...', 'webinarignition') . '</p>'
										);
										?>
									</div>
								</div>
							</div>

							<div class="cpCopyTY">
								<!-- ADD TO CALENDARS -->
								<div class="addCalendar" style="display:none;">

									<div class="addCalendarHeadline">
										<i class="icon-calendar icon-4x ticketIcon"></i>

										<?php if (!empty($webinar_data->ty_calendar_headline)) : ?>
											<span class="optinHeadline1 wiOptinHeadline1"><?php webinarignition_display($webinar_data->ty_calendar_headline, __('Add To Your Calendar', 'webinarignition')); ?></span>
										<?php endif; ?>

										<?php if (!empty($webinar_data->ty_calendar_subheadline)) : ?>
											<span class="optinHeadline2 wiOptinHeadline2"><?php webinarignition_display($webinar_data->ty_calendar_subheadline, __('Remind Yourself Of The Event', 'webinarignition')); ?></span>
										<?php endif; ?>

										<br clear="left" />
									</div>

								</div>


							</div>

							<br clear="all" />

						</div>

					</div>

				</div>


			</div>

			<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>


</body>

</html>