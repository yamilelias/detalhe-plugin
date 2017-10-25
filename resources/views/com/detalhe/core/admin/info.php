<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 24/10/17
 * Time: 01:42 PM
 */
?>

<h2>Posts</h2>

<?php

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$custom_args = array(
    'post_type' => 'product',
    'posts_per_page' => 2,
    'paged' => $paged,
    'meta_value'          => 'paquilia',
    'meta_key'            => 'product-brand',
);

$custom_query = new WP_Query( $custom_args ); ?>

<?php if ( $custom_query->have_posts() ) : ?>

    <!-- the loop -->
    <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
        <article class="loop">
            <h3><?php the_title(); ?></h3>
            <div class="content">
                <?php the_excerpt(); ?>
            </div>
        </article>
    <?php endwhile; ?>
    <!-- end of the loop -->

    <!-- pagination here -->
    <?php
    if (function_exists('custom_pagination')) {
        custom_pagination($custom_query->max_num_pages,"",$paged);
    }
    ?>

    <?php wp_reset_postdata(); ?>

<?php else:  ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php get_product_search_form(); ?>
