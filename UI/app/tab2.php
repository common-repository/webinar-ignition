<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * @var $webinar_data
 */
$is_too_late_lockout_enabled = WebinarignitionPowerups::webinarignition_is_too_late_lockout_enabled( $webinar_data );
$webinar_template = ( !empty( $webinar_data->webinar_template ) ? esc_html( $webinar_data->webinar_template ) : 'classic' );
if ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ) {
    $webinar_template = 'modern';
}
$webinar_preview_url = add_query_arg( array(
    'webinar' => '',
    'lid'     => '[lead_id]',
    'preview' => 'true',
), get_the_permalink( $data->postID ) );
?>
<div class="tabber" id="tab2" style="display: none;">
<div class="titleBar">
	<div class="titleBarText">
		<h2><?php 
esc_html_e( 'Webinar Settings', 'webinarignition' );
?></h2>

		<p><?php 
esc_html_e( 'Here you can edit & manage your webinar settings', 'webinarignition' );
?>...</p>
	</div>
	<div class="launchConsole" style="margin-right: -20px;">
		<a
				href="<?php 
echo esc_url( $webinar_preview_url );
?>"
				target="_blank"
				data-default-href="<?php 
echo esc_url( $webinar_preview_url );
?>"
				class="custom_webinar_page-webinarPreviewLinkDefaultHolder"
		>
			<i class="icon-external-link-sign"></i>
			<?php 
esc_html_e( 'Preview Webinar Page', 'webinarignition' );
?>
		</a>
	</div>

	<br clear="all"/>
</div>

<?php 
$input_get = array(
    'id' => filter_input( INPUT_GET, 'id', FILTER_UNSAFE_RAW ),
);
?>

<?php 
webinarignition_display_edit_toggle(
    'time',
    esc_html__( 'Countdown Page - Settings & Copy', 'webinarignition' ),
    'we_edit_countdown',
    esc_html__( 'SEPERATE PAGE: This is the settings for the countdown page... (before webinar is live)', 'webinarignition' )
);
?>

