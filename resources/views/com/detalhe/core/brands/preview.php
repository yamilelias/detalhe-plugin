<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 17/10/17
 * Time: 01:14 PM
 */

?>

<div class="no-padding-sides col-lg-6 col-md-6 col-xs-6">
    <div class="item">
        <a href="<?php echo get_term_link( $brand_term ) ?>" class="brand-link">
            <?php echo $image; ?>
            <p><?php echo $brand_term->name ?></p>
        </a>
    </div>
</div>