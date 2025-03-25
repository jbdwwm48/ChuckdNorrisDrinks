<?php
/**
 * Template Name: Custom Page Template
 * Description: Un modèle personnalisé pour WordPress
 */

get_header(); ?>

<main>
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="content">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    the_content();
                endwhile;
            else :
                echo '<p>Aucun contenu trouvé.</p>';
            endif;
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>