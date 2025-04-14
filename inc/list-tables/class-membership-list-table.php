<?php
/**
 * Membership List Table class.
 *
 * @package WP_Ultimo
 * @subpackage List_Table
 * @since 2.0.0
 */

namespace WP_Ultimo\List_Tables;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Membership List Table class.
 *
 * @since 2.0.0
 */
class Membership_List_Table extends Base_List_Table {

	/**
	 * Holds the query class for the object being listed.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $query_class = \WP_Ultimo\Database\Memberships\Membership_Query::class;

	/**
	 * Initializes the table.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			[
				'singular' => __('Membership', 'wp-multisite-waas'),  // singular name of the listed records
				'plural'   => __('Memberships', 'wp-multisite-waas'), // plural name of the listed records
				'ajax'     => true,                           // does this table support ajax?
				'add_new'  => [
					'url'     => wu_get_form_url('add_new_membership'),
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

		$_filter_fields                = parent::get_extra_query_fields();
		$_filter_fields['customer_id'] = wu_request('customer_id');

		return $_filter_fields;
	}

	/**
	 * Displays the membership reference code.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Ultimo\Models\Membership $item Membership object.
	 * @return string
	 */
	public function column_hash($item) {

		$url_atts = [
			'id'    => $item->get_id(),
			'model' => 'membership',
		];

		$code = sprintf('<a href="%s">%s</a>', wu_network_admin_url('wp-ultimo-edit-membership', $url_atts), $item->get_hash());

		$actions = [
			'edit'   => sprintf('<a href="%s">%s</a>', wu_network_admin_url('wp-ultimo-edit-membership', $url_atts), __('Edit', 'wp-multisite-waas')),
			'delete' => sprintf(
				'<a title="%s" class="wubox" href="%s">%s</a>',
				__('Delete', 'wp-multisite-waas'),
				wu_get_form_url(
					'delete_modal',
					$url_atts
				),
				__('Delete', 'wp-multisite-waas')
			),
		];

		$html = "<span class='wu-font-mono'><strong>{$code}</strong></span>";

		return $html . $this->row_actions($actions);
	}

	/**
	 * Displays the status of the membership.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Ultimo\Models\Membership $item Membership object.
	 * @return string
	 */
	public function column_status($item) {

		$label = $item->get_status_label();

		$class = $item->get_status_class();

		$html = "<span class='wu-bg-gray-200 wu-leading-none wu-text-gray-700 wu-py-1 wu-px-2 wu-rounded-sm wu-text-xs wu-font-mono $class'>{$label}</span>";

		return $html;
	}

	/**
	 * Displays the price of the membership.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Ultimo\Models\Membership $item Membership object.
	 * @return string
	 */
	public function column_amount($item) {

		if (empty($item->get_amount()) && empty($item->get_initial_amount())) {
			return __('Free', 'wp-multisite-waas');
		}

		if ($item->is_recurring()) {
			$amount = wu_format_currency($item->get_amount(), $item->get_currency());

			$duration = $item->get_duration();

			$message = sprintf(
				// translators: %1$s is the formatted price, %2$s the duration, and %3$s the duration unit (day, week, month, etc)
				_n('every %2$s', 'every %1$s %2$s', $duration, 'wp-multisite-waas'), // phpcs:ignore
				$duration,
				$item->get_duration_unit()
			);

			if ( ! $item->is_forever_recurring()) {
				$billing_cycles_message = sprintf(
					// translators: %s is the number of billing cycles.
					_n('for %s cycle', 'for %s cycles', $item->get_billing_cycles(), 'wp-multisite-waas'),
					$item->get_billing_cycles()
				);

				$message .= ' ' . $billing_cycles_message;
			}
		} else {
			$amount = wu_format_currency($item->get_initial_amount(), $item->get_currency());

			$message = __('one time payment', 'wp-multisite-waas');
		}

		return sprintf('%s<br><small>%s</small>', $amount, $message);
	}

