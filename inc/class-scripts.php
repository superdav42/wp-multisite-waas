<?php
/**
 * Multisite Ultimate helper class to handle global registering of scripts and styles.
 *
 * @package WP_Ultimo
 * @subpackage Scripts
 * @since 2.0.0
 */

namespace WP_Ultimo;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Multisite Ultimate helper class to handle global registering of scripts and styles.
 *
 * @since 2.0.0
 */
class Scripts {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Runs when the instantiation first occurs.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init(): void {

		add_action('init', [$this, 'register_default_scripts']);

		add_action('init', [$this, 'register_default_styles']);

		add_action('admin_init', [$this, 'enqueue_default_admin_styles']);

		add_action('admin_init', [$this, 'enqueue_default_admin_scripts']);

		add_action('wp_ajax_wu_toggle_container', [$this, 'update_use_container']);

		add_filter('admin_body_class', [$this, 'add_body_class_container_boxed']);
	}

	/**
	 * Wrapper for the register scripts function.
	 *
	 * @since 2.0.0
	 *
	 * @param string     $handle The script handle. Used to enqueue the script.
	 * @param string     $src URL to the file.
	 * @param array      $deps List of dependency scripts.
	 * @param array|bool $args     {
	 *     Optional. An array of additional script loading strategies. Default empty array.
	 *     Otherwise, it may be a boolean in which case it determines whether the script is printed in the footer. Default false.
	 *
	 *     @type string    $strategy     Optional. If provided, may be either 'defer' or 'async'.
	 *     @type bool      $in_footer    Optional. Whether to print the script in the footer. Default 'false'.
	 * }
	 * @return void
	 */
	public function register_script($handle, $src, $deps = [], $args = [
		'in_footer' => true,
	]): void {

		wp_register_script($handle, $src, $deps, \WP_Ultimo::VERSION, $args);
	}

	/**
	 * Wrapper for the register scripts module function.
	 *
	 * @since 2.4.1
	 *
	 * @param string $id The script handle. Used to enqueue the script.
	 * @param string $src URL to the file.
	 * @param array  $deps List of dependency scripts.
	 * @return void
	 */
	public function register_script_module($id, $src, $deps = []): void {
		// This method was added in WP 6.5. We're only using modules as a progressive enhancement so we don't need to add a workaround.
		if (function_exists('wp_register_script_module')) {
			wp_register_script_module($id, $src, $deps, \WP_Ultimo::VERSION);
		}
	}

	/**
	 * Wrapper for the register styles function.
	 *
	 * @since 2.0.0
	 *
	 * @param string $handle The script handle. Used to enqueue the script.
	 * @param string $src URL to the file.
	 * @param array  $deps List of dependency scripts.
	 * @return void
	 */
	public function register_style($handle, $src, $deps = []): void {

		wp_register_style($handle, $src, $deps, \WP_Ultimo::VERSION);
	}

	/**
	 * Registers the default Multisite Ultimate scripts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_default_scripts(): void {
		/*
		 * Adds Vue JS
		 */
		$this->register_script('wu-vue', wu_get_asset('lib/vue.js', 'js'));

		/*
		 * Adds Sweet Alert
		 */
		$this->register_script('wu-sweet-alert', wu_get_asset('lib/sweetalert2.all.js', 'js'));

		/*
		 * Adds Flat Picker
		 */
		$this->register_script('wu-flatpicker', wu_get_asset('lib/flatpicker.js', 'js'));

		/*
		 * Adds tipTip
		 */
		$this->register_script('wu-tiptip', wu_get_asset('lib/tiptip.js', 'js'), ['jquery-core']);

		/*
		 * Ajax list Table pagination
		 */
		$this->register_script('wu-ajax-list-table', wu_get_asset('list-tables.js', 'js'), ['jquery', 'wu-vue', 'underscore', 'wu-flatpicker']);

		/*
		 * Adds jQueryBlockUI
		 */
		$this->register_script('wu-block-ui', wu_get_asset('lib/jquery.blockUI.js', 'js'), ['jquery-core']);

		/*
		 * Adds FontIconPicker
		 */
		$this->register_script('wu-fonticonpicker', wu_get_asset('lib/jquery.fonticonpicker.js', 'js'), ['jquery']);

		/*
		 * Adds Accounting.js
		 */
		$this->register_script('wu-accounting', wu_get_asset('lib/accounting.js', 'js'), ['jquery-core']);

