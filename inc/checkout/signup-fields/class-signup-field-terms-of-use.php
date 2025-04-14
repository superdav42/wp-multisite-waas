<?php
/**
 * Creates a cart with the parameters of the purchase being placed.
 *
 * @package WP_Ultimo
 * @subpackage Order
 * @since 2.0.0
 */

namespace WP_Ultimo\Checkout\Signup_Fields;

use WP_Ultimo\Checkout\Signup_Fields\Base_Signup_Field;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Creates an cart with the parameters of the purchase being placed.
 *
 * @package WP_Ultimo
 * @subpackage Checkout
 * @since 2.0.0
 */
class Signup_Field_Terms_Of_Use extends Base_Signup_Field {

	/**
	 * Returns the type of the field.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_type() {

		return 'terms_of_use';
	}

	/**
	 * Returns if this field should be present on the checkout flow or not.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_required() {

		return false;
	}

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

		return false;
	}

	/**
	 * Requires the title of the field/element type.
	 *
	 * This is used on the Field/Element selection screen.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_title() {

		return __('Terms of Use', 'wp-multisite-waas');
	}

	/**
	 * Returns the description of the field/element.
	 *
	 * This is used as the title attribute of the selector.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_description() {

		return __('Adds a terms and conditions checkbox that must be marked before the account/site can be created.', 'wp-multisite-waas');
	}

	/**
	 * Returns the tooltip of the field/element.
	 *
	 * This is used as the tooltip attribute of the selector.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_tooltip() {

		return __('Adds a terms and conditions checkbox that must be marked before the account/site can be created.', 'wp-multisite-waas');
	}

	/**
	 * Returns the icon to be used on the selector.
	 *
	 * Can be either a dashicon class or a wu-dashicon class.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_icon() {

		return 'dashicons-wu-file-text';
	}

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

		return [
			'tou_name' => __('I agree with the terms of use.', 'wp-multisite-waas'),
		];
	}

	/**
	 * List of keys of the default fields we want to display on the builder.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function default_fields() {

		return [];
	}

	/**
	 * If you want to force a particular attribute to a value, declare it here.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function force_attributes() {

		return [
			'id'   => 'terms_of_use',
			'name' => __('Terms of Use', 'wp-multisite-waas'),
		];
	}

	/**
	 * Returns the list of additional fields specific to this type.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_fields() {

		return [
			'tou_name' => [
				'order'       => 10,
				'type'        => 'text',
				'title'       => __('Terms Checkbox Label', 'wp-multisite-waas'),
				'placeholder' => __('e.g. I agree with the terms of use.', 'wp-multisite-waas'),
			],
			'tou_url'  => [
				'order'       => 20,
				'type'        => 'url',
				'title'       => __('Link to the Terms Page', 'wp-multisite-waas'),
				'desc'        => __('Enter the link to the terms of use content.', 'wp-multisite-waas'),
				'placeholder' => __('e.g. https://yoursite.com/terms', 'wp-multisite-waas'),
			],
		];
	}

	/**
	 * Returns the field/element actual field array to be used on the checkout form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $attributes Attributes saved on the editor form.
	 * @return array An array of fields, not the field itself.
	 */
	public function to_fields_array($attributes) {

		$checkout_fields = [];

		$tou_link = sprintf('<a href="%s" target="_blank">%s</a>', $attributes['tou_url'], __('Read here', 'wp-multisite-waas'));

		$checkout_fields['terms_of_use'] = [
			'type'            => 'checkbox',
			'id'              => 'terms_of_use',
			'name'            => $attributes['tou_name'] . ' - ',
			'desc'            => $tou_link,
			'wrapper_classes' => $attributes['element_classes'],
			'required'        => true,
		];

		return $checkout_fields;
	}
}
