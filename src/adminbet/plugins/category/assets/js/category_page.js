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
    CKEDITOR.replace( 'description', {
        height: '400px',
        extraPlugins: 'forms',
        extraPlugins: 'video',
		filebrowserBrowseUrl : '../../filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
		filebrowserUploadUrl : '../../filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
		filebrowserImageBrowseUrl : '../../filemanager/dialog.php?type=1&editor=ckeditor&fldr='
    });
    // Stop Full featured editor



    // ------------------------------------------------------------------------------------------



    // Basic setup
    // ------------------------------

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
    $(".styled").uniform({ radioClass: 'choice' });




	// --------------------------------------------------------------------



    // File input
    $(".file-styled").uniform({
        fileButtonHtml: '<i class="icon-googleplus5"></i>',
        wrapperClass: 'bg-primary'
    });

    
	
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });





	// --------------------------------------------------------------------
	
	
	



    // Init with empty values
    $(".touchspin-empty").TouchSpin();





	// --------------------------------------------------------------------
	
	
	



});
