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



    // File input
    $(".file-styled").uniform({
        fileButtonHtml: '<i class="icon-googleplus5"></i>',
        wrapperClass: 'bg-primary'
    });



    // Styled form components

    // Checkboxes, radios
    


	// --------------------------------------------------------------------


    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });
	
	
	// --------------------------------------------------------------------

	$('#select-all').click(function () {    
		$(':checkbox').prop('checked', this.checked);    
	 });

    // Basic initialization
    $('.tokenfield').tokenfield();

    // Basic initialization
    $('.tags-input').tagsinput();



    // Init with empty values
    $(".touchspin-empty").TouchSpin();

	


});
