<?php
/**
 * Plugins Limit Module.
 *
 * @package WP_Ultimo
 * @subpackage Objects
 * @since 2.0.0
 */

namespace WP_Ultimo\Limitations;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Plugins Limit Module.
 *
 * @since 2.0.0
 */
class Limit_Plugins extends Limit {

	/**
	 * The module id.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $id = 'plugins';

	/**
	 * The check method is what gets called when allowed is called.
	 *
	 * Each module needs to implement a check method, that returns a boolean.
	 * This check can take any form the developer wants.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed  $value_to_check Value to check.
	 * @param mixed  $limit The list of limits in this modules.
	 * @param string $type Type for sub-checking.
	 * @return bool
	 */
	public function check($value_to_check, $limit, $type = '') {

		$plugin = (object) $this->{$value_to_check};

		$types = [
			'visible'               => 'visible' === $plugin->visibility,
			'hidden'                => 'hidden' === $plugin->visibility,
			'default'               => 'default' === $plugin->behavior,
			'force_active'          => 'force_active' === $plugin->behavior,
			'force_inactive'        => 'force_inactive' === $plugin->behavior,
			'force_active_locked'   => 'force_active_locked' === $plugin->behavior,
			'force_inactive_locked' => 'force_inactive_locked' === $plugin->behavior,
		];

		return wu_get_isset($types, $type, false);
	}

	/**
	 * Adds a magic getter for plugins.
	 *
	 * @since 2.0.0
	 *
	 * @param string $plugin_name The plugin name.
	 * @return object
	 */
	public function __get($plugin_name) {

		$plugin = (object) wu_get_isset($this->get_limit(), $plugin_name, $this->get_default_permissions($plugin_name));

		return (object) wp_parse_args($plugin, $this->get_default_permissions($plugin_name));
	}

	/**
	 * Returns a list of plugins by behavior and visibility.
	 *
	 * @since 2.0.0
	 *
	 * @param null|string $behavior The behaviour to search for.
	 * @param null|string $visibility The visibility to search for.
	 * @return array
	 */
	public function get_by_type($behavior = null, $visibility = null) {

		$search_params = [];

		if ($behavior) {
			$search_params[] = ['behavior', $behavior];
		}

		if ($visibility) {
			$search_params[] = ['visibility', $visibility];
		}

		$results = \Arrch\Arrch::find(
			(array) $this->get_limit(),
			[
				'where' => $search_params,
			]
		);

		return $results;
	}

	/**
	 * Returns default permissions.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type Type for sub-checking.
	 * @return array
	 */
	public function get_default_permissions($type) {

		return [
			'visibility' => 'visible',
			'behavior'   => 'default',
		];
	}

	/**
	 * Checks if a theme exists on the current module.
	 *
	 * @since 2.0.0
	 *
	 * @param string $plugin_name The theme name.
	 * @return bool
	 */
	public function exists($plugin_name) {

		$results = wu_get_isset($this->get_limit(), $plugin_name, []);

		return wu_get_isset($results, 'visibility', 'not-set') !== 'not-set' || wu_get_isset($results, 'behavior', 'not-set') !== 'not-set';
	}

	/**
	 * Checks if the module is enabled.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type Type for sub-checking.
	 * @return boolean
	 */
	public function is_enabled($type = '') {

		return true;
	}
}
