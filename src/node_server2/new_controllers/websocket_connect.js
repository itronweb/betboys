/**
 * Created by Muhammad-ASUS on 8/10/2018.
 */

var express         = require('express');
var bodyParser      = require('body-parser');
var game 			= require('./game');
var onlineGamerController 	= require('./databaseController');

var enter_game		= require('./game_command/game_enter_game');
var game_play		= require('./game_command/game_play');
var game_start		= require('./game_command/game_start');
var game_cancel		= require('./game_command/game_cancel_game');
var game_list		= require('./game_command/game_list');
var game_dic		= require('./game_command/game_dic');
var backgammon		= require('./game_command/game_backgammon');

var app = express();
var url_encode = bodyParser.urlencoded({ extended: false});
app.use(bodyParser.json());



function get_msg ( msg, callback) {

    if ( msg == null )
        callback(null);

    var msg_json = JSON.parse(msg);
	var token = msg_json.token;
	
	var filter = {
		user_id: msg_json.uid,
		token: msg_json.token,
		game_id: msg_json.game_id
	}
	
	
	
	game.auth_is_exist(msg_json, function(result){
		
//		game.waiting({token: msg_json.token}, function(cursor){
//			
//			
//			
//		});
		
		if ( msg_json.command == 'auth'){
			
			var game_auth = require('./game_command/game_auth');
			
			game_auth.check_auth_by_game_id(result,function(result){
				
				callback(JSON.stringify(result));
				
			});
			
		}
		else if ( msg_json.command == 'enter_game' ){
			
			var enter_game = require('./game_command/game_enter_game');
			
			enter_game.waiting( msg_json, function(result){
				
//				callback(JSON.stringify(result));
			});
			
			
		}
		else if ( msg_json.command == 'play' && msg_json.game_id == 5 ){
			
			var game_play = require('./game_command/game_play');
			
			game_play.play_hand( msg_json, function(result){
//				callback(JSON.stringify(result));
			})
			
			
		}
		else if ( msg_json.command == 'play' && msg_json.game_id == 6 ){
			
			var game_play = require('./game_command/game_play');
			
			game_play.play_backgammon( msg_json, function(result){
				callback(JSON.stringify(result));
			})
		}
		else if ( msg_json.command == 'cancel_game' ){
			
			var game_cancel = require('./game_command/game_cancel_game');
			
			game_cancel.cancel( msg_json, function(result){
//				callback(JSON.stringify(result));
			})
			
			
		}
		else if ( msg_json.command == 'drawn' ){
			
			var drawn = require('./game_command/game_drawn');
			if ( msg_json.game_id == 5 ){
				drawn.cancel( msg_json, function(result){
	//				callback(JSON.stringify(result));
				})
			}
			else if ( msg_json.game_id == 6 ){
				drawn.cancel_backgammon( msg_json, function(result){
	//				callback(JSON.stringify(result));
				})
			}
			
			
			
		}
		else if ( msg_json.command == 'games_list' ){
			
			var game_list = require('./game_command/game_list');
			
			game_list.get_list( msg_json, function(result){
				callback(JSON.stringify(result));
			})
			
			
		}
	});
    
}


