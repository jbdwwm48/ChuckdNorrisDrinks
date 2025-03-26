<?php
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
    <div class="header-container">
        

        <nav>
            <?php wp_nav_menu(array('theme_location' => 'main-menu', 'menu_class' => 'main-nav')); ?>
        </nav>
    </div>

    <?php
    $images = get_posts(array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => 5,
        'post_status'    => 'inherit',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    if ($images) : ?>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($images as $index => $image) : ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo wp_get_attachment_url($image->ID); ?>" class="d-block w-100" style="max-height: 300px; object-fit: cover;" alt="Image">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        </div>
    <?php endif; ?>

</header>

<main>
    <!-- Contenu de la page -->
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var myCarousel = new bootstrap.Carousel(document.getElementById("carouselExample"), {
        interval: 3000,
        ride: "carousel",
        pause: "hover",
        wrap: true
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
