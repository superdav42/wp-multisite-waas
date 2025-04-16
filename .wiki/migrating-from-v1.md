# Migrating from V1

## WP Ultimo has switched from its original 1.x family of releases to the 2.x family of releases.

WP Ultimo version 2.0 and up is a complete rewrite of the codebase, meaning that there's very little shared between the old version and the new one. For that reason, when upgrading from 1.x to 2.x, your data will need to be migrated to a format that the new versions can understand.

Thankfully, WP Ultimo 2.0+ **comes with a migrator** built into the core that is capable of detecting data from the old version and converting it to the new format. This migration happens during the **Setup Wizard** of version 2.0+.

This lesson covers how the migrator works, what to do in cases of failure, and how to troubleshoot issues that might arise during this process.

_**IMPORTANT: Before you begin upgrading from version 1.x to version 2.0 please make sure that you create a backup of your site database**_

## First steps

The first step is to download the plugin .zip file and install version 2.0 on your network admin dashboard.

After you [install and activate version 2.0](1677127281-installing-wp-ultimo.html), the system will automatically detect that your Multisite is running on the legacy version and you will see this message at the top of the plugin page.

_**NOTE:** If you have WP Ultimo 1.x installed on your Multisite, you'll have the option to replace the plugin with the version you've just downloaded. Please, go ahead and click to **Replace current with uploaded**._

![](assets/images/61a07cb4.png)

The next page will let you know what legacy add-ons you have installed along with version 1.x. It will have instructions on whether the version you are using is compatible with version 2.0 or if you need to install an upgraded version of the add-on after the migration.

![Message on the top of the plugins page: Thanks for updating to WP Ultimo version 2.0. There's a link below it that leads the user to the version upgrader. Then, there's a list of add-ons that need to be updated.](assets/images/3b396ba7.png)

Once you are ready to proceed, you can click the button that says **Visit the Installer to finish the upgrade**.

![Framed in red: button saying Visit the Installer to finish the upgrade](assets/images/882493d5.png)

It will then bring you to the installation wizard page with some welcome messages. You just need to click **Get Started** to move to the next page.

![Setup Wizard's welcoming page. Framed in red at the bottom-right corner: Get Started button.](assets/images/19e81a54.png)

After clicking **Get Started** , it will redirect you to the Pre-install Checks_._ This will show you your System Information and WordPress installation and tell you if it meets [WP Ultimo's requirements](https://help.wpultimo.com/article/323-wp-ultimo-requirements).

![Pre-install Checks page showing confirmation messages that the installation meets WP Ultimo's requirements. Framed on red, on the bottom-right corner: Go to the next step button.](assets/images/e889e503.png)

The next step is to key in your WP Ultimo license key and activate the plugin. This will ensure that all the features, including add-ons, will be available on your site.

![License activation page listing what the support includes and what it doesn't. There's a box on the bottom to insert the plugin's license. Framed in red, on the bottom-right corner: Agree and activate button.](assets/images/f6576ded.png)

After putting in your key, click **Agree & Activate**.

After license activation, you can begin the actual installation by clicking **Install** on the next page. This will automatically create the necessary files and database needed for version 2.0 to function.

![Installation page showing what will be updated in order to WP Ultimo to function. Framed in red, on the bottom-right: Install button](assets/images/dcbeae90.png)

## Now, the migration

The migrator has a built-in safety feature wherein it will check your entire multisite to make sure that all your WP Ultimo data can be migrated without any issues. Click the **Run Check** button to start the process.

![Migration page explaining it will run a check to see if all your data from v1 can be converted. Framed in red, on the bottom-right corner: Run check button](assets/images/9242dac0.png)

After running the check, you have two possibilities: the result can be either **with** an error or **without an error**.

### With Error

Should you get an error message, you will need to reach out to our support team so that they can assist you in fixing the error. Make sure you **provide the error log** when you create a ticket. You can download the log or you can click the link that says contact our support team. It will open the help widget on the right-hand side of your page with the fields pre-populated for you that include the error logs under the description.

_**Since the system found an error, you won't be able to proceed to migrate to version 2.0. You can then roll back to version 1.x to resume running your network until the error is fixed.**_

### Without Error

If the system doesn't find any error, you will see a success message and a **Migrate** button at the bottom that will allow you to proceed with the migration. On this page, you will be reminded to create a backup of your database before moving forward, which we strongly recommend. Hit **Migrate** if you already have a backup.

![Migration page showing a success message and a recommendation to create a backup.](assets/images/7a708c96.png)

![Framed in red, on the bottom-right corner: Migrate button](assets/images/592231a4.png)

And this is all it takes!

You can either continue to run the Wizard setup to update your logo and other things on your network or start navigating your WP Ultimo version 2.0 menu and its new interface. Go ahead and have some fun.
