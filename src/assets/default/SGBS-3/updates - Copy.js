
$(document).ready(function() {
    $('.mainnav li a').removeClass("active");
    $('.mainnav li a.home').addClass("active");

	
    setTimeout(function() {
        updateOdds();
    }, 3000);
	
//	prepareButtonEvents();
//    setTimeout(function() {
//        updateOtherOdds();
//    }, 3000);
//	setInterval(function() {
//        updateOtherOdds();
//    }, 3000);
//    setInterval(function() {
//        updateTimers();
//    }, 1000);
});


function updateOdds() {
    var events = "";
//	console.log($("#page-name").val());
	var pageName = $("#page-name").val();
	if( pageName == 'inplayMe' ){
		console.log('inplay Me');
		$('.odddetails').each(function() {
			if (events.length > 0)
				events += "&";
			events += $(this).data("eventid");
		});
		updateOddsCounter = 600;
		ajaxCallUpdateOdds(events);

	}
	if( pageName =='inplayOdds' ){
		console.log('inplay Odds');
		$('.eventodds').each(function() {
			events = $(this).data("eventid");
    	});
		updateOddsCounter = 600;
		ajaxCallUpdateInplayOdds(events);

	}
    
}
//
//function updateOtherOdds() {
//
//    var events = "";
//    $('.eventodds').each(function() {
//        if (events.length > 0)
//            events += "&";
////        events += $(this).data("eventid");
//        events += $(this).data("marketid");
//    });
//	
//    updateOddsCounter = 600;
//    ajaxCallUpdateOdds(events);
//	
//}
//
//function getInplayOddsID(){
//	var events = "";
//	$('.eventodds').each(function() {
//        events = $(this).data("eventid");
//		
//    });
//	console.log(events);
//	ajaxCallUpdateOdds(events);
//}

function ajaxCallUpdateOdds(events) {
	
//	console.log(events);
//	console.log($('#lastupdate').val());
	$.ajax(base_url+"php/inplay.php", {
		success: function(){
			console.log('update Inplay.php');
		},
		error: function(){
			console.log('Error Inplay.php');
		}
	});
	
    $.ajax(base_url + "bets/updateOdds", {
        type: "POST",
        dataType: "json",
        data: {
            LastUpdate: $('#lastupdate').val(),
            Matches: events
        },
        headers: {
        },
        success: function(result) {
//			console.log(result);
			console.log('get result');
            if (result.data == 'NoUpdate') {
                setTimeout(function() {
                    updateOdds()
                }, 1000);
            }
			else if (result.data == 'inplayOdds'){
				getInplayOddsID();
			}
			else {
				$('#lastupdate').val(result.lastUpdate);
//				console.log(result.data);
//				console.log(updateInplayOdds(result.data));
                
                console.log(' go to sports function');
				updateInplayOdds(result.data);
                updateBetslipOdds(result.data);
                setTimeout(function() {
                    updateOdds();
//					console.log('update odds');
//					$.ajax(base_url+"php/inplay.php");
                }, 30000);
            }
        },
        error: function() {
//			console.log(events);
			console.log('error');
            setTimeout(function() {
                updateOdds()
            }, 1000);
        }
    });
}

function ajaxCallUpdateInplayOdds(events) {
	
//	console.log(events);
//	console.log($('#lastupdate').val());
//	$.ajax(base_url+"php/inplay.php", {
//		success: function(){
//			console.log('1111111');
//		},
//		error: function(){
//			console.log('2222222');
//		}
//	});
	
    $.ajax(base_url + "bets/updateInplayOdds", {
        type: "POST",
        dataType: "json",
        data: {
            LastUpdate: $('#lastupdate').val(),
            Matches: events
        },
        headers: {
        },
        success: function(result) {
//			console.log(result);
            if (result.data == 'NoUpdate') {
                setTimeout(function() {
                    updateOdds()
                }, 1000);
            }
			else {
				$('#lastupdate').val(result.lastUpdate);
//				console.log(result.data);
//				console.log(updateInplayOdds(result.data));
                
                
				updateInplayOdds(result.data);
                updateBetslipOdds(result.data);
                setTimeout(function() {
                    updateOdds();
//					console.log('inplay odds updated');
//					$.ajax(base_url+"php/inplay.php");
                }, 30000);
            }
        },
        error: function() {
//			console.log(events);
//			console.log('error');
            setTimeout(function() {
                updateOdds()
            }, 1000);
        }
    });
}