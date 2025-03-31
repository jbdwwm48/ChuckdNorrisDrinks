<?php
/*
Template Name: Contact
*/
get_header();
?>

<main class="container my-3">
    <h1>Contactez-nous</h1>
    <p>Vous avez une question, une demande spéciale ou simplement envie de partager une histoire incroyable avec Chuck Norris ? Remplissez le formulaire ci-dessous. Mais attention, Chuck Norris est très occupé à sauver le monde et à donner des cours de roundhouse kick à des ours polaires. Il lira votre message quand il jugera que l’univers est prêt.</p>
    
    <form action="" method="post" class="mb-3">
        <div class="mb-3">
            <label for="name" class="form-label">Nom :</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message :</label>
            <textarea name="message" id="message" class="form-control" required></textarea>
        </div>
        <input type="submit" value="Envoyer" class="btn btn-chuck-red">
    </form>
    
    <?php echo do_shortcode('[chuck_norris_fact]'); ?>
</main>

<?php get_footer(); ?>