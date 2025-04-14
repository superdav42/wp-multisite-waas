<?php
/**
 * Country Functions
 *
 * @package WP_Ultimo\Functions
 * @since   2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Returns the list of countries.
 *
 * @since 2.0.0
 * @return array
 */
function wu_get_countries() {

	return apply_filters(
		'wu_get_countries',
		[
			'AF' => __('Afghanistan', 'wp-multisite-waas'),
			'AX' => __('&#197;land Islands', 'wp-multisite-waas'),
			'AL' => __('Albania', 'wp-multisite-waas'),
			'DZ' => __('Algeria', 'wp-multisite-waas'),
			'AS' => __('American Samoa', 'wp-multisite-waas'),
			'AD' => __('Andorra', 'wp-multisite-waas'),
			'AO' => __('Angola', 'wp-multisite-waas'),
			'AI' => __('Anguilla', 'wp-multisite-waas'),
			'AQ' => __('Antarctica', 'wp-multisite-waas'),
			'AG' => __('Antigua and Barbuda', 'wp-multisite-waas'),
			'AR' => __('Argentina', 'wp-multisite-waas'),
			'AM' => __('Armenia', 'wp-multisite-waas'),
			'AW' => __('Aruba', 'wp-multisite-waas'),
			'AU' => __('Australia', 'wp-multisite-waas'),
			'AT' => __('Austria', 'wp-multisite-waas'),
			'AZ' => __('Azerbaijan', 'wp-multisite-waas'),
			'BS' => __('Bahamas', 'wp-multisite-waas'),
			'BH' => __('Bahrain', 'wp-multisite-waas'),
			'BD' => __('Bangladesh', 'wp-multisite-waas'),
			'BB' => __('Barbados', 'wp-multisite-waas'),
			'BY' => __('Belarus', 'wp-multisite-waas'),
			'BE' => __('Belgium', 'wp-multisite-waas'),
			'PW' => __('Belau', 'wp-multisite-waas'),
			'BZ' => __('Belize', 'wp-multisite-waas'),
			'BJ' => __('Benin', 'wp-multisite-waas'),
			'BM' => __('Bermuda', 'wp-multisite-waas'),
			'BT' => __('Bhutan', 'wp-multisite-waas'),
			'BO' => __('Bolivia', 'wp-multisite-waas'),
			'BQ' => __('Bonaire, Saint Eustatius and Saba', 'wp-multisite-waas'),
			'BA' => __('Bosnia and Herzegovina', 'wp-multisite-waas'),
			'BW' => __('Botswana', 'wp-multisite-waas'),
			'BV' => __('Bouvet Island', 'wp-multisite-waas'),
			'BR' => __('Brazil', 'wp-multisite-waas'),
			'IO' => __('British Indian Ocean Territory', 'wp-multisite-waas'),
			'VG' => __('British Virgin Islands', 'wp-multisite-waas'),
			'BN' => __('Brunei', 'wp-multisite-waas'),
			'BG' => __('Bulgaria', 'wp-multisite-waas'),
			'BF' => __('Burkina Faso', 'wp-multisite-waas'),
			'BI' => __('Burundi', 'wp-multisite-waas'),
			'KH' => __('Cambodia', 'wp-multisite-waas'),
			'CM' => __('Cameroon', 'wp-multisite-waas'),
			'CA' => __('Canada', 'wp-multisite-waas'),
			'CV' => __('Cape Verde', 'wp-multisite-waas'),
			'KY' => __('Cayman Islands', 'wp-multisite-waas'),
			'CF' => __('Central African Republic', 'wp-multisite-waas'),
			'TD' => __('Chad', 'wp-multisite-waas'),
			'CL' => __('Chile', 'wp-multisite-waas'),
			'CN' => __('China', 'wp-multisite-waas'),
			'CX' => __('Christmas Island', 'wp-multisite-waas'),
			'CC' => __('Cocos (Keeling) Islands', 'wp-multisite-waas'),
			'CO' => __('Colombia', 'wp-multisite-waas'),
			'KM' => __('Comoros', 'wp-multisite-waas'),
			'CG' => __('Congo (Brazzaville)', 'wp-multisite-waas'),
			'CD' => __('Congo (Kinshasa)', 'wp-multisite-waas'),
			'CK' => __('Cook Islands', 'wp-multisite-waas'),
			'CR' => __('Costa Rica', 'wp-multisite-waas'),
			'HR' => __('Croatia', 'wp-multisite-waas'),
			'CU' => __('Cuba', 'wp-multisite-waas'),
			'CW' => __('Cura&ccedil;ao', 'wp-multisite-waas'),
			'CY' => __('Cyprus', 'wp-multisite-waas'),
			'CZ' => __('Czech Republic', 'wp-multisite-waas'),
			'DK' => __('Denmark', 'wp-multisite-waas'),
			'DJ' => __('Djibouti', 'wp-multisite-waas'),
			'DM' => __('Dominica', 'wp-multisite-waas'),
			'DO' => __('Dominican Republic', 'wp-multisite-waas'),
			'EC' => __('Ecuador', 'wp-multisite-waas'),
			'EG' => __('Egypt', 'wp-multisite-waas'),
			'SV' => __('El Salvador', 'wp-multisite-waas'),
			'GQ' => __('Equatorial Guinea', 'wp-multisite-waas'),
			'ER' => __('Eritrea', 'wp-multisite-waas'),
			'EE' => __('Estonia', 'wp-multisite-waas'),
			'ET' => __('Ethiopia', 'wp-multisite-waas'),
			'FK' => __('Falkland Islands', 'wp-multisite-waas'),
			'FO' => __('Faroe Islands', 'wp-multisite-waas'),
			'FJ' => __('Fiji', 'wp-multisite-waas'),
			'FI' => __('Finland', 'wp-multisite-waas'),
			'FR' => __('France', 'wp-multisite-waas'),
			'GF' => __('French Guiana', 'wp-multisite-waas'),
			'PF' => __('French Polynesia', 'wp-multisite-waas'),
			'TF' => __('French Southern Territories', 'wp-multisite-waas'),
			'GA' => __('Gabon', 'wp-multisite-waas'),
			'GM' => __('Gambia', 'wp-multisite-waas'),
			'GE' => __('Georgia', 'wp-multisite-waas'),
			'DE' => __('Germany', 'wp-multisite-waas'),
			'GH' => __('Ghana', 'wp-multisite-waas'),
			'GI' => __('Gibraltar', 'wp-multisite-waas'),
			'GR' => __('Greece', 'wp-multisite-waas'),
			'GL' => __('Greenland', 'wp-multisite-waas'),
			'GD' => __('Grenada', 'wp-multisite-waas'),
			'GP' => __('Guadeloupe', 'wp-multisite-waas'),
			'GU' => __('Guam', 'wp-multisite-waas'),
			'GT' => __('Guatemala', 'wp-multisite-waas'),
			'GG' => __('Guernsey', 'wp-multisite-waas'),
			'GN' => __('Guinea', 'wp-multisite-waas'),
			'GW' => __('Guinea-Bissau', 'wp-multisite-waas'),
			'GY' => __('Guyana', 'wp-multisite-waas'),
			'HT' => __('Haiti', 'wp-multisite-waas'),
			'HM' => __('Heard Island and McDonald Islands', 'wp-multisite-waas'),
			'HN' => __('Honduras', 'wp-multisite-waas'),
			'HK' => __('Hong Kong', 'wp-multisite-waas'),
			'HU' => __('Hungary', 'wp-multisite-waas'),
			'IS' => __('Iceland', 'wp-multisite-waas'),
			'IN' => __('India', 'wp-multisite-waas'),
			'ID' => __('Indonesia', 'wp-multisite-waas'),
			'IR' => __('Iran', 'wp-multisite-waas'),
			'IQ' => __('Iraq', 'wp-multisite-waas'),
			'IE' => __('Ireland', 'wp-multisite-waas'),
			'IM' => __('Isle of Man', 'wp-multisite-waas'),
			'IL' => __('Israel', 'wp-multisite-waas'),
			'IT' => __('Italy', 'wp-multisite-waas'),
			'CI' => __('Ivory Coast', 'wp-multisite-waas'),
			'JM' => __('Jamaica', 'wp-multisite-waas'),
			'JP' => __('Japan', 'wp-multisite-waas'),
			'JE' => __('Jersey', 'wp-multisite-waas'),
			'JO' => __('Jordan', 'wp-multisite-waas'),
			'KZ' => __('Kazakhstan', 'wp-multisite-waas'),
			'KE' => __('Kenya', 'wp-multisite-waas'),
			'KI' => __('Kiribati', 'wp-multisite-waas'),
			'KW' => __('Kuwait', 'wp-multisite-waas'),
			'KG' => __('Kyrgyzstan', 'wp-multisite-waas'),
			'LA' => __('Laos', 'wp-multisite-waas'),
			'LV' => __('Latvia', 'wp-multisite-waas'),
			'LB' => __('Lebanon', 'wp-multisite-waas'),
			'LS' => __('Lesotho', 'wp-multisite-waas'),
			'LR' => __('Liberia', 'wp-multisite-waas'),
			'LY' => __('Libya', 'wp-multisite-waas'),
			'LI' => __('Liechtenstein', 'wp-multisite-waas'),
			'LT' => __('Lithuania', 'wp-multisite-waas'),
			'LU' => __('Luxembourg', 'wp-multisite-waas'),
			'MO' => __('Macao S.A.R., China', 'wp-multisite-waas'),
			'MK' => __('Macedonia', 'wp-multisite-waas'),
			'MG' => __('Madagascar', 'wp-multisite-waas'),
			'MW' => __('Malawi', 'wp-multisite-waas'),
			'MY' => __('Malaysia', 'wp-multisite-waas'),
			'MV' => __('Maldives', 'wp-multisite-waas'),
			'ML' => __('Mali', 'wp-multisite-waas'),
			'MT' => __('Malta', 'wp-multisite-waas'),
			'MH' => __('Marshall Islands', 'wp-multisite-waas'),
			'MQ' => __('Martinique', 'wp-multisite-waas'),
			'MR' => __('Mauritania', 'wp-multisite-waas'),
			'MU' => __('Mauritius', 'wp-multisite-waas'),
			'YT' => __('Mayotte', 'wp-multisite-waas'),
			'MX' => __('Mexico', 'wp-multisite-waas'),
			'FM' => __('Micronesia', 'wp-multisite-waas'),
			'MD' => __('Moldova', 'wp-multisite-waas'),
			'MC' => __('Monaco', 'wp-multisite-waas'),
			'MN' => __('Mongolia', 'wp-multisite-waas'),
			'ME' => __('Montenegro', 'wp-multisite-waas'),
			'MS' => __('Montserrat', 'wp-multisite-waas'),
			'MA' => __('Morocco', 'wp-multisite-waas'),
			'MZ' => __('Mozambique', 'wp-multisite-waas'),
			'MM' => __('Myanmar', 'wp-multisite-waas'),
			'NA' => __('Namibia', 'wp-multisite-waas'),
			'NR' => __('Nauru', 'wp-multisite-waas'),
			'NP' => __('Nepal', 'wp-multisite-waas'),
			'NL' => __('Netherlands', 'wp-multisite-waas'),
			'NC' => __('New Caledonia', 'wp-multisite-waas'),
			'NZ' => __('New Zealand', 'wp-multisite-waas'),
			'NI' => __('Nicaragua', 'wp-multisite-waas'),
			'NE' => __('Niger', 'wp-multisite-waas'),
			'NG' => __('Nigeria', 'wp-multisite-waas'),
			'NU' => __('Niue', 'wp-multisite-waas'),
			'NF' => __('Norfolk Island', 'wp-multisite-waas'),
			'MP' => __('Northern Mariana Islands', 'wp-multisite-waas'),
			'KP' => __('North Korea', 'wp-multisite-waas'),
			'NO' => __('Norway', 'wp-multisite-waas'),
			'OM' => __('Oman', 'wp-multisite-waas'),
			'PK' => __('Pakistan', 'wp-multisite-waas'),
			'PS' => __('Palestinian Territory', 'wp-multisite-waas'),
			'PA' => __('Panama', 'wp-multisite-waas'),
			'PG' => __('Papua New Guinea', 'wp-multisite-waas'),
			'PY' => __('Paraguay', 'wp-multisite-waas'),
			'PE' => __('Peru', 'wp-multisite-waas'),
			'PH' => __('Philippines', 'wp-multisite-waas'),
			'PN' => __('Pitcairn', 'wp-multisite-waas'),
			'PL' => __('Poland', 'wp-multisite-waas'),
			'PT' => __('Portugal', 'wp-multisite-waas'),
			'PR' => __('Puerto Rico', 'wp-multisite-waas'),
			'QA' => __('Qatar', 'wp-multisite-waas'),
			'RE' => __('Reunion', 'wp-multisite-waas'),
			'RO' => __('Romania', 'wp-multisite-waas'),
			'RU' => __('Russia', 'wp-multisite-waas'),
			'RW' => __('Rwanda', 'wp-multisite-waas'),
			'BL' => __('Saint Barth&eacute;lemy', 'wp-multisite-waas'),
			'SH' => __('Saint Helena', 'wp-multisite-waas'),
			'KN' => __('Saint Kitts and Nevis', 'wp-multisite-waas'),
			'LC' => __('Saint Lucia', 'wp-multisite-waas'),
			'MF' => __('Saint Martin (French part)', 'wp-multisite-waas'),
			'SX' => __('Saint Martin (Dutch part)', 'wp-multisite-waas'),
			'PM' => __('Saint Pierre and Miquelon', 'wp-multisite-waas'),
			'VC' => __('Saint Vincent and the Grenadines', 'wp-multisite-waas'),
			'SM' => __('San Marino', 'wp-multisite-waas'),
			'ST' => __('S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wp-multisite-waas'),
			'SA' => __('Saudi Arabia', 'wp-multisite-waas'),
			'SN' => __('Senegal', 'wp-multisite-waas'),
			'RS' => __('Serbia', 'wp-multisite-waas'),
			'SC' => __('Seychelles', 'wp-multisite-waas'),
			'SL' => __('Sierra Leone', 'wp-multisite-waas'),
			'SG' => __('Singapore', 'wp-multisite-waas'),
			'SK' => __('Slovakia', 'wp-multisite-waas'),
			'SI' => __('Slovenia', 'wp-multisite-waas'),
			'SB' => __('Solomon Islands', 'wp-multisite-waas'),
			'SO' => __('Somalia', 'wp-multisite-waas'),
			'ZA' => __('South Africa', 'wp-multisite-waas'),
			'GS' => __('South Georgia/Sandwich Islands', 'wp-multisite-waas'),
			'KR' => __('South Korea', 'wp-multisite-waas'),
			'SS' => __('South Sudan', 'wp-multisite-waas'),
			'ES' => __('Spain', 'wp-multisite-waas'),
			'LK' => __('Sri Lanka', 'wp-multisite-waas'),
			'SD' => __('Sudan', 'wp-multisite-waas'),
			'SR' => __('Suriname', 'wp-multisite-waas'),
			'SJ' => __('Svalbard and Jan Mayen', 'wp-multisite-waas'),
			'SZ' => __('Swaziland', 'wp-multisite-waas'),
			'SE' => __('Sweden', 'wp-multisite-waas'),
			'CH' => __('Switzerland', 'wp-multisite-waas'),
			'SY' => __('Syria', 'wp-multisite-waas'),
			'TW' => __('Taiwan', 'wp-multisite-waas'),
			'TJ' => __('Tajikistan', 'wp-multisite-waas'),
			'TZ' => __('Tanzania', 'wp-multisite-waas'),
			'TH' => __('Thailand', 'wp-multisite-waas'),
			'TL' => __('Timor-Leste', 'wp-multisite-waas'),
			'TG' => __('Togo', 'wp-multisite-waas'),
			'TK' => __('Tokelau', 'wp-multisite-waas'),
			'TO' => __('Tonga', 'wp-multisite-waas'),
			'TT' => __('Trinidad and Tobago', 'wp-multisite-waas'),
			'TN' => __('Tunisia', 'wp-multisite-waas'),
			'TR' => __('Turkey', 'wp-multisite-waas'),
			'TM' => __('Turkmenistan', 'wp-multisite-waas'),
			'TC' => __('Turks and Caicos Islands', 'wp-multisite-waas'),
			'TV' => __('Tuvalu', 'wp-multisite-waas'),
			'UG' => __('Uganda', 'wp-multisite-waas'),
			'UA' => __('Ukraine', 'wp-multisite-waas'),
			'AE' => __('United Arab Emirates', 'wp-multisite-waas'),
			'GB' => __('United Kingdom (UK)', 'wp-multisite-waas'),
			'US' => __('United States (US)', 'wp-multisite-waas'),
			'UM' => __('United States (US) Minor Outlying Islands', 'wp-multisite-waas'),
			'VI' => __('United States (US) Virgin Islands', 'wp-multisite-waas'),
			'UY' => __('Uruguay', 'wp-multisite-waas'),
			'UZ' => __('Uzbekistan', 'wp-multisite-waas'),
			'VU' => __('Vanuatu', 'wp-multisite-waas'),
			'VA' => __('Vatican', 'wp-multisite-waas'),
			'VE' => __('Venezuela', 'wp-multisite-waas'),
			'VN' => __('Vietnam', 'wp-multisite-waas'),
			'WF' => __('Wallis and Futuna', 'wp-multisite-waas'),
			'EH' => __('Western Sahara', 'wp-multisite-waas'),
			'WS' => __('Samoa', 'wp-multisite-waas'),
			'YE' => __('Yemen', 'wp-multisite-waas'),
			'ZM' => __('Zambia', 'wp-multisite-waas'),
			'ZW' => __('Zimbabwe', 'wp-multisite-waas'),
		]
	);
}

