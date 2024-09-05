<?php
function motaphoto_enqueue_assets()
{
    // Enqueue la feuille de style du thème avec versionnage
    $stylesheet_path = '/assets/css/theme.css';
    $stylesheet_uri = get_stylesheet_directory_uri() . $stylesheet_path;
    $stylesheet_version = filemtime(get_stylesheet_directory() . $stylesheet_path);
    wp_enqueue_style('theme-style', $stylesheet_uri, array(), $stylesheet_version);

    // Enqueue le script principal du thème avec versionnage
    $script_path = '/assets/js/main/main.min.js'; // Corrected path
    $script_uri = get_stylesheet_directory_uri() . $script_path;
    $script_version = filemtime(get_stylesheet_directory() . $script_path);
    wp_enqueue_script('theme-main-script', $script_uri, array(), $script_version, true);

    // Enqueue le script de la page single-photography avec versionnage
    if (is_single() && is_singular()) {
        $script_path = '/assets/js/single-photography/single-photography.min.js'; // Corrected path
        $script_uri = get_stylesheet_directory_uri() . $script_path;
        $script_version = filemtime(get_stylesheet_directory() . $script_path);
        wp_enqueue_script('single-photography-script', $script_uri, array(), $script_version, true);
    }

    // Enqueue Font Awesome
    wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/541f29ae56.js', array(), null, true);

    // Enqueue le fichier JavaScript pour les filtres Ajax
    wp_enqueue_script('ajax-filters', get_template_directory_uri() . '/assets/js/jquery/jquery.min.js', array('jquery'), null, true);

    // Passer l'URL AJAX et le nonce au script des filtres Ajax
    wp_localize_script('ajax-filters', 'ajax_filters_params', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('filter_results_nonce')
    ));

    if (is_front_page()) {
        // Enqueue Select2 CSS et JavaScript
        wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');
        wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);

        // Enqueue le script d'initialisation personnalisé de Select2
        wp_enqueue_script('js-select2', get_template_directory_uri() . '/assets/js/front-page/front-page.min.js', array('jquery', 'select2-js'), null, true);
    }
}

add_action('wp_enqueue_scripts', 'motaphoto_enqueue_assets');
