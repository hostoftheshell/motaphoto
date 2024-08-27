<?php get_header(); ?>
<?php if (is_single() && get_post_type() === 'photography') : ?>

    <?php get_template_part('template-parts/content', 'photography'); ?>

    <section class="single-contact">
        <div  class="single-contact__CTA">
        <p><?php esc_html_e('Cette photo vous intéresse ?', 'motaphoto'); ?></p>
        <?php echo get_contact_button_html(false); ?>
        </div>
        <?php get_template_part('template-parts/navigation', 'photography'); ?>
    </section>


    <?php get_template_part('template-parts/related', 'photography'); ?>

<?php else : ?>
    <section class="no-posts">
        <p><?php esc_html_e('Aucun article de photographie trouvé.', 'motaphoto'); ?></p>
    </section>
<?php endif; ?>

<?php if (is_active_sidebar('sidebar-1')) : ?>
    <?php get_sidebar(); ?>
<?php endif; ?>

<?php get_footer(); ?>