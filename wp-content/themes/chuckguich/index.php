<?php get_header(); ?>

<main>
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article>
                    <header class="entry-header">
                        <h1><?php the_title(); ?></h1>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php
            endwhile;
        else :
            echo '<p>Aucun contenu trouvé.</p>';
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>


