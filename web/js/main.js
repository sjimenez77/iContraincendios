// JavaScript Document

//////////////////////////////////
//								//
// Santos Jiménez Linares 2013	//
//								//
//////////////////////////////////

(function($){
	
	// Uitilizo $(document).ajaxComplete ya que $(document).ready no gestiona correctamente el final de
	// la carga de las páginas de jQuery Mobile. Este evento se registra siempre que finalice un request AJAX.
	// 
	// El siguiente código habilita y deshabilita inputs de formularios asociados a si hay determinados 
	// locales de riesgo indicados mediante controles flip. Leemos el valor y habilitamos en cada caso.

	$(document).ajaxComplete(function(e) {
		$('select[name="trasteros"]').on('slidestop', function(e) {
			if ($('select[name="trasteros"]').val() == "si") {
				$('input[name="superficie_trasteros"]').removeAttr('disabled');
				$('input[name="superficie_trasteros"]').removeAttr('aria-disabled');
				$('input[name="superficie_trasteros"]').parent().removeClass('ui-disabled');
				$('input[name="superficie_trasteros"]').removeClass('ui-state-disabled');
				$('input[name="superficie_trasteros"]').removeClass('mobile-textinput-disabled');
			} else {
				$('input[name="superficie_trasteros"]').attr('disabled', 'disabled');
				$('input[name="superficie_trasteros"]').attr('aria-disabled', 'true');
				$('input[name="superficie_trasteros"]').parent().addClass('ui-disabled');
				$('input[name="superficie_trasteros"]').addClass('ui-state-disabled');
				$('input[name="superficie_trasteros"]').addClass('mobile-textinput-disabled');
			}
		});

		$('select[name="reprografia"]').on('slidestop', function(e) {
			if ($('select[name="reprografia"]').val() == "si") {
				$('input[name="volumen_construido"]').removeAttr('disabled');
				$('input[name="volumen_construido"]').removeAttr('aria-disabled');
				$('input[name="volumen_construido"]').parent().removeClass('ui-disabled');
				$('input[name="volumen_construido"]').removeClass('ui-state-disabled');
				$('input[name="volumen_construido"]').removeClass('mobile-textinput-disabled');
			} else {
				$('input[name="volumen_construido"]').attr('disabled', 'disabled');
				$('input[name="volumen_construido"]').attr('aria-disabled', 'true');
				$('input[name="volumen_construido"]').parent().addClass('ui-disabled');
				$('input[name="volumen_construido"]').addClass('ui-state-disabled');
				$('input[name="volumen_construido"]').addClass('mobile-textinput-disabled');
			}
		});

		$('select[name="roperos"]').on('slidestop', function(e) {
			if ($('select[name="roperos"]').val() == "si") {
				$('input[name="superficie_roperos"]').removeAttr('disabled');
				$('input[name="superficie_roperos"]').removeAttr('aria-disabled');
				$('input[name="superficie_roperos"]').parent().removeClass('ui-disabled');
				$('input[name="superficie_roperos"]').removeClass('ui-state-disabled');
				$('input[name="superficie_roperos"]').removeClass('mobile-textinput-disabled');
			} else {
				$('input[name="superficie_roperos"]').attr('disabled', 'disabled');
				$('input[name="superficie_roperos"]').attr('aria-disabled', 'true');
				$('input[name="superficie_roperos"]').parent().addClass('ui-disabled');
				$('input[name="superficie_roperos"]').addClass('ui-state-disabled');
				$('input[name="superficie_roperos"]').addClass('mobile-textinput-disabled');
			}
		});

		$('select[name="almacenes_fc"]').on('slidestop', function(e) {
			if ($('select[name="almacenes_fc"]').val() == "si") {
				$('input[name="v_almacenes_fc"]').removeAttr('disabled');
				$('input[name="v_almacenes_fc"]').removeAttr('aria-disabled');
				$('input[name="v_almacenes_fc"]').parent().removeClass('ui-disabled');
				$('input[name="v_almacenes_fc"]').removeClass('ui-state-disabled');
				$('input[name="v_almacenes_fc"]').removeClass('mobile-textinput-disabled');
			} else {
				$('input[name="v_almacenes_fc"]').attr('disabled', 'disabled');
				$('input[name="v_almacenes_fc"]').attr('aria-disabled', 'true');
				$('input[name="v_almacenes_fc"]').parent().addClass('ui-disabled');
				$('input[name="v_almacenes_fc"]').addClass('ui-state-disabled');
				$('input[name="v_almacenes_fc"]').addClass('mobile-textinput-disabled');
			}
		});

		$('select[name="lab_c"]').on('slidestop', function(e) {
			if ($('select[name="lab_c"]').val() == "si") {
				$('input[name="v_lab_c"]').removeAttr('disabled');
				$('input[name="v_lab_c"]').removeAttr('aria-disabled');
				$('input[name="v_lab_c"]').parent().removeClass('ui-disabled');
				$('input[name="v_lab_c"]').removeClass('ui-state-disabled');
				$('input[name="v_lab_c"]').removeClass('mobile-textinput-disabled');
			} else {
				$('input[name="v_lab_c"]').attr('disabled', 'disabled');
				$('input[name="v_lab_c"]').attr('aria-disabled', 'true');
				$('input[name="v_lab_c"]').parent().addClass('ui-disabled');
				$('input[name="v_lab_c"]').addClass('ui-state-disabled');
				$('input[name="v_lab_c"]').addClass('mobile-textinput-disabled');
			}
		});
		
	});
})(jQuery);