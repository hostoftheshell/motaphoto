<?php
$args = array(
    'post_type'      => 'photography',
    'posts_per_page' => 1,
    'orderby'        => 'rand',
    'post_status'    => 'publish',
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post(); ?>

        <?php if (has_post_thumbnail()) :
            $thumbnail_id = get_post_thumbnail_id(get_the_ID()); // Get the ID of the featured image
            $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); // Retrieve the alt text
            if (empty($alt_text)) {
                $alt_text = get_the_title(); // Use the post title as the alt text
            }
            ?>
            <div class="hero-banner">
                <?php the_post_thumbnail('full', [
                    'class' => 'hero-banner__image',
                    'alt'   => esc_attr($alt_text), // Use the alt text here
                    'loading' => 'lazy'
                ]); // Display the hero image 
                ?>
                <h1 class="hero-banner__title"><?php echo esc_html(get_bloginfo('description')); ?></h1>
            </div>
        <?php endif; // End if (has_post_thumbnail()) ?>

    <?php endwhile;
    wp_reset_postdata();
endif;
?>
