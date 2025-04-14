<?php
/**
 * Sort Helper Functions
 *
 * @package WP_Ultimo\Functions
 * @since   2.0.11
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Sort arrays based on a particular column.
 *
 * @since 2.0.0
 *
 * @param array  $a The first array.
 * @param array  $b The second array.
 * @param string $column The column to compare.
 * @return int
 */
function wu_sort_by_column($a, $b, $column = 'order') {

	$a[ $column ] = isset($a[ $column ]) ? (int) $a[ $column ] : 50;

	$b[ $column ] = isset($b[ $column ]) ? (int) $b[ $column ] : 50;

	return $a[ $column ] - $b[ $column ];
}

/**
 * Sorts the fields.
 *
 * @param array $a The first array containing a order key.
 * @param array $b The second array containing a order key.
 * @return int
 */
function wu_sort_by_order($a, $b) {

	return wu_sort_by_column($a, $b, 'order');
}

/**
 * Loops through the list items and adds a order key if none is set, based on the index.
 *
 * @since 2.0.7
 *
 * @param array  $items The list of sortable elements.
 * @param string $order_key The order key.
 * @return array
 */
function wu_set_order_from_index($items, $order_key = 'order') {

	$index = 1;

	foreach ($items as &$item) {
		if (isset($item[ $order_key ]) === false) {
			$index = $index ?: 1; // phpcs:ignore

			$item[ $order_key ] = $index * 10;

			++$index;
		}
	}

	return $items;
}
