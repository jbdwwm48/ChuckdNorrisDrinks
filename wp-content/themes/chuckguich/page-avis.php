<?php
/*
Template Name: Page des Avis
*/

get_header(); ?>

<main class="container my-5">
    <h1 class="text-center mb-4">Avis Clients</h1>
    <div class="row">
        <?php
        $args = array(
            'post_type'      => 'avis',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) :
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
        else :
            echo '<p class="text-center">Aucun avis pour le moment.</p>';
        endif;

        wp_reset_postdata();
        ?>
    </div>
</main>

<?php get_footer(); ?>
