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
    
    // Obtenemos los datos comunes
    $superficie = trim($request->get('superficie'));
    $altura_d = trim($request->get('altura_d'));
    $altura_a = trim($request->get('altura_a'));
    $centro_transf = ($request->get('centro_transf')=='si') ? 1 : 0;
    
    // Obtenemos los datos específicos de cada uso y declaramos null el resto
    switch ($opcion) {
        case 'Residencial Vivienda':
            $dens_1per = ($request->get('dens_1per')=='si') ? 1 : 0; // 1 -> True, 0 -> False
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $trasteros = ($request->get('trasteros')=='si') ? 1 : 0;
            $superficie_trasteros = trim($request->get('superficie_trasteros'));
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
        
        case 'Administrativo':
            $dens_1per = ($request->get('dens_1per')=='si') ? 1 : 0; // 1 -> True, 0 -> False
            $cocina_50kW = ($request->get('cocina_50kW')=='si') ? 1 : 0;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = ($request->get('locales_riesgo')=='si') ? 1 : 0;
            $volumen_construido = trim($request->get('volumen_construido'));
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
        
        case 'Residencial Público':
            $dens_1per = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = ($request->get('locales_riesgo')=='si') ? 1 : 0;
            $volumen_construido = null;
            $aloj_50pers = ($request->get('aloj_50pers')=='si') ? 1 : 0; 
            $cocina_20kW = ($request->get('cocina_20kW')=='si') ? 1 : 0;
            $superficie_locales = trim($request->get('superficie_locales'));
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
            break;
        
        case 'Hospitalario':
            $dens_1per = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $camas_100 = ($request->get('camas_100')=='si') ? 1 : 0; 
            $cocina_20kW = ($request->get('cocina_20kW')=='si') ? 1 : 0;
            $superficie_locales = null;
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
            break;
        
        case 'Docente':
            $dens_1per  = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
        
        case 'Comercial':
            $dens_1per  = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
        
        case 'Pública Concurrencia':
            $dens_1per  = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
        
        case 'Aparcamiento':
            $dens_1per  = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
        
        default:
            $dens_1per  = null;
            $cocina_50kW = null;
            $trasteros = null;
            $superficie_trasteros = null;
            $locales_riesgo = null;
            $volumen_construido = null;
            $aloj_50pers = null;
            $cocina_20kW = null;
            $superficie_locales = null;
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
            break;
    }

    // Procesamos los datos

    // Construimos los resultados en función de los datos rellenados
    return $app['twig']->render(
        'resultados.twig',
        array(
            'opcion' => $opcion,
            'superficie' => $superficie,
            'altura_d' => $altura_d,
            'altura_a' => $altura_a,
            'centro_transf' => $centro_transf,
            'dens_1pers' => $dens_1per,
            'cocinas_50kW' => $cocina_50kW,
            'trasteros' => $trasteros,
            'superficie_trasteros' => $superficie_trasteros,
            'locales_riesgo' => $locales_riesgo,
            'volumen_construido' => $volumen_construido,
            'aloj_50pers' => $aloj_50pers,
            'cocina_20kW' => $cocina_20kW,
            'superficie_locales' => $superficie_locales,
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
// ----------------------------------------------------------------------------
return $usos_ins;