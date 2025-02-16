<?php
/**
 * Installation steps view.
 *
 * @since 2.0.0
 */
?>
<div class="wu-advanced-filters">
	<table class="widefat fixed striped wu-border-b" data-id="<?php echo esc_attr($page->get_current_section()); ?>">
	<thead>
		<tr>
		<?php if ($checks) : ?>
			<th class="check" style="width: 30px;"></th>
		<?php endif ?>
		<th class="item"><?php _e('Item', 'wp-multisite-waas'); ?></th>
		<th class="status" style="width: 40%;"><?php _e('Status', 'wp-multisite-waas'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($steps as $slug => $default) : ?>

		<tr 
			<?php echo ! $default['done'] ? 'data-content="' . esc_attr($slug) . '"' : ''; ?>
			<?php echo wu_array_to_html_attrs(wu_get_isset($default, 'html_attr', [])); ?>
		>

			<?php if ($checks) : ?>
			<td>
				<?php if ( ! $default['done']) : ?>
				<input type="checkbox" name="default_content[<?php echo esc_attr($slug); ?>]" id="default_content_<?php echo esc_attr($slug); ?>" value="1" checked>
				<?php endif ?>
			</td>
			<?php endif ?>

			<td>
			<label class="wu-font-semibold wu-text-gray-700" for="default_content_<?php echo esc_attr($slug); ?>">
				<?php echo $default['title']; ?>
			</label>
			<span class="wu-text-xs wu-block wu-mt-1">
				<?php echo $default['description']; ?>
			</span>
			</td>

			<?php if ($default['done']) : ?>
			<td class="status">
				<span class="wu-text-green-600">
				<?php echo $default['completed'] ?? __('Completed!', 'wp-multisite-waas'); ?>
				</span>
			</td>
			<?php else : ?>
			<td class="status">
				<span><?php echo $default['pending']; ?></span>
				<div class="spinner"></div>
				<!-- <a style="display: none;" class="wu-no-underline wu-block help" href="<?php echo $default['help']; ?>" title="<?php esc_attr_e('Help', 'wp-multisite-waas'); ?>">
					<?php _e('Read More', 'wp-multisite-waas'); ?>
					<span class="dashicons-wu-help-with-circle"></span>
				</a> -->
			</td>
			<?php endif; ?>

		</tr>

		<?php endforeach; ?>

	</tbody>
	</table>
</div>
