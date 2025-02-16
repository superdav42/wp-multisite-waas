<?php
/**
 * System info table view.
 *
 * @since 2.0.0
 */

$text_yes = '<span class="dashicons dashicons-yes wu-text-green-400"></span>';
$text_no  = '<span class="dashicons dashicons-no-alt wu-text-red-600"></span>';

?>
<table class='wu-table-auto striped wu-w-full'>

	<?php if (empty($data)) : ?>

		<tr>
			<td colspan="2" class="wu-px-4 wu-py-2">
				<?php _e('No items found.', 'wp-multisite-waas'); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php foreach ($data as $key => $value) : ?>

		<tr>

				<td class='wu-px-4 wu-py-2 wu-w-4/12'> <?php echo $value['title']; ?> </td>

				<td class='wu-px-4 wu-py-2 wu-text-center wu-w-5'>

					<?php echo wu_tooltip($value['tooltip']); ?>

				</td>

				<?php if ('Yes' === $value['value'] || 'Enabled' === $value['value']) : ?>

					<td class='wu-px-4 wu-py-2'> <?php echo $text_yes; ?> </td>

				<?php elseif ('No' === $value['value'] || 'Disabled' === $value['value']) : ?>

					<td class='wu-px-4 wu-py-2'> <?php echo $text_no; ?> </td>

				<?php else : ?>

					<td class='wu-px-4 wu-py-2'> <?php echo $value['value']; ?> </td>

				<?php endif; ?>

		</tr>

	<?php endforeach; ?>

</table>
