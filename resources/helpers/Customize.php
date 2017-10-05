<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 17/05/17
 * Time: 10:59 PM
 */

namespace Com\Detalhe\Core\Helpers;

/**
 * Customize Class. Handles functions to customize Pages, Taxonomies, Metaboxes and PostTypes.
 *
 * @package Com\Detalhe\Core\Helpers
 * @since   1.0.0
 * @author  Yamil Elias <yamil@wtf.style>
 * @see     Components
 */
class Customize
{
    /**
     * Customize the columns that shows when you're editing the existing locations, so you know
     * which longitude and latitude have used before.
     *
     * @since 1.0.0
     * @param $existing_columns
     * @return array
     */
    function customize_provider_columns($existing_columns){
        $columns                     = array();
        $columns['cb']               = $existing_columns['cb'];

        $columns['provider-name']    = __( 'Provider Name', 'woocommerce' );
        $columns['phase']            = __( 'Phase', 'woocommerce' );
        $columns['location']         = __( 'Location', 'woocommerce' );

        return $columns;
    }

    /**
     * Customize the columns that shows when you're editing the existing locations, so you know
     * which longitude and latitude have used before.
     *
     * @since 1.0.0
     * @param $existing_columns
     * @return array
     */
    function customize_location_columns($existing_columns){
        $columns                = array();
        $columns['cb']          = $existing_columns['cb'];

        // Remove description, posts and wpseo_score
        unset($columns['description']);
        unset($columns['posts']);
        unset($columns['wpseo_score']);

        $columns['location-name']    = __( 'Location Name', 'woocommerce' );
        $columns['latitude']    = __( 'Latitude', 'woocommerce' );
        $columns['longitude']   = __( 'Longitude', 'woocommerce' );

        return $columns;
    }

    /**
     * Remove needed fields for taxonomies.
     *
     * @since 1.0.0
     * @see execute_location_remove()
     * @see execute_phase_remove()
     */
    function remove_fields($name){
        add_action( 'admin_footer-edit-tags.php', [new Customize(), "execute_{$name}_remove"]);
    }

    /**
     * Remove permalink in provider
     * TODO: Make it reusable and not static to one PostType only
     *
     * @since 1.0.0
     */
    function remove_provider_permalink(){
        add_action( 'admin_head', [new Customize(), 'execute_permalink_remove'] );
    }

    /**
     * Function where all the fields needed to remove are added.
     * TODO: Make it reusable and not static to one taxonomy only
     *
     * @since 1.0.0
     * @see Components
     */
    function execute_location_remove(){
        Components::remove_category_tag_field('location', '#tag-description'); // Remove Description text area in view
        Components::remove_category_tag_field('location', '#parent'); // Remove Parent option box in view
    }
    function execute_phase_remove(){ // Copied function
        Components::remove_category_tag_field('phase', '#parent'); // Remove Parent option box in view
    }

    // Function to reuse (a bit at least) remove code
//    function execute_remove($type){
//        switch ($type){
//            case 'location':
//                Components::remove_category_tag_field('location', '#tag-description'); // Remove Description text area in view
//                Components::remove_category_tag_field('location', '#parent'); // Remove Parent option box in view
//                break;
//            case 'phase':
//                Components::remove_category_tag_field('phase', '#parent'); // Remove Parent option box in view
//                break;
//        }
//    }

    /**
     * Function to remove Permalinks by an add_action() call. It uses the
     * removePermalinkEdit() function in Components class.
     * TODO: Make it reusable and not static to one PostType only
     *
     * @since 1.0.0
     */
    function execute_permalink_remove(){
        Components::remove_permalink_edit('provider');
    }

    /**
     * This function will create custom columns for the provided PostType.
     *
     * @since 1.0.0
     * @param $posttype
     */
    function create_custom_columns($posttype){
        add_filter( "manage_{$posttype}_posts_columns", [new Customize(), "customize_{$posttype}_columns"]);

//        add_action( "render_{$posttype}_coupon_columns", [new Templates(), "render_{$posttype}_columns"], 2);
    }

    /**
     * Get all the WP Hooks that are available. Function got from Chayka plugin.
     *
     * @link https://github.com/chayka/Chayka.Core.wpp
     * @since 1.0.0
     * @return array
     */
    public static function get_wp_hooks(){
        $tables = array();
        global $wp_filter;

        foreach($wp_filter as $tag=>$filters){
            $table = array();
            foreach ($filters as $priority=>$filterSet){
                foreach($filterSet as $func=>$implementation){
                    $function = $implementation['function'];
                    $callback = $function;
                    if(is_array($function)){
                        list($cls, $method) = $function;
                        $delimiter = '::';
                        if(is_object($cls)){
                            $delimiter = '->';
                            $cls = get_class($cls);
                        }
                        $callback = sprintf('%s %s %s', $cls, $delimiter, $method);
                    }elseif(is_object($function) && ($function instanceof \Closure)){
                        $callback = 'Closure';
                    }else{
//                        $r = $this->ReflectionFunctionFactory($function);
//                        $r = new ReflectionMethod("wpp_BRX_SearchEngine", 'addMetaBoxSearchOptions');
//                        $file = $r->getFileName();
//                        $startLine = $r->getStartLine();
//                        $ref = sprintf('%s (%d)', $file, $startLine);
                    }
//                    $ref = '';
                    $table[]=array(
                        'priority' => $priority,
                        'callback' => $callback,
                        'args' => $implementation['accepted_args'],
//                        'reflection' => $ref,
                    );
                }
            }
            usort($table, function($a, $b){
                $a = $a['priority'];
                $b = $b['priority'];
                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            });
            $tag = urldecode($tag);
            $tables[$tag]=$table;
        }
        ksort($tables);

        return $tables;
    }
}