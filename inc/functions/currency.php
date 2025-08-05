<?php
/**
 * Country Functions
 *
 * @package WP_Ultimo\Functions
 * @since   1.4.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Get all the currencies we use in Multisite Ultimate
 *
 * @return array Return the currencies array.
 */
function wu_get_currencies(): array {

	$currencies = apply_filters(
		'wu_currencies',
		[

			'AED' => __('United Arab Emirates Dirham', 'multisite-ultimate'),
			'AFN' => __('Afghan Afghani', 'multisite-ultimate'),
			'ALL' => __('Albanian Lek', 'multisite-ultimate'),
			'AMD' => __('Armenian Dram', 'multisite-ultimate'),
			'AOA' => __('Angolan Kwanza', 'multisite-ultimate'),
			'ARS' => __('Argentine Peso', 'multisite-ultimate'),
			'AUD' => __('Australian Dollar', 'multisite-ultimate'),
			'AWG' => __('Aruban Florin', 'multisite-ultimate'),
			'AZN' => __('Azerbaijani Manat', 'multisite-ultimate'),
			'BAM' => __('Bosnia & Herzegovina Convertible Mark', 'multisite-ultimate'),
			'BBD' => __('Barbadian Dollar', 'multisite-ultimate'),
			'BDT' => __('Bangladeshi Taka', 'multisite-ultimate'),
			'BGN' => __('Bulgarian Lev', 'multisite-ultimate'),
			'BIF' => __('Burundian Franc', 'multisite-ultimate'),
			'BMD' => __('Bermudian Dollar', 'multisite-ultimate'),
			'BND' => __('Brunei Dollar', 'multisite-ultimate'),
			'BOB' => __('Bolivian Boliviano', 'multisite-ultimate'),
			'BRL' => __('Brazilian Real', 'multisite-ultimate'),
			'BSD' => __('Bahamian Dollar', 'multisite-ultimate'),
			'BWP' => __('Botswana Pula', 'multisite-ultimate'),
			'BYN' => __('Belarusian Ruble', 'multisite-ultimate'),
			'BZD' => __('Belize Dollar', 'multisite-ultimate'),
			'CAD' => __('Canadian Dollar', 'multisite-ultimate'),
			'CDF' => __('Congolese Franc', 'multisite-ultimate'),
			'CHF' => __('Swiss Franc', 'multisite-ultimate'),
			'CLP' => __('Chilean Peso', 'multisite-ultimate'),
			'CNY' => __('Chinese Renminbi Yuan', 'multisite-ultimate'),
			'COP' => __('Colombian Peso', 'multisite-ultimate'),
			'CRC' => __('Costa Rican Colón', 'multisite-ultimate'),
			'CVE' => __('Cape Verdean Escudo', 'multisite-ultimate'),
			'CZK' => __('Czech Koruna', 'multisite-ultimate'),
			'DJF' => __('Djiboutian Franc', 'multisite-ultimate'),
			'DKK' => __('Danish Krone', 'multisite-ultimate'),
			'DOP' => __('Dominican Peso', 'multisite-ultimate'),
			'DZD' => __('Algerian Dinar', 'multisite-ultimate'),
			'EGP' => __('Egyptian Pound', 'multisite-ultimate'),
			'ETB' => __('Ethiopian Birr', 'multisite-ultimate'),
			'EUR' => __('Euro', 'multisite-ultimate'),
			'FJD' => __('Fijian Dollar', 'multisite-ultimate'),
			'FKP' => __('Falkland Islands Pound', 'multisite-ultimate'),
			'GBP' => __('British Pound', 'multisite-ultimate'),
			'GEL' => __('Georgian Lari', 'multisite-ultimate'),
			'GIP' => __('Gibraltar Pound', 'multisite-ultimate'),
			'GMD' => __('Gambian Dalasi', 'multisite-ultimate'),
			'GNF' => __('Guinean Franc', 'multisite-ultimate'),
			'GTQ' => __('Guatemalan Quetzal', 'multisite-ultimate'),
			'GYD' => __('Guyanese Dollar', 'multisite-ultimate'),
			'HKD' => __('Hong Kong Dollar', 'multisite-ultimate'),
			'HNL' => __('Honduran Lempira', 'multisite-ultimate'),
			'HRK' => __('Croatian Kuna', 'multisite-ultimate'),
			'HTG' => __('Haitian Gourde', 'multisite-ultimate'),
			'HUF' => __('Hungarian Forint', 'multisite-ultimate'),
			'IDR' => __('Indonesian Rupiah', 'multisite-ultimate'),
			'ILS' => __('Israeli New Sheqel', 'multisite-ultimate'),
			'INR' => __('Indian Rupee', 'multisite-ultimate'),
			'ISK' => __('Icelandic Króna', 'multisite-ultimate'),
			'JMD' => __('Jamaican Dollar', 'multisite-ultimate'),
			'JPY' => __('Japanese Yen', 'multisite-ultimate'),
			'KES' => __('Kenyan Shilling', 'multisite-ultimate'),
			'KGS' => __('Kyrgyzstani Som', 'multisite-ultimate'),
			'KHR' => __('Cambodian Riel', 'multisite-ultimate'),
			'KMF' => __('Comorian Franc', 'multisite-ultimate'),
			'KRW' => __('South Korean Won', 'multisite-ultimate'),
			'KYD' => __('Cayman Islands Dollar', 'multisite-ultimate'),
			'KZT' => __('Kazakhstani Tenge', 'multisite-ultimate'),
			'LAK' => __('Lao Kip', 'multisite-ultimate'),
			'LBP' => __('Lebanese Pound', 'multisite-ultimate'),
			'LKR' => __('Sri Lankan Rupee', 'multisite-ultimate'),
			'LRD' => __('Liberian Dollar', 'multisite-ultimate'),
			'LSL' => __('Lesotho Loti', 'multisite-ultimate'),
			'MAD' => __('Moroccan Dirham', 'multisite-ultimate'),
			'MDL' => __('Moldovan Leu', 'multisite-ultimate'),
			'MGA' => __('Malagasy Ariary', 'multisite-ultimate'),
			'MKD' => __('Macedonian Denar', 'multisite-ultimate'),
			'MMK' => __('Myanmar Kyat', 'multisite-ultimate'),
			'MNT' => __('Mongolian Tögrög', 'multisite-ultimate'),
			'MOP' => __('Macanese Pataca', 'multisite-ultimate'),
			'MRU' => __('Mauritanian Ouguiya', 'multisite-ultimate'), // MRO seems outdated, MRU modern replacements in ISO 4217.
			'MUR' => __('Mauritian Rupee', 'multisite-ultimate'),
			'MVR' => __('Maldivian Rufiyaa', 'multisite-ultimate'),
			'MWK' => __('Malawian Kwacha', 'multisite-ultimate'),
			'MXN' => __('Mexican Peso', 'multisite-ultimate'),
			'MYR' => __('Malaysian Ringgit', 'multisite-ultimate'),
			'MZN' => __('Mozambican Metical', 'multisite-ultimate'),
			'NAD' => __('Namibian Dollar', 'multisite-ultimate'),
			'NGN' => __('Nigerian Naira', 'multisite-ultimate'),
			'NIO' => __('Nicaraguan Córdoba', 'multisite-ultimate'),
			'NOK' => __('Norwegian Krone', 'multisite-ultimate'),
			'NPR' => __('Nepalese Rupee', 'multisite-ultimate'),
			'NZD' => __('New Zealand Dollar', 'multisite-ultimate'),
			'PAB' => __('Panamanian Balboa', 'multisite-ultimate'),
			'PEN' => __('Peruvian Nuevo Sol', 'multisite-ultimate'),
			'PGK' => __('Papua New Guinean Kina', 'multisite-ultimate'),
			'PHP' => __('Philippine Peso', 'multisite-ultimate'),
			'PKR' => __('Pakistani Rupee', 'multisite-ultimate'),
			'PLN' => __('Polish Złoty', 'multisite-ultimate'),
			'PYG' => __('Paraguayan Guaraní', 'multisite-ultimate'),
			'QAR' => __('Qatari Riyal', 'multisite-ultimate'),
			'RON' => __('Romanian Leu', 'multisite-ultimate'),
			'RSD' => __('Serbian Dinar', 'multisite-ultimate'),
			'RUB' => __('Russian Ruble', 'multisite-ultimate'),
			'RWF' => __('Rwandan Franc', 'multisite-ultimate'),
			'SAR' => __('Saudi Riyal', 'multisite-ultimate'),
			'SBD' => __('Solomon Islands Dollar', 'multisite-ultimate'),
			'SCR' => __('Seychellois Rupee', 'multisite-ultimate'),
			'SEK' => __('Swedish Krona', 'multisite-ultimate'),
			'SGD' => __('Singapore Dollar', 'multisite-ultimate'),
			'SHP' => __('Saint Helenian Pound', 'multisite-ultimate'),
			'SLE' => __('Sierra Leonean Leone', 'multisite-ultimate'), // SLL is outdated, SLE modern replacements in ISO 4217.
			'SOS' => __('Somali Shilling', 'multisite-ultimate'),
			'SRD' => __('Surinamese Dollar', 'multisite-ultimate'),
			'STD' => __('São Tomé and Príncipe Dobra', 'multisite-ultimate'),
			'SVC' => __('Salvadoran Colón', 'multisite-ultimate'),
			'SZL' => __('Swazi Lilangeni', 'multisite-ultimate'),
			'THB' => __('Thai Baht', 'multisite-ultimate'),
			'TJS' => __('Tajikistani Somoni', 'multisite-ultimate'),
			'TOP' => __('Tongan Paʻanga', 'multisite-ultimate'),
			'TRY' => __('Turkish Lira', 'multisite-ultimate'),
			'TTD' => __('Trinidad and Tobago Dollar', 'multisite-ultimate'),
			'TWD' => __('New Taiwan Dollar', 'multisite-ultimate'),
			'TZS' => __('Tanzanian Shilling', 'multisite-ultimate'),
			'UAH' => __('Ukrainian Hryvnia', 'multisite-ultimate'),
			'UGX' => __('Ugandan Shilling', 'multisite-ultimate'),
			'USD' => __('United States Dollar', 'multisite-ultimate'),
			'UYU' => __('Uruguayan Peso', 'multisite-ultimate'),
			'UZS' => __('Uzbekistani Som', 'multisite-ultimate'),
			'VND' => __('Vietnamese Đồng', 'multisite-ultimate'),
			'VUV' => __('Vanuatu Vatu', 'multisite-ultimate'),
			'WST' => __('Samoan Tala', 'multisite-ultimate'),
			'XAF' => __('Central African Cfa Franc', 'multisite-ultimate'),
			'XCD' => __('East Caribbean Dollar', 'multisite-ultimate'),
			'XOF' => __('West African Cfa Franc', 'multisite-ultimate'),
			'XPF' => __('Cfp Franc', 'multisite-ultimate'),
			'XCG' => __('Caribbean Guilder', 'multisite-ultimate'),
			'YER' => __('Yemeni Rial', 'multisite-ultimate'),
			'ZAR' => __('South African Rand', 'multisite-ultimate'),
			'ZMW' => __('Zambian Kwacha', 'multisite-ultimate'),
		]
	);

	return array_unique($currencies);
}

