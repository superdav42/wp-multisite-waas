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
 * Get all the currencies we use in WP Multisite WaaS
 *
 * @return array Return the currencies array.
 */
function wu_get_currencies(): array {

	$currencies = apply_filters(
		'wu_currencies',
		[
			'AED' => __('United Arab Emirates Dirham', 'wp-multisite-waas'),
			'ARS' => __('Argentine Peso', 'wp-multisite-waas'),
			'AUD' => __('Australian Dollars', 'wp-multisite-waas'),
			'BDT' => __('Bangladeshi Taka', 'wp-multisite-waas'),
			'BRL' => __('Brazilian Real', 'wp-multisite-waas'),
			'BGN' => __('Bulgarian Lev', 'wp-multisite-waas'),
			'CAD' => __('Canadian Dollars', 'wp-multisite-waas'),
			'CLP' => __('Chilean Peso', 'wp-multisite-waas'),
			'CNY' => __('Chinese Yuan', 'wp-multisite-waas'),
			'COP' => __('Colombian Peso', 'wp-multisite-waas'),
			'CZK' => __('Czech Koruna', 'wp-multisite-waas'),
			'DKK' => __('Danish Krone', 'wp-multisite-waas'),
			'DOP' => __('Dominican Peso', 'wp-multisite-waas'),
			'EUR' => __('Euros', 'wp-multisite-waas'),
			'HKD' => __('Hong Kong Dollar', 'wp-multisite-waas'),
			'HRK' => __('Croatia kuna', 'wp-multisite-waas'),
			'HUF' => __('Hungarian Forint', 'wp-multisite-waas'),
			'ISK' => __('Icelandic krona', 'wp-multisite-waas'),
			'IDR' => __('Indonesia Rupiah', 'wp-multisite-waas'),
			'INR' => __('Indian Rupee', 'wp-multisite-waas'),
			'NPR' => __('Nepali Rupee', 'wp-multisite-waas'),
			'ILS' => __('Israeli Shekel', 'wp-multisite-waas'),
			'JPY' => __('Japanese Yen', 'wp-multisite-waas'),
			'KES' => __('Kenyan Shilling', 'wp-multisite-waas'),
			'KIP' => __('Lao Kip', 'wp-multisite-waas'),
			'KRW' => __('South Korean Won', 'wp-multisite-waas'),
			'MYR' => __('Malaysian Ringgits', 'wp-multisite-waas'),
			'MXN' => __('Mexican Peso', 'wp-multisite-waas'),
			'NGN' => __('Nigerian Naira', 'wp-multisite-waas'),
			'NOK' => __('Norwegian Krone', 'wp-multisite-waas'),
			'NZD' => __('New Zealand Dollar', 'wp-multisite-waas'),
			'PYG' => __('Paraguayan Guaraní', 'wp-multisite-waas'),
			'PHP' => __('Philippine Pesos', 'wp-multisite-waas'),
			'PLN' => __('Polish Zloty', 'wp-multisite-waas'),
			'GBP' => __('Pounds Sterling', 'wp-multisite-waas'),
			'RON' => __('Romanian Leu', 'wp-multisite-waas'),
			'RUB' => __('Russian Ruble', 'wp-multisite-waas'),
			'SGD' => __('Singapore Dollar', 'wp-multisite-waas'),
			'ZAR' => __('South African rand', 'wp-multisite-waas'),
			'SAR' => __('Saudi Riyal', 'wp-multisite-waas'),
			'SEK' => __('Swedish Krona', 'wp-multisite-waas'),
			'CHF' => __('Swiss Franc', 'wp-multisite-waas'),
			'TWD' => __('Taiwan New Dollars', 'wp-multisite-waas'),
			'THB' => __('Thai Baht', 'wp-multisite-waas'),
			'TRY' => __('Turkish Lira', 'wp-multisite-waas'),
			'UAH' => __('Ukrainian Hryvnia', 'wp-multisite-waas'),
			'USD' => __('US Dollars', 'wp-multisite-waas'),
			'VND' => __('Vietnamese Dong', 'wp-multisite-waas'),
			'EGP' => __('Egyptian Pound', 'wp-multisite-waas'),
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
			$currency_symbol = '$';
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
			$currency_symbol = 'EGP';
			break;
		case 'EUR':
			$currency_symbol = '&euro;';
			break;
		case 'GBP':
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
		case 'MYR':
			$currency_symbol = 'RM';
			break;
		case 'NGN':
			$currency_symbol = '₦';
			break;
		case 'NOK':
			$currency_symbol = 'kr';
			break;
		case 'NPR':
			$currency_symbol = 'Rs.';
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
			$currency_symbol = 'руб.';
			break;
		case 'SEK':
			$currency_symbol = 'kr';
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
 * Determines if WP Multisite WaaS is using a zero-decimal currency.
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
