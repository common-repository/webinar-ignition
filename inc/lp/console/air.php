<!-- ON AIR AREA -->
<?php 
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="onairTab" style="display:none;" class="consoleTabs">
	<div class="statsDashbord">
		<div class="statsTitle statsTitle-Air">
			<div class="statsTitleIcon">
				<i class="icon-microphone icon-2x"></i>
			</div>

			<div class="statsTitleCopy">
				<h2><?php 
esc_html_e( 'On Air', 'webinarignition' );
?></h2>
				<p><?php 
esc_html_e( 'Manage the live broadcasting message to live viewers...', 'webinarignition' );
?></p>
			</div>

			<br clear="left" />
		</div>
	</div>

	<div class="innerOuterContainer">
		<div class="innerContainer">
			<div class="airSwitch">
				<div class="airSwitchLeft">
					<span class="airSwitchTitle"><?php 
esc_html_e( 'On Air Broadcast Switch', 'webinarignition' );
?></span>
					<span class="airSwitchInfo"><?php 
esc_html_e( 'If set to ON, the message/html below will appear under the webinar (instantly) for people on the webinar...', 'webinarignition' );
?></span>
				</div>

				<div class="airSwitchRight">
					<p class="field switch">
						<input type="hidden" id="airToggle" value="
						<?php 
if ( !isset( $webinar_data->air_toggle ) || empty( $webinar_data->air_toggle ) || 'on' === $webinar_data->air_toggle ) {
    echo 'on';
} else {
    echo esc_html( $webinar_data->air_toggle );
}
?>
						">
						<label for="radio1" class="cb-enable 
						<?php 
if ( !isset( $webinar_data->air_toggle ) || empty( $webinar_data->air_toggle ) || $webinar_data->air_toggle == 'on' ) {
    echo 'selected';
}
?>
						"><span><?php 
esc_html_e( 'On', 'webinarignition' );
?></span></label>
						<label for="radio2" class="cb-disable 
						<?php 
if ( isset( $webinar_data->air_toggle ) && $webinar_data->air_toggle == 'off' ) {
    echo 'selected';
}
?>
						"><span><?php 
esc_html_e( 'Off', 'webinarignition' );
?></span></label>
					</p>
				</div>

				<br clear="all" />
			</div>

		  

			<!-- Ameilia Switch -->
			<div class="airSwitch">
				<div class="airSwitchLeft">
					<span class="airSwitchTitle"><?php 
esc_html_e( 'Display CTA in iFrame', 'webinarignition' );
?></span>
					<?php 
// check if the iframe plugin is activated or not to display message to user.
if ( is_plugin_active( 'advanced-iframe/advanced-iframe.php' ) ) {
    ?>
				<span class="airSwitchInfo"><?php 
    esc_html_e( 'Display your CTA contents in Iframe using Advanced Iframe plugin.', 'webinarignition' );
    ?></span>
						<?php 
} else {
    $advanced_iframe_url = sprintf( '
								<a href="%s" target="_blank">%s</a>', esc_url( self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=advanced-iframe' ) ), esc_html__( 'Advanced iFrame', 'webinarignition' ) );
    ?>
				<span class="airSwitchInfo">
					<?php 
    printf( 
        /* translators: %s: URL to Advanced iFrame plugin */
        esc_html__( 'Does your CTA content contain scripts or does it not load and look good? Then you can display your CTA content in an Iframe, to enable this feature you need to install and activate the free "%s" plugin.', 'webinarignition' ),
        wp_kses( $advanced_iframe_url, array(
            'a' => array(
                'href'   => array(),
                'target' => array(),
            ),
        ) )
     );
    ?>
				</span>
						<?php 
    // Advanced iFrame plugin is not activated
}
?>
					
				</div>
			<?php 
if ( is_plugin_active( 'advanced-iframe/advanced-iframe.php' ) ) {
    ?>
				<div class="airSwitchRight">
					<p class="field switch ameliaSwitch">
						<input type="hidden" id="airAmeliaToggle" value="
						<?php 
    if ( !isset( $webinar_data->air_amelia_toggle ) || empty( $webinar_data->air_amelia_toggle ) || 'on' === $webinar_data->air_toggle ) {
        echo 'on';
    } else {
        echo esc_html( $webinar_data->air_amelia_toggle );
    }
    ?>">
						<label for="radio1" class="cb-enable 
						<?php 
    if ( !isset( $webinar_data->air_amelia_toggle ) || empty( $webinar_data->air_amelia_toggle ) || 'on' === $webinar_data->air_amelia_toggle ) {
        echo 'selected';
    }
    ?>
						"><span><?php 
    esc_html_e( 'On', 'webinarignition' );
    ?></span></label>
						<label for="radio2" class="cb-disable 
						<?php 
    if ( isset( $webinar_data->air_amelia_toggle ) && $webinar_data->air_amelia_toggle == 'off' ) {
        echo 'selected';
    }
    ?>
						"><span><?php 
    esc_html_e( 'Off', 'webinarignition' );
    ?></span></label>
					</p>
				</div>
				<?php 
}
//end if
?>

				<br clear="all" />
			</div>

			<div id="wi-notification-overlay" class="wi-notification-overlay wi-hidden"></div>
			<div id="wi-notification-box" class="wi-hidden">
				<div class="wi-notification-content">
	<span id="wi-notification-message"></span>
	<button id="wi-close-notification">Close</button>
	</div>
