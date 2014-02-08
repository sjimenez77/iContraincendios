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

    // Comprobamos errores al introducir manualmente las URLs e inicializamos los valores que le
    // pasamos a la plantilla
    $errores = null;
    
    // Inicializamos las variables resultado
    $extintores = False;
    $bies_25 = False;
    $bies_45 = False;
    $columna_seca = False;
    $sm_alarma = False;
    $sd_incendio = False;
    $hid_exteriores = False;
    $ia_extincion = False;
    $ia_extincion_cocina = False;
    $ia_extincion_centro_transf = False;
    $claves_comentarios = array();
    $lista_comentarios = array();
    $comentarios = array();

    // Datos comunes
    $superficie = null;
    $altura_d = null;
    $altura_a = null;
    $centro_transf = null;
    
    // Inicializamos los posibles parámetros
    $dens_1per = null;
    $cocina_50kW = null;
    $trasteros = null;
    $superficie_trasteros = null;
    $reprografia = null;
    $volumen_construido = null;
    $aloj_50pers = null;
    $cocina_20kW = null;
    $roperos = null;
    $superficie_roperos = null;
    $camas_100 = null;
    $almacenes_fc = null;
    $v_almacenes_fc = null;
    $lab_c = null;
    $v_lab_c = null;
    $zonas_est = null;
    $area_ventas_1500 = null;
    $densidad_cf_500 = null;
    $almacenes_cf_3400 = null;
    $ocupacion_500 = null;
    $tipo_pub_concurrencia = null;
    $talleres_dec = null;
    $robotizado = null;
    $plantas_rasante = null;


    if ($informe == False) {
    
    	// No hay ningún resultado en la consulta
    	$errores[] = "El informe con identificador <strong>".$id_informe."</strong> no existe.";
    
    } else {

	    // Hay un resultado
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

        // Obtenemos los datos específicos de cada uso mediante la BDD
        // en el caso de que no existan errores ¡¡¡¡¡¡ CORREGIR !!!!!!!
        if (count($errores) == 0) {

            // Obtenemos los datos comunes
            $superficie = $informe['superficie'];
            $altura_d = $informe['altura_d'];
            $altura_a = $informe['altura_a'];
            $centro_transf = $informe['centro_transf'];
            
            switch ($tipo_uso) {
                case 'Residencial Vivienda':
                    $dens_1per = $informe['dens_1per']; // 1 -> True, 0 -> False
                    $cocina_50kW = $informe['cocina_50kW'];
                    $trasteros = $informe['trasteros'];
                    $superficie_trasteros = $informe['superficie_trasteros'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 5000 || $altura_d > 28 || $altura_a > 6 || $dens_1per) $hid_exteriores = True;
                    if ($altura_d > 80) $ia_extincion = True;
                    if ($altura_d > 24 || $altura_a > 24) $columna_seca = True;
                    if ($altura_d > 50 || $altura_a > 50) $sm_alarma = True;
                    if ($altura_d > 50 || $altura_a > 50) $sd_incendio = True;
                    if ($trasteros && $superficie_trasteros > 500) $bies_25 = True;
                    if ($cocina_50kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($bies_25) {
                        array_push($claves_comentarios, "bies_25");
                        array_push($lista_comentarios, "Instalación de BIES de 25mm, en las que el riesgo se deba principalmente a materias combustibles sólidas. En nuestro caso los trasteros.");
                    }
                    if ($ia_extincion_cocina) {
                        array_push($claves_comentarios, "ia_extincion_cocina");
                        array_push($lista_comentarios, "Se recomienda instalar un sistema de extinción apto para Clases de Fuego F o K en cocinas.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Administrativo':
                    $dens_1per = $informe['dens_1per']; 
                    $cocina_50kW = $informe['cocina_50kW'];
                    $reprografia = $informe['reprografia'];
                    $volumen_construido = $informe['volumen_construido'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 2000) $bies_25 = True;
                    // Condición antigua -> if ($superficie > 5000 || $altura_d > 28 || $altura_a > 6 || $dens_1per) $hid_exteriores = True;
                    if ($superficie > 5000 && $superficie < 10000 && $dens_1per) $hid_exteriores = True;
                    if ($altura_d > 80) $ia_extincion = True;
                    if ($altura_d > 24 || $altura_a > 24) $columna_seca = True;
                    if ($superficie > 1000) $sm_alarma = True;
                    if ($superficie > 2000) $sd_incendio = True;
                    if ($reprografia && $volumen_construido > 500) $bies_45 = True;
                    if ($cocina_50kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($bies_45) {
                        array_push($claves_comentarios, "bies_45");
                        array_push($lista_comentarios, "Instalación de BIES de 45mm, en las que el riesgo se deba principalmente a materias combustibles sólidas. En los locales de riesgo especial alto.");
                    }
                    if ($sd_incendio && $superficie >= 5000) {
                        array_push($claves_comentarios, "sd_incendio");
                        array_push($lista_comentarios, "El sistema de detección automático de incendios debe instalarse en todo el edificio.");
                    }
                    if ($sd_incendio && $superficie < 5000) {
                        array_push($claves_comentarios, "sd_incendio");
                        array_push($lista_comentarios, "El sistema de detección automático de incendios debe instalarse en las zonas de riesgo especial alto.");
                    }
                    if ($ia_extincion_cocina) {
                        array_push($claves_comentarios, "ia_extincion_cocina");
                        array_push($lista_comentarios, "Se recomienda instalar un sistema de extinción apto para Clases de Fuego F o K en cocinas.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Residencial Público':
                    $aloj_50pers = $informe['aloj_50pers']; 
                    $cocina_20kW = $informe['cocina_20kW'];
                    $roperos = $informe['roperos'];
                    $superficie_roperos = $informe['superficie_roperos'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 1000 || $aloj_50pers) $bies_25 = True;
                    if ($superficie > 2000 || $altura_d > 28 || $altura_a > 6) $hid_exteriores = True;
                    if ($altura_d > 28 || $superficie > 5000) $ia_extincion = True;
                    if ($altura_d > 24 || $altura_a > 24) $columna_seca = True;
                    if ($superficie > 500) {
                        $sm_alarma = True;
                        $sd_incendio = True;
                    }
                    if ($roperos && $superficie_roperos > 100) $bies_25 = True;
                    if ($cocina_20kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($roperos && $superficie_roperos > 100) {
                        array_push($claves_comentarios, "bies_25");
                        array_push($lista_comentarios, "En los locales de riesgo en los que el riesgo se deba principalmente a materias combustibles sólidas, se recomienda la instalación de BIES de 45mm.");
                    }
                    if ($ia_extincion_cocina) {
                        array_push($claves_comentarios, "ia_extincion_cocina");
                        array_push($lista_comentarios, "Se recomienda instalar un sistema de extinción apto para Clases de Fuego F o K en cocinas.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Hospitalario':
                    $camas_100 = $informe['camas_100']; 
                    $cocina_20kW = $informe['cocina_20kW'];
                    $almacenes_fc = $informe['almacenes_fc'];
                    $v_almacenes_fc = $informe['v_almacenes_fc'];
                    $lab_c = $informe['lab_c'];
                    $v_lab_c = $informe['v_lab_c'];
                    $zonas_est = $informe['zonas_est'];
                    // Procesamos datos
                    if ($superficie > 0) {
                        $extintores = True;
                        $bies_25 = True;
                        $sm_alarma = True;
                        $sd_incendio = True;
                    }
                    if ($superficie > 2000 || $altura_d > 28 || $altura_a > 6) $hid_exteriores = True;
                    if ($altura_d > 28 || $superficie > 5000) $ia_extincion = True;
                    if ($altura_d > 15 || $altura_a > 15) $columna_seca = True;
                    if (($almacenes_fc && $v_almacenes_fc > 400) || ($lab_c && $v_lab_c > 400) || $zonas_est) $bies_45 = True;
                    if ($cocina_20kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($extintores && (($almacenes_fc && $v_almacenes_fc > 400) || ($lab_c && $v_lab_c > 500) || $zonas_est)) {
                        array_push($claves_comentarios, "extintores");
                        array_push($lista_comentarios, "En las zonas de riesgo especial alto, cuya superficie construida exceda de 500 m&sup2;, un extintor móvil de 25 kg de polvo o de CO2 por cada 2.500m&sup2; de superficie o fracción.");
                    }
                    if ($bies_45) {
                        // Sitios donde se realizarán la instalación de BIES de 45mm 
                        $bies_45_sitios = "";
                        if ($almacenes_fc && $v_almacenes_fc > 400) 
                            $bies_45_sitios .= "En los almacenes de productos farmaceuticos y clinicos";
                        if ($lab_c && $v_lab_c > 400) 
                            if (strlen($bies_45_sitios) > 0) 
                                $bies_45_sitios .= ", en la zona de laboratorios clínicos";
                            else
                                $bies_45_sitios .= "En la zona de laboratorios clínicos";
                        if ($zonas_est)
                            if (strlen($bies_45_sitios) > 0) 
                                $bies_45_sitios .= "y en la zona de esterilización y almacenajes anejos";
                            else
                                $bies_45_sitios .= "En la zona de esterilización y almacenajes anejos";
                        $bies_45_sitios .= ".";

                        array_push($claves_comentarios, "bies_45");
                        array_push($lista_comentarios, "Instalación de BIES de 45mm, en las que el riesgo se deba principalmente a materias combustibles sólidas. ".$bies_45_sitios);
                    }
                    if ($superficie > 0 && $camas_100) {
                        array_push($claves_comentarios, "sd_incendio");
                        array_push($lista_comentarios, "El edificio debe contar con comunicación telefónica directa con el servicio de bomberos.");
                    }
                    if ($ia_extincion_cocina) {
                        array_push($claves_comentarios, "ia_extincion_cocina");
                        array_push($lista_comentarios, "Se recomienda instalar un sistema de extinción apto para Clases de Fuego F o K en cocinas.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Docente':
                    $cocina_50kW = $informe['cocina_50kW'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 2000) $bies_25 = True;
                    if ($superficie > 5000 || $altura_d > 28 || $altura_a > 6) $hid_exteriores = True;
                    if ($altura_d > 28 || $superficie > 5000) $ia_extincion = True;
                    if ($altura_d > 24 || $altura_a > 24) $columna_seca = True;
                    if ($superficie > 1000) $sm_alarma = True;
                    if ($superficie > 2000) $sd_incendio = True;
                    if ($cocina_50kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($sd_incendio && $superficie >= 5000) {
                        array_push($claves_comentarios, "sd_incendio");
                        array_push($lista_comentarios, "El sistema de detección automático de incendios debe instalarse en todo el edificio.");
                    }
                    if ($sd_incendio && $superficie < 5000) {
                        array_push($claves_comentarios, "sd_incendio");
                        array_push($lista_comentarios, "El sistema de detección automático de incendios debe instalarse en las zonas de riesgo especial alto.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Comercial': 
                    $cocina_50kW = $informe['cocina_50kW'];
                    $area_ventas_1500 = $informe['area_ventas_1500'];
                    $densidad_cf_500 = $informe['densidad_cf_500'];
                    $almacenes_cf_3400 = $informe['almacenes_cf_3400'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 500) $bies_25 = True;
                    if ($superficie > 1000 || $altura_d > 28 || $altura_a > 6) $hid_exteriores = True;
                    if ($area_ventas_1500 && $densidad_cf_500) $ia_extincion = True;
                    if ($altura_d > 24 || $altura_a > 24) $columna_seca = True;
                    if ($superficie > 1000) $sm_alarma = True;
                    if ($superficie > 2000) $sd_incendio = True;
                    if ($almacenes_cf_3400) $bies_45 = True;
                    if ($cocina_50kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($superficie > 1000) {
                        array_push($claves_comentarios, "extintores");
                        array_push($lista_comentarios, "En toda agrupación de locales de riesgo especial medio y alto cuya superficie construida total excede de 1.000 m&sup2;, extintores móviles de 50 kg de polvo.");
                    }
                    if ($bies_45) {
                        array_push($claves_comentarios, "bies_45");
                        array_push($lista_comentarios, "Instalación de BIES de 45mm, en las zonas de riesgo especial alto en las que el riesgo se deba principalmente a materias combustibles sólidas.");
                    }
                    if ($ia_extincion) {
                        array_push($claves_comentarios, "ia_extincion");
                        array_push($lista_comentarios, "Contará con la instalación automática de extinción, tanto el área pública de ventas, como los locales y zonas de riesgo especial medio y alto.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Pública Concurrencia':
                    $cocina_50kW = $informe['cocina_50kW'];
                    $ocupacion_500 = $informe['ocupacion_500'];
                    $tipo_pub_concurrencia = $informe['tipo_pub_concurrencia'];
                    $talleres_dec = $informe['talleres_dec'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 500) $bies_25 = True;
                    if ($superficie > 500 || $altura_d > 28 || $altura_a > 6) $hid_exteriores = True;
                    if ($ocupacion_500) {
                        $ia_extincion = True;
                        $sm_alarma = True;
                    }
                    if ($altura_d > 24 || $altura_a > 24) $columna_seca = True;
                    if ($superficie > 1000) $sd_incendio = True;
                    if ($talleres_dec) $bies_45 = True;
                    if ($cocina_50kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($sm_alarma) {
                        array_push($claves_comentarios, "sm_alarma");
                        array_push($lista_comentarios, "El sistema de alarma debe ser apto para emitir mensajes por megafonía.");
                    }
                    if ($superficie > 5000 && ($tipo_pub_concurrencia == "Recintos deportivos" || $tipo_pub_concurrencia = "Otros")) {
                        array_push($claves_comentarios, "hid_exteriores");
                        array_push($lista_comentarios, "Al ser recinto deportivo o sin especificar es necesaria la instalación del sistema de hidrantes sólo si es mayor de 5000 m&sup2;.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                case 'Aparcamiento':
                    $cocina_50kW = $informe['cocina_50kW'];
                    $robotizado = $informe['robotizado'];
                    $plantas_rasante = $informe['plantas_rasante'];
                    // Procesamos datos
                    if ($superficie > 0) $extintores = True;
                    if ($superficie > 500) {
                        $bies_25 = True;
                        $sd_incendio = True;
                    }
                    if ($superficie > 1000 || $altura_d > 28 || $altura_a > 6) $hid_exteriores = True;
                    if ($plantas_rasante) $columna_seca = True;
                    if ($robotizado) {
                        $ia_extincion = True;
                        $sm_alarma = True;
                    }
                    if ($cocina_50kW) $ia_extincion_cocina = True;
                    if ($centro_transf) $ia_extincion_centro_transf = True;
                    // Rellenamos los comentarios asociados
                    if ($superficie > 500 && $robotizado) {
                        array_push($claves_comentarios, "bies_25");
                        array_push($lista_comentarios, "Se excluye de los aparcamientos robotizados, la necesidad de instalar un Sistema de Bocas de Incendio Equipadas.");
                    }
                    if ($sm_alarma) {
                        array_push($claves_comentarios, "sm_alarma");
                        array_push($lista_comentarios, "Los aparcamientos robotizados dispondrán de pulsadores de alarma en todo caso.");
                    }
                    // Combinamos los arrays de claves y de comentarios
                    $comentarios = array_combine($claves_comentarios, $lista_comentarios);
                    break;
                
                default:
                    break;
            }
    }



    }
    // -----------------------------------------------------------------------------
    

    return $app['twig']->render('informe.twig', array(
        'id' => $usuario_consulta['idUsuarios'],
        'nombre' => $usuario_consulta['nombre'],
        'apellidos' => $usuario_consulta['apellidos'],
        'errores' => $errores,
        'id_uso' => $tipo,
        'id_informe' => $id_informe,
        'opcion' => $tipo_uso,
        'fecha' => $informe['fecha'],
        'direccion' => $informe['direccion'],
        'cpostal' => $informe['cpostal'],
        'localidad' => $informe['localidad'],
        'provincia' => $informe['provincia'],
        'superficie' => $informe['superficie'],
        'altura_d' => $informe['altura_d'],
        'altura_a' => $informe['altura_a'],
        'centro_transf' => $informe['centro_transf'],
        'dens_1per' => $informe['dens_1per'],
        'cocina_50kW' => $informe['cocina_50kW'],
        'trasteros' => $informe['trasteros'],
        'superficie_trasteros' => $informe['superficie_trasteros'],
        'reprografia' => $informe['reprografia'],
        'volumen_construido' => $informe['volumen_construido'],
        'aloj_50pers' => $informe['aloj_50pers'],
        'cocina_20kW' => $informe['cocina_20kW'],
        'roperos' => $informe['roperos'],
        'superficie_roperos' => $informe['superficie_roperos'],
        'camas_100' => $informe['camas_100'],
        'almacenes_fc' => $informe['almacenes_fc'],
        'v_almacenes_fc' => $informe['v_almacenes_fc'],
        'lab_c' => $informe['lab_c'],
        'v_lab_c' => $informe['v_lab_c'],
        'zonas_est' => $informe['zonas_est'],
        'area_ventas_1500' => $informe['area_ventas_1500'],
        'densidad_cf_500' => $informe['densidad_cf_500'],
        'almacenes_cf_3400' => $informe['almacenes_cf_3400'],
        'ocupacion_500' => $informe['ocupacion_500'],
        'tipo_pub_concurrencia' => $informe['tipo_pub_concurrencia'],
        'talleres_dec' => $informe['talleres_dec'],
        'robotizado' => $informe['robotizado'],
        'plantas_rasante' => $informe['plantas_rasante'],
        'extintores' => $extintores,
        'bies_25' => $bies_25,
        'bies_45' => $bies_45,
        'columna_seca' => $columna_seca,
        'sm_alarma' =>  $sm_alarma,
        'sd_incendio' => $sd_incendio,
        'hid_exteriores' => $hid_exteriores,
        'ia_extincion' => $ia_extincion,
        'ia_extincion_cocina' => $ia_extincion_cocina,
        'ia_extincion_centro_transf' => $ia_extincion_centro_transf,
        'comentarios' => $comentarios
    ));
})
->bind('usuario.informe');

// -----------------------------------------------------------------------------

$usuarios->get('/{user}/{tipo}/{id_informe}/mapa', function (Request $request, $user, $tipo, $id_informe) use ($app) {
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

    // Obtenemos el total de informes para cada uso
    $total_informes_tipo = $app['db']->fetchAll(
        "SELECT * FROM informes WHERE idUsuarios = ? AND idUsos = ? ORDER BY fecha DESC",
        array(
            $usuario_consulta['idUsuarios'],
            $tipo
            )
        );

    // Comprobamos errores al introducir manualmente las URLs e inicializamos los valores que le
    // pasamos a la plantilla
    $errores = null;
    
    if ($informe == False) {
    
        // No hay ningún resultado en la consulta
        $errores[] = "El informe con identificador <strong>".$id_informe."</strong> no existe.";
    
    } else {

        // Hay un resultado
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

        return $app['twig']->render('mapa_informe.twig', array(
            'id' => $usuario_consulta['idUsuarios'],
            'nombre' => $usuario_consulta['nombre'],
            'apellidos' => $usuario_consulta['apellidos'],
            'errores' => $errores,
            'usuario' => $user,
            'id_uso' => $tipo,
            'id_informe' => $id_informe,
            'tipo_uso' => $tipo_uso,
            'direccion' => $informe['direccion'],
            'cpostal' => $informe['cpostal'],
            'localidad' => $informe['localidad'],
            'provincia' => $informe['provincia'],
        ));
    }
})
->bind('usuario.mapa');
return $usuarios;