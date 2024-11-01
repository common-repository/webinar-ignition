<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
?>
<div class="tabber" id="tab6" style="display: none;">

    <div class="titleBar">
        <h2><?php 
esc_html_e( 'Extra Settings:', 'webinarignition' );
?></h2>

        <p><?php 
esc_html_e( 'Here you can add extra code in the footer and custom JS/CSS...', 'webinarignition' );
?></p>
    </div>
    <?php 
$input_get = array(
    'id' => filter_input( INPUT_GET, 'id', FILTER_UNSAFE_RAW ),
);
?>
    <?php 
webinarignition_display_edit_toggle(
    'edit-sign',
    esc_html__( 'Protected access', 'webinarignition' ),
    'we_edit_protected_settings',
    esc_html__( 'Protected access settings', 'webinarignition' )
);
?>

<div id="we_edit_protected_settings" class="we_edit_area">
	<?php 
if ( !WebinarignitionPowerups::webinarignition_is_secure_access_enabled( $webinar_data ) ) {
    ?>
		<div style="display: none">
		<?php 
}
webinarignition_display_field(
    $input_get['id'],
    ( !empty( $webinar_data->secure_access_webinar_blacklisted ) ? $webinar_data->secure_access_webinar_blacklisted : '' ),
    esc_html__( 'Blacklist', 'webinarignition' ),
    'secure_access_webinar_blacklisted',
    esc_html__( 'Enter the Domains that you DO NOT want to have access to the event. Domains should be comma separated.', 'webinarignition' ),
    esc_html__( 'Ex. domain-black-listed-1.com, domain-black-listed-2.com, domain-black-listed-3.com', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    ( !empty( $webinar_data->secure_access_webinar_whitelisted ) ? $webinar_data->secure_access_webinar_whitelisted : '' ),
    esc_html__( 'Whitelist', 'webinarignition' ),
    'secure_access_webinar_whitelisted',
    esc_html__( 'Enter the Domains that you DO want to have access to the event. Domains should be comma separated.', 'webinarignition' ),
    esc_html__( 'Ex. domain-white-listed-1.com, domain-white-listed-2.com, domain-white-listed-3.com', 'webinarignition' )
);
webinarignition_display_info( esc_html__( 'Tip: Black / White Lists', 'webinarignition' ), esc_html__( 'Black and white lists allow you to control who can or who can not register and visit your event.', 'webinarignition' ) );
if ( !WebinarignitionPowerups::webinarignition_is_secure_access_enabled( $webinar_data ) ) {
    ?>
		</div>
		<?php 
}
webinarignition_display_option(
    $input_get['id'],
    ( !empty( $webinar_data->protected_webinar_id ) ? trim( $webinar_data->protected_webinar_id ) : 'public' ),
    esc_html__( 'Protected webinar ID', 'webinarignition' ),
    'protected_webinar_id',
    esc_html__( 'Choose if webinar should be available only by encoded webinar ID, so no can enter webinar without that ID', 'webinarignition' ),
    esc_html__( 'Protected', 'webinarignition' ) . ' [protected], ' . esc_html__( 'Public', 'webinarignition' ) . ' [public]'
);
webinarignition_display_option(
    $input_get['id'],
    ( !empty( $webinar_data->email_verification ) ? trim( $webinar_data->email_verification ) : 'global' ),
    esc_html__( 'Email verification', 'webinarignition' ),
    'email_verification',
    esc_html__( 'Choose if you want to verify email on registration page for current webinar. You can use general settings, or enable / disable only for this specific webinar.', 'webinarignition' ),
    esc_html__( 'General Settings', 'webinarignition' ) . ' [global],' . esc_html__( 'Disabled', 'webinarignition' ) . ' [no], ' . esc_html__( 'Enabled', 'webinarignition' ) . ' [yes]'
);
webinarignition_display_option(
    $input_get['id'],
    ( !empty( $webinar_data->protected_lead_id ) ? $webinar_data->protected_lead_id : 'public' ),
    esc_html__( 'Protected lead ID', 'webinarignition' ),
    'protected_lead_id',
    esc_html__( 'Choose if webinar should be available only by encoded lead ID, so no can enter webinar without that ID', 'webinarignition' ),
    esc_html__( 'Protected', 'webinarignition' ) . ' [protected], ' . esc_html__( 'Public', 'webinarignition' ) . ' [public]'
);
webinarignition_display_field(
    $input_get['id'],
    ( !empty( $webinar_data->protected_webinar_redirection ) ? $webinar_data->protected_webinar_redirection : '' ),
    esc_html__( 'Redirection Page URL', 'webinarignition' ),
    'protected_webinar_redirection',
    esc_html__( 'Set up an URL where visitor should be redirected if they try to visit protected webinar or lead id with public ids. By defaulr they will be redirected to home page.', 'webinarignition' ),
    esc_html__( 'Ex. http://yoursite.com/register-for-webinar/', 'webinarignition' )
);
webinarignition_display_option(
    $input_get['id'],
    ( !empty( $webinar_data->limit_lead_visit ) ? $webinar_data->limit_lead_visit : 'disabled' ),
    esc_html__( 'Single Lead', 'webinarignition' ),
    'limit_lead_visit',
    esc_html__( 'Choose if webinar visit allowed only from one device, it will protect your webinar from sharing links. In this case tracking is enabled and it could affect your server performance with a lot of visitors.', 'webinarignition' ),
    esc_html__( 'Enabled', 'webinarignition' ) . ' [enabled], ' . esc_html__( 'Disabled', 'webinarignition' ) . ' [disabled]'
);
webinarignition_display_number_field(
    $input_get['id'],
    ( !empty( $webinar_data->limit_lead_timer ) ? $webinar_data->limit_lead_timer : '30' ),
    esc_html__( 'Timeout before redirect', 'webinarignition' ),
    'limit_lead_timer',
    esc_html__( 'Setup timer (seconds) before user will be redirected to registration page. Minimum value should be 10 seconds', 'webinarignition' ),
    esc_html__( 'Ex. 30', 'webinarignition' ),
    10,
    '',
    5
);
?>
</div>
<?php 
webinarignition_display_edit_toggle(
    'edit-sign',
    esc_html__( 'Time & Date', 'webinarignition' ),
    'we_edit_time_date',
    esc_html__( 'Time & Date settings', 'webinarignition' )
);
?>

<div id="we_edit_time_date" class="we_edit_area">
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy"><?php 
echo esc_html__( 'Date Format', 'webinarignition' );
?></div>
			<div class="inputTitleHelp"><?php 
echo esc_html__( 'Choose date format', 'webinarignition' );
?></div>
		</div>
		<div class="inputSection dateTime" style="padding-top:20px; padding-bottom: 30px;">
			<?php 
$date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format', 'F j, Y' ) );
$webinar_locale = ( isset( $webinar_data->webinar_lang ) && !empty( $webinar_data->webinar_lang ) ? $webinar_data->webinar_lang : determine_locale() );
$webinar_locale = 'en_US';
$webinar_record = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $input_get['id'] );
$date_formats = array(
    esc_html( 'F j, Y' ),
    'Y-m-d',
    'm/d/Y',
    'd/m/Y'
);
$custom = true;
$documentation_link_str = esc_html__( 'Documentation on date and time formatting', 'webinarignition' );
$add_language_link_str = esc_html__( 'Want to add a language?', 'webinarignition' );
$default_date_format = $date_formats[0];
foreach ( $date_formats as $format ) {
    echo "\t<label><input type='radio' name='date_format' value='" . esc_attr( $format ) . "'";
    if ( $date_format === $format ) {
        echo " checked='checked'";
        $custom = false;
    }
    echo ' /> <span class="date-time-text format-i18n">' . esc_html( date_i18n( $format ) ) . '</span><code>' . esc_html( $format ) . "</code></label><br/><br/>\n";
}
?>
			<?php 
