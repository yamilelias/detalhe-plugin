<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 7/06/17
 * Time: 11:49 PM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Helpers\{Components, Customize};
use Themosis\Route\BaseController;
use Themosis\Facades\PostType;

/**
 * PostTypes Class. Initialize and manage all the PostType::any used by Themosis.
 *
 * @package     Com\Detalhe\Core\Controllers
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class PostTypes extends BaseController
{
    /**
     * ViewComposer constructor.
     * @since 1.0.0
     */
    function __construct(){
        self::init();
    }

    /**
     * PostType initializer that will run all the PostType::make and code needed.
     *
     * @since 1.0.0
     */
    public static function init(){
        $customizer = new Customize();

        self::brands_posttype($customizer);
    }

    /**
     * Function that will create the different brands, to set products when a multisite is not present.
     *
     * @since 1.0.0
     * @param $customizer
     */
    public static function brands_posttype($customizer){
        PostType::make('brand', 'Brand', 'Brand')->set([
            'label' => 'Brand',
            'labels' => Components::create_labels('brand'),
            'description' => 'Existing brands from WTF',
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 100.39190543265627,
            'menu_icon' => 'dashicons-shield-alt',
            'capability_type' => 'page',
            'hierarchical' => false,
            'has_archive' => false,
            'query_var' => 'provider',
            'rewrite' => true,
            'can_export' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes')
        ]);
    }
}