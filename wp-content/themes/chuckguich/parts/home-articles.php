<section class="container my-5">
    <?php
    $articles_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish' // Assure que seuls les articles publiés sont récupérés
    );
    $articles_query = new WP_Query($articles_args);

    if ($articles_query->have_posts()) :
        $counter = 0;
        while ($articles_query->have_posts()) : $articles_query->the_post();
            $counter++;
            $alignment_class = ($counter % 2 == 0) ? 'flex-row-reverse' : '';
    ?>
            <div class="card mb-4 border-0 shadow-sm" style="max-height: 200px; overflow: hidden;">
                <div class="row g-0 h-100 <?php echo $alignment_class; ?>">
                    <div class="col-md-4 h-100">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="h-100 d-block">
                                <img src="<?php the_post_thumbnail_url('medium'); ?>"
                                    class="img-fluid rounded-start h-100 w-100 object-fit-cover"
                                    alt="<?php the_title(); ?>"
                                    style="min-height: 200px;">
                            </a>
                        <?php else : ?>
                            <div class="bg-light h-100 d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                <span class="text-muted">Pas D'Image</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8 h-100">
                        <div class="card-body h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?php echo get_the_date(); ?>
                                </small>
                                <small class="text-muted">
                                    <i class="far fa-user me-1"></i>
                                    <?php the_author(); ?>
                                </small>
                            </div>
                            <h3 class="card-title h5 mb-2 fw-semibold">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p class="card-text flex-grow-1" style="font-size: 0.875rem; line-height: 1.4; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                            </p>
                            <div class="mt-auto">
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn btn-outline-danger">
                                    Plus D'Infos <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();
    else : ?>
        <p class="text-center py-5">Pas D'articles.</p>
    <?php endif; ?>
</section>