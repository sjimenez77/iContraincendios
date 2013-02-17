<?php

/**
 * This file is part of the iContraincendios app.
 * 
 * (c) Santos Jiménez <sjimenez77@gmail.com>
 */

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use ic;

$app = new Application();

// Registro del generador de URLs
$app->register(new UrlGeneratorServiceProvider());

// Registro del proveedor Doctrine DBAL para el acceso a BDD
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array (
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'fire',
        'user'      => 'root',
        'password'  => 'cornell1',
        'charset'   => 'utf8',
    ),
));

// Registro del proveedor de sesiones
$app->register(new SessionServiceProvider());

// Registro del proveedor de seguridad
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'user' => array(
            'pattern' => '^/user.*$',
            'anonymous' => false,
            'form' => array(
            	'login_path' => '/', 
            	'check_path' => '/login'
            	),
            'logout' => array('logout_path' => '/logout'), // url to call for logging out
            'users' => $app->share(function() use ($app) {
                // Specific class ic\UserProvider is described below
                return new ic\UserProvider($app['db']);
            }),
        ),
        'admin' => array(
            'pattern' => '^/admin.*$',
            'anonymous' => false, 
            'form' => array(
            	'login_path' => '/', 
            	'check_path' => '/login'
            ),
            'logout' => array('logout_path' => '/logout'), // url to call for logging out
            'users' => $app->share(function() use ($app) {
                // Specific class ic\UserProvider is described below
                return new ic\UserProvider($app['db']);
            }),
        ),
		'unsecured' => array(
			'pattern' => '^/.*$',
        	'anonymous' => true,
            'form' => array(
            	'login_path' => '/', 
            	'check_path' => '/login'
            ),
            'logout' => array('logout_path' => '/logout'), // url to call for logging out
		),
    ),
));

// Registro del proveedor de plantillas TWIG
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates')
    // descomenta esta línea para activar la cache de Twig y añade una coma
    // 'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));

// activada la cache HTTP
$app->register(new HttpCacheServiceProvider(), array(
   'http_cache.cache_dir' => __DIR__.'/../cache/http',
   'http_cache.esi'       => null,
));

return $app;