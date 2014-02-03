<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Controladores relacionados con la parte de inserción de datos para los diferentes
// usos de las instalaciones
$usos_ins = $app['controllers_factory'];


// -- USOS DE INSTALACIONES ----------------------------------------------------
$usos_ins->get('/residencial_vivienda', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'residencial_vivienda.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Residencial Vivienda'
        )
    );
})
->bind('uso.residencial_vivienda');

$usos_ins->get('/administrativo', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'administrativo.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Administrativo'
        )
    );
})
->bind('uso.administrativo');

$usos_ins->get('/residencial_publico', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'residencial_publico.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Residencial Público'
        )
    );
})
->bind('uso.residencial_publico');

$usos_ins->get('/hospitalario', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'hospitalario.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Hospitalario'
        )
    );
})
->bind('uso.hospitalario');

$usos_ins->get('/docente', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'docente.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Docente'
        )
    );
})
->bind('uso.docente');

$usos_ins->get('/comercial', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'comercial.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Comercial'
        )
    );
})
->bind('uso.comercial');

$usos_ins->get('/publica_concurrencia', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'publica_concurrencia.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Pública Concurrencia'
        )
    );
})
->bind('uso.publica_concurrencia');

$usos_ins->get('/aparcamiento', function (Request $request) use ($app) {
    return $app['twig']->render(
    	'aparcamiento.twig', 
    	array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => 'Aparcamiento'
        )
    );
})
->bind('uso.aparcamiento');