</div>

			<div class="airEditorArea" style="margin-top: 20px;">
				<?php 
$editor_content = ( isset( $webinar_data->air_html ) ? stripcslashes( $webinar_data->air_html ) : '' );
// added default WordPress editor instead of summernote editor
$content = $editor_content;
// Initial content
$editor_id = 'airCopy_editor';
// Unique ID for the editor
$settings = array(
    'textarea_name' => 'airCopy_textarea',
    'textarea_rows' => 10,
    'tinymce'       => array(
        'toolbar1' => 'styleselect | bold underline | fontselect | forecolor | bullist numlist | table | link | fullscreen code help',
    ),
    'quicktags'     => true,
);
// Generate the editor
wp_editor( $content, $editor_id, $settings );
?>
				<div class="airExtraOptions">
					<span class="airSwitchTitle"><?php 
esc_html_e( 'Order Button To Copy/Tab name', 'webinarignition' );
?></span>
					<span class="airSwitchInfo"><?php 
esc_html_e( 'This is the copy that is displayed on the button...', 'webinarignition' );
?></span>
					<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_html_e( 'Ex: Click Here To Download Your Copy', 'webinarignition' );
?>" id="air_btn_copy" value="<?php 
echo ( isset( $webinar_data->air_btn_copy ) ? esc_attr( $webinar_data->air_btn_copy ) : '' );
?>">
				</div>

				<div class="airExtraOptions">
					<span class="airSwitchTitle"><?php 
esc_html_e( 'Order Button URL', 'webinarignition' );
?></span>
					<span class="airSwitchInfo"><?php 
esc_html_e( 'This is the url the button goes to (leave blank if you don\'t want the button to appear)...', 'webinarignition' );
?></span>
					<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_html_e( 'Ex: http://yoursite.com/order-now', 'webinarignition' );
?>" id="air_btn_url" value="<?php 
echo ( isset( $webinar_data->air_btn_url ) ? esc_url( $webinar_data->air_btn_url ) : '' );
?>">
				</div>
				<div class="airExtraOptions">
					<?php 
$air_btn_color = ( !empty( $webinar_data->air_btn_color ) ? $webinar_data->air_btn_color : '#6BBA40' );
webinarignition_display_color(
    $ID,
    $air_btn_color,
    __( 'CTA Button Color', 'webinarignition' ),
    'air_btn_color',
    __( 'This is the color of the CTA button...', 'webinarignition' ),
    '#6BBA40'
);
?>
				</div>

				<?php 
if ( $webinar_data->cta_position != 'outer' ) {
    ?>
						<!-- Broadcast Message Width -->
						<div class="airExtraOptions">
							<span class="airSwitchTitle"><?php 
    esc_html_e( 'Broadcast Message Width', 'webinarignition' );
    ?></span>
							<span class="airSwitchInfo"><?php 
    esc_html_e( 'Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA 60% width', 'webinarignition' );
    ?></span>
							<input type="text" style="margin-top: 10px;" placeholder="<?php 
    esc_attr_e( 'Ex: 50%', 'webinarignition' );
    ?>" id="air_broadcast_message_width" value="<?php 
    echo ( isset( $webinar_data->air_broadcast_message_width ) ? esc_attr( stripcslashes( $webinar_data->air_broadcast_message_width ) ) : '' );
    ?>">
						</div>

						<div class="airExtraOptions">
							<span class="airSwitchTitle"><?php 
    esc_html_e( 'Broadcast Message Alignment', 'webinarignition' );
    ?></span>
							<span class="airSwitchInfo"><?php 
    esc_html_e( 'Set alignment for default CTA section. If not selected then center will be the default option.', 'webinarignition' );
    ?></span>
							<?php 
    ?>
								<?php 
    Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
    ?> 
							<?php 
    ?>
						</div>

						<?php 
} else {
    ?>
						<!-- Broadcast Message Width -->
						<div class="airExtraOptions">
							<span class="airSwitchTitle"><?php 
    esc_html_e( 'No width is applicable for sidebar CTA', 'webinarignition' );
    ?></span>
							<input type="hidden" style="margin-top: 10px;" id="air_broadcast_message_width" value="100%">
						</div>
						<div class="airExtraOptions">
							<span class="airSwitchTitle"><?php 
    esc_html_e( 'Broadcast Message Alignment Not Applicable on Sidebar CTA', 'webinarignition' );
    ?></span>
							<input class="live_webinar_ctas_alignment_radios" 
								<?php 
    checked( ( isset( $webinar_data->live_webinar_ctas_alignment_radios ) ? $webinar_data->live_webinar_ctas_alignment_radios : 'center' ), 'center' );
    ?> 
								type="checkbox" id="centerAlign" name="alignment" value="center">
						</div>
						<?php 
}
?>
				
			</div>

			
			<!-- Beware to remove html element who have js relation -->
			<div class="airSwitchSaveArea" rel="js-webinar-id" data-webinar-id="<?php 
echo esc_attr( $webinar_data->id );
?>">
				<div class="airSwitchFooterRight" style="margin-bottom: 20px;">
					<button type="button" href="#" id="saveAir" class="small button radius success" style="margin-right:0px;"><i class="icon-save"></i> <span id="saveAirText"><?php 
esc_html_e( 'Save On Air Settings', 'webinarignition' );
?></span></button>
				</div>

				<br clear="all" />

			</div>

		</div>
	</div>

</div>