<?php
/**
 * Handles the processing of new membership purchases.
 *
 * @package WP_Ultimo
 * @subpackage Checkout
 * @since 2.0.0
 */

namespace WP_Ultimo\Checkout;

// Exit if accessed directly
defined('ABSPATH') || exit;

use Psr\Log\LogLevel;
use WP_Ultimo\Database\Sites\Site_Type;
use WP_Ultimo\Database\Payments\Payment_Status;
use WP_Ultimo\Database\Memberships\Membership_Status;
use WP_Ultimo\Checkout\Checkout_Pages;
use WP_Ultimo\Managers\Payment_Manager;
use WP_Ultimo\Objects\Billing_Address;
use WP_Ultimo\Models\Site;

/**
 * Handles the processing of new membership purchases.
 *
 * @since 2.0.0
 */
class Checkout {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Holds checkout errors.
	 *
	 * @since 2.0.0
	 * @var \WP_Error|null
	 */
	public $errors;

	/**
	 * Keeps a reference to our order.
	 *
	 * @since 2.0.0
	 * @var Cart
	 */
	protected $order;

	/*
	 * Checkout progress info
	 */

	/**
	 * Current step of the signup flow.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $step;

	/**
	 * Keeps the name of the step.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $step_name;

	/**
	 * The current checkout form being used.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Checkout_Form
	 */
	public $checkout_form;

	/**
	 * List of steps for the signup flow.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $steps;

	/**
	 * Session object.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Contracts\Session
	 */
	protected $session;

	/**
	 * Checkout type.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $type = 'new';

	/**
	 * Check if setup method already run.
	 *
	 * @since 2.0.18
	 * @var bool
	 */
	protected $already_setup = false;

	/**
	 * Checks if a list of fields has an auto-submittable field.
	 *
	 * @since 2.1.2
	 * @var false|string
	 */
	protected $auto_submittable_field;

	/**
	 * The gateway id.
	 *
	 * @since 2.1.2
	 * @var string|bool
	 */
	protected $gateway_id;

	/**
	 * The customer object.
	 *
	 * @since 2.1.2
	 * @var \WP_Ultimo\Models\Customer
	 */
	protected $customer;

	/**
	 * The membership object.
	 *
	 * @since 2.0.23
	 * @var \WP_Ultimo\Models\Membership
	 */
	protected $membership;

	/**
	 * The pending site object.
	 *
	 * @since 2.1.2
	 * @var Site
	 */
	protected $pending_site;

	/**
	 * The payment object.
	 *
	 * @since 2.1.2
	 * @var \WP_Ultimo\Models\Payment
	 */
	protected $payment;

	/**
	 * Initializes the Checkout singleton and adds hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init(): void {
		/*
		 * Setup and handle checkout
		 */
		add_action('wu_setup_checkout', [$this, 'setup_checkout']);

		add_action('wu_setup_checkout', [$this, 'maybe_process_checkout'], 20);

		/*
		 * Add the rewrite rules.
		 */
		add_action('init', [$this, 'add_rewrite_rules'], 20);

		add_filter('wu_request', [$this, 'get_checkout_from_query_vars'], 10, 2);

		/*
		 * Creates the order object to display to the customer
		 */
		add_action('wu_ajax_wu_create_order', [$this, 'create_order']);

		add_action('wu_ajax_nopriv_wu_create_order', [$this, 'create_order']);

		/*
		 * Validates form and process preflight.
		 */
		add_action('wu_ajax_wu_validate_form', [$this, 'maybe_handle_order_submission']);

		add_action('wu_ajax_nopriv_wu_validate_form', [$this, 'maybe_handle_order_submission']);

		/*
		 * Adds the necessary scripts
		 */
		add_action('wu_checkout_scripts', [$this, 'register_scripts']);