// --- PROCESO DE LOS FORMULARIOS ---------------------------------------------
$usos_ins->post('/resultados', function (Request $request) use ($app) {
    // Obtenemos los datos del formulario
    $opcion = $request->get('uso');

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

    // Obtenemos los datos comunes
    $superficie = trim($request->get('superficie'));
    $altura_d = trim($request->get('altura_d'));
    $altura_a = trim($request->get('altura_a'));
    $centro_transf = ($request->get('centro_transf')=='si') ? 1 : 0;
    
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

    // Obtenemos los datos específicos de cada uso
    switch ($opcion) {
        case 'Residencial Vivienda':
            $dens_1per = ($request->get('dens_1per')=='si') ? 1 : 0; // 1 -> True, 0 -> False
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $trasteros = ($request->get('trasteros')=='si') ? 1 : 0;
            $superficie_trasteros = trim($request->get('superficie_trasteros'));
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
            $dens_1per = ($request->get('dens_1per')=='si') ? 1 : 0; // 1 -> True, 0 -> False
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $reprografia = ($request->get('reprografia')=='si') ? 1 : 0;
            $volumen_construido = trim($request->get('volumen_construido'));
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
            $aloj_50pers = ($request->get('aloj_50pers')=='si') ? 1 : 0; 
            $cocina_20kW = ($request->get('cocina_20kW')=='si') ? 1 : 0;
            $roperos = ($request->get('roperos')=='si') ? 1 : 0;
            $superficie_roperos = trim($request->get('superficie_roperos'));
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
            if ($roperos && $superficie_roperos > 500) $bies_45 = True;
            if ($cocina_20kW) $ia_extincion_cocina = True;
            if ($centro_transf) $ia_extincion_centro_transf = True;
            // Rellenamos los comentarios asociados
            if ($bies_45) {
                array_push($claves_comentarios, "bies_45");
                array_push($lista_comentarios, "Instalación de BIES de 45mm, en las que el riesgo se deba principalmente a materias combustibles sólidas. En los locales de riesgo especial alto.");
            }
            if ($ia_extincion_cocina) {
                array_push($claves_comentarios, "ia_extincion_cocina");
                array_push($lista_comentarios, "Se recomienda instalar un sistema de extinción apto para Clases de Fuego F o K en cocinas.");
            }
            // Combinamos los arrays de claves y de comentarios
            $comentarios = array_combine($claves_comentarios, $lista_comentarios);
            break;
        
        case 'Hospitalario':
            $camas_100 = ($request->get('camas_100')=='si') ? 1 : 0; 
            $cocina_20kW = ($request->get('cocina_20kW')=='si') ? 1 : 0;
            $almacenes_fc = ($request->get('almacenes_fc')=='si') ? 1 : 0;
            $v_almacenes_fc = trim($request->get('v_almacenes_fc'));
            $lab_c = ($request->get('lab_c')=='si') ? 1 : 0;
            $v_lab_c = trim($request->get('v_lab_c'));
            $zonas_est = ($request->get('zonas_est')=='si') ? 1 : 0;
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
                array_push($lista_comentarios, "En las zonas de riesgo especial alto, cuya superficie construida exceda de 500 m&sup2;, un extintor móvil de 25 kg de polvo o de CO<sub>2</sub> por cada 2.500m&sup2; de superficie o fracción.");
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
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
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
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $area_ventas_1500 = ($request->get('area_ventas_1500')=='si') ? 1 : 0;
            $densidad_cf_500 = ($request->get('densidad_cf_500')=='si') ? 1 : 0;
            $almacenes_cf_3400 = ($request->get('almacenes_cf_3400')=='si') ? 1 : 0;
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
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $ocupacion_500 = ($request->get('ocupacion_500')=='si') ? 1 : 0;
            $tipo_pub_concurrencia = $request->get('tipo_pub_concurrencia');
            $talleres_dec = ($request->get('talleres_dec')=='si') ? 1 : 0;
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
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $robotizado = ($request->get('robotizado')=='si') ? 1 : 0;
            $plantas_rasante = ($request->get('plantas_rasante')=='si') ? 1 : 0;
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

    // Construimos los resultados en función de los datos rellenados
    return $app['twig']->render(
        'resultados.twig',
        array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'opcion' => $opcion,
            'superficie' => $superficie,
            'altura_d' => $altura_d,
            'altura_a' => $altura_a,
            'centro_transf' => $centro_transf,
            'dens_1per' => $dens_1per,
            'cocina_50kW' => $cocina_50kW,
            'trasteros' => $trasteros,
            'superficie_trasteros' => $superficie_trasteros,
            'reprografia' => $reprografia,
            'volumen_construido' => $volumen_construido,
            'aloj_50pers' => $aloj_50pers,
            'cocina_20kW' => $cocina_20kW,
            'roperos' => $roperos,
            'superficie_roperos' => $superficie_roperos,
            'camas_100' => $camas_100,
            'almacenes_fc' => $almacenes_fc,
            'v_almacenes_fc' => $v_almacenes_fc,
            'lab_c' => $lab_c,
            'v_lab_c' => $v_lab_c,
            'zonas_est' => $zonas_est,
            'area_ventas_1500' => $area_ventas_1500,
            'densidad_cf_500' => $densidad_cf_500,
            'almacenes_cf_3400' => $almacenes_cf_3400,
            'ocupacion_500' => $ocupacion_500,
            'tipo_pub_concurrencia' => $tipo_pub_concurrencia,
            'talleres_dec' => $talleres_dec,
            'robotizado' => $robotizado,
            'plantas_rasante' => $plantas_rasante,
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
        )
    );
})
->bind('uso.resultados');

