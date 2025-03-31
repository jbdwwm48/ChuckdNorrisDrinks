<?php
error_log('POST Data avant vérification: ' . print_r($_POST, true));

if (!isset($_POST['order_nonce']) || !wp_verify_nonce($_POST['order_nonce'], 'submit_order')) {
    $order_error = 'Erreur de sécurité';
    error_log('Nonce échoué - isset: ' . (isset($_POST['order_nonce']) ? 'Oui' : 'Non') . ', valeur: ' . ($_POST['order_nonce'] ?? 'non défini'));
    return;
}

$required_fields = array('first_name', 'last_name', 'email');
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $order_error = 'Tous les champs sont obligatoires';
        return;
    }
}

if (!isset($_POST['products']) || !is_array($_POST['products']) || empty($_POST['products'])) {
    $order_error = 'Aucun produit sélectionné dans le formulaire';
    return;
}

global $order_data;
$order_data = array(
    'first_name' => sanitize_text_field($_POST['first_name']),
    'last_name' => sanitize_text_field($_POST['last_name']),
    'email' => sanitize_email($_POST['email']),
    'products' => array(),
    'discount_type' => sanitize_text_field($_POST['discount_type'] ?? ''),
    'discount_value' => floatval($_POST['discount_value'] ?? 0)
);

$has_valid_products = false;
foreach ($_POST['products'] as $product_id => $data) {
    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
    if ($quantity <= 0) continue;

    $stock = (int)get_field('stock', $product_id);
    if ($stock < $quantity) {
        $order_error = 'Stock insuffisant pour ' . get_the_title($product_id);
        return;
    }

    $new_stock = $stock - $quantity;
    update_field('stock', $new_stock, $product_id);

    $order_data['products'][] = array(
        'id' => $product_id,
        'name' => get_the_title($product_id),
        'quantity' => $quantity,
        'price' => (float)$data['price']
    );
    $has_valid_products = true;
}

if (!$has_valid_products) {
    $order_error = 'Aucun produit valide sélectionné';
    return;
}

require_once get_template_directory() . '/email.php';
send_order_confirmation($order_data);

$order_success = true;
?>