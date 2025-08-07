<?php
/**
 * Add-ons list page.
 *
 * @since 2.0.0
 */
?>

<style>
body .theme-browser .theme .theme-name {
	height: auto;
}
</style>

<div id="wp-ultimo-wrap" class="<?php wu_wrap_use_container(); ?> wrap wu-wrap <?php echo esc_attr($classes); ?>">

	<h1 class="wp-heading-inline">

	<?php echo esc_html($page->get_title()); ?> <span v-cloak v-if="count > 0" class="title-count theme-count" v-text="count"></span>

	<?php
	/**
	 * You can filter the get_title_link using wu_page_list_get_title_link, see class-wu-page-list.php
	 *
	 * @since 1.8.2
	 */
	foreach ($page->get_title_links() as $action_link) :
		$action_classes = isset($action_link['classes']) ? $action_link['classes'] : '';

		?>

		<a title="<?php echo esc_attr($action_link['label']); ?>" href="<?php echo esc_url($action_link['url']); ?>" class="page-title-action <?php echo esc_attr($action_classes); ?>">

		<?php if ($action_link['icon']) : ?>

			<span class="dashicons dashicons-<?php echo esc_attr($action_link['icon']); ?> wu-text-sm wu-align-middle wu-h-4 wu-w-4">
			&nbsp;
			</span>

		<?php endif; ?>

		<?php echo $action_link['label']; ?>

		</a>

	<?php endforeach; ?>

	<?php
	/**
	 * Allow plugin developers to add additional buttons to list pages
	 *
	 * @since 1.8.2
	 * @param WU_Page WP Ultimo Page instance
	 */
	do_action('wu_page_addon_after_title', $page);
	?>

	</h1>

	<?php if (wu_request('updated')) : ?>

	<div id="message" class="updated notice wu-admin-notice notice-success is-dismissible below-h2">
		<p><?php esc_html_e('Settings successfully saved.', 'wp-ultimo'); ?></p>
	</div>

	<?php endif; ?>

	<hr class="wp-header-end">

	<form method="post">

	<div id="poststuff" class="md:wu-flex">

		<div class="wu-w-full md:wu-w-4/12 lg:wu-w-2/12">

		<div class="wu-py-4 wu-relative" id="search-addons">

			<input
			type="text"
			placeholder="<?php esc_attr_e('Search Add-ons', 'wp-ultimo'); ?>"
			class="wu-w-full"
			v-model="search"
			>

		</div>

		<!-- Navigator -->
		<ul id="addons-menu">

			<li class="md:wu-hidden wu-p-4 wu-font-bold wu-uppercase wu-text-xs wu-text-gray-700">
			<?php _e('Menu', 'wp-ultimo'); ?>
			</li>

			<?php

			/**
			 * We need to set a couple of flags in here to control clickable navigation elements.
			 * This flag makes sure only steps the user already went through are clickable.
			 */
			$is_pre_current_section = true;

			?>

			<?php foreach ($sections as $section_name => $section) : ?>

				<?php

				/**
				 * Updates the flag after the current section is looped.
				 */
				if ($current_section === $section_name) {
					$is_pre_current_section = false;
				}

				?>

				<?php if (wu_get_isset($section, 'separator')) : ?>

				<!-- Separator Item -->
				<li class="wu-sticky wu-py-2 wu-px-4">&nbsp;</li>

			<?php else : ?>

				<!-- Menu Item -->
				<li class="wu-sticky">

				<!-- Menu Link -->
				<a 
					href="<?php echo esc_url($page->get_section_link($section_name)); ?>" 
					class="wu-block wu-py-2 wu-px-4 wu-no-underline wu-text-sm wu-rounded wu-text-gray-600 hover:wu-text-gray-700"
					:class="category === '<?php echo esc_attr($section_name); ?>' ? 'wu-bg-gray-300 wu-text-gray-800' : 'wu-text-gray-600 hover:wu-text-gray-700'"
					@click.prevent="set_category('<?php echo esc_attr($section_name); ?>')"
				>

					<span class="<?php echo esc_attr($section['icon']); ?> wu-align-text-bottom wu-mr-1"></span>

					<?php echo $section['title']; ?>

				</a>
				<!-- End Menu Link -->

				<?php if (! empty($section['sub-sections'])) : ?>

					<!-- Sub-menu -->
					<ul class="classes" v-show="false" v-cloak>

					<?php foreach ($section['sub-sections'] as $sub_section_name => $sub_section) : ?>

						<li class="classes">
						<a href="<?php echo esc_url($page->get_section_link($section_name) . '#' . $sub_section_name); ?>" class="wu-block wu-py-2 wu-px-4 wu-no-underline wu-text-gray-500 hover:wu-text-gray-600 wu-text-sm">
							&rarr; <?php echo $sub_section['title']; ?>
						</a>
						</li>

					<?php endforeach; ?>

					</ul>
					<!-- End Sub-menu -->

				<?php endif; ?>

				</li>
				<!-- End Menu Item -->

			<?php endif; ?>

