# WP Multisite WaaS

The Complete Network Solution for transforming your WordPress Multisite into a Website as a Service (WaaS) platform.

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![WordPress: 6.7.1 Tested](https://img.shields.io/badge/WordPress-6.7.1%20Tested-green.svg)](https://wordpress.org/)
[![PHP: 7.4.30+](https://img.shields.io/badge/PHP-7.4.30%2B-purple.svg)](https://php.net/)

## Description

WP Multisite WaaS helps you transform your WordPress Multisite installation into a powerful Website as a Service (WaaS) platform. This plugin enables you to offer website creation, hosting, and management services to your customers through a streamlined interface.

Now community maintained.

## Installation

There are two recommended ways to install WP Multisite WaaS:

### Method 1: Using the pre-packaged release (Recommended)

1. Download the latest release ZIP from the [Releases page](https://github.com/superdav42/wp-multisite-waas/releases)
2. Log in to your WordPress Network Admin dashboard
3. Navigate to Plugins > Add New > Upload Plugin
4. Choose the downloaded ZIP file and click "Install Now"
5. Network Activate the plugin through the 'Plugins' menu in WordPress
6. Follow the step by step Wizard to set the plugin up

### Method 2: Using Git and Composer (For developers)

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

## Common Installation Issues

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

## Requirements

- WordPress Multisite 5.3 or higher
- PHP 7.4.30 or higher
- MySQL 5.6 or higher
- HTTPS enabled (recommended for secure checkout)

## Support

For support, please open an issue on the [GitHub repository](https://github.com/superdav42/wp-multisite-waas/issues).

## Upgrade Notice

We recommend running this in a staging environment before updating your production environment.

## Recent Changes

### Version [2.3.4] - Released on 2024-01-31
- Fixed: Unable to checkout with any payment gateway
- Fixed: Warning Undefined global variable $pagenow

### Version [2.3.3] - Released on 2024-01-29
- Improved: Plugin renamed to WP Multisite WaaS
- Removed: Enforcement of paid license
- Fixed: Incompatibilities with WordPress 6.7 and i18n timing
- Improved: Reduced plugin size by removing many unnecessary files and shrinking images

For the complete changelog, please see [readme.txt](readme.txt).

## Contributors

WP Multisite WaaS is an open-source project with contributions from:
- [aanduque](https://github.com/aanduque)
- [superdav42](https://github.com/superdav42)
- [And the community](https://github.com/superdav42/wp-multisite-waas/graphs/contributors)

## License

WP Multisite WaaS is licensed under the GPL v2 or later.

Copyright © 2024 [WP Multisite WaaS Contributors](https://github.com/superdav42/wp-multisite-waas/graphs/contributors)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA 