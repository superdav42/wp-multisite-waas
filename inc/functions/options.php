<?php
/**
 * Options Functions
 *
 * @package WP_Ultimo\Functions
 * @since   2.0.11
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Get the value of a slugfied network option
 *
 * @param string $option_name Option name.
 * @param mixed  $default_value The default value.
 *
 * @return mixed
 */
function wu_get_option($option_name = 'settings', $default_value = []) {

	$option_value = get_network_option(null, wu_slugify($option_name), $default_value);

	return apply_filters('wu_get_option', $option_value, $option_name, $default_value);
}

/**
 * Save slugfied network option
 *
 * @since 1.9.6
 * @param string $option_name The option name to save.
 * @param mixed  $value       The new value of the option.
 * @return boolean
 */
function wu_save_option($option_name = 'settings', $value = false) {

	return update_network_option(null, wu_slugify($option_name), $value);
}

/**
 * Delete slugfied network option
 *
 * @since 1.9.6
 * @param string $option_name The option name to delete.
 * @return boolean
 */
function wu_delete_option($option_name) {

	return delete_network_option(null, wu_slugify($option_name));
}