/**
 * Gets the currency symbol of a currency.
 *
 * @since 0.0.1
 *
 * @param string $currency Currency to get symbol of.
 * @return string
 */
function wu_get_currency_symbol($currency = '') {

	if ( ! $currency) {
		$currency = wu_get_setting('currency_symbol', 'USD');
	} switch ($currency) {
		case 'AED':
			$currency_symbol = 'د.إ';
			break;
		case 'AUD':
		case 'ARS':
		case 'CAD':
		case 'CLP':
		case 'COP':
		case 'HKD':
		case 'MXN':
		case 'NZD':
		case 'SGD':
		case 'USD':
		case 'BBD':
			$currency_symbol = '$';
			break;
		case 'AFN':
			$currency_symbol = '؋';
			break;
		case 'BDT':
			$currency_symbol = '৳&nbsp;';
			break;
		case 'BGN':
			$currency_symbol = 'лв.';
			break;
		case 'BRL':
			$currency_symbol = 'R$';
			break;
		case 'BYN':
			$currency_symbol = 'Br';
		case 'CHF':
			$currency_symbol = 'CHF';
			break;
		case 'CNY':
		case 'JPY':
		case 'RMB':
			$currency_symbol = '&yen;';
			break;
		case 'CZK':
			$currency_symbol = 'Kč';
			break;
		case 'DKK':
			$currency_symbol = 'DKK';
			break;
		case 'DOP':
			$currency_symbol = 'RD$';
			break;
		case 'EGP':
			$currency_symbol = 'E£';
			break;
		case 'EUR':
			$currency_symbol = '&euro;';
			break;
		case 'GBP':
		case 'LBP':
		case 'GIP':
			$currency_symbol = '&pound;';
			break;
		case 'HRK':
			$currency_symbol = 'Kn';
			break;
		case 'HUF':
			$currency_symbol = 'Ft';
			break;
		case 'IDR':
			$currency_symbol = 'Rp';
			break;
		case 'ILS':
			$currency_symbol = '₪';
			break;
		case 'INR':
		case 'NPR':
		case 'LKR':
		case 'SCR':
		case 'PKR':
			$currency_symbol = 'Rs.';
			break;
		case 'ISK':
			$currency_symbol = 'Kr.';
			break;
		case 'KES':
			$currency_symbol = 'KSh';
			break;
		case 'KIP':
			$currency_symbol = '₭';
			break;
		case 'KRW':
			$currency_symbol = '₩';
			break;
		case 'MMK':
			$currency_symbol = 'K';
			break;
		case 'MRU':
			$currency_symbol = 'UM';
			break;
		case 'MYR':
			$currency_symbol = 'RM';
			break;
		case 'NGN':
			$currency_symbol = '₦';
			break;
		case 'NOK':
		case 'SEK':
			$currency_symbol = 'kr';
			break;
		case 'SLE':
			$currency_symbol = 'Le';
			break;
		case 'PHP':
			$currency_symbol = '₱';
			break;
		case 'PLN':
			$currency_symbol = 'zł';
			break;
		case 'PYG':
			$currency_symbol = '₲';
			break;
		case 'RON':
			$currency_symbol = 'lei';
			break;
		case 'RUB':
			$currency_symbol = '₽';
			break;
		case 'THB':
			$currency_symbol = '฿';
			break;
		case 'TRY':
			$currency_symbol = '₺';
			break;
		case 'TWD':
			$currency_symbol = 'NT$';
			break;
		case 'UAH':
			$currency_symbol = '₴';
			break;
		case 'VND':
			$currency_symbol = '₫';
			break;
		case 'ZAR':
			$currency_symbol = 'R';
			break;
		case 'SAR':
			$currency_symbol = 'ر.س';
			break;
		case 'RSD':
			$currency_symbol = 'Дин';
			break;
		case 'TZS':
			$currency_symbol = 'TSh';
			break;
		case 'ALL':
			$currency_symbol = 'Lek';
			break;
		case 'ANG':
		case 'AWG':
			$currency_symbol = 'ƒ';
			break;
		case 'AZN':
			$currency_symbol = '₼';
			break;
		case 'BAM':
			$currency_symbol = 'KM';
			break;
		case 'MKD':
			$currency_symbol = 'ден';
			break;
		case 'UZS':
			$currency_symbol = 'лв';
			break;
		default:
			$currency_symbol = $currency;
			break;
	}

	return apply_filters('wu_currency_symbol', $currency_symbol, $currency);
}

