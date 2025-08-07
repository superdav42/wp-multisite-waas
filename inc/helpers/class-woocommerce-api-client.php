<?php
/**
 * WooCommerce API Client for fetching addon products.
 *
 * @package WP_Ultimo
 * @subpackage Helpers
 * @since 2.0.0
 */

namespace WP_Ultimo\Helpers;

use WP_Error;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WooCommerce API Client for fetching addon products.
 *
 * @since 2.0.0
 */
class WooCommerce_API_Client {

	/**
	 * Base URL for the WooCommerce API.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $base_url;

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 * @param string $base_url The base URL for the WooCommerce API.
	 */
	public function __construct($base_url) {
		$this->base_url = trailingslashit($base_url);
	}


	/**
	 * Makes a public request to the WooCommerce API.
	 *
	 * @since 2.0.0
	 * @param string $endpoint The API endpoint.
	 * @param array  $params   Query parameters.
	 * @param string $method   HTTP method.
	 * @return array|WP_Error API response or WP_Error on failure.
	 */
	private function make_request($endpoint, $params = [], $method = 'GET') {
		// Try the public WooCommerce API endpoint first
		$public_url = $this->base_url . '/wp-json/wc/store/v1/' . ltrim($endpoint, '/');
		return $this->execute_request($public_url, $params, $method);
	}

	/**
	 * Executes the HTTP request.
	 *
	 * @since 2.0.0
	 * @param string $url    The request URL.
	 * @param array  $params Query parameters.
	 * @param string $method HTTP method.
	 * @return array|WP_Error API response or WP_Error on failure.
	 */
	private function execute_request($url, $params = [], $method = 'GET') {
		if ('GET' === $method) {
			$url = add_query_arg($params, $url);
			$args = [
				'method'  => 'GET',
				'timeout' => 30,
			];
		} else {
			$args = [
				'method'  => $method,
				'body'    => wp_json_encode($params),
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'timeout' => 30,
			];
		}

		$response = wp_remote_request($url, $args);

		if (is_wp_error($response)) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code($response);
		$response_body = wp_remote_retrieve_body($response);

		if ($response_code < 200 || $response_code >= 300) {
			return new WP_Error(
				'woocommerce_api_error',
				sprintf(
					/* translators: %1$s: HTTP response code, %2$s: response body */
					__('WooCommerce API request failed with status %1$s: %2$s', 'wp-ultimo'),
					$response_code,
					$response_body
				)
			);
		}

		$data = json_decode($response_body, true);

		if (null === $data) {
			return new WP_Error(
				'json_decode_error',
				__('Failed to decode API response JSON', 'wp-ultimo')
			);
		}

		return $data;
	}

	/**
	 * Gets products from the WooCommerce store.
	 *
	 * @since 2.0.0
	 * @param array $params Query parameters for filtering products.
	 * @return array|WP_Error Array of products or WP_Error on failure.
	 */
	public function get_products($params = []) {
		$default_params = [
			'per_page' => 100,
			'status'   => 'publish',
			'type'     => 'simple',
		];

		$params = array_merge($default_params, $params);

		return $this->make_request('products', $params);
	}

	/**
	 * Gets downloadable products (addons) with specific metadata.
	 *
	 * @since 2.0.0
	 * @return array|WP_Error Array of addon products or WP_Error on failure.
	 */
	public function get_addons() {
		$params = [
			'per_page'     => 100,
			'status'       => 'publish',
			'downloadable' => true,
			'type'         => 'subscription'
//			'category'     => 'addon', // Assuming addons are in a specific category
		];

		return $this->get_products($params);

		if (is_wp_error($products)) {
			return $products;
		}

		// Transform WooCommerce products into addon format
		$addons = [];
		foreach ($products as $product) {
			$addon = $this->transform_product_to_addon($product);
			if ($addon) {
				$addons[$addon['slug']] = $addon;
			}
		}

		return $addons;
	}