	/**
	 * Returns the list of columns for this particular List Table.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'cb'              => '<input type="checkbox" />',
			'hash'            => wu_tooltip(__('Reference Code', 'wp-multisite-waas'), 'dashicons-wu-hash wu-text-xs'),
			'status'          => __('Status', 'wp-multisite-waas'),
			'customer'        => __('Customer', 'wp-multisite-waas'),
			'product'         => __('Product', 'wp-multisite-waas'),
			'amount'          => __('Price', 'wp-multisite-waas'),
			'date_created'    => __('Created at', 'wp-multisite-waas'),
			'date_expiration' => __('Expiration', 'wp-multisite-waas'),
			'id'              => __('ID', 'wp-multisite-waas'),
		];

		return $columns;
	}

	/**
	 * Handles the default displaying of datetime columns.
	 *
	 * @since 2.0.0
	 *
	 * @param self $item The membership.
	 * @return string
	 */
	public function column_date_expiration($item) {

		$date = $item->get_date_expiration();

		if (empty($date) || '0000-00-00 00:00:00' === $date) {
			return sprintf('<span>%s</span><br><small>%s</small>', __('Lifetime', 'wp-multisite-waas'), __('It never expires', 'wp-multisite-waas'));
		}

		if ( ! wu_validate_date($date)) {
			return __('--', 'wp-multisite-waas');
		}

		$time = strtotime(get_date_from_gmt($date));

		$formatted_value = date_i18n(get_option('date_format'), $time);

		$placeholder = wu_get_current_time('timestamp') > $time ? __('%s ago', 'wp-multisite-waas') : __('In %s', 'wp-multisite-waas'); // phpcs:ignore

		$text = $formatted_value . sprintf('<br><small>%s</small>', sprintf($placeholder, human_time_diff($time)));

		return sprintf('<span %s>%s</span>', wu_tooltip_text(date_i18n('Y-m-d H:i:s', $time)), $text);
	}

	/**
	 * Returns the filters for this page.
	 *
	 * @since 2.0.0
	 */
	public function get_filters(): array {

		$membership_status = new \WP_Ultimo\Database\Memberships\Membership_Status();

		return [
			'filters'      => [

				/**
				 * Status
				 */
				'status' => [
					'label'   => __('Status', 'wp-multisite-waas'),
					'options' => $membership_status::to_array(),
				],

			],
			'date_filters' => [

				/**
				 * Created At
				 */
				'date_created'    => [
					'label'   => __('Created At', 'wp-multisite-waas'),
					'options' => $this->get_default_date_filter_options(),
				],

				/**
				 * Expiration Date
				 */
				'date_expiration' => [
					'label'   => __('Expiration Date', 'wp-multisite-waas'),
					'options' => $this->get_default_date_filter_options(),
				],

				/**
				 * Renewal Date
				 */
				'date_renewed'    => [
					'label'   => __('Renewal Date', 'wp-multisite-waas'),
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
			'all'       => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'all'),
				'label' => __('All Memberships', 'wp-multisite-waas'),
				'count' => 0,
			],
			'active'    => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'active'),
				'label' => __('Active', 'wp-multisite-waas'),
				'count' => 0,
			],
			'trialing'  => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'trialing'),
				'label' => __('Trialing', 'wp-multisite-waas'),
				'count' => 0,
			],
			'pending'   => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'pending'),
				'label' => __('Pending', 'wp-multisite-waas'),
				'count' => 0,
			],
			'on-hold'   => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'on-hold'),
				'label' => __('On Hold', 'wp-multisite-waas'),
				'count' => 0,
			],
			'expired'   => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'expired'),
				'label' => __('Expired', 'wp-multisite-waas'),
				'count' => 0,
			],
			'cancelled' => [
				'field' => 'status',
				'url'   => add_query_arg('status', 'cancelled'),
				'label' => __('Cancelled', 'wp-multisite-waas'),
				'count' => 0,
			],
		];
	}
}
