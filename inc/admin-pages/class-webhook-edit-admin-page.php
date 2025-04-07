<?php
/**
 * WP Multisite WaaS Webhook Edit/Add New Admin Page.
 *
 * @package WP_Ultimo
 * @subpackage Admin_Pages
 * @since 2.0.0
 */

namespace WP_Ultimo\Admin_Pages;

// Exit if accessed directly
defined('ABSPATH') || exit;

use WP_Ultimo\Models\Webhook;

/**
 * WP Multisite WaaS Webhook Edit/Add New Admin Page.
 */
class Webhook_Edit_Admin_Page extends Edit_Admin_Page {

	/**
	 * Holds the ID for this page, this is also used as the page slug.
	 *
	 * @var string
	 */
	protected $id = 'wp-ultimo-edit-webhook';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $type = 'submenu';

	/**
	 * Object ID being edited.
	 *
	 * @since 1.8.2
	 * @var string
	 */
	public $object_id = 'webhook';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $parent = 'none';

	/**
	 * This page has no parent, so we need to highlight another sub-menu.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $highlight_menu_slug = 'wp-ultimo-webhooks';

	/**
	 * If this number is greater than 0, a badge with the number will be displayed alongside the menu title
	 *
	 * @since 1.8.2
	 * @var integer
	 */
	protected $badge_count = 0;

