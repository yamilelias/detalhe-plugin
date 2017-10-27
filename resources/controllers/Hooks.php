<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 16/10/17
 * Time: 01:45 PM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Models\Brands;

use Themosis\Facades\Action;
use Themosis\Route\BaseController;

/**
 * Hooks Class. Manages all the hooks from the plugin. Check also the @Shortcodes class because
 * there is most of the implementation of the logic of the functions here provided. This class only
 * add them as an action in most cases.
 *
 * @package     Com\Detalhe\Core\Controllers
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Hooks extends BaseController
{
    /**
     * Init function.
     *
     * @since 1.0.0
     */
    public static function init() {
//        Action::add('brands_view', 'Com\Detalhe\Core\Controllers\Hooks@show_existing_brands', 10);
        add_action('brands_view', [new Hooks(), 'show_existing_brands'], 10);

        add_action('single_brand', [new Hooks(), 'show_other_brands_section'], 30);

        add_action('woocommerce_shortcode_before_brand_products_loop', [new Hooks(), 'before_products_loop'], 10);
        add_action('woocommerce_shortcode_after_brand_products_loop', [new Hooks(), 'after_products_loop'], 10);
//        add_action('woocommerce_shortcode_after_brand_products_loop', [new Hooks(), 'after_loop_pagination'], 20);
    }

    /**
     * Show in the shop all the existing brands for the user to select them.
     *
     * @see Shortcodes::our_brands()
     * @since 1.0.0
     */
    public static function show_existing_brands() {
        // Only if the storefront is activated, if not, it won't show
        if ( storefront_is_woocommerce_activated() ) {

            $args = apply_filters( 'render_our_brands_section_args', array(
                'limit' 			=> -1,
                'columns' 			=> 4,
                'title'				=> __( 'Brands', 'storefront' ),
            ) );

            echo '<section class="storefront-brands-section storefront-our_brands products" aria-label="' . esc_attr__( 'Our Brands', 'storefront' ) . '">';

            echo '<div class="container">';

            $shortcode_content = storefront_do_shortcode( 'our_brands', array(
                'limit' => intval( $args['limit'] )
            ) );

            echo wp_kses_post( $shortcode_content );

            echo '</div>';

            echo '</section>';
        }
    }

    /**
     * Renders the other brands section in the brand template.
     *
     * @see Shortcodes::our_brands()
     * @since  1.0.0
     * @return void
     */
    public static function show_other_brands_section(){
        // Only if the storefront is activated, if not, it won't work
        if ( storefront_is_woocommerce_activated() ) {

            $args = apply_filters( 'show_other_brands_section_args', array(
                'limit' 			=> -1,
//                'columns' 			=> 4,
                'title'				=> __( 'Other Brands', 'storefront' ),
            ) );

            echo '<section class="storefront-product-section storefront-other_brands products" aria-label="' . esc_attr__( 'Other Brands', 'storefront' ) . '">';

            echo '<div class="title"><h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2></div>';

            echo '<div class="show-brands">';

            $shortcode_content = storefront_do_shortcode( 'other_brands' );

            echo wp_kses_post( $shortcode_content );

            echo '</div>';

            echo '</section>';
        }
    }

    public static function after_loop_pagination() {
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

        $custom_args = array(
            'post_type' => 'product',
            'posts_per_page' => 2,
            'paged' => $paged,
            'meta_value'          => 'paquilia', // TODO: Change to any-brand value
            'meta_key'            => 'product-brand',
        );

//        if (function_exists('custom_pagination')) {
//            custom_pagination(2,"",$paged);
//        }

        woo_pagination( $custom_args );
    }

    public static function before_products_loop(){
        do_action( 'woocommerce_before_shop_loop' );
    }

    public static function after_products_loop(){
        do_action( 'woocommerce_after_shop_loop' );
    }
}