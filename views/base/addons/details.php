<?php
/**
 * Add-on details modal.
 *
 * @since 2.0.0
 */
?>
<style type="text/css">
	#plugin-information {
		position: static;
	}

	#plugin-information-footer {
		height: auto !important;
	}

	#plugin-information-title.with-banner {
		background-position: center;
		background-image: url("<?php echo $addon->images[0]['thumbnail'] ?? ''; ?>");
	}

	@media only screen and (-webkit-min-device-pixel-ratio: 1.5) {
		#plugin-information-title.with-banner {
		background-position: center;
		background-image: url("<?php echo $addon->images[0]['thumbnail'] ?? ''; ?>");
		}
	}
</style>

<div id="plugin-information">

	<div id="plugin-information-scrollable">

		<div id="plugin-information-title" class="with-banner">
			<div class="vignette"></div>
			<h2><?php echo esc_html($addon->name); ?></h2>
		</div>

		<div id="plugin-information-tabs" class="with-banner">

			<a name="description" href="#" class="current">

				<?php esc_html_e('Description', 'multisite-ultimate'); ?>

			</a>
			<!-- 

			<a name="faq" href="#">

				<?php esc_html_e('FAQ', 'multisite-ultimate'); ?>

			</a>

			<a name="changelog" href="#">

				<?php esc_html_e('Changelog', 'multisite-ultimate'); ?>

			</a>

			<a name="screenshots" href="#">

				<?php esc_html_e('Screenshots', 'multisite-ultimate'); ?>

			</a>

			<a name="reviews" href="#">

				<?php esc_html_e('Reviews', 'multisite-ultimate'); ?>

			</a>

			-->

		</div>

		<div id="plugin-information-content" class="with-banner">

			<div class="fyi">

				<ul>
					<li>
						<strong><?php _e('Author:', 'multisite-ultimate'); ?></strong> 
						<a class="wu-no-underline" href="<?php echo $addon->author_url; ?>" target="_blank">
							<?php echo $addon->author; ?>
						</a>
					</li>
					<!-- <li><strong>Version:</strong> 1.6</li>
					<li><strong>Last Updated:</strong> 2 months ago</li>
					<li>
						<strong>Requires WordPress Version:</strong>
						4.9 or higher
					</li>
					<li><strong>Compatible up to:</strong> 5.5.1</li> -->
					<?php if (isset($addon->requires_version)) : ?>

						<li>
							<strong><?php _e('Requires Multisite Ultimate Version:', 'multisite-ultimate'); ?></strong>
						<?php printf(__('%s or higher', 'multisite-ultimate'), $addon->requires_version); ?>
						</li>

					<?php endif; ?>

					<li>
						<a class="wu-no-underline" target="_blank" href="<?php echo esc_attr($addon->permalink); ?>">
						<?php esc_html_e('See on the Official Site »', 'multisite-ultimate'); ?>
						</a>
					</li>

				</ul>
			</div>
			<div id="section-holder">

				<!-- Description Section -->
				<div id="section-description" class="section" style="display: block; min-height: 200px;">

					<?php echo wp_kses_post($addon->description); ?>

				</div>

			</div>

		</div>

	</div>

	<div id="plugin-information-footer">

		<?php if (! $addon->prices['price'] > 0) : ?>

		<span class="wu-text-green-800 wu-inline-block wu-py-1">

			<?php esc_html_e('This is a Premium Add-on.', 'multisite-ultimate'); ?>

		</span>

		<?php endif; ?>

		<?php if ($addon->installed) : ?>

			<button
			disabled="disabled"
			data-slug="<?php echo esc_attr($addon_slug); ?>"
			class="button button-disabled right"
			>
			<?php esc_html_e('Already Installed', 'multisite-ultimate'); ?>
			</button>

		<?php else : ?>

			<?php if ($addon->is_purchasable) : ?>

				<?php if ($addon->extensions['wp-update-server-plugin']['download_url'] || (float) $addon->prices['price'] < 0) : ?>

				<button
				type="submit"
				name="install"
				data-slug="<?php echo esc_attr($addon_slug); ?>"
				class="button button-primary right"
				>
					<?php esc_html_e('Install Now', 'multisite-ultimate'); ?>
				</button>

			<?php else : ?>

				<a
				href="<?php echo esc_attr($addon->permalink . $addon->add_to_cart['url']); ?>"
				class="button button-primary right"
				>
				<?php esc_html_e('Purchase', 'multisite-ultimate'); ?>
				</a>

			<?php endif; ?>

			<?php endif; ?>

			<input type="hidden" name="action" value="wu_form_handler">

			<input type="hidden" name="addon" value="<?php echo $addon_slug; ?>">

			<?php wp_nonce_field('wu_form_addon_more_info'); ?>

		<?php endif; ?>

	</div>

</div>
