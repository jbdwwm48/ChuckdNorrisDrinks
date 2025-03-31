<?php get_header(); ?>
<main class="container my-3">
    <article class="single-drink">
        <div class="row">
            <div class="col-md-6">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('large'); ?>" class="img-fluid rounded" alt="<?php the_title(); ?>">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h1><?php the_title(); ?></h1>
                <div class="drink-meta mb-4">
                    <span class="price h4"><?php echo get_field('prix'); ?> €</span>
                </div>
                <div class="drink-content mb-4"><?php the_content(); ?></div>
                <div class="drink-stock mb-4">Stock : <?php echo get_field('stock'); ?></div>
                <?php echo do_shortcode('[chuck_norris_fact]'); ?>
                <a href="/commande" class="btn btn-chuck-red">Commander <i class="fas fa-shopping-cart ms-2"></i></a>
                <a href="/" class="btn btn-outline-danger">Accueil</a>
            </div>
        </div>
    </article>
</main>
<?php get_footer(); ?>