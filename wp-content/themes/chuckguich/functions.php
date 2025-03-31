<?php
/**
 * Fonctions du thème Chuck Guich
 */

/**
 * Configuration initiale du thème
 */
function chuckguich_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'chuckguich'),
    ));
}
add_action('after_setup_theme', 'chuckguich_setup');

/**
 * Chargement des styles et scripts
 */
function chuckguich_enqueue_assets() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    // Style principal du thème
    wp_enqueue_style('chuckguich-style', get_stylesheet_uri(), array('bootstrap-css'), '1.0.0');

    // jQuery (inclus par défaut dans WP)
    wp_enqueue_script('jquery');
    // Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
    // Script personnalisé pour la commande
    wp_enqueue_script('chuckguich-order', get_template_directory_uri() . '/js/order.js', array('jquery'), '1.0.0', true);
    // Script personnalisé du collègue (s’il existe)
    wp_enqueue_script('chuckguich-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'chuckguich_enqueue_assets');

/**
 * Réécriture des URLs pour les boissons
 */
function chuckguich_rewrite_rules() {
    add_rewrite_rule('boissons/([^/]+)/?$', 'index.php?drinks=$matches[1]', 'top');
    add_permastruct('drinks', 'boissons/%drinks%');
}
add_action('init', 'chuckguich_rewrite_rules');

function chuckguich_drink_post_type_link($link, $post) {
    if ($post->post_type === 'drinks') {
        return home_url('boissons/' . $post->post_name);
    }
    return $link;
}
add_filter('post_type_link', 'chuckguich_drink_post_type_link', 10, 2);

function chuckguich_flush_rewrites() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'chuckguich_flush_rewrites');

/**
 * Ajout de classes Bootstrap aux liens du menu
 */
function add_button_class_to_menu_links($atts, $item, $args) {
    if ($args->theme_location == 'main-menu') {
        $atts['class'] = 'btn btn-light'; // Boutons clairs pour le menu
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_button_class_to_menu_links', 10, 3);

/**
 * Shortcode pour afficher les boissons en cards (collègue)
 */
function afficher_boissons_en_cards($atts) {
    if (!is_front_page()) {
        return ''; // Ne rien afficher si ce n'est pas la page d'accueil
    }

    ob_start();
    $args = array(
        'post_type'      => 'drinks',
        'posts_per_page' => 4,
        'orderby'        => 'rand'
    );
    
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<section class="container my-5">';
        echo '<h2 class="text-center mb-4">Nos Meilleures Boissons</h2>';
        echo '<div class="row">';

        while ($query->have_posts()) : $query->the_post();
            ?>
            <div class="col-md-3">
                <div class="card">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <p class="card-text"><?php the_excerpt(); ?></p>
                        <p class="fw-bold"><?php echo get_field('prix'); ?> €</p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Voir le produit</a>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
        echo '<div class="text-center mt-4">';
        echo '<a href="' . site_url('/boutique') . '" class="btn btn-success btn-lg">Voir toute la boutique</a>';
        echo '</div>';
        echo '</section>';
    else :
        echo '<p class="text-center">Aucune boisson trouvée.</p>';
    endif;

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('boutique_boissons', 'afficher_boissons_en_cards');

/**
 * Shortcode pour afficher la liste des avis (collègue)
 */
function afficher_liste_avis() {
    ob_start();
    $args = array(
        'post_type'      => 'avis',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<section class="container my-5">';
        echo '<h2 class="text-center mb-4">Avis Clients</h2>';
        echo '<div class="row">';
        
        while ($query->have_posts()) : $query->the_post();
            ?>
            <div class="col-md-4">
                <div class="card p-3">
                    <blockquote class="blockquote">
                        <p><?php the_content(); ?></p>
                        <footer class="blockquote-footer">- <?php the_title(); ?></footer>
                    </blockquote>
                </div>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
        echo '</section>';
    else :
        echo '<p class="text-center">Aucun avis pour le moment.</p>';
    endif;

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('liste_avis', 'afficher_liste_avis');

/**
 * Création du CPT T-shirts (collègue)
 */
function creer_cpt_tshirts() {
    $args = array(
        'label'               => 'T-shirts',
        'public'              => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-shirt',
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'has_archive'         => true,
    );
    register_post_type('tshirts', $args);
}
add_action('init', 'creer_cpt_tshirts');

/**
 * Shortcode pour le CTA Chuck Saucisse (ton original)
 */
function chuckguich_saucisse_shortcode() {
    ob_start();
    ?>
    <div class="container my-5">
        <div class="card chuck-saucisse-card">
            <div class="row g-0">
                <div class="col-md-4 bg-chuck-red text-center d-flex align-items-center justify-content-center">
                    <div class="py-5">
                        <h3 class="text-white fw-bold">CS</h3>
                        <span class="badge bg-dark">NOUVEAU</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title">CHUCK SAUCISSE</h2>
                        <p class="card-text">L'énergie légendaire, maintenant en canette.</p>
                        <a href="/commande" class="btn btn-chuck-red">COMMANDER</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('chuck_saucisse', 'chuckguich_saucisse_shortcode');

/**
 * Shortcode pour le CTA Chuck Saucisse avec graphisme CSS et promo Chuck Norris
 */
function chuckguich_cta_shortcode() {
    $latest_drink = new WP_Query(array(
        'post_type' => 'drinks',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    ob_start();
    ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="cta-hero text-center py-4">
                    <div class="cta-overlay">
                        <?php if ($latest_drink->have_posts()) : while ($latest_drink->have_posts()) : $latest_drink->the_post(); ?>
                            <h2 class="cta-title fw-bold mb-2">DÉCOUVREZ <?php the_title(); ?> !</h2>
                        <?php endwhile; wp_reset_postdata(); endif; ?>
                        <p class="cta-text mb-3">La nouvelle bombe énergétique de Chuck Saucisse – goûtez la légende !</p>
                        <a href="/commande" class="btn btn-chuck-red px-4 py-2 shadow">Commandez Maintenant !</a>
                        <p class="chuck-promo mt-3 text-uppercase fw-bold">
                            "Chuck Norris ordonne 20% de reduc avec <b>SAUCISSE20</b> – les prix s’inclinent !"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('chuck_cta', 'chuckguich_cta_shortcode');