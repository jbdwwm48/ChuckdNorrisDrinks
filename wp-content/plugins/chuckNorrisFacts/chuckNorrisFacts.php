<?php
/**
 * Plugin Name: Chuck Norris Facts
 * Description: Affichage de Chuck Norris Facts en français avec un shortcode, aléatoire à chaque affichage
 * Version: 1.0.4
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

function get_chuck_norris_fact() {
    $response = wp_remote_get('https://api.chucknorris.io/jokes/random');
    if (is_wp_error($response)) {
        return 'Erreur : Impossible de récupérer un fait Chuck Norris.';
    }
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);
    return $data->value;
}

function translate_to_french($text) {
    $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($text) . '&langpair=en|fr';
    $response = wp_remote_get($url, ['timeout' => 15]);
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

function get_random_fact() {
    $fact = get_chuck_norris_fact();
    $translated = translate_to_french($fact);
    return $translated ? $translated : $fact; // Retourne la traduction si réussie, sinon l'original
}

function chuck_norris_fact_shortcode($atts) {
    $atts = shortcode_atts(['minimal' => 'yes'], $atts);
    $fact = get_random_fact(); // Nouveau fait à chaque appel

    ob_start();
    ?>
    <div class="chuck-fact-box">
        <div class="chuck-fact-header">
            <h5>Le Saviez Vous ? 🤓</h5>
        </div>
        <div class="chuck-fact-content">
            <p><?php echo esc_html($fact); ?></p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('chuck_norris_fact', 'chuck_norris_fact_shortcode');

function chuck_norris_fact_enqueue_assets() {
    wp_enqueue_style('chuck-fact-style', plugins_url('style.css', __FILE__), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'chuck_norris_fact_enqueue_assets');