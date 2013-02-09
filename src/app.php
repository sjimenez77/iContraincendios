<?php

/**
 * This file is part of the iContraincendios app.
 * 
 * (c) Santos Jiménez <sjimenez77@gmail.com>
 */

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;

$app = new Application();

// Registro de logs de desarrollo Monolog
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/desarrollo.log',
));

// Registro del proveedor de plantillas TWIG
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    // descomenta esta línea para activar la cache de Twig
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));

// activada la cache HTTP
$app->register(new HttpCacheServiceProvider(), array(
   'http_cache.cache_dir' => __DIR__.'/../cache/http',
   'http_cache.esi'       => null,
));

return $app;