<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class MCM_Menu_Renderer
 * Renders the circular menu on the frontend
 */
class MCM_Menu_Renderer
{

    public function render()
    {
        global $wpdb;

        // Get settings
        $table_settings = $wpdb->prefix . 'mesa_menu_settings';
        $settings_query = $wpdb->get_results("SELECT * FROM $table_settings", OBJECT_K);
        $settings = array();
        foreach ($settings_query as $key => $setting) {
            $settings[$key] = $setting->setting_value;
        }

        // Get active menu items
        $table_items = $wpdb->prefix . 'mesa_menu_items';

        // Get central item
        $central_item = $wpdb->get_row("SELECT * FROM $table_items WHERE active = 1 AND is_central = 1 LIMIT 1");

        // Get external items (max 5)
        $external_items = $wpdb->get_results("SELECT * FROM $table_items WHERE active = 1 AND is_central = 0 ORDER BY position ASC LIMIT 5");

        // Apply custom styles
        $background_color = $settings['background_color'] ?? '#1B4F5C';
        $circle_color = $settings['circle_color'] ?? '#FFFFFF';
        $hover_scale = $settings['hover_scale'] ?? '1.15';
        $animation_duration = $settings['animation_duration'] ?? '0.3';
        $circle_size = $settings['circle_size'] ?? '200';
        $circle_distance = $settings['circle_distance'] ?? '300';
        $central_circle_size = $settings['central_circle_size'] ?? '250';
        // Mobile settings
        $circle_size_mobile = $settings['circle_size_mobile'] ?? '140';
        $circle_distance_mobile = $settings['circle_distance_mobile'] ?? '180';
        $central_circle_size_mobile = $settings['central_circle_size_mobile'] ?? '120';

        $inline_styles = "
        <style>
            .mcm-menu-container {
                --bg-color: {$background_color};
                --circle-color: {$circle_color};
                --hover-scale: {$hover_scale};
                --animation-duration: {$animation_duration}s;
                --circle-size: {$circle_size}px;
                --circle-distance: {$circle_distance}px;
                --central-size: {$central_circle_size}px;
                --circle-size-mobile: {$circle_size_mobile}px;
                --circle-distance-mobile: {$circle_distance_mobile}px;
                --central-size-mobile: {$central_circle_size_mobile}px;
            }
        </style>
        ";

        // Build HTML
        $html = $inline_styles;
        $html .= '<div class="mcm-menu-container">';
        $html .= '<div class="mcm-menu-grid">';

        // Add central element
        if ($central_item) {
            $central_icon_url = $central_item->icon_path;
            // Check if it's a full URL or just a filename
            if (!filter_var($central_icon_url, FILTER_VALIDATE_URL)) {
                $central_icon_url = MCM_PLUGIN_URL . 'assets/icons/' . $central_item->icon_path;
            }

            $html .= sprintf(
                '<a href="%s" class="mcm-central-item">
                    <div class="mcm-central-circle">
                        <img src="%s" alt="%s" class="mcm-central-image" />
                    </div>
                </a>',
                esc_url($central_item->url),
                esc_url($central_icon_url),
                esc_attr($central_item->title)
            );
        }

        // Add external elements
        foreach ($external_items as $item) {
            $icon_url = $item->icon_path;
            // Check if it's a full URL or just a filename
            if (!filter_var($icon_url, FILTER_VALIDATE_URL)) {
                $icon_url = MCM_PLUGIN_URL . 'assets/icons/' . $item->icon_path;
            }

            $html .= sprintf(
                '<a href="%s" class="mcm-menu-item" data-title="%s">
                    <div class="mcm-circle">
                        <img src="%s" alt="%s" class="mcm-full-image" />
                    </div>
                </a>',
                esc_url($item->url),
                esc_attr($item->title),
                esc_url($icon_url),
                esc_attr($item->title)
            );
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
