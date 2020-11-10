// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');
var onlineGamerController = require('../databaseController');
var thisModule = require('./game_start');


module.exports.send_notify_for_user_id = function  (data, my_id, op_id, callback){
	
	
	var op_filter = {
					user_id 	: my_id,
					op_user_id 	: op_id,
					game_id 	: data.game_id
				}
	
	game.get_user_with_id( op_id, function(op_data){
		game.get_user_with_id( my_id, function(my_data){
			data.my_data = my_data[0];
			data.op_data = op_data[0];
			thisModule.check_start_game_by_game_id( data, function(result){
						
							callback(result);
						});
		})
	})
	
//	game.get_data_with_filter(op_filter, function(op_data){
//
//				var my_select = data.game_data.select,
//					op_select = op_data.game_data.select;
//		
//			
////				game.get_score_with_user_id_rps(op_select, my_select, function(op_score){
////					game.get_score_with_user_id_rps(my_select, op_select, function(my_score){
////						data.my_score = my_score;
////						data.op_score = op_score;
////						thisModule.check_start_game_by_game_id( data, function(result){
////							callback(result);
////						});
////					})
////				})
//			});
//	
	
	
	
}


module.exports.check_start_game_by_game_id = function  ( data, callback){
	
	switch ( data.game_id ){
			
		case '5':{
			callback( start_game_rps(data) );
			break;
		}
	}
	
}

function start_game_rps ( data ){
	var my_data = data.my_data,
		op_data = data.op_data;
	var return_array = {
							command       :  "game_started",
//							command       :  "played",
//							command       :  "game_turn",
//							score_1       :  data.my_score,
//							score_2       :  data.op_score,
//							
							turn_time     :  10000,
							turn_total    :  10000,
							
							name_1		  : my_data.username,
							name_2		  : op_data.username,
		
							amount 		  : data.game_data.amount,

							game_status   : 3,
							total_hand	  : 3,
		
					 //	"command"=> "not_found",
					 //	"command"=> "not_enough_chips",
					 // "selected"    :=> 8,
					//  "level_1"     :=> 4,
					//  "level_2"   :  => 5,
					};
	
		
	return return_array;
}



