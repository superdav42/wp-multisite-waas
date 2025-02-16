<?php
/**
 * WP Multisite WaaS 1.X to 2.X migrator.
 *
 * @package WP_Ultimo
 * @subpackage Installers/Core_Installer
 * @since 2.0.0
 */

namespace WP_Ultimo\Installers;

use WP_Ultimo\Integrations\Host_Providers\Closte_Host_Provider;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Multisite WaaS 1.X to 2.X migrator.
 *
 * @since 2.0.0
 */
class Core_Installer extends Base_Installer {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Init hooks to handle edge cases such as Closte.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init(): void {

		add_filter(
			'wu_core_installer_install_sunrise',
			function () {

				$is_closte = defined('CLOSTE_CLIENT_API_KEY') && CLOSTE_CLIENT_API_KEY;

				if ($is_closte) {
					if ( ! (defined('SUNRISE') && SUNRISE)) {

						// translators: %s is a URL to a documentation link.
						$closte_message = sprintf(__('You are using Closte and they prevent the wp-config.php file from being written to. <a href="%s" target="_blank">Follow these instructions to do it manually</a>.', 'wp-multisite-waas'), wu_get_documentation_url('wp-ultimo-closte-config'));

						throw new \Exception($closte_message);
					}

					return true;
				}

				return false;
			}
		);
	}

	/**
	 * Returns the list of migration steps.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_steps() {

		$has_tables_installed = \WP_Ultimo\Loaders\Table_Loader::get_instance()->is_installed();

		$steps = [];

		$steps['database_tables'] = [
			'done'        => $has_tables_installed,
			'title'       => __('Create Database Tables', 'wp-multisite-waas'),
			'description' => __('WP Multisite WaaS uses custom tables for performance reasons. We need to create those tables and make sure they are setup properly before we can activate the plugin.', 'wp-multisite-waas'),
			'pending'     => __('Pending', 'wp-multisite-waas'),
			'installing'  => __('Creating default tables...', 'wp-multisite-waas'),
			'success'     => __('Success!', 'wp-multisite-waas'),
			'help'        => wu_get_documentation_url('installation-errors'),
		];

		$steps['sunrise'] = [
			'done'        => defined('SUNRISE') && SUNRISE && defined('WP_ULTIMO_SUNRISE_VERSION'),
			'title'       => __('Install <code>sunrise.php</code> File', 'wp-multisite-waas'),
			'description' => __('We need to add our own sunrise.php file to the wp-content folder in order to be able to control access to sites and plugins before anything else happens on WordPress. ', 'wp-multisite-waas'),
			'pending'     => __('Pending', 'wp-multisite-waas'),
			'installing'  => __('Installing sunrise file...', 'wp-multisite-waas'),
			'success'     => __('Success!', 'wp-multisite-waas'),
			'help'        => wu_get_documentation_url('installation-errors'),
		];

		return $steps;
	}

	/**
	 * Installs our custom database tables.
	 *
	 * @since 2.0.0
	 * @throws \Exception When an error occurs during the creation.
	 * @return void
	 */
	public function _install_database_tables(): void {

		$tables = \WP_Ultimo\Loaders\Table_Loader::get_instance()->get_tables();

		foreach ($tables as $table_name => $table) {

			// Exclude native WP tables, as they already exist.
			$exclude_list = [
				'site_table',
				'sitemeta_table',
			];

			if (in_array($table_name, $exclude_list, true)) {
				continue;
			}

			$success = $table->install();

			if (false === $success) {

				// translators: %s is the name of a database table, e.g. wu_memberships.
				$error_message = sprintf(__('Installation of the table %s failed', 'wp-multisite-waas'), $table->get_name());

				throw new \Exception($error_message);
			}
		}
	}

	/**
	 * Copies the sunrise.php file and adds the SUNRISE constant.
	 *
	 * @since 2.0.0
	 * @throws \Exception When sunrise copying fails.
	 * @return void
	 */
	public function _install_sunrise(): void {

		$copy = \WP_Ultimo\Sunrise::try_upgrade();

		if (is_wp_error($copy)) {
			throw new \Exception($copy->get_error_message());
		}

		/**
		 * Allow host providers to install the constant differently.
		 *
		 * Returning true will prevent WP Multisite WaaS from trying to write to the wp-config file.
		 *
		 * @since 2.0.0
		 * @param bool $short_circuit
		 */
		$short_circuit = apply_filters('wu_core_installer_install_sunrise', false);

		if ($short_circuit) {
			return;
		}

		$success = \WP_Ultimo\Helpers\WP_Config::get_instance()->inject_wp_config_constant('SUNRISE', true);

		if (is_wp_error($success)) {
			throw new \Exception($success->get_error_message());
		}
	}
}
