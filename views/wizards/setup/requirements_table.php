<?php
/**
 * Requirements table view.
 *
 * @since 2.0.0
 */
?>
<div class="wu-block">

	<div class="wu-block wu-text-gray-700 wu-font-bold wu-uppercase wu-text-xs wu-py-2">
	<?php echo __('WP Multisite WaaS Requires:', 'wp-multisite-waas'); ?>
	</div>

	<div class="wu-advanced-filters">
	<table class="widefat fixed striped wu-border-b">
		<thead>
		<tr>
			<th><?php _e('Item', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Minimum Version', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Recommended', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Installed', 'wp-multisite-waas'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($requirements as $req) : ?>
		<tr class="">
			<td><?php echo $req['name']; ?></td>
			<td><?php echo $req['required_version']; ?></td>
			<?php // translators: %s is the requirement version ?>
			<td><?php printf(__('%s or later'), $req['recommended_version']); ?></td>
			<td class="<?php echo $req['pass_requirements'] ? 'wu-text-green-600' : 'wu-text-red-600'; ?>">
			<?php echo $req['installed_version']; ?>
			<?php echo $req['pass_requirements'] ? '<span class="dashicons-wu-check"></span>' : '<span class="dashicons-wu-cross"></span>'; ?>

			<?php if ( ! $req['pass_requirements']) : ?>

				<a class="wu-no-underline wu-block" href="<?php echo $req['help']; ?>" title="<?php esc_attr_e('Help', 'wp-multisite-waas'); ?>">
				<?php _e('Read More', 'wp-multisite-waas'); ?>
				<span class="dashicons-wu-help-with-circle"></span>
				</a>

			<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<br>
	</div>

	<div class="wu-block wu-text-gray-700 wu-font-bold wu-uppercase wu-text-xs wu-py-2">
	<?php echo __('And', 'wp-multisite-waas'); ?>
	</div>

	<div class="wu-advanced-filters">
	<table class="widefat fixed striped wu-border-b">
		<thead>
		<tr>
			<th><?php _e('Item', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Condition', 'wp-multisite-waas'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($plugin_requirements as $req) : ?>
		<tr class="">
			<td><?php echo $req['name']; ?></td>
			<td class="<?php echo $req['pass_requirements'] ? 'wu-text-green-600' : 'wu-text-red-600'; ?>">
			<?php echo $req['condition']; ?>
			<?php echo $req['pass_requirements'] ? '<span class="dashicons-wu-check"></span>' : '<span class="dashicons-wu-cross wu-align-middle"></span>'; ?>

			<?php if ( ! $req['pass_requirements']) : ?>

				<a target="_blank" class="wu-no-underline wu-ml-2" href="<?php echo $req['help']; ?>" title="<?php esc_attr_e('Help', 'wp-multisite-waas'); ?>">
				<span class="dashicons-wu-help-with-circle wu-align-baseline"></span>
				<?php _e('Read More', 'wp-multisite-waas'); ?>
				</a>

			<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<br>
	</div>

	<?php if (\WP_Ultimo\Requirements::met() === false) : ?>

	<div class="wu-mt-4 wu-p-4 wu-bg-red-100 wu-border wu-border-solid wu-border-red-200 wu-rounded-sm wu-text-red-500">
		<?php _e('It looks like your hosting environment does not support the current version of WP Multisite WaaS. Visit the <strong>Read More</strong> links on each item to see what steps you need to take to bring your environment up to the WP Multisite WaaS current requirements.', 'wp-multisite-waas'); ?>
	</div>

	<?php endif; ?>

</div>
