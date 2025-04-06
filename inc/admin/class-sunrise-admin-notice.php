<?php
/**
 * Handles the SUNRISE constant check and notifications.
 *
 * @package WP_Ultimo
 * @subpackage Admin
 * @since 2.0.0
 */

namespace WP_Ultimo\Admin;

use WP_Ultimo\Helpers\WP_Config;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Handles the SUNRISE constant check and notifications.
 *
 * @since 2.0.0
 */
class Sunrise_Admin_Notice {

    /**
     * Singleton trait.
     */
    use \WP_Ultimo\Traits\Singleton;

    /**
     * Initializes the class.
     *
     * @since 2.0.0
     * @return void
     */
    public function init() {

        // Only show this in the network admin
        if (!is_network_admin()) {
            return;
        }

        // Add the admin notice
        add_action('network_admin_notices', array($this, 'check_sunrise_constant'));

        // Handle the AJAX request to update wp-config.php
        add_action('wp_ajax_wu_update_sunrise_constant', array($this, 'handle_update_sunrise_constant'));
    }

    /**
     * Checks if the SUNRISE constant is defined and shows a notice if not.
     *
     * @since 2.0.0
     * @return void
     */
    public function check_sunrise_constant() {

        // Check if SUNRISE is defined and true
        if (defined('SUNRISE') && SUNRISE === true) {
            return;
        }

        // Check if we can write to wp-config.php
        $wp_config_path = WP_Config::get_instance()->get_wp_config_path();
        $can_write = is_writable($wp_config_path);

        ?>
        <div class="notice notice-error is-dismissible">
            <h3 style="margin-top: 0.5em;"><?php _e('Domain Mapping Not Active', 'wp-ultimo'); ?></h3>
            <p>
                <?php _e('WP Multisite WaaS domain mapping requires the <code>SUNRISE</code> constant to be defined and set to <code>true</code> in your wp-config.php file.', 'wp-ultimo'); ?>
                <?php _e('Without this setting, domain mapping will not work correctly.', 'wp-ultimo'); ?>
            </p>

            <?php if ($can_write) : ?>
                <p>
                    <button id="wu-update-sunrise-constant" class="button button-primary">
                        <?php _e('Add SUNRISE Constant to wp-config.php', 'wp-ultimo'); ?>
                    </button>
                    <span id="wu-sunrise-spinner" class="spinner" style="float: none; margin-top: 0;"></span>
                    <span id="wu-sunrise-message" style="display: none; margin-left: 10px;"></span>
                </p>
                <script>
                    jQuery(document).ready(function($) {
                        $('#wu-update-sunrise-constant').on('click', function(e) {
                            e.preventDefault();
                            
                            var $button = $(this);
                            var $spinner = $('#wu-sunrise-spinner');
                            var $message = $('#wu-sunrise-message');
                            
                            $button.prop('disabled', true);
                            $spinner.addClass('is-active');
                            
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'wu_update_sunrise_constant',
                                    nonce: '<?php echo wp_create_nonce('wu_update_sunrise_constant'); ?>'
                                },
                                success: function(response) {
                                    $spinner.removeClass('is-active');
                                    
                                    if (response.success) {
                                        $message.html(response.data.message).css('color', 'green').show();
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1500);
                                    } else {
                                        $button.prop('disabled', false);
                                        $message.html(response.data.message).css('color', 'red').show();
                                    }
                                },
                                error: function() {
                                    $button.prop('disabled', false);
                                    $spinner.removeClass('is-active');
                                    $message.html('<?php _e('An unknown error occurred.', 'wp-ultimo'); ?>').css('color', 'red').show();
                                }
                            });
                        });
                    });
                </script>
            <?php else : ?>
                <p>
                    <?php _e('We cannot automatically update your wp-config.php file as it is not writable.', 'wp-ultimo'); ?>
                    <?php _e('Please add the following line to your wp-config.php file:', 'wp-ultimo'); ?>
                </p>
                <p>
                    <code style="display: block; padding: 10px; background: #f0f0f1; margin-bottom: 10px;">define('SUNRISE', true);</code>
                </p>
                <p>
                    <?php printf(
                        __('This line should be added right after the line with <code>%s</code>.', 'wp-ultimo'),
                        '$table_prefix'
                    ); ?>
                    <?php _e('After adding this line, please refresh this page.', 'wp-ultimo'); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Handles the AJAX request to update wp-config.php.
     *
     * @since 2.0.0
     * @return void
     */
    public function handle_update_sunrise_constant() {

        // Check nonce
        if (!check_ajax_referer('wu_update_sunrise_constant', 'nonce', false)) {
            wp_send_json_error(array(
                'message' => __('Security check failed.', 'wp-ultimo')
            ));
            return;
        }

        // Check if SUNRISE is already defined
        if (defined('SUNRISE') && SUNRISE === true) {
            wp_send_json_success(array(
                'message' => __('SUNRISE constant is already defined.', 'wp-ultimo')
            ));
            return;
        }

        // Try to update wp-config.php
        $result = WP_Config::get_instance()->inject_wp_config_constant('SUNRISE', true);

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message()
            ));
            return;
        }

        if ($result === false) {
            wp_send_json_error(array(
                'message' => __('Failed to update wp-config.php. Try adding the constant manually.', 'wp-ultimo')
            ));
            return;
        }

        wp_send_json_success(array(
            'message' => __('SUNRISE constant added successfully! Reloading...', 'wp-ultimo')
        ));
    }

} 