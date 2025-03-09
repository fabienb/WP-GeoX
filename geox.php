<?php
/**
 * Plugin Name: GeoX
 * Plugin URI: https://fabienb.blog/
 * Description: Display conditional content based on visitor's location. Works with Gutenberg and Classic Editor. See README.md for a list of available shortcodes.
 * Version: 1.1.2
 * Author: Fabien Butazzi (@fabienb)
 * Author URI: https://fabienb.blog/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: geox
 */

if (!defined('ABSPATH')) {
    exit;
}

define('GEOX_VERSION', '1.1.1');
define('GEOX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GEOX_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once GEOX_PLUGIN_DIR . 'includes/class-geox-block.php';
require_once GEOX_PLUGIN_DIR . 'includes/class-geox-shortcode.php';

function geox_init() {
    $geox_block = new GeoX_Block();
    new GeoX_Shortcode($geox_block);
}
add_action('plugins_loaded', 'geox_init');

function geox_register_block_script() {
    wp_register_script(
        'geox-block',
        GEOX_PLUGIN_URL . 'js/geox-block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'),
        GEOX_VERSION,
        true
    );
    
    // Explicitly enqueue the script for the block editor
    if (function_exists('wp_set_script_translations')) {
        wp_set_script_translations('geox-block', 'geox');
    }
}
add_action('init', 'geox_register_block_script');

// Ensure the block script is enqueued in the editor
function geox_enqueue_block_editor_assets() {
    wp_enqueue_script('geox-block');
}
add_action('enqueue_block_editor_assets', 'geox_enqueue_block_editor_assets');

function geox_enqueue_scripts() {
    wp_enqueue_script('geox-frontend', GEOX_PLUGIN_URL . 'js/geox-frontend.js', array('jquery'), GEOX_VERSION, true);
    wp_localize_script('geox-frontend', 'geox_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'geox_enqueue_scripts');

function geox_ajax_check_location() {
    $include = isset($_POST['include']) ? sanitize_text_field($_POST['include']) : '';
    $exclude = isset($_POST['exclude']) ? sanitize_text_field($_POST['exclude']) : '';
    $geox_block = new GeoX_Block();
    $should_display = $geox_block->should_display($include, $exclude);
    wp_send_json_success(array('should_display' => $should_display));
}
add_action('wp_ajax_geox_check_location', 'geox_ajax_check_location');
add_action('wp_ajax_nopriv_geox_check_location', 'geox_ajax_check_location');

function geox_add_settings_page() {
    add_options_page(
        'GeoX Settings',
        'GeoX',
        'manage_options',
        'geox-settings',
        'geox_render_settings_page'
    );
}
add_action('admin_menu', 'geox_add_settings_page');

function geox_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('geox_options');
            do_settings_sections('geox-settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function geox_register_settings() {
    register_setting('geox_options', 'geox_google_maps_api_key');
    add_settings_section(
        'geox_main_section',
        'Main Settings',
        null,
        'geox-settings'
    );
    add_settings_field(
        'geox_google_maps_api_key',
        'Google Maps API Key',
        'geox_google_maps_api_key_callback',
        'geox-settings',
        'geox_main_section'
    );
}
add_action('admin_init', 'geox_register_settings');

function geox_google_maps_api_key_callback() {
    $api_key = get_option('geox_google_maps_api_key');
    echo '<input type="text" name="geox_google_maps_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
}
