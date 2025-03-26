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

    <?php
    $args = array(
        'post_type' => "drinks"
    );
    // The Query.
    $the_query = new WP_Query($args);

    // The Loop.
    if ($the_query->have_posts()) {
        echo '<ul>';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            echo '<li>' . esc_html(get_the_title()) . '</li>';
            echo '<li>' . the_post_thumbnail() . '</li>';
            echo '<li>' . esc_html(the_excerpt()) . '</li>';
            echo '<li>' . esc_html(get_field('prix')) . '</li>';
            echo '<li>' . esc_html(get_field('ingredients')) . '</li>';
            echo '<li>' . esc_html(get_field('taille')) . '</li>';
        }
        echo '</ul>';
    } else {
        esc_html_e('Sorry, no posts matched your criteria.');
    }
    // Restore original Post Data.
    wp_reset_postdata();
    ?>
</main>

<?php get_footer(); ?> 