$wi_show_day = 0;
if ( 'AUTO' === $webinar_data->webinar_date ) {
    if ( isset( $webinar_data->wi_show_day ) && !empty( $webinar_data->wi_show_day ) ) {
        $wi_show_day = $webinar_data->wi_show_day;
    }
    $day_string = 'D';
    if ( isset( $webinar_data->day_string ) && !empty( $webinar_data->day_string ) ) {
        $day_string = $webinar_data->day_string;
    }
    ?>
			<input type="hidden" id="applang" value="<?php 
    echo esc_attr( $webinar_locale );
    ?>" />
			<input type="hidden" id="apptz" value="<?php 
    echo esc_attr( wp_timezone_string() );
    ?>" />
			<div id="wi_show_day_wrap">
				<label>
					<input name="wi_show_day" type="checkbox" <?php 
    checked( 1 === $wi_show_day, true, true );
    ?>><span style="margin-left: 15px;"><?php 
    esc_html_e( 'Show Day', 'webinarignition' );
    ?></span> (<code id="wi_day_string"><?php 
    echo esc_attr( date_i18n( $day_string ) );
    ?></code>)
				</label>
				<div id="wi_day_string_input" style="margin-left: 35px; display: inline-flex;">
					<label style="text-align: right; min-width: 75px;"><input type="radio" name="day_string" value="D" data-string="<?php 
    echo esc_attr( date_i18n( 'D' ) );
    ?>" <?php 
    checked( 'D' === $day_string, true, true );
    ?>> <?php 
    esc_html_e( 'Short', 'webinarignition' );
    ?></label>
					<label style="text-align: right; min-width: 75px;"><input type="radio" name="day_string" value="l" data-string="<?php 
    echo esc_attr( date_i18n( 'l' ) );
    ?>" <?php 
    checked( 'l' === $day_string, true, true );
    ?>> <?php 
    esc_html_e( 'Long', 'webinarignition' );
    ?></label>
				</div>
			</div>
			<br/>
			<?php 
}
?>
			<?php 
