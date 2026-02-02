<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Handler: Save Settings
 */
function mcm_ajax_save_settings()
{
    check_ajax_referer('mcm_admin_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permessi insufficienti');
    }

    global $wpdb;
    $table_settings = $wpdb->prefix . 'mesa_menu_settings';

    $settings = array(
        'background_color' => sanitize_hex_color($_POST['background_color']),
        'circle_color' => sanitize_hex_color($_POST['circle_color']),
        'text_color' => sanitize_hex_color($_POST['text_color']),
        'hover_scale' => floatval($_POST['hover_scale']),
        'animation_duration' => floatval($_POST['animation_duration']),
        'circle_size' => intval($_POST['circle_size']),
        'circle_distance' => intval($_POST['circle_distance']),
        'central_circle_size' => intval($_POST['central_circle_size']),
        'circle_size_mobile' => intval($_POST['circle_size_mobile']),
        'circle_distance_mobile' => intval($_POST['circle_distance_mobile']),
        'central_circle_size_mobile' => intval($_POST['central_circle_size_mobile'])
    );

    foreach ($settings as $key => $value) {
        $wpdb->replace($table_settings, array(
            'setting_key' => $key,
            'setting_value' => $value
        ));
    }

    wp_send_json_success('Impostazioni salvate con successo!');
}
add_action('wp_ajax_mcm_save_settings', 'mcm_ajax_save_settings');

/**
 * AJAX Handler: Save Menu Item
 */
function mcm_ajax_save_item()
{
    check_ajax_referer('mcm_admin_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permessi insufficienti');
    }

    global $wpdb;
    $table_items = $wpdb->prefix . 'mesa_menu_items';

    $id = intval($_POST['id']);
    $is_central = isset($_POST['is_central']) ? intval($_POST['is_central']) : 0;

    $data = array(
        'title' => sanitize_text_field($_POST['title']),
        'url' => esc_url_raw($_POST['url']),
        'icon_path' => sanitize_text_field($_POST['icon_path']), // Changed to text_field for URLs
        'active' => isset($_POST['active']) ? intval($_POST['active']) : 1,
        'is_central' => $is_central
    );

    if ($id > 0) {
        $wpdb->update($table_items, $data, array('id' => $id));
        wp_send_json_success('Elemento aggiornato!');
    } else {
        $data['position'] = $wpdb->get_var("SELECT MAX(position) FROM $table_items") + 1;
        $wpdb->insert($table_items, $data);
        wp_send_json_success(array('message' => 'Elemento creato!', 'id' => $wpdb->insert_id));
    }
}
add_action('wp_ajax_mcm_save_item', 'mcm_ajax_save_item');

/**
 * AJAX Handler: Delete Menu Item
 */
function mcm_ajax_delete_item()
{
    check_ajax_referer('mcm_admin_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permessi insufficienti');
    }

    global $wpdb;
    $table_items = $wpdb->prefix . 'mesa_menu_items';

    $id = intval($_POST['id']);
    $wpdb->delete($table_items, array('id' => $id));

    wp_send_json_success('Elemento eliminato!');
}
add_action('wp_ajax_mcm_delete_item', 'mcm_ajax_delete_item');

/**
 * AJAX Handler: Update Item Positions
 */
function mcm_ajax_update_positions()
{
    check_ajax_referer('mcm_admin_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permessi insufficienti');
    }

    global $wpdb;
    $table_items = $wpdb->prefix . 'mesa_menu_items';

    $positions = $_POST['positions'];

    foreach ($positions as $position => $id) {
        $wpdb->update(
            $table_items,
            array('position' => $position + 1),
            array('id' => intval($id))
        );
    }

    wp_send_json_success('Ordine aggiornato!');
}
add_action('wp_ajax_mcm_update_positions', 'mcm_ajax_update_positions');

/**
 * AJAX Handler: Toggle Item Active Status
 */
function mcm_ajax_toggle_active()
{
    check_ajax_referer('mcm_admin_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permessi insufficienti');
    }

    global $wpdb;
    $table_items = $wpdb->prefix . 'mesa_menu_items';

    $id = intval($_POST['id']);
    $active = intval($_POST['active']);

    $wpdb->update($table_items, array('active' => $active), array('id' => $id));

    wp_send_json_success('Stato aggiornato!');
}
add_action('wp_ajax_mcm_toggle_active', 'mcm_ajax_toggle_active');
