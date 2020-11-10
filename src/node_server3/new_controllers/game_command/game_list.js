// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');
var onlineGamerController = require('../databaseController');



module.exports.get_list = function ( data, callback){

	filter = { game_id: data.game_id, status:3};
	game.get_game_list( filter, function(result){
		var arr = Array(),
			i = 0;
		
		result.forEach( function(item){
		
			game.get_user_with_id( item.user_id, function(user){
				
					var list = {
						command	: 'games_list' ,
						games	: [{
							name 	:user[0]['username'],
							amount	: item.game_data.amount,
							token 	: item.token,
							double 	: false
						}]

					};

					arr.push(list);	
					callback(list);
				});

				

			
			if ( i == result.length ){

			} 
			i++;
			
			
		});
		
	});
	
//	game.get_data_row( {token: data.token}, function(result){
//		
//		if ( data.uid == undefined ){
//			var user_id = data.user_id;
//		}
//		else {
//			var user_id = data.uid;
//		}
//		
//		if ( result.status == 3 ){
//			game.update_status( data.token, user_id, 20 , function(result){
////				callback(result);
////				thisModule.check_cancel_game_by_game_id( data, function(result){
////					callback(result);
////				});
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



