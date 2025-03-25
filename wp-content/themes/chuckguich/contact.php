<?php
/* Template Name: Contact */
get_header(); ?>
<main>
    <div class="container">
        <h1>Contactez-nous</h1>
        <form action="" method="post">
            <label for="name">Nom:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea>

            <input type="submit" value="Envoyer">
        </form>
    </div>
</main>
<?php get_footer(); ?>
