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
			'AED' => __('United Arab Emirates Dirham', 'wp-ultimo'),
			'ARS' => __('Argentine Peso', 'wp-ultimo'),
			'AUD' => __('Australian Dollars', 'wp-ultimo'),
			'BDT' => __('Bangladeshi Taka', 'wp-ultimo'),
			'BRL' => __('Brazilian Real', 'wp-ultimo'),
			'BGN' => __('Bulgarian Lev', 'wp-ultimo'),
			'CAD' => __('Canadian Dollars', 'wp-ultimo'),
			'CLP' => __('Chilean Peso', 'wp-ultimo'),
			'CNY' => __('Chinese Yuan', 'wp-ultimo'),
			'COP' => __('Colombian Peso', 'wp-ultimo'),
			'CZK' => __('Czech Koruna', 'wp-ultimo'),
			'DKK' => __('Danish Krone', 'wp-ultimo'),
			'DOP' => __('Dominican Peso', 'wp-ultimo'),
			'EUR' => __('Euros', 'wp-ultimo'),
			'HKD' => __('Hong Kong Dollar', 'wp-ultimo'),
			'HRK' => __('Croatia kuna', 'wp-ultimo'),
			'HUF' => __('Hungarian Forint', 'wp-ultimo'),
			'ISK' => __('Icelandic krona', 'wp-ultimo'),
			'IDR' => __('Indonesia Rupiah', 'wp-ultimo'),
			'INR' => __('Indian Rupee', 'wp-ultimo'),
			'NPR' => __('Nepali Rupee', 'wp-ultimo'),
			'ILS' => __('Israeli Shekel', 'wp-ultimo'),
			'JPY' => __('Japanese Yen', 'wp-ultimo'),
			'KES' => __('Kenyan Shilling', 'wp-ultimo'),
			'KIP' => __('Lao Kip', 'wp-ultimo'),
			'KRW' => __('South Korean Won', 'wp-ultimo'),
			'MYR' => __('Malaysian Ringgits', 'wp-ultimo'),
			'MXN' => __('Mexican Peso', 'wp-ultimo'),
			'NGN' => __('Nigerian Naira', 'wp-ultimo'),
			'NOK' => __('Norwegian Krone', 'wp-ultimo'),
			'NZD' => __('New Zealand Dollar', 'wp-ultimo'),
			'PYG' => __('Paraguayan Guaraní', 'wp-ultimo'),
			'PHP' => __('Philippine Pesos', 'wp-ultimo'),
			'PLN' => __('Polish Zloty', 'wp-ultimo'),
			'GBP' => __('Pounds Sterling', 'wp-ultimo'),
			'RON' => __('Romanian Leu', 'wp-ultimo'),
			'RUB' => __('Russian Ruble', 'wp-ultimo'),
			'SGD' => __('Singapore Dollar', 'wp-ultimo'),
			'ZAR' => __('South African rand', 'wp-ultimo'),
			'SAR' => __('Saudi Riyal', 'wp-ultimo'),
			'SEK' => __('Swedish Krona', 'wp-ultimo'),
			'CHF' => __('Swiss Franc', 'wp-ultimo'),
			'TWD' => __('Taiwan New Dollars', 'wp-ultimo'),
			'THB' => __('Thai Baht', 'wp-ultimo'),
			'TRY' => __('Turkish Lira', 'wp-ultimo'),
			'UAH' => __('Ukrainian Hryvnia', 'wp-ultimo'),
			'USD' => __('US Dollars', 'wp-ultimo'),
			'VND' => __('Vietnamese Dong', 'wp-ultimo'),
			'EGP' => __('Egyptian Pound', 'wp-ultimo'),
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
