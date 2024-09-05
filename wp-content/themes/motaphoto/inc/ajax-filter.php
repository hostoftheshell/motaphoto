<?php
// Fonction pour filtrer les résultats en fonction des critères spécifiés
function filter_results_ajax() {
    // Vérifier le nonce pour la sécurité afin de s'assurer que la requête est légitime
    if (!isset($_POST['filter_nonce']) || !wp_verify_nonce($_POST['filter_nonce'], 'filter_results_nonce')) {
        wp_send_json_error('Nonce invalide');
        wp_die(); // Terminer le script après avoir envoyé une réponse
    }

    // Récupérer les valeurs sélectionnées depuis la requête POST et les assainir
    $motaphoto_category = isset($_POST['motaphoto_category']) ? sanitize_text_field($_POST['motaphoto_category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $sort_order = isset($_POST['sort_order']) ? sanitize_text_field($_POST['sort_order']) : 'asc';
    $photos_per_page = isset($_POST['photos_per_page']) ? intval($_POST['photos_per_page']) : 8;
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // Configurer les arguments de la requête WP_Query
    $args = array(
        'post_type' => 'photography', // Type de post personnalisé
        'orderby' => 'title', // Trier par titre
        'order' => $sort_order, // Ordre croissant ou décroissant
        'posts_per_page' => $photos_per_page, // Nombre de posts par page
        'paged' => $paged, // Numéro de la page actuelle pour la pagination
        'tax_query' => array(
            'relation' => 'AND', // Utiliser la relation "AND" pour les filtres de taxonomie
        ),
    );

    // Ajouter des filtres tax_query si une catégorie ou un format est sélectionné
    if ($motaphoto_category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'motaphoto-category', // Taxonomie pour les catégories
            'field' => 'term_id',
            'terms' => $motaphoto_category,
        );
    }

    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format', // Taxonomie pour le format
            'field' => 'term_id',
            'terms' => $format,
        );
    }

    // Exécuter la requête
    $query = new WP_Query($args);

    // Préparer les données de réponse
    $response_data = array(
        'posts_html' => '',
        'lightbox_images' => array(),
        'load_more_button' => ''
    );

    if ($query->have_posts()) {
        // Bufferiser le contenu HTML pour les posts
        ob_start();
        while ($query->have_posts()) : $query->the_post();
            $thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $image_url = wp_get_attachment_url($thumbnail_id);
            $lightbox_image_url = wp_get_attachment_image_src($thumbnail_id, 'lightbox')[0]; // URL pour la taille lightbox
            $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            $reference = get_field('reference', get_the_ID());
            if (empty($alt_text)) {
                $alt_text = get_the_title(); // Utiliser le titre si le texte alternatif est vide
            }

            // Ajouter les données de l'image à la réponse, y compris l'URL du post pour la navigation
            $response_data['lightbox_images'][] = array(
                'url' => $image_url,
                'lightbox' => $lightbox_image_url,
                'alt' => $alt_text,
                'title' => get_the_title(),
                'post_url' => get_permalink(),
                'reference' => $reference,
            );

            // Inclure le HTML pour le catalogue photo
            echo '<div class="photo-catalog__media">';
            echo '<a href="' . get_permalink() . '">';
            echo get_the_post_thumbnail(get_the_ID(), 'large', ['alt' => esc_attr($alt_text)]);
            echo '</a>';
            include locate_template('template-parts/photo-overlay.php'); // Inclure le modèle pour l'overlay
            echo '</div>';
    
        endwhile;
        $response_data['posts_html'] = ob_get_clean(); // Nettoyer et stocker le HTML

        // S'il y a plus de posts à charger, générer le bouton "Charger plus"
        if ($query->max_num_pages > $paged) {
            $response_data['load_more_button'] = '<button id="load-more" data-page="' . ($paged + 1) . '">Charger plus</button>';
        }
    } else {
        // Si aucun post n'est trouvé et que c'est la première page, afficher un message
        if ($paged === 1) {
            $response_data['posts_html'] = '<p>Aucun post trouvé.</p>';
        }
    }

    // Envoyer les données de réponse au format JSON
    wp_send_json_success($response_data);

    wp_die(); // Terminer le script après avoir envoyé la réponse
}

// Enregistrer les actions pour les requêtes AJAX
add_action('wp_ajax_filter_results', 'filter_results_ajax');
add_action('wp_ajax_nopriv_filter_results', 'filter_results_ajax');

