<?php
/**
 * Created by PhpStorm.
 * User: yamilelias
 * Date: 24/05/17
 * Time: 02:31 AM
 */

namespace Com\Detalhe\Core\Controllers;

use Com\Detalhe\Core\Helpers\Customize;

use Themosis\Route\BaseController;

/**
 * Composer Class. Initialize and manage all the View::composer used by Themosis.
 *
 * @package     Style\Wtf\Core\Controllers
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Composer extends BaseController
{
    /**
     * Default method used for a view composer.
     *
     * @param $view
     * @since 1.0.0
     */
    public function compose($view){

    }

    /**
     * This composer is in order to pass the Hooks tables that the view will print.
     *
     * @since 1.0.0
     * @param $view
     */
    public function compose_wp_hooks_view($view){
        $tables = Customize::get_wp_hooks();

        $view->with([
            'tables' => $tables
        ]);
    }
}