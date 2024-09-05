
<?php
$current_post_id = get_the_ID();
$categories = get_the_terms($current_post_id, 'motaphoto-category');

if ($categories && !is_wp_error($categories)) {
    $category_ids = wp_list_pluck($categories, 'term_id');

    $args_related = array(
        'post_type'      => 'photography',
        'posts_per_page' => 2,  // Adjust the number of photos to display as needed
        'orderby'        => 'rand',  // Randomize the results
        'post__not_in'   => array($current_post_id),  // Exclude the current post
        'tax_query'      => array(
            array(
                'taxonomy' => 'motaphoto-category',
                'field'    => 'term_id',
                'terms'    => $category_ids,
            ),
        ),
    );

    $query_related = new WP_Query($args_related); ?>
    <section class="single-related-photos">
    <h3 class="single-related-photos__title">Vous aimerez AUSSI</h3>
    <div class="single-related-photos__container">
    <?php
    if ($query_related->have_posts()) :
        while ($query_related->have_posts()) : $query_related->the_post();
            // Output photo details
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-related-photos__media'); ?>>
    
        <?php
        // Get the post title to use in the title attribute
        $post_title = get_the_title();

        // Get the post thumbnail ID
        $thumbnail_id = get_post_thumbnail_id();

        // Retrieve the alt text from the attachment metadata
        $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

        // Use the post title as a fallback if the alt text is empty
        $alt_text = !empty($alt_text) ? $alt_text : $post_title;
        $terms = get_the_terms(get_the_ID(), 'motaphoto-category');
        ?>
        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($post_title); ?>">
            <?php 
            // Display the post thumbnail with the retrieved alt text
            the_post_thumbnail('large', array(
                'class' => 'single-related-photos__image',
                'alt'   => esc_attr($alt_text) // Use the alt text here
            )); 
            ?>
        </a>
        <?php
        include locate_template('template-parts/photo-overlay.php');
        ?>

</article>
            <?php
        endwhile;
        wp_reset_postdata();  // Reset the global post object
    else :
        echo '<p>No related photos found.</p>';
    endif;
}
?>
</div>
    </section>
