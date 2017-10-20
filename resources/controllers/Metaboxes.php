<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 8/06/17
 * Time: 12:04 AM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Models\Brands;
use Com\Detalhe\Core\Helpers\Functions;

use Themosis\Route\BaseController;
use Themosis\Facades\{Metabox, Field};

/**
 * Metaboxes Class. Initialize and manage all the Metabox::any used by Themosis.
 *
 * @package     Com\Detalhe\Core\Controllers
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Metaboxes extends BaseController
{
    /**
     * Metaboxes constructor.
     *
     * @since 1.0.0
     */
    function __construct(){
        self::init();
    }

    /**
     * Metabox initializer that will run all the Metabox::make and code needed.
     *
     * @since 1.0.0
     */
    public static function init(){
        self::items_needed_for_brands();
        self::brand_for_product_post_type_metabox();
    }

    /**
     * Set different metaboxes for the user to define the avything needed for each brand.
     *
     * @since 1.0.0
     */
    public static function items_needed_for_brands(){
        Metabox::make('Customized Brand', 'brand', [
            'context'  => 'normal',
            'priority' => 'high',
            'id'       => 'brand-banner-metabox'
        ])->set([
            Field::media('brand-header-banner', [
                'title'   => 'Brand Header Banner',
                'info'    => 'Select a banner that will appear in header when the brand is active.'
            ]),
            Field::color('brand-header-color', [
                'title'   => 'Brand Header Color',
                'info'    => 'Select a color that will appear in header when the header won\'t reach the borders of the page'
            ]),
        ]);
    }

    /**
     * This function will create everything for the brands metabox in product post type.
     *
     * @since 1.0.0
     */
    public static function brand_for_product_post_type_metabox(){

        // Get all the brands from database
        $model = Brands::get_all();

        // Options for the metabox
        $options = [];

        if($model->have_posts()){
            $options = array_merge($options, Functions::set_query_to_array($model));
        }

        Metabox::make('Brands', 'product', [
            'context'  => 'side',
            'priority' => 'low',
            'id'       => 'brands-metabox'
        ])->set([
            Field::select('product-brand', [
                $options // You must pass an array of arrays in order to work
            ], [
                'id'    => 'select-a-brand',
                'title' => 'Select a Brand',
                'class' => 'brand-select',
            ])
        ]);
    }
}
