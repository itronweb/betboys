// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');
var onlineGamerController = require('../databaseController');
var backgammon = require('./game_backgammon');


module.exports.waiting = function waiting( data, callback){
	
	var filter = { token: data.token };
	
	game.get_data_row( filter, function(my_data){
		
		var op_filter = {
			user_id 	: my_data.op_user_id,
			op_user_id	: my_data.user_id,
			game_id		: my_data.game_id,
		};
		game.get_data_row( op_filter, function(op_data){
		
		var select = my_data.game_data.select;
		var op_select = op_data.game_data.select;
		var amount = my_data.game_data.amount;
		
		select.push(data.selected);
		game_data = { select: select, amount: amount};
		
		if ((select.length==op_select.length+1) ){
			
				var update = {game_data: game_data, status: 5}
				
				onlineGamerController.updateData(filter, update, function(result){
					
					var filter_op = { user_id: 		my_data.op_user_id,
									  op_user_id : 	my_data.user_id,
									  game_id : 	my_data.game_id
									};
					var update = { status: 6 };
					
					onlineGamerController.updateData(filter_op, update, function(result){
						callback('waiting for user reply');
						return;
					});

				});	
			}
		else if ( (select.length  == op_select.length) ){
				var update = {game_data: game_data, status:7};
				
				onlineGamerController.updateData(filter, update, function(result){
					var filter_op = { user_id: 		my_data.op_user_id,
									  op_user_id : 	my_data.user_id,
									  game_id : 	my_data.game_id
									};
					var update = { status: 7 };
					
					onlineGamerController.updateData(filter_op, update, function(result){
						callback('play hand');
						return;
					})
				});
				
			}
			
		})
		
	})

}




module.exports.play_backgammon = function play_backgammon( data, callback){
	
	var filter = { token: data.token };
	
	game.get_data_row( filter, function(my_data){
		
		var op_filter = {
			user_id 	: my_data.op_user_id,
			op_user_id	: my_data.user_id,
			game_id		: my_data.game_id,
		};
		game.get_data_row( op_filter, function(op_data){
		
		var places = my_data.game_data.places;
		var amount = my_data.game_data.amount;
		
		
		backgammon.convert_place_to_array( places, function(place_array){
			
			var place = place_array.place;
			var place_color = place_array.place_color;
			var place_value = place_array.place_value;
			
			var game_data = my_data.game_data;
			var moves = data.moves;
			
			if ( (game_data.side == 'me' && game_data.time == 'WHITE') || game_data.side == 'op' && game_data.time == 'BLACK' ){
				
				if ( game_data.time == 'WHITE'){
					var search = 'W';
				}
				else if ( game_data.time == 'BLACK'){
					var search = 'B';
				}
				
				move = backgammon.move_selected ( moves, place_color, place_value, search );
				
				place_color = move.color;
				place_value = move.value;
				
				
//				moves.forEach( function(move){
//					
//					if( place_color[move.from] == 'W' ){
//						place_value[move.from] = Number(place_value[move.from])-1;
//						
//						if ( place_value[move.from] < 1 ){
//							place_color[move.from] = undefined;
//						}
//						
//						if ( place_color[move.to] == 'W'){
//							place_value[move.to] = Number(place_value[move.to]) + 1;
//						}
//						else if ( place_color[move.to] == undefined ){
//							place_value[move.to] = 1;
//							place_color[move.to] = 'W';
//						}
//						else if ( place_color[move.to] == 'B' && place_value[move.to] == 1 ){
//							place_color[move.to] = 'W';
//							place_value[move.to] = 1;
//						}
//						
//					}
//				});
				
				
				if ( backgammon.check_win ( place_color, place_value, search ) ){
					
					if ( place_value[0] == 15 ){
						var win = 'WHITE';
					}
					else if ( place_value[25] == 15){
						var win = 'BLACK';
					}
					
					if ( my_data.game_data.side == 'me'){
						var uid_1 = my_data.user_id,
							uid_2 = my_data.op_user_id;
						if ( win == 'WHITE'){
							var winner_uid = my_data.user_id;
						}
						else if ( win == 'BLACK'){
							var winner_uid = my_data.op_user_id;
						}
					}
					else if ( my_data.game_data.side == 'op'){
						var uid_1 = my_data.user_id,
							uid_2 = my_data.op_user_id;
						if ( win == 'WHITE'){
							var winner_uid = my_data.op_user_id;
						}
						else if ( win == 'BLACK'){
							var winner_uid = my_data.user_id;
						}
					}
					
					game.get_percent_play_with_game_id( data.game_id, function( get_row ){
						
						var percent = get_row[0].percent_play;
						
						var my_amount = my_data.game_data.amount;
						var amount = ( my_amount * 2 ) - ( (my_amount * percent) / 100 );
						
						my_data.game_data.amount = amount;
						op_data.game_data.amount = amount;
						
						var my_filter = { token: my_data.token},
							my_update = { win:winner_uid, status:15, game_data: my_data.game_data},
							op_filter_1 = { token: op_data.token},
							op_update = { win:winner_uid, status:15, game_data: op_data.game_data};

						game.update_online_gamer( my_filter, my_update,function(result){

							game.update_online_gamer( op_filter_1, op_update,function(result){

							})
						})
					})
					
					
					
					
				}
				else{
					
					var new_places = backgammon.convert_array_to_place( place_color, place_value);
				
					game_data.places = new_places;
					game_data.moves = data.moves;

					if ( game_data.time == 'WHITE' ){
						game_data.time = 'BLACK';
					}
					else if ( game_data.time == 'BLACK'){
						game_data.time = 'WHITE';
					}

					var update = { game_data: game_data,
								  status: 7,
								 };
					
					game.update_online_gamer( {token: data.token}, update,function(result){

						op_data.game_data.places = new_places;
						op_data.game_data.time = game_data.time;

						game.update_online_gamer( op_filter, {game_data: op_data.game_data, status: 5}, function(result){

						})
					});
				}
				
				
				
			}
			
			
			
			
		});
		
			
//		forEach( moves in data.moves ){
//			
//		}
		
		game_data = { moves: data.moves, amount: amount};
		
		})
		
	})

}



