<article id="post-<?php the_ID(); ?>" <?php post_class('single-entry'); ?>>
    <div class="single-entry-data">
        <h2 class="single-entry-data__title"><?php the_title(); ?></h2>

        <ul class="single-entry-data__meta">
            <?php
            // Helper function to display meta items
            function display_meta_item($label, $value)
            {
                $slug = esc_attr(strtolower(remove_accents($label)));
                if ($value) {
                    echo '<li class="single-entry-data__meta--' . $slug . '">' . esc_html($label) . ' : ' . esc_html($value) . '</li>';
                } else {
                    echo '<li class="single-entry-data__meta--' . $slug . '">' . esc_html($label) . ' : Non spécifié</li>';
                }
            }

            // Display Reference
            $reference = get_post_meta(get_the_ID(), 'reference', true);
            display_meta_item('Référence', $reference);

            // Function to retrieve and display taxonomy terms
            function display_taxonomy_terms($post_id, $taxonomy, $label)
            {
                $terms = get_the_terms($post_id, $taxonomy);
                if ($terms && !is_wp_error($terms)) {
                    $term_list = array_map(function ($term) {
                        return esc_html(remove_accents($term->name));
                    }, $terms);
                    if (!empty($term_list)) {
                        echo '<li class="single-entry-data__meta--' . esc_attr(strtolower(remove_accents($taxonomy))) . '">' . esc_html($label) . ' : ' . implode(', ', $term_list) . '</li>';
                    } else {
                        echo '<li class="single-entry-data__meta--' . esc_attr(strtolower(remove_accents($taxonomy))) . '">' . esc_html($label) . ' : Non spécifiée</li>';
                    }
                } else {
                    echo '<li class="single-entry-data__meta--' . esc_attr(strtolower(remove_accents($taxonomy))) . '">' . esc_html($label) . ' : Non spécifiée</li>';
                }
            }

            // Display Category and Format
            display_taxonomy_terms(get_the_ID(), 'motaphoto-category', 'Catégorie');
            display_taxonomy_terms(get_the_ID(), 'format', 'Format');

            // Display Type
            $type = get_post_meta(get_the_ID(), 'type', true);
            display_meta_item('Type', $type);

            // Display Year
            display_meta_item('Année', get_the_date('Y'));
            ?>
        </ul><!-- .single-entry__meta -->
    </div><!-- .single-entry -->

    <?php if (has_post_thumbnail()) : ?>
        <div class="single-entry-media">
            <?php
            $thumbnail_id = get_post_thumbnail_id(get_the_ID()); // Get the ID of the featured image
            $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); // Retrieve the alt text

            // If the alt text is empty, fall back to the post title
            if (empty($alt_text)) {
                $alt_text = get_the_title(); // Use the post title as the alt text
            }

            the_post_thumbnail('large', [
                'class' => 'single-entry-media__image',
                'alt' => esc_attr($alt_text), // Use the alt text here
                'loading' => 'lazy'
            ]);
            ?>
        </div><!-- .entry__media -->
    <?php else : ?>
        <div class="single-entry-media">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/fallback.jpg'); ?>"
                alt="Default Image" class="entry--media__image" />
        </div><!-- .entry-media -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->