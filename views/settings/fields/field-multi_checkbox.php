<?php
/**
 * Multi checkbox field view.
 *
 * @since 2.0.0
 */
?>
<tr id="multiselect-<?php echo $field_slug; ?>">
	<th scope="row"><label for="<?php echo $field_slug; ?>"><?php echo $field['title']; ?></label> <?php echo WU_Util::tooltip($field['tooltip']); ?></th>
	<td>

		<?php

		// Check if it was selected
		$settings = wu_get_setting($field_slug);

		if (false === $settings) {
			$settings = $field['default'] ?? false;
		}

		/**
		 * Allow multi-select
		*
		 * @since 1.5.0
		 */

		$sortable_class = isset($field['sortable']) && $field['sortable'] ? 'wu-sortable' : '';

		// If sortable, merge settings and list of items
		if (isset($field['sortable']) && $field['sortable'] && $settings) {
			$_settings = $settings;

			foreach ($_settings as $key => &$value) {
				if ( ! isset($field['options'][ $key ])) {
					unset($_settings[ $key ]);

					continue;
				}

				$value = $field['options'][ $key ];
			}

			$field['options'] = $_settings + $field['options'];
		}

		?>

		<div class="row <?php echo $sortable_class; ?>">

		<?php
		/**
		 * Loop the values
		 */
		foreach ($field['options'] as $field_value => $field_name) :

			// Check this setting
			$this_settings = $settings[ $field_value ] ?? false;

			?>

		<div class="wu-col-sm-4" style="margin-bottom: 2px;">

			<label for="multiselect-<?php echo $field_value; ?>">
			<input <?php checked($this_settings); ?> name="<?php printf('%s[%s]', $field_slug, $field_value); ?>" type="checkbox" id="multiselect-<?php echo $field_value; ?>" value="1">
			<?php echo $field_name; ?>
			</label>

		</div>

		<?php endforeach; ?>

		</div>

		<button type="button" data-select-all="multiselect-<?php echo $field_slug; ?>" class="button wu-select-all"><?php esc_html_e('Check / Uncheck All', 'wp-multisite-waas'); ?></button>

		<br>

		<?php if ( ! empty($field['desc'])) : ?>

		<p class="description" id="<?php echo $field_slug; ?>-desc">
			<?php echo $field['desc']; ?>
		</p>

		<?php endif; ?>

	</td>
	</tr>
