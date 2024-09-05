<?php

// Générer le HTML pour le bouton de contact
function get_contact_button_html($in_nav_menu = false) {
    $button_text = apply_filters('contact_btn_text', 'Contact');
    $button_class = $in_nav_menu ? 'menu-contact__btn' : 'contact-btn';
    
    if (is_single() && !$in_nav_menu) {
        $button_class = 'single-contact__btn';
    }

    return sprintf(
        '<a href="#" class="%s" id="contact-popup">%s</a>',
        esc_html($button_class),
        esc_html($button_text)
    );
}

// Ajouter le bouton de contact au menu principal
function contact_btn($items, $args) {
    if ('primary_menu' === $args->theme_location) {
        $contact_button = sprintf(
            '<li class="menu-item menu-item-type-custom menu-item-object-custom">%s</li>',
            get_contact_button_html(true)
        );
        $items .= $contact_button;
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'contact_btn', 10, 2);

// Texte personnalisé pour le bouton de contact
function custom_contact_button_text($text) {
    return ucfirst(strtolower($text));
}
add_filter('contact_btn_text', 'custom_contact_button_text');

// Récupérer la valeur du champ personnalisé 'reference'
function motaphoto_get_reference_value() {
    global $post;
    $reference = get_post_meta($post->ID, 'reference', true);
    return !empty($reference) ? esc_html($reference) : 'Reference non spécifiée';
}

// Shortcode pour afficher la valeur de 'reference'
function motaphoto_get_reference_value_shortcode() {
    return motaphoto_get_reference_value();
}
add_shortcode('motaphoto_reference', 'motaphoto_get_reference_value_shortcode');

// Insérer une valeur dynamique dans un champ de Contact Form 7
add_filter('wpcf7_form_elements', 'insert_dynamic_value_into_photo_references');
function insert_dynamic_value_into_photo_references($content) {
    $photo_references_value = do_shortcode('[motaphoto_reference]');
    $pattern = '/(<input [^>]*id="photo-references"[^>]* value=")([^"]*)("[^>]*>)/';
    $replacement = '$1' . esc_attr($photo_references_value) . '$3';
    return preg_replace($pattern, $replacement, $content);
}

// Ajouter les informations de copyright au menu de pied de page
function motaphoto_add_copyright_to_footer_menu($items, $args) {
    // Vérifier si le menu actuel est le 'footer_menu'
    if ($args->theme_location == 'footer_menu') {
        // Ajouter votre HTML personnalisé à la fin des éléments du menu
        $items .= '<li class="menu-item">' . esc_attr(get_option('motaphoto_copyright')) . '</li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'motaphoto_add_copyright_to_footer_menu', 10, 2);
