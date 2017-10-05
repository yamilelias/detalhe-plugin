<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 19/06/17
 * Time: 03:18 PM
 */

namespace Com\Detalhe\Core\Helpers;

use Com\Detalhe\Core\Controllers\Brands;

/**
 * Class Functions. Here are stored all the functions that are widely used by more than one
 * function through the plugin.
 *
 * @package     Com\Detalhe\Core\Helpers
 * @since       1.0.0
 * @author      Yamil Elías <yamil@wtf.style>
 */
class Functions
{
    /**
     * Function to loop over found products from within the provided query.
     * Function based on woocommerce shortcode class:
     * https://docs.woocommerce.com/wc-apidocs/class-WC_Shortcodes.html
     *
     * @since 1.0.0
     * @param  array $query_args
     * @param  array $atts
     * @param  string $loop_name
     * @return string
     */
    public static function product_loop_with_query($query_args, $atts, $loop_name ) {
        global $woocommerce_loop;

        $columns                     = absint( $atts['columns'] );
        $woocommerce_loop['columns'] = $columns;
        $woocommerce_loop['name']    = $loop_name;
        $query_args                  = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $atts, $loop_name );
        $transient_name              = 'wc_loop' . substr( md5( json_encode( $query_args ) . $loop_name ), 28 ) . \WC_Cache_Helper::get_transient_version( 'product_query' );
//        $products = get_transient( $transient_name );

        // Get parameters from view, if there aren't any, then put a default so it won't break
        try {
            $view_params = $atts['view_params'];
        } catch(\Exception $e){
            $view_params = array();
        }

        $products = new \WP_Query($query_args);

        set_transient( $transient_name, $products, DAY_IN_SECONDS * 30 );

        ob_start();

        if ($products->have_posts()) {
            while ($products->have_posts()) : $products->the_post();
                global $product;

                // Get the brand
                $brand = Brands::get_current_brand();

                // Check the product availability
                $availability = $product->get_availability();
                $available = $availability['class'] == 'out-of-stock' ? false : true;

                $product_url = get_site_url() . '/product/' . $product->get_slug();

                $product_data = array(
                    'brand'       => $brand,
                    'available'   => $available,
                    'product'     => $product,
                    'product_url' => $product_url,
                );

                // Merge information in a single array
                $data = array_merge($product_data, $view_params);

                try{
                    echo view('homepage.product', $data); // Set view with params
                } catch(\Error $e){
                    echo view('homepage.product', $product_data);
                } catch(\Exception $e){
                    echo view('homepage.product', $product_data);
                }

            endwhile;
        } else {
            do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results", $atts );
        }

        woocommerce_reset_loop();
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Call a shortcode function by tag name. Based on storefront_do_shortcode() function from
     * Woocommerce plugin.
     *
     * @since  1.0.0
     * @see \storefront_do_shortcode()
     *
     * @param string $tag     The shortcode whose function to call.
     * @param array  $atts    The attributes to pass to the shortcode function. Optional.
     * @param array  $content The shortcode's content. Default is null (none).
     *
     * @return string|bool False on failure, the result of the shortcode on success.
     */
    public static function do_shortcode($tag, array $atts = array(), $content = null){
        global $shortcode_tags;

        if ( ! isset( $shortcode_tags[ $tag ] ) ) {
            return false;
        }

        return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }

    /**
     * Helper function to create provider objects in order to pass relevant information as an
     * entire object and not just single data.
     *
     * @since 1.0.0
     * @param string $provider
     * @return object
     */
    public static function create_provider_object($provider){
        $post = get_page_by_path($provider, OBJECT, 'provider'); // Get the post
        $obj_id = $post->ID;

        // Get the provider thumbnail
        $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($obj_id), 'full');
        $post_thumbnail = $thumbnail[0];

        // Get the location object
        $location = wp_get_post_terms($obj_id, 'location');
        $location_id = $location[0]->term_id;

        // Get coordinates
        $term = get_term_meta($location_id);
        $lat = $term['latitude'];
        $lng = $term['longitude'];

        $phase = wp_get_post_terms($obj_id, 'phase', array("fields" => "names")); // Get selected Phase

        // Return a provider object
        return $provider_object = (object) array(
            'id'          => $obj_id,
            'slug'        => $post->post_name,
            'name'        => $post->post_title,
            'description' => $post->post_content,
            'phase'       => $phase[0],
            'thumbnail'   => $post_thumbnail,
            'location'    => (object) array(
                'id'          => $location_id,
                'slug'        => $location[0]->slug,
                'name'        => $location[0]->name,
                'latitude'    => $lat[0],
                'longitude'   => $lng[0]
            ),
        );
    }

    /**
     * Function that will receive a WordPress Query and return only an array with the slug of
     * the posts as id for each item.
     *
     * @since 1.0.0
     * @param $model \WP_Query
     * @return array
     */
    public static function set_query_to_array($model){
        $options = [];

        if($model->have_posts()){
            $objects = $model->get_posts();

            foreach ($objects as $object){
                $options[$object->post_name] = $object->post_title;
            }
        }

        return $options;
    }

    /**
     * Adds a tax_query index to the query to filter by category. Code obtained from the
     * Woocommerce shortcode class: https://docs.woocommerce.com/wc-apidocs/class-WC_Shortcodes.html
     *
     * @since 1.0.0
     * @param array $args
     * @param string $category
     * @param string $operator
     * @return array;
     */
    public static function add_category_args( $args, $category, $operator ) {
        if ( ! empty( $category ) ) {
            if ( empty( $args['tax_query'] ) ) {
                $args['tax_query'] = array();
            }
            $args['tax_query'][] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms'    => array_map( 'sanitize_title', explode( ',', $category ) ),
                    'field'    => 'slug',
                    'operator' => $operator,
                ),
            );
        }

        return $args;
    }

    /**
     * Add the needed args to check for only the products for a determined brand.
     *
     * @since 1.0.0
     * @param $args
     * @return array
     */
    public static function add_brand_args($args){
        global $post;

        $query_args = [];

        // If the page is of a brand, then show only the products from the brands.
        if($post->post_type == 'brand'){
            $brand = $post->post_name;

            // Create args to get the posts
            $brand_args = array(
                'meta_value'          => $brand,
                'meta_key'            => 'product-brand',
            );

            // Merge them to the existing args
            $query_args = array_merge($args, $brand_args);
        } else { // If not, then only return the same args
            $query_args = $args;
        }

        return $query_args;
    }
}