<?php endforeach; ?>

		</ul>
		<!-- End Navigator -->

		<div class="wu-mt-10 wu-p-4">

			<div>

			<span class="wu-bg-orange-600 wu-text-gray-100 wu-text-xs wu-inline-block wu-rounded wu-py-1 wu-px-2 wu-font-bold wu-uppercase wu-opacity-50">
				<?php esc_html_e('Beta', 'wp-ultimo'); ?>
			</span>

			<span class="wu-block wu-mt-2 wu-text-xs wu-text-gray-600"><?php esc_html_e('Ready for testing, but not necessarily production-ready.', 'wp-ultimo'); ?></span>


			</div>

			<div class="wu-mt-4">

			<span class="wu-bg-gray-800 wu-text-gray-200 wu-text-xs wu-inline-block wu-rounded wu-py-1 wu-px-2 wu-font-bold wu-uppercase wu-opacity-50">
				<?php esc_html_e('Coming Soon', 'wp-ultimo'); ?>
			</span>

			<span class="wu-block wu-mt-2 wu-text-xs wu-text-gray-600"><?php _e('In active development, but not yet available.', 'wp-ultimo'); ?></span>

			</div>

			<div class="wu-mt-4">

			<span class="wu-bg-purple-800 wu-text-gray-200 wu-text-xs wu-inline-block wu-rounded wu-py-1 wu-px-2 wu-font-bold wu-uppercase wu-opacity-50">
				<?php esc_html_e('Legacy', 'wp-ultimo'); ?>
			</span>

			<span class="wu-block wu-mt-2 wu-text-xs wu-text-gray-600"><?php esc_html_e('Developed for 1.X, but compatible with 2.X.', 'wp-ultimo'); ?></span>

			</div>

		</div>


		</div>

		<div class="wu-w-full md:wu-w-8/12 lg:wu-w-10/12 md:wu-pl-4 metabox-holder">
			<?php if ( $user ) : ?>
			<div>
				<?php echo $user['display_name'] . '(' . $user['user_email'] . ')'; ?>
				<a href="<?php echo esc_attr($logout_url); ?>">Logout</a>
			</div>
			<?php else : ?>
			<a class="wu-btn wp-btn-primary" href="<?php echo esc_attr($oauth_url); ?>">Login</a>
			<?php endif; ?>

		<div id="wu-addon" class="wu-relative">