/**
 * Formats a value into our defined format
 *
 * @param  string      $value Value to be processed.
 * @param  string|null $currency Currency code.
 * @param  string|null $format Format to return the string.
 * @param  string|null $thousands_sep Thousands separator.
 * @param  string|null $decimal_sep Decimal separator.
 * @param  string|null $precision Number of decimal places.
 * @return string Formatted Value.
 */
function wu_format_currency($value, $currency = null, $format = null, $thousands_sep = null, $decimal_sep = null, $precision = null) {

	$value = wu_to_float($value);

	$args = [
		'currency'      => $currency,
		'format'        => $format,
		'thousands_sep' => $thousands_sep,
		'decimal_sep'   => $decimal_sep,
		'precision'     => $precision,
	];

	// Remove invalid args
	$args = array_filter($args);

	$atts = wp_parse_args(
		$args,
		[
			'currency'      => wu_get_setting('currency_symbol', 'USD'),
			'format'        => wu_get_setting('currency_position', '%s %v'),
			'thousands_sep' => wu_get_setting('thousand_separator', ','),
			'decimal_sep'   => wu_get_setting('decimal_separator', '.'),
			'precision'     => (int) wu_get_setting('precision', 2),
		]
	);

	$currency_symbol = wu_get_currency_symbol($atts['currency']);

	$value = number_format($value, $atts['precision'], $atts['decimal_sep'], $atts['thousands_sep']);

	$format = str_replace('%v', $value, (string) $atts['format']);
	$format = str_replace('%s', $currency_symbol, $format);

	return apply_filters('wu_format_currency', $format, $currency_symbol, $value);
}

