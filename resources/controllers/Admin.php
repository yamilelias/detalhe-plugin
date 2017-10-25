<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 16/06/17
 * Time: 10:06 AM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Helpers\Functions;

use Themosis\Route\BaseController;
use Themosis\Facades\{Page, View};

/**
 * Class Admin. This class is to deploy all the menu and submenu pages needed.
 *
 * @since       1.0.0
 * @package     Com\Detalhe\Core\Controllers
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Admin extends BaseController
{
    /**
     * Setup class.
     * @since 1.0.0
     */
    public static function init(){
//        self::create_admin_page();
        self::create_other_subpages();
    }

    /**
     * Create the admin page defining his own view.
     *
     * @since 1.0.0
     */
    public static function create_admin_page(){
//        $view = View::make('admin.main');

        $page = Page::make('configuration', 'Configuration')->set([
            'capability' => 'manage_options',
//            'icon'       => DETALHE_PLUGIN_REL_PATH .'/dist/images/logo.ico',
            'position'   => 2,
            'tabs'       => true,
            'menu'       => __("Configuration"),
        ]);

        self::create_admin_subpages('configuration');
        self::create_admin_settings($page);
    }

    /**
     * Create the admin subpages adding them to the parent page.
     *
     * @since 1.0.0
     * @param string $parent
     */
    public static function create_admin_subpages($parent = ''){
        // Add here admin subpages
    }

    /**
     * Create the admin settings adding them to the main admin page.
     *
     * @updated 1.0.1
     * @since 1.0.0
     * @param $page
     */
    public static function create_admin_settings($page){

        $sections = [];
        $settings = [];

        // Add the created sections.
        $page->addSections($sections);

        // Add the created settings.
        $page->addSettings($settings);
    }

    public static function create_other_subpages(){

        /*
         * Plugin Information View. Show up information from development environment.
         */
        $view = View::make('com.detalhe.core.admin.info');
        Page::make('core-info', 'Detalhe Core', 'tools.php', $view)->set([
            'capability' => 'manage_options',
        ]);

        /*
         * PHP Info View. Literally only runs phpinfo() function to get what it have running.
         */
        $view = View::make('com.detalhe.core.admin.phpinfo');
        Page::make('phpinfo', 'PHP Info', 'tools.php', $view)->set([
            'capability' => 'manage_options',
        ]);

        /*
         * WP Hooks View. It gets all the current WP Hooks that are available to use.
         */
        $view = View::make('com.detalhe.core.admin.hooks');
        View::composer('com.detalhe.core.admin.hooks', 'Com\Detalhe\Core\Controllers\Composer@compose_wp_hooks_view');

        Page::make('wp-hooks', 'WP Hooks', 'tools.php', $view)->set([
            'capability' => 'manage_options',
        ]);
    }
}