echo '<label><input type="radio" name="date_format" id="date_format_custom_radio" value="' . esc_html( $date_format ) . '"';
checked( $custom );
echo '/> <span class="date-time-text date-time-custom-text">' . esc_html__( 'Custom:', 'webinarignition' ) . '</span><input type="text" name="date_format_custom" id="date_format_custom" value="' . esc_attr( $date_format ) . '" class="float-right small-text" /></label>' . '<br/><br/>' . '<p><strong>' . esc_html__( 'Preview:', 'webinarignition' ) . '</strong> <span id="date_format_preview" class="formatPreview">' . esc_attr( date_i18n( $date_format ) ) . '</span>' . "<span class='spinner'></span>\n" . '</p>';
echo "\t<p class='date-time-doc'>" . '<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank" title="' . esc_attr( $documentation_link_str ) . '">' . esc_attr( $documentation_link_str ) . '</a>' . "</p>\n";
echo "\t<p class='date-time-doc'>" . '<a href="https://webinarignition.tawk.help/article/add-language-to-webinarignition" title="' . esc_attr( $add_language_link_str ) . '" target="_blank">' . esc_attr( $add_language_link_str ) . '</a>' . "</p>\n";
?>
		</div>
		<br clear="left">
	</div>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy"><?php 
echo esc_html__( 'Time Format', 'webinarignition' );
?></div>
			<div class="inputTitleHelp"><?php 
echo esc_html__( 'Choose time format', 'webinarignition' );
?></div>
		</div>
		<div class="inputSection dateTime" style="padding-top:20px; padding-bottom: 30px;">
		<?php 
if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
    // old formats
    $webinar_data->time_format = get_option( 'time_format', 'H:i' );
}
$time_format = ( !empty( $webinar_data->time_format ) ? $webinar_data->time_format : get_option( 'time_format', 'H:i' ) );
$time_formats = array('g:i a', 'g:i A', 'H:i');
$custom = true;
foreach ( $time_formats as $format ) {
    echo "\t<label><input type='radio' name='time_format' value='" . esc_attr( $format ) . "'";
    if ( $time_format === $format ) {
        echo " checked='checked'";
        $custom = false;
    }
    echo ' /> <span class="date-time-text format-i18n">' . esc_html( date_i18n( $format ) ) . '</span><code>' . esc_html( $format ) . "</code></label><br/><br/>\n";
}
echo '<label><input type="radio" name="time_format" id="time_format_custom_radio" value="' . esc_attr( $time_format ) . '"';
checked( $custom );
echo '/> <span class="date-time-text date-time-custom-text">' . esc_html__( 'Custom:', 'webinarignition' ) . '</span><input type="text" name="time_format_custom" id="time_format_custom" value="' . esc_attr( $time_format ) . '" class="float-right small-text" /></label>' . '<br/><br/>' . '<p><strong>' . esc_html__( 'Preview:', 'webinarignition' ) . '</strong> <span id="time_format_preview" class="formatPreview">' . esc_html( date_i18n( $time_format ) ) . '</span>' . "<span class='spinner'></span>\n" . '</p>';
?>
		</div>
		<br clear="left">
	</div>

