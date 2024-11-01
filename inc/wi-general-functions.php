<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! function_exists( 'wi_get_page_by_title' ) ) {
	function wi_get_page_by_title( $title = 'sample post' ) {

		$query = new WP_Query(
			array(
				'post_type'              => 'page',
				'title'                  => $title,
				'post_status'            => 'all',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'orderby'                => 'post_date ID',
				'order'                  => 'ASC',
			)
		);

		if ( ! empty( $query->post ) ) {
			$page_got_by_title = $query->post;
		} else {
			$page_got_by_title = null;
		}

		return $page_got_by_title;
	}
}//end if

if ( ! function_exists( 'webinarignition_get_cta_aiframe_sc' ) ) {
	function webinarignition_get_cta_aiframe_sc( $webinar_id, $cta_index, $cta_iframe_sc = '' ) {

		$aiframe_id = "wi-cta-{$webinar_id}-{$cta_index}";

		$cta_aiframe_defaults = array(
			'src'                          => 'page',
			'id'                           => $aiframe_id,
			'name'                         => $aiframe_id,
			'use_shortcode_attribute_only' => 'true',
			'onload_resize'                => 'true',
			'onload_scroll_top'            => 'iframe',
			'hide_page_until_loaded'       => 'true',
			'scrolling'                    => 'no',
			'height'                       => '0',
			'onload_resize_delay'          => '2000',
			'width'                        => '100%',
			'content_id'                   => '',
			'content_styles'               => '',
			'parent_content_css'           => '.timedUnderArea { padding: 0; }',
			'iframe_content_id'            => 'body',
			'iframe_content_styles'        => 'background-color: white',
			'iframe_content_css'           => 'body {background-color: transparent} am-lite-footer { display:none;}header { display:none;} html {margin:0;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0; padding:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0; border:0;border-top:0;border-bottom:0;border-right:0;border-left:0; } body {margin:0;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0; padding:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0; border:0;border-top:0;border-bottom:0;border-right:0;border-left:0; }#wpadminbar { display:none; } #amelia-container { margin:0 !important; }',
			'iframe_hide_elements'         => '',
		);

		$user_defined_attrs = array();

		if ( ! empty( $cta_iframe_sc ) ) {
			$cta_iframe_sc      = str_replace( array( '[advanced_iframe', '/]', ']' ), '', $cta_iframe_sc );
			$user_defined_attrs = shortcode_parse_atts( $cta_iframe_sc );
		}

		if ( ! is_array( $user_defined_attrs ) ) {
			$user_defined_attrs = array();
		}

		if ( isset( $user_defined_attrs['iframe_content_css'] ) && ! empty( $user_defined_attrs['iframe_content_css'] ) ) {
			$cta_aiframe_defaults['iframe_content_css'] = $cta_aiframe_defaults['iframe_content_css'] . ' ' . $user_defined_attrs['iframe_content_css'];
			unset( $user_defined_attrs['iframe_content_css'] );
		}

		$cta_aiframe_attr = wp_parse_args( $user_defined_attrs, $cta_aiframe_defaults );

		$cta_aiframe_attr['src']                          = 'page';
		$cta_aiframe_attr['id']                           = $aiframe_id;
		$cta_aiframe_attr['name']                         = $aiframe_id;
		$cta_aiframe_attr['use_shortcode_attribute_only'] = 'true';

		$cta_aiframe_final_sc = '[advanced_iframe ';
		foreach ( $cta_aiframe_attr as $key => $value ) {
			$cta_aiframe_final_sc .= $key . '="' . $value . '" ';
		}

		$cta_aiframe_final_sc .= ']';
		$cta_aiframe_final_sc .= '<style>' . $cta_aiframe_defaults['iframe_content_css'] . '</style>';
		// $cta_aiframe_final_sc = '[advanced_iframe use_shortcode_attributes_only="true" src="page" width="300" height="300" id="'. $aiframe_id . '" onload_resize="true" onload_scroll_top="iframe" hide_page_until_loaded="true" iframe_content_id="body" iframe_content_styles="background-color: rgba(255, 255, 255, 0.00);" iframe_hide_elements="header,nav,aside,footer,#wpadminbar"]';

		return $cta_aiframe_final_sc;
	}
}//end if
