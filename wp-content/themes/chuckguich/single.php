<?php get_header(); ?>
<main class="container my-3">
    <article class="single-post">
        <h1 class="mb-4"><?php the_title(); ?></h1>
        <div class="meta mb-4 text-muted">
            <span class="me-3"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
            <span><i class="far fa-user"></i> <?php the_author(); ?></span>
        </div>
        <?php if (has_post_thumbnail()) : ?>
            <div class="text-center mb-4">
                <img src="<?php the_post_thumbnail_url('large'); ?>" class="img-fluid rounded" alt="<?php the_title(); ?>">
            </div>
        <?php endif; ?>
        <div class="post-content"><?php the_content(); ?></div>
        <div class="text-center my-3"><?php echo do_shortcode('[chuck_norris_fact]'); ?></div>
        <a href="/" class="btn btn-outline-danger btn-lg px-4 py-2">Accueil</a>
    </article>
</main>
<?php get_footer(); ?>