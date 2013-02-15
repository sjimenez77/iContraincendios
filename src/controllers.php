<?php

/**
 * This file is part of the iContraincendios app.
 * 
 * (c) Santos Jiménez <sjimenez77@gmail.com>
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// -- PORTADA -----------------------------------------------------------------
$app->get('/', function () use ($app) {
	return new Response(
        $app['twig']->render('portada.twig'),
        200, 
        array('Cache-Control' => 'public, max-age=600, s-maxage=600')
    );
})
->bind('portada');

$app->get('/inicio', function () use ($app) {
	return $app -> redirect('/');
});

$app->get('/index', function () use ($app) {
	return $app -> redirect('/');
});
// -----------------------------------------------------------------------------

// -- USOS DE INSTALACIONES ----------------------------------------------------
$app->get('/residencial_vivienda', function () use ($app) {
    return $app['twig']->render(
    	'residencial_vivienda.twig', 
    	array('opcion' => 'residencial_vivienda')
    );
})
->bind('uso.residencial_vivienda');

$app->get('/administrativo', function () use ($app) {
    return $app['twig']->render(
    	'administrativo.twig', 
    	array('opcion' => 'administrativo')
    );
})
->bind('uso.administrativo');

$app->get('/residencial_publico', function () use ($app) {
    return $app['twig']->render(
    	'residencial_publico.twig', 
    	array('opcion' => 'residencial_publico')
    );
})
->bind('uso.residencial_publico');

$app->get('/hospitalario', function () use ($app) {
    return $app['twig']->render(
    	'hospitalario.twig', 
    	array('opcion' => 'hospitalario')
    );
})
->bind('uso.hospitalario');

$app->get('/docente', function () use ($app) {
    return $app['twig']->render(
    	'docente.twig', 
    	array('opcion' => 'docente')
    );
})
->bind('uso.docente');

$app->get('/comercial', function () use ($app) {
    return $app['twig']->render(
    	'comercial.twig', 
    	array('opcion' => 'comercial')
    );
})
->bind('uso.comercial');

$app->get('/publica_concurrencia', function () use ($app) {
    return $app['twig']->render(
    	'publica_concurrencia.twig', 
    	array('opcion' => 'publica_concurrencia')
    );
})
->bind('uso.publica_concurrencia');

$app->get('/aparcamiento', function () use ($app) {
    return $app['twig']->render(
    	'aparcamiento.twig', 
    	array('opcion' => 'aparcamiento')
    );
})
->bind('uso.aparcamiento');
// ----------------------------------------------------------------------------