$usos_ins->post('/archivar', function (Request $request) use ($app) {
    // Obtenemos todos los campos
    $opcion = $request->get('uso');
    $superficie = $request->get('superficie');
    $altura_d = $request->get('altura_d');
    $altura_a = $request->get('altura_a');
    $centro_transf = $request->get('centro_transf');
    $dens_1per = $request->get('dens_1per'); 
    $cocina_50kW = $request->get('cocina_50kW');
    $trasteros = $request->get('trasteros');
    $superficie_trasteros = $request->get('superficie_trasteros');
    $reprografia = $request->get('reprografia');
    $volumen_construido = $request->get('volumen_construido');
    $aloj_50pers = $request->get('aloj_50pers');
    $cocina_20kW = $request->get('cocina_20kW');
    $roperos = $request->get('roperos');
    $superficie_roperos = $request->get('superficie_roperos');
    $camas_100 = $request->get('camas_100');
    $almacenes_fc = $request->get('almacenes_fc');
    $v_almacenes_fc = $request->get('v_almacenes_fc');
    $lab_c = $request->get('lab_c');
    $v_lab_c = $request->get('v_lab_c');
    $zonas_est = $request->get('zonas_est');
    $area_ventas_1500 = $request->get('area_ventas_1500');
    $densidad_cf_500 = $request->get('densidad_cf_500');
    $almacenes_cf_3400 = $request->get('almacenes_cf_3400');
    $ocupacion_500 = $request->get('ocupacion_500');
    $tipo_pub_concurrencia = $request->get('tipo_pub_concurrencia');
    $talleres_dec = $request->get('talleres_dec');
    $robotizado = $request->get('robotizado');
    $plantas_rasante = $request->get('plantas_rasante');

    // Archivamos el informe con los datos obtenidos
    $error_bd = False;
    
    // Obtenemos al usuario autenticado, su nombre, id...
    $token = $app['security']->getToken();
    if (null !== $token) {
        $usuario_autenticado = $token->getUser();
        $username = $usuario_autenticado->getUsername();
        
        // Obtenemos los datos del usuario
        $user = $app['db']->fetchAssoc(
            "SELECT * FROM usuarios WHERE username = ?",
            array($username)
        );
        
        // Obtenemos el id del uso de instalación
        $id_instalacion = $app['db']->fetchAssoc(
            "SELECT idUsos FROM usos WHERE Tipo = ?",
            array($opcion)
        );

        $numero = $request->get('numero');

        if ($numero == null) {
            $direccion = $request->get('direccion');
        } else {
            $direccion = $request->get('direccion').', '.$request->get('numero');
        }

        // Insertamos el informe en la BDD
        if (!$error_bd) {
            $app['db']->insert(
                'informes',
                array(
                    'idUsuarios' => $user['idUsuarios'],
                    'idUsos' => $id_instalacion['idUsos'],
                    'fecha' => date("Y-m-d H:i:s"), // Fecha en formato DateTime de MySQL
                    'direccion' => $direccion,
                    'cpostal' => $request->get('cpostal'),
                    'localidad' => $request->get('localidad'),
                    'provincia' => $request->get('provincia'),
                    'superficie' => $superficie,
                    'altura_d' => $altura_d,
                    'altura_a' => $altura_a,
                    'centro_transf' => $centro_transf,
                    'dens_1per' => $dens_1per,
                    'cocina_50kW' => $cocina_50kW,
                    'trasteros' => $trasteros,
                    'superficie_trasteros' => $superficie_trasteros,
                    'reprografia' => $reprografia,
                    'volumen_construido' => $volumen_construido,
                    'aloj_50pers' => $aloj_50pers,
                    'cocina_20kW' => $cocina_20kW,
                    'roperos' => $roperos,
                    'superficie_roperos' => $superficie_roperos,
                    'camas_100' => $camas_100,
                    'almacenes_fc' => $almacenes_fc,
                    'v_almacenes_fc' => $v_almacenes_fc,
                    'lab_c' => $lab_c,
                    'v_lab_c' => $v_lab_c,
                    'zonas_est' => $zonas_est,
                    'area_ventas_1500' => $area_ventas_1500,
                    'densidad_cf_500' => $densidad_cf_500,
                    'almacenes_cf_3400' => $almacenes_cf_3400,
                    'ocupacion_500' => $ocupacion_500,
                    'tipo_pub_concurrencia' => $tipo_pub_concurrencia,
                    'talleres_dec' => $talleres_dec,
                    'robotizado' => $robotizado,
                    'plantas_rasante' => $plantas_rasante,
                )
            );
        }

    } else {
        // Si no está autenticado no debería pasar por aquí
        // pero aún así lo mandamos a la portada
        return $app->redirect('/');
    }

    // Construimos la confirmación del archivado en caso de que todo haya ido bien
    return $app['twig']->render(
        'archivar.twig',
        array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'error_bd' => $error_bd,
            'idUsuarios' => $user['idUsuarios'],
            'idUsos' => $id_instalacion['idUsos'],
            'opcion' => $opcion
        )
    );
})
->bind('uso.archivar');
// ----------------------------------------------------------------------------
return $usos_ins;