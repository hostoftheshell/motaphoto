<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="main-container">
        <header id="masthead" class="site-header" role="banner">
            <div class="logo-menu-wrapper">
                <div class="logo-container">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'medium');
                    $home_url = esc_url(home_url('/'));

                    echo '<a href="' . $home_url . '" title="' . get_bloginfo('name') . '">';

                    if (has_custom_logo()) {
                        echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '">';
                    } else {
                        echo '<h1>' . get_bloginfo('name') . '</h1>';
                    }

                    echo '</a>';
                    ?>
                </div>

                <div id="menu__button" class="menu__button">
                    <span class="menu__button--line"></span>

                </div>
                <?php
                wp_nav_menu(array(
                    'container'       => 'nav',
                    'container_id'    => 'primary-navigation',
                    'container_class' => 'main-navigation',
                    'menu_id'         => 'main-menu',
                    'menu_class'      => 'main-menu',
                    'theme_location'  => 'primary_menu',
                ));
                ?>
            </div>
        </header>
        <main id="content" class="site-content">
        <!-- Main content -->
