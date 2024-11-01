<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CurlHelper {

	/*
	 * @param $args e.g. ['url' => 'https://example.com', 'method' => 'get', 'data' => [], 'response_is_json' => false]
	 * */
	private function webinarignition_wp_request( $args ) {
			$default_args = array(
				'url' => '',
				'method' => 'GET',
				'data' => array(),
				'response_is_json' => true,
			);

			$args = array_merge( $default_args, $args );
			$url = esc_url($args['url']); // Sanitize URL
			$method = strtoupper($args['method']);
			
			// Validate HTTP method
			$allowed_methods = ['GET', 'POST', 'PUT', 'DELETE'];
			if (!in_array($method, $allowed_methods)) {
				return new WP_Error('invalid_method', 'Invalid HTTP method');
			}

			$options = array(
				'method'  => $method,
				'body'    => $args['data'],
			);

			// Set headers if response is JSON
			if ($args['response_is_json']) {
				$options['headers'] = ['Content-Type' => 'application/json'];
				$options['body'] = json_encode($args['data']);
			}

			// Execute HTTP request
			$response = wp_remote_request($url, $options);

			// Check for errors
			if (is_wp_error($response)) {
				return $response->get_error_message();
			}

			// Get response body
			$body = wp_remote_retrieve_body($response);

			return $args['response_is_json'] ? json_decode($body) : $body;
	}

	public function get( $url, $response_is_json = true ) {
		$args = array(
			'url' => $url,
			'method' => 'get',
			'response_is_json' => $response_is_json,
		);
		return $this->curl( $args );
	}

	public function post( $url, $data, $response_is_json = true ) {
		$args = array(
			'url' => $url,
			'method' => 'get',
			'data' => $data,
			'response_is_json' => $response_is_json,
		);
		return $this->curl( $args );
	}
}
