<?php

use Silex\Provider\MonologServiceProvider;

// incluir primero la configuraci贸n de produccion
require __DIR__.'/prod.php';

// activar el modo debug en la aplicacion
$app['debug'] = true;

// Sal para el codificador...
$app['salt'] = 'tralariquetevi123412314kdfmdnñlanfñlkd';

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/dev.log',
));