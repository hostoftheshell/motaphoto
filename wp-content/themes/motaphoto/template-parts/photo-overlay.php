<div class="photo-overlay">
    <div class="photo-overlay__top">
        <div class="photo-overlay__icon-lightbox">
            <?php
            $post_id = get_the_ID(); // Get current post ID
            $post_title = get_the_title($post_id);
            $terms = get_the_terms($post_id, 'motaphoto-category'); // Replace 'motaphoto-category' with your actual taxonomy name
            $category = $terms ? implode(', ', wp_list_pluck($terms, 'name')) : 'No category';
            $thumbnail_id = get_post_thumbnail_id($post_id); // Get the thumbnail ID
            $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full')[0]; // Get the URL of the 'full' size image
            $lightbox_url = wp_get_attachment_image_src($thumbnail_id, 'lightbox')[0]; // Get the URL of the 'lightbox' size image
            $reference = get_field('reference', $post_id); // Fetch the reference
            $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) ?: $post_title;
            $images = json_encode(array(
                array('url' => $thumbnail_url, 'lightbox' => $lightbox_url, 'alt' => esc_attr($alt_text), 'title' => $post_title),
            ));
            ?>
            <a href="<?php echo esc_url($lightbox_url); ?>"
            id="open-lightbox"    
            class="lightbox-trigger"
                title="Voir dans la Lightbox"
                data-images='<?php echo $images; ?>'
                data-post-id="<?php echo $post_id; ?>"
                data-post-title="<?php echo esc_attr($post_title); ?>" 
                data-category="<?php echo esc_attr($category); ?>"
                data-reference="<?php echo esc_attr($reference); ?>">
                <?php
                $icon_lightbox_image_path = get_stylesheet_directory_uri() . '/assets/images/icon-lightbox.svg';
                echo file_get_contents($icon_lightbox_image_path);
                ?>
            </a>
        </div>
    </div>
    <div class="photo-overlay__center">
        <div class="photo-overlay__icon-view-details">
            <a href="<?php echo get_permalink($post_id); ?>" title="Voir les détails de la photo">
                <?php
                $icon_view_details_image_path = get_stylesheet_directory_uri() . '/assets/images/icon-view-details.svg';
                echo file_get_contents($icon_view_details_image_path);
                ?>
            </a>
        </div>
    </div>
    <div class="photo-overlay__bottom">
        <div class="photo-overlay__info"><?php echo esc_html($post_title); ?></div>
        <?php if ($terms && !is_wp_error($terms)) : ?>
            <div class="photo-overlay__info">
                <?php foreach ($terms as $term) : ?>
                    <span><?php echo esc_html($term->name); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