<?php 
webinarignition_display_option(
    $input_get['id'],
    ( !empty( $webinar_data->display_tz ) ? $webinar_data->display_tz : 'no' ),
    esc_html__( 'Display Time Zone', 'webinarignition' ),
    'display_tz',
    esc_html__( 'Choose whether to show the timezone when displaying the webinar start time', 'webinarignition' ),
    esc_html__( 'Yes', 'webinarignition' ) . ' [yes], ' . esc_html__( 'No', 'webinarignition' ) . ' [no]'
);
?>
		
</div>

<?php 
if ( 'AUTO' !== $webinar_data->webinar_date ) {
    webinarignition_display_edit_toggle(
        'edit-sign',
        esc_html__( 'Raw Optin Form Code -- 3rd Party Integration', 'webinarignition' ),
        'we_edit_raw_optin',
        esc_html__( 'This is a raw optin form that you can use to integrate this webinar with other landing pages / plugins... adv. users', 'webinarignition' )
    );
}
?>

<div id="we_edit_raw_optin" class="we_edit_area">

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy"><?php 
esc_html_e( 'Raw Optin Code', 'webinarignition' );
?></div>
			<div class="inputTitleHelp"><?php 
esc_html_e( 'Integrate this page into a 3rd party page...', 'webinarignition' );
?></div>
		</div>

		<div class="inputSection">

			<?php 
$raw_forms = esc_html__( 'Full Name: ', 'webinarignition' ) . ' <input type="text" name="name"><br>' . esc_html__( 'Best Email: ', 'webinarignition' ) . ' <input type="text" name="email"><br>
                                                     <input type="hidden" name="campaignID" value="' . $input_get['id'] . '" >
                                                     <input type="submit" value="' . esc_html__( 'Register For Webinar', 'webinarignition' ) . '">';
$raw_ar_code = '<form action="' . WEBINARIGNITION_URL . 'inc/lp/posted.php" method="post">' . $raw_forms . '</form>';
?>

			<textarea name="raw_optin_code" id="raw_optin_code" class="inputTextarea elem">
				<?php 
echo wp_kses_post( $raw_ar_code );
?>
			</textarea>

		</div>
		<br clear="left">

	</div>

	<?php 
webinarignition_display_info( esc_html__( 'Note: Raw Optin Code', 'webinarignition' ), __( 'This code can be used to integrate with other landing pages like OptimizePress, ListEruption, etc. <br><br>	When someone enters the form they get added to the webinar here, if you have sendgrid connected, they get an email and added to your sendgrid list. If you have an AR connected, they will also be added to the AR you setup.<br><br><b>** Note: Only <u>NAME & EMAIL</u> Are sent Over ** Your optin code must not require other fields to work properly or this may not work...</b>', 'webinarignition' ) );
?>

</div>


<?php 
Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
webinarignition_display_edit_toggle(
    'info-sign',
    esc_html__( 'META Info - THANK YOU PAGE', 'webinarignition' ),
    'we_edit_lp_meta_info2',
    esc_html__( 'Custom Meta Information for your thank you page (will fall back to landing page meta info)...', 'webinarignition' )
);
?>

<div id="we_edit_lp_meta_info2" class="we_edit_area">

	<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->meta_site_title_ty,
    esc_html__( 'Site Title', 'webinarignition' ),
    'meta_site_title_ty',
    esc_html__( 'This is the META Site Title', 'webinarignition' ),
    esc_html__( 'Ex: Awesome Webinar Training', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->meta_desc_ty,
    esc_html__( 'Site Description', 'webinarignition' ),
    'meta_desc_ty',
    esc_html__( 'This is the META Description', 'webinarignition' ),
    esc_html__( 'Ex: On This Webinar You Will Learn Amazing Things...', 'webinarignition' )
);
?>

