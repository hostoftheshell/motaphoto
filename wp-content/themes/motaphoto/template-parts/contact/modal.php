<?php

/**
 * Template part for displaying the contact modal
 *
 * @package motaphoto
 */
?>
<div class="contact-overlay hidden">
    <div class="contact-overlay__container">
    <i class="fa-regular fa-circle-xmark close-btn"></i>
        <div class="contact-overlay__header">
            <?php
            $image_src = get_stylesheet_directory_uri() . '/assets/images/contact-headerb.svg';
            $alt_text = 'Contact form decorative element';

            for ($j = 0; $j < 2; $j++) : ?>
                <div class="contact-overlay__header-wrapper">
                    <?php
                    for ($i = 0; $i < 3; $i++) : ?>
                        <img class="contact-overlay__header--image" src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr($alt_text); ?>">
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
        
        <div class="contact-overlay__form">
            <?php echo do_shortcode('[contact-form-7 id="1ee3960" title="motaphoto-contact-form"]'); ?>
        </div>
    </div>
</div>