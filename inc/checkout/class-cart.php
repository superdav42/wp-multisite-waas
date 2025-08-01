<?php
/**
 * Creates a cart with the parameters of the purchase being placed.
 *
 * @package WP_Ultimo
 * @subpackage Order
 * @since 2.0.0
 */

namespace WP_Ultimo\Checkout;

use WP_Ultimo\Database\Memberships\Membership_Status;
use Arrch\Arrch as Array_Search;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Creates an cart with the parameters of the purchase being placed.
 *
 * @package WP_Ultimo
 * @subpackage Checkout
 * @since 2.0.0
 */
class Cart implements \JsonSerializable {

	/**
	 * Holds a list of errors.
	 *
	 * These errors do not include
	 * validation errors, only errors
	 * that happen while we try to setup
	 * the cart object.
	 *
	 * @since 2.0.0
	 * @var \WP_Error
	 */
	public $errors;

	/**
	 * Cart Attributes.
	 *
	 * List of attributes passed to the
	 * constructor.
	 *
	 * @since 2.0.0
	 * @var object
	 * @readonly
	 */
	private \stdClass $attributes;

	/**
	 * Type of registration: new, renewal, upgrade, downgrade, retry, and display.
	 *
	 * The display type is used to create the tables that show the products purchased on a membership
	 * and payment screens.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $cart_type;

	/**
	 * The customer object, if that exists.
	 *
	 * @since 2.0.0
	 * @var null|\WP_Ultimo\Models\Customer
	 */
	protected $customer;

	/**
	 * The membership object, if that exists.
	 *
	 * This is used to pre-populate fields such as products
	 * and more.
	 *
	 * @since 2.0.0
	 * @var null|\WP_Ultimo\Models\Membership
	 */
	protected $membership;

	/**
	 * The payment object, if that exists.
	 *
	 * This is used to pre-populate fields such as products
	 * and more.
	 *
	 * @since 2.0.0
	 * @var null|\WP_Ultimo\Models\Payment
	 */
	protected $payment;

	/**
	 * The recovered payment object, if that exists.
	 *
	 * @since 2.0.0
	 * @var null|\WP_Ultimo\Models\Payment
	 */
	protected $recovered_payment;

	/**
	 * The discount code object, if any.
	 *
	 * @since 2.0.0
	 * @var null|\WP_Ultimo\Models\Discount_Code
	 */
	protected $discount_code;

	/**
	 * The country of the customer.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $country;

	/**
	 * The state of the customer.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $state;

	/**
	 * The city of the customer.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $city;

	/**
	 * The currency of this purchase.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $currency;

	/**
	 * The billing cycle duration.
	 *
	 * @since 2.0.0
	 * @var integer
	 */
	protected $duration;

	/**
	 * The billing cycle duration unit.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $duration_unit;

	/**
	 * The number of billing cycles.
	 *
	 * 0 means unlimited cycles (a.k.a until cancelled).
	 *
	 * @since 2.0.0
	 * @var integer
	 */
	protected $billing_cycles = 0;

	/**
	 * The id of the plan being hired.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $plan_id;

	/**
	 * The cart products.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Product[]
	 */
	protected $products = [];

	/**
	 * The cart recurring products.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Product[]
	 */
	protected $recurring_products = [];

	/**
	 * The cart additional products.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Product[]
	 */
	protected $additional_products = [];

	/**
	 * Line item representation of the products.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Checkout\Line_Item[]
	 */
	protected $line_items = [];

	/**
	 * If this cart should auto-renew.
	 *
	 * This flag tells the gateways that support
	 * subscriptions to go ahead and try to set up
	 * a new one.
	 *
	 * On occasion, the value might have been saved as 'yes'.
	 * This is handled automatically by the logic in here, so there's
	 * no reason to worry about it.
	 *
	 * @since 2.0.0
	 * @var bool|string
	 */
	protected $auto_renew = true;

	/**
	 * Extra parameters to send to front-end.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $extra = [];

	/**
	 * The cart description.
	 *
	 * @since 2.1.3
	 * @var string
	 */
	protected $cart_descriptor = '';

	/**
	 * Construct our cart/order object.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args An array containing the cart arguments.
	 */
	public function __construct($args) {
		/*
		 * Why are we using shortcode atts, you might ask?
		 *
		 * Well, shortcode atts cleans the array, allowing only
		 * the keys we list on the defaults array.
		 *
		 * Since we're passing over the entire $_POST array
		 * this helps us to keep things cleaner and secure.
		 */
		$args = shortcode_atts(
			[

				/*
				 * Cart Type.
				 */
				'cart_type'     => 'new',

				/*
				 * The list of products being bought.
				 */
				'products'      => [],

				/*
				 * The duration parameters
				 * This will dictate which price variations we are going to use.
				 */
				'duration'      => false,
				'duration_unit' => false,

				/*
				 * The membership ID.
				 * This is passed when we want to handle a upgrade/downgrade/addon.
				 */
				'membership_id' => false,

				/*
				 * Payment ID.
				 * This is passed when we are trying to recovered a abandoned/pending payment.
				 */
				'payment_id'    => false,

				/*
				 * The discount code to be used.
				 */
				'discount_code' => false,

				/*
				 * If we should auto-renew or not.
				 */
				'auto_renew'    => true,

				/*
				 * The country, state, and city of the customer.
				 * Used for taxation purposes.
				 */
				'country'       => '',
				'state'         => '',
				'city'          => '',

				/*
				 * Currency
				 */
				'currency'      => '',

			],
			$args
		);

		/*
		 * Checks for errors
		 */
		$this->errors = new \WP_Error();

		/*
		 * Save arguments in memory
		 */
		$this->attributes = (object) $args;

		/**
		 * Allow developers to make additional changes to
		 * the checkout object.
		 *
		 * @since 2.0.0
		 * @param $this \WP_Ultimo\Checkout\Cart The cart object.
		 */
		do_action('wu_cart_setup', $this); // @phpstan-ignore-line

		/*
		 * Set the country, duration and duration_unit.
		 */
		$this->cart_type     = $this->attributes->cart_type;
		$this->country       = $this->attributes->country;
		$this->state         = $this->attributes->state;
		$this->city          = $this->attributes->city;
		$this->currency      = $this->attributes->currency;
		$this->duration      = $this->attributes->duration;
		$this->duration_unit = $this->attributes->duration_unit;

		/*
		 * Loads the current customer, if it exists.
		 */
		$this->customer = wu_get_current_customer();

		/*
		 * At this point, we have almost everything we can ready.
		 * It's time to deal with discount codes.
		 */
		$this->set_discount_code($this->attributes->discount_code);

		/*
		 * Delegates the logic to another
		 * method that builds up the cart.
		 */
		$this->build_cart();

		/*
		 * Also set the auto-renew status.
		 *
		 * This setting can be forced if the settings say so,
		 * so we only set it if that is not enabled.
		 */
		if ( ! wu_get_setting('force_auto_renew', true)) {
			$this->auto_renew = wu_string_to_bool($this->attributes->auto_renew);
		}

		/*
		 * Calculate-totals.
		 *
		 * This will make sure our cart is ready to be consumed
		 * by other parts of the code.
		 */
		$this->calculate_totals();

		/**
		 * Allow developers to make additional changes to
		 * the checkout object.
		 *
		 * @since 2.0.0
		 * @param $this \WP_Ultimo\Checkout\Cart The cart object.
		 */
		do_action('wu_cart_after_setup', $this); // @phpstan-ignore-line
	}

	/**Null
	 * Get additional parameters set by integrations and add-ons.
	 *
	 * @param string  $key The parameter key.
	 * @param boolean $default_value The default value.
	 *
	 * @return mixed
	 * @since 2.0.0
	 */
	public function get_param($key, $default_value = false) {

		return wu_get_isset($this->attributes, $key, $default_value);
	}

	/**
	 * Set additional parameters set by integrations and add-ons.
	 *
	 * @since 2.0.0
	 *
	 * @param string $key The key to set.
	 * @param mixed  $value The value to set.
	 * @return void
	 */
	public function set_param($key, $value): void {

		$this->extra[] = $key;

		$this->attributes->{$key} = $value;
	}

