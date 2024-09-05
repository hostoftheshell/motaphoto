<?php

// Charger les scripts et styles via Enqueue
require_once get_template_directory() . '/inc/enqueue-scripts.php';

// Charger les fonctions d'initialisation du thème
require_once get_template_directory() . '/inc/theme-setup.php';

// Charger les types de publication personnalisés
require_once get_template_directory() . '/inc/custom-post-types.php';

// Charger les fonctions personnalisées
require_once get_template_directory() . '/inc/custom-functions.php';

// Charger les colonnes et le tri dans l'admin
require_once get_template_directory() . '/inc/admin-columns.php';

// Charger les filtres Ajax
require_once get_template_directory() . '/inc/ajax-filter.php';

// Inclure et instancier la classe d'options de droit d'auteur
require_once('options/copyright.php');
if (class_exists('CopyrightMenuPage')) {
    CopyrightMenuPage::register();
}