/* ------------------------------------------------------------------------------
*
*  # CKEditor editor
*
*  Specific JS code additions for editor_ckeditor.html page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {

    // ------------------------------------------------------------------------------------------
	

    // Basic select
    $('.bootstrap-select').selectpicker();



    // ------------------------------------------------------------------------------------------



    // Init with empty values
    $(".touchspin-empty").TouchSpin();



	// --------------------------------------------------------------------
	
	
	
	// Default initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });



	// --------------------------------------------------------------------
	

	$.mask.definitions['y']='[1]';
	$.mask.definitions['#']='[34]';
	$.mask.definitions['m']='[01]';
	$.mask.definitions['d']='[0123]';
	
	$('#tfi_listrsctns_lastupdate').mask('y#99/m9/d9');


	// --------------------------------------------------------------------
	
	

});
