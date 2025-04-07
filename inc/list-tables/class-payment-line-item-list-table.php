<?php
/**
 * Payment List Table class.
 *
 * @package WP_Ultimo
 * @subpackage List_Table
 * @since 2.0.0
 */

namespace WP_Ultimo\List_Tables;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Payment List Table class.
 *
 * @since 2.0.0
 */
class Payment_Line_Item_List_Table extends Line_Item_List_Table {

	/**
	 * Returns the list of columns for this particular List Table.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'responsive' => '',
		];

		return $columns;
	}

	/**
	 * Renders the inside column responsive.
	 *
	 * @since 2.0.0
	 *
	 * @param object $item The item being rendered.
	 * @return void
	 */
	public function column_responsive($item): void {

		$product = $item->get_product();

		$first_row = [
			'quantity'   => [
				'icon'  => 'dashicons-wu-package wu-align-middle wu-mr-1',
				'label' => __('Quantity', 'wp-multisite-waas'),
				'value' => sprintf(__('x%d', 'wp-multisite-waas'), $item->get_quantity()),
			],
			'unit_price' => [
				'icon'  => 'dashicons-wu-info1 wu-align-middle wu-mr-1',
				'label' => __('Unit Price', 'wp-multisite-waas'),
				'value' => wu_format_currency($item->get_unit_price()),
			],
		];

		$second_row = [];

		$url_atts = [
			'id'           => $this->get_payment()->get_id(),
			'line_item_id' => $item->get_id(),
		];

		$second_row['change'] = [
			'wrapper_classes' => 'wubox',
			'icon'            => 'dashicons-wu-edit1 wu-align-middle wu-mr-1',
			'label'           => '',
			'value'           => __('Edit', 'wp-multisite-waas'),
			'url'             => wu_get_form_url('edit_line_item', $url_atts),
		];

		$second_row['remove'] = [
			'wrapper_classes' => 'wu-text-red-500 wubox',
			'icon'            => 'dashicons-wu-trash-2 wu-align-middle wu-mr-1',
			'label'           => '',
			'value'           => __('Remove', 'wp-multisite-waas'),
			'url'             => wu_get_form_url('delete_line_item', $url_atts),
		];

		/*
		* Adds discounts
		*/
		if ($item->get_discount_total()) {
			if ($item->get_discount_type() === 'percentage' && $item->get_discount_rate()) {
				$tax_rate = $item->get_discount_rate() . '%';
			}

			$tax_label = $item->get_discount_rate() ? ($item->get_discount_label() ?: __('Discount', 'wp-multisite-waas')) : __('No discount', 'wp-multisite-waas');

			$tooltip = sprintf('%s (%s)', $tax_rate, $tax_label);

			$first_row['discounts_total'] = [
				'icon'  => 'dashicons-wu-percent wu-align-middle wu-mr-1',
				'label' => $tooltip,
				'value' => sprintf(__('Discounts: %s', 'wp-multisite-waas'), wu_format_currency($item->get_discount_total())),
			];
		}

		$first_row['subtotal'] = [
			'icon'  => 'dashicons-wu-info1 wu-align-middle wu-mr-1',
			'label' => '',
			/* translators: %s is a currency amount */
			'value' => sprintf(__('Subtotal: %s', 'wp-multisite-waas'), wu_format_currency($item->get_subtotal())),
		];

		/*
		* Adds Taxes
		*/
		if ($item->get_tax_total()) {
			if ($item->get_tax_type() === 'percentage' && $item->get_tax_rate()) {
				$tax_rate = $item->get_tax_rate() . '%';
			}

			$tax_label = $item->get_tax_rate() ? ($item->get_tax_label() ?: __('Tax Applied', 'wp-multisite-waas')) : __('No Taxes Applied', 'wp-multisite-waas');

			$tooltip = sprintf('%s (%s)', $tax_rate, $tax_label);

			$first_row['tax_total'] = [
				'icon'  => 'dashicons-wu-percent wu-align-middle wu-mr-1',
				'label' => $tooltip,
				'value' => sprintf(__('Taxes: %s', 'wp-multisite-waas'), wu_format_currency($item->get_tax_total())),
			];
		}

		$first_row['description'] = [
			'icon'  => 'dashicons-wu-file-text wu-align-middle wu-mr-1',
			'label' => __('Item Description', 'wp-multisite-waas'),
			'value' => $item->get_description(),
		];

		echo wu_responsive_table_row( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			[
				'id'     => '',
				'title'  => $item->get_title(),
				'url'    => '',
				'image'  => '',
				'status' => sprintf('<span class="wu-text-sm wu-font-medium wu-text-gray-700">%s</span>', wu_format_currency($item->get_total())),
			],
			$first_row,
			$second_row
		);
	}
}
