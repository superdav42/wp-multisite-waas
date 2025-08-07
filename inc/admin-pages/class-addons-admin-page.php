<?php
/**
 * WP Ultimo Dashboard Admin Page.
 *
 * @package WP_Ultimo
 * @subpackage Admin_Pages
 * @since 2.0.0
 */

namespace WP_Ultimo\Admin_Pages;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo Dashboard Admin Page.
 */
class Addons_Admin_Page extends Wizard_Admin_Page {

	/**
	 * Holds the ID for this page, this is also used as the page slug.
	 *
	 * @var string
	 */
	protected $id = 'wp-ultimo-addons';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $type = 'submenu';

	/**
	 * Menu position. This is only used for top-level menus
	 *
	 * @since 1.8.2
	 * @var integer
	 */
	protected $position = 999;

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $parent = 'wp-ultimo';

	/**
	 * If this number is greater than 0, a badge with the number will be displayed alongside the menu title
	 *
	 * @since 1.8.2
	 * @var integer
	 */
	protected $badge_count = 0;

	/**
	 * Holds the admin panels where this page should be displayed, as well as which capability to require.
	 *
	 * To add a page to the regular admin (wp-admin/), use: 'admin_menu' => 'capability_here'
	 * To add a page to the network admin (wp-admin/network), use: 'network_admin_menu' => 'capability_here'
	 * To add a page to the user (wp-admin/user) admin, use: 'user_admin_menu' => 'capability_here'
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $supported_panels = array(
		'network_admin_menu' => 'wu_read_settings',
	);

	/**
	 * Should we hide admin notices on this page?
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $hide_admin_notices = false;

	/**
	 * Should we force the admin menu into a folded state?
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $fold_menu = false;

	/**
	 * Holds the section slug for the URLs.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $section_slug = 'tab';

	/**
	 * Defines if the step links on the side are clickable or not.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $clickable_navigation = true;

	/**
	 * Caches the list of add-ons.
	 *
	 * @since 2.0.0
	 * @var null|array
	 */
	protected $addons;

	/**
	 * Allow child classes to add hooks to be run once the page is loaded.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/load-(page)
	 * @since 1.8.2
	 * @return void
	 */
	public function init() {

		parent::init();

		add_action('wp_ajax_serve_addons_list', array($this, 'serve_addons_list'));
	}

	/**
	 * Register forms
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_forms() {

		wu_register_form(
			'addon_more_info',
			array(
				'render'  => array($this, 'display_more_info'),
				'handler' => array($this, 'install_addon'),
			)
		);
	}

	/**
	 * Displays the more info tab.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function display_more_info() {

		$addon_slug = wu_request('addon');

		$addon = wu_get_isset($this->get_addons_list(), $addon_slug);

		$upgrade_url = wu_network_admin_url(
			'wp-ultimo-pricing',
			array(
				'checkout'      => 'true',
				'plan_id'       => '4675',
				'plan_name'     => 'wpultimo',
				'billing_cycle' => 'annual',
				'pricing_id'    => '3849',
				'currency'      => 'usd',
			)
		);

		wu_get_template(
			'base/addons/details',
			array(
				'upgrade_url' => $upgrade_url,
				'addon'       => (object) $addon,
				'addon_slug'  => $addon_slug,
			)
		);

		do_action('wu_form_scripts', false);
	}

	/**
	 * Installs a given add-on.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function install_addon() {

		if (! current_user_can('manage_network_plugins')) {
			$error = new \WP_Error('error', __('You do not have enough permissions to perform this task.', 'wp-ultimo'));

			wp_send_json_error($error);
		}

		$addon_slug = wu_request('addon');

		$addon = wu_get_isset($this->get_addons_list(), $addon_slug);

		if (! $addon) {
			$error = new \WP_Error('addon-not-found', __('Add-on not found.', 'wp-ultimo'));
			wp_send_json_error($error);
		}

		// Check if user has access to this addon (skip for free addons)
		if (! $addon['free'] && ! \WP_Ultimo\License::get_instance()->allowed('wpultimo')) {
			$error = new \WP_Error('license-required', __('A valid license is required to install premium add-ons.', 'wp-ultimo'));
			wp_send_json_error($error);
		}

		// Get access token for authenticated downloads (only needed for premium addons)
		$access_token = '';
		if (! $addon['free']) {
			$access_token = get_transient('wu-access-token');
			$refresh_token = wu_get_option('wu-refresh-token');

			if (! $access_token && $refresh_token) {
				// Try to refresh the token
				$access_token = $this->refresh_access_token($refresh_token);
			}
		}

		// Use WooCommerce API to get download URL
		$api_client = new \WP_Ultimo\Helpers\WooCommerce_API_Client(
			'https://multisiteultimate.com/',
		);

		$download_url = $api_client->get_download_url($addon_slug, $access_token);

		if (is_wp_error($download_url)) {
			wp_send_json_error($download_url);
		}

		/**
		 * Security check: Ensure URL is from our domain
		 */
		$allowed_sites = array(
			'https://multisiteultimate.com',
		);

