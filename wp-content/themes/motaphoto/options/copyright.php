<?php

class CopyrightMenuPage
{
    // Définir un groupe d'options pour le menu de copyright
    const GROUP = 'options_copyright';

    /**
     * Enregistrer le menu de paramètres.
     */
    public static function register()
    {
        // Ajouter les actions pour le menu et les paramètres
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }

    /**
     * Enregistrer les paramètres pour la page de menu.
     */
    public static function registerSettings() {
        // Enregistrer le paramètre 'motaphoto_copyright' avec une valeur par défaut
        register_setting(self::GROUP, 'motaphoto_copyright', ['default' => 'TOUS DROITS RÉSERVÉS']);
    
        // Ajouter une section de paramètres
        add_settings_section('options_copyright-section', 'Configuration', function () {
            echo "Vous pouvez ici gérer les réglages liés à la Mention des Droits d'Auteur dans le pied de page.";
        }, self::GROUP);

        // Ajouter un champ pour la mention des droits d'auteur
        add_settings_field('options_copyright-field','Mention des Droits d\'Auteur', function () {
            ?>
            <input type="text" id="name" name="motaphoto_copyright" maxlength="30" value="<?php echo esc_attr(get_option('motaphoto_copyright')); ?>" />
            <?php
        }, self::GROUP, 'options_copyright-section');
    }

    /**
     * Ajouter la page de menu de copyright sous les paramètres.
     */
    public static function addMenu()
    {
        add_options_page(
            __('Mention de Droits d\'auteur', 'motaphoto'), // Titre de la page
            __('Droits d\'auteur', 'motaphoto'),            // Titre du menu
            'manage_options',                                // Capacité requise pour accéder à ce menu
            self::GROUP,                                    // Slug du menu
            [self::class, 'render']                          // Fonction de rappel pour le rendu
        );
    }

    /**
     * Rendre le contenu de la page des options.
     */
    public static function render()
    {
        ?>
        <h1>Gestion de la Mention des Droits d'Auteur dans le pied de page</h1>
        <form action="options.php" method="post">
            <?php 
            // Générer les champs de paramétrage
            settings_fields(self::GROUP);
            // Afficher les sections de paramètres pour ce groupe
            do_settings_sections(self::GROUP);
            // Ajouter un bouton de soumission
            submit_button();
            ?>
        </form>
        <?php
    }
}