<div id="we_edit_countdown" class="we_edit_area">
	<?php 
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->cd_headline,
    esc_html__( 'Countdown Headline', 'webinarignition' ),
    'cd_headline',
    esc_html__( 'This is the copy that is shown above the countdown timer...', 'webinarignition' )
);
if ( $webinar_data->webinar_date == 'AUTO' ) {
} else {
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->cd_button_show,
        esc_html__( 'Show Registration Button', 'webinarignition' ),
        'cd_button_show',
        esc_html__( 'You can either show the registration button, or you can hide it.', 'webinarignition' ),
        esc_html__( 'Show button', 'webinarignition' ) . ' [shown], ' . esc_html__( 'Hide button', 'webinarignition' ) . ' [hidden]'
    );
    ?>
		<div class="cd_button_show" id="cd_button_show_shown">
			<?php 
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->cd_button_copy,
        esc_html__( 'Register Button Copy', 'webinarignition' ),
        'cd_button_copy',
        esc_html__( 'This is the copy that is shown on the button below the countdown timer...', 'webinarignition' ),
        esc_html__( 'Ex. Register For The Webinar', 'webinarignition' )
    );
    webinarignition_display_color(
        $input_get['id'],
        $webinar_data->cd_button_color,
        esc_html__( 'Button Color', 'webinarignition' ),
        'cd_button_color',
        esc_html__( 'This is the color of the button...', 'webinarignition' ),
        esc_html__( 'Ex. #000000', 'webinarignition' )
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->cd_button,
        esc_html__( 'Register Button URL', 'webinarignition' ),
        'cd_button',
        esc_html__( 'You can either link to the landing page in this funnel or a custom URL', 'webinarignition' ),
        esc_html__( 'Go To Registration Page', 'webinarignition' ) . ' [we], ' . esc_html__( 'Custom Registration Page URL', 'webinarignition' ) . ' [custom]'
    );
    ?>
			<div class="cd_button" id="cd_button_custom">
				<?php 
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->cd_button_url,
        esc_html__( 'Custom Registration Page URL', 'webinarignition' ),
        'cd_button_url',
        esc_html__( 'This is a custom URL you want the button to go on the countdown page...', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/register-for-webinar/', 'webinarignition' )
    );
    ?>
			</div>
		</div>
		<?php 
    webinarignition_display_wpeditor(
        $input_get['id'],
        $webinar_data->cd_headline2,
        esc_html__( 'Register Headline', 'webinarignition' ),
        'cd_headline2',
        esc_html__( 'This is the copy that is shown under the countdown timer, above the button...', 'webinarignition' )
    );
}
//end if
webinarignition_display_info( esc_html__( 'Note: Countdown Page', 'webinarignition' ), esc_html__( 'This is the page people will see if they go to the webinar page if it is not yet live...', 'webinarignition' ) );
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->cd_months,
    esc_html__( 'Translate: months', 'webinarignition' ),
    'cd_months',
    esc_html__( 'You can change the sub title for the count down...', 'webinarignition' ),
    esc_html__( 'Ex. months', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->cd_weeks,
    esc_html__( 'Translate: weeks', 'webinarignition' ),
    'cd_weeks',
    esc_html__( 'You can change the sub title for the count down...', 'webinarignition' ),
    esc_html__( 'Ex. weeks', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->cd_days,
    esc_html__( 'Translate: Days', 'webinarignition' ),
    'cd_days',
    esc_html__( 'You can change the sub title for the count down...', 'webinarignition' ),
    esc_html__( 'Ex. days', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->cd_hours,
    esc_html__( 'Translate: hours', 'webinarignition' ),
    'cd_hours',
    esc_html__( 'You can change the sub title for the count down...', 'webinarignition' ),
    esc_html__( 'Ex. hours', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->cd_minutes,
    esc_html__( 'Translate: minutes', 'webinarignition' ),
    'cd_minutes',
    esc_html__( 'You can change the sub title for the count down...', 'webinarignition' ),
    esc_html__( 'Ex. minutes', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->cd_seconds,
    esc_html__( 'Translate: seconds', 'webinarignition' ),
    'cd_seconds',
    esc_html__( 'You can change the sub title for the count down...', 'webinarignition' ),
    esc_html__( 'Ex. seconds', 'webinarignition' )
);
?>
</div>

<?php 
webinarignition_display_edit_toggle(
    'cogs',
    esc_html__( 'Webinar Info Copy', 'webinarignition' ),
    'we_edit_webinar_settings',
    esc_html__( 'Edit the webinar information...', 'webinarignition' )
);
?>

<div id="we_edit_webinar_settings" class="we_edit_area">
	<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'webinar_info',
    $webinar_data,
    true,
    true
);
?>

	<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->webinar_info_block,
    esc_html__( 'Webinar Info Block Copy', 'webinarignition' ),
    'webinar_info_block',
    esc_html__( 'You can edit what the webinar info block says, if you want to translate it...', 'webinarignition' ),
    esc_html__( 'Keep Defaults', 'webinarignition' ) . ' [default], ' . esc_html__( 'Translate / Edit Copy', 'webinarignition' ) . ' [custom]'
);
?>
	<div class="webinar_info_block" id="webinar_info_block_custom" style="display: none;">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_info_block_title,
    esc_html__( 'Title Of Info Block', 'webinarignition' ),
    'webinar_info_block_title',
    esc_html__( 'This is the copy shown at the top of the info block...', 'webinarignition' ),
    esc_html__( 'Ex. Webinar Information', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_info_block_host,
    esc_html__( 'Host Title', 'webinarignition' ),
    'webinar_info_block_host',
    esc_html__( 'This is the copy that displays next to the hosts...', 'webinarignition' ),
    esc_html__( 'Ex. Your Host:', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_info_block_eventtitle,
    esc_html__( 'Webinar Title', 'webinarignition' ),
    'webinar_info_block_eventtitle',
    esc_html__( 'This is the copy that displays next to the webinar title...', 'webinarignition' ),
    esc_html__( 'Ex. Webinar Topic:', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_info_block_desc,
    esc_html__( 'Webinar Description', 'webinarignition' ),
    'webinar_info_block_desc',
    esc_html__( 'This is the copy that displays next to the webinar description...', 'webinarignition' ),
    esc_html__( 'Ex. What You Will Learn:', 'webinarignition' )
);
?>
	</div>
</div>

<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    webinarignition_display_edit_toggle(
        'play-sign',
        esc_html__( 'Webinar Auto Video Settings', 'webinarignition' ),
        'we_edit_webinar_video',
        esc_html__( 'Your live video setup settings here...', 'webinarignition' )
    );
} else {
    webinarignition_display_edit_toggle(
        'play-sign',
        esc_html__( 'Webinar Live Embed Video Settings', 'webinarignition' ),
        'we_edit_webinar_video',
        esc_html__( 'Your live video setup settings here...', 'webinarignition' )
    );
}
?>

<div id="we_edit_webinar_video" class="we_edit_area">
	<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'webinar_video',
    $webinar_data,
    true,
    true
);
?>
	<?php 
if ( 'AUTO' === $webinar_data->webinar_date ) {
    // Settings only for Evergreen webinar
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->webinar_source_toggle,
        esc_html__( 'Toggle Video Source', 'webinarignition' ),
        'webinar_source_toggle',
        esc_html__( 'You can switch between iframe embed, or direct video source from Amazon S3, etc', 'webinarignition' ),
        esc_html__( 'Default', 'webinarignition' ) . ' [default], ' . esc_html__( 'Iframe', 'webinarignition' ) . ' [iframe]'
    );
    ?>
		<div class="webinar_source_toggle" id="webinar_source_toggle_default">
			<p style="border-bottom: 1px dotted #e4e4e4;padding: 20px;padding-top: 20px;">
				<?php 
    echo wp_kses_post( __( 'If you would like to convert your youtube video to a file, try <a href="https://www.clipconverter.cc/2/" target="_blank">clipconverter</a> <br> To convert from .mp4 to webM format, try <a href="https://convertio.co/de/mp4-webm/" target="_blank">convertio</a> <br> You may also use <a href="https://handbrake.fr/" target="_blank">handbrake</a> to convert your file formats and also reduce the file size.<br><strong>Benefits</strong>: Load video faster, reduce space and bandwidth needed on server', 'webinarignition' ) );
    ?>
			</p>

			<p style="border-bottom: 1px dotted #e4e4e4;padding: 20px;padding-top: 20px;">
				<?php 
    /* translators: %1$s and %2$s are HTML tags for a link to the Tuxedo Big File Uploads plugin */
    printf( 
        /* translators: %1$s and %2$s are HTML tags for a link to the Tuxedo Big File Uploads plugin */
        esc_html__( 'If your host let you upload small sized videos only, install free %1$sTuxedo Big File Uploads%2$s plugin and use the "multi file upload link inside the add new media screen, to upload as big files as you like.', 'webinarignition' ),
        '<a href="/wp-admin/plugin-install.php?s=Tuxedo%20Big%20File%20Uploads&tab=search&type=term" target="_blank"><strong>',
        '</strong></a>'
     );
    ?>
			</p>

			<?php 
    webinarignition_display_field_add_media(
        $input_get['id'],
        $webinar_data->auto_video_url,
        esc_html__( 'Webinar Video URL .MP4 *', 'webinarignition' ),
        'auto_video_url',
        esc_html__( 'The MP4 file that you want to play as your automated webinar... must be in .mp4 format as its uses a html5 video player...', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/webinar-video.mp4', 'webinarignition' )
    );
    webinarignition_display_field_add_media(
        $input_get['id'],
        $webinar_data->auto_video_url2,
        esc_html__( 'Webinar Video URL .WEBM', 'webinarignition' ),
        'auto_video_url2',
        esc_html__( 'The Webm file that you want to play as your automated webinar... must be in .webm format as its uses a html5 video player.', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/webinar-video.webm', 'webinarignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_video_length,
        esc_html__( 'Webinar Video Length In Minutes', 'webinarignition' ),
        'auto_video_length_default',
        esc_html__( 'This is how long your webinar video is... NB:Must be in minutes ie:  60', 'webinarignition' ),
        esc_html__( 'Ex. 60', 'webinarignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_video_load,
        esc_html__( 'Webinar Video Loading Copy', 'webinarignition' ),
        'auto_video_load',
        esc_html__( 'This is the text that is shown above the video as it loads...', 'webinarignition' ),
        esc_html__( 'Ex. Please Wait - Webinar Is Loading...', 'webinarignition' )
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->webinar_live_overlay,
        esc_html__( 'Video/Stream left and right click', 'webinarignition' ),
        'webinar_live_overlay',
        __( "Choose whether or not to disable Video/Stream player's left and right click functionality on the live page. Enabling this option will prevent users from being able to click any of the player controls.<br>NB: This feature won't work with ZOOM since users may need to sign in.\n\t\t\t\t\tAs CTAs are above Video/Stream they will be clickable anyway.", 'webinarignition' ),
        esc_html__( 'Enabled', 'webinarignition' ) . ' [1], ' . esc_html__( 'Disabled', 'webinarignition' ) . ' [0]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->webinar_show_videojs_controls,
        esc_html__( 'Video Controls', 'webinarignition' ),
        'webinar_show_videojs_controls',
        esc_html__( 'Choose whether to show video player controls (Works for mp4 and webm formats only). Video Controls always shown in preview mode.', 'webinarignition' ),
        esc_html__( 'Hide controls', 'webinarignition' ) . ' [0], ' . esc_html__( 'Show controls', 'webinarignition' ) . ' [1]'
    );
    webinarignition_display_info( esc_html__( 'Note: Live Embed Code', 'webinarignition' ), esc_html__( 'This is the embed code the live streaming service gives you, it is automatically resized to fit: 920px by 518px...', 'webinarignition' ) );
    ?>
		</div>
		<div class="webinar_source_toggle" id="webinar_source_toggle_iframe">
			<div style="border-bottom: 1px dotted #e4e4e4;padding: 20px;padding-top: 20px;">
			<p>
				<strong style="text-transform: uppercase;"><?php 
    esc_html_e( 'warning' );
    ?>:</strong> <?php 
    echo wp_kses_post( esc_html__( 'If you are using Iframes:
                <ol>
                <li>Your visitors reload the page the video will start from the beginning.</li>
                <li>Also not redirecting after the end of the video... (could be developed)</li>
                <li>Iframe maybe not fitting in screen or is not responsive.</li>
                <li>Use Iframes only if you know what you are doing.</li>
            </ol>', 'webinarignition' ) );
    ?>
			</p>
			<p>
				<?php 
    esc_html_e( 'Iframe makes sense when you are sharing a stream like a virtual expo room, or a continues space view transmission. Why make sense? Because when user reload the page the stream continued in the background.', 'webinarignition' );
    ?>
				<?php 
    $kb_iframe_url = 'https://webinarignition.tawk.help/article/auto-webinar-setting-up-an-evergreen-webinar';
    $kb_iframe_link = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $kb_iframe_url ), esc_html__( 'See whole KB article about evergreen webinars.', 'webinarignition' ) );
    echo esc_html( $kb_iframe_link );
    ?>
			</p>
			<p>
				<?php 
    esc_html_e( 'A solution is to use an MP4 file -- which we can track and save the time seen already. When the page is reloaded we continue to play the video where it left.', 'webinarignition' );
    ?> <br><?php 
    esc_html_e( 'This feature creates a live webinar experience for your audience.', 'webinarignition' );
    ?>
			</p>
			</div>
			<?php 
    webinarignition_display_textarea(
        $input_get['id'],
        $webinar_data->webinar_iframe_source,
        esc_html__( 'Auto Webinar Iframe', 'webinarignition' ),
        'webinar_iframe_source',
        esc_html__( 'Provide the iframe source of your video/embed code/etc.', 'webinarignition' ),
        esc_html__( 'Video embed code...', 'webinarignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_video_length,
        esc_html__( 'Webinar Video Length In Minutes', 'webinarignition' ),
        'auto_video_length',
        esc_html__( 'This is how long your webinar video is... NB: Must be in minutes ie:  60', 'webinarignition' ),
        esc_html__( 'Ex. 60', 'webinarignition' )
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->webinar_live_overlay,
        esc_html__( 'Video Controls', 'webinarignition' ),
        'webinar_live_overlay1',
        esc_html__( "Choose whether or not to disable video player's left and right click functionality on the live page. Enabling this option will prevent users from being able to click any of the player controls. NB: This feature won't work with Zoom since users may need to sign in.", 'webinarignition' ),
        esc_html__( 'Enabled', 'webinarignition' ) . ' [0], ' . esc_html__( 'Disabled', 'webinarignition' ) . ' [1]'
    );
    webinarignition_display_info( esc_html__( 'Note: Iframe timed CTA issue', 'webinarignition' ), esc_html__( "Due to the fact that we cannot reference videos embedded in an iframe, we are unable to determine the time a video has played. Therefore, timed CTA's will start again on every page reload, also when the video resumes. Use an MP4 file (also external hosted) and we can also resume the CTAs and the CTAs are synchronized with the video.", 'webinarignition' ) );
    ?>

		</div>
		<?php 
    webinarignition_display_color(
        $input_get['id'],
        $webinar_data->webinar_live_bgcolor,
        esc_html__( 'Video Background Color', 'webinarignition' ),
        'webinar_live_bgcolor',
        esc_html__( 'This is the color for the area around the video...', 'webinarignition' ),
        '#000000'
    );
    ?>
		<?php 
} else {
    // Settings only for Live webinar
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?><div style="display: none;"><?php 
    }
    ?>
		<div class="cta_position_container"<?php 
    echo ( 'classic' !== $webinar_template ? '' : '' );
    ?>>
			<?php 
    $outer_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Outer / In tab', 'webinarignition' ) : esc_html__( 'Outer', 'webinarignition' ) );
    webinarignition_display_option(
        $input_get['id'],
        ( !isset( $webinar_data->cta_position ) ? 'outer' : $webinar_data->cta_position ),
        esc_html__( 'CTA Position', 'webinarignition' ),
        'cta_position',
        esc_html__( 'This settings for standard webinar template. If you select overlay, CTA section will cover your webinar video.', 'webinarignition' ),
        $outer_label . ' [outer], ' . esc_html__( 'Overlay', 'webinarignition' ) . ' [overlay]'
    );
    webinarignition_display_option(
        $input_get['id'],
        ( !isset( $webinar_data->cta_border_desktop ) ? 'yes' : $webinar_data->cta_border_desktop ),
        esc_html__( 'CTA Border Desktop', 'webinarignition' ),
        'cta_border_desktop',
        esc_html__( 'Select if you want to show or hide CTA block border on desktop devices.', 'webinarignition' ),
        esc_html__( 'Show', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Hide', 'webinarignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        ( !isset( $webinar_data->cta_border_mobile ) ? 'yes' : $webinar_data->cta_border_mobile ),
        esc_html__( 'CTA Border Mobile', 'webinarignition' ),
        'cta_border_mobile',
        esc_html__( 'Select if you want to show or hide CTA block border on mobile devices.', 'webinarignition' ),
        esc_html__( 'Show', 'webinarignition' ) . ' [yes], ' . esc_html__( 'Hide', 'webinarignition' ) . ' [no]'
    );
    ?>
		</div>
		<?php 
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?></div><?php 
    }
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->privacy_status,
        esc_html__( 'Video Privacy Status', 'webinarignition' ),
        'privacy_status',
        esc_html__( 'Choose Privacy Status for your Youtube Broadcasts', 'webinarignition' ),
        esc_html__( 'Unlisted', 'webinarignition' ) . ' [unlisted], ' . esc_html__( 'Public', 'webinarignition' ) . ' [public]'
    );
    webinarignition_display_textarea(
        $input_get['id'],
        $webinar_data->webinar_live_video,
        esc_html__( 'Live Video Embed Code', 'webinarignition' ),
        'webinar_live_video',
        esc_html__( 'This is the embed code for the live streaming for the webinar, can be Youtube, Vimeo, YouStream, etc...', 'webinarignition' ),
        esc_html__( 'Live video embed code...', 'webinarignition' )
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->webinar_live_overlay,
        esc_html__( 'Video Controls', 'webinarignition' ),
        'webinar_live_overlay',
        esc_html__( "Choose whether or not to disable video player's left and right click functionality on the live page. Enabling this option will prevent users from being able to click any of the player controls. NB: This feature won't work with Zoom since users may need to sign in.", 'webinarignition' ),
        esc_html__( 'Enabled', 'webinarignition' ) . ' [0], ' . esc_html__( 'Disabled', 'webinarignition' ) . ' [1]'
    );
    webinarignition_display_color(
        $input_get['id'],
        $webinar_data->webinar_live_bgcolor,
        esc_html__( 'Live Video Background Color', 'webinarignition' ),
        'webinar_live_bgcolor',
        esc_html__( 'This is the color for the area around the video...', 'webinarignition' ),
        '#000000'
    );
    webinarignition_display_info( esc_html__( 'Note: Live Embed Code', 'webinarignition' ), esc_html__( 'This is the embed code the live streaming service gives you, it is automatically resized to fit: 920px by 518px...', 'webinarignition' ) );
}
//end if
?>
</div>

<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    webinarignition_display_edit_toggle(
        'money',
        esc_html__( 'Auto Webinar Actions', 'webinarignition' ),
        'we_edit_auto_actions',
        esc_html__( 'Settings for timed actions, ending redirect and CTA popup...', 'webinarignition' )
    );
    // Template for Additional CTA
    if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?>
		<div
				class="additional_auto_action_template_container auto_action_container"
				data-title="<?php 
        echo esc_html__( 'Additional CTA Settings', 'webinarignition' );
        ?>"
				style="display: none"
		>
			<div class="additional_auto_action_item auto_action_item auto_action_item_active">
				<div class="auto_action_header">
					<h4>
						<?php 
        echo esc_html__( 'Additional CTA Settings', 'webinarignition' );
        ?> <span class="index_holder"></span>
						<span class="auto_action_desc_holder"></span>
						<i class="icon-arrow-up"></i>
						<i class="icon-arrow-down"></i>
					</h4>
				</div>

				<div class="auto_action_body">
					<?php 
        $outer_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Outer / In tab', 'webinarignition' ) : esc_html__( 'Outer', 'webinarignition' ) );
        webinarignition_display_option(
            $input_get['id'],
            'outer',
            esc_html__( 'CTA Position', 'webinarignition' ),
            'cta_position_additional',
            esc_html__( 'This settings for standard webinar template. If you select overlay, CTA section will cover your webinar video.', 'webinarignition' ),
            $outer_label . ' [outer], ' . esc_html__( 'Overlay', 'webinarignition' ) . ' [overlay]'
        );
        $alignment_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Left', 'webinarignition' ) : (( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Center', 'webinarignition' ) : esc_html__( 'Right', 'webinarignition' ) )) );
        ?>			
					<div class="default_cta_alignement_new" style="display: none;" data-id="cta_position_additional">
						<?php 
        ?>
							<div class="editSection">
								<div class="inputTitle">
									<div class="inputTitleCopy">CTA Alignment</div>
									<div class="inputTitleHelp">This settings for standard webinar template. Select alignments for CTA section.</div>
								</div>
								<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
									<?php 
        Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
        ?> 
								</div>
								<br clear="left">
							</div>
						<?php 
        ?>
					</div>
					<?php 
        webinarignition_display_min_sec_mask_field(
            $input_get['id'],
            '',
            esc_html__( 'Action Time Show :: Minutes:Seconds', 'webinarignition' ),
            'additional-autoaction__auto_action_time',
            esc_html__( "This is when you want your CTA are to display based on the minutes:seconds mark of your video. Ie. when your video gets to (or passed) 1 min 59 sec, it will show the CTA. NB: Minute mark should be clear like '1' second - '59'", 'webinarignition' ),
            esc_html__( 'f.e. 1:59', 'webinarignition' )
        );
        webinarignition_display_min_sec_mask_field(
            $input_get['id'],
            '',
            esc_html__( 'Action Time Hide :: Minutes:Seconds', 'webinarignition' ),
            'additional-autoaction__auto_action_time_end',
            esc_html__( 'This is when you want your CTA to hide at the time (minutes:seconds) of your video. If this time value is less than "Action Time Show" or empty, then CTA will not show. <br>To keep your CTA visible after the video ends, set this value to anything more than the video time.', 'webinarignition' ),
            esc_html__( 'f.e. 2:59', 'webinarignition' )
        );
        webinarignition_display_textarea(
            $input_get['id'],
            '',
            esc_html__( 'CTA Headline Copy', 'webinarignition' ),
            'additional-autoaction__auto_action_copy__',
            esc_html__( 'This is the copy that is shown above the main CTA button...', 'webinarignition' ),
            ''
        );
        if ( class_exists( 'advancediFrame' ) ) {
            $no_option_label = esc_html__( 'No', 'webinarignition' );
            $yes_option_label = esc_html__( 'Yes', 'webinarignition' );
            webinarignition_display_option(
                $input_get['id'],
                'no',
                esc_html__( 'Display CTA in iFrame', 'webinarignition' ),
                'additional-autoaction__cta_iframe',
                esc_html__( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' ),
                "{$no_option_label} [no],{$yes_option_label} [yes]"
            );
            ?>
						<div class="additional-autoaction__cta_iframe" id="additional-autoaction__cta_iframe___yes">
							<?php 
            webinarignition_display_textarea(
                $input_get['id'],
                '',
                esc_html__( 'Advanced Iframe Shortcode', 'webinarignition' ),
                'additional-autoaction__cta_iframe_sc',
                esc_html__( 'You can modify default Advanced Iframe shortcode settings by pasting the shortcode with your own settings here.', 'webinarignition' ),
                esc_html__( 'Example: [advanced_iframe width="100%" height="100"]', 'webinarignition' )
            );
            ?>
						</div>
						<?php 
        } else {
            $advanced_iframe_url = sprintf( '<a href="%s" target="_blank">%s</a>', self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=advanced-iframe' ), esc_html__( 'Advanced iFrame', 'webinarignition' ) );
            webinarignition_display_two_col_info( 
                esc_html__( 'Display CTA in iFrame', 'webinarignition' ),
                esc_html__( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' ),
                /* translators: %s: HTML link to Advanced iFrame plugin */
                sprintf( esc_html__( 'Your CTA content is not looking nicely? Then you can show your CTA contents in an Iframe, to enable this feature you need to install and activate the free "%s" plugin.', 'webinarignition' ), $advanced_iframe_url )
             );
        }
        //end if
        webinarignition_display_field(
            $input_get['id'],
            '',
            ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ? esc_html__( 'CTA Button Copy / Tab Name', 'webinarignition' ) : esc_html__( 'CTA Button Copy', 'webinarignition' ) ),
            'additional-autoaction__auto_action_btn_copy__',
            esc_html__( 'This is what the CTA button copy says...', 'webinarignition' ),
            esc_html__( 'Ex. Click Here To Claim Your Spot', 'webinarignition' )
        );
        webinarignition_display_field(
            $input_get['id'],
            '',
            esc_html__( 'CTA Button URL', 'webinarignition' ),
            'additional-autoaction__auto_action_url__',
            esc_html__( 'This is where the button will go... NB: if you dont want the CTA button to appear, leave this area empty...', 'webinarignition' ),
            esc_html__( 'Ex. http://yoursite.com/order-now', 'webinarignition' )
        );
        webinarignition_display_color(
            $input_get['id'],
            '',
            'CTA Button Color',
            'additional-autoaction__replay_order_color__',
            esc_html__( 'This is the color of the CTA button...', 'webinarignition' ),
            '#6BBA40'
        );
        webinarignition_display_field(
            $input_get['id'],
            '0',
            esc_html__( 'CTA section Max width, px', 'webinarignition' ),
            'additional-autoaction__auto_action_max_width__',
            esc_html__( 'Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA 100% width', 'webinarignition' ),
            esc_html__( 'Ex. 10', 'webinarignition' )
        );
        webinarignition_display_field(
            $input_get['id'],
            '0',
            esc_html__( 'CTA background transparancy', 'webinarignition' ),
            'additional-autoaction__auto_action_transparency__',
            esc_html__( 'Set BG transparancy from 0 to 100, where 100 - totally transparent', 'webinarignition' ),
            esc_html__( 'Ex. 10', 'webinarignition' )
        );
        ?>
				</div>

				<div class="auto_action_footer" style="padding: 15px;">
					<button type="button" class="blue-btn-44 btn cloneAutoAction" style="color:#FFF;float:none;">
						<i class="icon-copy"></i> <?php 
        esc_html_e( 'Clone current', 'webinarignition' );
        ?>
					</button>

					<button type="button" class="blue-btn btn deleteAutoAction" style="color:#FFF;float:none;">
						<i class="icon-remove"></i> <?php 
        esc_html_e( 'Delete', 'webinarignition' );
        ?>
					</button>
				</div>
			</div>
		</div>
		<?php 
    }
    //end if
    ?>
	<div id="we_edit_auto_actions" class="we_edit_area">
		<div class="additional_auto_action_control editSection" style="border-bottom: 3px solid #e4e4e4;padding-top: 20px;">
			<h3 style="margin: 0;"><?php 
    esc_html_e( 'Call-To-Actions Settings', 'webinarignition' );
    ?></h3>
		</div>
	<?php 
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?><div style="display: none;"><?php 
    }
    ?>
		<div class="cta_position_container"<?php 
    echo ( 'classic' !== $webinar_template ? '' : '' );
    ?>>
			<?php 
    webinarignition_display_option(
        $input_get['id'],
        ( !isset( $webinar_data->cta_border_desktop ) ? 'no' : $webinar_data->cta_border_desktop ),
        esc_html__( 'CTA Border Desktop', 'webinarignition' ),
        'cta_border_desktop',
        esc_html__( 'Select if you want to show or hide CTA block border on desktop devices.', 'webinarignition' ),
        esc_html__( 'Hide', 'webinarignition' ) . ' [no], ' . esc_html__( 'Show', 'webinarignition' ) . ' [yes]'
    );
    webinarignition_display_option(
        $input_get['id'],
        ( !isset( $webinar_data->cta_border_mobile ) ? 'no' : $webinar_data->cta_border_mobile ),
        esc_html__( 'CTA Border Mobile', 'webinarignition' ),
        'cta_border_mobile',
        esc_html__( 'Select if you want to show or hide CTA block border on mobile devices.', 'webinarignition' ),
        esc_html__( 'Hide', 'webinarignition' ) . ' [no], ' . esc_html__( 'Show', 'webinarignition' ) . ' [yes]'
    );
    ?>
		</div>
		<?php 
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?></div><?php 
    }
    ?>
		<?php 
    WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
        'webinar_cta',
        $webinar_data,
        true,
        true
    );
    ?>

		<div class="default_auto_action_container auto_action_container auto_action_item_active auto_action_item">
			<?php 
    $additional_text = '';
    if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        $additional_text = '<br>' . esc_html__( 'If you select "Always Show CTA" - additioanal CTAs will be disabled. If you want to use multiple CTAs - select "Show CTA Based On Time In Video" option', 'webinarignition' ) . '<br><br><p> <span>' . '<a href="https://webinarignition.tawk.help/article/do-you-also-have-only-one-action-in-webinar" target="_blank"><strong>' . esc_html__( 'Integrate WP plugins, external sites inside your webinar room - Keep the CTAs and user inside the webinar - Tutorial', 'webinarignition' ) . '</strong></a>' . '</span></p>';
    }
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_action,
        esc_html__( 'Default CTA Action', 'webinarignition' ),
        'auto_action',
        esc_html__( 'Settings for the CTA to appear on the automated webinar page. Can either be shown from the start OR based on a time in the video...', 'webinarignition' ) . $additional_text,
        esc_html__( 'Show CTA Based On Time In Video', 'webinarignition' ) . ' [time], ' . esc_html__( 'Always Show CTA', 'webinarignition' ) . ' [start]'
    );
    ?>

			<?php 
    if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        $auto_action_time_holder = '';
        if ( !empty( $webinar_data->auto_action_time ) ) {
            $auto_action_time_holder .= $webinar_data->auto_action_time;
        }
        if ( !empty( $webinar_data->auto_action_time_end ) ) {
            if ( !empty( $auto_action_time_holder ) ) {
                $auto_action_time_holder .= ' - ';
            } else {
                $auto_action_time_holder .= '0:00 - ';
            }
            $auto_action_time_holder .= $webinar_data->auto_action_time_end;
        }
        if ( !empty( $webinar_data->auto_action_btn_copy ) ) {
            if ( !empty( $auto_action_time_holder ) ) {
                $auto_action_time_holder .= ' - ';
            }
            $auto_action_time_holder .= $webinar_data->auto_action_btn_copy;
        }
        if ( !empty( $auto_action_time_holder ) ) {
            $auto_action_time_holder = '(' . $auto_action_time_holder . ')';
        }
        ?>
				<div class="auto_action_header">
					<h4>
						<?php 
        echo esc_html__( 'Default CTA Settings', 'webinarignition' );
        ?>
						<span class="auto_action_desc_holder"><?php 
        echo esc_html( $auto_action_time_holder );
        ?></span>
						<i class="icon-arrow-up"></i>
						<i class="icon-arrow-down"></i>
					</h4>
				</div>
				<?php 
    }
    //end if
    ?>

			<div class="auto_action_body">
				<?php 
    $outer_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Outer / In tab', 'webinarignition' ) : esc_html__( 'Outer', 'webinarignition' ) );
    webinarignition_display_option(
        $input_get['id'],
        ( !isset( $webinar_data->cta_position ) ? 'outer' : $webinar_data->cta_position ),
        esc_html__( 'CTA Position', 'webinarignition' ),
        'cta_position',
        esc_html__( 'This settings for standard webinar template. If you select overlay, CTA section will cover your webinar video.', 'webinarignition' ),
        $outer_label . ' [outer], ' . esc_html__( 'Overlay', 'webinarignition' ) . ' [overlay]'
    );
    $alignment_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Left', 'webinarignition' ) : (( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Center', 'webinarignition' ) : esc_html__( 'Right', 'webinarignition' ) )) );
    ?>
				<div class="default_cta_alignement_new">
					<?php 
    ?>
						<div class="editSection">
							<div class="inputTitle">
								<div class="inputTitleCopy">CTA Alignment</div>
								<div class="inputTitleHelp">This settings for standard webinar template. Select alignments for CTA section.</div>
							</div>
							<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
								<?php 
    Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
    ?> 
							</div>
							<br clear="left">
						</div>
					<?php 
    ?>
				</div>

				<div class="auto_action" id="auto_action_time">
					<?php 
    webinarignition_display_min_sec_mask_field(
        $input_get['id'],
        $webinar_data->auto_action_time,
        esc_html__( 'Action Time Show :: Minutes:Seconds', 'webinarignition' ),
        'auto_action_time',
        esc_html__( "This is when you want your CTA are to display based on the minutes:seconds mark of your video. Ie. when your video gets to (or passed) 1 min 59 sec, it will show the CTA. NB: Minute mark should be clear like '1' second - '59'", 'webinarignition' ),
        esc_html__( 'f.e. 1:59', 'webinarignition' )
    );
    webinarignition_display_min_sec_mask_field(
        $input_get['id'],
        ( !empty( $webinar_data->auto_action_time_end ) ? $webinar_data->auto_action_time_end : '' ),
        esc_html__( 'Action Time Hide :: Minutes:Seconds', 'webinarignition' ),
        'auto_action_time_end',
        esc_html__( 'This is when you want your CTA to hide at the time (minutes:seconds) of your video. If this time value is less than "Action Time Show" or empty, then CTA will not show. <br>To keep your CTA visible after the video ends, set this value to anything more than the video time.', 'webinarignition' ),
        esc_html__( 'f.e. 2:59', 'webinarignition' )
    );
    ?>
				</div>
				<?php 
    webinarignition_display_wpeditor_media(
        $input_get['id'],
        $webinar_data->auto_action_copy,
        esc_html__( 'CTA Headline Copy', 'webinarignition' ),
        'auto_action_copy',
        esc_html__( 'This is the copy that is shown above the main CTA button...', 'webinarignition' )
    );
    if ( class_exists( 'advancediFrame' ) ) {
        $no_option_label = esc_html__( 'No', 'webinarignition' );
        $yes_option_label = esc_html__( 'Yes', 'webinarignition' );
        webinarignition_display_option(
            $input_get['id'],
            ( isset( $webinar_data->cta_iframe ) ? $webinar_data->cta_iframe : 'no' ),
            esc_html__( 'Display CTA in iFrame', 'webinarignition' ),
            'cta_iframe',
            esc_html__( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' ),
            "{$no_option_label} [no],{$yes_option_label} [yes]"
        );
        ?>
					<div class="cta_iframe" id="cta_iframe_yes">
						<?php 
        webinarignition_display_textarea(
            $input_get['id'],
            ( isset( $webinar_data->cta_iframe_sc ) ? $webinar_data->cta_iframe_sc : '' ),
            esc_html__( 'Advanced Iframe Shortcode', 'webinarignition' ),
            'cta_iframe_sc',
            esc_html__( 'You can modify default Advanced Iframe shortcode settings by pasting the shortcode with your own settings here.', 'webinarignition' ),
            esc_html__( 'Example: [advanced_iframe width="100%" height="100"]', 'webinarignition' )
        );
        ?>
					</div>
					<?php 
    } else {
        $advanced_iframe_url = sprintf( '<a href="%s" target="_blank">%s</a>', self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=advanced-iframe' ), esc_html__( 'Advanced iFrame', 'webinarignition' ) );
        webinarignition_display_two_col_info( 
            esc_html__( 'Display CTA in iFrame', 'webinarignition' ),
            esc_html__( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' ),
            /* translators: %s: HTML link to Advanced iFrame plugin */
            sprintf( esc_html__( 'Your CTA content is not looking nicely? Then you can show your CTA contents in an Iframe, to enable this feature you need to install and activate the free "%s" plugin.', 'webinarignition' ), $advanced_iframe_url )
         );
    }
    //end if
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_action_btn_copy,
        ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ? esc_html__( 'CTA Button Copy / Tab Name', 'webinarignition' ) : esc_html__( 'CTA Button Copy', 'webinarignition' ) ),
        'auto_action_btn_copy',
        esc_html__( 'This is what the CTA button copy says...', 'webinarignition' ),
        esc_html__( 'Ex. Click Here To Claim Your Spot', 'webinarignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_action_url,
        esc_html__( 'CTA Button URL', 'webinarignition' ),
        'auto_action_url',
        esc_html__( 'This is where the button will go... NB: if you dont want the CTA button to appear, leave this area empty...', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/order-now', 'webinarignition' )
    );
    webinarignition_display_color(
        $input_get['id'],
        $webinar_data->replay_order_color,
        'CTA Button Color',
        'replay_order_color',
        esc_html__( 'This is the color of the CTA button...', 'webinarignition' ),
        '#6BBA40'
    );
    // webinarignition_display_number_field(
    // $input_get['id'],
    // !empty($webinar_data->auto_action_max_width) ? $webinar_data->auto_action_max_width : '0',
    // esc_html__( "CTA section Max width, px", 'webinarignition' ),
    // "auto_action_max_width",
    // esc_html__( "Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA default width", 'webinarignition' ),
    // esc_html__( "Ex. 10", 'webinarignition' )
    // );
    ?>
				<div class="default-dashboard-cta-width-cont">
					<?php 
    webinarignition_display_field(
        $input_get['id'],
        ( !empty( $webinar_data->auto_action_max_width ) ? $webinar_data->auto_action_max_width : '0' ),
        esc_html__( 'CTA Section Max Width, px or %', 'webinarignition' ),
        'auto_action_max_width',
        esc_html__( 'Set maximum width for default CTA section. CTA will fill the width and on smaller screens it will show smaller. Left blank or set 0 if you want to set CTA 60% width', 'webinarignition' ),
        esc_html__( 'Ex. 10', 'webinarignition' )
    );
    ?>
				</div>
				<div class="default-dashboard-cta-transparency-cont">
					<?php 
    webinarignition_display_field(
        $input_get['id'],
        ( !empty( $webinar_data->auto_action_transparency ) ? $webinar_data->auto_action_transparency : '0' ),
        esc_html__( 'CTA background transparancy', 'webinarignition' ),
        'auto_action_transparency',
        esc_html__( 'Set BG transparancy from 0 to 100, where 100 - totally transparent', 'webinarignition' ),
        esc_html__( 'Ex. 10', 'webinarignition' )
    );
    ?>
				</div>
				<?php 
    if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?>
					<div class="auto_action_footer auto_action auto_action_time_visible" style="padding: 15px;">
						<button type="button" id="cloneMainAutoAction" class="blue-btn-44 btn" style="color:#FFF;float:none;">
							<i class="icon-copy"></i> <?php 
        esc_html_e( 'Clone current', 'webinarignition' );
        ?>
						</button>
					</div>
					<?php 
    }
    ?>
			</div>

		</div>

		<?php 
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?>
			<div class="editSection">
				<div class="inputTitle">
					<div class="inputTitleCopy" ><?php 
        echo esc_html__( 'Multiple CTA with Unlimited Shortcodes', 'webinarignition' );
        ?></div>
				</div>
				<div class="inputSection" >
					<p>
						<?php 
        printf( 
            /* translators: %s: URL to the help article about multiple CTAs */
            esc_html__( 'Multiple CTA allows you create as many CTAs as you need and show each according to video play time. Also add shortcodes like <code>[products category="women"]</code>, polls, quiz inside. <a href="%s" target="_blank">Read more...</a>', 'webinarignition' ),
            'https://webinarignition.tawk.help/article/do-you-also-have-only-one-action-in-webinar'
         );
        ?>
					</p>
					<p>
						<?php 
        $wi_dashboard_url = add_query_arg( 'page', 'webinarignition-dashboard', admin_url( 'admin.php' ) );
        /* translators: %s: URL to the WebinarIgnition dashboard */
        printf( esc_html__( 'Available only in Ultimate version, <a href="%s" target="_blank">get it here</a>.', 'webinarignition' ), esc_url( $wi_dashboard_url ) );
        ?>
					</p>

					<?php 
        // webinarignition_display_only_ultimate_upgrade($statusCheck, $webinar_data);
        ?>
				</div>
				<br clear="left" >
			</div>

			<div style="display: none;"><?php 
    }
    //end if
    ?>
			<div class="auto_action auto_action_start_visible auto_action_item" style="padding-bottom: 20px;">
				<?php 
    webinarignition_display_info( esc_html__( 'Note: Multiple CTA', 'webinarignition' ), esc_html__( 'If you want to have multiple CTAs select "Show CTA Based On Time In Video" for Default CTA Action ', 'webinarignition' ) );
    ?>
			</div>

			<div id="additional_auto_action_container" class="auto_action auto_action_time_visible additional_auto_action_container auto_action_container">
				<?php 
    if ( !empty( $webinar_data->additional_autoactions ) ) {
        $additional_autoactions = maybe_unserialize( $webinar_data->additional_autoactions );
    } else {
        $additional_autoactions = array();
    }
    foreach ( $additional_autoactions as $index => $additional_autoaction ) {
        // var_dump($additional_autoaction);
        $auto_action_time_holder = '';
        if ( !empty( $additional_autoaction['auto_action_time'] ) ) {
            $auto_action_time_holder .= $additional_autoaction['auto_action_time'];
        }
        if ( !empty( $additional_autoaction['auto_action_time_end'] ) ) {
            if ( !empty( $auto_action_time_holder ) ) {
                $auto_action_time_holder .= ' - ';
            } else {
                $auto_action_time_holder .= '0:00 - ';
            }
            $auto_action_time_holder .= $additional_autoaction['auto_action_time_end'];
        }
        if ( !empty( $additional_autoaction['auto_action_btn_copy'] ) ) {
            if ( !empty( $auto_action_time_holder ) ) {
                $auto_action_time_holder .= ' - ';
            }
            $auto_action_time_holder .= $additional_autoaction['auto_action_btn_copy'];
        }
        if ( !empty( $auto_action_time_holder ) ) {
            $auto_action_time_holder = '(' . $auto_action_time_holder . ')';
        }
        ?>
					<div class="additional_auto_action_item auto_action_item">
						<div class="auto_action_header">
							<h4>
								<?php 
        echo esc_html__( 'Additional CTA Settings', 'webinarignition' );
        ?>  <span class="index_holder"><?php 
        echo esc_html( $index );
        ?></span>
								<span class="auto_action_desc_holder"><?php 
        echo esc_html( $auto_action_time_holder );
        ?></span>
								<i class="icon-arrow-up"></i>
								<i class="icon-arrow-down"></i>
							</h4>

						</div>

						<div class="auto_action_body">
							<?php 
        $additional_cta_position = 'outer';
        if ( isset( $additional_autoaction['cta_position'] ) ) {
            $additional_cta_position = $additional_autoaction['cta_position'];
        } elseif ( isset( $webinar_data->cta_position ) ) {
            $additional_cta_position = $webinar_data->cta_position;
        }
        $outer_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Outer / In tab', 'webinarignition' ) : esc_html__( 'Outer', 'webinarignition' ) );
        webinarignition_display_option(
            $input_get['id'],
            $additional_cta_position,
            esc_html__( 'CTA Position', 'webinarignition' ),
            'additional-autoaction__cta_position__' . $index,
            esc_html__( 'This settings for standard webinar template. If you select overlay, CTA section will cover your webinar video.', 'webinarignition' ),
            $outer_label . ' [outer], ' . esc_html__( 'Overlay', 'webinarignition' ) . ' [overlay]'
        );
        $additional_cta_alignment = 'Center';
        if ( isset( $additional_autoaction['cta_alignment'] ) ) {
            $additional_cta_alignment = $additional_autoaction['cta_alignment'];
        } elseif ( isset( $additional_autoaction['cta_alignment'] ) ) {
            $additional_cta_alignment = $additional_autoaction['cta_alignment'];
        }
        $alignment_label = ( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Left', 'webinarignition' ) : (( WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ? esc_html__( 'Center', 'webinarignition' ) : esc_html__( 'Right', 'webinarignition' ) )) );
        ?>
							<div class="cta_alignement_new">
								<?php 
        ?>
									<div class="editSection">
										<div class="inputTitle">
											<div class="inputTitleCopy">CTA Alignment</div>
											<div class="inputTitleHelp">This settings for standard webinar template. Select alignments for CTA section.</div>
										</div>
										<div class="inputSection" style="padding-top:20px; padding-bottom: 30px;">
											<?php 
        Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
        ?> 
										</div>
										<br clear="left">
									</div>
								<?php 
        ?>
							</div>
						<?php 
        webinarignition_display_min_sec_mask_field(
            $input_get['id'],
            ( !empty( $additional_autoaction['auto_action_time'] ) ? $additional_autoaction['auto_action_time'] : '0:00' ),
            esc_html__( 'Action Time Show :: Minutes:Seconds', 'webinarignition' ),
            'additional-autoaction__auto_action_time__' . $index,
            esc_html__( "This is when you want your CTA are to display based on the minutes:seconds mark of your video. Ie. when your video gets to (or passed) 1 min 59 sec, it will show the CTA. NB: Minute mark should be clear like '1' second - '59'", 'webinarignition' ),
            esc_html__( 'f.e. 1:59', 'webinarignition' )
        );
        webinarignition_display_min_sec_mask_field(
            $input_get['id'],
            ( !empty( $additional_autoaction['auto_action_time_end'] ) ? $additional_autoaction['auto_action_time_end'] : '0:00' ),
            esc_html__( 'Action Time Hide :: Minutes:Seconds', 'webinarignition' ),
            'additional-autoaction__auto_action_time_end__' . $index,
            wp_kses_post( __( 'This is when you want your CTA to hide at the time (minutes:seconds) of your video. If this time value is less than "Action Time Show" or empty, then CTA will not show. <br>To keep your CTA visible after the video ends, set this value to anything more than the video time.', 'webinarignition' ) ),
            esc_html__( 'f.e. 2:59', 'webinarignition' )
        );
        webinarignition_display_wpeditor_media(
            $input_get['id'],
            ( isset( $additional_autoaction['auto_action_copy'] ) ? $additional_autoaction['auto_action_copy'] : '' ),
            esc_html__( 'CTA Headline Copy', 'webinarignition' ),
            'additional-autoaction__auto_action_copy__' . $index,
            esc_html__( 'This is the copy that is shown above the main CTA button...', 'webinarignition' )
        );
        if ( class_exists( 'advancediFrame' ) ) {
            $no_option_label = esc_html__( 'No', 'webinarignition' );
            $yes_option_label = esc_html__( 'Yes', 'webinarignition' );
            webinarignition_display_option(
                $input_get['id'],
                ( isset( $additional_autoaction['cta_iframe'] ) ? $additional_autoaction['cta_iframe'] : 'no' ),
                esc_html__( 'Display CTA in iFrame', 'webinarignition' ),
                'additional-autoaction__cta_iframe__' . $index,
                esc_html__( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' ),
                "{$no_option_label} [no],{$yes_option_label} [yes]"
            );
            ?>
								<div class="additional-autoaction__cta_iframe__<?php 
            echo esc_html( $index );
            ?>" id="additional-autoaction__cta_iframe__<?php 
            echo esc_html( $index );
            ?>_yes">
								<?php 
            webinarignition_display_textarea(
                $input_get['id'],
                ( isset( $additional_autoaction['cta_iframe_sc'] ) ? $additional_autoaction['cta_iframe_sc'] : '' ),
                esc_html__( 'Advanced Iframe Shortcode', 'webinarignition' ),
                'additional-autoaction__cta_iframe_sc__' . $index,
                esc_html__( 'You can modify default Advanced Iframe shortcode settings by pasting the shortcode with your own settings here.', 'webinarignition' ),
                esc_html__( 'Example: [advanced_iframe width="100%" height="100"]', 'webinarignition' )
            );
            ?>
								</div>
								<?php 
        } else {
            $advanced_iframe_url = sprintf( '<a href="%s" target="_blank">%s</a>', self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=advanced-iframe' ), esc_html__( 'Advanced iFrame', 'webinarignition' ) );
            webinarignition_display_two_col_info( 
                esc_html__( 'Display CTA in iFrame', 'webinarignition' ),
                esc_html__( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' ),
                /* translators: %s: HTML link to Advanced iFrame plugin */
                sprintf( esc_html__( 'Your CTA content is not looking nicely? Then you can show your CTA contents in an Iframe, to enable this feature you need to install and activate the free "%s" plugin.', 'webinarignition' ), $advanced_iframe_url )
             );
        }
        //end if
        webinarignition_display_field(
            $input_get['id'],
            $additional_autoaction['auto_action_btn_copy'],
            ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ? esc_html__( 'CTA Button Copy / Tab Name', 'webinarignition' ) : esc_html__( 'CTA Button Copy', 'webinarignition' ) ),
            'additional-autoaction__auto_action_btn_copy__' . $index,
            esc_html__( 'This is what the CTA button copy says...', 'webinarignition' ),
            esc_html__( 'Ex. Click Here To Claim Your Spot', 'webinarignition' )
        );
        webinarignition_display_field(
            $input_get['id'],
            $additional_autoaction['auto_action_url'],
            esc_html__( 'CTA Button URL', 'webinarignition' ),
            'additional-autoaction__auto_action_url__' . $index,
            esc_html__( "This is where the button will go... NB: if you don't want the CTA button to appear, leave this area empty...", 'webinarignition' ),
            esc_html__( 'Ex. http://yoursite.com/order-now', 'webinarignition' )
        );
        webinarignition_display_color(
            $input_get['id'],
            $additional_autoaction['replay_order_color'],
            'CTA Button Color',
            'additional-autoaction__replay_order_color__' . $index,
            esc_html__( 'This is the color of the CTA button...', 'webinarignition' ),
            '#6BBA40'
        );
        // webinarignition_display_number_field(
        // $input_get['id'],
        // !empty($additional_autoaction['auto_action_max_width']) ? $additional_autoaction['auto_action_max_width'] : '0',
        // esc_html__( "CTA section Max width, px", 'webinarignition' ),
        // "additional-autoaction__auto_action_max_width__" . $index,
        // esc_html__( "Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA default width", 'webinarignition' ),
        // esc_html__( "Ex. 10", 'webinarignition' )
        // );
        ?>
								<div class="dashboard-cta-width-cont">
									<?php 
        webinarignition_display_field(
            $input_get['id'],
            ( !empty( $additional_autoaction['auto_action_max_width'] ) ? $additional_autoaction['auto_action_max_width'] : '0' ),
            esc_html__( 'CTA Section Max Width, px or %', 'webinarignition' ),
            'additional-autoaction__auto_action_max_width__' . $index,
            esc_html__( 'Set the maximum width in % or px for this CTA. Leave blank or set to 0 if you want the CTA to be 60% wide.', 'webinarignition' ),
            esc_html__( 'Ex. 10', 'webinarignition' )
        );
        ?>
								</div>
								<div class="dashboard-cta-transparency-cont">
									<?php 
        webinarignition_display_field(
            $input_get['id'],
            ( !empty( $additional_autoaction['auto_action_transparency'] ) ? $additional_autoaction['auto_action_transparency'] : '0' ),
            esc_html__( 'CTA background transparancy', 'webinarignition' ),
            'additional-autoaction__auto_action_transparency__' . $index,
            esc_html__( 'Set BG transparancy from 0 to 100, where 100 - totally transparent', 'webinarignition' ),
            esc_html__( 'Ex. 10', 'webinarignition' )
        );
        ?>
								</div>
						</div>

						<div class="auto_action_footer" style="padding: 15px;">
							<button type="button" class="blue-btn-44 btn cloneAutoAction" style="color:#FFF;float:none;">
								<i class="icon-copy"></i> <?php 
        esc_html_e( 'Clone current', 'webinarignition' );
        ?>
							</button>

							<button type="button" class="blue-btn btn deleteAutoAction" style="color:#FFF;float:none;">
								<i class="icon-remove"></i> <?php 
        esc_html_e( 'Delete', 'webinarignition' );
        ?>
							</button>
						</div>
					</div>
					<?php 
    }
    //end foreach
    ?>
			</div>

			<div class="additional_auto_action_control auto_action  auto_action_time_visible editSection" style="border-bottom: 3px solid #e4e4e4;">
				<button type="button" id="createAutoAction" class="blue-btn-44 btn" style="color:#FFF;float:none;">
					<i class="icon-plus"></i> <?php 
    esc_html_e( 'Create New CTA', 'webinarignition' );
    ?>
				</button>
			</div>
			<?php 
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?></div><?php 
    }
    /** Time tags section */
    webinarignition_display_time_tags_section( $webinar_data );
    ?>

		<div class="auto_redirect_container">
			<?php 
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_redirect,
        esc_html__( 'Ending Redirect', 'webinarignition' ),
        'auto_redirect',
        esc_html__( 'You can have them redirect to any URL you want after the video is done playing...', 'webinarignition' ),
        esc_html__( 'Disable Ending Redirect', 'webinarignition' ) . ' [1], ' . esc_html__( 'Enable Ending Redirect', 'webinarignition' ) . ' [redirect]'
    );
    ?>
			<div class="auto_redirect" id="auto_redirect_redirect">
				<?php 
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_redirect_url,
        esc_html__( 'Ending Redirect URL', 'webinarignition' ),
        'auto_redirect_url',
        esc_html__( 'This is the URL you want them to go to when the webinar is over...', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/order-now', 'webinarignition' )
    );
    ?>
			</div>
			<div class="auto_redirect auto_redirect_redirect_visible">
				<?php 
    webinarignition_display_number_field(
        $input_get['id'],
        ( isset( $webinar_data->auto_redirect_delay ) ? absint( $webinar_data->auto_redirect_delay ) : 0 ),
        esc_html__( 'Ending Redirect Delay', 'webinarignition' ),
        'auto_redirect_delay',
        esc_html__( 'Set the delay time (in seconds) before redirection, defaults to "0" seconds which means no delay', 'webinarignition' ),
        esc_html__( 'Ex. 60', 'webinarignition' ),
        0,
        '',
        5
    );
    ?>
			</div>
		</div>

		<div class="too_late_lockout_container"<?php 
    echo ( $is_too_late_lockout_enabled ? '' : ' style="display:none;"' );
    ?>>
			<?php 
    webinarignition_display_option(
        $input_get['id'],
        ( isset( $webinar_data->too_late_lockout ) ? $webinar_data->too_late_lockout : '' ),
        esc_html__( 'Too-Late Lockout', 'webinarignition' ),
        'too_late_lockout',
        esc_html__( 'You can choose to lock out/redirect latecomers', 'webinarignition' ),
        esc_html__( 'Disable Too-Late Lockout', 'webinarignition' ) . ' [hide], ' . esc_html__( 'Enable Too-Late Lockout', 'webinarignition' ) . ' [show]'
    );
    ?>
			<div class="too_late_lockout" id="too_late_lockout_show">
				<?php 
    $too_late_lockout_minutes = ( isset( $webinar_data->too_late_lockout_minutes ) ? $webinar_data->too_late_lockout_minutes : '' );
    webinarignition_display_number_field(
        $input_get['id'],
        $too_late_lockout_minutes,
        esc_html__( 'Number Of Minutes After Which Latecomer Should Be Locked Out', 'webinarignition' ),
        'too_late_lockout_minutes',
        esc_html__( 'This is the number of minutes after which user will be locked out.', 'webinarignition' ),
        esc_html__( 'Ex. 10', 'webinarignition' )
    );
    $latecomer_redirection_type = ( isset( $webinar_data->latecomer_redirection_type ) ? $webinar_data->latecomer_redirection_type : 'registration_page' );
    webinarignition_display_option(
        $input_get['id'],
        $latecomer_redirection_type,
        esc_html__( 'Toggle Latecomer Redirection Type', 'webinarignition' ),
        'latecomer_redirection_type',
        esc_html__( 'You can choose whether to redirect latecomers to a URL or to the Registration page.', 'webinarignition' ),
        esc_html__( 'URL', 'webinarignition' ) . ' [url], ' . esc_html__( 'Registration Page', 'webinarignition' ) . ' [registration_page]'
    );
    ?>

				<div class="latecomer_redirection_type" id="latecomer_redirection_type_url">
					<?php 
    $too_late_redirect_url = ( isset( $webinar_data->too_late_redirect_url ) ? $webinar_data->too_late_redirect_url : get_home_url() );
    webinarignition_display_field(
        $input_get['id'],
        $too_late_redirect_url,
        esc_html__( 'Latecomer Redirect URL', 'webinarignition' ),
        'too_late_redirect_url',
        esc_html__( 'This is the URL you want them to go to when they come late...', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/latecomer', 'webinarignition' )
    );
    ?>
				</div>


				<div class="latecomer_redirection_type" id="latecomer_redirection_type_registration_page">
					<?php 
    $latecomer_registration_copy = ( isset( $webinar_data->latecomer_registration_copy ) ? $webinar_data->latecomer_registration_copy : '' );
    webinarignition_display_wpeditor_media(
        $input_get['id'],
        $latecomer_registration_copy,
        esc_html__( 'Registration Page Text', 'webinarignition' ),
        'latecomer_registration_copy',
        esc_html__( 'If you choose to redirect users to the registration page, you can choose to add some text giving users more information...', 'webinarignition' )
    );
    ?>
				</div>
			</div>
		</div>
	</div>
	<?php 
} else {
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?><div style="display: none;"><?php 
    }
    webinarignition_display_edit_toggle(
        'money',
        esc_html__( 'Live Webinar Actions', 'webinarignition' ),
        'we_edit_auto_actions',
        esc_html__( 'Settings for timed actions, ending redirect and CTA popup...', 'webinarignition' )
    );
    ?>

	<div id="we_edit_auto_actions" class="we_edit_area">
		<?php 
    webinarignition_display_time_tags_section( $webinar_data );
    ?>

		<div class="too_late_lockout_container"<?php 
    echo ( $is_too_late_lockout_enabled ? '' : ' style="display:none;"' );
    ?>>
			<?php 
    webinarignition_display_option(
        $input_get['id'],
        ( isset( $webinar_data->too_late_lockout ) ? $webinar_data->too_late_lockout : '' ),
        esc_html__( 'Too-Late Lockout', 'webinarignition' ),
        'too_late_lockout',
        esc_html__( 'You can choose to lock out/redirect latecomers', 'webinarignition' ),
        esc_html__( 'Disable Too-Late Lockout', 'webinarignition' ) . ' [false], ' . esc_html__( 'Enable Too-Late Lockout', 'webinarignition' ) . ' [show]'
    );
    ?>
			<div class="too_late_lockout" id="too_late_lockout_show">
				<?php 
    webinarignition_display_number_field(
        $input_get['id'],
        ( isset( $webinar_data->too_late_lockout_minutes ) ? $webinar_data->too_late_lockout_minutes : '' ),
        esc_html__( 'Number Of Minutes After Which Latecomer Should Be Locked Out', 'webinarignition' ),
        'too_late_lockout_minutes',
        esc_html__( 'This is the number of minutes after which user will be locked out.', 'webinarignition' ),
        esc_html__( 'Ex. 10', 'webinarignition' )
    );
    webinarignition_display_option(
        $input_get['id'],
        ( isset( $webinar_data->latecomer_redirection_type ) ? $webinar_data->latecomer_redirection_type : '' ),
        esc_html__( 'Toggle Latecomer Redirection Type', 'webinarignition' ),
        'latecomer_redirection_type',
        esc_html__( 'You can choose whether to redirect latecomers to a URL or to the Registration page.', 'webinarignition' ),
        esc_html__( 'URL', 'webinarignition' ) . ' [url], ' . esc_html__( 'Registration Page', 'webinarignition' ) . ' [registration_page]'
    );
    ?>

				<div class="latecomer_redirection_type" id="latecomer_redirection_type_url">
					<?php 
    webinarignition_display_field(
        $input_get['id'],
        ( isset( $webinar_data->too_late_redirect_url ) ? $webinar_data->too_late_redirect_url : '' ),
        esc_html__( 'Latecomer Redirect URL', 'webinarignition' ),
        'too_late_redirect_url',
        esc_html__( 'This is the URL you want them to go to when they come late...', 'webinarignition' ),
        esc_html__( 'Ex. http://yoursite.com/latecomer', 'webinarignition' )
    );
    ?>
				</div>


				<div class="latecomer_redirection_type" id="latecomer_redirection_type_registration_page">
					<?php 
    webinarignition_display_wpeditor_media(
        $input_get['id'],
        ( isset( $webinar_data->latecomer_registration_copy ) ? $webinar_data->latecomer_registration_copy : '' ),
        esc_html__( 'Registration Page Text', 'webinarignition' ),
        'latecomer_registration_copy',
        esc_html__( 'If you choose to redirect users to the registration page, you can choose to add some text giving users more information...', 'webinarignition' )
    );
    ?>
				</div>
			</div>
		</div>
	</div>
	<?php 
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
        ?></div><?php 
    }
}
//end if
?>
	<div id="we_edit_webinar_design_section" class="section-visible-for-webinar-classic"<?php 
