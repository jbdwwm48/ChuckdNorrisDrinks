<?php
function mon_theme_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'mon-theme'),
    ));
}
add_action('after_setup_theme', 'mon_theme_setup');

function mon_theme_enqueue_scripts()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_scripts');


function enqueue_bootstrap()
{
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

    // Bootstrap JS (avec dépendance à jQuery)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');


// Register the simple Chuck Saucisse shortcode
function chuck_saucisse_simple_shortcode()
{
    ob_start();
?>
    <div class="container my-5">
        <div class="card">
            <div class="row g-0">
                <div class="col-md-4 bg-danger text-center d-flex align-items-center justify-content-center">
                    <div class="py-5">
                        <h3 class="text-white fw-bold">CS</h3>
                        <span class="badge bg-dark">NOUVEAU</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title">CHUCK SAUCISSE</h2>
                        <p class="card-text">L'énergie légendaire, maintenant en canette.</p>
                        <a href="#" class="btn btn-danger">COMMANDER</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('chuck_saucisse_simple', 'chuck_saucisse_simple_shortcode');
