<?php
/**
 * Support terms view.
 *
 * @since 2.0.0
 */
?>
<div class="wu--mt-7">
	<p><?php esc_html_e('This plugin comes with support for issues you may have. Support can be requested via email on <a class="wu-no-underline" href="mailto:support@wpultimo.com" target="_blank">support@wpultimo.com</a> and includes:', 'wp-multisite-waas'); ?></p>

	<ul class="support-available">
	<li class="wu-text-green-700">
		<span class="dashicons-wu-check"></span>
		<?php esc_html_e('Availability of the author to answer questions', 'wp-multisite-waas'); ?>
	</li>
	<li class="wu-text-green-700">
		<span class="dashicons-wu-check"></span>
		<?php esc_html_e('Answering technical questions about item features', 'wp-multisite-waas'); ?>
	</li>
	<li class="wu-text-green-700">
		<span class="dashicons-wu-check"></span>
		<?php esc_html_e('Assistance with reported bugs and issues', 'wp-multisite-waas'); ?>
	</li>
	</ul>

	<p><?php esc_html_e('Support <strong>DOES NOT</strong> Include:', 'wp-multisite-waas'); ?></p>

	<ul class="support-unavailable">
	<li class="wu-text-red-500">
		<span class="dashicons-wu-circle-with-cross wu-align-middle"></span>
		<?php esc_html_e('Customization services', 'wp-multisite-waas'); ?>
	</li>
	<li class="wu-text-red-500">
		<span class="dashicons-wu-circle-with-cross wu-align-middle"></span>
		<?php esc_html_e('Installation services', 'wp-multisite-waas'); ?>
	</li>
	<li class="wu-text-red-500">
		<span class="dashicons-wu-circle-with-cross wu-align-middle"></span>
		<?php esc_html_e('Support for 3rd party plugins (i.e. plugins you install yourself later on)', 'wp-multisite-waas'); ?>
	</li>
	</ul>

</div>
