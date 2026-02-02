<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Helper function to get correct image URL
function mcm_get_image_url($icon_path)
{
    if (empty($icon_path)) {
        return '';
    }
    // Check if it's already a full URL
    if (filter_var($icon_path, FILTER_VALIDATE_URL)) {
        return $icon_path;
    }
    // Otherwise, assume it's in plugin's assets/icons folder
    return MCM_PLUGIN_URL . 'assets/icons/' . $icon_path;
}

global $wpdb;
$table_items = $wpdb->prefix . 'mesa_menu_items';
$table_settings = $wpdb->prefix . 'mesa_menu_settings';

// Get current settings
$settings_query = $wpdb->get_results("SELECT * FROM $table_settings", OBJECT_K);
$settings = array();
foreach ($settings_query as $key => $setting) {
    $settings[$key] = $setting->setting_value;
}

// Get central item
$central_item = $wpdb->get_row("SELECT * FROM $table_items WHERE is_central = 1 LIMIT 1");

// Get external menu items (max 5)
$external_items = $wpdb->get_results("SELECT * FROM $table_items WHERE is_central = 0 ORDER BY position ASC LIMIT 5");
?>

<div class="wrap mcm-admin-wrap">
    <h1>
        <span class="dashicons dashicons-screenoptions"></span>
        Mesa Circular Menu - Configurazione
    </h1>

    <p class="mcm-description">
        Configura il tuo menu circolare animato. Usa lo shortcode <code>[mesa_circular_menu]</code> per visualizzarlo su
        qualsiasi pagina o post.
    </p>

    <div class="mcm-admin-container">
        <!-- Settings Panel -->
        <div class="mcm-panel mcm-settings-panel">
            <h2><span class="dashicons dashicons-admin-settings"></span> Impostazioni Globali</h2>

            <form id="mcm-settings-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="background_color">Colore Sfondo</label></th>
                        <td>
                            <input type="color" id="background_color" name="background_color"
                                value="<?php echo esc_attr($settings['background_color'] ?? '#F5A623'); ?>" />
                            <p class="description">Colore di sfondo del menu</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="circle_color">Colore Cerchi</label></th>
                        <td>
                            <input type="color" id="circle_color" name="circle_color"
                                value="<?php echo esc_attr($settings['circle_color'] ?? '#FFFFFF'); ?>" />
                            <p class="description">Colore dei cerchi degli elementi</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="text_color">Colore Testo</label></th>
                        <td>
                            <input type="color" id="text_color" name="text_color"
                                value="<?php echo esc_attr($settings['text_color'] ?? '#333333'); ?>" />
                            <p class="description">Colore del testo</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hover_scale">Scala Hover</label></th>
                        <td>
                            <input type="number" id="hover_scale" name="hover_scale"
                                value="<?php echo esc_attr($settings['hover_scale'] ?? '1.15'); ?>" step="0.05" min="1"
                                max="2" />
                            <p class="description">Fattore di ingrandimento al passaggio del mouse (1.0 - 2.0)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="animation_duration">Durata Animazione (s)</label></th>
                        <td>
                            <input type="number" id="animation_duration" name="animation_duration"
                                value="<?php echo esc_attr($settings['animation_duration'] ?? '0.3'); ?>" step="0.1"
                                min="0.1" max="2" />
                            <p class="description">Durata delle animazioni in secondi</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="circle_size">Grandezza Cerchi (px)</label></th>
                        <td>
                            <input type="range" id="circle_size" name="circle_size"
                                value="<?php echo esc_attr($settings['circle_size'] ?? '200'); ?>" min="100" max="400"
                                step="10" />
                            <span
                                id="circle_size_value"><?php echo esc_attr($settings['circle_size'] ?? '200'); ?>px</span>
                            <p class="description">Dimensione dei cerchi esterni (100-400px)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="circle_distance">Distanza dal Centro (px)</label></th>
                        <td>
                            <input type="range" id="circle_distance" name="circle_distance"
                                value="<?php echo esc_attr($settings['circle_distance'] ?? '300'); ?>" min="200"
                                max="500" step="10" />
                            <span
                                id="circle_distance_value"><?php echo esc_attr($settings['circle_distance'] ?? '300'); ?>px</span>
                            <p class="description">Distanza degli elementi dal centro (200-500px)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="central_circle_size">Grandezza Cerchio Centrale (px)</label></th>
                        <td>
                            <input type="range" id="central_circle_size" name="central_circle_size"
                                value="<?php echo esc_attr($settings['central_circle_size'] ?? '250'); ?>" min="150"
                                max="400" step="10" />
                            <span
                                id="central_circle_size_value"><?php echo esc_attr($settings['central_circle_size'] ?? '250'); ?>px</span>
                            <p class="description">Dimensione del cerchio centrale (150-400px)</p>
                        </td>
                    </tr>

                    <!-- Separatore Mobile -->
                    <tr>
                        <th colspan="2">
                            <hr style="margin: 20px 0; border: none; border-top: 2px solid #ddd;">
                            <h3 style="margin: 10px 0;">📱 Impostazioni Mobile (< 768px)</h3>
                                    <p style="color: #666; font-weight: normal;">Controlla le dimensioni su schermi
                                        mobili</p>
                        </th>
                    </tr>

                    <tr>
                        <th scope="row"><label for="circle_size_mobile">Grandezza Cerchi Mobile (px)</label></th>
                        <td>
                            <input type="range" id="circle_size_mobile" name="circle_size_mobile"
                                value="<?php echo esc_attr($settings['circle_size_mobile'] ?? '140'); ?>" min="80"
                                max="250" step="10" />
                            <span
                                id="circle_size_mobile_value"><?php echo esc_attr($settings['circle_size_mobile'] ?? '140'); ?>px</span>
                            <p class="description">Dimensione cerchi esterni su mobile (80-250px)</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="circle_distance_mobile">Distanza dal Centro Mobile (px)</label></th>
                        <td>
                            <input type="range" id="circle_distance_mobile" name="circle_distance_mobile"
                                value="<?php echo esc_attr($settings['circle_distance_mobile'] ?? '180'); ?>" min="100"
                                max="300" step="10" />
                            <span
                                id="circle_distance_mobile_value"><?php echo esc_attr($settings['circle_distance_mobile'] ?? '180'); ?>px</span>
                            <p class="description">Distanza dal centro su mobile (100-300px)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="central_circle_size_mobile">Grandezza Centrale Mobile (px)</label>
                        </th>
                        <td>
                            <input type="range" id="central_circle_size_mobile" name="central_circle_size_mobile"
                                value="<?php echo esc_attr($settings['central_circle_size_mobile'] ?? '120'); ?>"
                                min="80" max="200" step="10" />
                            <span
                                id="central_circle_size_mobile_value"><?php echo esc_attr($settings['central_circle_size_mobile'] ?? '120'); ?>px</span>
                            <p class="description">Dimensione cerchio centrale su mobile (80-200px)</p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <span class="dashicons dashicons-yes"></span> Salva Impostazioni
                    </button>
                </p>
            </form>
        </div>

        <!-- Central Element Panel -->
        <div class="mcm-panel mcm-central-panel">
            <h2><span class="dashicons dashicons-star-filled"></span> Elemento Centrale</h2>

            <p class="mcm-info">
                <span class="dashicons dashicons-info"></span>
                L'elemento centrale viene visualizzato al centro del menu circolare.
            </p>

            <?php if ($central_item): ?>
                <div class="mcm-item mcm-central-item-edit" data-id="<?php echo esc_attr($central_item->id); ?>"
                    data-is-central="1">
                    <div class="mcm-item-preview mcm-central-preview">
                        <?php if ($central_item->icon_path): ?>
                            <img src="<?php echo esc_url(mcm_get_image_url($central_item->icon_path)); ?>"
                                alt="<?php echo esc_attr($central_item->title); ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="mcm-item-content">
                        <label>Titolo (solo admin)</label>
                        <input type="text" class="mcm-item-title" value="<?php echo esc_attr($central_item->title); ?>"
                            placeholder="Me.Sa.Group" />

                        <label>URL</label>
                        <input type="url" class="mcm-item-url" value="<?php echo esc_url($central_item->url); ?>"
                            placeholder="https://esempio.com" />

                        <label>Immagine</label>
                        <input type="text" class="mcm-item-icon" value="<?php echo esc_attr($central_item->icon_path); ?>"
                            placeholder="logo-centrale.png" readonly />

                        <button type="button" class="button button-secondary mcm-upload-icon">
                            <span class="dashicons dashicons-upload"></span> Carica Immagine Centrale
                        </button>

                        <button type="button" class="button button-primary mcm-save-item"
                            data-id="<?php echo esc_attr($central_item->id); ?>" data-is-central="1"
                            style="margin-top: 10px;">
                            <span class="dashicons dashicons-yes"></span> Salva Elemento Centrale
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <p class="mcm-warning">
                    <span class="dashicons dashicons-warning"></span>
                    Nessun elemento centrale trovato. Creane uno nuovo.
                </p>
                <button type="button" id="mcm-add-central" class="button button-primary">
                    <span class="dashicons dashicons-plus-alt"></span> Crea Elemento Centrale
                </button>
            <?php endif; ?>
        </div>

        <!-- Menu Items Panel -->
        <div class="mcm-panel mcm-items-panel">
            <h2><span class="dashicons dashicons-menu"></span> Elementi Esterni (5)</h2>

            <p class="mcm-info">
                <span class="dashicons dashboard-info"></span>
                Puoi aggiungere massimo 5 elementi esterni. Trascina per riordinarli.
            </p>

            <div id="mcm-menu-items" class="mcm-sortable-list">
                <?php foreach ($external_items as $item): ?>
                    <div class="mcm-item" data-id="<?php echo esc_attr($item->id); ?>">
                        <div class="mcm-item-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        <div class="mcm-item-preview">
                            <?php if ($item->icon_path): ?>
                                <img src="<?php echo esc_url(mcm_get_image_url($item->icon_path)); ?>"
                                    alt="<?php echo esc_attr($item->title); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="mcm-item-content">
                            <input type="text" class="mcm-item-title" value="<?php echo esc_attr($item->title); ?>"
                                placeholder="Titolo" />
                            <input type="url" class="mcm-item-url" value="<?php echo esc_url($item->url); ?>"
                                placeholder="https://esempio.com" />
                            <input type="text" class="mcm-item-icon" value="<?php echo esc_attr($item->icon_path); ?>"
                                placeholder="nome-icona.svg" readonly />
                            <button type="button" class="button mcm-upload-icon">
                                <span class="dashicons dashicons-upload"></span> Carica Immagine Completa
                            </button>
                        </div>
                        <div class="mcm-item-actions">
                            <button type="button" class="button mcm-save-item" data-id="<?php echo esc_attr($item->id); ?>">
                                <span class="dashicons dashicons-yes"></span>
                            </button>
                            <button type="button" class="button mcm-delete-item"
                                data-id="<?php echo esc_attr($item->id); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                            <label class="mcm-toggle">
                                <input type="checkbox" class="mcm-item-active" <?php checked($item->active, 1); ?>
                                    data-id="<?php echo esc_attr($item->id); ?>" />
                                <span class="mcm-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($external_items) < 5): ?>
                <button type="button" id="mcm-add-item" class="button button-secondary">
                    <span class="dashicons dashicons-plus-alt"></span> Aggiungi Elemento
                    (<?php echo count($external_items); ?>/5)
                </button>
            <?php else: ?>
                <p class="mcm-info"><span class="dashicons dashicons-warning"></span> Hai raggiunto il limite di 5 elementi
                    esterni.</p>
            <?php endif; ?>
        </div>

        <!-- Preview Panel -->
        <div class="mcm-panel mcm-preview-panel">
            <h2><span class="dashicons dashicons-visibility"></span> Anteprima</h2>

            <div class="mcm-preview-container">
                <iframe id="mcm-preview-frame"></iframe>
            </div>

            <p class="mcm-shortcode-info">
                <strong>Shortcode:</strong> <code>[mesa_circular_menu]</code>
                <button type="button" class="button button-small mcm-copy-shortcode">
                    <span class="dashicons dashicons-clipboard"></span> Copia
                </button>
            </p>
        </div>
    </div>
