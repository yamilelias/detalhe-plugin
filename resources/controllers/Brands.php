<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 5/10/17
 * Time: 10:44 AM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Helpers\Functions;
use Themosis\Route\BaseController;
use Themosis\Metabox\Meta;

/**
 * Class Brands. Here are stored all the functions that are widely used to manage the brands logic.
 *
 * @package     Com\Detalhe\Core\Controllers
 * @since       1.0.0
 * @author      Yamil Elías <yamil@wtf.style>
 */
class Brands extends BaseController
{
    /**
     * Global variable to define if there exists a brand or not.
     *
     * @var bool
     */
    private static $have_brand = false;

    /**
     * Validate in which brand the user is, if is not in any brand return empty string.
     *
     * @since  1.0.0
     * @param array $default Give the default brand you want
     * @return object Returns a brand object that will return all the needed information
     */
    public static function get_current_brand($default = array()) {
        $post = is_singular() ? get_queried_object() : false;

        // If an object is passed, then use it for the brand
        if($default){
            $post = (object)$default;
            $post->post_type = 'default';
        }
         else if(! $post instanceOf \WP_Post){
            $post = get_post();
        }

        self::$have_brand = false;

        $brand = array(
            'ID'         => 0,
            'post_name'  => '',
            'post_title' => '',
            'logo'       => '',
        );

        // Let's avoid "Trying to get property of non-object" error
        if(!empty($post)){
            switch($post->post_type) {
                case 'brand':
                    $brand = $post; // Set it as it is

                    self::$have_brand = true;

                    break;
                case 'product':
                    $slug = Meta::get($post->ID, 'product-brand', true); // Get brand slug
                    $brand_meta = get_page_by_path($slug, OBJECT, 'brand'); // Get brand information

                    // Check it again if it not ended up null...
                    if($brand_meta == null){
                        self::$have_brand = false;
                    } else {
                        $brand = $brand_meta; // If is not null, then pass it as a brand
                        self::$have_brand = true;
                    }

                    break;
                case 'default':

                    $slug = $default['slug'];

                    // If is a subcategory, then get the parent for the slug
                    if($default['parent'] != 0){
                        $parent = get_term($default['parent']);

                        $slug = $parent->name;
                    }

                    $args = array(
                        'name'           => $slug,
                        'post_type'      => 'brand',
                        'post_status'    => 'publish',
                        'posts_per_page' => 1
                    );
                    $my_posts = get_posts( $args ); // Get brand by slug

                    $brand = $my_posts[0];

                    self::$have_brand = true;

                    break;
            }
        }

        // Assign the header if at the end exists a brand
        if(self::have_brand()){
            $banner_id = Meta::get($brand->ID, 'brand-header-banner', true); // Get the header banner ID
            $brand->header_banner = Functions::image_array_to_object(wp_get_attachment_image_src($banner_id, 'full')); // Get the image

            $brand->color = Meta::get($brand->ID, 'brand-header-color'); // Get the color
        }

        return $brand;
    }

    /**
     * Returns if the current page have a brand or not.
     *
     * @since 1.0.0
     * @return bool
     */
    public static function have_brand() {
        return self::$have_brand;
    }

    /**
     * Return the current brand but as a WP_Term object. It can be provided a slug, ID or object for this.
     *
     * @since 1.0.0
     * @param string|int|object $term The term object, ID, or slug whose brand will be retrieved.
     * @return array|false|\WP_Term Brand as a WP_Term object.
     */
    public static function get_current_brand_as_term($term = '') {
        $brand_term = '';

        if($term) {
            if ( !is_object($term) ) {
                if ( is_int( $term ) ) {
                    $brand_term = get_term( $term, 'product_cat' );
                } else {
                    $brand_term = get_term_by( 'slug', $term, 'product_cat' );
                }
            } else {
                $brand_term = get_term_by( 'slug', $term->slug, 'product_cat' );
            }

            // Validations if for being an object and for an error provided to the function
            if ( !is_object($term) )
                $term = new \WP_Error('invalid_term', __('Empty Term'));

            if ( is_wp_error( $term ) )
                return $brand_term;


        } else {
            $brand = self::get_current_brand();

            // Now we have the brand, convert it to a WP_Term object
            $brand_term = get_term_by('slug', $brand->post_name, 'product_cat');
        }

        return $brand_term;
    }
}