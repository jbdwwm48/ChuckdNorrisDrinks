<?php
/**
 * Template Name: commande-templet
 * Description: page de commende
 */
require(get_template_directory() . '/email.php');
get_header(); ?>

<main>
    <div class="container">
        <h1><?php the_title(); ?></h1>

        <?php
    $args = array(
        'post_type' => "drinks"
    );
    // The Query.
    $the_query = new WP_Query($args);

    // Restore original Post Data.
    wp_reset_postdata();
    ?>
        <div class="content">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    the_content();
                endwhile;
            else :
                echo '<p>Aucun contenu trouvé.</p>';
            endif;
            ?>
        </div>
    </div>
</main>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #000;
            color: white;
        }
        .header {
            background-color: #555;
            text-align: center;
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .cart-container {
            background-color: black;
            padding: 20px;
            overflow-y: auto;
        }
        .form-container {
            background-color: #555;
            padding: 20px;
            border: 1px solid white;
        }
        .click-collect {
            background-color: #555;
            text-align: center;
            padding: 20px;
        }
        .total-and-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }
        .total {
            font-weight: bold;
            color: white;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .total span {
            display: inline-block;
        }
        #map {
            width: 100%;
            height: 200px;
            background: #ccc;
            border: 1px solid white;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Mon Click & Collect</h1>
    </header>
    <section class="cart-container">
        <div class="form-container">
            <h2 class="fw-bold">Formulaire Click & Collect</h2>
        <!-- Produits commandables -->
        <form id="collectForm" method="POST">
                <div id="articleSelection" class="row">
                    <p class="col-12">Choisissez vos articles :</p>
                    <?php
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            // Champs dynamiques pour chaque article
                            $prix = get_field('prix'); // Prix de l'article
                            $taille = get_field('taille'); // Taille ou autre attribut pertinent
                            $id = 'article_' . get_the_ID(); // ID unique basé sur l'ID WordPress
                    ?>
                           <div class="col-md-3 form-check">
                            <label class="form-check-label" for="<?php echo esc_attr($id); ?>">
                                <img 
                                    src="<?php echo esc_url(get_field('image_principale')); ?>" 
                                    alt="Image de <?php echo esc_html(get_the_title()); ?>" 
                                    style="width: 100px; height: 100px; object-fit: cover; display: block; margin: 10px auto;">
                                <?php echo esc_html(get_the_title()); ?> 
                                (<?php echo esc_html($taille); ?> - <?php echo esc_html($prix); ?>€)
                            </label>
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="<?php echo esc_attr($id); ?>"
                                name="articles[]"
                                value="<?php echo esc_attr(get_the_title()); ?>"
                                data-prix="<?php echo esc_attr($prix); ?>">
                            <select id="quantity_<?php echo esc_attr($id); ?>" class="form-select mt-2" disabled>
                                <option value="">Quantité</option>
                                <?php for ($i = 1; $i <= 10; $i++) : ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    <?php
                        } // Fin de la boucle
                    } else {
                        echo '<p>Aucun article disponible.</p>';
                    }
                    ?>
    </div>

    <!-- Données clients -->
    <div class="mt-3">
        <label for="firstName" class="form-label">Prénom :</label>
        <input type="text" id="firstName" class="form-control" required />
        <label for="lastName" class="form-label">Nom :</label>
        <input type="text" id="lastName" class="form-control" required />
        <label for="email" class="form-label">Email :</label>
        <input type="email" id="email" class="form-control" required />
    </div>

    <!-- Résumé commande à côté du bouton Valider -->
    <div class="total-and-button mt-4">
        <button type="button" id="validateButton" class="btn btn-light">Valider la commande</button>
        <div class="total">
            <span>Prix HT : <span id="priceHT">0€</span></span>
            <span>TVA (20%) : <span id="priceTVA">0€</span></span>
            <span>Prix TTC : <span id="priceTTC">0€</span></span>
            <span>Total : <span id="total">0€</span></span>
        </div>
    </div>
</form>

<!-- Carte Click & Collect -->
<section class="click-collect">
    <h2 class="fw-bold">Click & Collect</h2>
    <p>Choisissez votre point de retrait sur la carte :</p>
    <p>adresse : Pépinière d'entreprises POLeNRue Albert Einstein,48000 Mende</p>
    <div id="map"></div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialisation de la carte
        const map = L.map('map').setView([44.5201, 3.5019], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
        }).addTo(map);
        L.marker([44.5201, 3.5019]).addTo(map)
            .bindPopup('Votre point Click & Collect')
            .openPopup();

        // Gestion des cases à cocher et des sélecteurs de quantité
        const checkboxes = document.querySelectorAll('.form-check-input');

        // Fonction pour mettre à jour les totaux
        const updateSummary = () => {
            let totalHT = 0;
            const totalTVA_rate = 0.2; // Taux de TVA

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    const quantitySelect = document.getElementById(`quantity_${checkbox.id}`);
                    const prixHT = parseFloat(checkbox.dataset.prix) || 0; // Lire le prix HT depuis l'attribut data-prix
                    const quantity = parseInt(quantitySelect.value) || 0;

                    if (quantity > 0) {
                        totalHT += prixHT * quantity;
                    }
                }
            });

            // Calcul TVA et Total TTC
            const totalTVA = totalHT * totalTVA_rate;
            const totalTTC = totalHT + totalTVA;

            // Mise à jour de l'affichage des totaux
            document.getElementById("priceHT").textContent = totalHT.toFixed(2) + "€";
            document.getElementById("priceTVA").textContent = totalTVA.toFixed(2) + "€";
            document.getElementById("priceTTC").textContent = totalTTC.toFixed(2) + "€";
            document.getElementById("total").textContent = totalTTC.toFixed(2) + "€";
        };

        // Activer/Désactiver les sélecteurs de quantité et mettre à jour les totaux
        checkboxes.forEach((checkbox) => {
            const quantitySelect = document.getElementById(`quantity_${checkbox.id}`);
            checkbox.addEventListener('change', () => {
                quantitySelect.disabled = !checkbox.checked; // Activer ou désactiver le menu déroulant
                if (!checkbox.checked) {
                    quantitySelect.value = ""; // Réinitialise si décoché
                }
                updateSummary(); // Met à jour les totaux
            });

            // Écouter les changements dans les sélecteurs de quantité
            quantitySelect.addEventListener('change', updateSummary);
        });

        // Initialiser les totaux
        updateSummary();

        // Validation du formulaire
        const validateButton = document.getElementById("validateButton");
        validateButton.addEventListener('click', () => {
            const email = document.getElementById('email').value;
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;

            if (!email || !firstName || !lastName) {
                alert("Merci de remplir tous les champs requis avant de valider !");
                return;
            }

            const confirmed = confirm("Confirmez-vous l'envoi de votre commande ?");
            if (confirmed) {
                alert(`Votre commande a été validée ! Un email récapitulatif sera envoyé à : ${email}`);
            }
        });
    });
</script>
<?php get_footer(); ?>