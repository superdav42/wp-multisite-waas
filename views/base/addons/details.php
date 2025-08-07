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
		background-image: url("<?php echo $addon->image_url; ?>");
	}

	@media only screen and (-webkit-min-device-pixel-ratio: 1.5) {
		#plugin-information-title.with-banner {
		background-position: center;
		background-image: url("<?php echo $addon->image_url; ?>");
		}
	}
</style>

<div id="plugin-information">

	<div id="plugin-information-scrollable">

		<div id="plugin-information-title" class="with-banner">
			<div class="vignette"></div>
			<h2><?php echo $addon->name; ?></h2>
		</div>

		<div id="plugin-information-tabs" class="with-banner">

			<a name="description" href="#" class="current">

				<?php _e('Description', 'wp-ultimo'); ?>

			</a>
			<!-- 

			<a name="faq" href="#">

				<?php _e('FAQ', 'wp-ultimo'); ?>

			</a>

			<a name="changelog" href="#">

				<?php _e('Changelog', 'wp-ultimo'); ?>

			</a>

			<a name="screenshots" href="#">

				<?php _e('Screenshots', 'wp-ultimo'); ?>

			</a>

			<a name="reviews" href="#">

				<?php _e('Reviews', 'wp-ultimo'); ?>

			</a>

			-->

		</div>

		<div id="plugin-information-content" class="with-banner">

			<div class="fyi">

				<ul>
					<li>
						<strong><?php _e('Author:', 'wp-ultimo'); ?></strong> 
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
							<strong><?php _e('Requires WP Ultimo Version:', 'wp-ultimo'); ?></strong>
						<?php printf(__('%s or higher', 'wp-ultimo'), $addon->requires_version); ?>
						</li>

					<?php endif; ?>

					<li>
						<a class="wu-no-underline" target="_blank" href="https://multisiteultimate.com/product/<?php echo $addon_slug; ?>">
						<?php _e('See on the Official Site »', 'wp-ultimo'); ?>
						</a>
					</li>

				</ul>
