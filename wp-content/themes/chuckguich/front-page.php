<?php get_header(); ?>
<main>
    <?php get_template_part('parts/home-drinks'); ?>
    <div class="container my-3"><?php echo do_shortcode('[chuck_norris_fact]'); ?></div>
    <?php get_template_part('parts/home-articles'); ?>
    <?php echo do_shortcode('[chuck_cta]'); ?>
    <div id="review"><?php get_template_part('parts/home-review'); ?></div>
</main>
<?php get_footer(); ?>