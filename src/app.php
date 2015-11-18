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
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use ic\UserProvider; // Mi proveedor de usuarios

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
        'password'  => 'root',
        'charset'   => 'utf8',
    ),
));

// Registro del proveedor de sesiones
$app->register(new SessionServiceProvider());

// Registro del proveedor de seguridad
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
		'secured' => array(
			'pattern' => '^/.*$',
        	'anonymous' => true,
            'form' => array(
            	'login_path' => '/login',
            	'check_path' => '/login_check'
            ),
            'logout' => array(
                'logout_path' => '/logout',             // url to call for logging out
                'target' => '/',
                'invalidate_session' => true,
            ),
            'users' => $app->share(function() use ($app) {
                // Specific class UserProvider is described below
                return new UserProvider($app['db']);
            }),
		),
    ),
    'security.access_rules' => array(
        array('^/backend.+$', 'ROLE_ADMIN', 'https'),
        array('^/user.+$', 'ROLE_USER'),
        array('^/archivar.*$', 'ROLE_USER')
    ),
));

// Registro del proveedor de plantillas TWIG
$app->register(new TwigServiceProvider(), array(
    // descomenta esta línea para activar la cache de Twig y añade una coma
    'twig.path'    => array(__DIR__.'/../templates'),
    'twig.options' => array('cache' => __DIR__.'/../cache/twig')
));

// activada la cache HTTP
$app->register(new HttpCacheServiceProvider(), array(
   'http_cache.cache_dir' => __DIR__.'/../cache/http',
   'http_cache.esi'       => null,
));

return $app;
