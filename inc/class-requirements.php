<?php
/**
 * Check if all the pre-requisites to run WP Multisite WaaS are in place.
 *
 * @package WP_Ultimo
 * @subpackage Requirements
 * @since 2.0.0
 */

namespace WP_Ultimo;

use WP_Ultimo\Loaders\Table_Loader;
use WP_Ultimo\Installers\Core_Installer;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Check if all the pre-requisites to run WP Multisite WaaS are in place.
 *
 * @since 2.0.0
 */
class Requirements {

	/**
	 * Caches the result of the requirement check.
	 *
	 * @since 2.0.0
	 * @var bool
	 */
	public static $met;

	/**
	 * Minimum PHP version required to run WP Multisite WaaS.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public static $php_version = '7.4.1';

	/**
	 * Recommended PHP Version
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public static $php_recommended_version = '7.4.1';

	/**
	 * Minimum WordPress version required to run WP Multisite WaaS.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public static $wp_version = '5.3';

	/**
	 * Recommended WP Version.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public static $wp_recommended_version = '6.4.1';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Check if the minimum pre-requisites to run WP Multisite WaaS are present.
	 *
	 * - Check if the PHP version requirements are met;
	 * - Check if the WordPress version requirements are met;
	 * - Check if the install is a Multisite install;
	 * - Check if WP Multisite WaaS is network active.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public static function met() {

		if (null === self::$met) {
			self::$met = (
				self::check_php_version()
				&& self::check_wp_version()
				&& self::is_multisite()
				&& self::is_network_active()
			);
		}

		return self::$met;
	}

	/**
	 * Checks if we have ran through the setup already.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public static function run_setup() {

		global $wpdb;

		if (self::is_unit_test()) {
			// phpcs:disable

			$tables = wu_array_flatten($wpdb->get_results(sprintf('SHOW TABLES FROM %s;', DB_NAME), ARRAY_N));

			// phpcs:enable

			if (! in_array("{$wpdb->prefix}wu_domain_mappings", $tables, true)) {
				Core_Installer::get_instance()->_install_database_tables();
			}

			return true;
		}

		return get_network_option(null, 'wu_setup_finished', false);
	}

	/**
	 * Checks for a test environment.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public static function is_unit_test() {
		return defined('WP_TESTS_MULTISITE') && WP_TESTS_MULTISITE;
	}

	/**
	 * Check if the PHP version requirements are met
	 *
	 * @since 2.0.0
	 */
	public static function check_php_version(): bool {

		if (version_compare(phpversion(), self::$php_version, '<')) {
			add_action('network_admin_notices', [self::class, 'notice_unsupported_php_version']);

			return false;
		}

		return true;
	}

	/**
	 * Check if the WordPress version requirements are met
	 *
	 * @since 2.0.0
	 */
	public static function check_wp_version(): bool {

		global $wp_version;

		if (version_compare($wp_version, self::$wp_version, '<')) {
			add_action('network_admin_notices', [self::class, 'notice_unsupported_wp_version']);

			return false;
		}

		return true;
	}

	/**
	 * Check Cron Status
	 *
	 * Gets the current cron status by performing a test spawn.
	 * Cached for one hour when all is well.
	 *
	 * Heavily inspired on Astra's test_cron check:
	 *
	 * @see astra/inc/theme-update/class-astra-theme-background-updater.php
	 *
	 * @since 2.0.0
	 * @return false if there is a problem spawning a call to WP-Cron system.
	 */
	public static function check_wp_cron(): bool {

		global $wp_version;

		if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON) {
			return false;
		}

		if (defined('ALTERNATE_WP_CRON') && ALTERNATE_WP_CRON) {
			return false;
		}

		$cached_status = get_site_transient('wp-ultimo-cron-test-ok');

		if ($cached_status) {
			return true;
		}

		$sslverify     = version_compare($wp_version, 4.0, '<');
		$doing_wp_cron = sprintf('%.22F', microtime(true));

		$cron_request = apply_filters(
			'cron_request', // phpcs:ignore
			[
				'url'  => site_url('wp-cron.php?doing_wp_cron=' . $doing_wp_cron),
				'args' => [
					'timeout'   => 3,
					'blocking'  => true,
					'sslverify' => apply_filters('https_local_ssl_verify', $sslverify), // phpcs:ignore
				],
			]
		);

		$result = wp_remote_post($cron_request['url'], $cron_request['args']);

		if (wp_remote_retrieve_response_code($result) >= 300) {
			return false;
		}

		set_transient('wp-ultimo-cron-test-ok', 1, HOUR_IN_SECONDS);

		return true;
	}

	/**
	 * Check if the install is a Multisite install
	 *
	 * @since 2.0.0
	 */
	public static function is_multisite(): bool {

		if (! is_multisite()) {
			add_action('admin_notices', [self::class, 'notice_not_multisite']);

			return false;
		}

		return true;
	}

	/**
	 * Check if WP Multisite WaaS is network active.
	 *
	 * @since 2.0.0
	 */
	public static function is_network_active(): bool {

		/**
		 * Allow for developers to short-circuit this check.
		 *
		 * This is useful when using composer-based and other custom setups,
		 * such as Bedrock, for example, where using plugins as mu-plugins
		 * are the norm.
		 *
		 * @since 2.0.0
		 * @return bool
		 */
		$skip_network_activation_check = apply_filters('wp_ultimo_skip_network_active_check', wu_is_must_use());

		if ($skip_network_activation_check) {
			return true;
		}

		if (! function_exists('is_plugin_active_for_network')) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		if (! is_plugin_active_for_network(WP_ULTIMO_PLUGIN_BASENAME) && ! self::is_unit_test()) {
			add_action('admin_notices', [self::class, 'notice_not_network_active']);

			return false;
		}

		return true;
	}

	/**
	 * Adds a network admin notice about the PHP requirements not being met
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function notice_unsupported_php_version(): void {

		// translators: the %1$s placeholder is the required PHP version, while the %2$s is the current PHP version.
		$message = sprintf(__('WP Multisite WaaS requires at least PHP version %1$s to run. Your current PHP version is <strong>%2$s</strong>. Please, contact your hosting company support to upgrade your PHP version. If you want maximum performance consider upgrading your PHP to version 7.0 or later.', 'wp-ultimo'), self::$php_version, phpversion());

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);
	}

	/**
	 * Adds a network admin notice about the WordPress requirements not being met
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function notice_unsupported_wp_version(): void {

		global $wp_version;

		// translators: the %1$s placeholder is the required WP version, while the %2$s is the current WP version.
		$message = sprintf(__('WP Multisite WaaS requires at least WordPress version %1$s to run. Your current WordPress version is <strong>%2$s</strong>.', 'wp-ultimo'), self::$wp_version, $wp_version);

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);
	}

	/**
	 * Adds a network admin notice about the install not being a multisite install
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function notice_not_multisite(): void {

		$message = __('WP Multisite WaaS requires a multisite install to run properly. To know more about WordPress Networks, visit this link: <a href="https://wordpress.org/support/article/create-a-network/">Create a Network &rarr;</a>', 'wp-ultimo');

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);
	}

	/**
	 * Adds a network admin notice about the WP Multisite WaaS not being network-active
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function notice_not_network_active(): void {

		// translators: %s is a placeholder for the Network Admin plugins page URL.
		$message = sprintf(__('WP Multisite WaaS needs to be network active to run properly. You can "Network Activate" it <a href="%s">here</a>', 'wp-ultimo'), network_admin_url('plugins.php'));

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);
	}
}
