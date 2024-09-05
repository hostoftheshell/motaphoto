<?php

// Ajouter des colonnes personnalisées à la table de liste du CPT "Photography"
add_filter('manage_photography_posts_columns', function($columns) {
    return [
        'cb' => $columns['cb'],
        'title' => $columns['title'],
        'thumbnail' => __('Miniature'),
        'reference' => __('Référence'),
        'category' => __('Catégorie'),
        'format' => __('Format'),
        'type' => __('Type'),
        'date' => $columns['date'],
    ];
});

// Remplir les colonnes personnalisées avec du contenu
add_action('manage_photography_posts_custom_column', function($column, $postId) {
    switch ($column) {
        case 'thumbnail':
            echo get_the_post_thumbnail($postId, 'thumbnail');
            break;
        case 'reference':
            echo esc_html(get_post_meta($postId, 'reference', true));
            break;
        case 'category':
            $terms = get_the_terms($postId, 'motaphoto-category');
            if ($terms && !is_wp_error($terms)) {
                echo esc_html(join(', ', wp_list_pluck($terms, 'name')));
            }
            break;
        case 'format':
            $terms = get_the_terms($postId, 'format');
            if ($terms && !is_wp_error($terms)) {
                echo esc_html(join(', ', wp_list_pluck($terms, 'name')));
            }
            break;
        case 'type':
            echo esc_html(get_post_meta($postId, 'type', true));
            break;
    }
}, 10, 2);

// Rendre les colonnes personnalisées triables
add_filter('manage_edit-photography_sortable_columns', function($columns) {
    $columns['reference'] = 'reference';
    $columns['type'] = 'type';
    return $columns;
});

// Gérer le tri par colonnes personnalisées
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
