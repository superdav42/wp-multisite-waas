<?php
/**
 * Fluent Forms Limit Module.
 *
 * @package WP_Ultimo
 * @subpackage Limitations
 * @since 2.0.0
 */

namespace WP_Ultimo\Limitations;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Fluent Forms Limit Module.
 *
 * @since 2.0.0
 */
class Limit_Fluent_Forms extends Limit_Subtype {

	/**
	 * The module id.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $id = 'fluent_forms';

	/**
	 * Check if we are already above the form quota.
	 *
	 * @since 2.0.0
	 *
	 * @param string $form_type The form type to check against ('forms' or 'conversational_forms').
	 * @return boolean
	 */
	public function is_form_above_limit($form_type) {

		// Get the current form count
		$form_count = static::get_form_count($form_type);

		// Get the allowed quota
		$quota = $this->{$form_type}->number;

		/**
		 * Checks if a given form type is allowed on this plan
		 * Allow plugin developers to filter the return value
		 *
		 * @since 2.0.0
		 * @param bool $value If the form type is above limit or not
		 * @param string $form_type The form type being checked
		 * @param int $form_count Current form count
		 * @param int $quota The allowed quota
		 */
		return apply_filters('wu_limits_is_fluent_form_above_limit', $quota > 0 && $form_count >= $quota, $form_type, $form_count, $quota);
	}

	/**
	 * Checks if any form types are currently over the limit.
	 *
	 * @return array
	 */
	public function check_all_form_types() {

		$overlimits = [];

		if (empty($this->limit)) {
			return $overlimits;
		}

		foreach ($this->limit as $form_type => $limit) {

			// Get current form count
			$form_count = $this->get_form_count($form_type);

			// Get the allowed quota
			$quota = $limit['number'];

			/**
			 * Checks if a given form type is above the limit
			 *
			 * @since 2.0.0
			 * @param bool $value If the form type is above limit or not
			 * @param string $form_type The form type being checked
			 * @param int $form_count Current form count
			 * @param int $quota The allowed quota
			 */
			$is_above_limit = apply_filters('wu_limits_is_fluent_form_above_limit', $quota > 0 && $form_count > $quota, $form_type, $form_count, $quota);

			if ($is_above_limit) {
				$overlimits[ $form_type ] = [
					'current' => $form_count,
					'limit'   => (int) $limit['number'],
				];
			}
		}

		return $overlimits;
	}

	/**
	 * Get the form count for this site.
	 *
	 * @since 2.0.0
	 *
	 * @param string $form_type The form type to check against ('forms' or 'conversational_forms').
	 * @return int
	 */
	public static function get_form_count($form_type) {

		// Check if Fluent Forms is active
		if ( ! class_exists('FluentForm\App\Models\Form')) {
			return 0;
		}

		$count = 0;

		try {

			if ('forms' === $form_type) {

				// Count all regular forms (excluding conversational forms)
				$count = \FluentForm\App\Models\Form::where('status', '!=', 'trashed')
					->whereDoesntHave('conversationalMeta', function($query) {
						// No additional filtering needed - the relationship already handles meta_key filtering
					})
					->count();

			} elseif ('conversational_forms' === $form_type) {

				// Count conversational forms only
				$count = \FluentForm\App\Models\Form::where('status', '!=', 'trashed')
					->whereHas('conversationalMeta', function($query) {
						// No additional filtering needed - the relationship already handles meta_key filtering
					})
					->count();
			}
		} catch (\Exception $e) {
			// If there's any error, return 0 to be safe
			$count = 0;
		}

		/**
		 * Allow plugin developers to change the form count total
		 *
		 * @since 2.0.0
		 * @param int $count The total form count
		 * @param string $form_type The form type slug
		 * @return int New total
		 */
		return apply_filters('wu_fluent_form_count', $count, $form_type);
	}

	/**
	 * Check if Fluent Forms is active and available.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public static function is_fluent_forms_available(): bool {

		return class_exists('FluentForm\App\Models\Form') && function_exists('wpFluent');
	}
}
