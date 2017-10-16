<?php

use Com\Detalhe\Core\Controllers\{Admin, Composer, PostTypes, Metaboxes};

/**
 * Write your plugin custom code below.
 */

// Configuration to load widgets from the plugin
$loader = container('loader.widget');
$loader->add([
    themosis_path('plugin.com.detalhe.core.resources').'widgets/'
]);
$loader->load();

// Load controllers to action
Admin::init();
Metaboxes::init();
PostTypes::init();