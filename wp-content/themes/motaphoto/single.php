<?php get_header(); ?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
				<div> <?php the_post_thumbnail(); ?></div>
                <div class="entry-meta">
                    <span class="posted-on">Posted on <?php echo get_the_date(); ?></span>
                    <span class="byline"> by <?php the_author(); ?></span>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php the_content(); ?>
            </div><!-- .entry-content -->

            <footer class="entry-footer">
                <div class="entry-categories">
                    Categories: <?php the_category(', '); ?>
                </div>
                <div class="entry-tags">
                    <?php the_tags('Tags: ', ', ', '<br>'); ?>
                </div>
            </footer><!-- .entry-footer -->
        </article><!-- #post-## -->

        <?php if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile;
else :
    echo '<p>No posts found.</p>';
endif;
?>


<?php get_footer(); ?>
