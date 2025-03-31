<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f6fbfb; color: #0d0c0f; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #bc040d; color: #f6fbfb; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f6fbfb; }
        .footer { text-align: center; padding: 10px; background: #0d0c0f; color: #f6fbfb; font-size: 12px; }
        ul { list-style: none; padding: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Chuck Saucisse</h1>
            <h2>Confirmation de commande</h2>
        </div>
        <div class="content">
            <p>Merci <?php echo esc_html($email_data['first_name']); ?> ! Votre commande est bien enregistrée.</p>
            <h3>Récapitulatif :</h3>
            <ul>
                <?php foreach ($email_data['items'] as $item) : ?>
                    <li><?php echo esc_html($item['quantity']); ?> x <?php echo esc_html($item['name']); ?> - <?php echo esc_html($item['total']); ?>€</li>
                <?php endforeach; ?>
            </ul>
            <p>Total HT : <?php echo esc_html($email_data['subtotal']); ?>€</p>
            <p>TVA (20%) : <?php echo esc_html($email_data['tax']); ?>€</p>
            <?php if ($email_data['discount_amount']) : ?>
                <p>Réduction (<?php echo $email_data['discount_type'] === 'percentage' ? esc_html($email_data['discount_value']) . '%' : esc_html($email_data['discount_value']) . '€'; ?>) : -<?php echo esc_html($email_data['discount_amount']); ?>€</p>
            <?php endif; ?>
            <p><strong>Total TTC : <?php echo esc_html($email_data['total']); ?>€</strong></p>

            <p>Votre commande est prête à être récupérée dans notre point Click & Collect :</p>
            <div class="store-info">
                <h3>Retrait en magasin</h3>
                <h4>Horaires :</h4>
                <div><?php echo $email_data['store_hours']; ?></div>
                <h4>Adresse :</h4>
                <p><?php echo esc_html($email_data['store_address']); ?></p>
            </div>
        </div>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> Chuck Saucisse</p>
        </div>
    </div>
</body>
</html>