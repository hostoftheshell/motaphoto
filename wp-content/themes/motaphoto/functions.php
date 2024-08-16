<?php

function motaphoto_enqueue_assets() {
    // Define the path and version for the stylesheet
    $stylesheet_path = '/assets/css/theme.css';
    $stylesheet_uri = get_stylesheet_directory_uri() . $stylesheet_path;
    $stylesheet_version = filemtime(get_stylesheet_directory() . $stylesheet_path);
    
    // Enqueue the main theme stylesheet with versioning
    wp_enqueue_style('theme-style', $stylesheet_uri, array(), $stylesheet_version);
    
    // Define the path and version for the JavaScript file
    $script_path = '/assets/js/main.min.js';
    $script_uri = get_stylesheet_directory_uri() . $script_path;
    $script_version = '1.0.0';
    
    // Enqueue the custom JavaScript file
    wp_enqueue_script('theme-main-script', $script_uri, array(), $script_version, true);

    // Enqueue Font Awesome
    wp_enqueue_script(
        'font-awesome', 
        'https://kit.fontawesome.com/541f29ae56.js', 
        array(), 
        null, 
        true
    );
}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_assets');


function motaphoto_setup() {
    // Add support for title tag
    add_theme_support('title-tag');
    // Add support for the menus
    register_nav_menus( array(
        'primary_menu' => __('Primary Menu', 'motaphoto'),
        'footer_menu' => __('Footer Menu', 'motaphoto'),
    ) );
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 44,
        'width'       => 690,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'motaphoto_setup');

function contact_btn( $items, $args ) {
    // Check if we are targeting a specific menu location (optional)
    if ( 'primary_menu' === $args->theme_location ) {
        // Allow button text to be filtered
        $button_text = apply_filters( 'contact_btn_text', 'Contact' );
        
        // Build the contact button HTML
        $contact_button = sprintf(
            '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#" class="contact-btn" id="contact-popup">%s</a></li>',
            esc_html( $button_text )
        );
        
        // Append the contact button to the existing menu items
        $items .= $contact_button;
    }

    return $items;
}
add_filter( 'wp_nav_menu_items', 'contact_btn', 10, 2 );

// Optional: Add a filter hook for the button text
function custom_contact_button_text( $text ) {
    return strtoupper('contact');
}
add_filter( 'contact_btn_text', 'custom_contact_button_text' );



function motaphoto_get_reference_value_shortcode() {
    return "hello";
}
add_shortcode('motaphoto_reference_value', 'motaphoto_get_reference_value_shortcode');


add_filter('wpcf7_form_elements', 'insert_dynamic_value_into_photo_references');

function insert_dynamic_value_into_photo_references($content) {
    // Get the dynamic content from the shortcode
    $photo_references_value = do_shortcode('[motaphoto_reference_value]');
    
    // Pattern to find the specific input field
    $pattern = '/(<input [^>]*id="photo-references"[^>]* value=")([^"]*)("[^>]*>)/';
    
    // Replace the value attribute with the dynamic content
    $replacement = '$1' . esc_attr($photo_references_value) . '$3';
    $content = preg_replace($pattern, $replacement, $content);
    
    return $content;
}



require_once('options/copyright.php');
// Instantiate the class and register the actions
if (class_exists('CopyrightMenuPage')) {
    CopyrightMenuPage::register();
}


function motaphoto_add_copyright_to_footer_menu($items, $args) {
    // Check if the current menu being processed is the 'footer_menu'
    if ($args->theme_location == 'footer_menu') {
        // Add your custom HTML at the end of the menu items
        $items .= '<li class="menu-item">' . esc_attr(get_option('motaphoto_copyright')) . '</li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'motaphoto_add_copyright_to_footer_menu', 10, 2);