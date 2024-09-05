<?php
/**
 * Le modèle pour afficher la page d'accueil.
 *
 * @package thème WordPress motaphoto
 */
?>

<?php get_header(); ?>
<?php get_template_part('template-parts/front-page', 'banner'); ?>
<?php get_template_part('template-parts/front-page', 'filters'); ?>
<?php get_template_part('template-parts/front-page', 'catalog'); ?>
<?php get_footer(); ?>
