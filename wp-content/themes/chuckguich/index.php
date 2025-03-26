<?php get_header(); ?>

<main>
    <div class="container">
        <?php
        // Vérifie si on est sur la page d'accueil
        if (is_front_page()) {
            echo '<h1>Bienvenue sur mon site</h1>';
            echo '<p>Ceci est la page d’accueil.</p>';
        } else {
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
        }
        ?>
    </div>
    <div class='container'>
        <?php
        if (is_front_page()) {
            echo do_shortcode('[chuck_saucisse_simple]');
        }
        // Or any other condition
        ?>
    </div>
</main>

<?php get_footer(); ?>