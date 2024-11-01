<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="tabber wi-tab-design-and-shortcodes" id="tab9" style="display: none;">
	<div class="titleBar">
		<h2><?php esc_html_e( 'Design / Templates', 'webinarignition' ); ?></h2>
		<p><?php esc_html_e( 'Here you can select which front-end theme you want and what webinar theme you want...', 'webinarignition' ); ?></p>
	</div>
		<?php 
		$input_get = array(
			'id' => filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW)
		); 
		?>
	<div id="we_design_fe" class="we_edit_area" style="display:block;">
		<?php
		$fe_template 	= ! empty( $webinar_data->fe_template ) ? $webinar_data->fe_template : '';
		$template_items = "$sitePath" . "images/lp1.png [lp],
		$sitePath" . "images/lp2.png [ss],
		$sitePath" . 'images/lp3.png [cp]';
		$template_items = explode( ',', $template_items );

		$webinar_template_selected = ! empty( $webinar_data->webinar_template ) ? $webinar_data->webinar_template : 'modern';

		$webinar_templates = array(
			'modern' => array(
				'preview' => $sitePath . 'images/webip-modern.png',
			),
			'classic' => array(
				'preview' => $sitePath . 'images/webip-classic.png',
			),
		);
		?>

		<div class="editSection wi-edit-section">
			<div class="inputTitle" style="float: none;width: 100%;">
				<div class="inputTitleCopy"><?php echo esc_html__( 'Registration Funnel Theme: ', 'webinarignition' ); ?></div>
				<div class="inputTitleHelp"><?php echo esc_html__( 'You can choose between the styles on the right. This is for the landing page/registration page and for the thank you page styles...', 'webinarignition' ); ?></div>
			</div>

				<?php
				$i = 0; // Counter
				$selectedClass = '';

				foreach ( $template_items as $item ) {

					// parse value

					$item = explode( '[', $item );
					$item[0] = trim( $item[0] );
					$item[1] = str_replace( ']', '', $item[1] );

					if ( $fe_template == '' && $i == '0' ) {
						// Is First Element && Data is null
						$selectedClass = 'dub_select_image_selected';
					}

					?>
					<div class="dub_select_image ds_fe_template <?php echo esc_attr($selectedClass); ?> <?php if ( $fe_template == $item[1] ) {
						echo 'dub_select_image_selected';
																} ?>" dsData="<?php echo esc_attr($item[1]); ?>" dsID="fe_template">

						<img src="<?php echo esc_url($item[0]); ?>"/>

					</div>
					<?php

					++$i; // add to counter
					$selectedClass = ''; // Reset Class
				}//end foreach

				?>
			<br clear="all"/>
				<input type='hidden' class="elem" name="fe_template" id="fe_template" value="<?php echo esc_attr( isset( $webinar_data->fe_template ) ? $webinar_data->fe_template : 'lp' ); ?>"/>
		</div>

		<?php
		if ( $webinar_data && ! WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ) {
			?><div style="display: none"><?php
		}
		?>
			<div class="editSection">
				<div class="inputTitle" style="float: none;width: 100%;margin-bottom: 25px;">
					<div class="inputTitleCopy"><?php echo esc_html__( 'Webinar Page Layout: ', 'webinarignition' ); ?></div>
					<div class="inputTitleHelp"><?php echo esc_html__( 'You can choose between the styles below. This is for the webinar page and replay page...', 'webinarignition' ); ?></div>
				</div>

				<?php
				$i = 0;
				$selectedClass = '';

				foreach ( $webinar_templates as $slug => $item ) {
					?>
					<div
						class="dub_select_image ds_webinar_template<?php echo $slug === $webinar_template_selected ? ' dub_select_image_selected' : ''; ?>"
						dsData="<?php echo esc_attr( $slug ); ?>"
						dsID="webinar_template"
					>
						<img src="<?php echo esc_url($item['preview']); ?>"/>
					</div>
					<?php
				}
				?>

				<br clear="all"/>

				<input type='hidden' class="elem" name="webinar_template" id="webinar_template" value="<?php echo esc_attr( $webinar_template_selected ); ?>"/>
			</div>
		<?php
		if ( $webinar_data && ! WebinarignitionPowerups::webinarignition_is_modern_template_enabled( $webinar_data ) ) {
			?></div><?php
		}
		?>

		<?php
		if ( ! WebinarignitionPowerupsShortcodes::webinarignition_is_enabled( $webinar_data ) ) {
			?>
			<div style="display: none;">
			<?php
		}

			webinarignition_display_option(
				$input_get['id'],
				! empty( $webinar_data->custom_templates_styles ) ? $webinar_data->custom_templates_styles : 'on',
				esc_html__( 'Shortcodes styles', 'webinarignition' ),
				'custom_templates_styles',
				esc_html__( 'You can disable default shortcodes styles if you want to style all elements by your own.', 'webinarignition' ),
				esc_html__( 'Enable styles', 'webinarignition' ) . ' [on],' . esc_html__( 'Disable styles', 'webinarignition' ) . ' [off]'
			);


			?>
					
			<?php
			webinarignition_display_global_shortcodes(
				$webinar_data,
				$input_get['id'],
				esc_html__( 'Global shortcodes', 'webinarignition' ),
				esc_html__( 'This shortcodes can be used on any template page', 'webinarignition' )
			);

			?>
				</div>
			<?php

			$pages = get_posts( array(
				'numberposts' => -1,
				'orderby'     => 'post_title',
				'order'       => 'ASC',
				'include'     => array(),
				'post_type'   => 'page',
			) );

			$pages_options = array();

			if ( ! empty( $pages ) ) {
				foreach ( $pages as $page ) {
					$url = get_permalink( $page->ID );
					$pages_options[ $page->ID ] = array(
						'label' => $page->post_title,
						'url' 	=> $url,
					);
				}

				$custom_templates = WebinarignitionPowerupsShortcodes::webinarignition_get_available_templates();

				if ( $webinar_data->webinar_date == 'AUTO' ) {
					unset( $custom_templates['custom_closed_page'] );
				}

				$available_shortcodes = WebinarignitionPowerupsShortcodes::webinarignition_get_available_shortcodes();
				$available_shortcodes_by_tpl = array();

				foreach ( $available_shortcodes as $sh_key => $sh_data ) {
					if ( 'registration' 	=== $sh_data['page'] ) {
						$available_shortcodes_by_tpl['custom_registration_page'][ $sh_key ] = $sh_data;
					} elseif ( 'thankyou' 	=== $sh_data['page'] ) {
						$available_shortcodes_by_tpl['custom_thankyou_page'][ $sh_key ] = $sh_data;
					} elseif ( 'webinar' 	=== $sh_data['page'] ) {
						$available_shortcodes_by_tpl['custom_webinar_page'][ $sh_key ] = $sh_data;
					} elseif ( 'countdown' 	=== $sh_data['page'] ) {
						$available_shortcodes_by_tpl['custom_countdown_page'][ $sh_key ] = $sh_data;
					} elseif ( 'replay' 	=== $sh_data['page'] ) {
						$available_shortcodes_by_tpl['custom_replay_page'][ $sh_key ] = $sh_data;
					} elseif ( 'closed' 	=== $sh_data['page'] ) {
						$available_shortcodes_by_tpl['custom_closed_page'][ $sh_key ] = $sh_data;
					}
				}

				global $webinarignition_shortcodes_is_list;
				$webinarignition_shortcodes_is_list = true;

				foreach ( $custom_templates as $tpl_id => $tpl_data ) {
					$tpl_selected_data = ! empty( $webinar_data->{$tpl_id} ) ? $webinar_data->{$tpl_id} : '';
					$shortcodes = ! empty( $available_shortcodes_by_tpl[ $tpl_id ] ) ? $available_shortcodes_by_tpl[ $tpl_id ] : array();
					$tpl_data_help = isset( $tpl_data['help'] ) ? $tpl_data['help'] : '';
					webinarignition_display_template_dropdown_options(
						$webinar_data,
						$input_get['id'],
						$tpl_selected_data,
						$tpl_data['title'],
						$tpl_id,
						$tpl_data_help,
						$pages_options,
						$tpl_data['params'],
						$shortcodes,
						esc_html__( '-- select page --', 'webinarignition' )
					);
				}
				$webinarignition_shortcodes_is_list = false;
			} else {

			}//end if
			if ( ! WebinarignitionPowerupsShortcodes::webinarignition_is_enabled( $webinar_data ) ) {
				?>
			</div>
				<?php
			}
			?>

				<div class="bottomSaveArea">
						<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;" ><i class="icon-save" ></i> <?php esc_html_e( 'Save & Update', 'webinarignition' ); ?></a>
				</div>


	</div>

	
	
		<?php if ( ! empty( $webinar_data->webinar_lang ) ) {
			restore_previous_locale(); } //end if
		?>

</div>