echo ( 'classic' !== $webinar_template ? ' style="display:none;"' : '' );
?>>
	<?php 
webinarignition_display_edit_toggle(
    'picture',
    esc_html__( 'Webinar Banner Settings', 'webinarignition' ),
    'we_edit_webinar_design',
    esc_html__( 'Design settings for the top banner area...', 'webinarignition' )
);
?>

<div id="we_edit_webinar_design" class="we_edit_area">
	<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->webinar_banner_bg_style,
    esc_html__( 'Banner Background Style', 'webinarignition' ),
    'webinar_banner_bg_style',
    esc_html__( 'You can choose between a simple background color, or to have a background iamge (repeating horiztonally)', 'webinarignition' ),
    esc_html__( 'Show Banner Area', 'webinarignition' ) . ' [show], ' . esc_html__( 'Hide Banner Area', 'webinarignition' ) . ' [hide]'
);
?>
	<div class="webinar_banner_bg_style" id="webinar_banner_bg_style_show">
		<?php 
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_banner_bg_color,
    esc_html__( 'Banner Background Color', 'webinarignition' ),
    'webinar_banner_bg_color',
    esc_html__( 'Choose a color for the top banner area, this will fill the entire top banner area...', 'webinarignition' ),
    '#FFFFFF'
);
?>
		<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->webinar_banner_bg_repeater,
    esc_html__( 'Banner Repeating BG Image', 'webinarignition' ),
    'webinar_banner_bg_repeater',
    esc_html__( 'This is the image that is repeated horiztonally in the background of the banner area... If you leave this blank, it will just show the banner BG color... For the best results, use: 89px high..', 'webinarignition' ),
    esc_html__( 'http://yoursite.com/banner-bg.png', 'webinarignition' )
);
?>
		<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->webinar_banner_image,
    esc_html__( 'Banner Image URL:', 'webinarignition' ),
    'webinar_banner_image',
    __( 'This is the URL for the banner image you want to be shown. By defualt it is placed in the middle, perfect for a logo... <br><br><b>best results:</b> 89px high and 960px wide...', 'webinarignition' ),
    'http://yoursite.com/banner-image.png'
);
webinarignition_display_info( esc_html__( 'Note: Banner Image Sizing', 'webinarignition' ), esc_html__( 'The background image (repeating) and the banner image should have the size height, so it looks good on the site, any size will work, but best is around 89px high...', 'webinarignition' ) );
?>
	</div>
