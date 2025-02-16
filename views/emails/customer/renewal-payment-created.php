<?php
/**
 * Payment Received Email Template
 *
 * @since 2.0.19
 */
?>
<p><?php printf(__('Hey %s,', 'wp-multisite-waas'), '{{customer_name}}'); ?></p>

<p><?php printf(__('You have a new pending payment of %1$s for your membership.', 'wp-multisite-waas'), '{{payment_total}}'); ?></p>

<p><a href="{{default_payment_url}}" style="text-decoration: none;" rel="nofollow"><?php _e('Pay Now', 'wp-multisite-waas'); ?></a></p>

<h2><b><?php _e('Payment', 'wp-multisite-waas'); ?></b></h2>

<table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
	<tbody>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php _e('Products', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee; border: 1px solid #eee;">
		{{payment_product_names}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php _e('Subtotal', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">
		{{payment_subtotal}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php _e('Tax', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">
		{{payment_tax_total}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php _e('Total', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">
		{{payment_total}}
		</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 160px; padding: 8px; background: #f9f9f9; border: 1px solid #eee;"><b><?php _e('Created at', 'wp-multisite-waas'); ?></b></td>
		<td style="padding: 8px; background: #fff; border: 1px solid #eee;">{{payment_date_created}}</td>
	</tr>
	</tbody>
</table>
