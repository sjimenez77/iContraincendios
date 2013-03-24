<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Controladores relacionados con la parte de inserción de datos para los diferentes
// usos de las instalaciones
$usuarios = $app['controllers_factory'];


// -- USUARIOS -----------------------------------------------------------------
$usuarios->get('/{user}', function (Request $request, $user) use ($app) {

    // Comprobamos que es el usuario el que consulta su propio perfil 
    // y no el de otro usuario, salvo que sea administrador
    $token = $app['security']->getToken();
    if (null !== $token) {
        // Obtenemos al usuario autenticado, su nombre y si es administrador 
        $usuario_autenticado = $token->getUser();
        $nombre_usuario_autenticado = $usuario_autenticado->getUsername();
        $esAdmin = in_array('ROLE_ADMIN', $usuario_autenticado->getRoles());

        if ($user != $nombre_usuario_autenticado and !$esAdmin) {
            // El usuario autenticado no es administrador y está intentando
            // ver el perfil de otro usuario
            return $app->redirect($app['url_generator']->generate('inicio'));
        }

    } else {
        // Si no está autenticado no debería pasar por aquí
        // pero aún así lo mandamos a la portada
        return $app->redirect($app['url_generator']->generate('inicio'));
    }
    
    // Obtenemos el ID de usuario y sus informes
    $usuario_consulta = $app['db']->fetchAssoc(
        "SELECT * FROM usuarios WHERE username = ?",
        array($user)
        );

    // Obtenemos el total de informes
    $total_informes = $app['db']->fetchColumn(
        "SELECT count(*) FROM informes WHERE idUsuarios = ?",
        array($usuario_consulta['idUsuarios'])
        );

    // Obtenemos el total de informes para cada uso
    $total_informes_uso = $app['db']->fetchAll(
        "SELECT inf.idUsos AS idUsos, u.Tipo AS Tipo, count(*) AS Total FROM informes inf, usos u WHERE inf.idUsuarios = ? AND inf.idUsos = u.idUsos GROUP BY idUsos",
        array($usuario_consulta['idUsuarios'])
        );

    return $app['twig']->render('usuario.twig', array(
        'id' => $usuario_consulta['idUsuarios'],
        'nombre' => $usuario_consulta['nombre'],
        'apellidos' => $usuario_consulta['apellidos'],
        'total_informes' => $total_informes,
        'total_informes_uso' => $total_informes_uso
    ));

})
->bind('usuario');
// -----------------------------------------------------------------------------

$usuarios->get('/{user}/{tipo}', function (Request $request, $user, $tipo) use ($app) {

    // Comprobamos que es el usuario el que consulta su propio perfil 
    // y no el de otro usuario, salvo que sea administrador
    $token = $app['security']->getToken();
    if (null !== $token) {
        // Obtenemos al usuario autenticado, su nombre y si es administrador 
        $usuario_autenticado = $token->getUser();
        $nombre_usuario_autenticado = $usuario_autenticado->getUsername();
        $esAdmin = in_array('ROLE_ADMIN', $usuario_autenticado->getRoles());

        if ($user != $nombre_usuario_autenticado and !$esAdmin) {
            // El usuario autenticado no es administrador y está intentando
            // ver el perfil de otro usuario
            return $app->redirect($app['url_generator']->generate('inicio'));
        }

    } else {
        // Si no está autenticado no debería pasar por aquí
        // pero aún así lo mandamos a la portada
        return $app->redirect($app['url_generator']->generate('inicio'));
    }
    
    // Obtenemos el ID de usuario y sus informes
    $usuario_consulta = $app['db']->fetchAssoc(
        "SELECT * FROM usuarios WHERE username = ?",
        array($user)
        );

    // Obtenemos el título del tipo de uso
    $tipo_uso = $app['db']->fetchColumn(
        "SELECT Tipo FROM usos WHERE idUsos = ?",
        array($tipo)
        );

    // Obtenemos el total de informes para cada uso
    $total_informes_tipo = $app['db']->fetchAll(
        "SELECT * FROM informes WHERE idUsuarios = ? AND idUsos = ? ORDER BY fecha DESC",
        array(
        	$usuario_consulta['idUsuarios'],
        	$tipo
        	)
        );

    return $app['twig']->render('usuario_informes.twig', array(
        'id' => $usuario_consulta['idUsuarios'],
        'nombre' => $usuario_consulta['nombre'],
        'apellidos' => $usuario_consulta['apellidos'],
        'id_uso' => $tipo,
        'tipo_uso' => $tipo_uso,
        'total_informes_tipo' => $total_informes_tipo
    ));
})
->bind('usuario.total_informes_tipo');