/**
 * Returns the list of countries with an additional empty state option.
 *
 * @since 2.0.0
 * @return array
 */
function wu_get_countries_as_options() {

	return array_merge(
		[
			'' => __('Select Country', 'wp-multisite-waas'),
		],
		wu_get_countries()
	);
}

/**
 * Returns the country object.
 *
 * @since 2.0.11
 *
 * @param string      $country_code Two-letter country ISO code.
 * @param string|null $name The country name.
 * @param array       $fallback_attributes Fallback attributes if the country class is not present.
 * @return \WP_Ultimo\Country\Country
 */
function wu_get_country($country_code, $name = null, $fallback_attributes = []) {

	$country_code = strtoupper($country_code);

	$country_class = "\\WP_Ultimo\\Country\\Country_{$country_code}";

	if (class_exists($country_class)) {
		return $country_class::get_instance();
	}

	return \WP_Ultimo\Country\Country_Default::build($country_code, $name, $fallback_attributes);
}

/**
 * Get the state list for a country.
 *
 * @since 2.0.12
 *
 * @param string $country_code The country code.
 * @param string $key_name The name to use for the key entry.
 * @param string $value_name The name to use for the value entry.
 * @return array
 */
function wu_get_country_states($country_code, $key_name = 'id', $value_name = 'value') {

	static $state_options = [];

	$options = [];

	$cache = wu_get_isset($state_options, $country_code, false);

	if ($cache) {
		$options = $cache;
	} else {
		$country = wu_get_country($country_code);

		$state_options[ $country_code ] = $country->get_states_as_options(false);

		$options = $state_options[ $country_code ];
	}

	if (empty($key_name)) {
		return $options;
	}

	return wu_key_map_to_array($options, $key_name, $value_name);
}