function check_cursor( token, cursor, callback){
	
	
	game.get_online_gamer_row_with_token( token, function( data){});
	value = cursor.new_val;
	old_val = cursor.old_val;
	
	if ( value != undefined && old_val != undefined ){
		
		if ( value.status != old_val.status ){
			
			
			if ( value.status == 3 && value.game_id == 6){
				
				
			}
			else if ( value.status == 4 && value.game_id == 6 ){
				
//				console.log('start status 4 ');
//				setTimeout( function(){
					
					backgammon.status_4( value, function(json_result){

						callback( json_result );

					})
					
//				}, 200)
				
			}
			else if ( value.status == 5 && value.game_id == 6 ){
				
				game_dic.backgammon_dic( value, function(results){
					callback(JSON.stringify(results));
				})

				backgammon.status_5(value, function(result){
					callback(JSON.stringify(result));
					
				});
				
			}
			else if ( value.status == 6 && value.game_id == 6 ){
				
				backgammon.status_6( value, function(result){
					callback(JSON.stringify(result));
				});
				
			}
			else if ( value.status == 15 && value.game_id == 6 ){

				backgammon.status_15( value, function(result){
						callback(JSON.stringify(result));
					});
			}
			else if ( value.status == 16 && value.game_id == 6 ){

				backgammon.status_16( value, function(result){
						callback(JSON.stringify(result));
					});
			}
			else if ( value.status == 17 && value.game_id == 6 ){

				backgammon.status_17( value, function(result){
						callback(JSON.stringify(result));
					});
			}
			
			
			
			/////////////////////////// rps //////////////////////////////////
			
			if ( value.status == 4 && value.game_id == 5){
//				//////////////// enter game rps /////////////////
				game.get_user_with_id(value.op_user_id, function(op_user){
					game.get_user_with_id(value.user_id, function(my_user){
						data.my_data = my_user[0];
						data.op_data = op_user[0];
						data.amount = value.game_data.amount;
						
						game.update_user_cash(value.user_id, -data.amount,value.game_id, '7', function(update){
							
						})
						
						enter_game.check_enter_game_by_game_id(data, function(result){
							callback(JSON.stringify(result));
						})
					})
				});
			}
			else if ( value.status == 6 && value.game_id == 5 ){
				///////////////// wait for op select rps ///////////////
				
				clearTimeout(test);	
				game_start.send_notify_for_user_id( value, value.user_id,value.op_user_id, function( json ){
					callback(JSON.stringify(json));
				})
				
//				callback(JSON.stringify({
//							command			: 'game_started', 
//							turn_time     	:  10000,
//							turn_total     	:  10000,
//							game_status     :  3,
//				}));
				
				var test = game.waiting_after_time_and_play( value, 10000, function(status){
//					callback(JSON.stringify(status));
				});
			}
			else if ( value.status == 5 && value.game_id == 5 ){
				///////////////// wait for op select rps ///////////////
				
					
				game_start.send_notify_for_user_id( value, value.user_id,value.op_user_id, function( json ){
					callback(JSON.stringify(json));
				})
				
//				callback(JSON.stringify({
//							command			: 'game_started', 
//							turn_time     	:  10000,
//							turn_total     	:  10000,
//							game_status     :  3,
//				}));
				
//				game.waiting_after_time_and_play( value, 10000, function(status){
////					callback(JSON.stringify(status));
//				});
			}
			else if ( value.status == 7 && value.game_id == 5 ){
				////////////////// play game rps ///////////////////
				clearTimeout(test);
				game.get_my_and_op_data( value, function(result){
					var my_data = result.my_data,
						op_data = result.op_data;
				
					data.selected = my_data.game_data.select[my_data.game_data.select.length-1];
					data.op_select = op_data.game_data.select[op_data.game_data.select.length-1];
				
					game.get_score_with_user_id_rps( value, function(score){
							data.my_score = score.my_score;
							data.op_score = score.op_score;
				
							game_play.check_play_game_by_game_id( data, function(result){
								callback(JSON.stringify(result));
								
								game_play.check_winner_rps( value, score, function(winner){
									
									
									
//									callback(JSON.stringify(winner));
								});
							});
							
						});
						
						
					
				})
				
				
			}
			else if ( value.status == 20 && value.game_id == 5 ){
				
				game.get_data_row( {token: value.token}, function(result){
					game.reset_data_rps( value, function(result){
						game_cancel.check_cancel_game_by_game_id( value, function(result){
							callback(JSON.stringify(result));
						});
					});
				});
			}
			else if ( value.status == 21 && value.game_id == 5 ){
				
				game.get_data_row( {token: value.token}, function(result){
					
					game.reset_data_rps( value, function(result){
						game_cancel.check_cancel_game_by_game_id( value, function(result){
							callback(JSON.stringify(result));
						});
					});
//					game.update_status( value.token, value.user_id, 2 , function(result){
//						
//					});
				});
			}
			else if ( value.status == 30 && value.game_id == 5 ){
				
				
				game_play.check_winner_rps_with_status( value, 30, function(result){
					
					game.reset_data_rps( value, function(results){
						callback(JSON.stringify(result));
					});
					
					
				});
			}
			else if ( value.status == 31 && value.game_id == 5  ){
				
				game_play.check_winner_rps_with_status( value, 31, function(result){
					
					game.reset_data_rps( value, function(results){
						callback(JSON.stringify(result));
					});
					
					
				});
			}
//			else if ( value.status == 3 && value.game_id == 5 ){
//				game_list.get_list( value, function(result){
//					
//					callback(JSON.stringify(result));
//				})
//
//				
//			}
			else if ( value.status == 99 && (value.game_id == 5 || value.game_id == 6) ){
				
				var message = {
					message	: "not_enough_chips", 
					command	: "error",
				}
				game.update_online_gamer({token: value.token}, {status: 2}, function(result){
					callback(JSON.stringify(message));
				})
				
				
			}
		
			
			
			
		}
		
	}
	
//	if ( value.status == 4 && value.game_id == 5){
//				//////////////// enter game rps /////////////////
//				game.get_user_with_id(value.op_user_id, function(op_user){
//					game.get_user_with_id(value.user_id, function(my_user){
//						data.my_data = my_user[0];
//						data.op_data = op_user[0];
//						data.amount = value.game_data.amount;
//						
//						game.update_user_cash(value.user_id, -data.amount,value.game_id, '7', function(update){
//							
//						})
//						
//						enter_game.check_enter_game_by_game_id(data, function(result){
//							callback(JSON.stringify(result));
//						})
//					})
//				});
//			}
//			else if ( value.status == 6 && value.game_id == 5 ){
//				///////////////// wait for op select rps ///////////////
//				
//				clearTimeout(test);	
//				game_start.send_notify_for_user_id( value, value.user_id,value.op_user_id, function( json ){
//					callback(JSON.stringify(json));
//				})
//				
////				callback(JSON.stringify({
////							command			: 'game_started', 
////							turn_time     	:  10000,
////							turn_total     	:  10000,
////							game_status     :  3,
////				}));
//				
//				var test = game.waiting_after_time_and_play( value, 10000, function(status){
////					callback(JSON.stringify(status));
//				});
//			}
//			else if ( value.status == 5 && value.game_id == 5 ){
//				///////////////// wait for op select rps ///////////////
//				
//					
//				game_start.send_notify_for_user_id( value, value.user_id,value.op_user_id, function( json ){
//					callback(JSON.stringify(json));
//				})
//				
////				callback(JSON.stringify({
////							command			: 'game_started', 
////							turn_time     	:  10000,
////							turn_total     	:  10000,
////							game_status     :  3,
////				}));
//				
////				game.waiting_after_time_and_play( value, 10000, function(status){
//////					callback(JSON.stringify(status));
////				});
//			}
//			else if ( value.status == 7 && value.game_id == 5 ){
//				////////////////// play game rps ///////////////////
//				clearTimeout(test);
//				game.get_my_and_op_data( value, function(result){
//					var my_data = result.my_data,
//						op_data = result.op_data;
//				
//					data.selected = my_data.game_data.select[my_data.game_data.select.length-1];
//					data.op_select = op_data.game_data.select[op_data.game_data.select.length-1];
//				
//					game.get_score_with_user_id_rps( value, function(score){
//							data.my_score = score.my_score;
//							data.op_score = score.op_score;
//				
//							game_play.check_play_game_by_game_id( data, function(result){
//								callback(JSON.stringify(result));
//								
//								game_play.check_winner_rps( value, score, function(winner){
//									
//									
//									
////									callback(JSON.stringify(winner));
//								});
//							});
//							
//						});
//						
//						
//					
//				})
//				
//				
//			}
//			else if ( value.status == 20 && (value.game_id == 5||value.game_id == 6) ){
//				
//				game.get_data_row( {token: value.token}, function(result){
//					game.reset_data_rps( value, function(result){
//						game_cancel.check_cancel_game_by_game_id( value, function(result){
//							callback(JSON.stringify(result));
//						});
//					});
//				});
//			}
//			else if ( value.status == 21 && (value.game_id == 5||value.game_id == 6) ){
//				
//				game.get_data_row( {token: value.token}, function(result){
//					
//					game.reset_data_rps( value, function(result){
//						game_cancel.check_cancel_game_by_game_id( value, function(result){
//							callback(JSON.stringify(result));
//						});
//					});
////					game.update_status( value.token, value.user_id, 2 , function(result){
////						
////					});
//				});
//			}
//			else if ( value.status == 30 && value.game_id == 5 ){
//				
//				
//				game_play.check_winner_rps_with_status( value, 30, function(result){
//					
//					game.reset_data_rps( value, function(results){
//						callback(JSON.stringify(result));
//					});
//					
//					
//				});
//			}
//			else if ( value.status == 31 && value.game_id == 5  ){
//				
//				game_play.check_winner_rps_with_status( value, 31, function(result){
//					
//					game.reset_data_rps( value, function(results){
//						callback(JSON.stringify(result));
//					});
//					
//					
//				});
//			}
////			else if ( value.status == 3 && value.game_id == 5 ){
////				game_list.get_list( value, function(result){
////					
////					callback(JSON.stringify(result));
////				})
////
////				
////			}
//			else if ( value.status == 99 && (value.game_id == 5 || value.game_id == 6) ){
//				
//				var message = {
//					message	: "not_enough_chips", 
//					command	: "error",
//				}
//				game.update_online_gamer({token: value.token}, {status: 2}, function(result){
//					callback(JSON.stringify(message));
//				})
//				
//				
//			}
//		
//		
//		
//		/////////////////////// backgammon ////////////////////////////
//		
//		if ( value.status == 4 && value.game_id == 6){
//				//////////////// enter game rps /////////////////
//			
//				game.get_user_with_id(value.op_user_id, function(op_user){
//					game.get_user_with_id(value.user_id, function(my_user){
//						data.my_data = my_user[0];
//						data.op_data = op_user[0];
//						data.amount = value.game_data.amount;
//						
//						game.get_my_and_op_data( value, function(result){
//							
//							data.my = result.my_data;
//							data.op = result.op_data;
//							
//							enter_game.check_enter_game_by_game_id(data, function(result){
//										callback(JSON.stringify(result));
//
//		//								var game_data = value.game_data;
//		//
//		//								if ( game_data.side == 'me' && game_data.time == 'WHITE' ){
//		//									game.update_online_gamer({token:data.token}, {status:5}, function(result){
//		//									})
//		//								}
//		//									game_dic.backgammon_dic( value, function(results){
//		//										callback(JSON.stringify(results));
//		//									})
//									})
//							
//							game.update_user_cash(data.my.user_id, -data.amount,value.game_id, '7', function(update){
//								
//
//								})
//
//							
//							
//						})
//						
//						
//					})
//				});
//			}
//		else if ( value.status == 5 && value.game_id == 6 ){
//			
//			console.log('status 5');
//			console.log(value.user_id);
//			if ( old_val.status != value.status ){
//				
//				game_dic.backgammon_dic( value, function(results){
////					callback(JSON.stringify(results));
//				})
//
//				backgammon.status_5(value, function(result){
//					callback(JSON.stringify(result));
//					
//				});
//				
//			}
//			
//			
//			
////			callback({
////				command: 'game_dice',
////				turn	: 'BLACK',
////				dice_1 : 6,
////				dice_2 : 6,
////			})	
//		}
//		else if ( value.status == 6 && value.game_id == 6 ){
//			
//			if ( old_val.status != value.status ){
//				
//				backgammon.status_6( value, function(result){
//					callback(JSON.stringify(result));
//				});
//				
//			}
////			callback(JSON.stringify({
////				command: 'game_dice',
////				turn	: value.game_data.time,
////				dice_1 : 6,
////				dice_2 : 6,
////			}));	
//		}
//		else if ( value.status == 7 && value.game_id == 6 ){
//			
//		}
//		else if ( value.status == 15 && value.game_id == 6 ){
//			
//			backgammon.status_15( value, function(result){
//					callback(JSON.stringify(result));
//				});
//		}
//		else if ( value.status == 16 && value.game_id == 6 ){
//			
//			backgammon.status_16( value, function(result){
//					callback(JSON.stringify(result));
//				});
//		}
//		
	
	
}


