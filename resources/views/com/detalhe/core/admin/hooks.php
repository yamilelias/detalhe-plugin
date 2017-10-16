<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 16/10/17
 * Time: 01:03 PM
 */

?>

<style>
    div.wp_hook{
        float: left;
        margin: 0 2em 2em 0;
    }
    table.wp_hooks{
        border: 1px solid #444444;
        width: 400px;
    }
    table.wp_hooks td{
        padding: 1ex;
    }
    table.wp_hooks tr:first-child{
        font-weight: bold;
    }
</style>

<h2>WP Hooks</h2>
<div class="hooks_container">
    <?php foreach ($tables as $tag=>$table):?>
        <div class="wp_hook">
            <h3 class="hook"><?php echo $tag?></h3>
            <table class="wp_hooks" rules="all">
                <tr>
                    <?php if(is_array($table[0])): foreach ($table[0] as $column=>$value):?>
                        <td><?php echo $column;?></td>
                    <?php endforeach; endif;?>
                </tr>
                <?php foreach ($table as $row):?>
                    <tr>
                        <?php foreach ($row as $column=>$value):?>
                            <td><?php echo $value;?></td>
                        <?php endforeach;?>

                    </tr>
                <?php endforeach;?>
            </table>
        </div>
    <?php endforeach;?>

