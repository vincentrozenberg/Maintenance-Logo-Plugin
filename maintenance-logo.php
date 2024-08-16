<?php
/**
 * Plugin Name: Maintenance Logo Plugin
 * Description: A simple maintenance plugin that shows only a logo on the front end.
 * Version: 1.0
 * Author: Vincent Rozenberg
 * Author URI: https://vincentrozenberg.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register the plugin activation hook to save the default logo URL.
function mlp_activate_plugin() {
    if (!get_option('mlp_logo_url')) {
        update_option('mlp_logo_url', '');
    }
}
register_activation_hook(__FILE__, 'mlp_activate_plugin');

// Display the maintenance logo
function mlp_display_logo() {
    if (!current_user_can('edit_posts')) {
        $logo_url = get_option('mlp_logo_url');
        
        // Output the HTML and include the CSS inline
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<title>Under Maintenance</title>';
        echo '<style>
            .mlp-logo-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #fff;
                overflow: hidden;
                padding: 10px;
                box-sizing: border-box;
            }
            .mlp-logo-container img {
                max-width: 50%;
                max-height: 100%;
                width: auto;
                height: auto;
                object-fit: contain;
            }
            </style>';
        echo '</head>';
        echo '<body>';
        echo '<div class="mlp-logo-container">';
        if ($logo_url) {
            echo '<img src="' . esc_url($logo_url) . '" alt="Maintenance Logo">';
        } else {
            echo '<p>No logo set.</p>';
        }
        echo '</div>';
        echo '</body>';
        echo '</html>';

        exit();
    }
}
add_action('template_redirect', 'mlp_display_logo');

// Add a settings page to the admin menu
function mlp_add_admin_menu() {
    add_options_page('Maintenance Logo Settings', 'Maintenance Logo', 'manage_options', 'mlp_settings', 'mlp_settings_page');
}
add_action('admin_menu', 'mlp_add_admin_menu');

// Register the settings
function mlp_register_settings() {
    register_setting('mlp_settings_group', 'mlp_logo_url');
}
add_action('admin_init', 'mlp_register_settings');

// Create the settings page content
function mlp_settings_page() {
    ?>
    <div class="wrap">
        <h1>Maintenance Logo Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('mlp_settings_group'); ?>
            <?php do_settings_sections('mlp_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Logo URL</th>
                    <td><input type="text" name="mlp_logo_url" value="<?php echo esc_attr(get_option('mlp_logo_url')); ?>" size="50"/></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
?>