module.exports.play_hand = function play_hand(data, callback){
	
	
	if ( data.game_id == 5 ){
		this.waiting( data, function(result){

		});
	}
	else if (data.game_id == 6 ){
		callback({
					command		: "played",
					places		: '',
					places_were	: '',
					player		: 'me',
					posibilities:"29;9A,20;0B.24;45,26;68",
					moves	 	: data.moves,

				});

			callback({
							command		: "game_status",
							places		:'1.B.2,12.B.5,17.B.3,19.B.5,24.W.2,13.W.5,8.W.3,6.W.5',
	//						turn" :'BLACK',
							turn		: 'WHITE',
	//						amount		: data.amount,
	//						double":$Data['double'],
							double		 :'false',


							dice_1		 :'6',
							dice_2		 :'6',
	//						posibilities :"23;25,2425262728",
	//						posibilities :"23;25;31",
	//						posibilities	 :"27,24,28,29;29;30",
	//						posibilities	 :"29;9a,24;45;46,bc,bd.20;0A",
	//						posibilities	 :"29;9A;AB;BC,20;0B.24;45,26;68.23;34",
							posibilities	 :"D7,D9.D7;73.D9;93",


							status		 :4,

			})
	}
	
	
	
}




module.exports.check_play_game_by_game_id = function  ( data, callback){
	
	switch ( data.game_id ){
			
		case '5':{
			callback( play_game_rps(data) );
			break;
		}
	}
	
}

function play_game_rps ( data ){

	var return_array = {
							command       :  "select",
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
				var win_id = data.user_id,
					lose_id = data.op_user_id,
					status = 30;
//				callback( win_game_rps( data, data.user_id ) );
			}
			else if ( my_score < op_score ){
				var win_id = data.op_user_id,
					lose_id = data.user_id,
					status = 31;
//				callback( win_game_rps( data, data.op_user_id ) );
			}
			
			amount = data.game_data.amount;
			
			
			if (status == 30 || status == 31){
				var filter = {
					user_id : data.user_id,
					token	: data.token
				}
				var update = { status : status};
				game.update_online_gamer(filter, update, function(result){
					
				})

			}
			
			
//				game.update_user_cash( win_id, amount, data.game_id, function(result){
//				
//				});
			
//			callback( win_game_rps( data, win_id ) );
			

		}
		
		
		
	}, 4000);
		
	
	
}


module.exports.check_winner_rps_with_status = function ( data, status, callback){
	
	
	
	var amount = data.game_data.amount;
	
	game.get_percent_play_with_game_id( data.game_id, function(game_row){
		
		var percent	= game_row[0].percent_play;

		var percent_amount = (amount * percent) / 100;

		amount = (amount * 2) - percent_amount;
		console.log(status);
			if ( status == 30 ){
				var win_id = data.user_id;
				game.update_user_cash( win_id, amount, data.game_id, '8', function(result){
					callback( win_game_rps( data, amount, win_id ) );
				});
				
				affiliate_rps( data.op_user_id, amount );

	//				callback( win_game_rps( data, data.user_id ) );
			}
			else if ( status == 31 ){
				var win_id = data.op_user_id;
				
				callback( win_game_rps( data, amount, win_id ) );
	//				callback( win_game_rps( data, data.op_user_id ) );
			}


		
	});
	

		
	
	
	
}



function affiliate_rps ( user_id, amount ){
	
	game.get_casino_affiliate( user_id, function(row){
			
			
			if ( row.invite_user_id != undefined ){
				
				var price = ((amount / 2) * row.percent.value ) / 100;
				
				game.update_user_cash( row.invite_user_id, price, 5, '9', function( results){

				})
			}
			
								  
								  
		});
	
	
}




function win_game_rps ( data, amount, win_uid ){
	
	var uid1 = data.user_id,
		uid2 = data.op_user_id,
		winner = win_uid,
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