// Fonction pour obtenir le post suivant
function get_next_post_ajax() {
    // Vérifier le nonce pour la sécurité
    if (!isset($_POST['filter_nonce']) || !wp_verify_nonce($_POST['filter_nonce'], 'filter_results_nonce')) {
        wp_send_json_error('Nonce invalide');
        wp_die();
    }

    $current_post_id = intval($_POST['current_post_id']);
    $current_post = get_post($current_post_id);

    if (!$current_post) {
        wp_send_json_error('ID de post invalide');
        wp_die();
    }

    // Requête pour trouver le post suivant
    $args = array(
        'post_type' => 'photography',
        'orderby' => 'date',
        'order' => 'ASC',
        'posts_per_page' => 1,
        'post__not_in' => array($current_post_id),
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        ),
        'fields' => 'ids',
        'post_status' => 'publish',
        'date_query' => array(
            array(
                'after' => $current_post->post_date,
                'inclusive' => true,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $next_post_id = $query->posts[0];
    } else {
        // Si aucun post suivant n'est trouvé, revenir au premier post
        $args['date_query'] = array(
            array(
                'before' => $current_post->post_date,
                'inclusive' => true,
            ),
        );

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            $posts = $query->posts;
            // Trier les posts pour obtenir le premier
            usort($posts, function($a, $b) {
                return get_post($a)->post_date <=> get_post($b)->post_date;
            });
            $next_post_id = $posts[0];
        } else {
            wp_send_json_error('Aucun post suivant disponible.');
            wp_die();
        }
    }

    // Obtenir les détails du post suivant
    $thumbnail_id = get_post_thumbnail_id($next_post_id);
    $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full')[0];
    $lightbox_url = wp_get_attachment_image_src($thumbnail_id, 'lightbox')[0];
    $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    $reference = get_field('reference', $next_post_id);

    // Récupérer les catégories
    $categories = get_the_terms($next_post_id, 'motaphoto-category');
    $category_names = array();
    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_names[] = $category->name;
        }
    }

    $response_data = array(
        'post_id' => $next_post_id,
        'post_title' => get_the_title($next_post_id),
        'category' => implode(', ', $category_names),
        'lightbox_images' => array(
            array(
                'url' => $thumbnail_url,
                'lightbox' => $lightbox_url,
                'alt' => $alt_text,
                'title' => get_the_title($next_post_id),
                'reference' => $reference ? $reference : 'Aucune référence disponible',
            )
        ),
    );

    wp_send_json_success($response_data);
    wp_die();
}

// Enregistrer les actions pour les requêtes AJAX
add_action('wp_ajax_get_next_post', 'get_next_post_ajax');
add_action('wp_ajax_nopriv_get_next_post', 'get_next_post_ajax');

function get_prev_post_ajax() {
    // Vérifier le nonce pour la sécurité
    if (!isset($_POST['filter_nonce']) || !wp_verify_nonce($_POST['filter_nonce'], 'filter_results_nonce')) {
        wp_send_json_error(array('message' => 'Nonce invalide'));
        wp_die();
    }

    $current_post_id = intval($_POST['current_post_id']);

    // Vérifiez que l'ID du post est valide
    if (!is_numeric($current_post_id) || $current_post_id <= 0) {
        wp_send_json_error(array('message' => 'ID de post invalide'));
        wp_die();
    }

    // Récupérer le post
    $current_post = get_post($current_post_id);

    // Vérifiez si le post existe
    if (!$current_post) {
        wp_send_json_error(array('message' => 'Le post avec cet ID n\'existe pas'));
        wp_die();
    }

    // Requête pour trouver le post précédent
    $args = array(
        'post_type' => 'photography',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => 1,
        'post__not_in' => array($current_post_id),
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        ),
        'fields' => 'ids',
        'post_status' => 'publish',
        'date_query' => array(
            array(
                'before' => $current_post->post_date,
                'inclusive' => true,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $prev_post_id = $query->posts[0];
        $thumbnail_id = get_post_thumbnail_id($prev_post_id);
        $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full')[0];
        $lightbox_url = wp_get_attachment_image_src($thumbnail_id, 'lightbox')[0];
        $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
        $reference = get_field('reference', $prev_post_id);

        // Récupérer les catégories
        $categories = get_the_terms($prev_post_id, 'motaphoto-category');
        $category_names = array();
        if ($categories && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $category_names[] = $category->name;
            }
        }

        // Préparer les données de réponse
        $response_data = array(
            'post_id' => $prev_post_id,
            'post_title' => get_the_title($prev_post_id),
            'category' => implode(', ', $category_names),
            'lightbox_images' => array(
                array(
                    'url' => $thumbnail_url,
                    'lightbox' => $lightbox_url,
                    'alt' => $alt_text,
                    'title' => get_the_title($prev_post_id),
                    'reference' => $reference ? $reference : 'Aucune référence disponible',
                )
            ),
            'has_prev_post' => true, // Indiquer qu'un post précédent est disponible
        );

        wp_send_json_success($response_data);
    } else {
        // Indiquer qu'aucun post précédent n'est disponible
        $response_data = array(
            'has_prev_post' => false,
            'message' => 'Aucun post précédent disponible.',
        );

        wp_send_json_success($response_data);
    }

    wp_die();
}

add_action('wp_ajax_get_prev_post', 'get_prev_post_ajax');
add_action('wp_ajax_nopriv_get_prev_post', 'get_prev_post_ajax');

// Fonction pour préparer les données de lightbox pour un post donné
function prepare_lightbox_data_for_post($post_id) {
    // Récupérer l'ID de la miniature et les URL associées
    $thumbnail_id = get_post_thumbnail_id($post_id);
    $image_url = wp_get_attachment_url($thumbnail_id);
    $lightbox_image_url = wp_get_attachment_image_src($thumbnail_id, 'lightbox')[0];
    $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) ?: get_the_title($post_id);
    $reference = get_post_meta($post_id, 'reference', true); // Obtenir la référence

    // Récupérer les catégories
    $categories = get_the_terms($post_id, 'category'); // Remplacer 'category' par votre taxonomie personnalisée si nécessaire
    $category_names = array();
    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_names[] = $category->name;
        }
    }

    // Retourner les données de lightbox
    return array(
        'lightbox_images' => array(
            array(
                'url' => $image_url,
                'lightbox' => $lightbox_image_url,
                'alt' => $alt_text,
                'title' => get_the_title($post_id),
                'post_id' => $post_id,
                'post_url' => get_permalink($post_id),
                'categories' => implode(', ', $category_names),
                'reference' => $reference,
            )
        )
    );
}
