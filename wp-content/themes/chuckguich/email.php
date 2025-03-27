<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articles = isset($_POST['articles']) ? $_POST['articles'] : [];
    $prenom = isset($_POST['firstName']) ? sanitize_text_field($_POST['firstName']) : '';
    $nom = isset($_POST['lastName']) ? sanitize_text_field($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

    // Validation des données
    if (empty($prenom) || empty($nom) || empty($email)) {
        echo 'Veuillez remplir tous les champs obligatoires.';
        return;
    }

   // Traitement de la commande (exemple : envoi d'un email)
$to = $email; // Définit l'adresse email du destinataire.
$sujet = 'Confirmation de votre commande'; // Sujet de l'email.
$message = "Bonjour $prenom $nom,\n\nVoici les articles que vous avez commandés :\n" . implode("\n", $articles); // Contenu de l'email, listant les articles commandés.
$headers = ['From: votre-site@example.com', 'Content-Type: text/plain; charset=UTF-8']; // En-têtes de l'email.

wp_mail($to, $sujet, $message, $headers); // Envoi de l'email via la fonction WordPress `wp_mail()`.

echo 'Votre commande a été enregistrée avec succès !'; // Message de confirmation affiché à l'utilisateur.
} // Problème : une accolade fermante ici sans accolade ouvrante correspondante.

// Validation des entrées utilisateur.
/*$errors = []; // Initialisation d'un tableau pour stocker les erreurs.

// Vérification des champs obligatoires.
if (!$email) $errors[] = "Adresse email invalide"; // Vérifie si l'email est défini et valide.
if (empty($prenom)) $errors[] = "Prénom requis"; // Vérifie si le prénom est renseigné.
if (empty($nom)) $errors[] = "Nom requis"; // Vérifie si le nom est renseigné.

header('Content-Type: application/json'); // Définit le type de contenu en JSON pour la réponse.

if (!empty($errors)) { // Si des erreurs sont détectées :
    http_response_code(400); // Retourne un code HTTP 400 (Bad Request).
    echo json_encode(['success' => false, 'errors' => $errors]); // Renvoie la liste des erreurs au format JSON.
    exit; // Arrête l'exécution du script.
}

// Préparation de l'email de confirmation.
$to = $email; // Réaffectation de l'adresse email du destinataire.
$subject = 'Confirmation de votre commande'; // Sujet de l'email.
$message = "Bonjour {$prenom} {$nom},\n\nVotre commande a été enregistrée avec succès.\n\nCordialement,\nL'équipe."; // Contenu de l'email.
$headers = [ // Définition des en-têtes.
    'From: contact@machin.fr',
    'Content-Type: text/plain; charset=UTF-8'
];

if (mail($to, $subject, $message, implode("\r\n", $headers))) { // Envoi de l'email avec la fonction PHP `mail()`.
    echo json_encode(['success' => true, 'message' => "Email envoyé à {$email}"]); // Réponse JSON en cas de succès.
} else { // Si l'envoi échoue :
    http_response_code(500); // Retourne un code HTTP 500 (Internal Server Error).
    echo json_encode(['success' => false, 'errors' => ["Erreur d'envoi de l'email"]]); // Renvoie une erreur en JSON.
}
} // Problème : accolade fermante supplémentaire.

?>