/**
 * Get cities for a collection of states of a country.
 *
 * @since 2.0.11
 *
 * @param string $country_code The country code.
 * @param array  $states The list of state codes to retrieve.
 * @param string $key_name The name to use for the key entry.
 * @param string $value_name The name to use for the value entry.
 * @return array
 */
function wu_get_country_cities($country_code, $states, $key_name = 'id', $value_name = 'value') {

	static $city_options = [];

	$states = (array) $states;

	$options = [];

	foreach ($states as $state_code) {
		$cache = wu_get_isset($city_options, $state_code, false);

		if ($cache) {
			$options = array_merge($options, $cache);
		} else {
			$country = wu_get_country($country_code);

			$city_options[ $state_code ] = $country->get_cities_as_options($state_code, false);

			$options = array_merge($options, $city_options[ $state_code ]);
		}
	}

	if (empty($key_name)) {
		return $options;
	}

	return wu_key_map_to_array($options, $key_name, $value_name);
}

/**
 * Returns the country name for a given country code.
 *
 * @since 2.0.0
 *
 * @param string $country_code Country code.
 * @return string
 */
function wu_get_country_name($country_code) {

	$country_name = wu_get_isset(wu_get_countries(), $country_code, __('Not found', 'wp-multisite-waas'));

	return apply_filters('wu_get_country_name', $country_name, $country_code);
}

