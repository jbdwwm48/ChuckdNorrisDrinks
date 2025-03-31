<footer class="site-footer mt-auto" style="position:relative; bottom:0">
    <div class="footer-container container-fluid text-center py-3">
        <div class="d-flex justify-content-center align-items-center gap-3">
            <p class="mb-0">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
            </p>
            <a href="/politique-de-confidentialite" class="text-decoration-none text-white">
                Politique de confidentialité
            </a>
            <a href="/contact" class="text-decoration-none text-white">
                Contact
            </a>
        </div>
    </div>
    <?php wp_footer(); ?>
</footer>