/**
 * Determines if Multisite Ultimate is using a zero-decimal currency.
 *
 * @param  string $currency The currency code to check.
 *
 * @since  2.0.0
 * @return bool True if currency set to a zero-decimal currency.
 */
function wu_is_zero_decimal_currency($currency = 'USD') {

	$zero_dec_currencies = [
		'BIF', // Burundian Franc
		'CLP', // Chilean Peso
		'DJF', // Djiboutian Franc
		'GNF', // Guinean Franc
		'JPY', // Japanese Yen
		'KMF', // Comorian Franc
		'KRW', // South Korean Won
		'MGA', // Malagasy Ariary
		'PYG', // Paraguayan Guarani
		'RWF', // Rwandan Franc
		'VND', // Vietnamese Dong
		'VUV', // Vanuatu Vatu
		'XAF', // Central African CFA Franc
		'XOF', // West African CFA Franc
		'XPF', // CFP Franc
	];

	return apply_filters('wu_is_zero_decimal_currency', in_array($currency, $zero_dec_currencies, true));
}

/**
 * Sets the number of decimal places based on the currency.
 *
 * @param int $decimals The number of decimal places. Default is 2.
 *
 * @todo add the missing currency parameter?
 * @since  2.0.0
 * @return int The number of decimal places.
 */
function wu_currency_decimal_filter($decimals = 2) {

	$currency = 'USD';

	if (wu_is_zero_decimal_currency($currency)) {
		$decimals = 0;
	}

	return apply_filters('wu_currency_decimal_filter', $decimals, $currency);
}

/**
 * Returns the multiplier for the currency. Most currencies are multiplied by 100.
 * Zero decimal currencies should not be multiplied so use 1.
 *
 * @since 2.0.0
 *
 * @param string $currency The currency code, all uppercase.
 * @return int
 */
function wu_stripe_get_currency_multiplier($currency = 'USD') {

	$multiplier = (wu_is_zero_decimal_currency($currency)) ? 1 : 100;

	return apply_filters('wu_stripe_get_currency_multiplier', $multiplier, $currency);
}
