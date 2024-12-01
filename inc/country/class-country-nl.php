<?php // phpcs:ignore - @generation-checksum NL-15-1740
/**
 * Country Class for Netherlands (NL).
 *
 * State/province count: 15
 * City count: 1740
 * City count per state/province:
 * - NB: 317 cities
 * - GE: 271 cities
 * - ZH: 224 cities
 * - LI: 205 cities
 * - NH: 172 cities
 * - FR: 132 cities
 * - OV: 90 cities
 * - UT: 86 cities
 * - GR: 79 cities
 * - DR: 76 cities
 * - ZE: 74 cities
 * - FL: 14 cities
 *
 * @package WP_Ultimo\Country
 * @since 2.0.11
 */

namespace WP_Ultimo\Country;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Country Class for Netherlands (NL).
 *
 * IMPORTANT:
 * This file is generated by build scripts, do not
 * change it directly or your changes will be LOST!
 *
 * @since 2.0.11
 *
 * @property-read string $code
 * @property-read string $currency
 * @property-read int $phone_code
 */
class Country_NL extends Country {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * General country attributes.
	 *
	 * This might be useful, might be not.
	 * In case of doubt, keep it.
	 *
	 * @since 2.0.11
	 * @var array
	 */
	protected $attributes = array(
		'country_code' => 'NL',
		'currency'     => 'EUR',
		'phone_code'   => 31,
	);

	/**
	 * The type of nomenclature used to refer to the country sub-divisions.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $state_type = 'province';

	/**
	 * Return the country name.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	public function get_name() {

		return __('Netherlands', 'wp-ultimo-locations');

	} // end get_name;

	/**
	 * Returns the list of states for NL.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return array(
			'BQ1' => __('Bonaire', 'wp-ultimo-locations'),
			'DR'  => __('Drenthe', 'wp-ultimo-locations'),
			'FL'  => __('Flevoland', 'wp-ultimo-locations'),
			'FR'  => __('Friesland', 'wp-ultimo-locations'),
			'GE'  => __('Gelderland', 'wp-ultimo-locations'),
			'GR'  => __('Groningen', 'wp-ultimo-locations'),
			'LI'  => __('Limburg', 'wp-ultimo-locations'),
			'NB'  => __('North Brabant', 'wp-ultimo-locations'),
			'NH'  => __('North Holland', 'wp-ultimo-locations'),
			'OV'  => __('Overijssel', 'wp-ultimo-locations'),
			'BQ2' => __('Saba', 'wp-ultimo-locations'),
			'BQ3' => __('Sint Eustatius', 'wp-ultimo-locations'),
			'ZH'  => __('South Holland', 'wp-ultimo-locations'),
			'UT'  => __('Utrecht', 'wp-ultimo-locations'),
			'ZE'  => __('Zeeland', 'wp-ultimo-locations'),
		);

	} // end states;

} // end class Country_NL;