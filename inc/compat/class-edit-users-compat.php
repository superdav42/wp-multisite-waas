<?php
/**
 * Adds support to for site owners to edit user accounts for users on their site.
 *
 * WordPress, even in multisite mode, has only one User database table.
 * This can cause problems in a WaaS environment.
 *
 * A site owner with administrator role wants to edit the display name of a subscriber.
 * In the default Multisite only super admins can edit user accounts.
 * This makes it possible for Admins to edit users in their own sites.
 *
 * @package WP_Ultimo
 * @subpackage Compat/Edit_Users_Compat
 * @since 2.4.4
 */

namespace WP_Ultimo\Compat;

class Edit_Users_Compat {

	use \WP_Ultimo\Traits\Singleton;

	public function init(): void {
		// Add the settings to enable or disable this feature.
		add_action('wu_settings_login', [$this, 'add_settings'], 10);

		if ($this->should_load()) {
			// Apply the update_users_caps function to the 'map_meta_cap' filter.
			add_filter('map_meta_cap', [$this, 'update_users_caps'], 1, 4);

			// Add a filter to enable editing any user configuration.
			add_filter('enable_edit_any_user_configuration', '__return_true', 15);
		}
	}

	/**
	 * Allows subsite administrators to edit users in a WordPress Multisite network.
	 *
	 * In a WordPress 3.x Network, the Super Admin role is the only role allowed to edit users.
	 *
	 * @param array  $caps    The user's capabilities.
	 * @param string $cap     The capability being checked.
	 * @param int    $user_id The user ID.
	 * @param mixed  $args    Additional arguments.
	 *
	 * @return array Modified user capabilities.
	 */
	function update_users_caps($caps, $cap, $user_id, $args) {
		foreach ($caps as $key => $capability) {
			if ('do_not_allow' !== $capability) {
				continue;
			}

			switch ( $cap ) {
				case 'edit_user':
					// Editing a user profile.
					if (empty($args[0]) || is_super_admin($args[0])) {
						// Trying to edit a super admin while not being a super admin.
						$caps[] = 'do_not_allow';
					} elseif ( ! is_user_member_of_blog($args[0], get_current_blog_id()) || ! is_user_member_of_blog($user_id, get_current_blog_id())) {
						// Editing user and edited user aren't members of the same blog.
						$caps[] = 'do_not_allow';
					} else {
						$caps[ $key ] = 'edit_users';
					}

					break;
				case 'edit_users':
					$caps[ $key ] = 'edit_users';
					break;
				case 'delete_user':
				case 'delete_users':
					$caps[ $key ] = 'delete_users';
					break;
				case 'create_users':
					$caps[ $key ] = $cap;
					break;
			}
		}

		return $caps;
	}

	/**
	 * Allow plugin developers to disable this functionality to prevent compatibility issues.
	 *
	 * @since 2.0.0
	 *
	 * @return boolean
	 */
	public function should_load() {

		return apply_filters('wu_should_load_edit_user_support', wu_get_setting('enable_edit_users', false));
	}

	/**
	 * Add edit user setting to enable or disable this feature.
	 *
	 * @since 2.0.0
	 *
	 * @return void.
	 */
	public function add_settings(): void {

		wu_register_settings_field(
			'login-and-registration',
			'enable_edit_users',
			[
				'title'   => __('Enable Edit User Capability', 'multisite-ultimate'),
				'desc'    => __('Allow site owners to edit the user accounts of users on their own site.', 'multisite-ultimate'),
				'type'    => 'toggle',
				'default' => 0,
			]
		);
	}
}
