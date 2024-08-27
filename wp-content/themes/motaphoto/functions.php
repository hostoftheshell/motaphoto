<?php

// Enqueue styles and scripts
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

// Theme setup
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
    add_theme_support( 'post-thumbnails', array(
        'photography'));

add_action('after_setup_theme', 'motaphoto_setup');

// Function to generate the contact button HTML
function get_contact_button_html($in_nav_menu = false) {
    // Allow button text to be filtered
    $button_text = apply_filters('contact_btn_text', 'Contact');

    // Determine the appropriate class based on the context
    $button_class = $in_nav_menu ? 'menu-contact__btn' : 'contact-btn'; // Default class
    if (is_single() && !$in_nav_menu) {
        $button_class = 'single-contact__btn';
    }

    // Build the contact button HTML
    $contact_button = sprintf(
        '<a href="#" class="%s" id="contact-popup">%s</a>',
        esc_html($button_class),
        esc_html($button_text)
    );

    return $contact_button;
}

// Add the contact button to the primary menu
function contact_btn( $items, $args ) {
    // Check if we are targeting a specific menu location (optional)
    if ( 'primary_menu' === $args->theme_location ) {
        // Wrap the button in a <li> element for the menu
        $contact_button = sprintf(
            '<li class="menu-item menu-item-type-custom menu-item-object-custom">%s</li>',
            get_contact_button_html(true)
        );

        // Append the contact button to the existing menu items
        $items .= $contact_button;
    }

    return $items;
}
add_filter( 'wp_nav_menu_items', 'contact_btn', 10, 2 );

// Optional: Add a filter hook for the button text
function custom_contact_button_text( $text ) {
    return ucfirst(strtolower($text));
}
add_filter( 'contact_btn_text', 'custom_contact_button_text' );


// Function to retrieve the 'reference' custom field value
function motaphoto_get_reference_value() {
    global $post;

    // Get the 'reference' custom field value for the current post
    $reference = get_post_meta($post->ID, 'reference', true);

    // Check if the reference value is empty
    if (!empty($reference)) {
        return esc_html($reference); // Return the sanitized reference value
    } else {
        return 'Reference non spécifiée'; // Return a fallback message if empty
    }
}

// Shortcode to display the 'reference' custom field value
function motaphoto_get_reference_value_shortcode() {
    return motaphoto_get_reference_value();
}
add_shortcode('motaphoto_reference', 'motaphoto_get_reference_value_shortcode');


// Insert dynamic value into the photo references field
add_filter('wpcf7_form_elements', 'insert_dynamic_value_into_photo_references');
function insert_dynamic_value_into_photo_references($content) {
    // Get the dynamic content from the shortcode
    $photo_references_value = do_shortcode('[motaphoto_reference]');
    
    // Pattern to find the specific input field
    $pattern = '/(<input [^>]*id="photo-references"[^>]* value=")([^"]*)("[^>]*>)/';
    
    // Replace the value attribute with the dynamic content
    $replacement = '$1' . esc_attr($photo_references_value) . '$3';
    $content = preg_replace($pattern, $replacement, $content);
    
    return $content;
}

// Add copyright information to the footer menu
function motaphoto_add_copyright_to_footer_menu($items, $args) {
    // Check if the current menu being processed is the 'footer_menu'
    if ($args->theme_location == 'footer_menu') {
        // Add your custom HTML at the end of the menu items
        $items .= '<li class="menu-item">' . esc_attr(get_option('motaphoto_copyright')) . '</li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'motaphoto_add_copyright_to_footer_menu', 10, 2);

// Include and instantiate the copyright options class
require_once('options/copyright.php');
if (class_exists('CopyrightMenuPage')) {
    CopyrightMenuPage::register();
}

function motaphoto_init() {
    register_post_type('photography', array(
        'labels' => array(
            'name' => __('Photos', 'motaphoto'),
            'singular_name' => __('Photo', 'motaphoto'),
            'add_new' => __('Add New', 'motaphoto'),
            'add_new_item' => __('Add New Photo', 'motaphoto'),
            'edit_item' => __('Edit Photo', 'motaphoto'),
            'new_item' => __('New Photo', 'motaphoto'),
            'view_item' => __('View Photo', 'motaphoto'),
            'search_items' => __('Search Photos', 'motaphoto'),
            'not_found' => __('No photos found', 'motaphoto'),
            'not_found_in_trash' => __('No photos found in Trash', 'motaphoto'),
            'all_items' => __('All Photos', 'motaphoto'),
            'archives' => __('Photo Archives', 'motaphoto'),
            'attributes' => __('Photo Attributes', 'motaphoto'),
            'insert_into_item' => __('Insert into photo', 'motaphoto'),
            'uploaded_to_this_item' => __('Uploaded to this photo', 'motaphoto'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_position' => 4,
        'menu_icon' => 'dashicons-camera',
        'rewrite' => array('slug' => 'photo', 'with_front' => false),
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'query_var' => true,
    ));
}
add_action('init', 'motaphoto_init');

// Add custom columns to the Photography CPT list table
add_filter('manage_photography_posts_columns', function($columns){
    return [
        'cb' => $columns['cb'],          // Checkbox for bulk actions
        'title' => $columns['title'],    // Title column
        'thumbnail' => __('Miniature'),  // Custom Thumbnail column
        'reference' => __('Référence'),  // Custom Reference column
        'category' => __('Catégorie'),   // Custom Category column (Taxonomy)
        'format' => __('Format'),        // Custom Format column (Taxonomy)
        'type' => __('Type'),            // Custom Type column
        'date' => $columns['date']       // Date column
    ];
});

// Populate the custom columns with content
add_action('manage_photography_posts_custom_column', function($column, $postId){
    switch ($column) {
        case 'thumbnail':
            echo get_the_post_thumbnail($postId, 'thumbnail');  // Display the post thumbnail
            break;
        
        case 'reference':
            $reference = get_post_meta($postId, 'reference', true);
            echo esc_html($reference);
            break;
        
        case 'category':
            $terms = get_the_terms($postId, 'motaphoto-category');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(join(', ', $term_names));
            }
            break;

        case 'format':
            $terms = get_the_terms($postId, 'format');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(join(', ', $term_names));
            }
            break;
        
        case 'type':
            $type = get_post_meta($postId, 'type', true);
            echo esc_html($type);
            break;

        // Add more cases for other custom columns as needed
        
        default:
            break;
    }
}, 10, 2);

// Make custom columns sortable
add_filter('manage_edit-photography_sortable_columns', function($columns) {
    $columns['reference'] = 'reference';
    $columns['type'] = 'type';
    return $columns;
});

// Handle sorting by custom columns
add_action('pre_get_posts', function($query) {
    if (!is_admin()) {
        return;
    }
    $orderby = $query->get('orderby');
    if ('reference' === $orderby) {
        $query->set('meta_key', 'reference');
        $query->set('orderby', 'meta_value');
    } elseif ('type' === $orderby) {
        $query->set('meta_key', 'type');
        $query->set('orderby', 'meta_value');
    }
});
?>
