<?php
/**
 * Total widget view.
 *
 * @since 2.0.0
 */
?>
<div class="wu-styling">

	<div class="wu-widget-inset">

	<?php

	$data    = [];
	$slug    = 'taxes_by_day';
	$headers = [
		__('Day', 'wp-multisite-waas'),
		__('Orders', 'wp-multisite-waas'),
		__('Total Sales', 'wp-multisite-waas'),
		__('Tax Total', 'wp-multisite-waas'),
		__('Net Profit', 'wp-multisite-waas'),
	];

	foreach ($taxes_by_day as $day => $tax_line) {
		$line = [
			date_i18n(get_option('date_format'), strtotime($day)),
			$tax_line['order_count'],
			wu_format_currency($tax_line['total']),
			wu_format_currency($tax_line['tax_total']),
			wu_format_currency($tax_line['net_profit']),
		];

		$data[] = $line;
	} // end foreach;

	$page->render_csv_button(
		[
			'headers' => $headers,
			'data'    => $data,
			'slug'    => $slug,
		]
	);

	?>

	<table class="wp-list-table widefat fixed striped wu-border-none">

		<thead>
			<tr>
			<th class="wu-w-1/3"><?php _e('Day', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Orders', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Total Sales', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Tax Total', 'wp-multisite-waas'); ?></th>
			<th><?php _e('Net Profit', 'wp-multisite-waas'); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php if ($taxes_by_day) : ?>

				<?php foreach ($taxes_by_day as $day => $tax_line) : ?>

				<tr>
				<td>
					<?php echo date_i18n(get_option('date_format'), strtotime($day)); ?>
				</td>
				<td>
					<?php echo $tax_line['order_count']; ?>
				</td>
				<td>
					<?php echo wu_format_currency($tax_line['total']); ?>
				</td>
				<td>
					<?php echo wu_format_currency($tax_line['tax_total']); ?>
				</td>
				<td>
					<?php echo wu_format_currency($tax_line['net_profit']); ?>
				</td>
				</tr>

			<?php endforeach; ?>

			<?php else : ?>

				<tr>
				<td colspan="4">
					<?php _e('No Taxes found.', 'wp-multisite-waas'); ?>
				</td>
				</tr>

			<?php endif; ?>

		</tbody>

	</table>

	</div>

</div>
