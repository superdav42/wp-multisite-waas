<?php
/**
 * Payment Received Email Template
 *
 * @since 2.0.0
 */
?>
<p><?php printf(esc_html__('Hey %s,', 'wp-multisite-waas'), '{{customer_name}}'); ?></p>

<p><?php printf(esc_html__('We have great news! We successfully processed your payment of %1$s for %2$s.', 'wp-multisite-waas'), '{{payment_total}}', '{{payment_product_names}}'); ?></p>

<p><a href="{{payment_invoice_url}}" style="text-decoration: none;" rel="nofollow"><?php esc_html_e('Download Invoice', 'wp-multisite-waas'); ?></a></p>

<h2><b><?php esc_html_e('Payment', 'wp-multisite-waas'); ?></b></h2>

<table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
	<tbody>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php esc_html_e('Products', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee; border: 1px solid #eee;">
		{{payment_product_names}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php esc_html_e('Subtotal', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">
		{{payment_subtotal}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php esc_html_e('Tax', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">
		{{payment_tax_total}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php esc_html_e('Total', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">
		{{payment_total}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php esc_html_e('Processed at', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">{{payment_date_created}}</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9;"><b><?php esc_html_e('Invoice', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fdfdfd; border: 1px solid #eee;">
		<a href="{{payment_invoice_url}}" style="text-decoration: none;" rel="nofollow">
			<?php esc_html_e('Download PDF', 'wp-multisite-waas'); ?>
		</a>
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php esc_html_e('Type', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">Initial Payment</td>
	</tr>
	</tbody>
</table>
