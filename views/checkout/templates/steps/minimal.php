<?php
/**
 * Displays the navigation part on the bottom of the page
 *
 * This template can be overridden by copying it to yourtheme/wp-ultimo/signup/signup-steps-navigation.php.
 *
 * HOWEVER, on occasion WP Multisite WaaS will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author      NextPress
 * @package     WP_Ultimo/Views
 * @version     1.4.0
 */

if ( ! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>

<!-- This example requires Tailwind CSS v2.0+ -->
<nav aria-label="<?php esc_attr_e('Progress', 'wp-multisite-waas'); ?>">
	<ul class="wu-minimal-steps">


	<?php
	foreach ($steps as $index => $step) :
		$step_key = $step['id'];

		/**
		 * Class element of the Step Status Bar
		*
		 * @var string
		 */
		$class = '';

		if ($step_key === $current_step) {
			$class = 'step-current';
		} elseif (array_search($current_step, array_column($steps, 'id')) > array_search($step_key, array_column($steps, 'id'))) {
			$class = 'step-done';
		}

		?>

		<li class="<?php echo esc_attr($class); ?>">
		<span class="wu-minimal-steps-bar">&nbsp;</span>
		<span class="wu-minimal-steps-step-count"><?php printf(__('Step %d', 'wp-multisite-waas'), $index + 1); ?></span>
		<span class="wu-minimal-steps-step-label"><?php echo $step['name']; ?></span>
		</li>

<?php endforeach; ?>

	</ul>
</nav>