		/*
		 * Adds Cookie Helpers
		 */
		$this->register_script('wu-cookie-helpers', wu_get_asset('cookie-helpers.js', 'js'), ['jquery-core']);

		/*
		 * Adds Input Masking
		 */
		$this->register_script('wu-money-mask', wu_get_asset('lib/v-money.js', 'js'), ['wu-vue']);
		$this->register_script('wu-input-mask', wu_get_asset('lib/vue-the-mask.js', 'js'), ['wu-vue']);

		/*
		 * Adds General Functions
		 */
		$this->register_script('wu-functions', wu_get_asset('functions.js', 'js'), ['jquery-core', 'wu-tiptip', 'wu-flatpicker', 'wu-block-ui', 'wu-accounting', 'clipboard', 'wp-hooks']);

		wp_localize_script(
			'wu-functions',
			'wu_settings',
			[
				'currency'           => wu_get_setting('currency_symbol', 'USD'),
				'currency_symbol'    => wu_get_currency_symbol(),
				'currency_position'  => wu_get_setting('currency_position', '%s %v'),
				'decimal_separator'  => wu_get_setting('decimal_separator', '.'),
				'thousand_separator' => wu_get_setting('thousand_separator', ','),
				'precision'          => wu_get_setting('precision', 2),
				'use_container'      => get_user_setting('wu_use_container', false),
				'disable_image_zoom' => wu_get_setting('disable_image_zoom', false),
			]
		);

		/*
		 * Adds Fields & Components
		 */
		$this->register_script(
			'wu-fields',
			wu_get_asset('fields.js', 'js'),
			['jquery', 'wu-vue', 'wu-selectizer', 'wp-color-picker']
		);

		/*
		 * Localize components
		 */
		wp_localize_script(
			'wu-fields',
			'wu_fields',
			[
				'l10n' => [
					'image_picker_title'       => __('Select an Image.', 'multisite-ultimate'),
					'image_picker_button_text' => __('Use this image', 'multisite-ultimate'),
				],
			]
		);

		/*
		 * Adds Admin Script
		 */
		$this->register_script('wu-admin', wu_get_asset('admin.js', 'js'), ['jquery', 'wu-functions']);

		/*
		 * Adds Vue Apps
		 */
		$this->register_script('wu-vue-apps', wu_get_asset('vue-apps.js', 'js'), ['wu-functions', 'wu-vue', 'wu-money-mask', 'wu-input-mask', 'wp-hooks']);
		$this->register_script('wu-vue-sortable', wu_get_asset('lib/sortablejs.js', 'js'), []);
		$this->register_script('wu-vue-draggable', wu_get_asset('lib/vue-draggable.js', 'js'), ['wu-vue-sortable']);

		/*
		 * Adds Selectizer
		 */
		$this->register_script('wu-selectize', wu_get_asset('lib/selectize.js', 'js'), ['jquery']);
		$this->register_script('wu-selectizer', wu_get_asset('selectizer.js', 'js'), ['wu-selectize', 'underscore', 'wu-vue-apps']);

		/*
		 * Localize selectizer
		 */
		wp_localize_script(
			'wu-functions',
			'wu_selectizer',
			[
				'ajaxurl' => wu_ajax_url(),
			]
		);

		/*
		 * Load variables to localized it
		 */
		wp_localize_script(
			'wu-functions',
			'wu_ticker',
			[
			'server_clock_offset'          => (wu_get_current_time('timestamp') - time()) / 60 / 60, // phpcs:ignore
			'moment_clock_timezone_name'   => wp_date('e'),
			'moment_clock_timezone_offset' => wp_date('Z'),
			]
		);

		/*
		 * Adds our thickbox fork.
		 */
		$this->register_script('wubox', wu_get_asset('wubox.js', 'js'), ['wu-vue-apps']);

		wp_localize_script(
			'wubox',
			'wuboxL10n',
			[
				'next'             => __('Next &gt;'), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				'prev'             => __('&lt; Prev'), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				'image'            => __('Image'), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				'of'               => __('of'), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				'close'            => __('Close'), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				'noiframes'        => __('This feature requires inline frames. You have iframes disabled or your browser does not support them.'), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				'loadingAnimation' => includes_url('js/thickbox/loadingAnimation.gif'),
			]
		);