	/**
	 * Holds the admin panels where this page should be displayed, as well as which capability to require.
	 *
	 * To add a page to the regular admin (wp-admin/), use: 'admin_menu' => 'capability_here'
	 * To add a page to the network admin (wp-admin/network), use: 'network_admin_menu' => 'capability_here'
	 * To add a page to the user (wp-admin/user) admin, use: 'user_admin_menu' => 'capability_here'
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $supported_panels = [
		'network_admin_menu' => 'wu_edit_webhooks',
	];

	/**
	 * Registers the necessary scripts and styles for this admin page.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts(): void {

		parent::register_scripts();

		wp_register_script('wu-webhook-page', wu_get_asset('webhook-page.js', 'js'), ['jquery', 'wu-sweet-alert']);

		wp_localize_script(
			'wu-webhook-page',
			'wu_webhook_page',
			[
				'i18n' => [
					'error_title'   => __('Webhook Test', 'wp-multisite-waas'),
					'error_message' => __('An error occurred when sending the test webhook, please try again.', 'wp-multisite-waas'),
					'copied'        => __('Copied!', 'wp-multisite-waas'),
				],
			]
		);

		wp_enqueue_script('wu-webhook-page');
	}

	/**
	 * Register ajax forms that we use for webhook.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_forms(): void {
		/*
		 * Delete Webhook - Confirmation modal
		 */
		add_filter(
			'wu_data_json_success_delete_webhook_modal',
			fn($data_json) => [
				'redirect_url' => wu_network_admin_url('wp-ultimo-webhooks', ['deleted' => 1]),
			]
		);
	}

	/**
	 * Allow child classes to register widgets, if they need them.
	 *
	 * @since 1.8.2
	 * @return void
	 */
	public function register_widgets(): void {

		parent::register_widgets();

		$this->add_fields_widget(
			'domain-url',
			[
				'title'    => __('Webhook URL', 'wp-multisite-waas'),
				'position' => 'normal',
				'fields'   => [
					'webhook_url' => [
						'type'        => 'url',
						'title'       => __('Webhook URL', 'wp-multisite-waas'),
						'desc'        => __('The URL where we will send the payload when the event triggers.', 'wp-multisite-waas'),
						'placeholder' => __('https://example.com', 'wp-multisite-waas'),
						'value'       => $this->get_object()->get_webhook_url(),
					],
					'actions'     => [
						'type'            => 'actions',
						'tooltip'         => __('The event .', 'wp-multisite-waas'),
						'actions'         => [
							'send_test_event' => [
								'title'        => __('Send Test Event', 'wp-multisite-waas'),
								'action'       => 'wu_send_test_event',
								'object_id'    => $this->get_object()->get_id(),
								'loading_text' => 'Sending Test...',
							],
						],
						'html_attr'       => [
							'data-page' => 'edit',
						],
						'wrapper_classes' => 'wu-items-left wu-justify-start',
					],
				],
			]
		);

		add_meta_box('wp-ultimo-payload', __('Event Payload', 'wp-multisite-waas'), [$this, 'output_default_widget_payload'], get_current_screen()->id, 'normal');

		$this->add_list_table_widget(
			'events',
			[
				'title'        => __('Events', 'wp-multisite-waas'),
				'table'        => new \WP_Ultimo\List_Tables\Inside_Events_List_Table(),
				'query_filter' => [$this, 'query_filter'],
			]
		);

		$event_list = [];

		foreach (wu_get_event_types() as $key => $value) {
			$event_list[ $key ] = $value['name'];
		}

		$this->add_save_widget(
			'save',
			[
				'fields' => [
					'event' => [
						'type'        => 'select',
						'title'       => __('Event', 'wp-multisite-waas'),
						'desc'        => __('The event that triggers this webhook.', 'wp-multisite-waas'),
						'placeholder' => __('Select Event', 'wp-multisite-waas'),
						'options'     => $event_list,
						'value'       => $this->get_object()->get_event(),
					],
				],
			]
		);

		$this->add_fields_widget(
			'active',
			[
				'title'  => __('Active', 'wp-multisite-waas'),
				'fields' => [
					'active' => [
						'type'    => 'toggle',
						'title'   => __('Active', 'wp-multisite-waas'),
						'tooltip' => __('Deactivate will end the event trigger for this webhook.', 'wp-multisite-waas'),
						'desc'    => __('Use this option to manually enable or disable this webhook.', 'wp-multisite-waas'),
						'value'   => $this->get_object()->is_active(),
					],
				],
			]
		);

		$this->add_fields_widget(
			'options',
			[
				'title'  => __('Options', 'wp-multisite-waas'),
				'fields' => [
					'integration' => [
						'edit'          => true,
						'title'         => __('Integration', 'wp-multisite-waas'),
						'type'          => 'text-edit',
						'placeholder'   => 'manual',
						'value'         => $this->get_object()->get_integration(),
						'display_value' => ucwords((string) $this->get_object()->get_integration()),
						'tooltip'       => __('Name of the service responsible for creating this webhook. If you are manually creating this webhook, use the value "manual".', 'wp-multisite-waas'),
					],
					'event_count' => [
						'title'         => __('Run Count', 'wp-multisite-waas'),
						'type'          => 'text-edit',
						'min'           => 0,
						'placeholder'   => 0,
						'edit'          => true,
						'value'         => $this->get_object()->get_event_count(),
						// translators: %d is the number of times that this webhook was triggered.
						'display_value' => sprintf(__('This webhook was triggered %d time(s).', 'wp-multisite-waas'), $this->get_object()->get_event_count()),
						'tooltip'       => __('The number of times that this webhook was triggered so far. It includes test runs.', 'wp-multisite-waas'),
					],
				],
			]
		);
	}

	/**
	 * Outputs the markup for the payload widget.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function output_default_widget_payload(): void {

		$object_event_slug = $this->get_object()->get_event();

		$event = wu_get_event_type($object_event_slug);

		$payload = isset($event['payload']) ? wp_json_encode(wu_maybe_lazy_load_payload($event['payload']), JSON_PRETTY_PRINT) : '{}';

		wu_get_template(
			'events/widget-payload',
			[
				'title'        => __('Event Payload', 'wp-multisite-waas'),
				'loading_text' => __('Loading Payload', 'wp-multisite-waas'),
				'payload'      => $payload,
			]
		);
	}

	/**
	 * Filters the list table to return only relevant events.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args Query args passed to the list table.
	 * @return array Modified query args.
	 */
	public function query_filter($args) {

		$extra_args = [
			'object_type' => 'webhook',
			'object_id'   => absint($this->get_object()->get_id()),
		];

		return array_merge($args, $extra_args);
	}

	/**
	 * Returns the title of the page.
	 *
	 * @since 2.0.0
	 * @return string Title of the page.
	 */
	public function get_title() {

		return $this->edit ? __('Edit Webhook', 'wp-multisite-waas') : __('Add new Webhook', 'wp-multisite-waas');
	}

	/**
	 * Returns the title of menu for this page.
	 *
	 * @since 2.0.0
	 * @return string Menu label of the page.
	 */
	public function get_menu_title() {

		return __('Edit Webhook', 'wp-multisite-waas');
	}

	/**
	 * Returns the action links for that page.
	 *
	 * @since 1.8.2
	 * @return array
	 */
	public function action_links() {

		return [];
	}

	/**
	 * Returns the labels to be used on the admin page.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_labels() {

		return [
			'edit_label'          => __('Edit Webhook', 'wp-multisite-waas'),
			'add_new_label'       => __('Add new Webhook', 'wp-multisite-waas'),
			'updated_message'     => __('Webhook updated successfully!', 'wp-multisite-waas'),
			'title_placeholder'   => __('Enter Webhook', 'wp-multisite-waas'),
			'title_description'   => '',
			'save_button_label'   => __('Save Webhook', 'wp-multisite-waas'),
			'save_description'    => '',
			'delete_button_label' => __('Delete Webhook', 'wp-multisite-waas'),
			'delete_description'  => __('Be careful. This action is irreversible.', 'wp-multisite-waas'),
		];
	}

	/**
	 * Returns the object being edit at the moment.
	 *
	 * @since 2.0.0
	 * @return \WP_Ultimo\Models\Webhook
	 */
	public function get_object() {

		if (wu_request('id')) {
			$query = new \WP_Ultimo\Database\Webhooks\Webhook_Query();

			$item = $query->get_item_by('id', wu_request('id'));

			if ( ! $item) {
				wp_safe_redirect(wu_network_admin_url('wp-ultimo-webhooks'));

				exit;
			}

			return $item;
		}

		return new Webhook();
	}

	/**
	 * Webhooks have titles.
	 *
	 * @since 2.0.0
	 */
	public function has_title(): bool {

		return true;
	}

	/**
	 * Handles the save of this form.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function handle_save(): void {

		$object = $this->get_object();

		$object->attributes($_POST);

		if (is_wp_error($object->save())) {
			$errors = implode('<br>', $object->save()->get_error_messages());

			WP_Ultimo()->notices->add($errors, 'error', 'network-admin');

			return;
		} else {
			$array_params = [
				'updated' => 1,
			];

			if (false === $this->edit) {
				$array_params['id'] = $object->get_id();
			}

			$url = add_query_arg($array_params);

			wp_safe_redirect($url);

			exit;
		}
	}
}