</div>

<style>
    .mcm-admin-wrap {
        margin: 20px 20px 20px 0;
    }

    .mcm-admin-wrap h1 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .mcm-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 30px;
    }

    .mcm-admin-container {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        gap: 20px;
    }

    .mcm-panel {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .mcm-panel h2 {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 0;
        font-size: 18px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 12px;
    }

    .mcm-info {
        background: #f0f6fc;
        border-left: 4px solid #0073aa;
        padding: 12px;
        margin: 15px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mcm-sortable-list {
        margin: 20px 0;
    }

    .mcm-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }

    .mcm-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .mcm-item-handle {
        cursor: move;
        color: #999;
    }

    .mcm-item-preview {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #eee;
    }

    .mcm-item-preview img {
        width: 35px;
        height: 35px;
        object-fit: contain;
    }

    .mcm-item-content {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .mcm-item-content input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .mcm-item-content .mcm-upload-icon {
        grid-column: 1 / -1;
        justify-self: start;
    }

    .mcm-item-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .mcm-toggle {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .mcm-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .mcm-toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 24px;
        transition: 0.3s;
    }

    .mcm-toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: 0.3s;
    }

    .mcm-toggle input:checked+.mcm-toggle-slider {
        background-color: #2196F3;
    }

    .mcm-toggle input:checked+.mcm-toggle-slider:before {
        transform: translateX(26px);
    }

    .mcm-preview-container {
        width: 100%;
        height: 400px;
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
    }

    #mcm-preview-frame {
        width: 100%;
        height: 100%;
        border: none;
    }

    .mcm-shortcode-info {
        background: #f0f0f0;
        padding: 12px;
        border-radius: 6px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mcm-shortcode-info code {
        font-size: 14px;
        padding: 4px 8px;
        background: #fff;
        border-radius: 3px;
    }

    @media (max-width: 1400px) {
        .mcm-admin-container {
            grid-template-columns: 1fr;
        }
    }
</style>