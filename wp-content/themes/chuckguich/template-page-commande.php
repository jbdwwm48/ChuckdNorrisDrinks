<?php
/**
 * Template Name: Commande Click & Collect
 */
get_header();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    require_once(get_template_directory() . '/process-order.php');
}

$args = array(
    'post_type' => 'drinks',
    'meta_query' => array(
        array(
            'key' => 'stock',
            'value' => 0,
            'compare' => '>'
        )
    )
);
$drinks_query = new WP_Query($args);
?>

<div class="order-page py-5">
    <?php if (isset($order_success)) : ?>
        <div class="container">
            <div class="alert alert-success commande-alert">
                <h4>Merci <?php echo esc_html($order_data['first_name']); ?> !</h4>
                <p>Votre commande a bien été enregistrée.</p>
            </div>
        </div>
    <?php elseif (isset($order_error)) : ?>
        <div class="container">
            <div class="alert alert-danger commande-alert">
                <?php echo esc_html($order_error); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <form method="POST" id="orderForm">
            <div class="row">
                <!-- Produits : Mobile (1 colonne), Desktop (moitié gauche) -->
                <div class="col-12 col-md-6">
                    <h1 class="mb-4 commande-title"><?php the_title(); ?></h1>
                    <div class="row">
                        <?php while ($drinks_query->have_posts()) : $drinks_query->the_post(); ?>
                            <div class="col-12 col-md-6 mb-4">
                                <div class="card h-100 product-card">
                                    <div class="card-img-top">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('thumbnail', ['class' => 'product-thumbnail']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php the_title(); ?></h5>
                                        <p class="text-muted"><?php echo get_field('contenance'); ?></p>
                                        <p class="price-text"><?php echo get_field('prix'); ?>€</p>
                                        <div class="input-group mt-3">
                                            <input type="number" 
                                                name="products[<?php echo get_the_ID(); ?>][quantity]" 
                                                class="form-control quantity-input" 
                                                min="0" 
                                                max="<?php echo get_field('stock'); ?>"
                                                data-price="<?php echo get_field('prix'); ?>"
                                                value="0">
                                            <input type="hidden" 
                                                name="products[<?php echo get_the_ID(); ?>][price]" 
                                                value="<?php echo get_field('prix'); ?>">
                                        </div>
                                        <small class="text-muted stock-info"><?php echo get_field('stock'); ?> en stock</small>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>

                <!-- Colonne droite : Mobile (1 colonne), Desktop (moitié droite, sticky) -->
                <div class="col-12 col-md-6 sticky-col">
                    <!-- Votre commande -->
                    <div class="card shadow order-summary mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Votre commande</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Informations</label>
                                <input type="text" name="first_name" class="form-control mb-2" placeholder="Prénom" required>
                                <input type="text" name="last_name" class="form-control mb-2" placeholder="Nom" required>
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Code promo</label>
                                <div class="input-group">
                                    <input type="text" id="promoCode" name="promo_code" class="form-control">
                                    <button type="button" id="applyPromo" class="btn btn-outline-danger">Appliquer</button>
                                </div>
                                <small id="promoMessage" class="form-text"></small>
                                <input type="hidden" id="discountType" name="discount_type">
                                <input type="hidden" id="discountValue" name="discount_value">
                            </div>

                            <div class="mb-3 order-recap">
                                <h6>Récapitulatif</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Total HT :</span>
                                    <span id="subtotal">0.00€</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>TVA (20%) :</span>
                                    <span id="tax">0.00€</span>
                                </div>
                                <div id="beforeDiscountRow" class="d-none d-flex justify-content-between">
                                    <span>Total avant réduction :</span>
                                    <span id="totalBeforeDiscount">0.00€</span>
                                </div>
                                <div id="discountRow" class="d-none discount-row">
                                    <span>Réduction :</span>
                                    <span id="discountAmount">-0.00€</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold total-row">
                                    <span>Total TTC :</span>
                                    <span id="total">0.00€</span>
                                </div>
                            </div>

                            <?php wp_nonce_field('submit_order', 'order_nonce'); ?>
                            <input type="hidden" name="submit_order" value="1">
                            <button type="submit" class="btn btn-danger w-100 py-2 submit-btn">
                                <i class="fas fa-check-circle me-2"></i>Valider la commande
                            </button>
                        </div>
                    </div>

                    <!-- Retrait en magasin -->
                    <div class="store-info p-4 rounded">
                        <h3 class="store-title">Retrait en magasin</h3>
                        <?php echo do_shortcode('[leaflet-map lat=44.52814856454269 lng=3.4766571204239853 zoom=12]'); ?>
                        <h4 class="mt-3">Horaires :</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-clock me-2"></i> Lundi - Vendredi : 9h-12h / 14h-18h</li>
                            <li><i class="fas fa-clock me-2"></i> Samedi : 9h-12h</li>
                        </ul>
                        <h4 class="mt-3">Adresse :</h4>
                        <p>Pépinière d'entreprises POLeN<br>Rue Albert Einstein, 48000 Mende</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>