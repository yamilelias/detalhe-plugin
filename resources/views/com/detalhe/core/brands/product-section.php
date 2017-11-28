<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 23/10/17
 * Time: 10:54 AM
 */
?>

<div class="col-lg-2 col-md-6 col-xs-6">
    <div class="item">
        <a href="<?php echo get_term_link( $brand_term ) ?>" class="brand-link">
            <?php echo $image; ?>
            <p><?php echo $brand_term->name ?></p>
        </a>
    </div>
</div>
