<?php

use Com\Detalhe\Core\Controllers\{Admin, Hooks, PostTypes, Shortcodes, Metaboxes};

/**
 * Class Detalhe_Core. This will initialize the plugin and all the controllers and functions it needs.
 *
 * @since 1.0.0
 */
class Detalhe_Core
{
    /**
     * Public method to run everything.
     *
     * @since 1.0.0
     */
    public static function run_configuration() {
        // Configure widgets first
        self::configure_widgets();

        // Load controllers to action
        Admin::init();
        Hooks::init();
        Metaboxes::init();
        Shortcodes::init();
        PostTypes::init();
    }

    /**
     * Configure the plugin to work with the themosis implementation for widgets.
     *
     * @since 1.0.0
     */
    private static function configure_widgets() {
        // Configuration to load widgets from the plugin
        $loader = container('loader.widget');
        $loader->add([
            themosis_path('plugin.com.detalhe.core.resources').'widgets/'
        ]);
        $loader->load();
    }
}

// Run!
Detalhe_Core::run_configuration();