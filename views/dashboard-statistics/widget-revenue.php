<?php
/**
 * Total widget view.
 *
 * @since 2.0.0
 */
?>
<div class="wu-styling">

	<ul class="lg:wu-flex wu-my-0 wu-mx-0">

	<li class="wu-p-2 wu-w-full md:wu-w-4/12 wu-relative" <?php echo wu_tooltip_text(__('MRR stands for Monthly Recurring Revenue', 'wp-multisite-waas')); ?>>

		<div>

		<strong class="wu-text-gray-800 wu-text-2xl md:wu-text-xl">
			<?php echo wu_format_currency($mrr); ?>
		</strong>

		</div>

		<div class="wu-text-md wu-text-gray-600">
		<span class="wu-block"><?php esc_html_e('MRR', 'wp-multisite-waas'); ?></span>
		</div>

	</li>

	<li class="wu-p-2 wu-w-full md:wu-w-4/12 wu-relative">

		<div>

		<strong class="wu-text-gray-800 wu-text-2xl md:wu-text-xl">
			<?php echo wu_format_currency($gross_revenue); ?>
		</strong>

		</div>

		<div class="wu-text-md wu-text-gray-600">
		<span class="wu-block"><?php esc_html_e('Gross Revenue', 'wp-multisite-waas'); ?></span>
		</div>

	</li>

	<li class="wu-p-2 wu-w-full md:wu-w-4/12 wu-relative">

		<div>

		<strong class="wu-text-gray-800 wu-text-2xl md:wu-text-xl">
			<?php echo wu_format_currency($refunds); ?>
		</strong>

		</div>

		<div class="wu-text-md wu-text-gray-600">
		<span class="wu-block"><?php esc_html_e('Refunded', 'wp-multisite-waas'); ?></span>
		</div>

	</li>

	</ul>

	<div class="wu--mx-3 wu--mb-3 wu-mt-2">

	<table class="wp-list-table widefat fixed striped wu-border-t-1 wu-border-l-0 wu-border-r-0">

		<thead>
			<tr>
			<th><?php esc_html_e('Product', 'wp-multisite-waas'); ?></th>
			<th class="wu-text-right"><?php esc_html_e('Revenue', 'wp-multisite-waas'); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php if (wu_get_products()) : ?>

				<?php foreach ($product_stats as $stats) : ?>

				<tr>
				<td>
					<?php echo $stats['label']; ?>
				</td>
				<td class="wu-text-right">
					<?php echo wu_format_currency($stats['revenue']); ?>
				</td>
				</tr>

			<?php endforeach; ?>

			<?php else : ?>

			<tr>
				<td colspan="2">
				<?php esc_html_e('No Products found.', 'wp-multisite-waas'); ?>
				</td>
			</tr>

			<?php endif; ?>

		</tbody>

	</table>

	</div>

</div>