// -----------------------------------------------------------------------------

$usuarios->get('/{user}/{tipo}/{id_informe}', function (Request $request, $user, $tipo, $id_informe) use ($app) {
    // Comprobamos que es el usuario el que consulta su propio perfil 
    // y no el de otro usuario, salvo que sea administrador
    $token = $app['security']->getToken();
    if (null !== $token) {
        // Obtenemos al usuario autenticado, su nombre y si es administrador 
        $usuario_autenticado = $token->getUser();
        $nombre_usuario_autenticado = $usuario_autenticado->getUsername();
        $esAdmin = in_array('ROLE_ADMIN', $usuario_autenticado->getRoles());

        if ($user != $nombre_usuario_autenticado and !$esAdmin) {
            // El usuario autenticado no es administrador y está intentando
            // ver el perfil de otro usuario
            return $app->redirect($app['url_generator']->generate('inicio'));
        }

    } else {
        // Si no está autenticado no debería pasar por aquí
        // pero aún así lo mandamos a la portada
        return $app->redirect($app['url_generator']->generate('inicio'));
    }

    // Obtenemos el ID de usuario y sus informes
    $usuario_consulta = $app['db']->fetchAssoc(
        "SELECT * FROM usuarios WHERE username = ?",
        array($user)
        );

    // Obtenemos el informe concreto
    $informe = $app['db']->fetchAssoc(
        "SELECT * FROM informes WHERE idInformes = ?",
        array($id_informe)
        );

    // Obtenemos el título del tipo de uso del informe obtenido
    $tipo_uso = $app['db']->fetchColumn(
        "SELECT Tipo FROM usos WHERE idUsos = ?",
        array($informe['idUsos'])
        );
    // Obtenemos el título del tipo de uso de campo de la URL
    $tipo_uso_url = $app['db']->fetchColumn(
        "SELECT Tipo FROM usos WHERE idUsos = ?",
        array($tipo)
        );

    // Comprobamos errores al introducir manualmente las URLs
    $errores = null;

    if ($informe == False) {
    	// No hay ningún resultado en la consulta
    	$errores[] = "El informe con identificador <strong>".$id_informe."</strong> no existe.";
    } else {
	    if ($informe['idUsos'] != $tipo) {
	    	if (count($errores) > 0)
	            array_push($errores, "El informe con identificador <strong>".$id_informe."</strong> no corresponde con el uso <strong>".$tipo_uso_url."</strong>");
	        else
	    		$errores[] = "El informe con identificador <strong>".$id_informe."</strong> no corresponde con el uso <strong>".$tipo_uso."</strong>";
	    }

	    if ($informe['idUsuarios'] != $usuario_consulta['idUsuarios']) {
	    	if (count($errores) > 0)
	            array_push($errores, "Este informe no ha sido realizado por el usuario <strong>".$nombre_usuario_autenticado."</strong>");
	        else
	    		$errores[] = "Este informe no ha sido realizado por el usuario <strong>".$nombre_usuario_autenticado."</strong>";
	    }
    }
    // -----------------------------------------------------------------------------
    

    return $app['twig']->render('informe.twig', array(
        'id' => $usuario_consulta['idUsuarios'],
        'nombre' => $usuario_consulta['nombre'],
        'apellidos' => $usuario_consulta['apellidos'],
        'errores' => $errores,
        'id_uso' => $tipo,
        'tipo_uso' => $tipo_uso,
        'informe' => $informe
    ));
})
->bind('usuario.informe');

// -----------------------------------------------------------------------------
return $usuarios;