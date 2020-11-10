
$(document).ready(function() {
    $('.mainnav li a').removeClass("active");
    $('.mainnav li a.home').addClass("active");


    setTimeout(function() {
        updateOdds();
    }, 500);

});

function timer(){
	updateTimers();
	setTimeout(function(){
		timer();
	},1000);
}

function updateOdds() {
    var events = "";
	var soccer = 'all';
//	console.log('updateOdds function');
//	console.log($("#page-name").val());
	var pageName = $("#page-name").val();
	if( pageName == 'inplayMe' ){
//		console.log('inplay Me');
		$('.odddetails').each(function() {
			if (events.length > 0)
				events += "&";
			events += $(this).data("eventid");
		});
		updateOddsCounter = 1000;
		ajaxCallUpdateOdds(events, soccer);

	}
	else if( pageName =='inplayOdds' ){
//		console.log('inplay Odds');
		$('.eventodds').each(function() {
			events = $(this).data("eventid");
			soccer = 'inplayOdds';
    	});

		updateOddsCounter = 1000;
		ajaxCallUpdateOdds(events, soccer);
//		updateOddsCounter = 600;
//		ajaxCallUpdateInplayOdds(events);

	}
	else{
//		console.log('not inplay odds and inplay me page');
	}
    
}

function ajaxCallUpdateOdds(events, soccer) {

		updatesInplayOdds(events, soccer);

}

function updateAPI(){
//	console.log('updateAPI function');
	
//	$.ajax(base_url+"php/inplay.php", {
//		success: function(){
//			console.log('update Inplay.php');
//		},
//		error: function(){
//			console.log('Error Inplay.php');
//		}
//	});
}

function updatesInplayOdds(events, soccer){
//	console.log('updatesInplayOdds function');
	
	// console.log(events);
	$.ajax(base_url + "bets/updateOdds/"+soccer, {
			type: "POST",
			dataType: "json",
			data: {
				LastUpdate: $('#lastupdate').val(),
				Matches: events
			},
			headers: {
			},
			success: function(result) {
//				 console.log(result);
				$('#lastupdate').val(result.lastUpdate);
				if (result.data == 'NoUpdate') {
					setTimeout(function() {
						updateOdds()
					}, 1000);
				}
				else if (result.data == 'inplayOdds'){
//					console.log('return inplay odds means matches is undefined');
				}
				else {
					// console.log(' go to sports function');
					updateInplayOdds(result.data);
					updateBetslipOdds(result.data);
					setTimeout(function() {
						updateOdds();
	//					console.log('update odds');
	//					$.ajax(base_url+"php/inplay.php");
					}, 1000);
				}
			},
			error: function() {
				// console.log(events);
				console.log('error');
				setTimeout(function() {
					updateOdds()
				}, 500);
			}
		});
}



function ajaxCallUpdateInplayOdds(events) {
	
//	console.log(events);
//	console.log($('#lastupdate').val());
	$.get(base_url+'upload/API/inPlay/lastupdate.txt', function(data) {
		var d = new Date();
		var thisTime = d.getTime();
		if( (thisTime/1000) - data > 5 ){
//			console.log('update InplayOdds.php greater than 5 second');
//			console.log((thisTime/1000) + ' - ' + data);
			$.ajax(base_url+"php/inplay.php", {
				success: function(){
//					console.log('update Inplay.php');

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

//								console.log('go to sports function');
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
//							console.log('error');
							setTimeout(function() {
								updateOdds()
							}, 1000);
						}
					});
				},
				error: function(){
//					console.log('Error Inplay.php');
					setTimeout(function() {
						ajaxCallUpdateInplayOdds()
					}, 1000);
				}
			});
		}
		else{
//			console.log('not update and try after 1 second');
			setTimeout(function() {
						ajaxCallUpdateInplayOdds()
					}, 1000);
		}
	}, 'text');
	
	
    
}