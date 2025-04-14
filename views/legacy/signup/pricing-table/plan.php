<?php
/**
 * Displays each individual plan on the pricing table loop
 *
 * This template can be overridden by copying it to yourtheme/wp-ultimo/signup/plan.php.
 *
 * HOWEVER, on occasion WP Multisite WaaS will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author      NextPress
 * @package     WP_Ultimo/Views
 * @version     1.0.0
 */

if ( ! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>

<?php

/**
 * Set plan attributes
 *
 * @var string
 */
$plan_attrs = '';

foreach ([1, 3, 12] as $type) {
	$price       = $plan->free ? __('Free!', 'wp-multisite-waas') : str_replace(wu_get_currency_symbol(), '', wu_format_currency((((float) $plan->{'price_' . $type}) / $type)));
	$plan_attrs .= " data-price-$type='$price'";
}

$plan_attrs = apply_filters('wu_pricing_table_plan', $plan_attrs, $plan);

?>

<div id="plan-<?php echo $plan->get_id(); ?>" data-plan="<?php echo $plan->get_id(); ?>" <?php echo $plan_attrs; ?> class="<?php echo "wu-product-{$plan->get_id()}"; ?> lift wu-plan plan-tier <?php echo $plan->is_featured_plan() ? 'callout' : ''; ?> wu-col-sm-<?php echo $columns; ?> wu-col-xs-12">

	<?php if ($plan->is_featured_plan()) : ?>

	<h6><?php echo apply_filters('wu_featured_plan_label', __('Featured Plan', 'wp-multisite-waas'), $plan); ?></h6>

	<?php endif; ?>

	<h4 class="wp-ui-primary"><?php echo $plan->get_name(); ?></h4>

	<!-- Price -->
	<?php if ($plan->is_free()) : ?>

	<h5>
		<span class="plan-price"><?php esc_html_e('Free!', 'wp-multisite-waas'); ?></span>
	</h5>

	<?php elseif ($plan->is_contact_us()) : ?>

	<h5>
		<span class="plan-price-contact-us"><?php echo apply_filters('wu_plan_contact_us_price_line', __('--', 'wp-multisite-waas')); ?></span>
	</h5>

	<?php else : ?>

	<h5>
		<?php $symbol_left = in_array(wu_get_setting('currency_position', '%s%v'), ['%s%v', '%s %v']); ?>
		<?php
		if ($symbol_left) :
			?>
			<sup class="superscript"><?php echo wu_get_currency_symbol(); ?></sup><?php endif; ?>
		<span class="plan-price"><?php echo str_replace(wu_get_currency_symbol(), '', wu_format_currency($plan->price_1)); ?></span>
		<sub> <?php echo (! $symbol_left ? wu_get_currency_symbol() : '') . ' ' . __('/mo', 'wp-multisite-waas'); ?></sub>
	</h5>

	<?php endif; ?>
	<!-- end Price -->

	<p class="early-adopter-price"><?php echo $plan->get_description(); ?>&nbsp;</p><br>


	<!-- Feature List Begins -->
	<ul>

	<?php
	/**
	 *
	 * Display quarterly and Annually plans, to be hidden
	 */
	$prices_total = [
		3  => __('every 3 months', 'wp-multisite-waas'),
		12 => __('yearly', 'wp-multisite-waas'),
	];

	foreach ($prices_total as $freq => $string) {
		$text = sprintf(__('%1$s, billed %2$s', 'wp-multisite-waas'), wu_format_currency($plan->{"price_$freq"}), $string);

		if ($plan->free || $plan->is_contact_us()) {
			echo "<li class='total-price total-price-$freq'>-</li>";
		} else {
			echo "<li class='total-price total-price-$freq'>$text</li>";
		}
	}

	/**
	 * Loop and Displays Pricing Table Lines
	 */
	foreach ($plan->get_pricing_table_lines() as $key => $line) :
		?>

		<li class="<?php echo str_replace('_', '-', $key); ?>"><?php echo $line; ?></li>

	<?php endforeach; ?>

	<?php
	$button_attrubutes = apply_filters('wu_plan_select_button_attributes', '', $plan, $current_plan);
	$button_label      = null != $current_plan && $plan->get_id() == $current_plan->id ? __('This is your current plan', 'wp-multisite-waas') : __('Select Plan', 'wp-multisite-waas');
	$button_label      = apply_filters('wu_plan_select_button_label', $button_label, $plan, $current_plan);
	?>

	<?php if ($plan->is_contact_us()) : ?>

		<li class="wu-cta">
		<a href="<?php echo $plan->contact_us_link; ?>" class="button button-primary">
			<?php echo $plan->get_contact_us_label(); ?>
		</a>
		</li>

	<?php else : ?>

		<li class="wu-cta">
		<button type="submit" name="plan_id" class="button button-primary button-next" value="<?php echo $plan->get_id(); ?>" <?php echo $button_attrubutes; ?>>
			<?php echo $button_label; ?>
		</button>
		</li>

	<?php endif; ?>

	</ul>
	<!-- Feature List Begins -->

</div>
