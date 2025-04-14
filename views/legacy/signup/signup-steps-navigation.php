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

if ( ! $signup) {
	return;
}

?>

<?php
/**
 * Displays the Steps Bar on the bottom of the page
 */
$ouput_steps = $signup->get_steps(false);
$count       = count($ouput_steps);
$percent     = 100 / $count;

?>

<ol class="wu-setup-steps">

	<?php
	foreach ($ouput_steps as $step) :
		$step_key = $step['id'];

		/**
		 * Class element of the Step Status Bar
	 *
		 * @var string
		 */
		$class = '';

		if ($signup->step === $step_key) {
			$class = 'active';
		} elseif (array_search($signup->step, array_keys($signup->steps)) > array_search($step_key, array_keys($signup->steps))) {
			$class = 'done';
		}

		?>

	<li style="width: <?php echo $percent; ?>%;" class="<?php echo $class; ?>">

		<?php echo esc_html($step['name']); ?>

	</li>

<?php endforeach; ?>

</ol>

<?php if ($prev_link = $signup->get_prev_step_link()) : ?>

	<div class="wu-signup-back">

	<a class="wu-signup-back-link" href="<?php echo $prev_link; ?>">

		<?php esc_html_e('&larr; Go Back to Previous Step', 'wp-multisite-waas'); ?>

	</a>

	</div>

<?php endif; ?>