		if (defined('WP_DEBUG') && WP_DEBUG) {
			$allowed_sites[] = 'http://localhost';
			$allowed_sites[] = 'https://wp-multisite-waas.test';
		}

		$allowed = false;

		foreach ($allowed_sites as $allowed_site) {
			if (strncmp($download_url, $allowed_site, strlen($allowed_site)) === 0) {
				$allowed = true;
				break;
			}
		}

		if (! $allowed) {
			$error = new \WP_Error('insecure-url', __('You are trying to download an add-on from an insecure URL', 'wp-ultimo'));
			wp_send_json_error($error);
		}

		// Install the plugin
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/misc.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$skin = new \Automatic_Upgrader_Skin(array());
		$upgrader = new \Plugin_Upgrader($skin);

		// Add authentication headers for download
		add_filter('http_request_args', array($this, 'add_auth_headers_to_download'), 10, 2);

		$results = $upgrader->install($download_url);

		remove_filter('http_request_args', array($this, 'add_auth_headers_to_download'), 10);

		if (is_wp_error($results)) {
			wp_send_json_error($results);
		}

		$messages = $upgrader->skin->get_upgrade_messages();

		if (! in_array($upgrader->strings['process_success'], $messages, true)) {
			$error = new \WP_Error('installation-failed', array_pop($messages));
			wp_send_json_error($error);
		}

