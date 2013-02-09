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

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/', function () use ($app) {
	return new Response(
        $app['twig']->render('portada.twig'),
        200, 
        array('Cache-Control' => 'public, max-age=600, s-maxage=600')
    );
})
->bind('portada');
