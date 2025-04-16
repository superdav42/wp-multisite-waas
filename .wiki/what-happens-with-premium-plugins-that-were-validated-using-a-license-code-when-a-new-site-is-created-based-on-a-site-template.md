# What happens with premium plugins that were validated using a License Code when a new site is created based on a Site Template?

Some of the sub-sites your clients will buy may use premium plugins that require an activation code. So, how does WP Ultimo work in those cases?

**The short answer is:_it depends_**.

Each plugin has its own way of validating license codes and, unfortunately, there is not much we can do on our side.

If the plugin only uses the license code for activation and then saves the activation status (which most do), the activation status will be copied over to the newly created site.

Some plugins allow for activation via _wp-config.php_. If this is the case, the activation will be present on all sub-sites automatically.

Some plugins (a minority) will send additional info for the validation servers and will periodically re-validate (a good example is Elementor). In cases like this, the validation will be carried over, but as soon as the re-validation process begins, once it's refreshed, the license will no longer be valid.
