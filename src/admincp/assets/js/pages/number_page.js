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


	 $(".number").on("keypress", function(event) {
	
		var englishAlphabetAndWhiteSpace = /[0-9]/g;
		var key = String.fromCharCode(event.which);
		
		if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || englishAlphabetAndWhiteSpace.test(key)) 
			return true;
	
		return false;
	});
	
	$('.number').on("paste",function(e)
	{
		e.preventDefault();
	});


	// --------------------------------------------------------------------


});
