<section class="container my-5">
    <h2 class="text-center mb-4">Nos Boissons</h2>
    <div class="row">
        <?php
        $drinks_args = array(
            'post_type'      => 'drinks',
            'posts_per_page' => 4,
            'orderby'        => 'rand'
        );
        $drinks_query = new WP_Query($drinks_args);

        if ($drinks_query->have_posts()) :
            while ($drinks_query->have_posts()) : $drinks_query->the_post();
                // Récupérer le slug de la boisson
                $drink_slug = get_post_field('post_name', get_the_ID());
        ?>
                <div class="col-md-3">
                    <div class="card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php echo home_url('/boissons/' . $drink_slug); ?>">
                                <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                            </a>
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold">
                                <a href="<?php echo home_url('/boissons/' . $drink_slug); ?>" class="text-decoration-none text-dark hover:text-danger">
                                    <?php the_title(); ?>
                                </a>
                            </h5>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                            <p class="fw-bold"><?php echo get_field('prix'); ?> €</p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-danger">Voir le produit</a>
                            <a href="/commande" class="btn btn-danger">Commander</a>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p class="text-center">Aucune boisson trouvée.</p>
        <?php endif; ?>
    </div>

</section>