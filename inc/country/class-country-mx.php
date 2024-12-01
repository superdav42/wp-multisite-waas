<?php // phpcs:ignore - @generation-checksum MX-32-9174
/**
 * Country Class for Mexico (MX).
 *
 * State/province count: 32
 * City count: 9174
 * City count per state/province:
 * - MEX: 940 cities
 * - JAL: 821 cities
 * - VER: 764 cities
 * - PUE: 761 cities
 * - OAX: 694 cities
 * - CHP: 534 cities
 * - GUA: 476 cities
 * - MIC: 442 cities
 * - GRO: 433 cities
 * - HID: 417 cities
 * - MOR: 253 cities
 * - SIN: 221 cities
 * - TAB: 214 cities
 * - QUE: 202 cities
 * - YUC: 200 cities
 * - SLP: 177 cities
 * - ZAC: 175 cities
 * - SON: 160 cities
 * - TLA: 154 cities
 * - CHH: 148 cities
 * - DUR: 132 cities
 * - NAY: 118 cities
 * - COA: 111 cities
 * - TAM: 110 cities
 * - BCN: 95 cities
 * - NLE: 92 cities
 * - AGU: 91 cities
 * - CAM: 73 cities
 * - ROO: 64 cities
 * - CDMX: 38 cities
 * - COL: 33 cities
 * - BCS: 31 cities
 *
 * @package WP_Ultimo\Country
 * @since 2.0.11
 */

namespace WP_Ultimo\Country;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Country Class for Mexico (MX).
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
class Country_MX extends Country {

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
		'country_code' => 'MX',
		'currency'     => 'MXN',
		'phone_code'   => 52,
	);

	/**
	 * The type of nomenclature used to refer to the country sub-divisions.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $state_type = 'unknown';

	/**
	 * Return the country name.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	public function get_name() {

		return __('Mexico', 'wp-ultimo-locations');

	} // end get_name;

	/**
	 * Returns the list of states for MX.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return array(
			'AGU'  => __('Aguascalientes', 'wp-ultimo-locations'),
			'BCN'  => __('Baja California', 'wp-ultimo-locations'),
			'BCS'  => __('Baja California Sur', 'wp-ultimo-locations'),
			'CAM'  => __('Campeche', 'wp-ultimo-locations'),
			'CHP'  => __('Chiapas', 'wp-ultimo-locations'),
			'CHH'  => __('Chihuahua', 'wp-ultimo-locations'),
			'CDMX' => __('Ciudad de México', 'wp-ultimo-locations'),
			'COA'  => __('Coahuila de Zaragoza', 'wp-ultimo-locations'),
			'COL'  => __('Colima', 'wp-ultimo-locations'),
			'DUR'  => __('Durango', 'wp-ultimo-locations'),
			'MEX'  => __('Estado de México', 'wp-ultimo-locations'),
			'GUA'  => __('Guanajuato', 'wp-ultimo-locations'),
			'GRO'  => __('Guerrero', 'wp-ultimo-locations'),
			'HID'  => __('Hidalgo', 'wp-ultimo-locations'),
			'JAL'  => __('Jalisco', 'wp-ultimo-locations'),
			'MIC'  => __('Michoacán de Ocampo', 'wp-ultimo-locations'),
			'MOR'  => __('Morelos', 'wp-ultimo-locations'),
			'NAY'  => __('Nayarit', 'wp-ultimo-locations'),
			'NLE'  => __('Nuevo León', 'wp-ultimo-locations'),
			'OAX'  => __('Oaxaca', 'wp-ultimo-locations'),
			'PUE'  => __('Puebla', 'wp-ultimo-locations'),
			'QUE'  => __('Querétaro', 'wp-ultimo-locations'),
			'ROO'  => __('Quintana Roo', 'wp-ultimo-locations'),
			'SLP'  => __('San Luis Potosí', 'wp-ultimo-locations'),
			'SIN'  => __('Sinaloa', 'wp-ultimo-locations'),
			'SON'  => __('Sonora', 'wp-ultimo-locations'),
			'TAB'  => __('Tabasco', 'wp-ultimo-locations'),
			'TAM'  => __('Tamaulipas', 'wp-ultimo-locations'),
			'TLA'  => __('Tlaxcala', 'wp-ultimo-locations'),
			'VER'  => __('Veracruz de Ignacio de la Llave', 'wp-ultimo-locations'),
			'YUC'  => __('Yucatán', 'wp-ultimo-locations'),
			'ZAC'  => __('Zacatecas', 'wp-ultimo-locations'),
		);

	} // end states;

} // end class Country_MX;