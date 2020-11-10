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


    // Start Full featured editor
    CKEDITOR.replace( 'copyright', {
        height: '150px',
		enterMode	: Number(2),
    });
    // Stop Full featured editor

	CKEDITOR.config.toolbar = [
	   ['Styles','Format','Font','FontSize'],
	   ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
	   ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	   ['Image','Table','-','Link','Flash','Smiley','TextColor','BGColor','Source']
	] ;
	
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
	
	
	



});
