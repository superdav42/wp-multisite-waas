<?php
/**
 * Test view.
 *
 * @since 2.0.0
 */
?>
<h1><?php esc_html_e('Testing the Integration', 'wp-multisite-waas'); ?></h1>

<p class="wu-text-lg wu-text-gray-600 wu-my-4 wu-mb-6">
	<?php printf(__('We will send a test API call to %s to make sure we are able to connect. This will confirm if everything we did so far have worked.', 'wp-multisite-waas'), $integration->get_title()); ?>
</p>

<div id="integration-test">

	<div v-if="loading" class="wu-flex wu-rounded wu-content-center wu-py-2 wu-px-4 wu-bg-gray-100 wu-border wu-border-solid wu-border-gray-300 wu-m-0">
	<span class="dashicons dashicons-warning wu-text-blue-400 wu-self-center wu-mr-2"></span>
	<span>
		<?php esc_html_e('Sending API call...', 'wp-multisite-waas'); ?>
	</span>
	</div>

	<div v-cloak v-if="!loading && success" class="wu-flex wu-rounded wu-content-center wu-py-2 wu-px-4 wu-bg-gray-100 wu-border wu-border-solid wu-border-gray-300 wu-m-0">
	<span class="dashicons dashicons-yes-alt wu-text-green-400 wu-self-center wu-mr-2"></span>
	<span>
		<?php esc_html_e('Yey! Everything seems to be working!', 'wp-multisite-waas'); ?>
	</span>
	</div>

	<div v-cloak v-if="!loading && !success" class="wu-flex wu-rounded wu-content-center wu-py-2 wu-px-4 wu-bg-gray-100 wu-border wu-border-solid wu-border-gray-300 wu-m-0">
	<span class="dashicons dashicons-dismiss wu-text-red-400 wu-self-center wu-mr-2"></span>
	<span>
		<?php esc_html_e('Something wrong happened... We might need to make some adjustments to make this work.', 'wp-multisite-waas'); ?>
	</span>
	</div>

	<pre class="wu-overflow-auto wu-p-4 wu-rounded wu-content-center wu-bg-gray-800 wu-text-white wu-font-mono wu-border wu-border-solid wu-border-gray-300" v-html="results"><?php esc_html_e('Waiting for results...', 'wp-multisite-waas'); ?></pre>

	<div v-cloak v-if="!loading && !success">

	<h1><?php esc_html_e('Troubleshooting', 'wp-multisite-waas'); ?></h1>

	<ol>
		<li>
		<?php esc_html_e('Go back to the <strong>Configuration</strong> step - if available - and make sure you entered all the necessary information correctly;', 'wp-multisite-waas'); ?>
		</li>
		<li>
		<?php echo wp_kses_post(__('If you have added the constants to your wp-config.php file manually, double check to make sure you\'ve added them to the right wp-config.php file and in the right place (just above the <code>/* That\'s all, stop editing! Happy publishing. */</code>)', 'wp-multisite-waas')); ?>);
		</li>
		<li>
		<?php esc_html_e('If you are sure everything is right, take a screenshot of this screen and contact support.', 'wp-multisite-waas'); ?>
		</li>
	</ol>

	</div>

	<!-- Submit Box -->
	<div v-cloak v-if="!loading && !success" class="wu-flex wu-justify-between wu-bg-gray-100 wu--m-in wu-mt-4 wu-p-4 wu-overflow-hidden wu-border-t wu-border-solid wu-border-l-0 wu-border-r-0 wu-border-b-0 wu-border-gray-300">

	<a href="<?php echo esc_url($page->get_prev_section_link()); ?>" class="wu-self-center button button-large wu-float-left">
		<?php esc_html_e('&larr; Go Back', 'wp-multisite-waas'); ?>
	</a>

	</div>
	<!-- End Submit Box -->

	<div v-cloak v-if="!loading && success">
	<?php
	/**
	 * Default Submit box
	 */
	$page->render_submit_box();
	?>
	</div>

</div>

<script>
	(function($) {
	$(document).ready(function() {

		new Vue({
		el: "#integration-test",
		data: {
			success: false,
			loading: false,
			results: '<?php echo esc_js(__('Waiting for results...', 'wp-multisite-waas')); ?>',
		},
		mounted: function() {

			var that = this;

			this.loading = true;

			setTimeout(() => {

			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
				action: 'wu_test_hosting_integration',
				integration: '<?php echo esc_js($integration->get_id()); ?>',
				},
				success: function(response) {
				console.log(response);
				that.loading = false;
				that.success = response.success;
				that.results = response.data;
				}
			});

			}, 1000);

		},
		});

	});
	})(jQuery);
</script>