</div>

<?php 
Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
webinarignition_display_edit_toggle(
    'info-sign',
    esc_html__( 'META Info - WEBINAR PAGE', 'webinarignition' ),
    'we_edit_lp_meta_info3',
    esc_html__( 'Custom Meta Information for your webinar page (will fall back to landing page meta info)...', 'webinarignition' )
);
?>

<div id="we_edit_lp_meta_info3" class="we_edit_area">

	<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->meta_site_title_webinar,
    esc_html__( 'Site Title', 'webinarignition' ),
    'meta_site_title_webinar',
    esc_html__( 'This is the META Site Title', 'webinarignition' ),
    esc_html__( 'Ex: Awesome Webinar Training', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->meta_desc_webinar,
    esc_html__( 'Site Description', 'webinarignition' ),
    'meta_desc_webinar',
    esc_html__( 'This is the META Description', 'webinarignition' ),
    esc_html__( 'Ex: On This Webinar You Will Learn Amazing Things...', 'webinarignition' )
);
?>

</div>

<?php 
Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
webinarignition_display_edit_toggle(
    'info-sign',
    esc_html__( 'META Info - REPLAY PAGE', 'webinarignition' ),
    'we_edit_lp_meta_info32',
    esc_html__( 'Custom Meta Information for your replay page (will fall back to landing page meta info)...', 'webinarignition' )
);
?>

<div id="we_edit_lp_meta_info32" class="we_edit_area">

	<?php 
webinarignition_display_field(
    $input_get['id'],
    ( isset( $webinar_data->meta_site_title_replay ) ? $webinar_data->meta_site_title_replay : '' ),
    esc_html__( 'Site Title', 'webinarignition' ),
    'meta_site_title_replay',
    esc_html__( 'This is the META Site Title', 'webinarignition' ),
    esc_html__( 'Ex: Awesome Webinar Training', 'webinarignition' )
);
webinarignition_display_field(
    $input_get['id'],
    ( isset( $webinar_data->meta_desc_replay ) ? $webinar_data->meta_desc_replay : '' ),
    esc_html__( 'Site Description', 'webinarignition' ),
    'meta_desc_replay',
    esc_html__( 'This is the META Description', 'webinarignition' ),
    esc_html__( 'Ex: On This Webinar You Will Learn Amazing Things...', 'webinarignition' )
);
?>

</div>

<?php 
Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
webinarignition_display_edit_toggle(
    'cog',
    esc_html__( 'Webinar Settings', 'webinarignition' ),
    'we_footer_code',
    esc_html__( 'Global Webinar Settings', 'webinarignition' )
);
?>

<div id="we_footer_code" class="we_edit_area">
	<?php 
webinarignition_display_textarea(
    $input_get['id'],
    ( isset( $webinar_data->footer_code ) ? $webinar_data->footer_code : '' ),
    esc_html__( 'Footer Code', 'webinarignition' ),
    'footer_code',
    esc_html__( 'Adds custom code at the end of the body tag.', 'webinarignition' ),
    ''
);
webinarignition_display_textarea(
    $input_get['id'],
    ( isset( $webinar_data->footer_code_ty ) ? $webinar_data->footer_code_ty : '' ),
    esc_html__( 'Footer Code on Thank You page', 'webinarignition' ),
    'footer_code_ty',
    esc_html__( 'Adds custom code at the end of the body tag only to the thank you page.', 'webinarignition' ),
    ''
);
?>
</div>

<?php 
webinarignition_display_edit_toggle(
    'cog',
    esc_html__( 'Performance', 'webinarignition' ),
    'we_edit_performance',
    esc_html__( 'Increase performance at the cost of less features', 'webinarignition' )
);
?>

<div id="we_edit_performance" class="we_edit_area">
	<?php 
