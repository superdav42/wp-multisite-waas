<?php
/**
 * Creates a cart with the parameters of the purchase being placed.
 *
 * @package WP_Ultimo
 * @subpackage Order
 * @since 2.0.0
 */

namespace WP_Ultimo\Checkout\Signup_Fields;

use \WP_Ultimo\Checkout\Signup_Fields\Base_Signup_Field;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Creates an cart with the parameters of the purchase being placed.
 *
 * @package WP_Ultimo
 * @subpackage Checkout
 * @since 2.0.0
 */
class Signup_Field_Billing_Address extends Base_Signup_Field {

	/**
	 * Returns the type of the field.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_type() {

		return 'billing_address';

	} // end get_type;

	/**
	 * Returns if this field should be present on the checkout flow or not.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_required() {

		return false;

	} // end is_required;

	/**
	 * Is this a user-related field?
	 *
	 * If this is set to true, this field will be hidden
	 * when the user is already logged in.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_user_field() {

		return true;

	} // end is_user_field;

	/**
	 * Requires the title of the field/element type.
	 *
	 * This is used on the Field/Element selection screen.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_title() {

		return __('Address', 'wp-ultimo');

	} // end get_title;

	/**
	 * Returns the description of the field/element.
	 *
	 * This is used as the title attribute of the selector.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_description() {

		return __('Adds billing address fields such as country, zip code.', 'wp-ultimo');

	} // end get_description;

	/**
	 * Returns the tooltip of the field/element.
	 *
	 * This is used as the tooltip attribute of the selector.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_tooltip() {

		return __('Adds billing address fields such as country, zip code.', 'wp-ultimo');

	} // end get_tooltip;

	/**
	 * Returns the icon to be used on the selector.
	 *
	 * Can be either a dashicon class or a wu-dashicon class.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_icon() {

		return 'dashicons-wu-map1';

	} // end get_icon;

	/**
	 * Returns the default values for the field-elements.
	 *
	 * This is passed through a wp_parse_args before we send the values
	 * to the method that returns the actual fields for the checkout form.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function defaults() {

		return array(
			'zip_and_country' => true,
		);

	} // end defaults;

	/**
	 * List of keys of the default fields we want to display on the builder.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function default_fields() {

		return array(
			'name',
		);

	} // end default_fields;

	/**
	 * If you want to force a particular attribute to a value, declare it here.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function force_attributes() {

		return array(
			'id'       => 'billing_address',
			'required' => true,
		);

	}  // end force_attributes;

	/**
	 * Returns the list of additional fields specific to this type.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_fields() {

		return array(
			'zip_and_country' => array(
				'type'  => 'toggle',
				'title' => __('Display only ZIP and Country?', 'wp-ultimo'),
				'desc'  => __('Checking this option will only add the ZIP and country fields, instead of all the normal billing address fields.', 'wp-ultimo'),
				'value' => true,
			),
		);

	} // end get_fields;

	/**
	 * Build a filed alternative.
	 *
	 * @since 2.0.11
	 *
	 * @param array  $base_field The base field.
	 * @param string $data_key_name The data key name.
	 * @param string $label_key_field The field label name.
	 * @return array
	 */
	public function build_select_alternative(&$base_field, $data_key_name, $label_key_field) {

		$base_field['wrapper_html_attr']['v-if'] = "!{$data_key_name}.length";

		$field = $base_field;

		$option_template = sprintf('<option v-for="item in %s" :value="item.code">
			{{ item.name }}
		</option>', $data_key_name);

		$field['type']                      = 'select';
		$field['options_template']          = $option_template;
		$field['options']                   = array();
		$field['required']                  = true;
		$field['wrapper_html_attr']['v-if'] = "{$data_key_name}.length";
		$field['html_attr']['required']     = 'required';
		$field['html_attr']['required']     = 'required';
		$field['html_attr']['v-bind:name']  = "'billing_" . str_replace('_list', '', $data_key_name) . "'";
		$field['title']                     = sprintf('<span v-html="%s">%s</span>', "labels.$label_key_field", $field['title']);

		return $field;

	} // end build_select_alternative;

	/**
	 * Returns the field/element actual field array to be used on the checkout form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $attributes Attributes saved on the editor form.
	 * @return array An array of fields, not the field itself.
	 */
	public function to_fields_array($attributes) {

		$zip_only = wu_string_to_bool($attributes['zip_and_country']);

		$customer = wu_get_current_customer();

		/*
		 * Checks for an existing customer
		 */
		if ($customer) {

			$fields = $customer->get_billing_address()->get_fields($zip_only);

		} else {

			$checkout_form = \WP_Ultimo\Checkout\Checkout::get_instance()->checkout_form;

			$fields = \WP_Ultimo\Objects\Billing_Address::fields($zip_only, $checkout_form);

		} // end if;

		if (isset($fields['billing_country'])) {

			$fields['billing_country']['html_attr'] = array(
				'v-model' => 'country',
			);

		} // end if;

		if (!$zip_only) {

			if (isset($fields['billing_state'])) {

				$fields['billing_state']['html_attr'] = array(
					'v-model.lazy' => 'state',
				);

				/**
				 * Format the state field accordingly.
				 *
				 * @since 2.0.11
				 */
				$fields['billing_state_select'] = $this->build_select_alternative($fields['billing_state'], 'state_list', 'state_field');

			} // end if;

			if (isset($fields['billing_city'])) {

				$fields['billing_city']['html_attr'] = array(
					'v-model.lazy' => 'city',
				);

				/**
				 * Format the city field accordingly.
				 *
				 * @since 2.0.11
				 */
				$fields['billing_city_select'] = $this->build_select_alternative($fields['billing_city'], 'city_list', 'city_field');

			} // end if;

		} // end if;

		foreach ($fields as &$field) {

			$field['wrapper_classes'] = trim(wu_get_isset($field, 'wrapper_classes', '') . ' ' . $attributes['element_classes']);

		} // end foreach;

		uasort($fields, 'wu_sort_by_order');

		return $fields;

	} // end to_fields_array;

} // end class Signup_Field_Billing_Address;
