// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');
var onlineGamerController = require('../databaseController');
var thisModule = require('./game_drawn');
var backgammon = require('./game_backgammon');


module.exports.cancel = function waiting( data, callback){

	game.get_data_row( {token: data.token}, function(my_data){
		var op_filter = {
			user_id : my_data.op_user_id,
			op_user_id : my_data.user_id,
			game_id : my_data.game_id
		}
		game.get_data_row( op_filter, function( op_data){
			var game_start = my_data.game_data.select.length;
			
			if( game_start == 0 ){
				game.update_status( my_data.token, my_data.user_id, 20, function(result){
					game.update_status( op_data.token, op_data.user_id, 21, function(result){
						callback(result);
					});
				});
			}
			else if ( game_start > 0 ){
				game.update_status( my_data.token, my_data.user_id, 31, function(result){
					game.update_status( op_data.token, op_data.user_id, 30, function(result){
						callback(result);
					})	
				})
			}
		})
	})

	game.get_data_row( {token: data.token}, function(result){
		
		if ( result.status == 4 ){
			game.update_status( data.token, data.uid, 2 , function(result){
				thisModule.check_cancel_game_by_game_id( data, function(result){
					callback(result);
				});
			});
		}	
		
	});
	
	
	
	
}


module.exports.cancel_backgammon = function waiting( data, callback){

	
	var filter = { token: data.token };
		var update = { status: 17 };
		backgammon.status_17 ( data, function( result ){
			callback(result);
		});
	
	
//	game.get_data_row( {token: data.token}, function(my_data){
//		var op_filter = {
//			user_id : my_data.op_user_id,
//			op_user_id : my_data.user_id,
//			game_id : my_data.game_id
//		}
//		game.get_data_row( op_filter, function( op_data){
//			
//			var win_id = op_data.user_id;
//			
//			var update = { win: win_id, status: 16};
//			
//			game.update_online_gamer({token: my_data.token}, update, function(result){
//				
//				game.update_online_gamer({token: op_data.token}, update, function(result){
//					callback(result);
//				})
//				
//			})
//			
//			
////			game.update_status( my_data.token, my_data.user_id, 20, function(result){
////				game.update_status( op_data.token, op_data.user_id, 21, function(result){
////					callback(result);
////				});
////			});
//			
//		})
//	})

//	game.get_data_row( {token: data.token}, function(result){
//		
//		if ( result.status == 4 ){
//			game.update_status( data.token, data.uid, 2 , function(result){
//				thisModule.check_cancel_game_by_game_id( data, function(result){
//					callback(result);
//				});
//			});
//		}	
//		
//	});
	
	
	
	
}


module.exports.play_hand = function play_hand(data, callback){
	
	
}




module.exports.check_cancel_game_by_game_id = function  ( data, callback){
	
	switch ( data.game_id ){
			
		case '5':{
			callback( cancel_game_rps(data) );
			break;
		}
	}
	
}

function cancel_game_rps ( data ){

	var return_array = {
							command       :  "cancelled",
//							command       :  "played",
//							command       :  "game_turn",
							selected      :  data.selected,
							score_1       :  data.my_score,
							score_2       :  data.op_score,
//							winner        :  'op',
							selected_1    :  data.selected,
							selected_2    :  data.op_select,
							turn_time     :  0,
							turn_total     : 0,

							game_status   : 3,
					 //	"command"=> "not_found",
					 //	"command"=> "not_enough_chips",
					 // "selected"    :=> 8,
					//  "level_1"     :=> 4,
					//  "level_2"   :  => 5,
					};
	
		
	return return_array;
}


module.exports.check_winner_rps = function ( data, score, callback){
	
	
	setTimeout( function(){
		
		var my_score = score.my_score,
		op_score = score.op_score,
		max_score = 3 ;

		if ( my_score >= max_score || op_score >= 3 ){
			
			

			if ( my_score > op_score ){
				win_id = data.user_id;
//				callback( win_game_rps( data, data.user_id ) );
			}
			else if ( my_score < op_score ){
				win_id = data.op_user_id;
//				callback( win_game_rps( data, data.op_user_id ) );
			}
			
			amount = data.game_data.amount;
			
			if ( win_id == data.user_id ){
				game.update_user_cash( win_id, amount, data.game_id, function(result){
				
				});
			}
			
			callback( win_game_rps( data, win_id ) );
			

		}
		
		
		
	}, 4000);
		
	
	
}


function win_game_rps ( data, win_uid ){
	
	var uid1 = data.user_id,
		uid2 = data.op_user_id,
		winner = win_uid,
		amount = data.game_data.amount,
		chips = 20000;
	
	
	var return_array = {
		command:		'win',
		uid1:			uid1,
		uid2:			uid2,
		winner_uid:		winner,
		amount:			amount,
		chips:			chips,
	}
	
	return return_array;
	
	
}



