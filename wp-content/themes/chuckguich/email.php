<?php
function send_order_confirmation($data) {
    $to_customer = $data['email'];
    $to_admin = 'jb.charbonnier.dwwm48@gmail.com';
    $subject = 'Confirmation de commande #' . uniqid();
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Chuck Saucisse <no-reply@chucksaucisse.com>'
    );

    ob_start();
    ?>
    <h2>Merci pour votre commande, <?php echo esc_html($data['first_name']); ?> !</h2>
    <p>Détails de votre commande :</p>
    <ul>
    <?php foreach ($data['products'] as $product) : ?>
        <li><?php echo esc_html($product['quantity']); ?> x <?php echo esc_html($product['name']); ?> - <?php echo esc_html($product['price']); ?>€</li>
    <?php endforeach; ?>
    </ul>
    <?php if ($data['discount_type'] && $data['discount_value']) : ?>
        <p>Réduction appliquée : <?php echo $data['discount_type'] === 'percentage' ? $data['discount_value'] . '%' : $data['discount_value'] . '€'; ?></p>
    <?php endif; ?>
    <p>Retrait à : Pépinière d'entreprises POLeN, Rue Albert Einstein, 48000 Mende</p>
    <p>Horaires : Lundi - Vendredi 9h-12h / 14h-18h, Samedi 9h-12h</p>
    <?php
    $message = ob_get_clean();

    $sent_customer = wp_mail($to_customer, $subject, $message, $headers);
    $sent_admin = wp_mail($to_admin, $subject, $message, $headers);

    error_log('Email customer sent: ' . ($sent_customer ? 'Yes' : 'No'));
    error_log('Email admin sent: ' . ($sent_admin ? 'Yes' : 'No'));

    if (!$sent_customer || !$sent_admin) {
        $order_error = 'Erreur lors de l\'envoi des emails';
        return;
    }
}
?>