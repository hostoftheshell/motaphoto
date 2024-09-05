<?php

function motaphoto_setup() {
    // Ajouter le support pour la balise de titre
    add_theme_support('title-tag');
    
    // Ajouter le support pour les menus
    register_nav_menus(array(
        'primary_menu' => __('Menu Principal', 'motaphoto'),
        'footer_menu' => __('Menu du Pied de Page', 'motaphoto'),
    ));
    
    // Ajouter le support pour le logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 44,
        'width'       => 690,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Ajouter le support pour les vignettes de publication
    add_theme_support('post-thumbnails', array('photography'));

    // Ajouter une taille d'image
    add_image_size('lightbox', 866, 0, false);
}

add_action('after_setup_theme', 'motaphoto_setup');
