// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');

function waiting(data, callback){
	
	game.bets_amount_is_valid_with_id( data.uid, data.amount, function(my_data){
		
		if ( my_data === false ){
			
			 //			"command"=> "not_found",
                            //			"command"=> "not_enough_chips",
			
			game.update_status( data.token, data.uid, 99, function(result){
				
			});
			return;
		}
		
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
				var update = {
					status : 3,
					game_data: game_data,
					win : ''
				};
				game.update_online_gamer( {token: data.token}, update,  function(result){

					game.check_any_user_waiting( data.token, data.game_id, data.amount, function(op_user){

					});



				});
			}
			
			
		}
		
		
		
		
	});
	
}



function check_enter_game_by_game_id ( data, callback){
	
	switch ( data.game_id ){
			
		case '5':{
			callback( enter_game_rps(data) );
			break;
		}
		case '6':{
			
			
//			
			
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
		
		game.update_online_gamer({token:data.token}, {status:5}, function(result){
			return return_array;
		})
	}
	
		
	
	
	
	
		
	
}



module.exports = {
    waiting			: waiting,
	check_enter_game_by_game_id : check_enter_game_by_game_id, 
    
};