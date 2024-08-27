<div class="single-navigation">
<div class="post-thumbnail-preview">
        <img src="" alt="">
    </div>
<div class="single-navigation__arrow-container">
    <?php
    // Retrieve previous and next posts
    $next_post = get_next_post();
    $previous_post = get_previous_post();

    // Function to get a specific post based on order
    function get_specific_post($order, $post_type)
    {
        $args = array(
            'posts_per_page' => 1,
            'order'          => $order,
            'post_type'      => $post_type
        );
        $posts = get_posts($args);
        return !empty($posts) ? $posts[0] : null;
    }

    // If there's no next post, get the first post
    if (empty($next_post)) {
        $next_post = get_specific_post('ASC', 'photography');
    }

    // If there's no previous post, get the last post
    if (empty($previous_post)) {
        $previous_post = get_specific_post('DESC', 'photography');
    }

    // Function to display post link with thumbnail data and arrow image
    function display_post_link_with_thumbnail($post, $class, $arrow_image_url)
    {
        if ($post) {
            // Get the URL of the post thumbnail
            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');

            // Get the title of the post to use in the 'title' attribute
            $post_title = get_the_title($post->ID);

            // Echo the HTML structure
            echo '<div class="' . esc_attr($class) . '-post">';
            echo '<a href="' . esc_url(get_permalink($post->ID)) . '" title="' . esc_attr($post_title) . '" data-thumbnail="' . esc_url($thumbnail_url) . '">';
            echo '<img src="' . esc_url($arrow_image_url) . '" alt="' . esc_attr($post_title) . '">';
            echo '</a></div>';
        }
    }

    // Display Previous Post link with thumbnail
    $prev_arrow_image_url = get_stylesheet_directory_uri() . '/assets/images/previous-arrow.svg';
    display_post_link_with_thumbnail($previous_post, 'prev', $prev_arrow_image_url);

    // Display Next Post link with thumbnail
    $next_arrow_image_url = get_stylesheet_directory_uri() . '/assets/images/next-arrow.svg';
    display_post_link_with_thumbnail($next_post, 'next', $next_arrow_image_url);
    ?>
</div><!-- .single-navigation__arrow-container -->
    
</div>