<!-- 
				<h3>Average Rating</h3>
				<div class="star-rating">
					<span class="screen-reader-text">5.0 rating based on 890 ratings</span>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
				</div>
				<p aria-hidden="true" class="fyi-description">
					(based on 890 ratings)
				</p>
				<h3>Reviews</h3>
				<p class="fyi-description">Read all reviews on WordPress.org or write your own!</p>
				<div class="counter-container">
					<span class="counter-label"> <a href="https://wordpress.org/support/plugin/classic-editor/reviews/?filter=5" target="_blank" aria-label="Reviews with 5 stars: 859. Opens in a new tab.">5 stars</a> </span>
					<span class="counter-back">
						<span class="counter-bar" style="width: 88.795505617978px;"></span>
					</span>
					<span class="counter-count" aria-hidden="true">859</span>
				</div>
				<div class="counter-container">
					<span class="counter-label"> <a href="https://wordpress.org/support/plugin/classic-editor/reviews/?filter=4" target="_blank" aria-label="Reviews with 4 stars: 15. Opens in a new tab.">4 stars</a> </span>
					<span class="counter-back">
						<span class="counter-bar" style="width: 1.5505617977528px;"></span>
					</span>
					<span class="counter-count" aria-hidden="true">15</span>
				</div>
				<div class="counter-container">
					<span class="counter-label"> <a href="https://wordpress.org/support/plugin/classic-editor/reviews/?filter=3" target="_blank" aria-label="Reviews with 3 stars: 6. Opens in a new tab.">3 stars</a> </span>
					<span class="counter-back">
						<span class="counter-bar" style="width: 0.62022471910112px;"></span>
					</span>
					<span class="counter-count" aria-hidden="true">6</span>
				</div>
				<div class="counter-container">
					<span class="counter-label"> <a href="https://wordpress.org/support/plugin/classic-editor/reviews/?filter=2" target="_blank" aria-label="Reviews with 2 stars: 1. Opens in a new tab.">2 stars</a> </span>
					<span class="counter-back">
						<span class="counter-bar" style="width: 0.10337078651685px;"></span>
					</span>
					<span class="counter-count" aria-hidden="true">1</span>
				</div>
				<div class="counter-container">
					<span class="counter-label"> <a href="https://wordpress.org/support/plugin/classic-editor/reviews/?filter=1" target="_blank" aria-label="Reviews with 1 star: 9. Opens in a new tab.">1 star</a> </span>
					<span class="counter-back">
						<span class="counter-bar" style="width: 0.93033707865169px;"></span>
					</span>
					<span class="counter-count" aria-hidden="true">9</span>
				</div>
				<h3>Contributors</h3>
				<ul class="contributors">
					<li>
						<a href="https://profiles.wordpress.org/wordpressdotorg" target="_blank">
							<img src="https://secure.gravatar.com/avatar/61ee2579b8905e62b4b4045bdc92c11a?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />WordPress.org
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/azaozz" target="_blank">
							<img src="https://secure.gravatar.com/avatar/4e84843ebff0918d72ade21c6ee7b1e4?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Andrew Ozz
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/melchoyce" target="_blank">
							<img src="https://secure.gravatar.com/avatar/9ffb8027a6f8cb090148a2ea8310b64f?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Mel Choyce-Dwan
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/chanthaboune" target="_blank">
							<img src="https://secure.gravatar.com/avatar/da526066c9f187ca1e16263158d2e9a3?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Josepha
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/alexislloyd" target="_blank">
							<img src="https://secure.gravatar.com/avatar/41261ee7861fe1331bf1cd32bb24f4ea?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Alexis Lloyd
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/pento" target="_blank">
							<img src="https://secure.gravatar.com/avatar/1ad9e5c98d81c6815a65dab5b6e1f669?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Gary Pendergast
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/youknowriad" target="_blank">
							<img src="https://secure.gravatar.com/avatar/9929daa7594d5afa910a777ccb9e88e4?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Riad Benguella
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/desrosj" target="_blank">
							<img src="https://secure.gravatar.com/avatar/f22c0ec09eb5a6df4da4239a37dbdf9d?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />Jonathan Desrosiers
						</a>
					</li>
					<li>
						<a href="https://profiles.wordpress.org/luciano-croce" target="_blank">
							<img src="https://secure.gravatar.com/avatar/e598c948e82a783e0f40634c0766c965?s=36&amp;d=monsterid&amp;r=g" width="18" height="18" alt="" />luciano-croce
						</a>
					</li>
				</ul> -->
			</div>
			<div id="section-holder">

				<!-- Description Section -->
				<div id="section-description" class="section" style="display: block; min-height: 200px;">

					<?php echo $addon->description; ?>

				</div>
				<!-- /Description Section -->

				<!-- <div id="section-faq" class="section" style="display: none;">
					<h4>
						Default settings
					</h4>
					<p></p>
					<p>
						When activated this plugin will restore the previous (“classic”) WordPress editor and hide the new block editor (“Gutenberg”).<br />
						These settings can be changed at the Settings =&gt; Writing screen.
					</p>
					<p></p>
					<h4>
						Default settings for network installation
					</h4>
					<p></p>
					<p>There are two options:</p>
					<ul>
						<li>
							When network-activated this plugin will set the classic editor as default and prevent site administrators and users from changing editors.<br />
							The settings can be changed and default network-wide editor can be selected on the Network Settings screen.
						</li>
						<li>When not network-activated each site administrator will be able to activate the plugin and choose options for their users.</li>
					</ul>
					<p></p>
					<h4>
						Cannot find the “Switch to classic editor” link
					</h4>
					<p></p>
					<p>It is in the main block editor menu, see this <a href="https://ps.w.org/classic-editor/assets/screenshot-7.png?rev=2023480" target="_blank">screenshot</a>.</p>
					<p></p>
				</div> -->

				<!-- <div id="section-changelog" class="section" style="display: none;">
					<h4>1.6</h4>
					<ul>
						<li>Updated for WordPress 5.5.</li>
						<li>Fixed minor issues with calling deprecated functions, needlessly registering uninstall hook, and capitalization of some strings.</li>
					</ul>
					<h4>1.5</h4>
					<ul>
						<li>Updated for WordPress 5.2 and Gutenberg 5.3.</li>
						<li>Enhanced and fixed the “open posts in the last editor used to edit them” logic.</li>
						<li>Fixed adding post state so it can easily be accessed from other plugins.</li>
					</ul>
					<h4>1.4</h4>
					<ul>
						<li>On network installations removed the restriction for only network activation.</li>
						<li>Added support for network administrators to choose the default network-wide editor.</li>
						<li>Fixed the settings link in the warning on network About screen.</li>
						<li>Properly added the “Switch to classic editor” menu item to the block editor menu.</li>
					</ul>
					<h4>1.3</h4>
					<ul>
						<li>Fixed removal of the “Try Gutenberg” dashboard widget.</li>
						<li>Fixed condition for displaying of the after upgrade notice on the “What’s New” screen. Shown when the classic editor is selected and users cannot switch editors.</li>
					</ul>
					<h4>1.2</h4>
					<ul>
						<li>Fixed switching editors from the Add New (post) screen before a draft post is saved.</li>
						<li>Fixed typo that was appending the edit URL to the <code>classic-editor</code> query var.</li>
						<li>Changed detecting of WordPress 5.0 to not use version check. Fixes a bug when testing 5.1-alpha.</li>
						<li>Changed the default value of the option to allow users to switch editors to false.</li>
						<li>Added disabling of the Gutenberg plugin and lowered the required WordPress version to 4.9.</li>
						<li>Added <code>classic_editor_network_default_settings</code> filter.</li>
					</ul>
					<h4>1.1</h4>
					<p>Fixed a bug where it may attempt to load the block editor for post types that do not support editor when users are allowed to switch editors.</p>
					<h4>1.0</h4>
					<ul>
						<li>Updated for WordPress 5.0.</li>
						<li>Changed all “Gutenberg” names/references to “block editor”.</li>
						<li>Refreshed the settings UI.</li>
						<li>
							Removed disabling of the Gutenberg plugin. This was added for testing in WordPress 4.9. Users who want to continue following the development of Gutenberg in WordPress 5.0 and beyond will not need another plugin
							to disable it.
						</li>
						<li>Added support for per-user settings of default editor.</li>
						<li>Added support for admins to set the default editor for the site.</li>
						<li>Added support for admins to allow users to change their default editor.</li>
						<li>Added support for network admins to prevent site admins from changing the default settings.</li>
						<li>Added support to store the last editor used for each post and open it next time. Enabled when users can choose default editor.</li>
						<li>Added “post editor state” in the listing of posts on the Posts screen. Shows the editor that will be opened for the post. Enabled when users can choose default editor.</li>
						<li>
							Added <code>classic_editor_enabled_editors_for_post</code> and <code>classic_editor_enabled_editors_for_post_type</code> filters. Can be used by other plugins to control or override the editor used for a
							particular post of post type.
						</li>
						<li>Added <code>classic_editor_plugin_settings</code> filter. Can be used by other plugins to override the settings and disable the settings UI.</li>
					</ul>
					<h4>0.5</h4>
					<ul>
						<li>Updated for Gutenberg 4.1 and WordPress 5.0-beta1.</li>
						<li>Removed some functionality that now exists in Gutenberg.</li>
						<li>Fixed redirecting back to the classic editor after looking at post revisions.</li>
					</ul>
					<h4>0.4</h4>
					<ul>
						<li>Fixed removing of the “Try Gutenberg” call-out when the Gutenberg plugin is not activated.</li>
						<li>Fixed to always show the settings and the settings link in the plugins list table.</li>
						<li>Updated the readme text.</li>
					</ul>
					<h4>0.3</h4>
					<ul>
						<li>Updated the option from a checkbox to couple of radio buttons, seems clearer. Thanks to @designsimply for the label text suggestions.</li>
						<li>Some general updates and cleanup.</li>
					</ul>
					<h4>0.2</h4>
					<ul>
						<li>Update for Gutenberg 1.9.</li>
						<li>Remove warning and automatic deactivation when Gutenberg is not active.</li>
					</ul>
					<h4>0.1</h4>
					<p>Initial release.</p>
				</div> -->

				<!-- <div id="section-screenshots" class="section" style="display: none;">
					<ol>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-1.png?rev=1998671" target="_blank">
								<img src="https://ps.w.org/classic-editor/assets/screenshot-1.png?rev=1998671" alt="Admin settings on the Settings -> Writing screen." />
							</a>

							<p>Admin settings on the Settings -&gt; Writing screen.</p>
						</li>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-2.png?rev=1998671" target="_blank">
								<img src="https://ps.w.org/classic-editor/assets/screenshot-2.png?rev=1998671" alt="User settings on the Profile screen. Visible when the users are allowed to switch editors." />
							</a>

							<p>User settings on the Profile screen. Visible when the users are allowed to switch editors.</p>
						</li>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-3.png?rev=1998671" target="_blank">
								<img src="https://ps.w.org/classic-editor/assets/screenshot-3.png?rev=1998671" alt='"Action links" to choose alternative editor. Visible when the users are allowed to switch editors.' />
							</a>

							<p>"Action links" to choose alternative editor. Visible when the users are allowed to switch editors.</p>
						</li>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-4.png?rev=1998671" target="_blank">
								<img
									src="https://ps.w.org/classic-editor/assets/screenshot-4.png?rev=1998671"
									alt="Link to switch to the block editor while editing a post in the classic editor. Visible when the users are allowed to switch editors."
								/>
							</a>

							<p>Link to switch to the block editor while editing a post in the classic editor. Visible when the users are allowed to switch editors.</p>
						</li>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-5.png?rev=1998671" target="_blank">
								<img
									src="https://ps.w.org/classic-editor/assets/screenshot-5.png?rev=1998671"
									alt="Link to switch to the classic editor while editing a post in the block editor. Visible when the users are allowed to switch editors."
								/>
							</a>

							<p>Link to switch to the classic editor while editing a post in the block editor. Visible when the users are allowed to switch editors.</p>
						</li>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-6.png?rev=1998671" target="_blank">
								<img src="https://ps.w.org/classic-editor/assets/screenshot-6.png?rev=1998671" alt="Network settings to select the default editor for the network and allow site admins to change it." />
							</a>

							<p>Network settings to select the default editor for the network and allow site admins to change it.</p>
						</li>
						<li>
							<a href="https://ps.w.org/classic-editor/assets/screenshot-7.png?rev=2023480" target="_blank">
								<img src="https://ps.w.org/classic-editor/assets/screenshot-7.png?rev=2023480" alt='The "Switch to classic editor" link.' />
							</a>

							<p>The "Switch to classic editor" link.</p>
						</li>
					</ol>
				</div> -->

				<!-- <div id="section-reviews" class="section" style="display: none;">
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Great!</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/sandrardillard" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/46635dba153f976268f9f9206c8de3eb?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/sandrardillard" target="_blank">sandrardillard</a> on <span class="review-date">October 4, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">Nice tool!</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Best Editor Out</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/rechtvanhuyssteen" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/61569e7b98df7fa0e961638f2fe4e493?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/rechtvanhuyssteen" target="_blank">rechtvanhuyssteen</a> on <span class="review-date">September 30, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">The best, can we get it updateted for latest WP seems to conflict with some other plugins after update. Will never use another plugin except this for editing. Well done</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Must need plugin</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/adeebc" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/68cee995a65b40da73d51818576c9007?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/adeebc" target="_blank">adeebc</a> on <span class="review-date">September 30, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">I'm using WordPress because of this editor. Classic Editor changed my life. I'm using this plugin for more than 50 websites.</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Must have</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/cleoni" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/1e5d1686cc26b244794744b07cb74ee4?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/cleoni" target="_blank">cleoni</a> on <span class="review-date">September 24, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">Must have plugin. Watch out, it seems it currently does not work under WP 5.5 due to deprecated jquery methods.</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Until at least 2022</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/kirbyfoster" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/5eda3070b8145f285e03561e7bccb249?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/kirbyfoster" target="_blank">kirbyfoster</a> on <span class="review-date">September 23, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">
							Thankfully this is still available on WP. However it states that 'Classic Editor is an official WordPress plugin, and will be fully supported and maintained until at least 2022' What's going to happen after 2022?
							Am I going to have to rewrite my websites? Maybe I ought to start looking at other options instead of WP? Gutenberg is really starting to piss me off. I wish I'd never heard of WP and instead just use html/css/js
							like I used to do.
						</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>So much better!</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/sknydrinks" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/368188434b0840cba8c31e15a8208132?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/sknydrinks" target="_blank">sknydrinks</a> on <span class="review-date">September 21, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">This is so much better. Thank you!</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Please go back to Classic Editor</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/passegua" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/4c8f34536195de842099db7de654dc64?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/passegua" target="_blank">passegua</a> on <span class="review-date">September 17, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">Please get rid of Gutenberg horrible Block Editor! Please ask WordPress user about it, no one uses block editor! Please please go back to the beautiful classic editor!</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>BROKEN</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-empty"></span><span class="star dashicons dashicons-star-empty"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/lexiol" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/06251c1360f2d474b199c130c7ce509c?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/lexiol" target="_blank">Lexiol</a> on <span class="review-date">September 13, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">Not sure how to remove my review</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>I still use the classic editor</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/janejsmaster" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/8a823e5639bc30c2ac79be5168d82285?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/janejsmaster" target="_blank">Jane (janejsmaster)</a> on <span class="review-date">September 10, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">
							I still prefer the classic editor compared to the Gutenberg one. The classic editor is what we have used for years and I hope it will always be maintained by its developers. Many of us still depend on it
							everyday.
						</div>
					</div>
					<div class="review">
						<div class="review-head">
							<div class="reviewer-info">
								<div class="review-title-section">
									<h4>Gave up</h4>
									<div class="star-rating">
										<div class="wporg-ratings">
											<span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span>
											<span class="star dashicons dashicons-star-empty"></span><span class="star dashicons dashicons-star-empty"></span>
										</div>
									</div>
								</div>
								<p>
									By
									<a href="https://profiles.wordpress.org/kielce" target="_blank">
										<img alt="" src="https://secure.gravatar.com/avatar/036809f4dfd63fcf041af812ac28a4cb?s=16&amp;d=monsterid&amp;r=g" class="avatar avatar-16 photo" />
									</a>

									<a href="https://profiles.wordpress.org/kielce" target="_blank">kielce</a> on <span class="review-date">September 8, 2020</span>
								</p>
							</div>
						</div>
						<div class="review-body">I gave up on Classic Editor and shifted to the "Disable Gutenberg" plugin months ago. "Disable Gutenberg" has no issues with the WordPress 5.5 update.</div>
					</div>
				</div> -->

			</div>

		</div>

	</div>

	<div id="plugin-information-footer">

		<?php if (! $addon->free) : ?>

		<span class="wu-text-green-800 wu-inline-block wu-py-1">

			<?php _e('This is a Premium Add-on.', 'wp-ultimo'); ?>

		</span>

		<?php endif; ?>

		<form id="plugin-install" class="wu_form">

		<?php if ($addon->installed) : ?>

			<button
			disabled="disabled"
			data-slug="<?php echo $addon_slug; ?>"
			class="button button-disabled right"
			>
			<?php _e('Already Installed', 'wp-ultimo'); ?>
			</button>

		<?php else : ?>

			<?php if ($addon->available) : ?>

				<?php if ($license->allowed('wpultimo') || $addon->free) : ?>

				<button
				type="submit"
				name="install"
				data-slug="<?php echo $addon_slug; ?>"
				class="button button-primary right"
				>
					<?php _e('Install Now', 'wp-ultimo'); ?>
				</button>

			<?php else : ?>

				<a
				href="<?php echo $upgrade_url; ?>"
				class="button button-primary right"
				>
				<?php _e('Upgrade your License', 'wp-ultimo'); ?>
				</a>

			<?php endif; ?>

			<?php endif; ?>

			<input type="hidden" name="action" value="wu_form_handler">

			<input type="hidden" name="addon" value="<?php echo $addon_slug; ?>">

			<input type="hidden" name="wu-when" value="<?php echo base64_encode('after_setup_theme'); ?>">

			<?php wp_nonce_field('wu_form_addon_more_info'); ?>

		<?php endif; ?>

	</div>

</div>