	/**
	 * Transforms a WooCommerce product into addon format.
	 *
	 * @since 2.0.0
	 * @param array $product WooCommerce product data.
	 * @return array|false Addon data or false if not a valid addon.
	 */
	private function transform_product_to_addon($product) {

		// Extract addon-specific metadata
		$addon_data = [
			'name'        => $product['name'],
			'slug'        => $product['slug'],
			'description' => $product['description'],
			'price'       => $product['prices']['price'],
			'free'        => '0' === $product['prices']['price'] || 0.0 === (float) $product['prices']['price'],
			'available'   => $product['is_purchasable'],
			'author'      => 'Multisite Ultimate',
			'author_url'  => 'https://multisiteultimate.com',
			'image_url'   => '',
			'installed'   => false, // Will be checked separately
			'beta'        => false,
			'legacy'      => false,
			'category'    => [],
			'download_url' => '',
		];

		// Extract image URL
		if (!empty($product['images']) && is_array($product['images'])) {
			$addon_data['image_url'] = $product['images'][0]['src'] ?? '';
		}

		// Extract categories
		if (!empty($product['categories']) && is_array($product['categories'])) {
			$addon_data['category'] = array_column($product['categories'], 'name');
		}

		// Extract download URL (for authenticated users)
		if (!empty($product['downloads']) && is_array($product['downloads'])) {
			$addon_data['download_url'] = $product['downloads'][0]['file'] ?? '';
		}

		// Check for beta/legacy status from product tags or meta
		if (!empty($product['tags']) && is_array($product['tags'])) {
			$tag_names = array_column($product['tags'], 'name');
			$addon_data['beta'] = in_array('beta', array_map('strtolower', $tag_names), true);
			$addon_data['legacy'] = in_array('legacy', array_map('strtolower', $tag_names), true);
		}

		// Extract version requirement from meta data
		if (!empty($product['meta_data']) && is_array($product['meta_data'])) {
			foreach ($product['meta_data'] as $meta) {
				if ('requires_version' === $meta['key']) {
					$addon_data['requires_version'] = $meta['value'];
					break;
				}
			}
		}

		return $addon_data;
	}

	/**
	 * Gets a specific product by ID.
	 *
	 * @since 2.0.0
	 * @param int $product_id The product ID.
	 * @return array|WP_Error Product data or WP_Error on failure.
	 */
	public function get_product($product_id) {
		return $this->make_request("products/{$product_id}");
	}

	/**
	 * Gets download URL for a product with authentication.
	 *
	 * @since 2.0.0
	 * @param string $product_slug The product slug.
	 * @param string $access_token OAuth access token.
	 * @return string|WP_Error Download URL or WP_Error on failure.
	 */
	public function get_download_url($product_slug, $access_token = '') {
		// For free addons, we can provide direct download without authentication
		$product = $this->get_product_by_slug($product_slug);
		if (!is_wp_error($product) && !empty($product['free']) && $product['free']) {
			// Try to get direct download URL from product data
			if (!empty($product['downloads']) && is_array($product['downloads'])) {
				return $product['downloads'][0]['file'] ?? '';
			}
		}

		// For premium addons, construct authenticated download URL
		$download_url = add_query_arg([
			'action' => 'download_addon',
			'slug'   => $product_slug,
		], $this->base_url . 'wp-json/multisite-ultimate/v1/download');

		if ($access_token) {
			$download_url = add_query_arg('access_token', $access_token, $download_url);
		}

		return $download_url;
	}

	/**
	 * Gets a product by its slug.
	 *
	 * @since 2.0.0
	 * @param string $slug The product slug.
	 * @return array|WP_Error Product data or WP_Error on failure.
	 */
	public function get_product_by_slug($slug) {
		$params = [
			'slug' => $slug,
			'per_page' => 1,
		];

		$products = $this->get_products($params);

		if (is_wp_error($products)) {
			return $products;
		}

		if (empty($products) || !is_array($products)) {
			return new WP_Error('product_not_found', __('Product not found.', 'wp-ultimo'));
		}

		return $products[0];
	}
}