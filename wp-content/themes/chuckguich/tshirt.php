<?php get_header(); ?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article class="product-detail">
            <div class="product-image">
                <?php the_post_thumbnail('large'); ?>
            </div>
            <div class="product-info">
                <h1><?php the_title(); ?></h1>

                <?php if (get_field('prix')) : ?>
                    <p class="price">Prix : <?php the_field('prix'); ?> €</p>
                <?php endif; ?>

                <?php if (get_field('taille')) : ?>
                    <p class="size">Tailles disponibles : <?php the_field('taille'); ?></p>
                <?php endif; ?>

                <?php if (get_field('couleur')) : ?>
                    <p class="color">Couleurs : <?php the_field('couleur'); ?></p>
                <?php endif; ?>

                <?php if (get_field('matiere')) : ?>
                    <p class="material">Matière : <?php the_field('matiere'); ?></p>
                <?php endif; ?>

                <div class="product-description">
                    <?php the_content(); ?>
                </div>

                <a href="#" class="cta-button">Acheter maintenant</a>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
