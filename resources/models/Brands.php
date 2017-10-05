<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 6/07/17
 * Time: 09:56 PM
 */

namespace Com\Detalhe\Core\Models;

/**
 * Brands Class. Handle all the logic to retrieve brands from database.
 *
 * @package     Com\Detalhe\Core\Models
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Brands
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
            'post_type'      => 'brand',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'asc',
        ]);

        return $query;
    }
}