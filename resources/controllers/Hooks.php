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
        add_action('brands_view', [new Hooks(), 'show_existing_brands'], 10);

        add_action('single_brand', [new Hooks(), 'show_other_brands_section'], 30);

        add_action('subcategories_filters', [new Hooks(), 'generate_subcategories_filters'], 10);
        
        add_action('save_post_brand', [new Hooks(), 'create_term_when_brand_created'], 20);
//        add_action('delete_post', [new Hooks(), 'delete_term_when_brand_deleted'], 20);
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

    /**
     * This will generate the filters for each category in the category view.
     *
     * @since 1.0.0
     */
    public static function generate_subcategories_filters() {
        $term = get_queried_object();
        $children = get_term_children($term->term_id, $term->taxonomy);

        foreach( $children as $child) {
            $subcategory = get_term($child);

            echo '<a href="'. get_site_url() . '/product-category/' . $subcategory->slug .'">
                    <button class="submit sub-categories">'
                        . $subcategory->name . '
                    </button>
                  </a>';
        }
    }

    /**
     * This will create a term so is linked directly to the brand post type object.
     *
     * @param int $post_id
     * @since 1.0.0
     * @see wp_insert_term()
     * @see wp_update_term()
     * @see add_term_meta()
     * @see update_term_meta()
     */
    public static function create_term_when_brand_created($post_id) {
        $post = get_post($post_id); // Get the post

        // Don't do anything if is not a brand post type
        if($post->post_type != 'brand'){
            return;
        }

        // Remove the function to avoid an infinite loop
        remove_action( 'save_post_brand', 'set_private_categories', 13 );

        /*
         * Get the attributes that will be updated
         */
        $attributes = array(
            'description' => '',
            'slug'        => '',
        );

        if(isset($_POST['post_content'])){
            $attributes['description'] = $post->post_content;
        }

        if(isset($_POST['post_name'])){
            $attributes['slug'] = $post->post_name;
        }

        // If it already exists then update
        if(term_exists($post->post_name, 'product_cat')) {
            $term = get_term_by('slug', $post->post_name, 'product_cat');

            // Update term
            wp_update_term(
                $term->term_id,
                $term->taxonomy,
                $attributes
            );

            // Update its meta
            update_term_meta(
                $term->term_id,
                'thumbnail_id',
                get_post_thumbnail_id($post->ID)
            );
        } else {
            // Create product category
            $term = wp_insert_term(
                $post->post_title,
                'product_cat', // set as a product category
                $attributes
            );

            // Validate its not a WP_Error
            if(is_array($term)){
                // Create its meta
                add_term_meta(
                    $term['term_id'],
                    'thumbnail_id',
                    get_post_thumbnail_id($post->ID) // Get the ID of the image set to the post
                );
            }
        }

        // Re-hook this function
        add_action( 'save_post_brand', 'set_private_categories', 13, 2 );
    }

    /**
     * When the brand is deleted, then delete it's category and children.
     * FIXME: Children category can't be deleted, it only works with the term
     *
     * @param int $post_id
     * @since 1.0.0
     * @see wp_delete_term()
     */
    public static function delete_term_when_brand_deleted($post_id){
        $post = get_post($post_id);

        // Get the category from brand
        $term = get_term_by('slug', $post->post_name, 'product_cat');

        $children = get_term_children($term->term_id, $term->taxonomy);

        // Delete its children
        if(!empty($children)){
            foreach ($children as $child){
                wp_delete_term($child, 'product_cat');
            }
        }

        // Delete term
        wp_delete_term($term->term_id, $term->taxonomy);
    }
}