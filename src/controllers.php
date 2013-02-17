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
$app->get('/', function (Request $request) use ($app) {
	return new Response(
        $app['twig']->render('portada.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        )),
        200/*, 
        array('Cache-Control' => 'public, max-age=600, s-maxage=600')*/
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

// -- LOGIN --------------------------------------------------------------------
$app->post('/login', function(Request $request) use ($app) {
    $usuario = $request->get('user');
    $passwd = $request->get('pass');
    $app['monolog']->addDebug('Usuario: '.$usuario);
    $app['monolog']->addDebug('Password: '.$passwd);

/*    return $app['twig']->render('portada.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));*/
    return $app -> redirect('/');
})
->bind('login');
// -----------------------------------------------------------------------------

// -- REGISTRO -----------------------------------------------------------------
$app->get('/registro', function(Request $request) use ($app) {
    return $app['twig']->render('registro.twig');
})
->bind('registro');

$app->post('/registro_tecnico', function(Request $request) use ($app) {
    
    // Comprobamos que los datos están correctamente almacenados
    $nombre = $request->get('nombre');
    $apellidos = $request->get('apellidos');
    $usuario = $request->get('usuario');
    $pass = $app['security.encoder.digest']->encodePassword($request->get('password'), $app['salt']);

    if (trim($nombre) == '') {
        $errores[] = "No ha introducido su nombre";
    }

    if (trim($apellidos) == '') {
        if (count($errores) > 0)
            array_push($errores, "No ha introducido sus apellidos");
        else
            $errores[] = "No ha introducido sus apellidos";
    }

    if (trim($usuario) == '') {
        if (count($errores) > 0)
            array_push($errores, "No ha introducido su nombre de usuario o email");
        else
            $errores[] = "No ha introducido su nombre de usuario o email";
    }


    // Almacenamos al nuevo usuario
    $app['db']->insert(
        'Usuarios',
        array(
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'usuario' => $usuario,
            'pass' => $pass,
            'roles' => 'ROLE_USER'
        )
    );

    return $app -> redirect('/');
})
->bind('registro_tecnico');
// -----------------------------------------------------------------------------

// -- MENÚ GENERAL -------------------------------------------------------------
$app->get('/quienes', function() use ($app) {
    return $app['twig']->render('quienes.twig');
})
->bind('quienes');

$app->get('/contacto', function() use ($app) {
    return $app['twig']->render('contacto.twig');
})
->bind('contacto');

$app->get('/ayuda', function() use ($app) {
    return $app['twig']->render('ayuda.twig');
})
->bind('ayuda');
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
