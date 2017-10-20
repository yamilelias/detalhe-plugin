<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 17/10/17
 * Time: 12:55 PM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Models\Brands;
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
            'our_brands'                  => __CLASS__ . '::our_brands',
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

            echo \view('com.detalhe.core.brands.preview',[
                'brand_slug' => $brand->post_name,
                'image' => $image
            ]);
        }
    }
}