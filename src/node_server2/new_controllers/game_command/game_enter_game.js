// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');

function waiting(data, callback){
	
	var token = data.token;
	game.bets_amount_is_valid_with_id( data.uid, data.amount, function(my_data){
		
		if ( my_data === false ){
			
			 //			"command"=> "not_found",
                            //			"command"=> "not_enough_chips",
			
			game.update_status( data.token, data.uid, 99, function(result){
				
			});
			return;
		}
		
		// enter with game list
		if ( data.op_token != undefined ){
			
			game.get_data_row( {token: data.op_token}, function(op_data){
				
				if ( op_data.status == 3 ){
					update = {
						status		: 4,
						game_data 	: { select:[], side: 'me', amount: data.amount, score: 0 },
						op_user_id	: data.uid
					}
					game.update_online_gamer({token: data.op_token}, update, function(result){

						update = {
							status : 4,
							game_data 	: { select:[], side: 'op', amount: data.amount, score: 0 },
							op_user_id	: op_data.user_id
						}
						game.update_online_gamer({token: data.token}, update, function(result){

						});

					})
				}
				
			})
		}
		else {
			
			if ( data.game_id == 5 ){
				game_data = {select: [], amount: data.amount};
			}
			else if ( data.game_id == 6 ){
				game_data = {amount: data.amount, moves:[]}
			}
			
			
			
			if ( typeof game_data != undefined ){
				
				
				check_user_is_waiting( data.token, data.game_id, data.amount, function(result){
					
					if ( result == 0 ){
						
						var update = {
							status 		: 3,
							game_data	: game_data,
							amount		: data.amount, 
							win 		: ''
						};
						
						game.update_online_gamer( {token: token}, update, function(result){});	
					}
					else {
						var update = {
							game_data	: game_data,
							amount		: data.amount, 
							win 		: ''
						};
						game.update_online_gamer( {token: token}, update, function(results){
							
							game.get_online_gamer_row_with_token( token, function(my_data){

									my_data.amount = data.amount;
									set_player_from_list( my_data, result, function(res){

									});
								})
							
							
						});	
						
						
						
					}
					
				})
//				game.update_online_gamer( {token: data.token}, update,  function(result){
//
////					game.check_any_user_waiting( data.token, data.game_id, data.amount, function(op_user){
////
////					});
//
//
//
//				});
			}
			
			
		}
		
		
		
		
	});
	
}



function check_user_is_waiting( token, game_id, amount, callback){
	
	var search = { game_id: game_id, status: 3, amount: amount};
	
	game.get_data ( search, function(result){
		
		if ( result == 0 ){
			callback(0);
		}
		else{
			callback( result );
		}
		
	})
//	
//	onlineGamerController.filterData(search, function (result) {
//		
//		var x = result._responses[0].r;
//		var continues = true;
//		
//		if ( x.length != 0 ){
//			x.forEach( function(item){
//				
//				if ( item.amount == amount ){
//					
//					var op_data = item;
//					
//					if (game_id == 6 ){
//					var places = '1.B.2,12.B.5,17.B.3,19.B.5,24.W.2,13.W.5,8.W.3,6.W.5';
////					var places = '7.W.5,6.W.2,5.W.3,4.W.3,3.W.2,18.B.5,19.B.3,20.B.2,21.B.3,22.B.2';
////					var places = '1.W.5,6.W.2,5.W.3,4.W.3,3.W.2,20.B.5,21.B.3,22.B.1,23.B.1,19.B.3,24.B.2';
////					var places = '1.W.2,24.B.2,0.W.13,25.B.13';
////					var places = '1.W.5,2.W.2,3.W.2,4.B.1,5.W.3,26.W.1,21.B.1,20.B.4,19.B.2,18.B.2,17.B.2,16.B.2,';
//					
//					var time = 'WHITE';
//					var game_data = { moves: [], 
//									 places: places, 
//									 amount: amount, 
//									 side: 'me',
//									 time: time
//									}
//					}
//					
//					var update = {op_user_id: my_data.user_id , status: 4, game_data: game_data };
//					var op_search = { token: op_data.token };
//					
//				}
//			});
//		}
//	})
//		
//	
	
	
}

function set_player_from_list( my_data, result, callback){
	
	var continues = true;
	result.forEach( function(item){
		
		if ( continues === true ){
			
			var game_data = get_game_data_backgammon('WHITE', my_data.amount, 'me');
			
			var filter = { token: item.token };
			var update = {game_data	: game_data, op_user_id : my_data.user_id,status : 4 };
			
			game.check_data_is_correct_then_update( filter, update, 3, function(res){

				if ( res == 1 ){
					
					var op_game_data = get_game_data_backgammon('BLACK', my_data.amount, 'op');
					
					var my_filter = { token: my_data.token };
					var my_update = { game_data: op_game_data,op_user_id:item.user_id, status : 4};
					
					game.update_online_gamer( my_filter, my_update, function(result){
						
					});
					
					continues = false;
				} 
				else if ( res == 0 ){
					continues = true;
				}
			});
		}
		
	})
	
}

