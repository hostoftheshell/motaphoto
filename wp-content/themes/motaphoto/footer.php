<?php

/**
 * Le modèle pour afficher le pied de page.
 *
 * @package thème WordPress motaphoto
 */


?>
</main>
<footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
    wp_nav_menu(array(
        'theme_location'  => 'footer_menu',
        'menu_class'      => 'site-footer__navigation--menu',
        'container'       => 'nav',
        'container_class' => 'site-footer__navigation',
    ));
    ?>
</footer>
</div>
<?php get_template_part('template-parts/contact', 'modal'); ?>
<?php wp_footer(); ?>
</body>

</html>