/**
 * Get the list of countries and counts based on customers.
 *
 * @since 2.0.0
 * @param integer        $count The number of results to return.
 * @param boolean|string $start_date The start date.
 * @param boolean|string $end_date The end date.
 * @return array
 */
function wu_get_countries_of_customers($count = 10, $start_date = false, $end_date = false) {

	global $wpdb;

	$table_name          = "{$wpdb->base_prefix}wu_customermeta";
	$customer_table_name = "{$wpdb->base_prefix}wu_customers";

	$date_query = '';

	if ($start_date || $end_date) {
		$date_query = 'AND c.date_registered >= %s AND c.date_registered <= %s';

		$date_query = $wpdb->prepare($date_query, $start_date . ' 00:00:00', $end_date . " 23:59:59"); // phpcs:ignore
	}

	$query = "
		SELECT m.meta_value as country, COUNT(distinct c.id) as count
		FROM `{$table_name}` as m
		INNER JOIN `{$customer_table_name}` as c ON m.wu_customer_id = c.id
		WHERE m.meta_key = 'ip_country' AND m.meta_value != ''
		$date_query
		GROUP BY m.meta_value
		ORDER BY count DESC
		LIMIT %d
	";

	$query = $wpdb->prepare($query, $count); // phpcs:ignore

	$results = $wpdb->get_results($query); // phpcs:ignore

	$countries = [];

	foreach ($results as $result) {
		$countries[ $result->country ] = $result->count;
	}

	return $countries;
}

