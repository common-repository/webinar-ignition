<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div id="listapps" class="createWrapper wi-create-wrapper">

	<div id="appHeader" class="dashHeaderListing" style="display: none;">
		<span><i class="icon-edit" style="margin-right: 5px;"></i> <?php esc_html_e('Create a New LIVE Webinar', 'webinarignition'); ?>:</span>
	</div>

	<div id="formArea" class="createWebinar wi-create-webinar">

		<div class="weCreateLeft wi-create-left">









			<div class="weCreateExtraSettings wi-create-extra-settings">


				<div class="weCreateTitle wi-create-title-wrap">

					<div class="weCreateTitleIcon">
						<!-- <i class="icon-arrow-right icon-3x weCreateTitleIconI"></i> -->
						<i class="icon-share-sign icon-3x"></i>
					</div>
					<div class="weCreateTitleCopy">
						<span class="weCreateTitleHeadline"><?php esc_html_e('Create New Webinar', 'webinarignition'); ?></span>
						<span class="weCreateTitleSubHeadline wi-create-title-sub-headline"><?php esc_html_e('Here you can set up a new webinar...', 'webinarignition'); ?></span>
					</div>


				<br clear="both"/>


				</div>

				<div class="wi-create-left-form-wrap">




					<div class="createTitleCopy1">Webinar Type: <span style="font-weight:normal;">
							<?php esc_html_e('Select the webinar type...', 'webinarignition'); ?></span>
					</div>
					<p class="createTitleCopy2"><?php esc_html_e('You can create a Live Webinar, Auto Webinar, OR Clone a Webinar...', 'webinarignition'); ?></p>

					<select class="inputField inputFieldDash2" name="cloneapp" id="cloneapp" autocomplete="off">
						<optgroup label="<?php echo esc_attr__('Create New', 'webinarignition'); ?>">
							<option value="auto"><?php echo esc_html__('Evergreen', 'webinarignition'); ?></option>
							<option value="new"><?php echo esc_html__('Live', 'webinarignition'); ?></option>
							<option value="import"><?php echo esc_html__('Import', 'webinarignition'); ?></option>
						</optgroup>
						<optgroup label="<?php echo esc_attr__('Clone Exisiting', 'webinarignition'); ?>">
							<?php
							global $wpdb;
							$table_db_name = $wpdb->prefix . 'webinarignition';

							$templates = $wpdb->get_results($wpdb->prepare("SELECT * FROM %i", $table_db_name), ARRAY_A);

							foreach ($templates as $template) {

								$name = stripslashes($template['appname']);
								$id   = stripslashes($template['ID']);

								$webinar_data_to_clone = WebinarignitionManager::webinarignition_get_webinar_data($id);

								$webinar_to_clone_type = isset($webinar_data_to_clone->webinar_date) && $webinar_data_to_clone->webinar_date === 'AUTO' ? 'AUTO' : 'live';

								echo "<option value='" . esc_attr($id) . "'>" . esc_html($name) . "</option>";
							}

							$current_user               = wp_get_current_user();
							$current_user_first_name    = $current_user->user_firstname ? $current_user->user_firstname : '';
							$current_user_last_name     = $current_user->user_lastname ? $current_user->user_lastname : '';
							$current_user_name          = ($current_user->user_firstname && $current_user->user_lastname) ? $current_user_first_name . ' ' . $current_user_last_name : $current_user->display_name;
							$tzstring                   = get_option('timezone_string');
							$date_formats               = array(__('F j, Y'), 'Y-m-d', 'm/d/Y', 'd/m/Y');
							$default_date_format        = $date_formats[0];
							$time_formats               = array( 'g:i a', 'g:i A', 'H:i' );
							$time_format                = $time_formats[0];
							?>
						</optgroup>
					</select>

					<div class="createTitleCopy1"><?php echo esc_html__('Webinar Name', 'webinarignition'); ?>: <span style="font-weight:normal;"><?php echo esc_html__('Give your new webinar a name / pretty url...', 'webinarignition'); ?></span>
					</div>
					<p class="createTitleCopy2">** <?php echo esc_html__('Used for the URL: ie:', 'webinarignition'); ?> <b><?php echo esc_html__('http://yoursite.com/webinar-name', 'webinarignition'); ?></b></p>
					<input class="inputField inputFieldDash2" placeholder="<?php echo esc_attr__('Webinar Name', 'webinarignition'); ?>" type="text" name="appname" id="appname" value="">

					<div class="createTitleCopy1" id="webinar_language">

						<?php echo esc_html__('Webinar Language', 'webinarignition'); ?>: <span style="font-weight:normal;">
							<?php echo esc_html__('Select the webinar language...', 'webinarignition'); ?></span>

						<?php

						$statusCheck = WebinarignitionLicense::webinarignition_get_license_level();

						$is_ultimate_activated = false;
						if (! empty($statusCheck) && (isset($statusCheck->switch) || isset($statusCheck->is_trial))) {
							$is_ultimate_activated = ! empty($statusCheck->is_trial) || $statusCheck->switch === 'enterprise_powerup'; // If enterprise_powerup, consider it as ultimate
						}

						$show_all_live_languages = true;
						$show_all_eg_languages   = true;

						?>
						<input type="hidden" id="wi_segl" name="wi_segl" value="<?php esc_attr_e(  $show_all_eg_languages ? 1 : 0 ); ?>" disabled />
						<input type="hidden" id="wi_sll" name="wi_sll" value="<?php esc_attr_e( $show_all_live_languages ? 1 : 0 ); ?>" disabled />

						<?php
						require_once ABSPATH . 'wp-admin/includes/translation-install.php';
						$translations        = wp_get_available_translations();
						$available_languages = webinarignition_get_available_languages();
						// Remove the prefixes from each element
						$available_languages = array_map(function ($value) {
							return str_replace(array('webinarignition-', 'webinar-ignition-'), '', $value);
						}, $available_languages);
						$available_languages = array_merge(array('en_US'), $available_languages);
						$translations        = array_merge(array('en_US' => array('native_name' => __('English'))), $translations);
						$selected_language   = get_locale();
						?>
						<select class="inputField inputFieldDash2" id="applang" name="applang" autocomplete="off">
							<?php foreach ($available_languages as $language) : ?>
								<option value="<?php echo esc_attr($language); ?>" <?php selected($selected_language, $language, true); ?>><?php echo isset($translations[$language]) ? esc_html($translations[$language]['native_name']) : ''; ?></option>
							<?php endforeach; ?>
						</select>
						<span id="wi_new_webinar_lang_select" class="spinner wi-spinner"></span>
						<p class="createTitleCopy2"><a title="<?php esc_attr_e('Want to overwrite language strings?', 'webinarignition'); ?>" href="https://webinarignition.tawk.help/article/overwrite-webinar-language" target="_blank"><?php esc_html_e('Want to overwrite language strings?', 'webinarignition'); ?></a></p>
						<p class="createTitleCopy2"><a title="<?php esc_attr_e('Want to add a language?', 'webinarignition'); ?>" href="https://webinarignition.tawk.help/article/add-language-to-webinarignition" target="_blank"><?php esc_html_e('Want to add a language?', 'webinarignition'); ?></a></p>

						<div class="createTitleCopy1">
							<?php esc_html_e('Use webinar language in webinar settings?', 'webinarignition'); ?>
						</div>
						<select class="inputField inputFieldDash2" id="settings_language" name="settings_language" autocomplete="off" ?>>
							<option value="no"><?php esc_html_e('No', 'webinarignition'); ?></option>
							<option value="yes" <?php selected($selected_language === 'en_US', true, true); ?>><?php esc_html_e('Yes', 'webinarignition'); ?></option>
						</select>

						<input type="hidden" name="site_default_language" id="site_default_language" value="<?php echo esc_attr( determine_locale() ); ?>">
					</div>

					<div class="importArea" style="display:none;">
						<div class="createTitleCopy1"><?php esc_html_e('Import Campaign', 'webinarignition'); ?>: <span style="font-weight:normal;"><?php esc_html_e('Clone From External WI Webinar', 'webinarignition'); ?></span>
						</div>
						<p class="createTitleCopy2"><?php esc_html_e('Paste in the export code below from another WI campaign...', 'webinarignition'); ?></p>
						<textarea id="importcode" style="width:100%; height: 150px;"
							placeholder="<?php esc_html_e('add import code here...', 'webinarignition'); ?>"></textarea>
					</div>



					<div class="weDashSection locale_formats date_formats">
						<span class="weDashSectionTitle"><?php esc_html_e('Date Format', 'webinarignition'); ?> * <span class="weDashSectionIcon"><i class="icon-calendar"></i></span> </span>

						<br />
						<?php
						foreach ($date_formats as $date_format) {
						?>
							<label id='default_date_radio_label'>
								<input type='radio' name="date_format" value="<?php echo esc_attr($date_format); ?>" <?php checked($default_date_format === $date_format, true, true); ?> /><span class="date-time-text format-i18n"><?php echo esc_html(date_i18n($date_format)); ?></span><code><?php echo esc_html($date_format); ?></code>
							</label>
							<br /><br />
						<?php
						}
						?>
						<input type="hidden" id="apptz" value="<?php echo esc_attr( wp_timezone_string() ); ?>" />
						<div id="wi_show_day_wrap">
							<label>
								<input name="wi_show_day" type="checkbox" checked><span style="margin-left: 15px;"><?php esc_html_e('Show Day', 'webinarignition'); ?></span> (<code id="wi_day_string"><?php echo esc_html(date_i18n('D')); ?></code>)
								<div id="wi_day_string_input" style="width: 160px; display: flex;float: right;">
									<label style="text-align: right;"><input type="radio" name="day_string" value="D" data-string="<?php echo esc_html(date_i18n('D')); ?>" checked> <?php esc_html_e('Short', 'webinarignition'); ?></label>
									<label style="text-align: right;"><input type="radio" name="day_string" value="l" data-string="<?php echo esc_attr( date_i18n('l') ); ?>"> <?php esc_html_e('Long', 'webinarignition'); ?></label>
								</div>
							</label>
							<br />
							<br />
						</div>
						<label>
							<input type="radio" name="date_format" id="date_format_custom_radio" value="custom" />
							<span class="date-time-text date-time-custom-text"><?php esc_html_e('Custom:'); ?></span>
							<input type="text" name="date_format_custom" id="date_format_custom" value="<?php esc_attr_e($default_date_format); ?>" class="float-right small-text" autocomplete="off" />
						</label>
						<br /><br />
						<p>
							<strong class="preview_text"><?php esc_html_e('Preview:'); ?></strong>
							<span class="formatPreview" id="date_format_preview"><?php echo esc_html( date_i18n( $default_date_format ) ); ?></span>
						</p>

					</div>

					<div class="weDashSection locale_formats time_formats">
						<span class="weDashSectionTitle"><?php esc_html_e('Time Format', 'webinarignition'); ?> * <span class="weDashSectionIcon"><i class="icon-time"></i></span> </span>
						<br />
						<?php

						$custom         = true;

						foreach ($time_formats as $format) {
							echo "\t<label id='default_time_radio_label'><input type='radio' name='time_format' value='" . esc_html($format) . "'";
							if ($time_format === $format) {
								echo " checked='checked'";
								$custom = false;
							}
							echo ' /> <span class="date-time-text format-i18n">' . esc_html( date_i18n($format) ) . '</span><code>' . esc_html($format) . "</code></label><br/><br/>\n";
						}

						echo '<label><input type="radio" name="time_format" id="time_format_custom_radio" value="' . esc_attr($time_format) . '"';
						checked($custom);
						echo '/> <span class="date-time-text date-time-custom-text">' . esc_html__('Custom:', 'webinarignition') . '</span><input type="text" name="time_format_custom" id="time_format_custom" value="' . esc_attr($time_format) . '" class="float-right small-text" /></label>';
						echo '<br/><br/><p><strong class="preview_text">' . esc_html__('Preview:', 'webinarignition') . '</strong> <span class="formatPreview" id="time_format_preview">' . esc_html(date_i18n(get_option('time_format'))) . '</span>';

						echo "\t<p class='date-time-doc'>" . esc_html__('Documentation on date and time formatting', 'webinarignition') . ' <a href="' . esc_url('https://wordpress.org/support/article/formatting-date-and-time/') . '" target="_blank">' . esc_html__('Read more', 'webinarignition') . '</a>' . "</p>\n";

						?>

					</div>


				</div>

			</div>

			<div class="timezoneRef wi-time-zone-ref">
				<div class="timezoneRefTitle"><b><?php esc_html_e('REFERENCE', 'webinarignition'); ?></b> :: <?php esc_html_e('Current Time:', 'webinarignition'); ?> <span class="timezoneRefZ"></span></div>
			</div>

		</div>

		<div class="weCreateRight wi-create-right">
 
			<div class="weDashRight wi-create-dash-right" style="margin-top: 0px;">

				<div class="weDashDateTitle">
					<!-- <i class="icon-ticket"></i> Webinar Event Info: -->
					<div class="dashWebinarTitleIcon"><i class="icon-ticket icon-3x"></i></div>

					<div class="dashWebinarTitleCopy">
						<h2 style="margin:0px; margin-top: 3px;"><?php esc_html_e('Webinar Event Info', 'webinarignition'); ?></h2>

						<p style="margin:0px; margin-top: 3px;"><?php esc_html_e('The core settings for your webinar event...', 'webinarignition'); ?></p>
					</div>

					<br clear="left">
				</div>

				<div class="weDashDateInner">

					<div class="weDashSection">
						<span class="weDashSectionTitle"><?php esc_html_e('Webinar Title', 'webinarignition'); ?> *
							<span class="weDashSectionIcon"><i class="icon-desktop"></i></span>
						</span>
						<br clear="right">
						<input type="text" class="inputField inputFieldDash elem" name="webinar_desc" id="webinar_desc" value=""
							placeholder="<?php esc_attr_e('Your Webinar Title', 'webinarignition'); ?>...">
					</div>

					<div class="weDashSection">
						<span class="weDashSectionTitle"><?php esc_html_e('Webinar Host(s)', 'webinarignition'); ?> *
							<span class="weDashSectionIcon"><i class="icon-user"></i></span>
						</span>
						<br clear="right">
						<input type="text" class="inputField inputFieldDash elem" name="webinar_host" id="webinar_host" value="<?php echo esc_attr($current_user_name); ?>"
							placeholder="<?php esc_attr_e('The Name Of The Host(s)', 'webinarignition'); ?>...">
					</div>




					<div class="weDashSection" id="createToggle1">
						<span class="weDashSectionTitle"><?php esc_html_e('Event Date', 'webinarignition'); ?> * <span class="weDashSectionIcon"><i class="icon-calendar"></i></span>
						</span>
						<br clear="right">
						<input type="text" class="inputField inputFieldDash elem dp-date" name="webinar_date" id="webinar_date" value="<?php echo esc_attr( date_i18n($default_date_format) ); ?>">
					</div>

					<div class="weDashSection" id="createToggle2">
						<span class="weDashSectionTitle"><?php esc_html_e('Event Time', 'webinarignition'); ?> *
							<span class="weDashSectionIcon"><i class="icon-time"></i></span>
						</span>
						<br clear="right">
						<input type="text" class="timepicker inputField inputFieldDash elem" name="webinar_start_time" id="webinar_start_time" value="<?php echo esc_attr( date_i18n($time_format) ); ?>" />
					</div>

					<div class="weDashSection" id="createToggle3">
						<span class="weDashSectionTitle"><?php esc_html_e('Event Timezone', 'webinarignition'); ?> *
							<span class="weDashSectionIcon"><i class="icon-globe"></i></span>
						</span>
						<br clear="right">
						<select name="webinar_timezone" id="webinar_timezone" class="inputField inputFieldDash elem ">
							<?php echo esc_html( webinarignition_create_tz_select_list($tzstring, get_user_locale()) ); ?>
						</select>
					</div>

				</div>

			</div>

			<div class="blue-btn-2create btn wi-btn-create-wb" id="createnewappBTN" style="float: left;">
				<a class="wi-btn-create-webinar" id="createnewapp" href="<?php echo esc_url(admin_url('?page=webinarignition-dashboard&create')); ?>">
						<i class="icon-plus-sign" style="margin-right: 5px;"></i>
						<?php esc_html_e('Create New Webinar', 'webinarignition'); ?>
				</a>
			</div>
 
		</div>




		<br clear="all" />



	</div>


</div>

<br clear="left" />