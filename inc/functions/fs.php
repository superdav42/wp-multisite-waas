<?php
/**
 * File System Functions
 *
 * @package WP_Ultimo\Functions
 * @since   2.0.11
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Returns the main site uploads dir array from WordPress.
 *
 * @since 2.0.11
 * @return array
 */
function wu_get_main_site_upload_dir() {

	// Check for cached value
	$cached_uploads = wp_cache_get('wu_main_site_upload_dir', 'wu_filesystem');

	if (false !== $cached_uploads) {
		return $cached_uploads;
	}

	global $current_site;

	is_multisite() && switch_to_blog($current_site->blog_id);

	if ( ! defined('WP_CONTENT_URL')) {
		define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	}

	$uploads = wp_upload_dir(null, false);

	is_multisite() && restore_current_blog();

	// Cache the result for 1 hour - this rarely changes
	wp_cache_set('wu_main_site_upload_dir', $uploads, 'wu_filesystem', HOUR_IN_SECONDS);

	return $uploads;
}

/**
 * Creates a WP Multisite WaaS folder inside the uploads folder - if needed - and return its path.
 *
 * @since 2.0.11
 *
 * @param string $folder Name of the folder.
 * @param string ...$path Additional path segments to be attached to the folder path.
 * @return string The path to the folder
 */
function wu_maybe_create_folder($folder, ...$path) {

	// Generate a cache key for this folder path
	$cache_key = 'wu_folder_path_' . md5($folder . implode('/', $path));

	// Check for cached path
	$cached_path = wp_cache_get($cache_key, 'wu_filesystem');

	if (false !== $cached_path) {
		return $cached_path;
	}

	$uploads = wu_get_main_site_upload_dir();

	$folder_path = trailingslashit($uploads['basedir'] . '/' . $folder);

	// Use WordPress Filesystem API for better compatibility
	global $wp_filesystem;

	// Initialize the WP filesystem if needed
	if (empty($wp_filesystem)) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	/*
	 * Checks if the folder exists.
	 */
	if (!$wp_filesystem->exists($folder_path)) {

		// Creates the Folder
		wp_mkdir_p($folder_path);

		// Creates htaccess
		$htaccess = $folder_path . '.htaccess';

		if (!$wp_filesystem->exists($htaccess)) {
			$wp_filesystem->put_contents($htaccess, 'deny from all', FS_CHMOD_FILE);
		}

		// Creates index
		$index = $folder_path . 'index.html';

		if (!$wp_filesystem->exists($index)) {
			$wp_filesystem->put_contents($index, '', FS_CHMOD_FILE);
		}
	}

	$full_path = $folder_path . implode('/', $path);

	// Cache the result for 1 hour
	wp_cache_set($cache_key, $full_path, 'wu_filesystem', HOUR_IN_SECONDS);

	return $full_path;
}

/**
 * Gets the URL for the folders created with maybe_create_folder().
 *
 * @see wu_maybe_create_folder()
 * @since 2.0.0
 *
 * @param string $folder The name of the folder.
 * @return string
 */
function wu_get_folder_url($folder) {

	// Generate a cache key for this folder URL
	$cache_key = 'wu_folder_url_' . md5($folder);

	// Check for cached URL
	$cached_url = wp_cache_get($cache_key, 'wu_filesystem');

	if (false !== $cached_url) {
		return $cached_url;
	}

	$uploads = wu_get_main_site_upload_dir();

	$folder_url = trailingslashit($uploads['baseurl'] . '/' . $folder);

	$url = set_url_scheme($folder_url);

	// Cache the result for 1 hour
	wp_cache_set($cache_key, $url, 'wu_filesystem', HOUR_IN_SECONDS);

	return $url;
}

/**
 * Clears the filesystem cache.
 *
 * @since 2.0.0
 *
 * @param string $folder Optional. If provided, only clears cache for this folder.
 * @return void
 */
function wu_clear_filesystem_cache($folder = '') {

	// Clear main site upload dir cache
	wp_cache_delete('wu_main_site_upload_dir', 'wu_filesystem');

	// If a specific folder is provided, only clear that folder's cache
	if (!empty($folder)) {
		$folder_path_key = 'wu_folder_path_' . md5($folder);
		$folder_url_key = 'wu_folder_url_' . md5($folder);

		wp_cache_delete($folder_path_key, 'wu_filesystem');
		wp_cache_delete($folder_url_key, 'wu_filesystem');
	}
}
