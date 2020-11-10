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

    // Full featured editor


    CKEDITOR.replace( 'text', {
        height: '400px',
        extraPlugins: 'forms'
    });


    CKEDITOR.replace( 'textbody', {
        height: '400px',
        extraPlugins: 'forms'
    });



    
});
