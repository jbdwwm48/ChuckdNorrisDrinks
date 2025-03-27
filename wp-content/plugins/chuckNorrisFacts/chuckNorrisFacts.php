<?php

/**
 * Plugin Name: Chuck Norris Facts
 * Description: Affichage de Chuck Norris Facts en français avec un shortcode
 * Version: 1.0.2
 * Author: Jbdwwm48
 * License: GPL v2 or later
 */

// Enable activation/deactivation of the plugin in WP_Admin/PluginManager
register_activation_hook(__FILE__, function () {
    touch(__DIR__ . '/chuckNorrisFacts');
});

register_deactivation_hook(__FILE__, function () {
    unlink(__DIR__ . '/chuckNorrisFacts');
});

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Cache duration in seconds (24 hours)
define('CNF_CACHE_DURATION', 86400);

function get_chuck_norris_fact()
{
    $response = wp_remote_get('https://api.chucknorris.io/jokes/random');

    if (is_wp_error($response)) {
        return 'Error: Could not fetch Chuck Norris fact.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    return $data->value;
}

function translate_to_french($text)
{
    // Using MyMemory Translation API which is free and doesn't require API keys
    $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($text) . '&langpair=en|fr';

    $response = wp_remote_get($url, [
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        error_log('Translation Error: ' . $response->get_error_message());
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (!$data || !isset($data->responseData->translatedText)) {
        error_log('Translation API Response: ' . $body);
        return false;
    }

    return $data->responseData->translatedText;
}

function get_cached_fact()
{
    $cached = get_transient('chuck_norris_fact_fr');

    if ($cached !== false) {
        return $cached;
    }

    $fact = get_chuck_norris_fact();
    $translated = translate_to_french($fact);

    if ($translated) {
        set_transient('chuck_norris_fact_fr', $translated, CNF_CACHE_DURATION);
        return $translated;
    }

    // If translation fails, return the original English fact instead of an error message
    return $fact;
}

function chuck_norris_fact_shortcode($atts)
{
    // Parse attributes
    $atts = shortcode_atts([
        'show_original' => 'no', // Default to not showing original
        'minimal' => 'yes',     // Default to minimal style
    ], $atts);

    $fact = get_chuck_norris_fact();
    $translated = translate_to_french($fact);

    if ($atts['minimal'] === 'yes') {
        // Minimalistic version
        $html = '<div class="chuck-minimal-container">';

        if ($translated && $atts['show_original'] === 'yes') {
            $html .= '
                <div class="chuck-minimal-card">
                    <div class="chuck-minimal-content">
                        <p class="chuck-minimal-text"><small class="text-muted">English:</small> ' . esc_html($fact) . '</p>
                        <p class="chuck-minimal-text"><small class="text-muted">Français:</small> ' . esc_html($translated) . '</p>
                    </div>
                    <button class="chuck-minimal-refresh btn btn-link p-0 text-muted">
                        <small><i class="bi bi-arrow-clockwise"></i> Nouveau fait</small>
                    </button>
                </div>';
        } elseif ($translated) {
            $html .= '
                <div class="chuck-minimal-card">
                    <div class="chuck-minimal-content">
                        <p class="chuck-minimal-text">' . esc_html($translated) . '</p>
                    </div>
                    <button class="chuck-minimal-refresh btn btn-link p-0 text-muted">
                        <small><i class="bi bi-arrow-clockwise"></i> Nouveau fait</small>
                    </button>
                </div>';
        } else {
            $html .= '
                <div class="chuck-minimal-card">
                    <div class="chuck-minimal-content">
                        <p class="chuck-minimal-text">' . esc_html($fact) . '</p>
                        <small class="text-muted">Translation unavailable</small>
                    </div>
                    <button class="chuck-minimal-refresh btn btn-link p-0 text-muted">
                        <small><i class="bi bi-arrow-clockwise"></i> Nouveau fait</small>
                    </button>
                </div>';
        }

        $html .= '</div>';
    } else {
        // Original version (unchanged)
        $html = '<div class="container py-3">';
        $html .= '<div class="row justify-content-center">';
        $html .= '<div class="col-12 col-md-8 col-lg-6">';

        if ($translated && $atts['show_original'] === 'yes') {
            // Show both original and translated
            $html .= '
                <div class="card mb-3 shadow-sm" style="background-color: #fff; border-color: #333;">
                    <div class="card-header py-2" style="background-color: #333; color: #fff;">
                        <h5 class="mb-0">Chuck Norris Fact</h5>
                    </div>
                    <div class="card-body py-2">
                        <div class="mb-2">
                            <h6 class="card-subtitle mb-1" style="color: #666;">Original (English):</h6>
                            <p class="card-text small">' . esc_html($fact) . '</p>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1" style="color: #666;">Français:</h6>
                            <p class="card-text small">' . esc_html($translated) . '</p>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-center" style="background-color: #f5f5f5;">
                        <button class="btn btn-sm chuck-refresh" style="background-color: #333; color: #fff; border-color: #333;">Nouveau fait</button>
                    </div>
                </div>';
        } elseif ($translated) {
            // Show only translated version
            $html .= '
                <div class="card mb-3 shadow-sm" style="background-color: #fff; border-color: #333;">
                    <div class="card-header py-2" style="background-color: #333; color: #fff;">
                        <h5 class="mb-0">Chuck Norris Fact</h5>
                    </div>
                    <div class="card-body py-2">
                        <p class="card-text small">' . esc_html($translated) . '</p>
                    </div>
                    <div class="card-footer py-2 text-center" style="background-color: #f5f5f5;">
                        <button class="btn btn-sm chuck-refresh" style="background-color: #333; color: #fff; border-color: #333;">Nouveau fait</button>
                    </div>
                </div>';
        } else {
            // Fallback to original if translation failed
            $html .= '
                <div class="card mb-3 shadow-sm" style="background-color: #fff; border-color: #333;">
                    <div class="card-header py-2" style="background-color: #333; color: #fff;">
                        <h5 class="mb-0">Chuck Norris Fact</h5>
                    </div>
                    <div class="card-body py-2">
                        <p class="card-text small">' . esc_html($fact) . '</p>
                        <small style="color: #999;">Translation unavailable</small>
                    </div>
                    <div class="card-footer py-2 text-center" style="background-color: #f5f5f5;">
                        <button class="btn btn-sm chuck-refresh" style="background-color: #333; color: #fff; border-color: #333;">Nouveau fait</button>
                    </div>
                </div>';
        }

        $html .= '</div></div></div>';
    }

    // Add simple JavaScript to refresh on button click
    $html .= '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle minimal version refresh
        const minimalRefreshButtons = document.querySelectorAll(".chuck-minimal-refresh");
        minimalRefreshButtons.forEach(function(button) {
            button.addEventListener("click", function() {
                const container = this.closest(".chuck-minimal-container");
                if (container) {
                    container.innerHTML = \'<div class="text-center py-2"><div class="spinner-border spinner-border-sm text-muted" role="status"></div></div>\';
                    
                    // Reload the page to get a new fact
                    location.reload();
                }
            });
        });
        
        // Handle original version refresh (unchanged)
        const refreshButtons = document.querySelectorAll(".chuck-refresh");
        refreshButtons.forEach(function(button) {
            button.addEventListener("click", function() {
                const container = this.closest(".card");
                if (container) {
                    container.innerHTML = "<div class=\"d-flex justify-content-center p-3\"><div class=\"spinner-border\" style=\"color: #333;\" role=\"status\"><span class=\"visually-hidden\">Loading...</span></div></div>";
                    
                    // Reload the page to get a new fact
                    location.reload();
                }
            });
        });
    });
    </script>';

    return $html;
}

add_shortcode('chuck_norris_fact', 'chuck_norris_fact_shortcode');

function chuck_norris_fact_enqueue_assets()
{
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

    // Bootstrap Icons
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css');

    // Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.3.0', true);

    // Minimal style
    wp_add_inline_style('bootstrap', '
        .chuck-minimal-container {
            max-width: 100%;
            font-size: 0.9rem;
        }
        .chuck-minimal-card {
            background: transparent;
            border: none;
            padding: 0;
            margin: 0;
        }
        .chuck-minimal-content {
            padding: 0.5rem 0;
        }
        .chuck-minimal-text {
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        .chuck-minimal-refresh {
            text-decoration: none;
            cursor: pointer;
        }
        .chuck-minimal-refresh:hover {
            text-decoration: underline;
        }
    ');
}
add_action('wp_enqueue_scripts', 'chuck_norris_fact_enqueue_assets');