function get_game_list( token, cursor, callback){
	
//	var data = JSON.parse(msg);
	
	if ( cursor.new_val != null){
		value = cursor.new_val;
		old_val = cursor.old_val;
		
		game_list.get_list( value, function(result){
			
			
			callback(JSON.stringify(result));
		})
		
	}
}


function connection_close1( msg, callback){
	
	if ( msg.game_id == 6 ){
		var filter = { token: msg.token };
		var update = { status: 17 };
		backgammon.status_17 ( msg, function( result ){
			callback(result);
		});
		
	}
	else if ( msg.game_id == 5 ){
		var filter = { token: msg.token };
		
		game.get_data_row( filter, function(my_data){
		
		
		if ( my_data.status != 2 ){
			
			var my_update = {status: 2};
			
			
//			game.remove_online_gamer( filter, function(result){
	//			
	//		});

			var op_filter = { 
				user_id: my_data.op_user_id, 
				game_id: my_data.game_id, 
				op_user_id: my_data.user_id
			};
			var op_update = { status: 30};


			game.update_online_gamer( filter, my_update,function(result){
				callback(result);
					});
			
			game.update_online_gamer( op_filter, op_update,function(result){
				callback(result);
					});
			
			}
			
		})
	}
	
	
}

function connection_close( token, callback){
	
	game.get_online_gamer_row_with_token( token, function( result ){
		
		
		if ( result != 0 ){
			
			
			console.log('connection closed data');
			
			console.log(result);
			if ( result.op_user_id != undefined ){
				var filter = {user_id		: result.op_user_id,
						  game_id		: result.game_id,
						  op_user_id	: result.user_id};
					
				if ( result.game_id == 6 )
					var update = { status: 16};
				else if ( result.game_id == 5 )
					var update = { status: 30};
			
				game.update_online_gamer( filter, update, function(result){

				});

				game.remove_online_gamer( {token: result.token}, function( result){

				})
			}
			
			
			
			
		}
		
		
	})
//	
//	if ( msg.game_id == 6 ){
//		var filter = { token: msg.token };
//		var update = { status: 17 };
//		backgammon.status_17 ( msg, function( result ){
//			callback(result);
//		});
//		
//	}
//	else if ( msg.game_id == 5 ){
//		var filter = { token: msg.token };
//		
//		game.get_data_row( filter, function(my_data){
//		
//		
//		if ( my_data.status != 2 ){
//			
//			var my_update = {status: 2};
//			
//			
////			game.remove_online_gamer( filter, function(result){
//	//			
//	//		});
//
//			var op_filter = { 
//				user_id: my_data.op_user_id, 
//				game_id: my_data.game_id, 
//				op_user_id: my_data.user_id
//			};
//			var op_update = { status: 30};
//
//
//			game.update_online_gamer( filter, my_update,function(result){
//				callback(result);
//					});
//			
//			game.update_online_gamer( op_filter, op_update,function(result){
//				callback(result);
//					});
//			
//			}
//			
//		})
//	}
//	
	
}


module.exports = {
    get_msg: get_msg,
    check_cursor: check_cursor,
    get_game_list: get_game_list,
    connection_close: connection_close,
};