/**
 * Get the list of countries and counts based on customers.
 *
 * @since 2.0.0
 * @param string         $country_code The country code.
 * @param integer        $count The number of results to return.
 * @param boolean|string $start_date The start date.
 * @param boolean|string $end_date The end date.
 * @return array
 */
function wu_get_states_of_customers($country_code, $count = 100, $start_date = false, $end_date = false) {

	global $wpdb;

	static $states = [];

	$table_name          = "{$wpdb->base_prefix}wu_customermeta";
	$customer_table_name = "{$wpdb->base_prefix}wu_customers";

	$date_query = '';

	if ($start_date || $end_date) {
		$date_query = 'AND c.date_registered >= %s AND c.date_registered <= %s';

		$date_query = $wpdb->prepare($date_query, $start_date . ' 00:00:00', $end_date . " 23:59:59"); // phpcs:ignore
	}

	$states = wu_get_country_states('BR', false);

	if (empty($states)) {
		return [];
	}

	$states_in = implode("','", array_keys($states));

	$query = "
		SELECT m.meta_value as state, COUNT(distinct c.id) as count
		FROM `{$table_name}` as m
		INNER JOIN `{$customer_table_name}` as c ON m.wu_customer_id = c.id
		WHERE m.meta_key = 'ip_state' AND m.meta_value IN ('$states_in')
		$date_query
		GROUP BY m.meta_value
		ORDER BY count DESC
		LIMIT %d
	";

	$query = $wpdb->prepare($query, $count); // phpcs:ignore

	$results = $wpdb->get_results($query); // phpcs:ignore

	if (empty($results)) {
		return [];
	}

	$_states = [];

	foreach ($results as $result) {
		$final_label = sprintf('%s (%s)', $states[ $result->state ], $result->state);

		$_states[ $final_label ] = absint($result->count);
	}

	return $_states;
}
