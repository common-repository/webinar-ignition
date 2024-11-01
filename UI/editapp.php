<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php

global $wpdb;
$table_db_name = $wpdb->prefix . WEBINARIGNITION_PLUGIN_NAME;
$ID            = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
$sql           = "SELECT * FROM $table_db_name WHERE ID = %d";
$safe_query    = $wpdb->prepare( $sql, $ID );
$data          = $wpdb->get_row( $safe_query, OBJECT );

// Return Option Object:
$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

function webinarIgnition_stripslashesFull( $input ) {
	if ( is_array( $input ) ) {
		$input = array_map( 'webinarIgnition_stripslashesFull', $input );
	} elseif ( is_object( $input ) ) {
		$vars = get_object_vars( $input );
		foreach ( $vars as $k => $v ) {
			$input->{$k} = webinarIgnition_stripslashesFull( $v );
		}
	} else {
		$input = stripslashes( $input );
	}
	return $input;
}

$webinar_date_format       = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : ( ( $webinar_data->webinar_date == 'AUTO' ) ? 'l, F j, Y' : get_option( 'date_format' ) );
$webinar_data->date_format = $webinar_date_format; // just in case date_format has backslah, as in 'j \d\e F \d\e Y'
$settings_language         = isset( $webinar_data->settings_language ) ? $webinar_data->settings_language : '';
if ( ! empty( $settings_language ) ) {
	switch_to_locale( $settings_language );
	unload_textdomain( 'webinarignition' );
	load_textdomain( 'webinarignition', WEBINARIGNITION_PATH . 'languages/webinarignition-' . $settings_language . '.mo' );
}

$default_page_url = get_edit_post_link( $data->postID );

?>
<div class="editTop" style="margin-bottom: 20px;">
	<div class="appinfo" style="margin-left: 5px;">

		<div class="apptopIcon">
			<i class="icon-<?php
			if ( $webinar_data->webinar_date == 'AUTO' ) {
				echo 'refresh ';
			} else {
				echo 'microphone ';
			}
			?>icon-3x "></i>
		</div>

		<div class="apptopTitle">
			<span class="appTitle">
				<span class="weName"><?php echo esc_attr( get_the_title( $data->postID ) ); ?></span>
				<span class="weNameField" style="display:none;">
					<input type="text" class="inputField inputFieldNameEdit" name="webinarURLName" id="webinarURLName" value="<?php echo esc_attr( get_the_title( $data->postID ) ); ?>">
				</span>
				<a href="<?php echo esc_url( $default_page_url ); ?>" target="_blank" class="editURLWE"><?php esc_html_e( 'EDIT', 'webinarignition' ); ?></a>
			</span>
			<span class="appMeta"><b><?php esc_html_e( 'Created', 'webinarignition' ); ?>:</b> <?php echo esc_html( stripcslashes( $data->created ) ); ?></span>
		</div>

		<br clear="left"/>

	</div>

	<div class="appactionz" style="padding-top: 12px;">
		
		<label class="toggle-switchy" for="webinar_status" data-size="xl">
				<input <?php echo ( isset( $webinar_data->webinar_status ) && $webinar_data->webinar_status === 'draft' ) ? '' : 'checked'; ?> type="checkbox" id="webinar_status">
				<span class="toggle"><span class="switch"></span></span>
		</label>
		
		<span class=" btn blue-btn-4 saveIt " id="saveIt" style="margin-left: 15px;">
			<a href="#"><i class="icon-save" style="margin-right: 5px;"></i>
				<?php esc_html_e( 'Save & Update', 'webinarignition' ); ?>
			</a>
		</span>
	</div>


	<br clear="all">

</div>

<div class="editNav wi-edit-nav">

	<div class="editItem editSelected editItemFirst" tab="tab1">
		<i class="icon-home icon-3x"></i>
		<?php esc_html_e( 'Dashboard', 'webinarignition' ); ?>
	</div>

	<div class="editItem" tab="tab9">
		<i class="icon-beaker icon-3x"></i>
		<?php esc_html_e( 'Design / Shortcodes', 'webinarignition' ); ?>
	</div>

	<div class="editItem" tab="tab3">
		<i class="icon-calendar icon-3x"></i>
		<?php esc_html_e( 'Registration Page', 'webinarignition' ); ?>
	</div>

	<div class="editItem" tab="tab4">
		<i class="icon-copy icon-3x"></i>
		<?php esc_html_e( 'Thank You', 'webinarignition' ); ?>
	</div>

	<div class="editItem" tab="tab2">
		<i class="icon-microphone icon-3x"></i>
		<?php
		if ( $webinar_data->webinar_date == 'AUTO' ) {
			esc_html_e( 'Auto Webinar', 'webinarignition' );
		} else {
			esc_html_e( 'Live Webinar', 'webinarignition' );
		}
		?>
	</div>

	<div class="editItem" tab="tab5">
		<i class="icon-film icon-3x"></i>
		<?php esc_html_e( 'Webinar Replay', 'webinarignition' ); ?>
	</div>

	<div class="editItem" tab="tab8">
		<i class="icon-envelope icon-3x"></i>
		<?php esc_html_e( 'Notifications', 'webinarignition' ); ?>
	</div>

	<div class="editItem editItemEnd" tab="tab6">
		<i class="icon-cogs icon-3x"></i>
		<?php esc_html_e( 'Extra Settings', 'webinarignition' ); ?>
	</div>

	<br clear="all">

</div>

<div class="editArea wi-edit-area">
	<form id="editApp">

		<input type="text" class="inputField inputFieldNameEdit" name="webinarURLName2" id="webinarURLName2" value="<?php echo esc_attr( $data->appname ); ?>" style="display:none;">
		<input type="hidden" name="webinar_permalink" id="webinar_permalink" value="<?php echo esc_url( get_permalink( $data->postID ) ); ?>">
		<input type="hidden" name="security" value="<?php echo esc_attr( wp_create_nonce( 'webinarignition_ajax_nonce' ) ); ?>">

		<?php

		webinarignition_display_field_hidden(
			'action',
			'webinarignition_edit'
		);

		webinarignition_display_field_hidden(
			'id',
			$ID
		);

		?>

		<?php


		require 'app/tab1.php';
		require 'app/tab2.php';
		require 'app/tab3.php';
		require 'app/tab4.php';
		require 'app/tab5.php';
		require 'app/tab6.php';
		require 'app/tab8.php';
		require 'app/tab9.php';

		?>


	</form>


	<div id="arcode_hdn_div"></div>
	<div id="arcode_hdn_div2"></div>
</div>

<style>
	
/* Colors: Default (blue) */
.editTop .toggle-switchy > input + .toggle:before {content:'<?php esc_html_e( 'Published', 'webinarignition' ); ?>';}
.editTop .toggle-switchy > input + .toggle:after {content:'<?php esc_html_e( 'Draft', 'webinarignition' ); ?>';}    
</style>