</div>
	</div>
<?php 
webinarignition_display_edit_toggle(
    'magic',
    esc_html__( 'Webinar Background Settings', 'webinarignition' ),
    'we_edit_webinar_bg',
    esc_html__( 'Design settings for the background area...', 'webinarignition' )
);
?>

<div id="we_edit_webinar_bg" class="we_edit_area">
	<div id="we_edit_webinar_tabs_wrapper" class="section-visible-for-webinar-classic"<?php 
echo ( 'classic' !== $webinar_template ? ' style="display:none;"' : '' );
?>>
	<?php 
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_background_color,
    esc_html__( 'Background Color', 'webinarignition' ),
    'webinar_background_color',
    esc_html__( 'This is the color for the main section, this fills the entire webinar page area...', 'webinarignition' ),
    '#DDDDDD'
);
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->webinar_background_image,
    esc_html__( 'Repeating Background Image URL', 'webinarignition' ),
    'webinar_background_image',
    esc_html__( 'You can have a repeating image to be shown as the background to add some flare to your webinar page...', 'webinarignition' ),
    esc_html__( 'http://yoursite.com/background-image.png', 'webinarignition' )
);
webinarignition_display_info( esc_html__( 'Note: Background Image', 'webinarignition' ), esc_html__( 'If this is left blank, no background image will be shown...', 'webinarignition' ) );
?>
	</div>

	<div id="we_edit_webinar_tabs_wrapper" class="section-visible-for-webinar-modern"<?php 