<!-- 
			<div class="wp-filter" v-cloak>

			<ul class="filter-links">

				<li :class="category == 'all' ? '' : 'selector-inactive'">
					<a 
						v-cloak 
						href="#"
						:class="category == 'all' ? 'current wu-font-medium' : ''" 
						@click.prevent="category = 'all'"
					>{{ i18n.all }}</a>
				</li>

				<li 
					v-for="_category in categories"
					:class="category == _category ? '' : 'selector-inactive'" 
				>
					<a 
						v-cloak 
						href="#"
						:class="category == _category.toLowerCase() ? 'current wu-font-medium' : ''" 
						@click.prevent="category = _category.toLowerCase()"
					>{{ _category }}</a>
				</li>

			</ul>

			</div> -->

			<div class="theme-browser rendered">

				<div v-if="loading"
				class="">

					<?php
					echo wu_render_empty_state( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'message'     => __('Loading...', 'wp-ultimo'),
							'sub_message' => __('We are fetching the list of WP Ultimo add-ons.', 'wp-ultimo'),
							'link_url'    => false,
						)
					);
					?>

				</div>

				<div class="themes wp-clearfix wu-grid wu-gap-6 wu-grid-cols-1 sm:wu-grid-cols-2 lg:wu-grid-cols-3">

					<div 
					class="theme wu-col-span-1" 
					style="width: 100% !important; margin: 0 !important;"
					tabindex="0"
					v-cloak
					v-for="addon in addons_list"
					:data-slug="addon.slug"
					>

						<div class="theme-screenshot wu-bg-gray-100">

							<img :class="addon.available ? '' : 'wu-opacity-50'" :src="addon.image_url" :alt="addon.name" />

						</div>

						<span class="wu-absolute wu-m-6 wu-bg-gray-800 wu-text-gray-200 wu-text-xs wu-inline-block wu-rounded wu-top-0 wu-right-0 wu-py-1 wu-px-2 wu-font-bold wu-uppercase" v-cloak v-if="!addon.available">
						<?php _e('Coming Soon', 'wp-ultimo'); ?>
						</span>

						<span class="wu-absolute wu-m-6 wu-bg-purple-800 wu-text-gray-200 wu-text-xs wu-inline-block wu-rounded wu-top-0 wu-right-0 wu-py-1 wu-px-2 wu-font-bold wu-uppercase" v-cloak v-show="addon.legacy">
						<?php _e('Legacy', 'wp-ultimo'); ?>
						</span>

						<span class="wu-absolute wu-m-6 wu-bg-orange-600 wu-text-gray-100 wu-text-xs wu-inline-block wu-rounded wu-top-0 wu-right-0 wu-py-1 wu-px-2 wu-font-bold wu-uppercase" v-cloak v-show="addon.beta">
						<?php _e('Beta', 'wp-ultimo'); ?>
						</span>

						<a 
						class="more-details wubox wu-no-underline" 
						:title="addon.name"
						:href="'<?php echo esc_attr($more_info_url); ?>'.replace('ADDON_SLUG', addon.slug)"
						>

						<?php _e('Add-on Details', 'wp-ultimo'); ?>

						</a>

						<div class="theme-author">

							<?php _e('By WP Ultimo', 'wp-ultimo'); ?>

						</div>

						<h2 class="theme-name" :id="addon.slug" :class="addon.available ? '' : 'wu-opacity-50'" >
						{{ addon.name }}

						<div class="wu-pt-1 wu-block">
							<span 
							v-cloak
							class="wu-text-gray-600 wu-font-normal wu-text-xs"
							v-if="addon.free"
							>
							<?php _e('Free Add-on', 'wp-ultimo'); ?>
							</span>
							<span 
							v-cloak
							class="wu-text-gray-600 wu-font-normal wu-text-xs"
							v-else
							>
							<?php _e('Premium Add-on', 'wp-ultimo'); ?>
							</span>

							<span 
							v-cloak
							class="wu-ml-2 wu-text-green-600 wu-font-normal wu-text-xs"
							v-if="addon.installed"
							>
							<span class="dashicons-wu-check"></span>
							<?php _e('Installed', 'wp-ultimo'); ?>
							</span>

						</div>
						</h2>

					</div>

				</div>

			</div>

			<div class="theme-overlay"></div>

			<div 
			v-cloak
			v-if="! loading && addons_list.length == 0"
			>
			<?php
			echo wu_render_empty_state( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'message'      => __('No add-ons found...', 'wp-ultimo'),
					'sub_message'  => __('Check the search terms or navigate between categories to see what add-ons we have available.', 'wp-ultimo'),
					'link_label'   => __('See all add-ons', 'wp-ultimo'),
					'link_url'     => remove_query_arg('tab'),
					'link_classes' => '',
					'link_icon'    => 'dashicons-wu-reply',
				)
			);
			?>
			</div>

		</div>

		</div>

	</div>

	<?php
	/**
	 * Allow plugin developers to add scripts to the bottom of the page
	 *
	 * @since 1.8.2
	 * @param WU_Page WP Ultimo Page instance
	 */
	do_action('wu_page_addon_footer', $page);
	?>

	</form>

</div>