function get_game_data_backgammon ( time, amount, side ){
	
	var places = '1.B.2,12.B.5,17.B.3,19.B.5,24.W.2,13.W.5,8.W.3,6.W.5';
//					var places = '7.W.5,6.W.2,5.W.3,4.W.3,3.W.2,18.B.5,19.B.3,20.B.2,21.B.3,22.B.2';
//					var places = '1.W.5,6.W.2,5.W.3,4.W.3,3.W.2,20.B.5,21.B.3,22.B.1,23.B.1,19.B.3,24.B.2';
//					var places = '1.W.2,24.B.2,0.W.13,25.B.13';
//					var places = '1.W.5,2.W.2,3.W.2,4.B.1,5.W.3,26.W.1,21.B.1,20.B.4,19.B.2,18.B.2,17.B.2,16.B.2,';
					
//	var time = 'WHITE';
	var game_data = { moves: [], 
					 places: places, 
					 amount: amount, 
					 side: side,
					 time: time
					}
	
	return game_data;
}

function check_enter_game_by_game_id ( data, callback){
	
	switch ( data.game_id ){
			
		case '5':{
			callback( enter_game_rps(data) );
			break;
		}
		case '6':{
			
			callback( enter_game_backgammon(data) );
			break;
		}
	}
	
}

function enter_game_rps ( data ){
	my_data = data.my_data;
	op_data = data.op_data;
	var return_array = {
						command   		: "game_status",
						side      		: 'me',
						amount 	  		: data.amount,//poli ke vasat gozashtan
						total_hand		: 3,

						game_status   	: 3,

						turn_time     	: 0,
						turn_total    	: 0,

						uid_1         	: my_data.id ,
						photo_1       	: '',

						chips_1      	: my_data.cash,
						name_1       	: my_data.username,
						score_1			: 0,
						score_2			: 0,

						uid_2        	: op_data.id,
						photo_2       	: '',

						chips_2       	: op_data.cash,
						name_2        	: op_data.username,
					 //	"command"=> "not_found",
					 //	"command"=> "not_enough_chips",
					 // "selected"    :=> 8,
					//  "level_1"     :=> 4,
					//  "level_2"   :  => 5,
					};
	
		
	return return_array;
}

function enter_game_backgammon ( data ){
	
	if ( data.my.game_data.side == 'me'){
		var user_1 	= data.my_data,
			user_2 	= data.op_data,
			row_1 	= data.my,
			row_2 	= data.op;
	}
	else if ( data.my.game_data.side == 'op') {
		var user_1 	= data.op_data,
			user_2 	= data.my_data,
			row_1 	= data.op,
			row_2 	= data.my;
	}
	
	var game_data =  data.my.game_data;
	
	var return_array = {
						command		: "game_status",
						places		: data.my.game_data.places,
//						turn" :'BLACK',
//						turn		: data.my.game_data.time,
						turn		: data.my.game_data.time,
						amount		: data.amount,
//						double":$Data['double'],
						double		 :'false',
		
//----------			------------AGE POR BASHE BAZI DOUBLE KHAHD SHOD 
//						double":0,//age bkhy shart double bashe  dar ghyr insort bayad  "double":'',
//						double_level":2,
//						double_waiting" :1,
//						double_offered" :1,
		
						uid_1		:user_1.id,
						photo_1		:'',
//						level_1":4,
						chips_1		:user_1.cash,
						name_1		:user_1.username,
//						double_uid_1":2,
//						score_1":60000000,
			
						uid_2		:user_2.id,
						photo_2		:'',
//						level_2":0,
						chips_2		:user_2.cash,
						name_2		:user_2.username,
//						double_uid_2":2,
//						score_2":80000000,
		
						dice_1		 :'6',
						dice_2		 :'6',
//						posibilities :"23;25,2425262728",
//						posibilities :"23;25;31",
//						posibilities	 :"27,24,28,29;29;30",
//						posibilities	 :"29;9a,24;45;46,bc,bd.20;0A",
//						posibilities	 :"29;9A;AB;BC,20;0B.24;45,26;68.23;34",
//						posibilities	 :"D8;85,D7;74,D9;93.D9;95",
//						posibilities	 :"C7;73.C9;93.C7,C9",


						status		 :4,

					};

	if ( game_data.side == 'me' && game_data.time == 'WHITE' ){
		
		console.log('update status');
		
		game.update_online_gamer({token:data.token}, {status:5}, function(result){
			
		});
		
		return return_array;	
		
	}
	else if ( game_data.side == 'op' && game_data.time == 'BLACK'){
		
		return return_array;
	}
	
	
	
	
	
	
		
	
}



module.exports = {
    waiting			: waiting,
	check_enter_game_by_game_id : check_enter_game_by_game_id, 
    
};