webinarignition_display_option(
    $input_get['id'],
    ( isset( $webinar_data->live_stats ) ? $webinar_data->live_stats : null ),
    esc_html__( 'Live Stats', 'webinarignition' ),
    'live_stats',
    esc_html__( 'Disable live stats in case you are using other statistics system, and need to improve performance.', 'webinarignition' ),
    esc_html__( 'Enabled ', 'webinarignition' ) . ' [enabled], ' . esc_html__( 'Disabled', 'webinarignition' ) . ' [hide]'
);
webinarignition_display_option(
    $input_get['id'],
    ( isset( $webinar_data->wp_head_footer ) ? $webinar_data->wp_head_footer : null ),
    esc_html__( 'WP Head/Footer Integration', 'webinarignition' ),
    'wp_head_footer',
    esc_html__( 'Allows to other plugins to integrate custom scripts/style in WebinarIgnition pages', 'webinarignition' ),
    esc_html__( 'Disabled ', 'webinarignition' ) . '[disabled], ' . esc_html__( 'Enabled', 'webinarignition' ) . ' [enabled]'
);
?>
</div>

<?php 
webinarignition_display_edit_toggle(
    'play-circle',
    esc_html__( 'Live Console', 'webinarignition' ),
    'we_live_console_settings',
    esc_html__( 'Settings for customising live console', 'webinarignition' )
);
?>

	<div id="we_live_console_settings" class="we_edit_area">
		<?php 
if ( isset( $webinar_data->webinar_date ) && $webinar_data->webinar_date == 'AUTO' ) {
} else {
    webinarignition_display_field(
        $input_get['id'],
        ( !empty( $webinar_data->live_dash_url ) ? $webinar_data->live_dash_url : 'https://www.youtube.com/live_dashboard' ),
        esc_html__( 'Livestreamingservice dashboard URL', 'webinarignition' ),
        'live_dash_url',
        esc_html__( 'Put an URL to livestreamingservice dashboard, f.e to Youtube Studio', 'webinarignition' ),
        esc_html__( 'Ex. https://www.youtube.com/live_dashboard', 'webinarignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        ( !empty( $webinar_data->live_dash_btn_text ) ? $webinar_data->live_dash_btn_text : esc_html__( 'Go to Youtube Live', 'webinarignition' ) ),
        esc_html__( 'Text in link to Livestreamingservice', 'webinarignition' ),
        'live_dash_btn_text',
        esc_html__( 'This is what the link says for Livestreamingservice dashboard link...', 'webinarignition' ),
        esc_html__( 'Ex. Go to Youtube Live', 'webinarignition' )
    );
}
//end if
webinarignition_display_field_image_upd(
    $input_get['id'],
    ( isset( $webinar_data->live_console_logo ) ? $webinar_data->live_console_logo : '' ),
    esc_html__( 'Live Console Logo URL', 'webinarignition' ),
    'live_console_logo',
    esc_html__( 'Update default Webinarignition logo here...', 'webinarignition' ),
    esc_html__( 'http://yoursite.com/background-image.png', 'webinarignition' )
);
webinarignition_display_wpeditor_media(
    $input_get['id'],
    ( isset( $webinar_data->live_console_footer_area_content ) ? $webinar_data->live_console_footer_area_content : '<p>' . esc_html__( 'Live Console For WebinarIgnition - All Rights Reserved', 'webinarignition' ) . ' @ {{currentYear}}</p>' ),
    esc_html__( 'Footer Area Content', 'webinarignition' ),
    'live_console_footer_area_content',
    sprintf( esc_html__( 'Add some HTML content on the bottom of your live console page. Use %s placeholder if you want to show current year in your footer.', 'webinarignition' ), '{{currentYear}}' )
);
webinarignition_display_textarea(
    $input_get['id'],
    ( isset( $webinar_data->live_console_footer_code ) ? $webinar_data->live_console_footer_code : '' ),
    esc_html__( 'Footer Code', 'webinarignition' ),
    'live_console_footer_code',
    esc_html__( 'Adds custom code at the end of the body tag in live console page.', 'webinarignition' ),
    ''
);
?>
	</div>

<div class="bottomSaveArea">
	<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php 
esc_html_e( 'Save & Update', 'webinarignition' );
?></a>
</div>

</div>
