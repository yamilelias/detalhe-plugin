<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 17/10/17
 * Time: 12:55 PM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Helpers\Functions;
use Com\Detalhe\Core\Models\Brands;
use Com\Detalhe\Core\Controllers\Brands as Brand;

use Themosis\Route\BaseController;

/**
 * Shortcodes Class. This class means to manage the shortcodes used by the plugin,
 * both in a single site or a multisite environment.
 *
 * @package     Style\Wtf\Core\Controllers
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Shortcodes extends BaseController
{
    /**
     * Init shortcodes.
     *
     * @since 1.0.0
     */
    public static function init() {

        // Put in here all the shortcodes from the Controller
        $shortcodes = array(
            'our_brands'               => __CLASS__ . '::our_brands',
            'other_brands'             => __CLASS__ . '::other_brands',
            'brand_products'           => __CLASS__ . '::brand_products',
        );

        foreach ( $shortcodes as $shortcode => $function ) {
            add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
        }
    }

    /**
     * Get all the brands from a single site environment with their respective links to deploy in a view.
     *
     * @since 1.0.0
     * @param $atts
     */
    public static function our_brands($atts){
        $query = Brands::get_all();
        $brands = $query->get_posts();

        foreach($brands as $brand){
            $image = get_the_post_thumbnail($brand->ID);

            $brand = Brand::get_current_brand_as_term($brand->post_name);

            echo \view('com.detalhe.core.brands.preview',[
                'brand_term' => $brand,
                'image' => $image
            ]);
        }
    }

    /**
     * This will deploy the Other Brands section in the Brand view.
     *
     * @since 1.0.0
     * @param $atts
     */
    public static function other_brands($atts){
        $query = Brands::get_all();
        $brands = $query->get_posts();

        foreach($brands as $brand){
            $image = get_the_post_thumbnail($brand->ID);

            $brand = Brand::get_current_brand_as_term($brand->post_name);

            echo \view('com.detalhe.core.brands.product-section',[
                'brand_term' => $brand,
                'image' => $image
            ]);
        }
    }

    /**
     * Show all the products that appears in the brand page.
     *
     * @since 1.0.0
     * @param $atts
     * @return string
     */
    public static function brand_products($atts) {
        $atts = shortcode_atts( array(
            'per_page' => '4',
            'columns'  => '4',
            'orderby'  => 'menu_order',
            'order'    => 'desc',
            'category' => '',  // Slugs
            'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
        ), $atts, 'brand_products' );

        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
        );

        // Add extra args to the query
        $query_args = Functions::add_brand_args($query_args);
        $query_args = Functions::add_category_args( $query_args, $atts['category'], $atts['operator'] );

        return Functions::product_loop_with_query( $query_args, $atts, 'brand_products' );
    }
}