echo ( 'modern' !== $webinar_template ? ' style="display:none;"' : '' );
?>>
		<?php 
$webinar_modern_background_color = ( !empty( $webinar_data->webinar_modern_background_color ) ? $webinar_data->webinar_modern_background_color : '#ced4da' );
webinarignition_display_color(
    $input_get['id'],
    $webinar_modern_background_color,
    esc_html__( 'Header / Footer Background Color', 'webinarignition' ),
    'webinar_modern_background_color',
    esc_html__( 'This is the color for the header and footer of modern webinar page template...', 'webinarignition' ),
    '#DDDDDD'
);
?>
	</div>
</div>

<?php 
webinarignition_display_edit_toggle(
    'comments',
    esc_html__( 'Question / Answer Area', 'webinarignition' ),
    'we_edit_webinar_qa',
    esc_html__( 'Settings for your question system - built-in or 3rd party integration...', 'webinarignition' )
);
?>

<div id="we_edit_webinar_qa" class="we_edit_area">
	<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'webinar_qa',
    $webinar_data,
    true,
    true
);
?>
	<?php 
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->webinar_qa_title,
    esc_html__( 'Q / A Headline Copy', 'webinarignition' ),
    'webinar_qa_title',
    esc_html__( 'This is the copy shown above the QA System (under the webinar video)', 'webinarignition' )
);
if ( WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
    $webinar_qa_type = ( !empty( $webinar_data->webinar_qa ) ? $webinar_data->webinar_qa : 'chat' );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->webinar_qa,
        esc_html__( 'Q / A Type', 'webinarignition' ),
        'webinar_qa',
        esc_html__( 'You can either choose from our built-in Email Q&A (answers will be sent to attendee via email) or Chat Q&A System (answers will be sent to attendee immediately in chat window, additionally can be sent via email), or use a 3rd party service...', 'webinarignition' ),
        esc_html__( 'Chat Q&A', 'webinarignition' ) . ' [chat], ' . esc_html__( 'Email Q&A', 'webinarignition' ) . ' [we], ' . esc_html__( '3rd Party Service', 'webinarignition' ) . ' [custom],' . esc_html__( 'Hide Q/A', 'webinarignition' ) . ' [hide]'
    );
} else {
    webinarignition_display_option(
        $input_get['id'],
        ( empty( $webinar_data->webinar_qa ) || 'chat' === $webinar_data->webinar_qa ? 'we' : $webinar_data->webinar_qa ),
        esc_html__( 'Q / A Type', 'webinarignition' ),
        'webinar_qa',
        esc_html__( 'You can either choose from our built-in simple Q/A System, or use a 3rd party service...', 'webinarignition' ),
        esc_html__( 'Simple Q/A', 'webinarignition' ) . ' [we], ' . esc_html__( '3rd Party Service', 'webinarignition' ) . ' [custom],' . esc_html__( 'Hide Q/A', 'webinarignition' ) . ' [hide]'
    );
}
?>

	<div class="webinar_qa webinar_qa_we_visible">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_qa_name_placeholder,
    esc_html__( 'Name Field Placeholder', 'webinarignition' ),
    'webinar_qa_name_placeholder',
    esc_html__( 'This is the placeholder copy for the name field on the Q / A system...', 'webinarignition' ),
    esc_html__( 'Ex. Your Full Name...', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_qa_email_placeholder,
    esc_html__( 'Email Field Placeholder', 'webinarignition' ),
    'webinar_qa_email_placeholder',
    esc_html__( 'This is the placeholder copy for the email field on the Q / A system...', 'webinarignition' ),
    esc_html__( 'Ex. Your Email Address...', 'webinarignition' )
);
webinarignition_display_option(
    $input_get['id'],
    ( !empty( $webinar_data->webinar_qa_edit_name_email ) ? $webinar_data->webinar_qa_edit_name_email : 'forbid' ),
    esc_html__( 'Allow update name and email', 'webinarignition' ),
    'webinar_qa_edit_name_email',
    esc_html__( 'Choose if visitor can change name or email in Q&A section.', 'webinarignition' ),
    esc_html__( 'Allow', 'webinarignition' ) . ' [allow], ' . esc_html__( 'Forbid', 'webinarignition' ) . ' [forbid]'
);
?>
	</div>

	<div class="webinar_qa webinar_qa_chat_visible">
		<?php 
