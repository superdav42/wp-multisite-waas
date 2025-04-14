<?php
/**
 * Plugin Name: WP Multisite WaaS
 * Description: The WordPress Multisite Website as a Service (WaaS) plugin.
 * Plugin URI: https://wpmultisitewaas.org
 * Text Domain: wp-multisite-waas
 * Version: 2.4.0
 * Author: WP Multisite Community
 * Author URI: https://github.com/superdav42/wp-multisite-waas
 * GitHub Plugin URI: https://github.com/superdav42/wp-multisite-waas
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 * Requires at least: 5.3
 * Requires PHP: 7.4.30
 *
 * WP Multisite WaaS is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Multisite WaaS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Multisite WaaS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque and NextPress and WP Multisite Community
 * @category Core
 * @package  WP_Ultimo
 * @version  2.4.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if ( defined('WP_SANDBOX_SCRAPING') && WP_SANDBOX_SCRAPING ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active('wp-ultimo/wp-ultimo.php') ) {
		// old plugin still installed and active with the old name and path
		// and the user is trying to activate this plugin. So deactivate and return.
		deactivate_plugins('wp-ultimo/wp-ultimo.php', true, true);

		if ( file_exists(WP_CONTENT_DIR . '/sunrise.php')) {
			// We must override the old sunrise file or more name conflicts will occur.
			copy(__DIR__ . '/sunrise.php', WP_CONTENT_DIR . '/sunrise.php');
		}
		return;
	}
}

if ( ! defined('WP_ULTIMO_PLUGIN_FILE')) {
	define('WP_ULTIMO_PLUGIN_FILE', __FILE__);
}

/**
 * Require core file dependencies
 */
require_once __DIR__ . '/constants.php';

require_once __DIR__ . '/vendor/autoload_packages.php';

require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';

require_once __DIR__ . '/inc/traits/trait-singleton.php';

/**
 * Setup activation/deactivation hooks
 */
WP_Ultimo\Hooks::init();

if ( ! function_exists('WP_Ultimo')) {
	/**
	 * Initializes the WP Ultimo class
	 *
	 * This function returns the WP_Ultimo class singleton, and
	 * should be used to avoid declaring globals.
	 *
	 * @return WP_Ultimo
	 * @since 2.0.0
	 */
	function WP_Ultimo() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return WP_Ultimo::get_instance();
	}
}
// Initialize and set to global for back-compat
$GLOBALS['WP_Ultimo'] = WP_Ultimo();
