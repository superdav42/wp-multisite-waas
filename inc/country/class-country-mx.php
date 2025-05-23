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
	protected $attributes = [
		'country_code' => 'MX',
		'currency'     => 'MXN',
		'phone_code'   => 52,
	];

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

		return __('Mexico', 'wp-multisite-waas');
	}

	/**
	 * Returns the list of states for MX.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return [
			'AGU'  => __('Aguascalientes', 'wp-multisite-waas'),
			'BCN'  => __('Baja California', 'wp-multisite-waas'),
			'BCS'  => __('Baja California Sur', 'wp-multisite-waas'),
			'CAM'  => __('Campeche', 'wp-multisite-waas'),
			'CHP'  => __('Chiapas', 'wp-multisite-waas'),
			'CHH'  => __('Chihuahua', 'wp-multisite-waas'),
			'CDMX' => __('Ciudad de México', 'wp-multisite-waas'),
			'COA'  => __('Coahuila de Zaragoza', 'wp-multisite-waas'),
			'COL'  => __('Colima', 'wp-multisite-waas'),
			'DUR'  => __('Durango', 'wp-multisite-waas'),
			'MEX'  => __('Estado de México', 'wp-multisite-waas'),
			'GUA'  => __('Guanajuato', 'wp-multisite-waas'),
			'GRO'  => __('Guerrero', 'wp-multisite-waas'),
			'HID'  => __('Hidalgo', 'wp-multisite-waas'),
			'JAL'  => __('Jalisco', 'wp-multisite-waas'),
			'MIC'  => __('Michoacán de Ocampo', 'wp-multisite-waas'),
			'MOR'  => __('Morelos', 'wp-multisite-waas'),
			'NAY'  => __('Nayarit', 'wp-multisite-waas'),
			'NLE'  => __('Nuevo León', 'wp-multisite-waas'),
			'OAX'  => __('Oaxaca', 'wp-multisite-waas'),
			'PUE'  => __('Puebla', 'wp-multisite-waas'),
			'QUE'  => __('Querétaro', 'wp-multisite-waas'),
			'ROO'  => __('Quintana Roo', 'wp-multisite-waas'),
			'SLP'  => __('San Luis Potosí', 'wp-multisite-waas'),
			'SIN'  => __('Sinaloa', 'wp-multisite-waas'),
			'SON'  => __('Sonora', 'wp-multisite-waas'),
			'TAB'  => __('Tabasco', 'wp-multisite-waas'),
			'TAM'  => __('Tamaulipas', 'wp-multisite-waas'),
			'TLA'  => __('Tlaxcala', 'wp-multisite-waas'),
			'VER'  => __('Veracruz de Ignacio de la Llave', 'wp-multisite-waas'),
			'YUC'  => __('Yucatán', 'wp-multisite-waas'),
			'ZAC'  => __('Zacatecas', 'wp-multisite-waas'),
		];
	}
}
