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

    // File input
    $(".file-styled").uniform({
        fileButtonHtml: '<i class="icon-googleplus5"></i>',
        wrapperClass: 'bg-warning'
    });

    
});
