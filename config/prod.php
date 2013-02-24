<?php

use Silex\Provider\MonologServiceProvider;

// descomenta las siguientes líneas para activar la depuración
// en el entorno de producción
// $app['debug'] = true;
// $app->register(new MonologServiceProvider(), array(
//     'monolog.logfile' => __DIR__.'/../logs/prod.log',
// ));

// Añadir a continuación cualquier otra opción de configuración de producción
// **********************************************************************************
 
// Esta configuración hace que todos los usuarios que tengan el rol ROLE_ADMIN 
// también dispongan automáticamente de los roles ROLE_USER y ROLE_ALLOWED_TO_SWITCH.
$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'),
);

// Sal para el codificador ($1$ -> MD5)...
$app['salt'] = '$1$tralariquetevi12341kdfmdnñlanfñlkd';