webinarignition_display_number_field(
    $input_get['id'],
    ( !empty( $webinar_data->webinar_qa_chat_refresh ) ? $webinar_data->webinar_qa_chat_refresh : 2 ),
    esc_html__( 'QA Chat Refresh Period (seconds)', 'webinarignition' ),
    'webinar_qa_chat_refresh',
    esc_html__( 'Setup period for checking new answers in seconds.', 'webinarignition' ),
    esc_html__( 'Ex. 30', 'webinarignition' ),
    1,
    '',
    1
);
?>
	</div>

	<div class="webinar_qa webinar_qa_we_visible webinar_qa_chat_visible">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_qa_desc_placeholder,
    esc_html__( 'Question Field Placeholder', 'webinarignition' ),
    'webinar_qa_desc_placeholder',
    esc_html__( 'This is the placeholder copy for the question field on the Q / A system...', 'webinarignition' ),
    esc_html__( 'Ex. Ask Your Question Here...', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_qa_button,
    esc_html__( 'Submit Question Button Copy', 'webinarignition' ),
    'webinar_qa_button',
    esc_html__( 'This is the copy that is shown on the button to submit the question', 'webinarignition' ),
    esc_html__( 'Ex. Submit Your Question', 'webinarignition' )
);
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_qa_button_color,
    esc_html__( 'Button Color', 'webinarignition' ),
    'webinar_qa_button_color',
    esc_html__( 'This is the color of the button for submitting a question', 'webinarignition' ),
    esc_html__( 'Ex. #000000', 'webinarignition' )
);
?>
	</div>

	<div class="webinar_qa webinar_qa_chat_visible">
		<?php 
