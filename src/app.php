<?php

/**
 * This file is part of the iContraincendios app.
 * 
 * (c) Santos Jiménez <sjimenez77@gmail.com>
 */

use Silex\Application;

$app = new Application();

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    // descomenta esta línea para activar la cache de Twig
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));


$app['debug'] = true;

return $app;