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
    // Borramos los datos de formulario almacenados en la sesión si existen
    if ($app['session']->has('formData'))
    {
        $app['session']->remove('formData');
    }

    return new Response(
        $app['twig']->render('portada.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        )),
        200/*, 
        array('Cache-Control' => 'public, max-age=300, s-maxage=300')*/
    );
})
->bind('portada');

$app->get('/portada', function() use ($app) {
    return $app -> redirect($app['url_generator']->generate('portada'));
})
->bind('inicio');
// -----------------------------------------------------------------------------

// -- GENERAL ------------------------------------------------------------------
$app->get('/quienes', function (Request $request) use ($app) {
    // Borramos los datos de formulario almacenados en la sesión si existen
    if ($app['session']->has('formData'))
    {
        $app['session']->remove('formData');
    }

    return $app['twig']->render('quienes.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})
->bind('quienes');

$app->get('/contacto', function(Request $request) use ($app) {
    // Borramos los datos de formulario almacenados en la sesión si existen
    if ($app['session']->has('formData'))
    {
        $app['session']->remove('formData');
    }

    return $app['twig']->render('contacto.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
})
->bind('contacto');

$app->post('/contacto', function(Request $request) use ($app) {
    return $app['twig']->render('contacto.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
})
->bind('contacto.post');

$app->get('/ayuda', function (Request $request) use ($app) {
    // Borramos los datos de formulario almacenados en la sesión si existen
    if ($app['session']->has('formData'))
    {
        $app['session']->remove('formData');
    }

    return $app['twig']->render('ayuda.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})
->bind('ayuda');
// -----------------------------------------------------------------------------

// -- LOGIN --------------------------------------------------------------------
$app->get('/login', function(Request $request) use ($app) {
    // Borramos los datos de formulario almacenados en la sesión si existen
    if ($app['session']->has('formData'))
    {
        $app['session']->remove('formData');
    }

    return $app['twig']->render('portada.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})
->bind('login');
// -----------------------------------------------------------------------------

// -- USOS DE INSTALACIONES ----------------------------------------------------
$app->mount('/uso', include 'usos.php');
// -----------------------------------------------------------------------------

// -- USUARIOS -----------------------------------------------------------------
$app->mount('/user', include 'usuarios.php');
// -----------------------------------------------------------------------------

// -- REGISTRO -----------------------------------------------------------------
$app->get('/registro', function(Request $request) use ($app) {
    // Borramos los datos de formulario almacenados en la sesión si existen
    if ($app['session']->has('formData'))
    {
        $app['session']->remove('formData');
    }

    return $app['twig']->render('registro.twig', array(
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'errores' => null,
        'datos' => array(
            'nombre' => null, 
            'apellidos' => null,
            'usuario' => null
        )
    ));
})
->bind('registro');

$app->post('/registro_tecnico', function(Request $request) use ($app) {
    // Comprobamos que los datos están correctamente almacenados
    $nombre = trim($request->get('nombre'));
    $apellidos = trim($request->get('apellidos'));
    $usuario = trim($request->get('usuario'));
    $pass = trim($request->get('password'));
    $errores = null;

    if ($nombre == '') {
        $errores[] = "No ha introducido su nombre";
    }

    if ($apellidos == '') {
        if (count($errores) > 0)
            array_push($errores, "No ha introducido sus apellidos");
        else
            $errores[] = "No ha introducido sus apellidos";
    }

    if ($usuario == '') {
        if (count($errores) > 0)
            array_push($errores, "No ha introducido su nombre de usuario o email");
        else
            $errores[] = "No ha introducido su nombre de usuario o email";
    } elseif (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) { 
        // Filtramos el usuario para comprobar que sea un email válido
        if (count($errores) > 0)
            array_push($errores, "El usuario/email <".$usuario."> no es válido");
        else
            $errores[] = "El usuario/email <".$usuario."> no es válido";
    } else {
        // Comprobamos que el usuario no exista ya en la BDD
        $usuario_consulta = $app['db']->fetchAssoc(
            "SELECT * FROM usuarios WHERE username = ?",
            array($usuario)
        );
        if ($usuario == $usuario_consulta['username']) {
            if (count($errores) > 0)
                array_push($errores, "El usuario <strong>".$usuario."</strong> ya está registrado");
            else
                $errores[] = "El usuario <strong>".$usuario."</strong> ya está registrado";
        }
    }

    if ($pass == '') {
        if (count($errores) > 0)
            array_push($errores, "No ha introducido ninguna contraseña");
        else
            $errores[] = "No ha introducido ninguna contraseña";
    } else {
        // Codificamos la contraseña
        // Generamos un salt aleatorio para este usuario
        // $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $salt = '';
        
        // DEBUG
        $app['monolog']->addDebug('Salt: '.$salt);
        $app['monolog']->addDebug('Password: '.$pass);
        
        $pass = $app['security.encoder.digest']->encodePassword($pass, $salt);
        $app['monolog']->addDebug('Password Codificada: '.$pass);
    }

    if (count($errores) > 0) {
        // Volvemos a la pantalla de registro con los errores
        return $app['twig']->render('registro.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'errores' => $errores,
            'datos' => array(
                'nombre' => $nombre, 
                'apellidos' => $apellidos,
                'usuario' => $usuario
            )
        ));
    } else {
        // Almacenamos al nuevo usuario
        $app['db']->insert(
            'usuarios',
            array(
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'username' => $usuario,
                'password' => $pass,
                'salt' => $salt,
                'roles' => 'ROLE_USER'
            )
        );
        return $app -> redirect($app['url_generator']->generate('inicio'));
    }

})
->bind('registro_tecnico');
// -----------------------------------------------------------------------------