$webinar_qa_chat_question_color = ( !empty( $webinar_data->webinar_qa_chat_question_color ) ? $webinar_data->webinar_qa_chat_question_color : '' );
$webinar_qa_chat_answer_color = ( !empty( $webinar_data->webinar_qa_chat_answer_color ) ? $webinar_data->webinar_qa_chat_answer_color : '' );
webinarignition_display_color(
    $input_get['id'],
    $webinar_qa_chat_question_color,
    esc_html__( 'Question Bubbles Color', 'webinarignition' ),
    'webinar_qa_chat_question_color',
    '',
    esc_html__( 'Ex. #000000', 'webinarignition' )
);
webinarignition_display_color(
    $input_get['id'],
    ( isset( $webinar_data->webinar_qa_chat_answer_color ) ? $webinar_data->webinar_qa_chat_answer_color : '' ),
    esc_html__( 'Answer Bubbles Color', 'webinarignition' ),
    'webinar_qa_chat_answer_color',
    '',
    esc_html__( 'Ex. #000000', 'webinarignition' )
);
?>
	</div>

	<div class="webinar_qa webinar_qa_we_visible">
		<?php 
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->webinar_qa_thankyou,
    esc_html__( 'Thank You Copy', 'webinarignition' ),
    'webinar_qa_thankyou',
    esc_html__( 'This is the copy that is shown when they submit a question, shows for a 20 seconds, then QA re apears..', 'webinarignition' )
);
?>
	</div>

	<div class="webinar_qa" id="webinar_qa_custom">
		<?php 