	/**
	 * Gets the tax exempt status of the current cart.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_tax_exempt() {

		return apply_filters('wu_cart_is_tax_exempt', false, $this);
	}

	/**
	 * Builds the cart.
	 *
	 * Here, we try to determine the type of
	 * cart so we can properly set it up, based
	 * on the payment, membership, and products passed.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	protected function build_cart() {
		/*
		 * Maybe deal with payment recovery first.
		 */
		$is_recovery_cart = $this->build_from_payment($this->attributes->payment_id);

		/*
		 * If we are recovering a payment, we stop right here.
		 * The pending payment object has all the info we need
		 * in order to build the proper cart.
		 */
		if ($is_recovery_cart) {
			return;
		}

		/*
		 * The next step is to deal with membership changes.
		 * These include downgrades/upgrades and addons.
		 */
		$is_membership_change = $this->build_from_membership($this->attributes->membership_id);

		/*
		 * If this is a membership change,
		 * we can return as our work is done.
		 */
		if ($is_membership_change) {
			return;
		}

		/*
		 * Otherwise, we add the the products normally,
		 * and set the cart as new.
		 */
		$this->cart_type = 'new';

		if (is_array($this->attributes->products)) {
			/*
			 * Otherwise, we add the products to build the cart.
			 */
			foreach ($this->attributes->products as $product_id) {
				$this->add_product($product_id);
			}
		}
	}

	/**
	 * Creates a string that describes the cart.
	 *
	 * Some gateways require a description that you need
	 * to match after the payment confirmation.
	 *
	 * This method generates such a string based on
	 * the products on the cart.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_cart_descriptor() {

		if ( ! empty($this->cart_descriptor)) {
			return $this->cart_descriptor;
		}

		$desc = wu_get_setting('company_name', get_network_option(null, 'site_name'));

		$products = [];

		foreach ($this->get_line_items() as $line_item) {
			$product = $line_item->get_product();

			if ( ! $product) {
				continue;
			}

			$products[] = $line_item->get_title();
		}

		$descriptor = $desc . ' - ' . implode(', ', $products);

		return trim($descriptor);
	}

	/**
	 * Set a custom cart descriptor.
	 *
	 * @since 2.1.3
	 *
	 * @param string $descriptor The cart description.
	 * @return void
	 */
	public function set_cart_descriptor($descriptor): void {

		$this->cart_descriptor = $descriptor;
	}

	/**
	 * Decides if we are trying to recover a payment.
	 *
	 * @since 2.0.0
	 *
	 * @param int $payment_id A valid payment ID.
	 */
	protected function build_from_payment($payment_id): bool {
		/*
		 * No valid payment id passed, so we
		 * are not trying to recover a payment.
		 */
		if (empty($payment_id)) {
			return false;
		}

		/*
		 * Now, let's try to fetch the payment in question.
		 */
		$payment = wu_get_payment($payment_id);

		if ( ! $payment) {
			$this->errors->add('payment_not_found', __('The payment in question was not found.', 'multisite-ultimate'));

			return true;
		}

		/*
		 * The payment exists, set it globally.
		 */
		$this->payment = $payment;

		/*
		 * Adds the country to calculate taxes.
		 */
		$this->country = $this->country ?: ($this->customer ? $this->customer->get_country() : '');

		/*
		 * Set the currency in the cart
		 */
		$this->set_currency($payment->get_currency());

		/*
		 * Check for the correct permissions.
		 *
		 * For obvious reasons, only the customer that owns
		 * a payment can pay it. Let's check for that.
		 */
		if (empty($this->customer) || $this->customer->get_id() !== $payment->get_customer_id()) {
			$this->errors->add('lacks_permission', __('You are not allowed to modify this payment.', 'multisite-ultimate'));

			return true;
		}

		/*
		 * Sets the membership as well, to prevent issues
		 */
		$membership = $payment->get_membership();

		if ( ! $membership) {
			$this->errors->add('membership_not_found', __('The membership in question was not found.', 'multisite-ultimate'));

			return true;
		}

		if ($payment->get_discount_code()) {
			/**
			 *  First check if is a membership discount code;
			 */
			$discount_code = $membership->get_discount_code();

			if ($discount_code && $discount_code->get_code() === $payment->get_discount_code()) {
				$this->add_discount_code($discount_code);
			} else {
				$this->add_discount_code($payment->get_discount_code());
			}
		}

		/*
		 * Sets membership globally.
		 */
		$this->membership    = $membership;
		$this->duration      = $membership->get_duration();
		$this->duration_unit = $membership->get_duration_unit();

		/*
		 * Finally, copy the line items from the payment.
		 */
		foreach ($payment->get_line_items() as $line_item) {
			$product = $line_item->get_product();

			if ($product) {
				if ($product->is_recurring() && ($product->get_duration_unit() !== $this->duration_unit || $product->get_duration() !== $this->duration)) {
					$product_variation = $product->get_as_variation($this->duration, $this->duration_unit);

					/*
					 * Checks if the variation exists before re-setting the product.
					 */
					if ($product_variation) {
						$product = $product_variation;
					}
				}

				$this->products[] = $product;

				if ($line_item->get_type() === 'product' && $product->get_type() === 'plan') {
					/*
					 * If we already have a plan, we can't add
					 * another one.
					 */
					if (empty($this->plan_id)) {
						$this->plan_id        = $product->get_id();
						$this->billing_cycles = $product->get_billing_cycles();

						$this->duration      = $line_item->get_duration();
						$this->duration_unit = $line_item->get_duration_unit();
					}
				}
			}

			$this->add_line_item($line_item);
		}

		/*
		 * If the payment is completed
		 * this can't be a retry, so we skip
		 * the rest.
		 */
		if ($payment->get_status() === 'completed') {
			/**
			 * We should return false to continue in case of membership updates.
			 */
			return false;
		}

		/*
		 * Check for payment status.
		 *
		 * We want to make sure we only allow for repayment of pending,
		 * cancelled, or abandoned payments
		 */
		$allowed_status = apply_filters(
			'wu_cart_set_payment_allowed_status',
			[
				'pending',
			]
		);

		if ( ! in_array($payment->get_status(), $allowed_status, true)) {
			$this->errors->add('invalid_status', __('The payment in question has an invalid status.', 'multisite-ultimate'));

			return true;
		}

		/*
		 * If the membership is active or is
		 * already in trial this can't be a
		 * retry, so we skip the rest.
		 */
		if ($membership->is_active() || ($membership->get_status() === Membership_Status::TRIALING && ! $this->has_trial())) {
			return false;
		}

		/*
		 * We got here, that means
		 * the intend behind this cart was to actually
		 * recover a payment.
		 *
		 * That means we can safely set the cart type to retry.
		 */
		$this->cart_type = 'retry';

		return true;
	}

	/**
	 * Uses the membership to decide if this is a upgrade/downgrade/addon cart.
	 *
	 * @since 2.0.0
	 *
	 * @param int $membership_id A valid membership ID.
	 */
	protected function build_from_membership($membership_id): bool {
		/*
		 * No valid membership id passed, so we
		 * are not trying to change a membership.
		 */
		if (empty($membership_id)) {
			return false;
		}

		/*
		 * We got here, that means
		 * the intend behind this cart was to actually
		 * change a membership.
		 *
		 * We can set the cart type provisionally.
		 * This assignment might change in the future, as we make
		 * additional assertions about the contents of the cart.
		 */
		$this->cart_type = 'upgrade';

		/*
		 * Now, let's try to fetch the membership in question.
		 */
		$membership = wu_get_membership($membership_id);

		if ( ! $membership) {
			$this->errors->add('membership_not_found', __('The membership in question was not found.', 'multisite-ultimate'));

			return true;
		}

		/*
		 * The membership exists, set it globally.
		 */
		$this->membership = $membership;

		/*
		 * In the case of membership changes,
		 * the status is not that relevant, as customers
		 * might want to make changes to memberships that are
		 * active, cancelled, etc.
		 *
		 * We do need to check for permissions, though.
		 * Only the customer that owns a membership can change it.
		 */
		if (empty($this->customer) || $this->customer->get_id() !== $membership->get_customer_id()) {
			$this->errors->add('lacks_permission', __('You are not allowed to modify this membership.', 'multisite-ultimate'));

			return true;
		}

		/*
		 * Adds the country to calculate taxes.
		 */
		$this->country = $this->country ?: $this->customer->get_country();

		/*
		 * Set the currency in cart
		 */
		$this->set_currency($membership->get_currency());

		/*
		 * If we get to this point, we now need to assess
		 * what are the changes being made.
		 *
		 * First, we need to see if there are actual products
		 * being added, and process those.
		 */
		if (empty($this->attributes->products)) {
			if ($this->payment) {
				/**
				 *  If we do not have any change but we have a already
				 *  created payment it means that this cart is to pay
				 *  for this.
				 */
				return false;
			}

			$this->errors->add('no_changes', __('This cart proposes no changes to the current membership.', 'multisite-ultimate'));

			return true;
		}

		/*
		 * Otherwise, we add the products to build the cart.
		 */
		foreach ($this->attributes->products as $product_id) {
			$this->add_product($product_id);
		}

		/*
		 * With products added, let's check if this is an addon.
		 *
		 * An addon cart adds a new product or service to the current membership.
		 * If this cart, after adding the products, doesn't have a plan, it means
		 * it should continue to use the membership plan, and the other products
		 * must be added to the membership.
		 */
		if (empty($this->plan_id)) {
			if (count($this->products) === 0) {
				$this->errors->add('no_changes', __('This cart proposes no changes to the current membership.', 'multisite-ultimate'));

				return true;
			}

			/*
			 * Set the type to addon.
			 */
			$this->cart_type = 'addon';

			/*
			 * Sets the durations to avoid problems
			 * with addon purchases.
			 */
			$plan_product = $membership->get_plan();

			if ($plan_product && ! $membership->is_free()) {
				$this->duration      = $plan_product->get_duration();
				$this->duration_unit = $plan_product->get_duration_unit();
			}

			/*
			 * Checks the membership to see if we need to add back the
			 * setup fee.
			 *
			 * If the membership was already successfully charged once,
			 * it probably means that the setup fee was already paid, so we can skip it.
			 */
			add_filter('wu_apply_signup_fee', fn() => $membership->get_times_billed() <= 0);

			/*
			 * Adds the membership plan back in, for completeness.
			 * This is also useful to make sure we present
			 * the totals correctly for the customer.
			 */
			$this->add_product($membership->get_plan_id());

			/*
			 * Adds the credit line, after
			 * calculating pro-rate.
			 */
			$this->calculate_prorate_credits();

			return true;
		}

		/*
		 * With products added, let's check if the plan is changing.
		 *
		 * A plan change implies a upgrade or a downgrade, which we will determine
		 * below.
		 *
		 * A plan change can take many forms.
		 * - Different plan altogether;
		 * - Same plan with different periodicity;
		 * - upgrade to lifetime;
		 * - downgrade to free;
		 */
		$is_plan_change = false;

		if ($membership->get_plan_id() !== $this->plan_id || $membership->get_duration_unit() !== $this->duration_unit || $membership->get_duration() !== $this->duration) {
			$is_plan_change = true;
		}

		/*
		 * Checks for periodicity changes.
		 */
		$old_periodicity = sprintf('%s-%s', $membership->get_duration(), $membership->get_duration_unit());
		$new_periodicity = sprintf('%s-%s', $this->duration, $this->duration_unit);

		if ($old_periodicity !== $new_periodicity) {
			$is_plan_change = true;
		}

		/*
		 * If there is no plan change, but the product count is > 1
		 * We know that there is another product in this cart other than the
		 * plan, so this is again an addon cart.
		 */
		if (count($this->products) > 1 && false === $is_plan_change) {
			/*
			 * Set the type to addon.
			 */
			$this->cart_type = 'addon';

			/*
			 * Sets the durations to avoid problems
			 * with addon purchases.
			 */
			$plan_product = $membership->get_plan();

			if ($plan_product && ! $membership->is_free()) {
				$this->duration      = $plan_product->get_duration();
				$this->duration_unit = $plan_product->get_duration_unit();
			}

			/*
			 * Checks the membership to see if we need to add back the
			 * setup fee.
			 *
			 * If the membership was already successfully charged once,
			 * it probably means that the setup fee was already paid, so we can skip it.
			 */
			add_filter('wu_apply_signup_fee', fn() => $membership->get_times_billed() <= 0);

			/*
			 * Adds the credit line, after
			 * calculating pro-rate.
			 */
			$this->calculate_prorate_credits();

			return true;
		}

		/*
		 * We'll probably never enter in this if, but we
		 * hev it here to prevent bugs.
		 */
		if ( ! $is_plan_change || ($this->get_plan_id() === $membership->get_plan_id() && $membership->get_duration_unit() === $this->duration_unit && $membership->get_duration() === $this->duration)) {
			$this->products   = [];
			$this->line_items = [];

			$this->errors->add('no_changes', __('This cart proposes no changes to the current membership.', 'multisite-ultimate'));

			return true;
		} else {
			$new_plan        = $this->get_plan();
			$new_limitations = $new_plan->get_limitations();
			$sites           = $this->get_membership()->get_sites(false);
			foreach ($sites as $site) {
				switch_to_blog($site->get_id());

				$overlimits = $new_limitations->post_types->check_all_post_types();

				if ( $overlimits ) {
					foreach ( $overlimits as $post_type_slug => $limit ) {
						$post_type = get_post_type_object($post_type_slug);

						$this->errors->add(
							'overlimits_' . $post_type_slug,
							sprintf(
							// translators: %1$d: current number of posts, %2$s: post type name, %3$d: posts quota, %4$s: post type name, %5$d: number of posts to be deleted, %6$s: post type name.
								esc_html__('Your site currently has %1$d %2$s but the new plan is limited to %3$d %4$s. You must trash %5$d %6$s before you can downgrade your plan.', 'multisite-ultimate'),
								$limit['current'],
								$limit['current'] > 1 ? $post_type->labels->name : $post_type->labels->singular_name,
								$limit['limit'],
								$limit['limit'] > 1 ? $post_type->labels->name : $post_type->labels->singular_name,
								$limit['current'] - $limit['limit'],
								$limit['current'] - $limit['limit'] > 1 ? $post_type->labels->name : $post_type->labels->singular_name
							)
						);
					}
					restore_current_blog();

					return true;
				}

				// Check domain mapping limits for downgrade
				$domain_overlimits = $new_limitations->domain_mapping->check_all_domains($site->get_id());

				if ( $domain_overlimits ) {
					$domain_count = $domain_overlimits['current'];
					$domain_limit = $domain_overlimits['limit'];

					if (0 === $domain_limit) {
						$this->errors->add(
							'overlimits',
							sprintf(
								esc_html__('This new plan does NOT support custom domains. You must remove all custom domains before you can downgrade your plan.', 'multisite-ultimate'),
							)
						);
					} else {
						$this->errors->add(
							'overlimits',
							sprintf(
							// translators: %1$d: current number of custom domains, %2$s: 'custom domain' or 'custom domains', %3$d: domain limit, %4$s: 'custom domain' or 'custom domains', %5$d: number of domains to be removed, %6$s: 'custom domain' or 'custom domains'.
								esc_html__('Your site currently has %1$d %2$s but the new plan is limited to %3$d %4$s. You must remove %5$d %6$s before you can downgrade your plan.', 'multisite-ultimate'),
								$domain_count,
								$domain_count > 1 ? __('custom domains', 'multisite-ultimate') : __('custom domain', 'multisite-ultimate'),
								$domain_limit,
								$domain_limit > 1 ? __('custom domains', 'multisite-ultimate') : __('custom domain', 'multisite-ultimate'),
								$domain_count - $domain_limit,
								($domain_count - $domain_limit) > 1 ? __('custom domains', 'multisite-ultimate') : __('custom domain', 'multisite-ultimate')
							)
						);
					}
					restore_current_blog();

					return true;
				}
				restore_current_blog();
			}
		}

		/*
		 * Upgrade to Lifetime.
		 */
		if ( ! $this->has_recurring() && ! $this->is_free()) {
			/*
			 * Adds the credit line, after
			 * calculating pro-rate.
			 */
			$this->calculate_prorate_credits();

			return true;
		}

		/*
		 * If we get to this point, we know that this is either
		 * an upgrade or a downgrade, so we need to determine which.
		 *
		 * Since by default we set the value to upgrade,
		 * we just need to check for a downgrade scenario.
		 */
		$days_in_old_cycle = wu_get_days_in_cycle($membership->get_duration_unit(), $membership->get_duration());
		$days_in_new_cycle = wu_get_days_in_cycle($this->duration_unit, $this->duration);

		$old_price_per_day = $days_in_old_cycle > 0 ? $membership->get_amount() / $days_in_old_cycle : $membership->get_amount();
		$new_price_per_day = $days_in_new_cycle > 0 ? $this->get_recurring_total() / $days_in_new_cycle : $this->get_recurring_total();

		$is_same_product = $membership->get_plan_id() === $this->plan_id;

		/**
		 * Here we search for variations of the plans
		 * with the same duration to avoid mistakens
		 * when setting a downgrade cart.
		 */
		if ($days_in_old_cycle !== $days_in_new_cycle) {
			$old_plan = $membership->get_plan();
			$new_plan = $this->get_plan();

			$variations = $this->search_for_same_period_plans($old_plan, $new_plan);

			if ($variations) {
				$old_plan = $variations[0];
				$new_plan = $variations[1];

				$days_in_old_cycle_plan = wu_get_days_in_cycle($old_plan->get_duration_unit(), $old_plan->get_duration());
				$days_in_new_cycle_plan = wu_get_days_in_cycle($new_plan->get_duration_unit(), $new_plan->get_duration());

				$old_price_per_day = $days_in_old_cycle_plan > 0 ? $old_plan->get_amount() / $days_in_old_cycle_plan : $old_plan->get_amount();
				$new_price_per_day = $days_in_new_cycle_plan > 0 ? $new_plan->get_amount() / $days_in_new_cycle_plan : $new_plan->get_amount();
			}
		}

		if ( ! $membership->is_free() && $old_price_per_day < $new_price_per_day && $days_in_old_cycle > $days_in_new_cycle && $membership->get_status() === Membership_Status::ACTIVE) {
			$this->products   = [];
			$this->line_items = [];

			$description = sprintf(
				1 === $membership->get_duration() ? '%2$s' : '%1$s %2$s',
				$membership->get_duration(),
				wu_get_translatable_string(($membership->get_duration() <= 1 ? $membership->get_duration_unit() : $membership->get_duration_unit() . 's'))
			);

			// Translators: Placeholder receives the recurring period description
			$message = sprintf(__('You already have an active %s agreement.', 'multisite-ultimate'), $description);

			$this->errors->add('no_changes', $message);

			return true;
		}

		/*
		 * If is the same product and the customer will start to pay less
		 * or if is not the same product and the price per day is smaller
		 * this is a downgrade
		 */
		if (($is_same_product && $membership->get_amount() > $this->get_recurring_total()) || (! $is_same_product && $old_price_per_day > $new_price_per_day)) {
			$this->cart_type = 'downgrade';

			// If membership is active or trialing we will schendule the swap
			if ($membership->is_active() || $membership->get_status() === Membership_Status::TRIALING) {
				$line_item_params = apply_filters(
					'wu_checkout_credit_line_item_params',
					[
						'type'         => 'credit',
						'title'        => __('Scheduled Swap Credit', 'multisite-ultimate'),
						'description'  => __('Swap scheduled to next billing cycle.', 'multisite-ultimate'),
						'discountable' => false,
						'taxable'      => false,
						'quantity'     => 1,
						'unit_price'   => - $this->get_total(),
					]
				);

				$credit_line_item = new Line_Item($line_item_params);

				$this->add_line_item($credit_line_item);
			}
		}

		// If this is an upgrade, we need to prorate the current amount
		if ('upgrade' === $this->cart_type) {
			$this->calculate_prorate_credits();
		}

		/*
		 * All set!
		 */
		return true;
	}

	/**
	 * Search for variations of the plans with same duration.
	 *
	 * @since 2.0.20
	 * @param \WP_Ultimo\Models\Product $plan_a The first plan without variations.
	 * @param \WP_Ultimo\Models\Product $plan_b The second plan without variations.
	 * @return mixed[]|false
	 */
	protected function search_for_same_period_plans($plan_a, $plan_b) {

		if ($plan_a->get_duration_unit() === $plan_b->get_duration_unit() && $plan_a->get_duration() === $plan_b->get_duration()) {
			return [
				$plan_a,
				$plan_b,
			];
		}

		$plan_a_variation = $plan_a->get_as_variation($plan_b->get_duration(), $plan_b->get_duration_unit());

		if ($plan_a_variation) {
			return [
				$plan_a_variation,
				$plan_b,
			];
		}

		$plan_b_variation = $plan_b->get_as_variation($plan_a->get_duration(), $plan_a->get_duration_unit());

		if ($plan_b_variation) {
			return [
				$plan_a,
				$plan_b_variation,
			];
		}

		if ($this->duration_unit && $this->duration && ($plan_b->get_duration_unit() !== $this->duration_unit || $plan_b->get_duration() !== $this->duration)) {
			$plan_a_variation = $plan_a->get_as_variation($this->duration, $this->duration_unit);

			if ( ! $plan_a_variation) {
				return false;
			}

			if ($plan_b->get_duration_unit() === $plan_a_variation->get_duration_unit() && $plan_b->get_duration() === $plan_a_variation->get_duration()) {
				return [
					$plan_a_variation,
					$plan_b,
				];
			}

			$plan_b_variation = $plan_b->get_as_variation($this->duration, $this->duration_unit);

			if ($plan_b_variation) {
				return [
					$plan_a_variation,
					$plan_b_variation,
				];
			}
		}

		return false;
	}

	/**
	 * Calculate pro-rate credits.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	protected function calculate_prorate_credits() {
		/*
		 * Now we come to the craziest part: pro-rating!
		 *
		 * This is super hard to get right, but we basically need to add
		 * new line items to account for the time using the old plan.
		 */

		/*
		 * If the membership is in trial period theres nothing to prorate.
		 */
		if ($this->membership->get_status() === Membership_Status::TRIALING) {
			return;
		}

		if ($this->membership->is_lifetime() || ! $this->membership->is_recurring()) {
			$credit = $this->membership->get_initial_amount();
		} else {
			$days_unused = $this->membership->get_remaining_days_in_cycle();

			$days_in_old_cycle = wu_get_days_in_cycle($this->membership->get_duration_unit(), $this->membership->get_duration());

			$old_price_per_day = $days_in_old_cycle > 0 ? $this->membership->get_amount() / $days_in_old_cycle : $this->membership->get_amount();

			if (($this->membership->get_date_created() && wu_date($this->membership->get_date_created())->format('Y-m-d') === wu_date()->format('Y-m-d')) ||
				($this->membership->get_date_renewed() && wu_date($this->membership->get_date_renewed())->format('Y-m-d') === wu_date()->format('Y-m-d'))) {
				// If the membership was created today, We'll use the average days in the cycle to prevent some odd numbers.
				$days_unused = $days_in_old_cycle;
			}

			$credit = $days_unused * $old_price_per_day;

			if ($credit > $this->membership->get_amount()) {
				$credit = $this->membership->get_amount();
			}
		}

		/*
		 * No credits
		 */
		if (empty($credit)) {
			return;
		}

		/*
		 * Checks if we need to add back the value of the
		 * setup fee
		 */
		$has_setup_fee = $this->get_line_items_by_type('fee');

		if ( ! empty($has_setup_fee) || $this->get_cart_type() === 'upgrade') {
			$old_plan = $this->membership->get_plan();

			$new_plan = $this->get_plan();

			if ($old_plan && $new_plan) {
				$old_setup_fee = $old_plan->get_setup_fee();
				$new_setup_fee = $new_plan->get_setup_fee();

				$fee_credit = $old_setup_fee < $new_setup_fee ? $old_setup_fee : $new_setup_fee;

				if ($fee_credit > 0) {
					$new_line_item = new Line_Item(
						[
							'product'     => $old_plan,
							'type'        => 'fee',
							'description' => '--',
							'title'       => '',
							'taxable'     => $old_plan->is_taxable(),
							'recurring'   => false,
							'unit_price'  => $fee_credit,
							'quantity'    => 1,
						]
					);

					$new_line_item = $this->apply_taxes_to_item($new_line_item);

					$new_line_item->recalculate_totals();

					$credit += $new_line_item->get_total();
				}
			}
		}

		/**
		 * Allow plugin developers to meddle with the credit value.
		 *
		 * @since 2.0.0
		 *
		 * @param int  $credit The credit amount.
		 * @param self $cart This cart object.
		 */
		$credit = apply_filters('wu_checkout_calculate_prorate_credits', $credit, $this);

		$credit = round($credit, wu_currency_decimal_filter());

		/*
		 * No credits
		 */
		if (empty($credit)) {
			return;
		}

		$line_item_params = apply_filters(
			'wu_checkout_credit_line_item_params',
			[
				'type'         => 'credit',
				'title'        => __('Credit', 'multisite-ultimate'),
				'description'  => __('Prorated amount based on the previous membership.', 'multisite-ultimate'),
				'discountable' => false,
				'taxable'      => false,
				'quantity'     => 1,
				'unit_price'   => - $credit,
			]
		);

		/*
		 * Finally, we add the credit to the purchase.
		 */
		$credit_line_item = new Line_Item($line_item_params);

		$this->add_line_item($credit_line_item);
	}

	/**
	 * Adds a discount code to the cart.
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $code A valid discount code ID or code.
	 */
	protected function set_discount_code($code): bool {

		if (empty($code)) {
			return false;
		}

		$code = strtoupper($code);

		$discount_code = wu_get_discount_code_by_code($code);

		if (empty($discount_code)) {

			// translators: %s is the coupon code being used, all-caps. e.g. PROMO10OFF
			$this->errors->add('discount_code', sprintf(__('The code %s do not exist or is no longer valid.', 'multisite-ultimate'), $code));

			return false;
		}

		$is_valid = $discount_code->is_valid();

		if (is_wp_error($is_valid)) {
			$this->errors->merge_from($is_valid);

			return false;
		}

		/*
		 * Set the coupon
		 */
		$this->discount_code = $discount_code;

		return true;
	}

	/**
	 * Returns the current errors.
	 *
	 * @since 2.0.0
	 * @return \WP_Error
	 */
	public function get_errors() {

		return $this->errors;
	}

	/**
	 * For an order to be valid, all the recurring products must have the same
	 * billing intervals and cycle.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_valid() {

		$is_valid = true;

		/*
		 * If we got any errors during
		 * the setup, bail.
		 */
		if ($this->errors->has_errors()) {
			return false;
		}

		$interval = null;

		foreach ($this->line_items as $line_item) {
			$duration      = $line_item->get_duration();
			$duration_unit = $line_item->get_duration_unit();
			$cycles        = $line_item->get_billing_cycles();

			if ( ! $line_item->is_recurring()) {
				continue;
			}

			/*
			 * Create a key that will tell us if something changes.
			 *
			 * If unit, duration or cycles are different, we return false.
			 * This means that this order is not valid.
			 *
			 * Maybe in the future we can try to come of ways of accommodating
			 * different billing periods on the same order, right now, there
			 * isn't a way of doing that with all the different gateways we
			 * plan to support.
			 */
			$line_item_interval = "{$duration}-{$duration_unit}-{$cycles}";

			if ( ! $interval) {
				$interval = $line_item_interval;
			}

			if ($line_item_interval !== $interval) {
				// translators: two intervals
				$this->errors->add('wrong', sprintf(__('Interval %1$s and %2$s do not match.', 'multisite-ultimate'), $line_item_interval, $interval));

				return false;
			}
		}

		return $is_valid;
	}

	/**
	 * Checks if this order is free.
	 *
	 * This is used on the checkout to deal with this separately.
	 *
	 * @todo handle 100% off coupon codes.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_free() {

		return empty($this->get_total());
	}

	/**
	 * Checks if we need to collect a payment method.
	 *
	 * Will return false if the order is free or when
	 * the order contains a trial and no payment method is required.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_collect_payment() {

		$should_collect_payment = true;

		if ($this->is_free() && $this->get_recurring_total() === 0.0) {
			$should_collect_payment = false;
		} elseif ($this->has_trial()) {
			$should_collect_payment = ! wu_get_setting('allow_trial_without_payment_method', false);
		}

		return (bool) apply_filters('wu_cart_should_collect_payment', $should_collect_payment, $this);
	}

	/**
	 * Checks if the cart has a plan.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function has_plan() {

		return (bool) $this->get_plan();
	}

	/**
	 * Returns the cart plan.
	 *
	 * @since 2.0.0
	 * @return \WP_Ultimo\Models\Product|false
	 */
	public function get_plan() {

		return wu_get_product((int) $this->plan_id);
	}

	/**
	 * Returns the recurring products added to the cart.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_recurring_products() {

		return $this->recurring_products;
	}

	/**
	 * Returns the non-recurring products added to the cart.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_non_recurring_products() {

		return $this->additional_products;
	}

	/**
	 * Returns an array containing all products added to the cart, recurring or not.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_all_products() {

		return $this->products;
	}

	/**
	 * Returns the duration value for this cart.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_duration() {

		return $this->duration;
	}

	/**
	 * Returns the duration unit for this cart.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_duration_unit() {

		return $this->duration_unit;
	}

	/**
	 * Add a new line item.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Checkout\Line_Item $line_item The line item.
	 * @return void
	 */
	public function add_line_item($line_item): void {

		if ( ! is_a($line_item, '\WP_Ultimo\Checkout\Line_Item')) {
			return;
		}

		if ($line_item->is_discountable()) {
			$line_item = $this->apply_discounts_to_item($line_item);
		}

		if ($line_item->is_taxable()) {
			$line_item = $this->apply_taxes_to_item($line_item);
		}

		$this->line_items[ $line_item->get_id() ] = $line_item;

		krsort($this->line_items);
	}

	/**
	 * Adds a new product to the cart.
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $product_id_or_slug The product id to add.
	 * @param int        $quantity The quantity.
	 */
	public function add_product($product_id_or_slug, $quantity = 1): bool {

		$product = is_numeric($product_id_or_slug) ? wu_get_product($product_id_or_slug) : wu_get_product_by_slug($product_id_or_slug);

		if ( ! $product) {
			$message = __('The product you are trying to add does not exist.', 'multisite-ultimate');

			$this->errors->add('missing-product', $message);

			return false;
		}

		// Here we check if the product is recurring and if so, get the correct variation
		if ($product->is_recurring() && ! empty($this->duration) && ($product->get_duration() !== $this->duration || $product->get_duration_unit() !== $this->duration_unit)) {
			$product = $product->get_as_variation($this->duration, $this->duration_unit);

			if ( ! $product) {
				$message = __('The product you are trying to add does not exist for the selected duration.', 'multisite-ultimate');

				$this->errors->add('missing-price-variations', $message);

				return false;
			}
		}

		if ($product->get_type() === 'plan') {
			/*
			 * If we already have a plan, we can't add
			 * another one. Bail.
			 */
			if ( ! empty($this->plan_id)) {
				$message = __('Theres already a plan in this membership.', 'multisite-ultimate');

				$this->errors->add('plan-already-added', $message);

				return false;
			}

			$this->plan_id        = $product->get_id();
			$this->billing_cycles = $product->get_billing_cycles();
		}

		/*
		 * We only try to reset the duration and such if
		 * they are not already set.
		 *
		 * We need to do this because we
		 * want access this to fetch price variations.
		 */
		if (empty($this->duration) || $product->is_recurring() === false) {
			$this->duration      = $product->get_duration();
			$this->duration_unit = $product->get_duration_unit();
		}

		if (empty($this->currency)) {
			$this->currency = $product->get_currency();
		}

		/*
		 * Set product amount in here, because
		 * that can change...
		 */
		$amount        = $product->get_amount();
		$duration      = $product->get_duration();
		$duration_unit = $product->get_duration_unit();

		/*
		 * Deal with price variations.
		 *
		 * Here's the general idea:
		 *
		 * If the cart duration or duration unit differs from
		 * the product's, we try to fetch a price variation.
		 *
		 * If a price variation doesn't exist, we add an error to
		 * the cart.
		 */
		if ($product->is_free() === false) {
			if (absint($this->duration) !== $product->get_duration() || $product->get_duration_unit() !== $this->duration_unit) {
				$price_variation = $product->get_price_variation($this->duration, $this->duration_unit);

				if ($price_variation) {
					$price_variation = (object) $price_variation;

					$amount        = $price_variation->amount;
					$duration      = $price_variation->duration;
					$duration_unit = $price_variation->duration_unit;
				} else {
					/*
					 * This product does not have a valid
					 * price variation. We need to add an error.
					 */
					// translators: respectively, product name, duration, and duration unit.
					$message = sprintf(__('%1$s does not have a valid price variation for that billing period (every %2$s %3$s(s)) and was not added to the cart.', 'multisite-ultimate'), $product->get_name(), $this->duration, $this->duration_unit);

					$this->errors->add('missing-price-variations', $message);

					return false;
				}
			}
		}

		$line_item_data = apply_filters(
			'wu_add_product_line_item',
			[
				'product'       => $product,
				'quantity'      => $quantity,
				'unit_price'    => $amount,
				'duration'      => $duration,
				'duration_unit' => $duration_unit,
			],
			$product,
			$duration,
			$duration_unit,
			$this
		);

		$this->products[] = $product;

		if (empty($line_item_data)) {
			return false;
		}

		$line_item = new Line_Item($line_item_data);

		/*
		 * Allows for product removal on the checkout summary.
		 */
		$line_item->set_product_slug($product->get_slug());

		$this->add_line_item($line_item);

		/**
		 * Signup Fees
		 */
		if (empty($product->get_setup_fee())) {
			return true;
		}

		$add_signup_fee = 'renewal' !== $this->get_cart_type();

		/**
		 * Filters whether or not the signup fee should be applied.
		 *
		 * @param bool             $add_signup_fee Whether or not to add the signup fee.
		 * @param object           $product   Membership level object.
		 * @param \WP_Ultimo\Checkout\Cart $this           Registration object.
		 *
		 * @since 3.1
		 */
		$add_signup_fee = apply_filters('wu_apply_signup_fee', $add_signup_fee, $product, $this); // @phpstan-ignore-line

		if ( ! $add_signup_fee) {
			return true;
		}

		// translators: placeholder is the product name.
		$description = ($product->get_setup_fee() > 0) ? __('Signup Fee for %s', 'multisite-ultimate') : __('Signup Credit for %s', 'multisite-ultimate');

		$description = sprintf($description, $product->get_name());

		/**
		 * Allow developers to make changes to the setup fee line item.
		 *
		 * @since 2.1
		 *
		 * @param array $setup_fee_line_item Setup fee line item parameters.
		 * @param \WP_Ultimo\Models\Product $product The product related to the setup fee.
		 * @param \WP_Ultimo\Checkout\Cart $cart The cart object.
		 * @return array
		 */
		$setup_fee_line_item = apply_filters(
			'wu_add_product_setup_fee_line_item',
			[
				'product'     => $product,
				'type'        => 'fee',
				'description' => '--',
				'title'       => $description,
				'taxable'     => $product->is_taxable(),
				'recurring'   => false,
				'unit_price'  => $product->get_setup_fee(),
				'quantity'    => $quantity,
			],
			$product,
			$this
		);

		$setup_fee_line_item = new Line_Item($setup_fee_line_item);

		$this->add_line_item($setup_fee_line_item);

		return true;
	}

	/**
	 * Returns an array containing the subtotal per tax rate.
	 *
	 * @since 2.0.0
	 * @return array $tax_rate => $tax_total.
	 */
	public function get_tax_breakthrough() {

		$line_items = $this->line_items;

		$tax_brackets = [];

		foreach ($line_items as $line_item) {
			$tax_bracket = $line_item->get_tax_rate();

			if (isset($tax_brackets[ $tax_bracket ])) {
				$tax_brackets[ $tax_bracket ] += $line_item->get_tax_total();

				continue;
			}

			$tax_brackets[ $tax_bracket ] = $line_item->get_tax_total();
		}

		return $tax_brackets;
	}

	/**
	 * Determine whether or not the level being registered for has a trial that the current user is eligible
	 * for. This will return false if there is a trial but the user is not eligible for it.
	 *
	 * @access public
	 * @since  2.0.0
	 * @return bool
	 */
	public function has_trial() {

		$products = $this->get_all_products();

		if (empty($products)) {
			return false;
		}

		$is_trial = $this->get_billing_start_date();

		if ( ! $is_trial) {
			return false;
		}

		// There is a trial, but let's check eligibility.
		$customer = wu_get_current_customer();

		// No customer, which means they're brand new, which means they're eligible.
		if (empty($customer)) {
			return true;
		}

		// Check if this is the initial membership payment with trial
		if ($this->membership && $this->payment && $this->membership->is_trialing()) {
			return empty($this->payment->get_total());
		}

		return ! $customer->has_trialed();
	}

	/**
	 * Get the recovered payment object.
	 *
	 * @since 2.0.0
	 * @return object|false Payment object if set, false if not.
	 */
	public function get_recovered_payment() {

		return $this->recovered_payment;
	}

	/**
	 * Add discount to the order.
	 *
	 * @since 2.0.0
	 *
	 * @param string|\WP_Ultimo\Models\Discount_Code $code Coupon code to add.
	 */
	public function add_discount_code($code): bool {

		if (is_a($code, \WP_Ultimo\Models\Discount_Code::class)) {
			$this->discount_code = $code;

			return true;
		}

		$discount_code = wu_get_discount_code_by_code($code);

		if ( ! $discount_code) {
			return false;
		}

		$this->discount_code = $discount_code;

		return true;
	}

	/**
	 * Get registration discounts.
	 *
	 * @since 2.5
	 * @return mixed[]|bool
	 */
	public function get_discounts() {

		return $this->get_line_items_by_type('discount');
	}

	/**
	 * Checks if the cart has any discounts applied.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function has_discount() {

		return $this->get_total_discounts() > 0;
	}

	/**
	 * Returns a list of line items based on the line item type.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type The type. Can be 'product', 'fee', 'discount'.
	 * @param array  $where_clauses Additional where clauses for search.
	 * @return \WP_Ultimo\Checkout\Line_Item[]
	 */
	public function get_line_items_by_type($type = 'product', $where_clauses = []): array {

		$where_clauses[] = ['type', $type];

		// Cast to array recursively
		$line_items = json_decode(wp_json_encode($this->line_items), true);

		$line_items = Array_Search::find(
			$line_items,
			[
				'where' => $where_clauses,
			]
		);

		$ids = array_keys($line_items);

		return array_filter($this->line_items, fn($id) => in_array($id, $ids, true), ARRAY_FILTER_USE_KEY);
	}

	/**
	 * Get registration fees.
	 *
	 * @since 2.0.0
	 * @return mixed[]|bool
	 */
	public function get_fees() {

		return $this->get_line_items_by_type('fees');
	}

	/**
	 * Calculates the total tax amount.
	 *
	 * @todo Refactor this.
	 * @since 2.0.0
	 * @return float
	 */
	public function get_total_taxes() {

		$total_taxes = 0;

		foreach ($this->line_items as $line_item) {
			$total_taxes += $line_item->get_tax_total();
		}

		return $total_taxes;
	}

	/**
	 * Get the total number of fees.
	 *
	 * @since 2.0.0
	 *
	 * @param null|float $total The total of fees in the order so far.
	 * @param bool       $only_recurring | set to only get fees that are recurring.
	 *
	 * @return float
	 */
	public function get_total_fees($total = null, $only_recurring = false) {

		$line_items = $this->get_fees();

		if ( ! $line_items) {
			return 0;
		}

		$fees = 0;

		foreach ($line_items as $fee) {
			if ($only_recurring && ! $fee->is_recurring()) {
				continue;
			}

			$fees += $fee->get_total();
		}

		// if total is present, make sure that any negative fees are not
		// greater than the total.
		if ($total && ($fees + $total) < 0) {
			$fees = -1 * $total;
		}

		return apply_filters('wu_cart_get_total_fees', (float) $fees, $total, $only_recurring, $this);
	}

	/**
	 * Get the total proration amount.
	 *
	 * @todo Needs to be used and implemented on the checkout flow.
	 * @since 2.0.0
	 *
	 * @return float
	 */
	public function get_proration_credits() {

		if ( ! $this->get_fees()) {
			return 0;
		}

		$proration = 0;

		foreach ($this->get_fees() as $fee) {
			if ( ! $fee['proration']) {
				continue;
			}

			$proration += $fee['amount'];
		}

		return apply_filters('wu_cart_get_proration_fees', (float) $proration, $this);
	}

	/**
	 * Get the total discounts.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function get_total_discounts() {

		$total_discount = 0;

		foreach ($this->line_items as $line_item) {
			$total_discount -= $line_item->get_discount_total();
		}

		$total_discount = round($total_discount, wu_currency_decimal_filter());

		return apply_filters('wu_cart_get_total_discounts', $total_discount, $this);
	}

	/**
	 * Gets the subtotal value of the cart.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function get_subtotal() {

		$subtotal = 0;

		$exclude_types = [
			'discount',
			'credit',
		];

		foreach ($this->line_items as $line_item) {
			if (in_array($line_item->get_type(), $exclude_types, true)) {
				continue;
			}

			$subtotal += $line_item->get_subtotal();
		}

		if (0 > $subtotal) {
			$subtotal = 0;
		}

		$subtotal = round($subtotal, wu_currency_decimal_filter());

		/**
		 * Filter the "initial amount" total.
		 *
		 * @param float $subtotal     Total amount due today.
		 * @param \WP_Ultimo\Checkout\Cart $this Cart object.
		 */
		return apply_filters('wu_cart_get_subtotal', floatval($subtotal), $this); // @phpstan-ignore-line
	}

	/**
	 * Get the registration total due today.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function get_total() {

		$total = 0;

		foreach ($this->line_items as $line_item) {
			$total += $line_item->get_total();
		}

		if (0 > $total) {
			$total = 0;
		}

		$total = round($total, wu_currency_decimal_filter());

		/**
		 * Filter the "initial amount" total.
		 *
		 * @param float $total     Total amount due today.
		 * @param \WP_Ultimo\Checkout\Cart $this Cart object.
		 */
		return apply_filters('wu_cart_get_total', floatval($total), $this); // @phpstan-ignore-line
	}

	/**
	 * Get the registration recurring total.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function get_recurring_total() {

		$total = 0;

		foreach ($this->line_items as $line_item) {
			if ( ! $line_item->is_recurring()) {
				continue;
			}

			/*
			 * Check for coupon codes
			 */
			if ($line_item->get_discount_total() > 0 && ! $line_item->should_apply_discount_to_renewals()) {
				$new_line_item = clone $line_item;

				$new_line_item->attributes(
					[
						'discount_rate' => 0,
					]
				);

				$new_line_item->recalculate_totals();

				$amount = $new_line_item->get_total();
			} else {
				$amount = $line_item->get_total();
			}

			$total += $amount;
		}

		if (0 > $total) {
			$total = 0;
		}

		$total = round($total, wu_currency_decimal_filter());

		/**
		 * Filters the "recurring amount" total.
		 *
		 * @param float $total     Recurring amount.
		 * @param \WP_Ultimo\Checkout\Cart $this Cart object.
		 */
		return apply_filters('wu_cart_get_recurring_total', floatval($total), $this); // @phpstan-ignore-line
	}

	/**
	 * Gets the recurring subtotal, before taxes.
	 *
	 * @since 2.0.0
	 * @return float
	 */
	public function get_recurring_subtotal() {

		$subtotal = 0;

		foreach ($this->line_items as $line_item) {
			if ( ! $line_item->is_recurring()) {
				continue;
			}

			$subtotal += $line_item->get_subtotal();
		}

		if (0 > $subtotal) {
			$subtotal = 0;
		}

		$subtotal = round($subtotal, wu_currency_decimal_filter());

		/**
		 * Filters the "recurring amount" total.
		 *
		 * @param float $subtotal     Recurring amount.
		 * @param \WP_Ultimo\Checkout\Cart $this Cart object.
		 */
		return apply_filters('wu_cart_get_recurring_total', floatval($subtotal), $this); // @phpstan-ignore-line
	}

	/**
	 * Returns the timestamp of the end of the trial period.
	 *
	 * @since 2.0.0
	 * @return int|null
	 */
	public function get_billing_start_date() {

		if ($this->is_free() && ! $this->has_recurring()) {
			return null;
		}

		/*
		 * Set extremely high value at first to prevent any change of errors.
		 */
		$smallest_trial = 300 * YEAR_IN_SECONDS;

		foreach ($this->get_all_products() as $product) {
			if ( ! $product->has_trial()) {
				$smallest_trial = 0;
			}

			$duration = $product->get_trial_duration();

			$duration_unit = $product->get_trial_duration_unit();

			if ($duration && $duration_unit) {
				$trial_period = strtotime("+$duration $duration_unit");

				if ($trial_period < $smallest_trial) {
					$smallest_trial = $trial_period;
				}
			}
		}

		return $smallest_trial;
	}

	/**
	 * Returns the timestamp of the next charge, if recurring.
	 *
	 * @since 2.0.0
	 * @return string|false
	 */
	public function get_billing_next_charge_date() {
		/*
		 * Set extremely high value at first to prevent any chance of errors.
		 */
		$smallest_next_charge = 300 * YEAR_IN_SECONDS;

		if ($this->get_cart_type() === 'downgrade') {
			$membership = $this->membership;

			if ($membership->is_active() || $membership->get_status() === Membership_Status::TRIALING) {
				$next_charge = strtotime($membership->get_date_expiration());

				return $next_charge;
			}
		}

		foreach ($this->get_all_products() as $product) {
			if ( ! $product->is_recurring() || ($product->has_trial() && $this->has_trial())) {
				continue;
			}

			$duration = $product->get_duration();

			$duration_unit = $product->get_duration_unit();

			$next_charge = strtotime("+$duration $duration_unit");

			if ($next_charge < $smallest_next_charge) {
				$smallest_next_charge = $next_charge;
			}
		}

		return $smallest_next_charge;
	}

	/**
	 * Checks if the order is recurring or not.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function has_recurring() {

		return $this->get_recurring_total() > 0;
	}

	/**
	 * Returns an array with all types of line-items of the cart.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_line_items() {

		return $this->line_items;
	}

	/**
	 * Apply discounts to a line item.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Checkout\Line_Item $line_item The line item.
	 * @return \WP_Ultimo\Checkout\Line_Item
	 */
	public function apply_discounts_to_item($line_item) {

		/**
		 * Product is not taxable, bail.
		 */
		if ( ! $line_item->is_discountable() || ! $this->discount_code) {
			return $line_item;
		}

		if (is_wp_error($this->discount_code->is_valid($line_item->get_product_id()))) {
			return $line_item;
		}

		/**
		 * Should apply to fees?
		 */
		if ($line_item->get_type() === 'fee') {
			if ($this->discount_code->get_setup_fee_value() <= 0) {
				return $line_item;
			}

			$line_item->attributes(
				[
					'discount_rate'              => $this->discount_code->get_setup_fee_value(),
					'discount_type'              => $this->discount_code->get_setup_fee_type(),
					'apply_discount_to_renewals' => false,
					'discount_label'             => strtoupper($this->discount_code->get_code()),
				]
			);
		} else {
			$line_item->attributes(
				[
					'discount_rate'              => $this->discount_code->get_value(),
					'discount_type'              => $this->discount_code->get_type(),
					'apply_discount_to_renewals' => $this->discount_code->should_apply_to_renewals(),
					'discount_label'             => strtoupper($this->discount_code->get_code()),
				]
			);
		}

		$line_item->recalculate_totals();

		return $line_item;
	}

	/**
	 * Apply taxes to a line item.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Checkout\Line_Item $line_item The line item.
	 * @return \WP_Ultimo\Checkout\Line_Item
	 */
	public function apply_taxes_to_item($line_item) {

		/**
		 * Tax collection is not enabled
		 */
		if ( ! wu_should_collect_taxes()) {
			return $line_item;
		}

		/**
		 * Product is not taxable, bail.
		 */
		if ( ! $line_item->is_taxable()) {
			return $line_item;
		}

		$tax_category = $line_item->get_tax_category();

		/**
		 * No tax category, bail.
		 */
		if ( ! $tax_category) {
			return $line_item;
		}

		$tax_rates = apply_filters('wu_cart_applicable_tax_rates', wu_get_applicable_tax_rates($this->country, $tax_category, $this->state, $this->city), $this->country, $tax_category, $this);

		if (empty($tax_rates)) {
			return $line_item;
		}

		foreach ($tax_rates as $applicable_tax_rate) {
			$tax_type  = 'percentage';
			$tax_rate  = $applicable_tax_rate['tax_rate'];
			$tax_label = $applicable_tax_rate['title'];

			continue;
		}

		$line_item->attributes(
			[
				'tax_rate'      => $tax_rate ?? 0,
				'tax_type'      => $tax_type ?? 'percentage',
				'tax_label'     => $tax_label ?? '',
				'tax_inclusive' => wu_get_setting('inclusive_tax', false),
				'tax_exempt'    => $this->is_tax_exempt(),
			]
		);

		$line_item->recalculate_totals();

		return $line_item;
	}

	/**
	 * Calculates the totals of the cart and return them.
	 *
	 * @since 2.0.0
	 * @return object
	 */
	public function calculate_totals() {

		return (object) [
			'recurring'       => (object) [
				'subtotal' => $this->get_recurring_subtotal(),
				'total'    => $this->get_recurring_total(),
			],
			'subtotal'        => $this->get_subtotal(),
			'total_taxes'     => $this->get_total_taxes(),
			'total_fees'      => $this->get_total_fees(),
			'total_discounts' => $this->get_total_discounts(),
			'total'           => $this->get_total(),
		];
	}

	/**
	 * Used for serialization purposes.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function jsonSerialize(): string {

		return wp_json_encode($this->done());
	}

	/**
	 * Get the list of extra parameters.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_extra_params() {

		$extra_params = [];

		foreach ($this->extra as $key) {
			$extra_params[ $key ] = $this->get_param($key);
		}

		return apply_filters('wu_cart_get_extra_params', $extra_params, $this);
	}

	/**
	 * Implements our on json_decode version of this object. Useful for use in vue.js.
	 *
	 * @since 2.0.0
	 * @return \stdClass
	 */
	public function done() {

		$totals = $this->calculate_totals();

		$errors = [];

		if ($this->errors->has_errors()) {
			foreach ($this->errors as $code => $messages) {
				foreach ($messages as $message) {
					$errors[] = [
						'code'    => $code,
						'message' => $message,
					];
				}
			}
		}

		return (object) [

			'errors'                 => $errors,
			'url'                    => $this->get_cart_url(),
			'type'                   => $this->get_cart_type(),
			'valid'                  => $this->is_valid(),
			'is_free'                => $this->is_free(),
			'should_collect_payment' => $this->should_collect_payment(),

			'has_plan'               => $this->has_plan(),
			'has_recurring'          => $this->has_recurring(),
			'has_discount'           => $this->has_discount(),
			'has_trial'              => $this->has_trial(),

			'line_items'             => $this->get_line_items(),
			'discount_code'          => $this->get_discount_code(),
			'totals'                 => $this->calculate_totals(),

			'extra'                  => $this->get_extra_params(),

			'dates'                  => (object) [
				'date_trial_end'   => $this->get_billing_start_date(),
				'date_next_charge' => $this->get_billing_next_charge_date(),
			],

		];
	}

	/**
	 * Converts the current cart to an array of membership elements.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function to_membership_data() {

		$membership_data = [];

		$all_additional_products = $this->get_line_items_by_type(
			'product',
			[
				['product_id', '!=', $this->get_plan_id()],
			]
		);

		$addon_list = [];

		foreach ($all_additional_products as $line_item) {
			$addon_list[ $line_item->get_product_id() ] = $line_item->get_quantity();
		}

		$membership_data = array_merge(
			[
				'recurring'      => $this->has_recurring(),
				'plan_id'        => $this->get_plan() ? $this->get_plan()->get_id() : 0,
				'initial_amount' => $this->get_total(),
				'addon_products' => $addon_list,
				'currency'       => $this->get_currency(),
				'duration'       => $this->get_duration(),
				'duration_unit'  => $this->get_duration_unit(),
				'amount'         => $this->get_recurring_total(),
				'times_billed'   => 0,
				'billing_cycles' => $this->get_plan() ? $this->get_plan()->get_billing_cycles() : 0,
				'auto_renew'     => false, // @todo: revisit
				'upgraded_from'  => false, // @todo: revisit
			]
		);

		return $membership_data;
	}

	/**
	 * Converts the current cart to a payment data array.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function to_payment_data() {

		$payment_data = [];

		// Creates the pending payment
		$payment_data = [
			'status'        => 'pending',
			'tax_total'     => $this->get_total_taxes(),
			'fees'          => $this->get_total_fees(),
			'discounts'     => $this->get_total_discounts(),
			'line_items'    => $this->get_line_items(),
			'discount_code' => $this->get_discount_code() ? $this->get_discount_code()->get_code() : '',
			'subtotal'      => $this->get_subtotal(),
			'total'         => $this->get_total(),
		];

		return $payment_data;
	}

	/**
	 * Get the value of discount_code
	 *
	 * @since 2.0.0
	 * @return null|\WP_Ultimo\Models\Discount_Code
	 */
	public function get_discount_code() {

		return $this->discount_code;
	}

	/**
	 * Get the value of plan_id
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_plan_id() {

		return $this->plan_id;
	}

	/**
	 * Get the currency code.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_currency() {

		return $this->currency;
	}

	/**
	 * Set the currency.
	 *
	 * @since 2.0.0
	 * @param mixed $currency The currency code.
	 * @return void
	 */
	public function set_currency($currency): void {

		$this->currency = $currency;
	}

	/**
	 * Get the cart membership.
	 *
	 * @since 2.0.0
	 * @return null|\WP_Ultimo\Models\Membership
	 */
	public function get_membership() {

		return $this->membership;
	}

	/**
	 * Get the cart payment.
	 *
	 * @since 2.0.0
	 * @return null|\WP_Ultimo\Models\Payment
	 */
	public function get_payment() {

		return $this->payment;
	}

	/**
	 * Get the cart customer.
	 *
	 * @since 2.0.0
	 * @return null|\WP_Ultimo\Models\Customer
	 */
	public function get_customer() {

		return $this->customer;
	}

	/**
	 * Set the cart membership.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Models\Membership $membership A valid membership object.
	 * @return void
	 */
	public function set_membership($membership): void {

		$this->membership = $membership;
	}

	/**
	 * Set the cart customer.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Models\Customer $customer A valid customer object.
	 * @return void
	 */
	public function set_customer($customer): void {

		$this->customer = $customer;
	}

	/**
	 * Set the cart payment.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Models\Payment $payment A valid payment object.
	 * @return void
	 */
	public function set_payment($payment): void {

		$this->payment = $payment;
	}

	/**
	 * Get the value of auto_renew.
	 *
	 * @since 2.0.0
	 * @return mixed
	 */
	public function should_auto_renew() {

		return 'yes' === $this->auto_renew || true === $this->auto_renew;
	}

	/**
	 * Get the cart type.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_cart_type() {

		return $this->cart_type;
	}

	/**
	 * Get the country of the customer.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_country() {

		return $this->country;
	}

	/**
	 * Set the country of the customer.
	 *
	 * @since 2.0.0
	 * @param string $country The country of the customer.
	 * @return void
	 */
	public function set_country($country): void {

		$this->country = $country;
	}

	/**
	 * Builds a cart URL that we can use with the browser history APIs.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_cart_url() {

		$base_url = '';

		$plan = wu_get_product($this->plan_id);

		if ($plan) {
			$base_url .= $plan->get_slug();
		}

		if ($this->duration && absint($this->duration) !== 1) {
			$base_url .= "/{$this->duration}";
		}

		if ($this->duration_unit && 'month' !== $this->duration_unit) {
			$base_url .= "/{$this->duration_unit}";
		}

		$all_products = $this->products;

		$products_list = [];

		foreach ($all_products as $product) {
			if ($product->get_id() !== $this->plan_id) {
				$products_list[] = $product->get_slug();
			}
		}

		return add_query_arg(
			[
				'products' => $products_list,
			],
			$base_url
		);
	}
}
