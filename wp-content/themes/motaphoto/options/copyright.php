<?php

class CopyrightMenuPage
{

    const GROUP = 'options_copyright';
    /**
     * Register the menu page.
     */
    public static function register()
    {
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }

    public static function registerSettings() {
        register_setting(self::GROUP, 'motaphoto_copyright', ['default' => 'TOUS DROITS RÉSERVÉS']);
    
        add_settings_section('options_copyright-section', 'Configuration', function () {
            echo "Vous pouvez ici gérer les réglages liés à la Mention des Droits d'Auteur dans le pied de page.";
        }, self::GROUP);
        add_settings_field('options_copyright-field','Mention des Droits d\'Auteur', function () {
            ?>
            <input type="text" id="name" name="motaphoto_copyright" maxlength="30" value="<?php echo esc_attr(get_option('motaphoto_copyright')); ?>" />
            <?php
        }, self::GROUP, 'options_copyright-section');
    }
    /**
     * Add the copyright menu page under Settings.
     */
    public static function addMenu()
    {
        add_options_page(
            __('Mention de Droits d\'auteur', 'motaphoto'), // Page title
            __('Droits d\'auteur', 'motaphoto'),            // Menu title
            'manage_options',                                // Capability
            self::GROUP,                             // Menu slug
            [self::class, 'render']                          // Callback function
        );
    }

    /**
     * Render the content of the options page.
     */
    public static function render()
    {
        ?>
        <h1>Gestion de la Mention des Droits d'Auteur dans le pied de page</h1>
        <form action="options.php" method="post">
            <?php 
            settings_fields(self::GROUP);
            do_settings_sections(self::GROUP);
            submit_button()
            ?>
        </form>
        <?php
    }
}