webinarignition_display_textarea(
    $input_get['id'],
    $webinar_data->webinar_qa_custom,
    esc_html__( 'Q / A Custom Code', 'webinarignition' ),
    'webinar_qa_custom',
    esc_html__( 'This is the code for the live chat / QA system you want to use, this code should be provided to you by the 3rd party service...', 'webinarignition' ),
    esc_html__( 'Live chat code...', 'webinarignition' )
);
?>
	</div>
</div>

<div id="we_edit_webinar_speaker_wrapper" class="section-visible-for-webinar-classic"<?php 
echo ( 'classic' !== $webinar_template ? ' style="display:none;"' : '' );
?>>
	<?php 
webinarignition_display_edit_toggle(
    'volume-up',
    esc_html__( 'Turn Up Speakers Copy', 'webinarignition' ),
    'we_edit_webinar_speaker',
    esc_html__( 'Copy / Settings for the turn up speakers copy...', 'webinarignition' )
);
?>

	<div id="we_edit_webinar_speaker" class="we_edit_area">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_speaker,
    esc_html__( 'Turn Up Speakers Copy', 'webinarignition' ),
    'webinar_speaker',
    esc_html__( 'This is the copy shown at the top of the webinar reminding viewers to turn up their speakers...', 'webinarignition' ),
    esc_html__( 'Ex. Turn Up Your Speakers...', 'webinarignition' )
);
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_speaker_color,
    esc_html__( 'Turn Up Speakers Copy Color', 'webinarignition' ),
    'webinar_speaker_color',
    esc_html__( 'This is the color of the copy fo the turn up speaker...', 'webinarignition' ),
    esc_html__( 'Ex. #000000', 'webinarignition' )
);
?>
	</div>
</div>
<?php 
// webinarignition_display_edit_toggle(
// 	'user',
// 	esc_html__( 'Invite Friends To Webinar', 'webinarignition' ),
// 	'we_edit_webinar_social',
// 	esc_html__( 'Copy / Settings for inviting friends into the webinar...', 'webinarignition' )
// );
?>

<!-- <div id="we_edit_webinar_social" class="we_edit_area">
	<?php 
// webinarignition_display_option(
// $input_get['id'],
// $webinar_data->social_share_links,
// esc_html__( 'Enable / Disable Social Share Links', 'webinarignition' ),
// 'social_share_links',
// esc_html__( 'You can enable or disable the social share links.', 'webinarignition' ),
// esc_html__( 'Disable', 'webinarignition' ) . ' [disabled], ' . esc_html__( 'Enable', 'webinarignition' ) . ' [enabled]'
// );
?>

	<div class="social_share_links" id="social_share_links_enabled">
		<?php 
// webinarignition_display_field(
// $input_get['id'],
// $webinar_data->webinar_invite,
// esc_html__( 'Invite Headline', 'webinarignition' ),
// 'webinar_invite',
// esc_html__( 'This is the copy the copy shown above the webinar video to invite friends to the webinar (Facebook & Twitter)...', 'webinarignition' ),
// esc_html__( 'Ex. Invite Your Friends To The Webinar', 'webinarignition' )
// );
// webinarignition_display_color(
// $input_get['id'],
// $webinar_data->webinar_invite_color,
// esc_html__( 'Invite Headline Color', 'webinarignition' ),
// 'webinar_invite_color',
// esc_html__( 'This is the color of the copy fo the invite headline...', 'webinarignition' ),
// esc_html__( 'Ex. #000000', 'webinarignition' )
// );
// webinarignition_display_option(
// $input_get['id'],
// $webinar_data->webinar_tw_share,
// esc_html__( 'Twitter Share', 'webinarignition' ),
// 'webinar_tw_share',
// esc_html__( 'You can turn on or off the Twiter like area...', 'webinarignition' ),
// esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
// );
// webinarignition_display_option(
// $input_get['id'],
// $webinar_data->webinar_ld_share,
// esc_html__( 'LinkedIn Share', 'webinarignition' ),
// 'webinar_ld_share',
// esc_html__( 'You can turn on or off the LinkedIn like area...', 'webinarignition' ),
// esc_html__( 'Enable', 'webinarignition' ) . ' [on], ' . esc_html__( 'Disable', 'webinarignition' ) . ' [off]'
// );
// webinarignition_display_info(
// esc_html__( 'Note: Social Share Messages', 'webinarignition' ),
// esc_html__( 'The share social messages for the Twitter and Facebook are taken from the webinar event info; Title & Description...', 'webinarignition' )
//);
?>
	</div>

</div> -->

<?php 
// webinarignition_display_edit_toggle(
// "phone",
// "Call In Number",
// "we_edit_webinar_callin",
// "Copy / Settings for the call in number (can be replaced for something else)"
// );
?>

<div id="we_edit_webinar_callin" class="we_edit_area">
	<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->webinar_callin,
    esc_html__( 'Webinar Call In Number', 'webinarignition' ),
    'webinar_callin',
    esc_html__( 'You can hide or show the call in number if you have a number for viewers to call in and ask questions... ', 'webinarignition' ),
    esc_html__( 'Enable Call Number', 'webinarignition' ) . ' [show], ' . esc_html__( 'Disable Call Number', 'webinarignition' ) . ' [hide]'
);
?>
	<div class="webinar_callin" id="webinar_callin_show">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_callin_copy,
    esc_html__( 'Call In Copy', 'webinarignition' ),
    'webinar_callin_copy',
    esc_html__( 'This is the copy that is shown next to the call number...', 'webinarignition' ),
    esc_html__( 'Ex. To Join Call:', 'webinarignition' )
);
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_callin_color,
    esc_html__( 'Call In Phone Copy Color', 'webinarignition' ),
    'webinar_callin_color',
    esc_html__( 'This is the color of the copy fo the Call In Phone headline...', 'webinarignition' ),
    esc_html__( 'Ex. #000000', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_callin_number,
    esc_html__( 'Call In Phone Number', 'webinarignition' ),
    'webinar_callin_number',
    esc_html__( 'This is the actual number they would need to call to join in on the live call...', 'webinarignition' ),
    esc_html__( 'Ex. 1-555-555-5555', 'webinarignition' )
);
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_callin_color2,
    esc_html__( 'Phone Number Color', 'webinarignition' ),
    'webinar_callin_color2',
    esc_html__( 'This is the color of the copy fo the Phone number...', 'webinarignition' ),
    esc_html__( 'Ex. #000000', 'webinarignition' )
);
webinarignition_display_info( esc_html__( 'Note: Call Number', 'webinarignition' ), __( "Need a phone number for a conference call? Try <a href='http://freeconferencing.com/ ' target='_blank' >Free Conferencing</a>...", 'webinarignition' ) );
?>
	</div>
</div>

<?php 
// webinarignition_display_edit_toggle(
// "microphone",
// "Live Copy",
// "we_edit_webinar_live",
// "Copy / Settings for the 'we are live' text under the live video..."
// );
?>

<div id="we_edit_webinar_live" class="we_edit_area">
	<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_live,
    esc_html__( 'Live Webinar Copy', 'webinarignition' ),
    'webinar_live',
    esc_html__( 'This is the copy shown under the video in green to show people the webinar is live...', 'webinarignition' ),
    esc_html__( 'Ex. Webinar Is Live', 'webinarignition' )
);
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_live_color,
    esc_html__( 'Live Webinar Color', 'webinarignition' ),
    'webinar_live_color',
    esc_html__( 'This is the color of the copy...', 'webinarignition' ),
    '#000000'
);
?>
</div>

<?php 
webinarignition_display_edit_toggle(
    'gift',
    esc_html__( 'Live Give Away', 'webinarignition' ),
    'we_edit_webinar_giveaway',
    esc_html__( 'Copy / Settings for the give away block... (not required)', 'webinarignition' )
);
?>

<div id="we_edit_webinar_giveaway" class="we_edit_area">
	<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'webinar_giveaway',
    $webinar_data,
    true,
    true
);
?>
	<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->webinar_giveaway_toggle,
    esc_html__( 'Toggle Webinar Giveaway', 'webinarignition' ),
    'webinar_giveaway_toggle',
    esc_html__( 'You can hide or show the free give away block on the webinar page...', 'webinarignition' ),
    esc_html__( 'Show Giveaway Block', 'webinarignition' ) . ' [show], ' . esc_html__( 'Hide Giveaway Block', 'webinarignition' ) . ' [hide]'
);
?>
	<div class="webinar_giveaway_toggle" id="webinar_giveaway_toggle_show">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->webinar_giveaway_title,
    esc_html__( 'Give Away Block Title', 'webinarignition' ),
    'webinar_giveaway_title',
    esc_html__( 'This is the title for the give away block...', 'webinarignition' ),
    esc_html__( 'Ex. Thank You Gift:', 'webinarignition' )
);
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->webinar_giveaway,
    esc_html__( 'Give Away Copy', 'webinarignition' ),
    'webinar_giveaway',
    esc_html__( 'Copy for the give away, can anything you want here...', 'webinarignition' )
);
webinarignition_display_info( esc_html__( 'Note: Give Away', 'webinarignition' ), esc_html__( 'Giving people a gift for coming to the webinar is a great way to get people to join the Webinar. You can give away a report, a checklist, or something else of great value...', 'webinarignition' ) );
?>

	</div>

</div>

<?php 
if ( !WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ) {
    ?>
<div style="display: none;">
<?php 
} else {
    ?>
<div id="we_edit_webinar_tabs_wrapper" class="section-visible-for-webinar-modern"<?php 
    echo ( 'modern' !== $webinar_template ? ' style="display:none;"' : '' );
    ?>>
<?php 
}
?>

	<?php 
webinarignition_display_edit_toggle(
    'bookmark',
    esc_html__( 'Modern webinar tabs', 'webinarignition' ),
    'we_edit_webinar_tabs',
    esc_html__( 'Setup tabs in side area on modern webinar template', 'webinarignition' )
);
?>

	<div id="we_edit_webinar_tabs" class="we_edit_area">
		<?php 
webinarignition_display_webinar_tabs_section( $webinar_data );
?>

		<?php 
webinarignition_display_info( esc_html__( 'Note: How to hide sidebar', 'webinarignition' ), esc_html__( 'To hide sidebar and only show only when show CTAs, disable Q&A and Giveaway in their settings.', 'webinarignition' ) );
?>
	</div>
</div>

<div class="bottomSaveArea">
	<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php 
esc_html_e( 'Save & Update', 'webinarignition' );
?> </a>
</div>

</div>
