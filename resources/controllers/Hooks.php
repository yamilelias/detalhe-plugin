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
    }

    /**
     * Show in the shop all the existing brands for the user to select them.
     *
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

            // Call the shortcode depending if is multisite or single site environment
            $shortcode_content = storefront_do_shortcode( 'our_brands', array(
                'limit' => intval( $args['limit'] )
            ) );

            echo wp_kses_post( $shortcode_content );

            echo '</div>';

            echo '</section>';
        }
    }
}