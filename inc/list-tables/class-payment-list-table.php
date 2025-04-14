<?php
/**
 * Payment List Table class.
 *
 * @package WP_Ultimo
 * @subpackage List_Table
 * @since 2.0.0
 */

namespace WP_Ultimo\List_Tables;

use WP_Ultimo\Database\Payments\Payment_Status;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Payment List Table class.
 *
 * @since 2.0.0
 */
class Payment_List_Table extends Base_List_Table {

	/**
	 * Holds the query class for the object being listed.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $query_class = \WP_Ultimo\Database\Payments\Payment_Query::class;

	/**
	 * Initializes the table.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			[
				'singular' => __('Payment', 'wp-multisite-waas'),
				'plural'   => __('Payments', 'wp-multisite-waas'),
				'ajax'     => true,
				'add_new'  => [
					'url'     => wu_get_form_url('add_new_payment'),
					'classes' => 'wubox',
				],
			]
		);
	}

	/**
	 * Adds the extra search field when the search element is present.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_extra_query_fields() {

		$_filter_fields                  = parent::get_extra_query_fields();
		$_filter_fields['membership_id'] = wu_request('membership_id', false);
		$_filter_fields['customer_id']   = wu_request('customer_id', false);
		$_filter_fields['parent_id__in'] = ['0', 0, '', null];

		return $_filter_fields;
	}

	/**
	 * Displays the payment reference code.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment $item Payment object.
	 * @return string
	 */
	public function column_hash($item) {

		$url_atts = [
			'id' => $item->get_id(),
		];

		$code = sprintf('<a href="%s">%s</a>', wu_network_admin_url('wp-ultimo-edit-payment', $url_atts), $item->get_hash());

		$actions = [
			'edit'   => sprintf('<a href="%s">%s</a>', wu_network_admin_url('wp-ultimo-edit-payment', $url_atts), __('Edit', 'wp-multisite-waas')),
			'delete' => sprintf(
				'<a title="%s" class="wubox" href="%s">%s</a>',
				__('Delete', 'wp-multisite-waas'),
				wu_get_form_url(
					'delete_modal',
					[
						'model' => 'payment',
						'id'    => $item->get_id(),
					]
				),
				__('Delete', 'wp-multisite-waas')
			),
		];

		$html = "<span class='wu-font-mono'><strong>{$code}</strong></span>";

		return $html . $this->row_actions($actions);
	}

	/**
	 * Displays the membership photo and special status.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Ultimo\Models\Payment $item Payment object.
	 * @return string
	 */
	public function column_status($item) {

		$label = $item->get_status_label();

		$class = $item->get_status_class();

		return "<span class='wu-bg-gray-200 wu-text-gray-700 wu-py-1 wu-px-2 wu-inline-block wu-leading-none wu-rounded-sm wu-text-xs wu-font-mono $class'>{$label}</span>";
	}

	/**
	 * Returns the number of subscriptions owned by this membership.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment $item Payment object.
	 * @return string
	 */
	public function column_product($item) {

		$product = $item->get_product();

		if ( ! $product) {
			return __('No product found', 'wp-multisite-waas');
		}

		$url_atts = [
			'product_id' => $product->get_id(),
		];

		$actions = [
			'view' => sprintf('<a href="%s">%s</a>', wu_network_admin_url('wp-ultimo-edit-product', $url_atts), __('View', 'wp-multisite-waas')),
		];

		$html = $product->get_name();

		return $html . $this->row_actions($actions);
	}

	/**
	 * Displays the column for the total amount of the payment.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment $item Payment object.
	 * @return string
	 */
	public function column_total($item) {

		$gateway = wu_slug_to_name($item->get_gateway());

		return wu_format_currency($item->get_total()) . "<small class='wu-block'>{$gateway}</small>";
	}

	/**
	 * Returns the list of columns for this particular List Table.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'cb'           => '<input type="checkbox" />',
			'hash'         => wu_tooltip(__('Reference Code', 'wp-multisite-waas'), 'dashicons-wu-hash wu-text-xs'),
			'status'       => __('Status', 'wp-multisite-waas'),
			'customer'     => __('Customer', 'wp-multisite-waas'),
			'membership'   => __('Membership', 'wp-multisite-waas'),
			'total'        => __('Total', 'wp-multisite-waas'),
			'date_created' => __('Created at', 'wp-multisite-waas'),
			'id'           => __('ID', 'wp-multisite-waas'),
		];

		return $columns;
	}

	/**
	 * Returns the filters for this page.
	 *
	 * @since 2.0.0
	 */
	public function get_filters(): array {

		return [
			'filters'      => [

				/**
				 * Status
				 */
				'status'  => [
					'label'   => __('Status', 'wp-multisite-waas'),
					'options' => [
						'pending'   => __('Pending', 'wp-multisite-waas'),
						'completed' => __('Completed', 'wp-multisite-waas'),
						'refund'    => __('Refund', 'wp-multisite-waas'),
						'partial'   => __('Partial', 'wp-multisite-waas'),
						'failed'    => __('Failed', 'wp-multisite-waas'),
					],
				],

				/**
				 * Gateway
				 */
				'gateway' => [
					'label'   => __('Gateway', 'wp-multisite-waas'),
					'options' => [
						'free'   => __('Free', 'wp-multisite-waas'),
						'manual' => __('Manual', 'wp-multisite-waas'),
						'paypal' => __('Paypal', 'wp-multisite-waas'),
						'stripe' => __('Stripe', 'wp-multisite-waas'),
					],
				],
			],
			'date_filters' => [

				/**
				 * Created At
				 */
				'date_created' => [
					'label'   => __('Created At', 'wp-multisite-waas'),
					'options' => $this->get_default_date_filter_options(),
				],
			],
		];
	}

	/**
	 * Returns the pre-selected filters on the filter bar.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_views() {

		return [
			'all'                          => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'all'),
				'label' => __('All Payments', 'wp-multisite-waas'),
				'count' => 0,
			],
			Payment_Status::COMPLETED      => [
				'field' => 'status',
				'url'   => add_query_arg('status', Payment_Status::COMPLETED),
				'label' => __('Completed', 'wp-multisite-waas'),
				'count' => 0,
			],
			Payment_Status::PENDING        => [
				'field' => 'status',
				'url'   => add_query_arg('status', Payment_Status::PENDING),
				'label' => __('Pending', 'wp-multisite-waas'),
				'count' => 0,
			],
			Payment_Status::PARTIAL_REFUND => [
				'field' => 'status',
				'url'   => add_query_arg('status', Payment_Status::PARTIAL_REFUND),
				'label' => __('Partially Refunded', 'wp-multisite-waas'),
				'count' => 0,
			],
			Payment_Status::REFUND         => [
				'field' => 'status',
				'url'   => add_query_arg('status', Payment_Status::REFUND),
				'label' => __('Refunded', 'wp-multisite-waas'),
				'count' => 0,
			],
			Payment_Status::FAILED         => [
				'field' => 'status',
				'url'   => add_query_arg('status', Payment_Status::FAILED),
				'label' => __('Failed', 'wp-multisite-waas'),
				'count' => 0,
			],
		];
	}
}
