<?php get_header(); ?>

<main>
    <?php if (is_front_page()) : // Vérifie si on est sur la page d'accueil ?>
        <section class="container my-5">
            <h2 class="text-center mb-4">Nos Meilleures Boissons</h2>
            <div class="row">
                <?php
                $args = array(
                    'post_type'      => 'drinks',
                    'posts_per_page' => 4,
                    'orderby'        => 'rand'
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                ?>
                <div class="col-md-3">
                    <div class="card">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                            <p class="fw-bold"><?php echo get_field('prix'); ?> €</p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-info">Voir le produit</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); else : ?>
                    <p class="text-center">Aucune boisson trouvée.</p>
                <?php endif; ?>
            </div>
        </section>
        <div class="text-center mt-4">
            <a href="<?php echo site_url('/boutique'); ?>" class="btn btn-info btn-lg">Boutique</a>
        </div>
    <?php endif; // Fin de la condition pour l'accueil ?>

    <section class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article>
                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                </article>
                <?php
            endwhile;
        else :
            echo '<p>Aucun contenu trouvé.</p>';
        endif;
        ?>
    </section>

    <!-- Section Avis Clients -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Avis Clients</h2>
        <div class="row">
            <?php
            $args_avis = array(
                'post_type'      => 'avis',
                'posts_per_page' => 3,
                'orderby'        => 'rand'
            );
            $query_avis = new WP_Query($args_avis);

            if ($query_avis->have_posts()) :
                while ($query_avis->have_posts()) : $query_avis->the_post();
            ?>
            <div class="col-md-4">
                <div class="card p-3">
                    <blockquote class="blockquote">
                        <p><?php the_content(); ?></p>
                        <footer class="blockquote-footer">- <?php the_title(); ?></footer>
                    </blockquote>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p class="text-center">Aucun avis pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
