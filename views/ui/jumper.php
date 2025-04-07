<?php
/**
 * Displays the Jumper UI.
 *
 * @package WP_Ultimo/Views
 * @subpackage Jumper
 * @since 2.0.0
 */

?>
<div id="wu-jumper" style="display: none;" class="wu-styling">

	<div class="wu-jumper-icon-container wu-relative wu-w-full wu-bg-gray-100 wu-rounded">

	<select id="wu-jumper-select" data-placeholder="<?php esc_attr_e('Search Anything...', 'wp-multisite-waas'); ?>">

		<option></option>

		<?php if ( ! count($menu_groups)) : ?>

		<option></option>

		<optgroup label="<?php esc_attr_e('Error', 'wp-multisite-waas'); ?>">

			<option value="<?php echo network_admin_url('?wu-rebuild-jumper=1'); ?>">

			<?php esc_html_e('Click to rebuild menu list', 'wp-multisite-waas'); ?>

			</option>

		</optgroup>

		<?php endif; ?>

		<?php foreach ($menu_groups as $optgroup => $menus) : ?>

		<optgroup label="<?php esc_attr_e('Menu', 'wp-multisite-waas'); ?> - <?php echo esc_attr($optgroup); ?>" value="<?php esc_attr_e('Menu', 'wp-multisite-waas'); ?> - <?php echo esc_attr($optgroup); ?>">

			<?php foreach ($menus as $url => $menu) : ?>

			<option value="<?php echo esc_attr($url); ?>">

				<?php echo $menu; ?>

			</option>

			<?php endforeach; ?>

		</optgroup>

		<?php endforeach; ?>

		<optgroup label="<?php esc_attr_e('Settings', 'wp-multisite-waas'); ?>" value="setting"></optgroup>

		<optgroup label="<?php esc_attr_e('Users', 'wp-multisite-waas'); ?>" value="user"></optgroup>

		<optgroup label="<?php esc_attr_e('Customers', 'wp-multisite-waas'); ?>" value="customer"></optgroup>

		<optgroup label="<?php esc_attr_e('Products', 'wp-multisite-waas'); ?>" value="product"></optgroup>

		<optgroup label="<?php esc_attr_e('Domains', 'wp-multisite-waas'); ?>" value="domain"></optgroup>

		<optgroup label="<?php esc_attr_e('Sites', 'wp-multisite-waas'); ?>" value="site"></optgroup>

		<optgroup label="<?php esc_attr_e('Memberships', 'wp-multisite-waas'); ?>" value="membership"></optgroup>

		<optgroup label="<?php esc_attr_e('Payments', 'wp-multisite-waas'); ?>" value="payment"></optgroup>

		<optgroup label="<?php esc_attr_e('Discount Codes', 'wp-multisite-waas'); ?>" value="discount-code"></optgroup>

		<optgroup label="<?php esc_attr_e('Webhooks', 'wp-multisite-waas'); ?>" value="webhook"></optgroup>

		<optgroup label="<?php esc_attr_e('Broadcasts', 'wp-multisite-waas'); ?>" value="broadcast"></optgroup>

		<optgroup label="<?php esc_attr_e('Checkout Forms', 'wp-multisite-waas'); ?>" value="checkout-form"></optgroup>

		<?php

		/**
		 * Allow plugin developers to add new opt-groups.
		 *
		 * @since 2.0.0
		 */
		do_action('wu_jumper_options');

		?>

	</select>

	</div>

	<div class="wu-jumper-redirecting wu-bg-gray-200">

	<?php esc_html_e('Redirecting you to the target page...', 'wp-multisite-waas'); ?>

	</div>

	<div class="wu-jumper-loading wu-bg-gray-200">

	<?php esc_html_e('Searching Results...', 'wp-multisite-waas'); ?>

	</div>

</div>
