<?php

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
