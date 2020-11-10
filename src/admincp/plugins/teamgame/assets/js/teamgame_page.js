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



    // Basic setup
    // ------------------------------

    // Basic select
//    $(".bootstrap-select").selectpicker();


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
	
	// --------------------------------------------------------------------
		
		
	jQuery('#game_name').change(function() {
		var ccode=$("#game_name").val();
			console.log(ccode);
		$.ajax({
			url: "../getteamgame.php",
			type: 'POST',
//			data: "cc="+ccode,
			data: { cc: ccode },
			success: function(res) {
//				$('#getteamgame').val('').trigger('change');
				$("#getteamgame").html(res);
//				
			}
		});
	});
	



});