		/*
		 * Errors
		 */
		add_action('wu_checkout_errors', [$this, 'maybe_display_checkout_errors']);
	}

	/**
	 * Add checkout rewrite rules.
	 *
	 * Adds the following URL structures.
	 * For this example, let's use /register as the registration page.
	 *
	 * It registers:
	 * 1. site.com/register/plan_id:         Pre-selects the plan_id;
	 * 2. site.com/register/plan_id/3:       Pre-selects the plan_id and 3 months;
	 * 3. site.com/register/plan_id/12/year: Pre-selects the plan and the duration unit.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function add_rewrite_rules(): void {

		$register = Checkout_Pages::get_instance()->get_signup_page('register');

		if ( ! is_a($register, '\WP_Post')) {
			return;
		}

		$register_slug = $register->post_name;

		/*
		 * The first rewrite rule.
		 *
		 * This will match the registration URL and a plan
		 * slug.
		 *
		 * Example: site.com/register/premium
		 * Will pre-select the premium product.
		 */
		add_rewrite_rule(
			"{$register_slug}\/([0-9a-zA-Z-_]+)[\/]?$",
			'index.php?pagename=' . $register_slug . '&products[]=$matches[1]&wu_preselected=products',
			'top'
		);

		/*
		 * This one is here for backwards compatibility.
		 * It always assign to months.
		 */
		add_rewrite_rule(
			"{$register_slug}\/([0-9a-zA-Z-_]+)\/([0-9]+)[\/]?$",
			'index.php?pagename=' . $register_slug . '&products[]=$matches[1]&duration=$matches[2]&duration_unit=month&wu_preselected=products',
			'top'
		);

		/*
		 * This is the one we really want.
		 * It allows us to create custom registration URLs
		 * such as /register/premium/1/year
		 */
		add_rewrite_rule(
			"{$register_slug}\/([0-9a-zA-Z-_]+)\/([0-9]+)[\/]?([a-z]+)[\/]?$",
			'index.php?pagename=' . $register_slug . '&products[]=$matches[1]&duration=$matches[2]&duration_unit=$matches[3]&wu_preselected=products',
			'top'
		);

		/*
		 * By the default, the template selection
		 * URL structure uses the word template.
		 * This can be changed using the filter below.
		 */
		$template_slug = apply_filters('wu_template_selection_rewrite_rule_slug', 'template', $register_slug);

		/*
		 * Template site pre-selection.
		 * Allows for registration urls such as
		 * /register/template/starter
		 */
		add_rewrite_rule(
			"{$register_slug}\/{$template_slug}\/([0-9a-zA-Z-_]+)[\/]?$",
			'index.php?pagename=' . $register_slug . '&template_name=$matches[1]&wu_preselected=template_id',
			'top'
		);
	}

	/**
	 * Filters the wu_request with the query vars.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed  $value The value from wu_request.
	 * @param string $key The key value.
	 * @return mixed
	 */
	public function get_checkout_from_query_vars($value, $key) {

		if ( ! did_action('wp')) {
			return $value;
		}

		$from_query = get_query_var($key);

		$cart_arguments = apply_filters(
			'wu_get_checkout_from_query_vars',
			[
				'products',
				'duration',
				'duration_unit',
				'template_id',
				'wu_preselected',
			]
		);

		/**
		 * Deal with site templates in a specific manner.
		 *
		 * @since 2.0.8
		 */
		if ('template_id' === $key) {
			$template_name = get_query_var('template_name', null);

			if (null !== $template_name) {
				$d = wu_get_site_domain_and_path($template_name);

				$wp_site = get_site_by_path($d->domain, $d->path);

				$site = $wp_site ? wu_get_site($wp_site->blog_id) : false;

				if ($site && $site->get_type() === Site_Type::SITE_TEMPLATE) {
					return $site->get_id();
				}
			}
		}

		/*
		 * Otherwise, simply check for its existence
		 * on the query object.
		 */
		if (in_array($key, $cart_arguments, true) && $from_query) {
			return $from_query;
		}

		return $value;
	}

	/**
	 * Setups the necessary boilerplate code to have checkouts work.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\UI\Checkout_Element $element The checkout element.
	 * @return void
	 */
	public function setup_checkout($element = null): void {

		if ($this->already_setup) {
			return;
		}

		$checkout_form_slug = wu_request('checkout_form');

		if (wu_request('pre-flight')) {
			$checkout_form_slug = false;

			$_REQUEST['pre_selected'] = $_REQUEST; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! $checkout_form_slug && is_a($element, \WP_Ultimo\UI\Checkout_Element::class)) {
			$pre_loaded_checkout_form_slug = $element->get_pre_loaded_attribute('slug', $checkout_form_slug);

			$checkout_form_slug = $pre_loaded_checkout_form_slug ?: $checkout_form_slug;
		}

		$this->checkout_form = wu_get_checkout_form_by_slug($checkout_form_slug);

		if (null === $this->session) {
			$this->session = wu_get_session('signup');
		}

		if ($this->checkout_form) {
			$this->steps = $this->checkout_form->get_steps_to_show();

			$first_step = current($this->steps);

			$step_name = wu_request('checkout_step', wu_get_isset($first_step, 'id', 'checkout'));

			$this->step_name = $step_name;

			$this->step = $this->checkout_form->get_step($this->step_name, true);

			if(!$this->step) {
				$this->step = [];
			}

			$this->step['fields'] ??= [];

			$this->auto_submittable_field = $this->contains_auto_submittable_field($this->step['fields']);

			$this->step['fields'] = wu_create_checkout_fields($this->step['fields']);
		}

		if (is_user_logged_in()) {
			$_REQUEST['user_id'] = get_current_user_id();
		}

		$this->already_setup = true;

		wu_no_cache(); // Prevent the registration page from being cached.
	}

	/**
	 * Checks if a list of fields has an auto-submittable field.
	 *
	 * @since 2.0.4
	 *
	 * @param array $fields The list of fields of a step we need to check.
	 * @return false|string False if no auto-submittable field is present, the field to watch otherwise.
	 */
	public function contains_auto_submittable_field($fields) {

		$relevant_fields = [];

		$field_types_to_ignore = [
			'hidden',
			'products',
			'submit_button',
			'period_selection',
			'steps',
		];

		// Extra check to prevent error messages from being displayed.
		if ( ! is_array($fields)) {
			$fields = [];
		}

		foreach ($fields as $field) {
			if (in_array($field['type'], $field_types_to_ignore, true)) {
				continue;
			}

			$relevant_fields[] = $field;

			if (count($relevant_fields) > 1) {
				return false;
			}
		}

		if ( ! $relevant_fields) {
			return false;
		}

		$auto_submittable_field = $relevant_fields[0]['type'];

		return wu_get_isset($this->get_auto_submittable_fields(), $auto_submittable_field, false);
	}

	/**
	 * Returns a list of auto-submittable fields.
	 *
	 * @since 2.0.4
	 * @return array
	 */
	public function get_auto_submittable_fields() {

		/**
		 * They key should be the signup field ID to search for,
		 * while the value should be the parameter we should watch for changes
		 * so we can submit the form when we detect one.
		 */
		$auto_submittable_fields = [
			'template_selection' => 'template_id',
			'pricing_table'      => 'products',
		];

		return apply_filters('wu_checkout_get_auto_submittable_fields', $auto_submittable_fields, $this);
	}

	/**
	 * Decides if we want to handle a step submission or a full checkout submission.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function maybe_handle_order_submission(): void {

		$this->setup_checkout();

		check_ajax_referer('wu_checkout');
		if ($this->is_last_step()) {
			$this->handle_order_submission();
		} else {
			$validation = $this->validate();

			if (is_wp_error($validation)) {
				wp_send_json_error($validation);
			}

			wp_send_json_success([]);
		}
	}

	/**
	 * Validates the order submission, and then delegates the processing to the gateway.
	 *
	 * We use database transactions in here to prevent failed sign-ups from being
	 * committed to the database. This means that if a \Throwable or a WP_Error
	 * happens anywhere in the process, we halt it and rollback on writes up to that point.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function handle_order_submission(): void {

		global $wpdb;

		$wpdb->query('START TRANSACTION'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		try {
			/*
			 * Allow developers to intercept an order submission.
			 */
			do_action('wu_before_handle_order_submission', $this);

			/*
			 * Here's where we actually process the order.
			 *
			 * Throwables are caught and they rollback
			 * any database writes sent up until this point.
			 *
			 * @see process_order below.
			 * @since 2.0.0
			 */
			$results = $this->process_order();

			/*
			 * Allow developers to change the results an order submission.
			 */
			do_action('wu_after_handle_order_submission', $results, $this);

			if (is_wp_error($results)) {
				$this->errors = $results;
			}
		} catch (\Throwable $e) {
			wu_maybe_log_error($e);

			$wpdb->query('ROLLBACK'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

			$this->errors = new \WP_Error('exception-order-submission', $e->getMessage(), $e->getTrace());
		}

		if (is_wp_error($this->errors)) {
			$wpdb->query('ROLLBACK'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

			wp_send_json_error($this->errors);
		}

		$wpdb->query('COMMIT'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		$this->session->set('signup', []);
		$this->session->commit();

		wp_send_json_success($results);
	}

	/**
	 * Process an order.
	 *
	 * This method is responsible for
	 * creating all the data elements we
	 * need in order to actually process a
	 * checkout.
	 *
	 * Those include:
	 * - A customer;
	 * - A pending payment;
	 * - A membership.
	 *
	 * With those elements, we can then
	 * delegate to the gateway to run their
	 * own preparations (@see run_preflight).
	 *
	 * We then return everything to be added
	 * to the front-end form. That data then
	 * gets submitted with the rest of the form,
	 * and eventually handled by process_checkout.
	 *
	 * @see process_checkout
	 *
	 * @since 2.0.0
	 * @return mixed[]|\WP_Error
	 */
	public function process_order() {

		global $current_site, $wpdb;

		/*
		 * First, we start to work on the cart object.
		 * We need to take into consideration the date we receive from
		 * the form submission.
		 */
		$cart = new Cart(
			apply_filters(
				'wu_cart_parameters',
				[
					'products'      => $this->request_or_session('products', []),
					'discount_code' => $this->request_or_session('discount_code'),
					'country'       => $this->request_or_session('billing_country'),
					'state'         => $this->request_or_session('billing_state'),
					'city'          => $this->request_or_session('billing_city'),
					'membership_id' => $this->request_or_session('membership_id'),
					'payment_id'    => $this->request_or_session('payment_id'),
					'auto_renew'    => $this->request_or_session('auto_renew', false),
					'duration'      => $this->request_or_session('duration'),
					'duration_unit' => $this->request_or_session('duration_unit'),
					'cart_type'     => $this->request_or_session('cart_type', 'new'),
				],
				$this
			)
		);

		/*
		 * Check if our order is valid.
		 *
		 * The is valid method checks for
		 * cart setup issues, as well as
		 */
		if ($cart->is_valid() === false) {
			return $cart->get_errors();
		}

		/*
		 * Update the checkout type
		 * based on the cart type we have on hand.
		 */
		$this->type = $cart->get_cart_type();

		/*
		 * Gets the gateway object we want to use.
		 *
		 * This will have been set on a previous step (session)
		 * or is going to be passed via the form (request)
		 */
		$gateway_id = $this->request_or_session('gateway');
		$gateway    = wu_get_gateway($gateway_id);

		/*
			* We need to handle free payments separately.
			*
			* In the same manner, if the order
			* IS NOT free, we need to make sure
			* the customer is not trying to game the system
			* passing the free gateway to get an free account.
			*
			* That's what's we checking on the else case.
			*/
		if ($cart->should_collect_payment() === false) {
			$gateway = wu_get_gateway('free');
		} elseif ( ! $gateway || $gateway->get_id() === 'free') {
			$this->errors = new \WP_Error('no-gateway', __('Payment gateway not registered.', 'multisite-ultimate'));

			return false;
		}

		/*
		 * If we do not have a gateway object,
		 * we need to bail.
		 */
		if ( ! $gateway) {
			return new \WP_Error('no-gateway', __('Payment gateway not registered.', 'multisite-ultimate'));
		}

		$this->gateway_id = $gateway->get_id();

		/*
		 * Now we need to validate the form.
		 *
		 * Here we use the validation rules set.
		 * @see validation_rules
		 */
		$validation = $this->validate();

		/*
		 * Bail on error.
		 */
		if (is_wp_error($validation)) {
			return $validation;
		}

		/*
		 * From now on, logic can be delegated to
		 * special methods, so we need to set
		 * the order as globally accessible.
		 */
		$this->order = $cart;

		/*
		 * Handles display names, if needed.
		 */
		add_filter('pre_user_display_name', [$this, 'handle_display_name']);

		/*
		 * If we get to this point, most of the validations are done.
		 * Now, we will actually begin to create new data elements
		 * if necessary.
		 *
		 * First, we need to check for a customer.
		 */
		$this->customer = $this->maybe_create_customer();

		/*
		 * We encountered errors while trying to create
		 * a new customer or retrieve an existing one.
		 */
		if (is_wp_error($this->customer)) {
			return $this->customer;
		}

		/*
		 * Next, we need to create a membership.
		 *
		 * The cart object has a couple of handy methods
		 * that allow us to easily convert from it
		 * to an array of data that we can use
		 * to create a membership.
		 */
		$this->membership = $this->maybe_create_membership();

		/*
		 * We encountered errors while trying to create
		 * a new membership or retrieve an existing one.
		 */
		if (is_wp_error($this->membership)) {
			return $this->membership;
		}

		/*
		 * Next step: maybe create a site.
		 *
		 * Depending on the status of the cart,
		 * we might need to create a pending site to
		 * attach to the membership.
		 */
		$this->pending_site = $this->maybe_create_site();

		/*
		 * It's not really possible to get a wp error
		 * in here for now but completeness dictates I add this.
		 */
		if (is_wp_error($this->pending_site)) {
			return $this->pending_site;
		}

		/*
		 * Next, we need to create a payment.
		 *
		 * The cart object has a couple of handy methods
		 * that allow us to easily convert from it
		 * to an array of data that we can use
		 * to create a payment.
		 */
		$this->payment = $this->maybe_create_payment();

		/*
		 * We encountered errors while trying to create
		 * a new payment or retrieve an existing one.
		 */
		if (is_wp_error($this->payment)) {
			return $this->payment;
		}

		/**
		 * Keep the cart used in checkout for later reference.
		 */
		$this->payment->update_meta('wu_original_cart', $this->order);

		/*
		 * Hey champs!
		 *
		 * If we are here, we have almost everything we
		 * need. Now is time to prepare things to hand
		 * over to the gateway.
		 */
		$this->order->set_customer($this->customer);
		$this->order->set_membership($this->membership);
		$this->order->set_payment($this->payment);

		$gateway->set_order($this->order);

		/*
		 * Before we move on,
		 * let's check if the user is logged in,
		 * and if not, let's do that.
		 */
		if ( ! is_user_logged_in()) {
			wp_clear_auth_cookie();

			$user_credentials = array(
				'user_login'    => $this->customer->get_username(),
				'user_password' => $this->request_or_session('password'),
			);

			// Remove the pending payment check action so the customer is not prompted to pay for the payment when they are already on the checkout page.
			remove_action('wp_login', array(Payment_Manager::get_instance(), 'check_pending_payments'), 10);

			// Sign in the user as if they used the login form.
			wp_signon($user_credentials, is_ssl());
		}

		/*
		 * Action time.
		 *
		 * Here's where we actually call the gateway
		 * and build the success data we want to return to the
		 * front-end form.
		 */
		try {
			/*
			 * Checks for free memberships.
			 */
			if ($this->order->is_free() && $this->order->get_recurring_total() === 0.0 && (! wu_get_setting('enable_email_verification', true) || $this->customer->get_email_verification() !== 'pending')) {
				if ($this->order->get_plan_id() === $this->membership->get_plan_id()) {
					$this->membership->set_status(Membership_Status::ACTIVE);

					$this->membership->save();
				}

				/**
				 * Trigger payment received manually.
				 *
				 * @since 2.0.10
				 */
				$gateway->trigger_payment_processed($this->payment, $this->membership);
			} elseif ($this->order->has_trial()) {
				$this->membership->set_date_trial_end(gmdate('Y-m-d 23:59:59', $this->order->get_billing_start_date()));
				$this->membership->set_date_expiration(gmdate('Y-m-d 23:59:59', $this->order->get_billing_start_date()));

				if (wu_get_setting('allow_trial_without_payment_method') && (! wu_get_setting('enable_email_verification', true) || $this->customer->get_email_verification() !== 'pending')) {
					/*
					 * In this particular case, we need to set the status to trialing here as we will not update the membership after and then, publish the site.
					 */
					$this->membership->set_status(Membership_Status::TRIALING);

					$this->membership->publish_pending_site_async();
				}

				$this->membership->save();

				/**
				 * Trigger payment received manually.
				 *
				 * @since 2.0.10
				 */
				$gateway->trigger_payment_processed($this->payment, $this->membership);
			}

			$success_data = [
				'nonce'           => wp_create_nonce('wp-ultimo-register-nonce'),
				'customer'        => $this->customer->to_search_results(),
				'total'           => $this->order->get_total(),
				'recurring_total' => $this->order->get_recurring_total(),
				'membership_id'   => $this->membership->get_id(),
				'payment_id'      => $this->payment->get_id(),
				'cart_type'       => $this->order->get_cart_type(),
				'auto_renew'      => $this->order->should_auto_renew(),
				'gateway'         => [
					'slug' => $gateway->get_id(),
					'data' => [],
				],
			];

			/*
			 * Let's the gateway do its thing.
			 *
			 * Here gateways will run pre-flight code
			 * such as setting up payment intents and other
			 * important things that we need to be able to finish
			 * the process.
			 */
			$result = $gateway->run_preflight();

			/*
			 * Attach the gateway results to the return array.
			 */
			$success_data['gateway']['data'] = $result && is_array($result) ? $result : [];

			/*
			 * On error, bail.
			 */
			if (is_wp_error($result)) {
				return $result;
			}
		} catch (\Throwable $e) {
				wu_maybe_log_error($e);

				return new \WP_Error('exception', $e->getMessage(), $e->getTrace());
		}

		/**
		 * Allow developers to triggers additional hooks.
		 *
		 * @since 2.0.9
		 *
		 * @param Checkout $checkout The checkout object instance.
		 * @param Cart     $cart The checkout cart instance.
		 * @return void
		 */
		do_action('wu_checkout_after_process_order', $this, $this->order);

		/*
		 * All set!
		 */
		return $success_data;
	}

	/**
	 * Checks if a customer exists, otherwise, creates a new one.
	 *
	 * @since 2.0.0
	 * @return \WP_Ultimo\Models\Customer|\WP_Error
	 */
	protected function maybe_create_customer() {
		/*
		 * Check if we have
		 * a customer for the current user.
		 */
		$customer = wu_get_current_customer();

		/*
		 * Get the form slug to save with the customer.
		 */
		$form_slug = $this->checkout_form ? $this->checkout_form->get_slug() : 'none';

		/*
		 * We don't have one,
		 * so we'll need to create it.
		 *
		 * We can't return early because we need
		 * to set and validate the billing address,
		 * and that happens at the end of this method.
		 */
		if (empty($customer)) {
			$username = $this->request_or_session('username');

			/*
			 * Handles auto-generation based on the email address.
			 */
			if ($this->request_or_session('auto_generate_username') === 'email') {
				$username = wu_username_from_email($this->request_or_session('email_address'));

				/*
				 * Case where the site title is also auto-generated, based on the username.
				 */
				if ($this->request_or_session('auto_generate_site_title') && $this->request_or_session('site_title', '') === '') {
					$_REQUEST['site_title'] = $username;
				}
			}

			/*
			 * If we get to this point,
			 * we don't have an existing customer.
			 *
			 * Next step then would be to create one.
			 */
			$customer_data = [
				'username'           => $username,
				'email'              => $this->request_or_session('email_address'),
				'password'           => $this->request_or_session('password'),
				'email_verification' => $this->get_customer_email_verification_status(),
				'signup_form'        => $form_slug,
				'meta'               => [],
			];

			/*
			 * If the user is logged in,
			 * we use the existing email address to create the customer.
			 */
			if ($this->is_existing_user()) {
				$customer_data = [
					'email'              => wp_get_current_user()->user_email,
					'email_verification' => 'verified',
				];
			} elseif (isset($customer_data['email']) && get_user_by('email', $customer_data['email'])) {
				return new \WP_Error('email_exists', __('The email address you entered is already in use.', 'multisite-ultimate'));
			}

			/*
			 * Tries to create it.
			 */
			$customer = wu_create_customer($customer_data);

			/*
			 * Something failed, bail.
			 */
			if (is_wp_error($customer)) {
				return $customer;
			}
		}

		/*
		 * Updates IP, and country
		 */
		$customer->update_last_login(true, true);

		/*
		 * Next, we need to validate the billing address,
		 * and save it.
		 */
		$billing_address = $customer->get_billing_address();

		$session = $this->session->get('signup') ?? [];
		$billing_address->load_attributes_from_post($session);

		/*
		 * Validates the address.
		 */
		$valid_address = $billing_address->validate();

		/*
		 * There's something invalid on the address,
		 * bail with the errors.
		 */
		if (is_wp_error($valid_address)) {
			return $valid_address;
		}

		$customer->set_billing_address($billing_address);

		$address_saved = $customer->save();

		/*
		 * This should rarely happen, but if something goes
		 * wrong with the customer update, we return a general error.
		 */
		if ( ! $address_saved) {
			return new \WP_Error('address_failure', __('Something wrong happened while attempting to save the customer billing address', 'multisite-ultimate'));
		}

		/*
		 * Handle meta fields.
		 *
		 * Gets all the meta fields for customers and
		 * save them to the customer as meta.
		 */
		$this->handle_customer_meta_fields($customer, $form_slug);

		/**
		 * Allow plugin developers to do additional stuff when the customer
		 * is added.
		 *
		 * Here's where we add the hooks for adding the customer->user to
		 * the main site as well, for example.
		 *
		 * @since 2.0.0
		 * @param Customer $customer The customer that was maybe created.
		 * @param Checkout $this     The current checkout class.
		 */
		do_action('wu_maybe_create_customer', $customer, $this);

		/*
		 * Otherwise, get the customer back.
		 */
		return $customer;
	}

	/**
	 * Save meta data related to customers.
	 *
	 * @since 2.0.0
	 *
	 * @param Customer $customer The created customer.
	 * @param string   $form_slug The form slug.
	 * @return void
	 */
	protected function handle_customer_meta_fields($customer, $form_slug) {

		if (empty($form_slug) || 'none' === $form_slug) {
			return;
		}

		$checkout_form = wu_get_checkout_form_by_slug($form_slug);

		if ($checkout_form) {
			$customer_meta_fields = $checkout_form->get_all_meta_fields('customer_meta');

			$meta_repository = [];

			foreach ($customer_meta_fields as $customer_meta_field) {
				/*
				 * Adds to the repository so we can save it again.
				 * in filters, if we need be.
				 */
				$meta_repository[ $customer_meta_field['id'] ] = $this->request_or_session($customer_meta_field['id']);

				wu_update_customer_meta(
					$customer->get_id(),
					$customer_meta_field['id'],
					$this->request_or_session($customer_meta_field['id']),
					$customer_meta_field['type'],
					$customer_meta_field['name']
				);
			}

			/**
			 * Allow plugin developers to save meta
			 * data in different ways if they need to.
			 *
			 * @since 2.0.0
			 * @param array $meta_repository The list of meta fields, key => value structured.
			 * @param Customer $customer The Multisite Ultimate customer object.
			 * @param Checkout $this The checkout class.
			 */
			do_action('wu_handle_customer_meta_fields', $meta_repository, $customer, $this);

			/**
			 * Do basically the same thing, now for user meta.
			 *
			 * @since 2.0.4
			 */
			$user_meta_fields = $checkout_form->get_all_meta_fields('user_meta');

			$user = $customer->get_user();

			$user_meta_repository = [];

			foreach ($user_meta_fields as $user_meta_field) {
				/*
				 * Adds to the repository so we can save it again.
				 * in filters, if we need be.
				 */
				$user_meta_repository[ $user_meta_field['id'] ] = $this->request_or_session($user_meta_field['id']);

				update_user_meta($customer->get_user_id(), $user_meta_field['id'], $this->request_or_session($user_meta_field['id']));
			}

			/**
			 * Allow plugin developers to save user meta
			 * data in different ways if they need to.
			 *
			 * @since 2.0.4
			 * @param array $meta_repository The list of meta fields, key => value structured.
			 * @param \WP_User $user The WordPress user object.
			 * @param Customer $customer The Multisite Ultimate customer object.
			 * @param Checkout $this The checkout class.
			 */
			do_action('wu_handle_user_meta_fields', $user_meta_repository, $user, $customer, $this);
		}
	}

	/**
	 * Checks if a membership exists, otherwise, creates a new one.
	 *
	 * @since 2.0.0
	 * @return \WP_Ultimo\Models\Membership|\WP_Error
	 */
	protected function maybe_create_membership() {
		/*
		 * The first thing we'll do is check the cart
		 * to see if a membership was passed.
		 */
		if ($this->order->get_membership()) {
			return $this->order->get_membership();
		}

		/*
		 * If that's not the case,
		 * we'll need to create a new one.
		 *
		 * The cart object has a couple of handy methods
		 * that allow us to easily convert from it
		 * to an array of data that we can use
		 * to create a membership.
		 */
		$membership_data = $this->order->to_membership_data();

		/*
		 * Append additional data to the membership.
		 */
		$membership_data['customer_id']   = $this->customer->get_id();
		$membership_data['user_id']       = $this->customer->get_user_id();
		$membership_data['gateway']       = $this->gateway_id;
		$membership_data['signup_method'] = wu_request('signup_method');

		/*
		 * Important dates.
		 */
		$membership_data['date_expiration'] = $this->order->get_billing_start_date();

		$membership = wu_create_membership($membership_data);

		$discount_code = $this->order->get_discount_code();

		if ($discount_code) {
			$membership->set_discount_code($discount_code);
			$membership->save();
		}

		return $membership;
	}

	/**
	 * Checks if a pending site exists, otherwise, creates a new one.
	 *
	 * @since 2.0.0
	 * @return bool|\WP_Ultimo\Models\Site|\WP_Error
	 */
	protected function maybe_create_site() {
		/*
		 * Let's get a list of membership sites.
		 * This list includes pending sites as well.
		 */
		$sites = $this->membership->get_sites();

		/*
		 * Decide if we should create a new site or not.
		 *
		 * When should we create a new pending site?
		 * There are a couple of rules:
		 * - The membership must not have a pending site;
		 * - The membership must not have an existing site;
		 *
		 * The get_sites method already includes pending sites,
		 * so we can safely rely on it.
		 */
		if ( ! empty($sites)) {
			/*
			 * Returns the first site on that list.
			 * This is not ideal, but since we'll usually only have
			 * one site here, it's ok. for now.
			 */
			return current($sites);
		}

		$site_url   = $this->request_or_session('site_url');
		$site_title = $this->request_or_session('site_title');

		if ( ! $site_url && ! $site_title) {
			return false;
		}

		$auto_generate_url = $this->request_or_session('auto_generate_site_url');

		$site_title = ! $site_title && ! $auto_generate_url ? $site_url : $site_title;

		/*
		 * Let's handle auto-generation of site URLs.
		 *
		 * To decide if we need to auto-generate the site URL,
		 * we'll check the request for the auto_generate_site_url = username request value.
		 *
		 * If that's present and no site_url is present, then we need to auto-generate this.
		 * The strategy here is simple, we basically set the site_url to the username and
		 * check if it is already taken.
		 */
		if (empty($site_url) || 'username' === $auto_generate_url) {
			if ('username' === $auto_generate_url) {
				$site_url = $this->customer->get_username();

				$site_title = $site_title ?: $site_url;
			} else {
				$site_url = strtolower(str_replace(' ', '', preg_replace('/&([a-z])[a-z]+;/i', '$1', htmlentities(trim((string) $site_title)))));
			}

			$d = wu_get_site_domain_and_path($site_url, $this->request_or_session('site_domain'));

			$n = 0;

			while (domain_exists($d->domain, $d->path)) {
				++$n;

				$site_url = $this->customer->get_username() . $n;

				$d = wu_get_site_domain_and_path($site_url, $this->request_or_session('site_domain'));
			}
		}

		$d = wu_get_site_domain_and_path($site_url, $this->request_or_session('site_domain'));

		/*
		 * Validates the site url.
		 */
		$results = wpmu_validate_blog_signup($site_url, $site_title, $this->customer->get_user());

		if ($results['errors']->has_errors()) {
			return $results['errors'];
		}

		/*
		 * Get the form slug to save with the customer.
		 */
		$form_slug = $this->checkout_form ? $this->checkout_form->get_slug() : 'none';

		/*
		 * Get the transient data to save with the site
		 * that way we can use it when actually registering
		 * the site on WordPress.
		 */
		$transient = [];

		if ($this->checkout_form) {
			$site_meta_fields = $this->checkout_form->get_all_fields();

			foreach ($site_meta_fields as $site_meta_field) {
				/*
				 * Removes password fields from transient data,
				 * to make sure plain passwords do not get stored
				 * on the database.
				 */
				if (str_contains((string) $site_meta_field['id'], 'password') ) {
					continue;
				}

				$transient[ $site_meta_field['id'] ] = $this->request_or_session($site_meta_field['id']);
			}
		}

		/*
		 * Gets the template id from the request.
		 * Here, there's some logic we need to do to
		 * try to get the template id if we get a
		 * template name instead of a number.
		 *
		 * This logic is handled inside the
		 * get_checkout_from_query_vars() method.
		 *
		 * @see get_checkout_from_query_vars()
		 */
		$template_id = apply_filters('wu_checkout_template_id', (int) $this->request_or_session('template_id'), $this->membership, $this);

		$site_data = [
			'domain'         => $d->domain,
			'path'           => $d->path,
			'title'          => $site_title,
			'template_id'    => $template_id,
			'customer_id'    => $this->customer->get_id(),
			'membership_id'  => $this->membership->get_id(),
			'transient'      => $transient,
			'signup_options' => $this->get_site_meta_fields($form_slug, 'site_option'),
			'signup_meta'    => $this->get_site_meta_fields($form_slug, 'site_meta'),
			'type'           => Site_Type::CUSTOMER_OWNED,
		];

		$pending_site = $this->membership->create_pending_site($site_data);

		return $pending_site;
	}

	/**
	 * Gets list of site meta data.
	 *
	 * @since 2.0.0
	 *
	 * @param string $form_slug The form slug.
	 * @param string $meta_type The meta type. Can be site_meta or site_option.
	 * @return array
	 */
	protected function get_site_meta_fields($form_slug, $meta_type = 'site_meta') {

		if (empty($form_slug) || 'none' === $form_slug) {
			return [];
		}

		$checkout_form = wu_get_checkout_form_by_slug($form_slug);

		$list = [];

		if ($checkout_form) {
			$site_meta_fields = $checkout_form->get_all_meta_fields($meta_type);

			foreach ($site_meta_fields as $site_meta_field) {
				$list[ $site_meta_field['id'] ] = $this->request_or_session($site_meta_field['id']);
			}
		}

		return $list;
	}

	/**
	 * Checks if a pending payment exists, otherwise, creates a new one.
	 *
	 * @since 2.0.0
	 * @return \WP_Ultimo\Models\Payment|\WP_Error
	 */
	protected function maybe_create_payment() {
		/*
		 * The first thing we'll do is check the cart
		 * to see if a payment was passed.
		 */
		$payment = $this->order->get_payment();

		if ($payment) {
			/**
			 *  Set the gateway in existing payment
			 */
			if ($payment->get_gateway() !== $this->gateway_id) {
				$payment->set_gateway($this->gateway_id);
				$payment->save();
			}

			return $this->order->get_payment();
		}

		/*
		 * The membership might have a previous payment.
		 * We'll go ahead and cancel that one out in cases
		 * of a upgrade/downgrade or add-on.
		 */
		$previous_payment = $this->membership->get_last_pending_payment();

		$cancel_types = [
			'upgrade',
			'downgrade',
			'addon',
		];

		if ($previous_payment && in_array($this->type, $cancel_types, true)) {
			$previous_payment->set_status(Payment_Status::CANCELLED);

			/*
			 * This can actually return a wp_error,
			 * but to be honest, we don't really care if we
			 * were able to cancel the previous payment or not.
			 */
			$previous_payment->save();
		}

		/*
		 * If that's not the case,
		 * we'll need to create a new one.
		 *
		 * The cart object has a couple of handy methods
		 * that allow us to easily convert from it
		 * to an array of data that we can use
		 * to create a payment.
		 */
		$payment_data = $this->order->to_payment_data();

		/*
		 * Append additional data to the payment.
		 */
		$payment_data['customer_id']   = $this->customer->get_id();
		$payment_data['membership_id'] = $this->membership->get_id();
		$payment_data['gateway']       = $this->gateway_id;

		/*
		 * If this is a free order and a downgrade we need
		 * to handle the status here as the payment is not
		 * passed to process_checkout method in this case.
		 */
		if ( ! $this->order->should_collect_payment() && 'downgrade' === $this->type) {
			$payment_data['status'] = Payment_Status::COMPLETED;
		}

		/*
		 * Create new payment.
		 */
		$payment = wu_create_payment($payment_data);

		/*
		 * Then, if this is a trial,
		 * we need to set the payment value to zero.
		 */
		if ($this->order->has_trial()) {
			$payment->attributes(
				[
					'tax_total'    => 0,
					'subtotal'     => 0,
					'refund_total' => 0,
					'total'        => 0,
				]
			);

			$payment->save();
		}

		return $payment;
	}

	/**
	 * Validates the checkout form to see if it's valid por not.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function validate_form(): void {

		$validation = $this->validate();

		if (is_wp_error($validation)) {
			wp_send_json_error($validation);
		}

		wp_send_json_success();
	}

	/**
	 * Creates an order object to display the order summary tables.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function create_order(): void {

		$this->setup_checkout();

		// Set billing address to be used on the order
		$country = ! empty($this->request_or_session('country')) ? $this->request_or_session('country') : $this->request_or_session('billing_country', '');
		$state   = ! empty($this->request_or_session('state')) ? $this->request_or_session('state') : $this->request_or_session('billing_state', '');
		$city    = ! empty($this->request_or_session('city')) ? $this->request_or_session('city') : $this->request_or_session('billing_city', '');

		$cart = new Cart(
			apply_filters(
				'wu_cart_parameters',
				[
					'products'      => $this->request_or_session('products', []),
					'discount_code' => $this->request_or_session('discount_code'),
					'country'       => $country,
					'state'         => $state,
					'city'          => $city,
					'membership_id' => $this->request_or_session('membership_id'),
					'payment_id'    => $this->request_or_session('payment_id'),
					'auto_renew'    => $this->request_or_session('auto_renew', false),
					'duration'      => $this->request_or_session('duration'),
					'duration_unit' => $this->request_or_session('duration_unit'),
					'cart_type'     => $this->request_or_session('cart_type', 'new'),
				],
				$this
			)
		);

		/**
		 * Calculate state and city options, if necessary.
		 *
		 * @since 2.0.11
		 */
		$country_data = wu_get_country($cart->get_country());

		wp_send_json_success(
			[
				'order'  => $cart->done(),
				'states' => wu_key_map_to_array($country_data->get_states_as_options(), 'code', 'name'),
				'cities' => wu_key_map_to_array($country_data->get_cities_as_options($state), 'code', 'name'),
				'labels' => [
					'state_field' => $country_data->get_administrative_division_name(null, true),
					'city_field'  => $country_data->get_municipality_name(null, true),
				],
			]
		);
	}

	/**
	 * Returns the checkout variables.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_checkout_variables() {

		global $current_site;

		/*
		 * Localized strings.
		 */
		$i18n = [
			'loading'        => __('Loading...', 'multisite-ultimate'),
			'added_to_order' => __('The item was added!', 'multisite-ultimate'),
			'weak_password'  => __('The Password entered is too weak.', 'multisite-ultimate'),
		];

		/*
		 * Get the default gateway.
		 */
		$default_gateway = current(array_keys(wu_get_active_gateway_as_options()));

		$d = wu_get_site_domain_and_path('replace');

		$site_domain = str_replace('replace.', '', (string) $d->domain);

		$duration      = $this->request_or_session('duration');
		$duration_unit = $this->request_or_session('duration_unit');

		// If duration is not set we check for a previous period_selection field in form to use;
		if (empty($duration) && $this->steps) {
			foreach ($this->steps as $step) {
				foreach ($step['fields'] as $field) {
					if ('period_selection' === $field['type']) {
						$duration      = $field['period_options'][0]['duration'];
						$duration_unit = $field['period_options'][0]['duration_unit'];

						break;
					}
				}

				if ($duration) {
					break;
				}
			}
		}

		$products = array_merge($this->request_or_session('products', []), wu_request('products', []));

		$geolocation = \WP_Ultimo\Geolocation::geolocate_ip('', true);

		/*
		 * Set the default variables.
		 */
		$variables = [
			'i18n'               => $i18n,
			'ajaxurl'            => wu_ajax_url(),
			'late_ajaxurl'       => wu_ajax_url('init'),
			'baseurl'            => remove_query_arg('pre-flight', wu_get_current_url()),
			'country'            => $this->request_or_session('billing_country', $geolocation['country']),
			'state'              => $this->request_or_session('billing_state', $geolocation['state']),
			'city'               => $this->request_or_session('billing_city'),
			'duration'           => $duration,
			'duration_unit'      => $duration_unit,
			'site_url'           => $this->request_or_session('site_url'),
			'site_domain'        => $this->request_or_session('site_domain', preg_replace('#^https?://#', '', $site_domain)),
			'is_subdomain'       => is_subdomain_install(),
			'gateway'            => wu_request('gateway', $default_gateway),
			'needs_billing_info' => true,
			'auto_renew'         => true,
			'products'           => array_unique($products),
		];

		/*
		 * There's a couple of things we need to determine.
		 *
		 * First, we need to check for a payment parameter.
		 */
		$payment_hash = wu_request('payment');

		/*
		 * If a hash exists, we need to retrieve the ID.
		 */
		$payment    = wu_get_payment_by_hash($payment_hash);
		$payment_id = $payment ? $payment->get_id() : 0;

		/*
		 * With the payment id in hand, we can
		 * we do not pass the products, as this is
		 * a retry.
		 */
		if ($payment_id) {
			$variables['payment_id'] = $payment_id;
		}

		/*
		 * The next case we need to take care of
		 * are addons, upgrades and downgrades.
		 *
		 * Those occur when we have a membership hash present
		 * and additional products, including or not a plan.
		 */
		$membership_hash = wu_request('membership');

		/*
		 * If a hash exists, we need to retrieve the ID.
		 */
		$membership    = wu_get_membership_by_hash($membership_hash);
		$membership_id = $membership ? $membership->get_id() : 0;

		/*
		 * With the membership id in hand, we can
		 * we do not pass the products, as this is
		 * a retry.
		 */
		if ($membership_id) {
			$variables['membership_id'] = $membership_id;
		}

		[$plan, $other_products] = wu_segregate_products($variables['products']);

		$variables['plan'] = $plan ? $plan->get_id() : 0;

		/*
		 * Try to fetch the template_id
		 */
		$variables['template_id'] = $this->request_or_session('template_id', 0);

		/*
		 * Let's also create a cart object,
		 * so we can pre-configure the form on the front-end
		 * accordingly.
		 */
		$variables['order'] = (new Cart($variables))->done();

		if ( ! empty($variables['order']->discount_code)) {
			$variables['discount_code'] = $variables['order']->discount_code->get_code();
		}

		/**
		 * Allow plugin developers to filter the pre-sets of a checkout page.
		 *
		 * Be careful, missing keys can completely break the checkout
		 * on the front-end.
		 *
		 * @since 2.0.0
		 * @param array    $variables Localized variables.
		 * @param Checkout $this The checkout class.
		 * @return array The new variables array.
		 */
		return apply_filters('wu_get_checkout_variables', $variables, $this);
	}

	/**
	 * Returns the validation rules for the fields.
	 *
	 * @todo The fields needs to declare this themselves.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function validation_rules() {
		/*
		 * Validations rules change
		 * depending on the type of order.
		 *
		 * For example, the only type that
		 * requires the site fields
		 * are the 'new'.
		 *
		 * First, let's set upm the general rules:
		 */
		$rules = [
			'email_address'    => 'required_without:user_id|email|unique:\WP_User,email',
			'username'         => 'required_without:user_id|alpha_dash|min:4|lowercase|unique:\WP_User,login',
			'password'         => 'required_without:user_id|min:6',
			'password_conf'    => 'same:password',
			'template_id'      => 'integer|site_template',
			'products'         => 'products',
			'gateway'          => '',
			'valid_password'   => 'accepted',
			'billing_country'  => 'country|required_with:billing_country',
			'billing_zip_code' => 'required_with:billing_zip_code',
			'billing_state'    => 'state',
			'billing_city'     => 'city',
		];

		/*
		 * Add rules for site when creating a new account.
		 */
		if ('new' === $this->type) {

			// char limit according https://datatracker.ietf.org/doc/html/rfc1034#section-3.1
			$rules['site_title'] = 'min:4';
			$rules['site_url']   = 'min:3|max:63|lowercase|unique_site';
		}

		return apply_filters('wu_checkout_validation_rules', $rules, $this);
	}

	/**
	 * Returns the list of validation rules.
	 *
	 * If we are dealing with a step submission, we will return
	 * only the validation rules that refer to the keys sent via POST.
	 *
	 * If this is the submission of the last step, though, we return all
	 * validation rules so we can validate the entire signup.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_validation_rules() {

		$validation_rules = $this->validation_rules();

		if (wu_request('pre-flight') || wu_request('checkout_form') === 'wu-finish-checkout') {
			$validation_rules = [];

			return $validation_rules;
		}

		if ($this->step_name && $this->is_last_step() === false) {
			$fields_available = array_column($this->step['fields'], 'id');

			/*
			 * Re-adds the template id check
			 */
			if (wu_request('template_id', null) !== null) {
				$fields_available[] = 'template_id';
			}

			$validation_rules = array_filter($validation_rules, fn($rule) => in_array($rule, $fields_available, true), ARRAY_FILTER_USE_KEY);
		}

		// We'll use this to validate product fields
		$product_fields = [
			'pricing_table',
			'products',
		];

		/**
		 * Add the additional required fields.
		 */
		foreach ($this->step['fields'] as $field) {
			/*
			 * General required fields
			 */
			if (wu_get_isset($field, 'required') && wu_get_isset($field, 'id')) {
				if (isset($validation_rules[ $field['id'] ])) {
					$validation_rules[ $field['id'] ] .= '|required';
				} else {
					$validation_rules[ $field['id'] ] = 'required';
				}
			}

			/*
			 * Product fields
			 */
			if (wu_get_isset($field, 'id') && in_array($field['id'], $product_fields, true)) {
				$validation_rules['products'] = 'products|required';
			}
		}

		/**
		 * Allow plugin developers to filter the validation rules.
		 *
		 * @since 2.0.20
		 * @param array    $validation_rules The validation rules to be used.
		 * @param Checkout $this The checkout class.
		 */
		return apply_filters('wu_checkout_validation_rules', $validation_rules, $this);
	}

	/**
	 * Validates the rules and make sure we only save models when necessary.
	 *
	 * @since 2.0.0
	 * @param array $rules Custom rules to use instead of the default ones.
	 * @return true|\WP_Error
	 */
	public function validate($rules = null) {

		$validator = new \WP_Ultimo\Helpers\Validator();

		$session = $this->session->get('signup');

		// Nonce check handled in calling method.
		if (is_array($session)) {
			$stack = array_merge($session, $_REQUEST); // phpcs:ignore WordPress.Security.NonceVerification
		} else {
			$stack = $_REQUEST;
		}

		if (null === $rules) {
			$rules = $this->get_validation_rules();
		}

		$base_aliases = [];

		$checkout_form_fields = $this->checkout_form ? $this->checkout_form->get_all_fields() : [];

		// Add current form fields
		foreach ($checkout_form_fields as $field) {
			$base_aliases[ $field['id'] ] = wu_get_isset($field, 'name', '');
		}

		// Add Billing Address fields
		foreach (Billing_Address::fields() as $field_key => $field) {
			$base_aliases[ $field_key ] = wu_get_isset($field, 'title', '');
		}

		// Add some hidden or compound fields ids
		$validation_aliases = array_merge(
			[
				'password_conf'  => __('Password confirmation', 'multisite-ultimate'),
				'template_id'    => __('Template ID', 'multisite-ultimate'),
				'valid_password' => __('Valid password', 'multisite-ultimate'),
				'products'       => __('Products', 'multisite-ultimate'),
				'gateway'        => __('Payment Gateway', 'multisite-ultimate'),
			],
			$base_aliases
		);

		/**
		 * Allow plugin developers to add custom aliases in form validator.
		 *
		 * @since 2.1
		 * @param array    $validation_aliases The array with id => alias.
		 * @param Checkout $this The checkout class.
		 */
		$validation_aliases = apply_filters('wu_checkout_validation_aliases', $validation_aliases, $this);

		$validator->validate($stack, $rules, $validation_aliases);

		if ($validator->fails()) {
			$errors = $validator->get_errors();

			$errors->remove('valid_password');

			return $errors;
		}

		return true;
	}

	/**
	 * Decides if we are to process a checkout.
	 *
	 * Needs to decide if we are simply putting the customer through the next step
	 * or if we need to actually process the checkout.
	 * It checks of the current checkout is multi-step;
	 * If it is, process info, save into session and send to the next step.
	 * Otherwise, we process the checkout.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function maybe_process_checkout(): void {
		/*
		 * Sets up the checkout
		 * environment.
		 */
		$this->setup_checkout();

		/*
		 * Checks if we should be here.
		 * We can only process a checkout
		 * if certain conditions are met.
		 */
		if ( ! $this->should_process_checkout()) {
			return;
		}

		/*
		 * Checks if we are in the last step.
		 *
		 * Multisite Ultimate supports multi-step checkout
		 * flows. That means that we do different
		 * things on the intermediary steps (mostly
		 * add things to the session) and on the final,
		 * where we process the checkout.
		 *
		 * Let's deal with the last step case first.
		 */
		if ($this->is_last_step()) {
			/*
			 * We are in the last step and
			 * we can process the checkout normally.
			 */
			$results = $this->process_checkout();

			/*
			 * Error!
			 *
			 * We redirect the customer back to the
			 * checkout page, passing the payment query
			 * arg so the customer can try again.
			 */
			if (is_wp_error($results)) {
				$redirect_url = wu_get_current_url();

				$this->session->set('errors', $results);

				/*
				 * We attach the payment data
				 * to the error, so we can retrieve it here.
				 */
				$payment = wu_get_isset($results->get_error_data(), 'payment');

				/*
				 * If the payment exists,
				 * use the hash to redirect the customer
				 * to a try again page.
				 */
				if ($payment) {
					$redirect_url = add_query_arg(
						[
							'payment' => $payment->get_hash(),
							'status'  => 'error',
						],
						$redirect_url
					);
				}

				/*
				 * Redirect go burrr!
				 */
				wp_safe_redirect($redirect_url);

				exit;
			}

			/*
			* This is not the final step,
			* so we just clean the data and save it
			* for later.
			*/
		} else {
			/*
			 * Cleans data and add it to the session.
			 *
			 * Here we remove the items that either
			 * have checkout_ on their name or start
			 * with an underscore.
			 */
			$to_save = array_filter($_POST, fn($item) => ! str_starts_with((string) $item, 'checkout_') && ! str_starts_with((string) $item, '_'), ARRAY_FILTER_USE_KEY); // phpcs:ignore WordPress.Security.NonceVerification

			if (isset($to_save['pre-flight'])) {
				unset($to_save['pre-flight']);
				$this->session->add_values('signup', ['pre_selected' => $to_save]);
			}

			/*
			 * Append the cleaned date to the
			 * active session.
			 */
			$this->session->add_values('signup', $to_save);
			$this->session->commit();

			/**
			 * Whether we should advance to the next step.
			 * This prevents breaking the checkout flow when triggered from a shortcode page.
			 */
			if ( ! wu_request('pre-flight')) {
				/*
				* Go to the next step.
				*/
				$next_step = $this->get_next_step_name();

				wp_safe_redirect(add_query_arg('step', $next_step));

				exit;
			}
		}
	}

	/**
	 * Runs pre-checks to see if we should process the checkout.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_process_checkout() {

		return wu_request('checkout_action') === 'wu_checkout' && ! wp_doing_ajax();
	}

	/**
	 * Handles the checkout submission.
	 *
	 * @since 2.0.0
	 * @return mixed
	 */
	public function process_checkout() {

		/**
		 * Before we process the checkout.
		 *
		 * @since 2.0.11
		 * @param Checkout $checkout The current checkout instance;
		 */
		do_action('wu_checkout_before_process_checkout', $this);

		$this->setup_checkout();

		$gateway    = wu_get_gateway(wu_request('gateway'));
		$payment    = wu_get_payment($this->request_or_session('payment_id'));
		$customer   = $payment->get_customer();
		$membership = $payment->get_membership();

		/**
		 * Get the original cart from saved payment meta so we can finish the process.
		 * It ensure that the cart is the same used in beginning of the process.
		 */
		$this->order = $payment->get_meta('wu_original_cart');
		$this->order->set_membership($membership);
		$this->order->set_customer($customer);
		$this->order->set_payment($payment);

		try {
			/*
			 * We need to handle free payments and trials w/o cc separately.
			 *
			 * In the same manner, if the order
			 * IS NOT free, we need to make sure
			 * the customer is not trying to game the system
			 * passing the free gateway to get an free account.
			 *
			 * That's what's we checking on the else case.
			 */
			if ($payment->get_status() === Payment_Status::COMPLETED) {
				$gateway = wu_get_gateway($payment->get_gateway());
			} elseif ($this->order->should_collect_payment() === false) {
				$gateway = wu_get_gateway('free');
			} elseif ($gateway->get_id() === 'free') {
					$this->errors = new \WP_Error('no-gateway', __('Payment gateway not registered.', 'multisite-ultimate'));

					return false;
			}

			if ( ! $gateway) {
				$this->errors = new \WP_Error('no-gateway', __('Payment gateway not registered.', 'multisite-ultimate'));

				return false;
			}

			/*
			 * Set the gateway data.
			 */
			$gateway->set_order($this->order);

			/*
			 * Let's grab the cart type.
			 * We'll use it to perform the necessary actions
			 * with memberships, payments, and such.
			 */
			$type = $this->order->get_cart_type();

			/*
			 * Here's where the action actually happens.
			 *
			 * The gateway takes in the info about the transaction
			 * and perform the necessary steps to make sure the
			 * data on the gateway correctly reflects the data on Multisite Ultimate.
			 */
			$status = $gateway->process_checkout($payment, $membership, $customer, $this->order, $type);

			/*
			 * If the gateway returns a explicit false value
			 * we understand that as a signal that the gateway wants to
			 * deal with the modifications by itself.
			 *
			 * In that case, we simply return.
			 */
			if (false === $status) {
				return;
			}

			/*
			 * Run after every checkout processing.
			 *
			 * @since 2.0.4
			 */
			do_action('wu_checkout_done', $payment, $membership, $customer, $this->order, $type, $this);

			/*
			 * Deprecated hook for registration.
			 */
			if (has_action('wp_ultimo_registration')) {
				$_payment = wu_get_payment($payment->get_id());

				$args = [
					0, // Site ID is not yet available at this point
					$customer->get_user_id(),
					$this->session->get('signup'),
					$_payment && $_payment->get_membership() ? new \WU_Plan($_payment->get_membership()->get_plan()) : false,
				];

				ob_start();

				do_action_deprecated('wp_ultimo_registration', $args, '2.0.0');

				ob_flush();
			}

			/*
			 * Otherwise, we redirect
			 * to the thank you page
			 * of the front-end mode.
			 */
			$redirect_url = $gateway->get_return_url();

			if ( ! is_admin()) {
				/**
				 * Set the redirect URL.
				 *
				 * This is a legacy filter. Some of the parameters
				 * passed are not available, such as the $site_id.
				 *
				 * @since 1.1.3 Let developers filter the redirect URL.
				 */
				$redirect_url = apply_filters('wp_ultimo_redirect_url_after_signup', $redirect_url, 0, get_current_user_id());

				$redirect_url = add_query_arg(
					[
						'payment' => $payment ? $payment->get_hash() : 'none',
						'status'  => 'done',
					],
					$redirect_url
				);
			}

			wp_safe_redirect($redirect_url);

			exit;
		} catch (\Throwable $e) {
			$membership_id = $this->order->get_membership() ? $this->order->get_membership()->get_id() : 'unknown';

			// translators: %s is the membership ID
			$log_message  = sprintf(__('Checkout failed for customer %s: ', 'multisite-ultimate'), $membership_id);
			$log_message .= $e->getMessage();

			wu_log_add('checkout', $log_message, LogLevel::ERROR);

			return new \WP_Error(
				'error',
				$e->getMessage(),
				[
					'trace'   => $e->getTrace(),
					'payment' => $payment,
				]
			);
		}
	}

	/**
	 * Handle user display names, if first and last names are available.
	 *
	 * @since 2.0.4
	 *
	 * @param string $display_name The current display name.
	 * @return string
	 */
	public function handle_display_name($display_name) {

		$first_name = $this->request_or_session('first_name', '');

		$last_name = $this->request_or_session('last_name', '');

		if ($first_name || $last_name) {
			$display_name = trim("$first_name $last_name");
		}

		return $display_name;
	}

	/*
	 * Helper methods
	 *
	 * These mostly deal with
	 * multi-step checkout control
	 * and can be mostly ignored!
	 */

	/**
	 * Get thank you page URL.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_thank_you_page() {

		return wu_get_current_url();
	}

	/**
	 * Checks if the user already exists.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_existing_user() {

		return is_user_logged_in();
	}

	/**
	 * Returns the customer email verification status we want to use depending on the type of checkout.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_customer_email_verification_status() {

		$email_verification_setting = wu_get_setting('enable_email_verification', 'free_only');

		switch ($email_verification_setting) {
			case 'never':
				return 'none';

			case 'always':
				return 'pending';

			case 'free_only':
				return $this->order->should_collect_payment() === false ? 'pending' : 'none';

			default:
				// Legacy behavior - handle boolean values
				$should_confirm_email = (bool) $email_verification_setting;
				return $this->order->should_collect_payment() === false && $should_confirm_email ? 'pending' : 'none';
		}
	}

	/**
	 * Adds the checkout scripts.
	 *
	 * @see $this->get_checkout_variables()
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts(): void {

		$custom_css = apply_filters('wu_checkout_custom_css', '');

		if ($custom_css) {
			wp_add_inline_style('wu-checkout', $custom_css);
		}

		wp_enqueue_style('wu-checkout');

		wp_enqueue_style('wu-admin');

		wp_register_script('wu-checkout', wu_get_asset('checkout.js', 'js'), ['jquery-core', 'wu-vue', 'moment', 'wu-block-ui', 'wu-functions', 'password-strength-meter', 'underscore', 'wp-polyfill', 'wp-hooks', 'wu-cookie-helpers'], wu_get_version(), true);

		wp_localize_script('wu-checkout', 'wu_checkout', $this->get_checkout_variables());

		wp_enqueue_script('wu-checkout');
	}

	/**
	 * Gets the info either from the request or session.
	 *
	 * We try to get the key from the session object, but
	 * if that doesn't work or it doesn't exist, we try
	 * to get it from the request instead.
	 *
	 * @since 2.0.0
	 *
	 * @param string $key Key to retrieve the value for.
	 * @param mixed  $default_value The default value to return, when nothing is found.
	 * @return mixed
	 */
	public function request_or_session($key, $default_value = false) {

		$value = $default_value;

		if (null !== $this->session) {
			$session = $this->session->get('signup');

			if (isset($session[ $key ])) {
				$value = $session[ $key ];
			}
		}

		$value = wu_request($key, $value);

		return $value;
	}

	/**
	 * Returns the name of the next step on the flow.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_next_step_name() {

		$steps = $this->steps;

		$keys = array_column($steps, 'id');

		$current_step_index = array_search($this->step_name, array_values($keys), true);

		/*
		 * If we enter the if statement below,
		 * it means that we don't have a step name set
		 * so we need to set it to the first.
		 */
		if (false === $current_step_index) {
			$current_step_index = 0;
		}

		$index = $current_step_index + 1;

		return $keys[ $index ] ?? $keys[ $current_step_index ];
	}

	/**
	 * Checks if we are in the first step of the signup.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_first_step() {

		$step_names = array_column($this->steps, 'id');

		if (empty($step_names)) {
			return true;
		}

		return array_shift($step_names) === $this->step_name;
	}

	/**
	 * Checks if we are in the last step of the signup.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_last_step() {

		/**
		 * What is this pre-flight parameter, you may ask...
		 *
		 * Well, some shortcodes can jump-start the signup process
		 * for example, the pricing table shortcode allows the
		 * customers to select a plan.
		 *
		 * This submits the form inside the shortcode to the registration
		 * page, but if that page is a one-step signup, this class-checkout
		 * will deal with it as if it was a last-step submission.
		 *
		 * The presence of the pre-flight URL parameter prevents that
		 * from happening.
		 *
		 * The summary is: if you need to post info to the registration page
		 * you need to add the ?pre-flight to the action URL.
		 */
		if (wu_request('pre-flight')) {
			return false;
		}

		$step_names = array_column($this->steps, 'id');

		if (empty($step_names)) {
			return true;
		}

		return array_pop($step_names) === $this->step_name;
	}

	/**
	 * Decides if we should display errors on the checkout screen.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function maybe_display_checkout_errors(): void {

		if (wu_request('status') !== 'error') {
			return;
		}
	}
}
