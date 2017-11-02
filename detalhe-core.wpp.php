<?php

/**
 * Plugin Name: Detalhe Core Plugin
 * Plugin URI: https://github.com/yamilelias/detalhe-plugin
 * Description: Plugin created to add custom functionality for Detalhe Store.
 * Version: 1.0.0
 * Author: Yamil Elías <yamileliassoto@gmail.com>
 * Author URI: https://yamilelias.github.io
 * Text Domain: detalhe-core-wpp.
 * Domain Path: /languages
 */

/**
 * Use statements. Add any useful class import
 * below.
 */
use Composer\Autoload\ClassLoader;

/*
 * Default constants.
 */
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);

/*
 * Always define the following constant to help you handle
 * your plugin text domain. Make sure to define the same value as
 * the one defined in the plugin header comments text domain.
 *
 * Please update (search and replace) all constants calls found in this file.
 *
 */
defined('DETALHE_CORE_TD') ? DETALHE_CORE_TD : define('DETALHE_CORE_TD', 'detalhe-core');

/*
 * Plugin variables.
 * Configure your plugin.
 */
$vars = [
    'slug' => 'detalhe-core',
    'name' => 'Detalhe Core',
    'namespace' => 'com.detalhe.core',
    'config' => 'com_detalhe_core',
];

/*
 * Verify that the main framework is loaded.
 */
add_action('admin_notices', function () use ($vars) {
    if (!class_exists('\Themosis\Foundation\Application')) {
        printf('<div class="notice notice-error"><p>%s</p></div>', __('This plugin requires the Themosis framework in order to work.', DETALHE_CORE_TD));
    }
});

/*
 * Setup the plugin paths.
 */
$paths['plugin.'.$vars['namespace']] = __DIR__.DS;
$paths['plugin.'.$vars['namespace'].'.resources'] = __DIR__.DS.'resources'.DS;
$paths['plugin.'.$vars['namespace'].'.admin'] = __DIR__.DS.'resources'.DS.'admin'.DS;
$paths['plugin.'.$vars['namespace'].'.vendor'] = __DIR__.DS.'vendor'.DS;

themosis_set_paths($paths);

/*
 * Setup plugin config files.
 */
container('config.finder')->addPaths([
    themosis_path('plugin.'.$vars['namespace'].'.resources').'config'.DS,
]);

/*
 * Autoloading.
 */
$loader = new ClassLoader();
$classes = container('config.factory')->get($vars['config'].'_loading');
foreach ($classes as $prefix => $path) {
    $loader->addPsr4($prefix, $path);
}
$loader->register();

/*
 * Register plugin public assets folder [dist directory].
 */
container('asset.finder')->addPaths([
    plugins_url('dist', __FILE__) => themosis_path('plugin.'.$vars['namespace']).'dist',
]);

/*
 * Register plugin views.
 */
container('view.finder')->addLocation(themosis_path('plugin.'.$vars['namespace'].'.resources').'views');

/*
 * Update Twig Loader registered paths.
 */
container('twig.loader')->setPaths(container('view.finder')->getPaths());

/*
 * Service providers.
 */
$providers = container('config.factory')->get($vars['config'].'_providers');

foreach ($providers as $provider) {
    container()->register($provider);
}

/*
 * Admin files autoloading.
 * I18n.
 */
container('action')->add('plugins_loaded', function () use ($vars) {

	/**
	 * I18n
	 */
	load_plugin_textdomain(DETALHE_CORE_TD, false, trailingslashit(dirname(plugin_basename(__FILE__))).'languages');

    /*
     * Plugin admin files.
     * Autoload files in alphabetical order.
     */
    $loader = container('loader')->add([
        themosis_path('plugin.'.$vars['namespace'].'.admin'),
    ]);

    $loader->load();

});

/*
 * Add extra features below.
 */

// Define Constants and Variables
define('DETALHE_PLUGIN_PATH', __DIR__ . DS);
define('DETALHE_PLUGIN_REL_PATH', '/' . basename(dirname(dirname(dirname(__FILE__)))) . '/' . basename(dirname(dirname(__FILE__))) . '/' . basename(dirname(__FILE__)) . DS);