# Error Installing the Sunrise File

The sunrise.php file is a special file that WordPress looks for while it bootstraps itself. For WordPress to be able to detect the sunrise.php file, it needs to be located inside the **wp-content folder**.

When you activate WP Ultimo and go through the setup wizard like the one you have on the screenshot, WP Ultimo tries to copy our sunrise.php file to the wp-content folder.

![](assets/images/696c8902.png)

Most of the time, we’re able to successfully copy the file and everything works. However, if something is not properly set up (folder permissions, for example), you might run into a scenario where WP Ultimo is not able to copy the file.

If you read the error message Ultimo gives you, you’ll see that’s exactly what happened here: **Sunrise copy failed**.

![](assets/images/e469a16f.png)

To fix that, you can simply copy the sunrise.php file inside the wp-ultimo plugin folder and paste it into your wp-content folder. After you do that, reload the wizard page and the checks should pass.

![](assets/images/ed3d5ab9.png) In any case, this might warrant a general check of your folder permissions to avoid having problems in the future (not only with WP Ultimo but with other plugins and themes as well).

The **Health Check tool** that is part of WordPress (you can access it via your main site **admin panel > Tools > Health Check**) is capable of letting you know if you have folder permissions set to values that might cause problems with WordPress.

![](assets/images/767c7c37.png)