		wp_register_script_module(
			'wu-flags-polyfill',
			wu_get_asset('flags.js', 'js'),
			array(),
			\WP_Ultimo::VERSION
		);

		/*
		 * WordPress localizes month names and all, but
		 * does not localize anything else. We need relative
		 * times to be translated, so we need to do it ourselves.
		 */
		$this->localize_moment();
	}

	/**
	 * Localize moment.js relative times.
	 *
	 * @since 2.0.8
	 * @return bool
	 */
	public function localize_moment() {

		$time_format = get_option('time_format', __('g:i a')); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		$date_format = get_option('date_format', __('F j, Y')); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain

		$long_date_formats = array_map(
			'wu_convert_php_date_format_to_moment_js_format',
			[
				'LT'   => $time_format,
				'LTS'  => str_replace(':i', ':i:s', (string) $time_format),
				/* translators: the day/month/year date format used by Multisite Ultimate. You can changed it to localize this date format to your language. the default value is d/m/Y, which is the format 31/12/2021. */
				'L'    => __('d/m/Y', 'multisite-ultimate'),
				'LL'   => $date_format,
				'LLL'  => sprintf('%s %s', $date_format, $time_format),
				'LLLL' => sprintf('%s %s', $date_format, $time_format),
			]
		);

		$strings = [
			'relativeTime'   => [
				// translators: %s is a relative future date.
				'future' => __('in %s', 'multisite-ultimate'),
				// translators: %s is a relative past date.
				'past'   => __('%s ago', 'multisite-ultimate'),
				's'      => __('a few seconds', 'multisite-ultimate'),
				// translators: %s is the number of seconds.
				'ss'     => __('%d seconds', 'multisite-ultimate'),
				'm'      => __('a minute', 'multisite-ultimate'),
				// translators: %s is the number of minutes.
				'mm'     => __('%d minutes', 'multisite-ultimate'),
				'h'      => __('an hour', 'multisite-ultimate'),
				// translators: %s is the number of hours.
				'hh'     => __('%d hours', 'multisite-ultimate'),
				'd'      => __('a day', 'multisite-ultimate'),
				// translators: %s is the number of days.
				'dd'     => __('%d days', 'multisite-ultimate'),
				'w'      => __('a week', 'multisite-ultimate'),
				// translators: %s is the number of weeks.
				'ww'     => __('%d weeks', 'multisite-ultimate'),
				'M'      => __('a month', 'multisite-ultimate'),
				// translators: %s is the number of months.
				'MM'     => __('%d months', 'multisite-ultimate'),
				'y'      => __('a year', 'multisite-ultimate'),
				// translators: %s is the number of years.
				'yy'     => __('%d years', 'multisite-ultimate'),
			],
			'longDateFormat' => $long_date_formats,
		];

		$inline_script = sprintf("moment.updateLocale( '%s', %s );", get_user_locale(), wp_json_encode($strings));

		return did_action('init') && wp_add_inline_script('moment', $inline_script, 'after');
	}

	/**
	 * Registers the default Multisite Ultimate styles.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_default_styles(): void {

		$this->register_style('wu-styling', wu_get_asset('framework.css', 'css'), []);

		$this->register_style('wu-admin', wu_get_asset('admin.css', 'css'), ['wu-styling']);

		$this->register_style('wu-checkout', wu_get_asset('checkout.css', 'css'), []);

		$this->register_style('wu-flags', wu_get_asset('flags.css', 'css'), []);
	}

	/**
	 * Loads the default admin styles.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_default_admin_styles(): void {

		// Get current screen to conditionally load styles
		$screen = get_current_screen();

		if (!$screen) {
			return;
		}

		// Only load styles on WP Ultimo admin pages
		if ($this->is_wp_ultimo_admin_page($screen)) {
			wp_enqueue_style('wu-admin');

			// Load flag styles only when needed (e.g., on pages with country selection)
			if ($this->is_wp_ultimo_flags_page($screen)) {
				wp_enqueue_style('wu-flags');
			}
		}

		// Load checkout styles only on checkout pages
		if (isset($_GET['page']) && strpos($_GET['page'], 'wu-checkout') === 0) {
			wp_enqueue_style('wu-checkout');
		}
	}

	/**
	 * Loads the default admin scripts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_default_admin_scripts(): void {

		// Get current screen to conditionally load scripts
		$screen = get_current_screen();

		if (!$screen) {
			return;
		}

		// Base admin script for all WP Ultimo admin pages
		if ($this->is_wp_ultimo_admin_page($screen)) {
			wp_enqueue_script('wu-admin');
		}

		// Only load these scripts on specific pages where they're needed
		if ($this->is_wp_ultimo_list_page($screen)) {
			wp_enqueue_script('wu-ajax-list-table');
		}

		// Load Vue apps only on pages that need them
		if ($this->is_wp_ultimo_vue_page($screen)) {
			wp_enqueue_script('wu-vue-apps');
		}

		// Load wubox only when needed
		if ($this->is_wp_ultimo_modal_page($screen)) {
			wp_enqueue_script('wubox');
		}
	}

	/**
	 * Update the use container setting.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function update_use_container(): void {

		check_ajax_referer('wu_toggle_container', 'nonce');

		$new_value = (bool) ! (get_user_setting('wu_use_container', false));

		set_user_setting('wu_use_container', $new_value);

		wp_die();
	}

	/**
	 * Add body classes of container boxed if user has setting.
	 *
	 * @since 2.0.0
	 *
	 * @param string $classes Body classes.
	 * @return string
	 */
	public function add_body_class_container_boxed($classes) {

		if (get_user_setting('wu_use_container', false)) {
			$classes .= ' has-wu-container ';
		}

		return $classes;
	}

	/**
	 * Checks if the current screen is a WP Ultimo admin page.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Screen $screen The current screen object.
	 * @return bool
	 */
	private function is_wp_ultimo_admin_page($screen) {
		// Check if the screen ID contains 'wp-ultimo' or if it's a WP Ultimo admin page
		return (
			strpos($screen->id, 'wp-ultimo') !== false ||
			strpos($screen->id, 'wu-') !== false ||
			(isset($_GET['page']) && strpos($_GET['page'], 'wu-') === 0)
		);
	}

	/**
	 * Checks if the current screen is a WP Ultimo list page that needs the list table scripts.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Screen $screen The current screen object.
	 * @return bool
	 */
	private function is_wp_ultimo_list_page($screen) {
		// Pages that use list tables
		$list_pages = array(
			'wu-memberships',
			'wu-products',
			'wu-customers',
			'wu-payments',
			'wu-discount-codes',
			'wu-domain-mappings',
			'wu-sites',
		);

		return $this->is_wp_ultimo_admin_page($screen) &&
			   isset($_GET['page']) &&
			   in_array($_GET['page'], $list_pages);
	}

	/**
	 * Checks if the current screen is a WP Ultimo page that needs Vue.js.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Screen $screen The current screen object.
	 * @return bool
	 */
	private function is_wp_ultimo_vue_page($screen) {
		// Pages that use Vue.js
		$vue_pages = array(
			'wu-settings',
			'wu-add-membership',
			'wu-edit-membership',
			'wu-add-product',
			'wu-edit-product',
			'wu-add-customer',
			'wu-edit-customer',
			'wu-add-payment',
			'wu-edit-payment',
			'wu-add-discount-code',
			'wu-edit-discount-code',
			'wu-add-domain-mapping',
			'wu-edit-domain-mapping',
			'wu-add-site',
			'wu-edit-site',
			'wu-checkout-form',
		);

		return $this->is_wp_ultimo_admin_page($screen) &&
			   isset($_GET['page']) &&
			   (in_array($_GET['page'], $vue_pages) || strpos($_GET['page'], 'wu-checkout') === 0);
	}

	/**
	 * Checks if the current screen is a WP Ultimo page that needs modal functionality.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Screen $screen The current screen object.
	 * @return bool
	 */
	private function is_wp_ultimo_modal_page($screen) {
		// Most WP Ultimo admin pages need the modal functionality
		return $this->is_wp_ultimo_admin_page($screen);
	}

	/**
	 * Checks if the current screen is a WP Ultimo page that needs country flags.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Screen $screen The current screen object.
	 * @return bool
	 */
	private function is_wp_ultimo_flags_page($screen) {
		// Pages that use country flags
		$flags_pages = array(
			'wu-settings',
			'wu-add-customer',
			'wu-edit-customer',
			'wu-add-payment',
			'wu-edit-payment',
		);

		return $this->is_wp_ultimo_admin_page($screen) &&
			   isset($_GET['page']) &&
			   in_array($_GET['page'], $flags_pages);
	}
}
