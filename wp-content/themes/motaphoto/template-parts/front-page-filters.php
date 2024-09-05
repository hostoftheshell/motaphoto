<?php
// Generate a nonce for the AJAX request
$ajax_nonce = wp_create_nonce('filter_results_nonce');

// Set a default value for photos per page
$photos_per_page = 8; // Adjust this value as needed

// Start the form and set the action attribute to the WordPress AJAX URL
echo '<form id="filter-form" action="' . esc_url(admin_url('admin-ajax.php')) . '" method="post">';

// Get the first taxonomy terms (motaphoto-category)
$taxonomies = get_terms(array(
    'taxonomy' => 'motaphoto-category',
    'hide_empty' => false
));

// Get the taxonomy object to retrieve the labels
$taxonomy_obj = get_taxonomy('motaphoto-category');

if (!empty($taxonomies)) :
    // Start the select element for the first taxonomy
    $output = '<select name="motaphoto_category" class="js-select2">';

    // Add a placeholder option if needed (optional)
    $output .= '<option value="">' . esc_attr($taxonomy_obj->labels->name) . '</option>';

    // Loop through the terms
    foreach ($taxonomies as $category) {
        if ($category->parent == 0) { // Check if it’s a top-level category
            $output .= '<option value="'. esc_attr($category->term_id) .'">'
                    . esc_html($category->name) .'</option>';
        }
    }

    $output .= '</select>';
    echo $output;
endif;

// Get the second taxonomy terms (format)
$taxonomies = get_terms(array(
    'taxonomy' => 'format',
    'hide_empty' => false
));

// Get the taxonomy object to retrieve the labels
$taxonomy_obj = get_taxonomy('format');

if (!empty($taxonomies)) :
    // Start the select element for the second taxonomy
    $output = '<select name="format" class="js-select2">';

    // Add a placeholder option if needed (optional)
    $output .= '<option value="">' . esc_attr($taxonomy_obj->labels->name) . '</option>';

    // Loop through the terms
    foreach ($taxonomies as $category) {
        if ($category->parent == 0) { // Check if it’s a top-level category
            $output .= '<option value="'. esc_attr($category->term_id) .'">'
                    . esc_html(ucwords($category->name)) .'</option>';
        }
    }

    $output .= '</select>';
    echo $output;
endif;

// Add a sorting select element
echo '<select name="sort_order" class="js-select2">';
echo '<option value="">Trier par</option>';
echo '<option value="asc">A partir des plus récentes</option>';
echo '<option value="desc">A partir des plus anciennes</option>';
echo '</select>';

// Include the photos_per_page in a hidden field
echo '<input type="hidden" name="photos_per_page" value="' . esc_attr($photos_per_page) . '">';

// Include the nonce in a hidden field
echo '<input type="hidden" name="filter_nonce" value="' . esc_attr($ajax_nonce) . '">';

// Specify the action for the AJAX request
echo '<input type="hidden" name="action" value="filter_results">';

// End the form
echo '</form>';
?>
