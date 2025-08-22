<?php
/**
 * Handles limitations to Fluent Forms.
 *
 * @package WP_Ultimo
 * @subpackage Limits
 * @since 2.0.0
 */

namespace WP_Ultimo\Limits;

use FluentForm\App\Models\Form;

defined('ABSPATH') || exit;

/**
 * Handles limitations to Fluent Forms.
 *
 * @since 2.0.0
 */
class Fluent_Forms_Limits {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Runs on the first and only instantiation.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init(): void {

		// Check if Fluent Forms is active first
		if ( ! \WP_Ultimo\Limitations\Limit_Fluent_Forms::is_fluent_forms_available()) {
			return;
		}

		/**
		 * Allow plugin developers to short-circuit the limitations.
		 *
		 * @since 2.0.0
		 * @return bool
		 */
		if ( ! apply_filters('wu_apply_plan_limits', wu_get_current_site()->has_limitations())) {
			return;
		}

		if ( ! wu_get_current_site()->has_module_limitation('fluent_forms')) {
			return;
		}

		Form::creating([$this, 'check_form_before_creation']);
	}

	/**
	 * Check if form creation should be allowed before the form is stored.
	 *
	 * @since 2.0.0
	 *
	 * @param Form $form Form attributes being stored.
	 *
	 * @throws \Exception When the limit is reached.
	 * @return null|false
	 */
	public function check_form_before_creation($form) {

		if (is_main_site()) {
			return null;
		}

		$form_type = (isset($_REQUEST['predefined']) && 'conversational' === $_REQUEST['predefined']) ? 'conversational_forms' : 'forms'; // phpcs:ignore WordPress.Security.NonceVerification

		$limitations = wu_get_current_site()->get_limitations()->fluent_forms;

		// Check if the form type is enabled
		if ( ! $limitations->{$form_type}->enabled) {
			switch ($form_type) {
				case 'conversational_forms':
					throw new \Exception(esc_html__('Your plan does not support creating Conversational Fluent Forms.', 'multisite-ultimate'));
				case 'forms':
					throw new \Exception(esc_html__('Your plan does not support creating Fluent Forms.', 'multisite-ultimate'));
			}
		}

		// Check if we're above the limit
		if ($limitations->is_form_above_limit($form_type)) {
			switch ($form_type) {
				case 'conversational_forms':
					throw new \Exception(esc_html__('You have reached the limit of Conversational Fluent Forms. Please upgrade your plan.', 'multisite-ultimate'));
				case 'forms':
					throw new \Exception(esc_html__('You have reached the limit of Fluent Forms. Please upgrade your plan.', 'multisite-ultimate'));
			}
		}

		return null;
	}
}
