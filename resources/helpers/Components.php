<?php

namespace Com\Detalhe\Core\Helpers;

/**
 * Components Class. This is a class full of missing components from the Themosis plugin
 * that is able to connect to some features that are extremely needed
 * (maybe some of them aren't extremely neded, but is easier to work with them)
 * for the correct development and functionality of the plugin.
 *
 * @package   Style\Wtf
 * @since     1.0.0
 * @author    Yamil Elias <yamil@wtf.style>
 */
class Components
{
    /**
     * This function scaffolds the labels for new Labels.
     * @param $name
     * @access public
     * @since 1.0.0
     * @return array
     */
    public static function create_labels($name = ''){

        $labels = array(
            'name' => ucfirst($name) . 's',
            'singular_name' => ucfirst($name),
            'menu_name' => ucfirst($name) . 's',
            'name_admin_bar' => ucfirst($name),
            'all_items' => ucfirst($name) . 's',
            'add_new' => 'Add New ' . ucfirst($name),
            'add_new_item' => 'Add New ' . ucfirst($name),
            'edit_item' => 'Edit '. ucfirst($name),
            'new_item' => 'New ' . ucfirst($name),
            'view_item' => 'View ' . ucfirst($name),
            'search_items' => 'Search ' . ucfirst($name) . 's',
            'not_found' => 'No ' . $name . 's found',
            'not_found_in_trash' => 'No ' . $name . 's found in Trash',
            'parent_item_colon' => 'Parent ' . ucfirst($name) . ':',
        );

        return $labels;
    }

    /**
     * Function that receive a page ID (the page where the field will be removed)
     * and a Field ID (the going to be removed field).
     *
     * @since 1.0.0
     * @param $pageId
     * @param $fieldId
     */
    public static function remove_category_tag_field($pageId, $fieldId){
        global $current_screen;
        switch ( $current_screen->id )
        {
            case 'edit-' . $pageId:
                // WE ARE AT /wp-admin/edit-tags.php?taxonomy=[pageId]
                // OR AT /wp-admin/edit-tags.php?action=edit&taxonomy=[pageId]&tag_ID=3&post_type=post
                ?>
                <script type="text/javascript">
                    jQuery(document).ready( function($) {
                        $('<?php echo $fieldId; ?>').parent().remove();
                    });
                </script>
                <?php
                break;
        }
    }

    /**
     * It get the cpt_name to delete the permalink option in the Edit View.
     * FIXME: See why is not working...
     *
     * @since 1.0.0
     * @param string $cpt_name
     */
    public static function remove_permalink_edit($cpt_name = '') {
        global $post_type;

        if($post_type == $cpt_name) {
            echo '<style type="text/css">#edit-slug-box,#view-post-btn,#post-preview,.updated p a{display: none;}</style>';
        }
    }
}