		wp_send_json_success(
			array(
				'redirect_url' => add_query_arg(
					array(
						's' => rawurlencode((string) $addon['name']),
					),
					network_admin_url('plugins.php')
				),
			)
		);
	}

	/**
	 * Enqueue the necessary scripts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts() {

		wp_enqueue_style('theme');

		wp_register_script('wu-addons', wu_get_asset('addons.js', 'js'), array('jquery', 'wu-vue', 'underscore'), wu_get_version(), true);

		wp_localize_script(
			'wu-addons',
			'wu_addons',
			array(
				'search'   => wu_request('s', ''),
				'category' => wu_request('tab', 'all'),
				'i18n'     => array(
					'all' => __('All Add-ons', 'wp-ultimo'),
				),
			)
		);

		wp_enqueue_script('wu-addons');
	}

	/**
	 * Fetches the list of add-ons available.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	protected function get_addons_list() {
		/*
		 * Checks if we have a cached version.
		 */
		if (is_array($this->addons)) {
			return $this->addons;
		}

		/*
		 * Check for local cache.
		 */
		if (! wu_is_debug()) {
			$addons_list = get_site_transient('wu-addons-list');

			if (is_array($addons_list) && ! empty($addons_list)) {
				$this->addons = $addons_list;

				return $this->addons;
			}
		}

		// Use WooCommerce API to fetch addons (credentials are optional for public data)
		$api_client = new \WP_Ultimo\Helpers\WooCommerce_API_Client(
			MULTISITE_ULTIMATE_UPDATE_URL,
		);

		$data = $api_client->get_addons();

		if (is_wp_error($data)) {
			// translators: %s error message.
			wu_log_add('api-calls', sprintf(__('Failed to fetch addons from API: %s'), $data->get_error_message()));
			return array();
		}

		/*
		 * Process addon data and check installation status
		 */
		foreach ($data as $slug => $item) {
			/*
			 * Checks if the plugin is installed.
			 */
			$item['installed'] = $this->is_plugin_installed($slug);

			$this->addons[ $slug ] = $item;
		}

		set_transient('wu-addons-list', $this->addons, 2 * DAY_IN_SECONDS);

		return $this->addons;
	}

	/**
	 * Checks if a given plugin is installed.
	 *
	 * @since 2.0.0
	 * @param string $plugin_slug The plugin slug to check.
	 * @return boolean
	 */
	public function is_plugin_installed($plugin_slug) {

		$plugin_keys = array_keys(get_plugins());

		$installed_plugins = implode(' - ', $plugin_keys);

		return stristr($installed_plugins, $plugin_slug) !== false;
	}

	/**
	 * Gets the list of addons from the remote server.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function serve_addons_list() {

		$addons_list = $this->get_addons_list();

		wp_send_json_success($addons_list);
	}

	/**
	 * Returns the title of the page.
	 *
	 * @since 2.0.0
	 * @return string Title of the page.
	 */
	public function get_title() {

		return __('Add-ons', 'wp-ultimo');
	} // end get_title;

	/**
	 * Returns the title of menu for this page.
	 *
	 * @since 2.0.0
	 * @return string Menu label of the page.
	 */
	public function get_menu_title() {

		return __('Add-ons', 'wp-ultimo');
	}


	/**
	 * Saves the OAuth access token using the authorization code.
	 *
	 * @param string $code The authorization code received from OAuth provider.
	 * @param string $redirect_url The redirect URL used in the OAuth flow.
	 *
	 * @return void
	 * @throws \Exception When the API request fails.
	 */
	private function save_access_token($code, $redirect_url) {
		$url     = 'https://multisiteultimate.com/oauth/token';
		$data    = array(
			'code'          => $code,
			'redirect_uri'  => $redirect_url,
			'grant_type'    => 'authorization_code',
			'client_id'     => wu_get_option('oauth_client_id', ''),
			'client_secret' => wu_get_option('oauth_client_secret', ''),
		);
		$request = \wp_remote_post(
			$url,
			[
				'body'      => $data,
				'sslverify' => defined('WP_DEBUG') && WP_DEBUG ? false : true,
			]
		);

		$body    = wp_remote_retrieve_body($request);
		$code    = wp_remote_retrieve_response_code($request);
		$message = wp_remote_retrieve_response_message($request);

		if (is_wp_error($request)) {
			throw new \Exception(esc_html($request->get_error_message()), esc_html($request->get_error_code()));
		}

		if (200 === absint($code) && 'OK' === $message) {
			$response = json_decode($body, true);

			set_transient('wu-access-token', $response['access_token'], $response['expires_in']);
			wu_save_option('wu-refresh-token', $response['refresh_token']);
		}
	}

	/**
	 * Adds authentication headers to download requests.
	 *
	 * @param array  $args HTTP request arguments.
	 * @param string $url  The request URL.
	 * @return array Modified arguments.
	 */
	public function add_auth_headers_to_download($args, $url) {
		if (strpos($url, 'multisiteultimate.com') !== false) {
			$access_token = get_transient('wu-access-token');
			if ($access_token) {
				if (! isset($args['headers'])) {
					$args['headers'] = array();
				}
				$args['headers']['Authorization'] = 'Bearer ' . $access_token;
			}
		}
		return $args;
	}
	/**
	 * Every child class should implement the output method to display the contents of the page.
	 *
	 * @since 1.8.2
	 * @return void
	 */
	public function output() {

		$redirect_url = wu_network_admin_url('wp-ultimo-addons');
		$code         = wu_request('code');

		$addon_repo = \WP_Ultimo::get_instance()->get_addon_repository();

		if ( $code) {
			$addon_repo->save_access_token($code, $redirect_url);
		}

		if ( wu_request('logout') && wp_verify_nonce(wu_request('_wpnonce'), 'logout')) {
			$addon_repo->delete_tokens();
		}


		$more_info_url = wu_get_form_url(
			'addon_more_info',
			array(
				'width' => 768,
				'addon' => 'ADDON_SLUG',
			)
		);
		$oauth_url = add_query_arg(
			[
				'response_type' => 'code',
				'client_id'     => wu_get_option('oauth_client_id', ''),
				'redirect_uri'  => $redirect_url,
			],
			'https://multisiteultimate.com/oauth/authorize'
		);

		wu_get_template(
			'base/addons',
			array(
				'screen'               => get_current_screen(),
				'page'                 => $this,
				'classes'              => '',
				'sections'             => $this->get_sections(),
				'current_section'      => $this->get_current_section(),
				'clickable_navigation' => $this->clickable_navigation,
				'more_info_url'        => $more_info_url,
				'oauth_url'            => $oauth_url,
				'logout_url'           => wu_network_admin_url(
					'wp-ultimo-addons',
					[
						'logout'   => 'logout',
						'_wpnonce' => wp_create_nonce('logout'),
					]
				),
				'user'                 => $user ?? false,
			)
		);
	}

	/**
	 * Returns the list of settings sections.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_sections() {

		return array(
			'all'           => array(
				'title' => __('All Add-ons', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-grid',
			),
			'premium'       => array(
				'title' => __('Premium', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-rocket',
			),
			'free'          => array(
				'title' => __('Free', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-pin',
			),
			'gateways'      => array(
				'title' => __('Gateways', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-credit-card',
			),
			'growth'        => array(
				'title' => __('Growth & Scaling', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-line-graph',
			),
			'integrations'  => array(
				'title' => __('Integrations', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-power-plug',
			),
			'customization' => array(
				'title' => __('Customization', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-edit',
			),
			'admin theme'   => array(
				'title' => __('Admin Themes', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-palette',
			),
			'monetization'  => array(
				'title' => __('Monetization', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-credit',
			),
			'migrators'     => array(
				'title' => __('Migrators', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-publish',
			),
			'separator'     => array(
				'separator' => true,
			),
			'marketplace'   => array(
				'title' => __('Marketplace', 'wp-ultimo'),
				'icon'  => 'dashicons-wu-shop',
			),
		);
	}

	/**
	 * Default handler for step submission. Simply redirects to the next step.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function default_handler() {

		// Nonce handled in calling function
		WP_Ultimo()->settings->save_settings($_POST); // phpcs:ignore WordPress.Security.NonceVerification

		wp_safe_redirect(add_query_arg('updated', 1, wu_get_current_url()));

		exit;
	}


	private function get_client_id() {
		if (isset($this->client_id)) {
			return $this->client_id;
		}
		$stuff               = include __DIR__ . '/stuff.php';
		$this->client_id     = $this->decrypt_value($stuff[0]);
		$this->client_secret = $this->decrypt_value($stuff[1]);
		return $this->client_id;
	}

	private function get_client_secret() {
		if (isset($this->client_secret)) {
			return $this->client_secret;
		}
		$stuff               = include __DIR__ . '/stuff.php';
		$this->client_id     = $this->decrypt_value($stuff[0]);
		$this->client_secret = $this->decrypt_value($stuff[1]);
		return $this->client_secret;
	}
}
