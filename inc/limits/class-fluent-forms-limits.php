<?php
/**
 * Handles limitations to Fluent Forms.
 *
 * @package WP_Ultimo
 * @subpackage Limits
 * @since 2.0.0
 */

namespace WP_Ultimo\Limits;

// Exit if accessed directly
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

		// Hook into Fluent Forms form creation
		add_action('fluentform/form_created', [$this, 'check_form_creation_limit'], 10, 2);

		// Hook into form status changes
		add_filter('fluentform/form_store_attributes', [$this, 'check_form_before_creation'], 10, 1);

		// Admin notices for limit reached
		add_action('admin_notices', [$this, 'show_form_limit_notices']);

		// Block form duplication if limits are reached
		add_action('fluentform/before_form_duplicate', [$this, 'check_form_duplication_limit'], 10, 2);
	}

	/**
	 * Check if form creation should be allowed before the form is stored.
	 *
	 * @since 2.0.0
	 *
	 * @param array $attributes Form attributes being stored.
	 * @return array
	 */
	public function check_form_before_creation($attributes) {

		if (is_main_site()) {
			return $attributes;
		}

		$form_type   = $this->get_form_type_from_attributes($attributes);
		$limitations = wu_get_current_site()->get_limitations()->fluent_forms;

		// Check if the form type is enabled
		if ( ! $limitations->{$form_type}->enabled) {
			$this->block_form_creation($form_type, __('Your plan does not support this form type.', 'multisite-ultimate'));
		}

		// Check if we're above the limit
		if ($limitations->is_form_above_limit($form_type)) {
			$this->block_form_creation($form_type, __('You have reached your form creation limit for this plan.', 'multisite-ultimate'));
		}

		return $attributes;
	}

	/**
	 * Check form creation limits after form is created (backup check).
	 *
	 * @since 2.0.0
	 *
	 * @param int   $form_id The created form ID.
	 * @param array $form_data The form data.
	 * @return void
	 */
	public function check_form_creation_limit($form_id, $form_data) {

		if (is_main_site()) {
			return;
		}

		$form_type   = $this->get_form_type_from_form_id($form_id);
		$limitations = wu_get_current_site()->get_limitations()->fluent_forms;

		// If we somehow got past the initial check and are now over limit
		if ($limitations->is_form_above_limit($form_type)) {
			// Delete the form that was just created
			$this->delete_form_by_id($form_id);

			$this->block_form_creation($form_type, __('You have reached your form creation limit for this plan.', 'multisite-ultimate'));
		}
	}

	/**
	 * Check form duplication limits.
	 *
	 * @since 2.0.0
	 *
	 * @param int $form_id The form ID being duplicated.
	 * @param int $new_form_id The new form ID being created.
	 * @return void
	 */
	public function check_form_duplication_limit($form_id, $new_form_id) {

		if (is_main_site()) {
			return;
		}

		$form_type   = $this->get_form_type_from_form_id($form_id);
		$limitations = wu_get_current_site()->get_limitations()->fluent_forms;

		// Check if we would be above the limit with this duplication
		if ($limitations->is_form_above_limit($form_type)) {
			$this->block_form_creation($form_type, __('You cannot duplicate this form as it would exceed your plan\'s form limit.', 'multisite-ultimate'));
		}
	}

	/**
	 * Show admin notices for form limits.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function show_form_limit_notices() {

		if (is_main_site()) {
			return;
		}

		// Only show on Fluent Forms pages
		$screen = get_current_screen();
		if ( ! $screen || strpos($screen->id, 'fluent') === false) {
			return;
		}

		$limitations = wu_get_current_site()->get_limitations()->fluent_forms;
		$overlimits  = $limitations->check_all_form_types();

		if ( ! empty($overlimits)) {
			foreach ($overlimits as $form_type => $limit_info) {
				$form_type_label = $this->get_form_type_label($form_type);

				echo '<div class="notice notice-warning is-dismissible">';
				echo '<p>';
				printf(
					// translators: %1$s: form type label, %2$d: current count, %3$d: limit
					esc_html__('Warning: You have %2$d %1$s, which exceeds your plan limit of %3$d. Some functionality may be restricted.', 'multisite-ultimate'),
					esc_html($form_type_label),
					(int) $limit_info['current'],
					(int) $limit_info['limit']
				);
				echo '</p>';
				echo '</div>';
			}
		}
	}

	/**
	 * Block form creation with a proper error message.
	 *
	 * @since 2.0.0
	 *
	 * @param string $form_type The form type.
	 * @param string $message The error message.
	 * @return void
	 */
	protected function block_form_creation($form_type, $message) {

		$form_type_label = $this->get_form_type_label($form_type);
		$upgrade_url     = wu_generate_upgrade_to_unlock_url(
			[
				'module' => 'fluent_forms',
				'type'   => $form_type,
			]
		);

		$full_message = $message;

		if ($upgrade_url) {
			$full_message .= ' ' . sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url($upgrade_url),
				esc_html__('Upgrade your plan', 'multisite-ultimate')
			);
		}

		wp_die(
			wp_kses_post($full_message),
			esc_html__('Form Creation Limit Reached', 'multisite-ultimate'),
			['back_link' => true]
		);
	}

	/**
	 * Get form type from form attributes during creation.
	 *
	 * @since 2.0.0
	 *
	 * @param array $attributes Form attributes.
	 * @return string
	 */
	protected function get_form_type_from_attributes($attributes) {

		// Check if it's marked as a conversational form
		$type = wu_get_isset($attributes, 'type', 'form');

		if ('conversational' === $type) {
			return 'conversational_forms';
		}

		// Default to regular forms
		return 'forms';
	}

	/**
	 * Get form type from existing form ID.
	 *
	 * @since 2.0.0
	 *
	 * @param int $form_id The form ID.
	 * @return string
	 */
	protected function get_form_type_from_form_id($form_id) {

		if ( ! class_exists('FluentForm\App\Models\Form')) {
			return 'forms';
		}

		try {
			// Check if the form has conversational meta using Fluent Forms ORM
			$form = \FluentForm\App\Models\Form::with('conversationalMeta')->find($form_id);
			
			if ($form && $form->conversationalMeta && '1' === $form->conversationalMeta->meta_value) {
				return 'conversational_forms';
			}
		} catch (\Exception $e) {
			// If there's an error, default to regular forms
		}

		return 'forms';
	}

	/**
	 * Get human-readable form type label.
	 *
	 * @since 2.0.0
	 *
	 * @param string $form_type The form type.
	 * @return string
	 */
	protected function get_form_type_label($form_type) {

		$labels = [
			'forms'                => __('Forms', 'multisite-ultimate'),
			'conversational_forms' => __('Conversational Forms', 'multisite-ultimate'),
		];

		return wu_get_isset($labels, $form_type, __('Forms', 'multisite-ultimate'));
	}

	/**
	 * Delete a form by ID (cleanup function).
	 *
	 * @since 2.0.0
	 *
	 * @param int $form_id The form ID to delete.
	 * @return bool
	 */
	protected function delete_form_by_id($form_id) {

		if ( ! class_exists('FluentForm\App\Models\Form')) {
			return false;
		}

		try {
			\FluentForm\App\Models\Form::remove($form_id);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}
