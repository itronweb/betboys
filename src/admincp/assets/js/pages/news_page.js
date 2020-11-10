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
	
    // Start Full featured editor
    CKEDITOR.replace( 'text', {
        height: '400px',
        extraPlugins: 'forms'
    });

    CKEDITOR.replace( 'textbody', {
        height: '400px',
        extraPlugins: 'forms'
    });
    // Stop Full featured editor

    // ------------------------------------------------------------------------------------------



    // Basic select
    $('.bootstrap-select').selectpicker();



    // ------------------------------------------------------------------------------------------



    // Basic initialization
    $('.tokenfield').tokenfield();

    // Basic initialization
    $('.tags-input').tagsinput();



    // ------------------------------------------------------------------------------------------



    // Styled form components
    // Checkboxes, radios



	// --------------------------------------------------------------------



    // File input
    $(".file-styled").uniform({
        fileButtonHtml: '<i class="icon-googleplus5"></i>',
        wrapperClass: 'bg-warning'
    });

    
	
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });



	// --------------------------------------------------------------------
	
	
	
    // Init with empty values
    $(".touchspin-empty").TouchSpin();



	// --------------------------------------------------------------------
	
	
	
	// Default initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });



	// --------------------------------------------------------------------
	
	
/*
    // Start Date
    $('[name="startdate"]').formatter({
        pattern: '{{9999}}/{{99}}/{{99}} {{99}}:{{99}}:{{99}}'
    }); */
/*
	$('#enddate').inputmask('Regex', { 
		regex: "^1[3,4][0-9][0-9]/((?:0[1-9]|1[012]))/(0[1-9]|[12][0-9]|3[01]) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$"
	});
	*/

	$.mask.definitions['y']='[1]';
	$.mask.definitions['#']='[34]';
	$.mask.definitions['m']='[01]';
	$.mask.definitions['d']='[0123]';
	$.mask.definitions['h']='[012]';
	$.mask.definitions['i']='[012345]';
	
	$('#startdate').mask('y#99/m9/d9 h9:i9:i9');
	$('#enddate').mask('y#99/m9/d9 h9:i9:i9');

	// --------------------------------------------------------------------
	

});



