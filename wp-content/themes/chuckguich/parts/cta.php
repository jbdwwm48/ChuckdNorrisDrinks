<?php
// Récupérer toutes les images de la médiathèque
$images = new WP_Query(array(
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => -1, // Toutes les images
    'post_status' => 'inherit',
));

// Stocker les URLs des images dans un tableau
$image_urls = array();
if ($images->have_posts()) {
    while ($images->have_posts()) {
        $images->the_post();
        $image_urls[] = wp_get_attachment_url(get_the_ID());
    }
    wp_reset_postdata();
}

// Choisir une image aléatoire
$random_image_url = !empty($image_urls) ? $image_urls[array_rand($image_urls)] : '';

// Récupérer la dernière boisson pour le titre
$latest_drink = new WP_Query(array(
    'post_type' => 'drinks',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC'
));
?>

<div class="cta-hero text-center py-5" style="background-image: url('<?php echo esc_url($random_image_url); ?>');">
    <div class="cta-overlay">
        <?php if ($latest_drink->have_posts()) : while ($latest_drink->have_posts()) : $latest_drink->the_post(); ?>
            <h2 class="cta-title fw-bold mb-3">DÉCOUVREZ <?php the_title(); ?> !</h2>
        <?php endwhile; wp_reset_postdata(); endif; ?>
        <p class="cta-text mb-4">La nouvelle bombe énergétique de Chuck Saucisse – goûtez la légende dès aujourd’hui !</p>
        <a href="/commande" class="btn btn-chuck-red btn-lg px-5 py-3 shadow">Commandez Maintenant !</a>
        <p class="chuck-promo mt-4 text-uppercase fw-bold">
            "Chuck Norris ne fait pas de réductions, mais il te donne 20% avec <b>SAUCISSE20</b> – parce que même les prix ont peur de lui !"
        </p>
    </div>
</div>