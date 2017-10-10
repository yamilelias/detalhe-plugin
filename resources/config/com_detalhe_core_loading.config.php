<?php

/**
 * Plugin autoloading configuration.
 */
return [
    'Com\\Detalhe\\Core\\Services\\' => themosis_path('plugin.com.detalhe.core.resources').'providers',
    'Com\\Detalhe\\Core\\Models\\' => themosis_path('plugin.com.detalhe.core.resources').'models',
    'Com\\Detalhe\\Core\\Controllers\\' => themosis_path('plugin.com.detalhe.core.resources').'controllers',
    'Com\\Detalhe\\Core\\Helpers\\' => themosis_path('plugin.com.detalhe.core.resources').'helpers',

    // Set composer libraries loading
    'PHPMailer\\PHPMailer\\' => themosis_path('plugin.com.detalhe.core.vendor').'phpmailer/phpmailer/src',
];