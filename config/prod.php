<?php

use Silex\Provider\MonologServiceProvider;

// descomenta las siguientes líneas para activar la depuración
// en el entorno de producción
// $app['debug'] = true;
// $app->register(new MonologServiceProvider(), array(
//     'monolog.logfile' => __DIR__.'/../logs/prod.log',
// ));

// añadir a continuación cualquier otra opción de configuración de producción

// Sal para el codificador...
$app['salt'] = 'tralariquetevi123412314kdfmdnñlanfñlkd';
