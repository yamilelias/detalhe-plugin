<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 8/06/17
 * Time: 12:37 AM
 */

namespace Com\Detalhe\Core\Models;

/**
 * Products Class. Handle all the logic to retrieve products from database.
 *
 * @package     Com\Detalhe\Core\Models
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Products
{
    /**
     * Retrieve a list of all published products in the current site.
     *
     * @since 1.0.0
     * @return \WP_Query
     */
    public static function get_all()
    {
        $query = new \WP_Query([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        return $query;
    }

    /**
     * Get some published products in the current site.
     *
     * @since 1.0.0
     * @param $quantity
     * @return \WP_Query
     */
    public static function get_products($quantity)
    {
        $query = new \WP_Query([
            'post_type' => 'product',
            'posts_per_page' => $quantity,
            'post_status' => 'publish',
        ]);

        return $query;
    }


    /**
     * Returns all the products from all sites from a multisite environment.
     *
     * @since 1.0.0
     * @return \WP_Query
     */
    public static function all_sites_products() {
        $products = new \WP_Query();
        $retrieved_products = new \WP_Query();
        $sites = get_sites();

        // Loop through all sites
        foreach ($sites as $site) {
            // Switch to each blog to get the posts
            switch_to_blog($site->blog_id);

            if($site->blog_id == 1){
                $products = self::get_products('1');
            } else {
                $retrieved_products = self::get_products('1');
            }

            if($site->blog_id != 1){
                // Add the new products (posts)
                $products->posts = array_merge( $products->posts, $retrieved_products->posts);
                // Increment the count so the loop doesn't get broken
                $products->post_count = $products->post_count + $retrieved_products->post_count;
            }

            // Restore, so we don't get lost
            restore_current_blog();
        }
        unset($site); // Unset last variable used to avoid bugs.

        return $products;
    }
}