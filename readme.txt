=== WP Multisite WaaS ===
Contributors: aanduque, superdav42 
Donate link: https://github.com/sponsors/superdav42/
Tags: multisite, waas, membership, domain-mapping, recurring payments, subscription
Requires at least: 5.3
Tested up to: 6.7.1
Requires PHP: 7.4.30
Stable tag: 2.3.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Complete Network Solution for transforming your WordPress Multisite into a Website as a Service (WaaS) platform.

== Description ==

**WP Multisite WaaS** helps you transform your WordPress Multisite installation into a powerful Website as a Service (WaaS) platform. This plugin enables you to offer website creation, hosting, and management services to your customers through a streamlined interface.

This plugin was formerly known as WP Ultimo and is now community maintained.

= Key Features =

* **Site Creation** - Allow customers to create their own sites in your network
* **Domain Mapping** - Support for custom domains with automated DNS verification
* **Payment Processing** - Integrations with popular payment gateways like Stripe and PayPal
* **Plan Management** - Create and manage subscription plans with different features and limitations
* **Template Sites** - Easily clone and use template sites for new customer websites
* **Customer Dashboard** - Provide a professional management interface for your customers
* **White Labeling** - Brand the platform as your own
* **Hosting Integrations** - Connect with popular hosting control panels like cPanel, RunCloud, and more

= Where to find help =

