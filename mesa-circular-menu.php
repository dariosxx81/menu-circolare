<?php
/**
 * Plugin Name: Mesa Circular Menu
 * Plugin URI: https://mesafood.it
 * Description: Un menu circolare animato con icone personalizzate per WordPress. Completamente configurabile tramite pannello admin.
 * Version: 1.0.0
 * Author: Mesa Team
 * Author URI: https://mesafood.it
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mesa-circular-menu
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MCM_VERSION', '1.0.0');
define('MCM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MCM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MCM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Plugin Activation Hook
 */
function mcm_activate()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Create menu items table
    $table_items = $wpdb->prefix . 'mesa_menu_items';
    $sql_items = "CREATE TABLE IF NOT EXISTS $table_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        url VARCHAR(512) NOT NULL,
        icon_path VARCHAR(512),
        position INT DEFAULT 0,
        is_central TINYINT(1) DEFAULT 0,
        active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) $charset_collate;";

    // Create settings table
    $table_settings = $wpdb->prefix . 'mesa_menu_settings';
    $sql_settings = "CREATE TABLE IF NOT EXISTS $table_settings (
        setting_key VARCHAR(100) PRIMARY KEY,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_items);
    dbDelta($sql_settings);

    // Insert default menu items (5 external + 1 central)
    $default_items = array(
        // Central element
        array('title' => 'Me.Sa.Group', 'url' => '#coordinamento', 'icon_path' => 'mesa-logo.svg', 'position' => 0, 'is_central' => 1),
        // External elements (5)
        array('title' => 'Consulenza Tecnica', 'url' => '#consulenza', 'icon_path' => 'consulenza.png', 'position' => 1, 'is_central' => 0),
        array('title' => 'Architettura e Arredamento', 'url' => '#architettura', 'icon_path' => 'architettura.png', 'position' => 2, 'is_central' => 0),
        array('title' => 'Edilizia e Ristrutturazione', 'url' => '#edilizia', 'icon_path' => 'edilizia.png', 'position' => 3, 'is_central' => 0),
        array('title' => 'Impiantistica ed Efficienza Energetica', 'url' => '#impiantistica', 'icon_path' => 'impiantistica.png', 'position' => 4, 'is_central' => 0),
        array('title' => 'Coibentazione e Infissi', 'url' => '#coibentazione', 'icon_path' => 'coibentazione.png', 'position' => 5, 'is_central' => 0)
    );

    foreach ($default_items as $item) {
        $wpdb->insert($table_items, $item);
    }

    // Insert default settings
    $default_settings = array(
        'background_color' => '#1B4F5C',
        'circle_color' => '#FFFFFF',
        'text_color' => '#333333',
        'hover_scale' => '1.15',
        'animation_duration' => '0.3',
        'circle_size' => '200',
        'circle_distance' => '300',
        'central_circle_size' => '250',
        // Mobile-specific settings
        'circle_size_mobile' => '140',
        'circle_distance_mobile' => '180',
        'central_circle_size_mobile' => '120'
    );

    foreach ($default_settings as $key => $value) {
        $wpdb->insert($table_settings, array(
            'setting_key' => $key,
            'setting_value' => $value
        ));
    }
}
register_activation_hook(__FILE__, 'mcm_activate');

/**
 * Plugin Deactivation Hook
 */
function mcm_deactivate()
{
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'mcm_deactivate');

/**
 * Enqueue frontend styles and scripts
 */
function mcm_enqueue_frontend_assets()
{
    wp_enqueue_style('mcm-menu-style', MCM_PLUGIN_URL . 'assets/css/menu-style.css', array(), MCM_VERSION);
    wp_enqueue_script('mcm-menu-animations', MCM_PLUGIN_URL . 'assets/js/menu-animations.js', array('jquery'), MCM_VERSION, true);
}
add_action('wp_enqueue_scripts', 'mcm_enqueue_frontend_assets');

/**
 * Enqueue admin styles and scripts
 */
function mcm_enqueue_admin_assets($hook)
{
    if ($hook !== 'toplevel_page_mesa-circular-menu') {
        return;
    }

    wp_enqueue_style('mcm-admin-style', MCM_PLUGIN_URL . 'admin/admin-style.css', array(), MCM_VERSION);
    wp_enqueue_script('mcm-admin-script', MCM_PLUGIN_URL . 'admin/admin-script.js', array('jquery', 'jquery-ui-sortable'), MCM_VERSION, true);
    wp_enqueue_media();

    wp_localize_script('mcm-admin-script', 'mcmAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mcm_admin_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'mcm_enqueue_admin_assets');

/**
 * Add admin menu
 */
function mcm_add_admin_menu()
{
    add_menu_page(
        'Mesa Circular Menu',
        'Menu Circolare',
        'manage_options',
        'mesa-circular-menu',
        'mcm_admin_page',
        'dashicons-screenoptions',
        30
    );
}
add_action('admin_menu', 'mcm_add_admin_menu');

/**
 * Admin page content
 */
function mcm_admin_page()
{
    require_once MCM_PLUGIN_DIR . 'admin/admin-panel.php';
}

/**
 * Shortcode registration
 */
function mcm_render_menu_shortcode($atts)
{
    require_once MCM_PLUGIN_DIR . 'public/class-menu-renderer.php';
    $renderer = new MCM_Menu_Renderer();
    return $renderer->render();
}
add_shortcode('mesa_circular_menu', 'mcm_render_menu_shortcode');

/**
 * AJAX handlers
 */
require_once MCM_PLUGIN_DIR . 'admin/ajax-handlers.php';
