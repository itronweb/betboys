"use strict";
$(document).ready(function(){
//	"use strict";
	
	$(".backgroundColor").click(function(){
		var id = $(this).val();
		var color = $(this).css('background-color');
		alert(color);
		$.ajax({
            url: 'php/change_background_color.php',
            type: 'POST',
            data: 'color='+color,
            statusCode: {
                404 : function () {
                    $('#searchBox').html("this page is not found");
                }
            },
            success: function (data) {
				$('#test1').html(data);
//                $(str).parent().prev('.contentBox').append(data);

            }
        });
		
	});
	
});


//function test(){
//	e.preventDefault();
//	var test = $(this).attr('id');
//	alert(test);
//}
$(document).on('click',':checkbox',function(e){
	 // e.preventDefault();

	var checked = $(this).attr('checked');
	
	if(checked != 'checked')
		$(this).attr('checked', 'checked') ;
	else
		$(this).removeAttr('checked');
	
	  var id = $(this).val();
	  
	  if($(this).prev().val() == 'inplayMe')
		  var urls = $(this).prev().prev().val();
//		  var urls = '../php/inplay_favorite.php';
	  else
		  var urls = '../php/change_favorite.php';

	  var tr = $(".odddetails[data-eventid ="+ id +"]");
	  var league = tr[0].dataset.leagueid;
//	  tr.addClass('hidden');
	$(".odddetails[data-eventid ="+ id +"]").remove();
	
	var odddetails_lenght = $(".odddetails[data-leagueid="+league+"]").length;
	var favorite_lenght = $(".odddetails[data-leagueid="+league+"][data-favorite=true]").length;
	var hidden_lenght = $(".odddetails.hidden[data-leagueid="+league+"]").length;
	
        $.ajax({
            url: urls,
            type: 'POST',
            data: 'id='+id+'&tr='+tr[0].outerHTML,
            statusCode: {
                404 : function () {
                    $('#searchBox').html("this page is not found");
                }
            },
            success: function (data) {
//				console.log('1111111111')
//				console.log($(this).parent('tr.odddetails'));
//				$('#favoriteMatch').html(data);
				if( checked != 'checked' ){
					var table = document.getElementById("favoriteMatch");
					$(tr[0]).attr('data-favorite','true');
					$(table).append(tr[0].outerHTML);
//					var row = table.insertRow(1).outerHTML = tr[0].outerHTML;
					$('.inplayheaders[data-leagueid=1]').removeClass('hidden');
					if((odddetails_lenght - hidden_lenght) == favorite_lenght)
						$(".inplayheader[data-leagueid="+league+"]").addClass('hidden');
					
					
				}
				else{
					var inplayheader = $(".inplayheader[data-leagueid="+league+"]");
					var odddetails = $(".odddetails[data-leagueid="+league+"]");
//					$(inplayheader).insertAfter(tr[0].outerHTML);
					$(tr[0]).removeAttr('data-favorite');
					$(tr[0].outerHTML).insertAfter($(inplayheader));
					
					if($(".odddetails[data-favorite=true]").length == 0)
						$(".inplayheader[data-leagueid=1]").addClass('hidden');
					
//					
					if((odddetails_lenght - hidden_lenght) == favorite_lenght)
						$(".inplayheader[data-leagueid="+league+"]").removeClass('hidden');
					
					
				
						
					
				}
				
//				table.prependTo(tr[0].outerHTML);
//				tr.addClass('inplaybtn');
				
//				var row = table.insertRow(0).outerHTML = data;
				
//				row.innerHTML = tr[0].outerHTML;
//				console.log(tr);
//				console.log($('#favoriteMatch'));
//				$('#favoriteMatch').html($(tr));
//				$('#favoriteMatch').html($(this).parent('td').parent('tr').innerHTML);
//				$('#favoriteMatch').appendTo($(this).parent('td').parent('tr').innerHTML);
				
//                $(str).parent().prev('.contentBox').append(data);

            }
        });
	
	
	
//	if((odddetails_lenght - hidden_lenght) == favorite_lenght)
//		$(".inplayheader[data-leagueid="+league+"]").addClass('hidden');
//	else if ((odddetails_lenght - hidden_lenght) < favorite_lenght)
//		$(".inplayheader[data-leagueid="+league+"]").removeClass('hidden');
	
  });    
//$(function() {
//    
//  $(":checkbox").change(function(e){
//	 // e.preventDefault();
////	  alert($(this).prev().val());
////	  alert($(this).attr('id'));
//	  var id = $(this).val();
////	  var offset = $(str).prev('.morePrivateList').val();
////        var id = $(str).prev().prev().prev('.moreID').val();
//        //alert($('#'+id).val());
////        var offsetAds = $(str).prev().prev('.moreOffsetAds').val();
////        var search = $('#search').val();
//	  
//	  if($(this).prev().val() == 'inplayMe')
//		  var urls = $(this).prev().prev().val();
////		  var urls = '../php/inplay_favorite.php';
//	  else
//		  var urls = '../php/change_favorite.php';
////	  console.log($(this).parent('td').parent('tr')[0].innerHTML);
////	  var new_tr = $(this).parent('td').parent('tr')[0].innerHTML;
////	  console.log($(".odddetails[data-eventid ="+ id +"]"));
//	  var tr = $(".odddetails[data-eventid ="+ id +"]");
////	  tr.addClass('hidden');
//	  console.log(tr);
//        $.ajax({
//            url: urls,
//            type: 'POST',
//            data: 'id='+id+'&tr='+tr[0].outerHTML,
//            statusCode: {
//                404 : function () {
//                    $('#searchBox').html("this page is not found");
//                }
//            },
//            success: function (data) {
////				console.log('1111111111')
////				console.log($(this).parent('tr.odddetails'));
////				$('#favoriteMatch').html(data);
//				var table = document.getElementById("favoriteMatch");
////				table.prependTo(data);
//				tr.addClass('inplaybtn');
//				var row = table.insertRow(0).outerHTML = tr[0].outerHTML;
////				var row = table.insertRow(0).outerHTML = data;
//				
//				console.log($('.mlodds li'));
////				row.innerHTML = tr[0].outerHTML;
////				console.log(tr);
////				console.log($('#favoriteMatch'));
////				$('#favoriteMatch').html($(tr));
////				$('#favoriteMatch').html($(this).parent('td').parent('tr').innerHTML);
////				$('#favoriteMatch').appendTo($(this).parent('td').parent('tr').innerHTML);
//				
////                $(str).parent().prev('.contentBox').append(data);
//
//            }
//        });
//    	
//  });    
//});