* [GitHub Repository](https://github.com/superdav42/wp-multisite-waas)
* [Issue Tracker](https://github.com/superdav42/wp-multisite-waas/issues)

= Contributing =

We welcome contributions to WP Multisite WaaS! To contribute effectively:

**Development Workflow:**

1. Fork the repository on GitHub
2. Create a feature branch from main
3. Run `npm install` and `composer install` to set up dependencies
4. Make your changes
5. Before committing, run `npm run build` to:
   * Generate translation POT files
   * Minify CSS and JS assets
   * Process and optimize other assets
6. Open a Pull Request with your changes

**Pull Request Guidelines:**

Please include a clear description of your changes and their purpose, reference any related issues, and ensure your code follows existing style conventions.

**Release Process:**

Releases are automated using GitHub Actions workflows that trigger when a version tag is pushed. 

To trigger a new release build, push a tag following the semantic versioning format:
`git tag v2.3.5` (for version 2.3.5) and then `git push origin v2.3.5`

The tag must begin with "v" followed by the version number (v*.*.*).

For more detailed contribution guidelines, see the [GitHub repository](https://github.com/superdav42/wp-multisite-waas).

== Installation ==

There are two recommended ways to install WP Multisite WaaS:

= Method 1: Using the pre-packaged release (Recommended) =

1. Download the latest release ZIP from the [Releases page](https://github.com/superdav42/wp-multisite-waas/releases)
2. Log in to your WordPress Network Admin dashboard
3. Navigate to Plugins > Add New > Upload Plugin
4. Choose the downloaded ZIP file and click "Install Now"
5. Network Activate the plugin through the 'Plugins' menu in WordPress
6. Follow the step by step Wizard to set the plugin up

= Method 2: Using Git and Composer (For developers) =

This method requires command-line access to your server and familiarity with Git and Composer.

1. Clone the repository to your plugins directory:
   ```
   cd wp-content/plugins/
   git clone https://github.com/superdav42/wp-multisite-waas.git
   cd wp-multisite-waas
   ```

2. Install the required dependencies using Composer:
   ```
   composer install
   ```

3. Network Activate the plugin in your WordPress Network Admin dashboard
4. Follow the setup wizard to complete the installation

= Common Installation Issues =

**"Failed opening required [...]/vendor/autoload_packages.php"**

This error occurs when the required vendor files are missing. This typically happens when:
- You've downloaded the repository directly from GitHub without using a release package
- The composer dependencies haven't been installed

Solution: Use the pre-packaged release from the [Releases page](https://github.com/superdav42/wp-multisite-waas/releases) or run `composer install` in the plugin directory.

**"Cannot declare class ComposerAutoloaderInitWPUltimoDependencies, because the name is already in use"**

This error usually occurs when updating from an older version of WP Ultimo or when multiple versions of the plugin are installed.

Solution: Deactivate and remove any older versions of WP Ultimo or WP Multisite WaaS before activating the new version.

**"Class 'WP_Ultimo\Database\Sites\Site_Query' not found"**

This error can occur if the plugin's autoloader isn't properly loading all the necessary classes.

Solution: Use the pre-packaged release from the [Releases page](https://github.com/superdav42/wp-multisite-waas/releases) which includes all required files.

== Requirements ==

* WordPress Multisite 5.3 or higher
* PHP 7.4.30 or higher
* MySQL 5.6 or higher
* HTTPS enabled (recommended for secure checkout)

== Frequently Asked Questions ==

= Can I use this plugin with a regular WordPress installation? =

No, this plugin specifically requires WordPress Multisite to function properly. It transforms your Multisite network into a platform for hosting multiple customer websites.

= Does this plugin support custom domains? =

Yes, WP Multisite WaaS includes robust domain mapping functionality that allows your customers to use their own domains for their websites within your network.

= Which payment gateways are supported? =

The plugin supports multiple payment gateways including Stripe, PayPal, and manually handled payments.

= Can I migrate from WP Ultimo to this plugin? =

Yes, WP Multisite WaaS is a community-maintained fork of WP Ultimo. The plugin includes migration tools to help you transition from WP Ultimo.

== Screenshots ==

1. Dashboard overview with key metrics
2. Subscription plans management
3. Customer management interface
4. Site creation workflow
5. Domain mapping settings

== Support ==

For support, please open an issue on the [GitHub repository](https://github.com/superdav42/wp-multisite-waas/issues).

== Upgrade Notice ==

We recommend running this in a staging environment before updating your production environment.

== Changelog ==

= 2.3.4 - 2024-01-31 =
* Fixed: Unable to checkout with any payment gateway
* Fixed: Warning Undefined global variable $pagenow

= 2.3.3 - 2024-01-29 =
* Improved: Plugin renamed to WP Multisite WaaS
* Removed: Enforcement of paid license
* Fixed: Incompatibilities with WordPress 6.7 and i18n timing
* Improved: Reduced plugin size by removing many unnecessary files and shrinking images

= 2.3.2 - 2023-12-05 =
* Improved: Ensure the initialization of the required class params during core updates verification to prevent errors in some environments
* Fixed: Make sure the amount in price variations is a float to avoid issues with the currency formatting
* Fixed: Ensure that the 'wu_original_cart' metadata is consistently set in payment during checkout process

= 2.3.1 - 2023-11-21 =
* Improved: Remove Freemius SDK from the plugin and add our own license validation
* Fixed: Remove double slash from Cloudways API calls, avoiding request rejection

= 2.3.0 - 2023-11-07 =
* Added: Allow the addition of custom meta fields in the customer edit page
* Improved: Change the WP_Ultimo\Helpers\Sender::email_sender() calls to use as a static method only
* Improved: Add more translated strings for Spanish, Brazilian Portuguese, and French
* Improved: Improve PHP 8.2 compatibility
* Fixed: Ensure scoped autoload dependencies with composer autoload
* Fixed: Some webhook events were not being triggered during the creation or update process
* Fixed: Bind the amount of the price variations to another field in product admin page to avoid errors with some currencies

= 2.2.3 - 2023-10-25 =
* Fixed: Resolved issues with certain popup form submissions failing due to an error in retrieving the form ID attribute

= 2.2.2 - 2023-10-24 =
* Improved: Avoid errors during site data duplication process
* Fixed: The invoices PDF loading
* Fixed: Errors not showing in form modals
* Fixed: Ensure the correct period is used in forms where the period switcher is in a upcoming step

= 2.2.1 - 2023-10-16 =
* Fixed: Ensure the default public title exhibition of all payment gateways in pdf invoices
* Fixed: Scope the mPDF dependency to avoid conflicts with other plugins
* Fixed: checkout forms duplicate feature
* Fixed: Avoid create a duplicated user during a site duplication with multiple accounts enabled

= 2.2.0 - 2023-09-28 =
* Added: PHP 8.2 compatibility
* Added: Webhook errors stack trace on logs
* Improved: Use webhook event name instead of event slug in the create webhook popup labels
* Improved: Removed unnecessary params in class WP_Ultimo\Compat\Multiple_Accounts_Compat
* Improved: Scope PSR classes to avoid conflicts with other plugins
* Improved: Ensure that the thank you page reloads after the pending site is created
* Improved: Ensure Domain::get_blog_id() method returns the correct type
* Improved: Allow float values in discount codes
* Improved: Allow discount codes with two or more characters in the code
* Improved: Validate user email and username in all steps in a multi-step checkout form
* Fixed: Login getting the right user via email in multiple accounts compat
* Fixed: Multiple account user query to avoid MySQL query errors
* Fixed: Pre-selected products field not loading at checkout form initialization
* Fixed: Pre-selected products field avoiding the auto-submit of the checkout form
* Fixed: Do not persist useremail in object cache on multiple accounts compat
* Fixed: Lost password redirection in subsites
* Fixed: Serverpilot integration instructions
* Fixed: Runcloud integration instructions
* Fixed: Remove the ID field from new database items to be added to avoid errors with auto-increment
* Fixed: Get correct product variation in Line_Item::get_product() method
* Fixed: Dismissal of the affiliation message
* Fixed: Keep custom body classes in customer-facing admin pages
* Fixed: Keep site title during template switch
* Fixed: Customer template in Selectize search

= 2.1.5 - 2023-09-01 =
* Fixed: Error preventing bulk delete popup and pending payment popup from loading

= 2.1.4 - 2023-08-29 =
* Added: New webhooks for Payment, Costumer and Membership
* Added: Select templates by categories in templates selection field on forms
* Added: Option to show all or owned sites on My Sites block
* Added: Divi Builder compatibility in page edit screen
* Added: Filter `wu_bulk_action_function_prefix` on `process_bulk_action()` method
* Added: Better messages for membership downgrade via PayPal
* Added: Remove jQuery from legacy-signup, template-previewer, thank-you.js, visit-counter.js, wubox.js and vue-apps.js files
* Fixed: Domain mapping allowing uppercase
* Fixed: Eliminates the fake email loophole and enables users to register on different subsites using the same email
* Fixed: Fixes the resetting password process
* Fixed: Allow user verification on wp-activate.php page
* Fixed: PayPal renew payments not showing line items
* Fixed: Change the status in membership and payment schemas for rest api validation
* Fixed: Fix the detection of pre-selected products in the checkout form
* Fixed: Rrlencode on my sites widget URL
* Fixed: Plan frequencies and duration on migration from v1 to v2
* Fixed: Add the username in login error handler message

= 2.1.3 - 2023-08-09 =
* Added: WordPress 6.3 compatibility
* Added: Implemented periodic cleanup for possible forgotten pending sites from memberships
* Added: Enabled membership addon products cancellation
* Added: Synchronized membership products and prices with gateway subscriptions
* Changed: Now ensures that the site is a customer site before syncing the site's plugin limitations
* Fixed: Updated the checkout process to get all fields for search and replace in template sites
* Fixed: Corrected the display of product limits in the legacy pricing table
* Fixed: Added validation for site names to allow hyphens
* Fixed: Addressed possible PayPal API errors during the checkout process

For older versions, please see the GitHub repository.
