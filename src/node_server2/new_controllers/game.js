// JavaScript Document

var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var onlineGamerController = require('./databaseController');
var db_mysql = require('../models/db_mysql');
var thisController = require('./game');

function auth_is_exist ( insert_data, callback){
	
	var search = {
		game_id	: insert_data.game_id, 
		token 	: insert_data.token,
		user_id: insert_data.uid,
		
	}
	
	onlineGamerController.filterData(search, function(filter_result){
		
		if (  filter_result._responses[0] != undefined ){
			
			if ( filter_result._responses[0].r.length == 1 ){
				var result = filter_result._responses[0].r[0];
				get_user_with_id( insert_data.uid, function(user_result){

					if ( result === false){
						callback(false);
					}
					else {
						result.username = user_result[0].username;
						result.cash = user_result[0].cash;

						callback(result);
					}

				});
			}
		}
		

	});

}

function get_game_name_with_id ( game_id, callback){
	
	var query = "SELECT * FROM casino WHERE id=? AND status=?";
	
	db_mysql.select_2( query, game_id, 1, function(result){
	
		if( result == null )
			callback(false);
		else
			callback(result);
	})
	
	
}

function get_percent_play_with_game_id ( game_id, callback ){
	
	var query = "SELECT * FROM casino WHERE id=? AND status=?";
	
	db_mysql.select_2( query, game_id, 1, function(result){
	
		if( result == null )
			callback(false);
		else
			callback(result);
	})
	
	
}

function get_user_with_id( user_id, callback){
	
	var query = 'SELECT id,username,cash FROM users WHERE id = ? AND status = ?';
	
	db_mysql.select_2( query, user_id, 1, function(result){
		
		if ( result == null )
			callback(false);
		else
			callback(result);
	});
	
}

function get_online_gamer_row_with_token ( token, callback ){
	
	
	var token = { token: token };
	
	onlineGamerController.filterData( token, function(result){
		
		if ( result._responses != undefined ){
			if ( result._responses[0] != undefined )
				if ( result._responses[0].r != undefined )
					if ( result._responses[0].r[0] != undefined )
						callback(result._responses[0].r[0]);
		}
		else{
			callback(0);
		}
		
		
	});
	
}

function get_casino_affiliate ( user_id, callback ){
	
	var query = 'SELECT value FROM settings WHERE code = ? ';
	
	db_mysql.select_1( query, 'affiliate_casino', function(result){
		
		if ( result == null ){
			callback(false);
		}
		else{
			percent = result[0];
			get_casino_affiliate_user_id( user_id, function(in_user_id){
				
				callback({ percent: percent, invite_user_id: in_user_id})
				
			})
		}
			
	});
	
	
}

function get_casino_affiliate_user_id ( user_id, callback ){
	
	var query = 'SELECT user_id FROM affiliate WHERE invited_user_id = ? ';
	
	db_mysql.select_1( query, user_id, function(result){
		
		if ( result == null ){
			callback(false);
		}
		else{
			if ( result[0] != undefined ){
				invite_user_id = result[0].user_id;
				callback(invite_user_id);
			}
				
			
		}
			
	});
}

function insert_into_transactions( user_id, cash, game_id, invoice_type,new_cash, callback){
	
	get_game_name_with_id(game_id, function(game){
	
		var game_name = game[0].name_fa;

		if ( invoice_type == 7 ){
			description = ' شروع بازی ' + game_name;
			cash *= -1;
		}
		else if ( invoice_type == 8 ){
			description = ' برد بازی '  + game_name;
		}
		else if ( invoice_type == 9 ){
			description = ' کارمزد زیر مجموعه در بازی '  + game_name;
		}
		
		invoice_type = invoice_type + game_id;
		var trans_id		= user_id + Date.now() + game_id,
			pay_code		= trans_id,
			status			= 1;

		var query = "INSERT INTO transactions (price, invoice_type, description, user_id, trans_id, cash, pay_code, status) VALUES ? ";
		
		var values =[[cash, invoice_type, description, user_id, trans_id, new_cash, pay_code, status]];
		
		db_mysql.insert( query, values, function(result){
			callback(result);
		})

	})	
}

function insert_into_transactions1( user_id, cash, game_id, invoice_type,new_cash, callback){
	
	get_game_name_with_id(game_id, function(game){
	
		var game_name = game[0].name_fa;

		if ( invoice_type == 50 ){
			description = ' شروع بازی ' + game_name;
			cash *= -1;
		}
		else if ( invoice_type == 51 ){
			description = ' برد بازی '  + game_name;
		}
		
		var trans_id		= user_id + Date.now() + game_id,
			pay_code		= trans_id,
			status			= 1;

		var query = "INSERT INTO transactions (price, invoice_type, description, user_id, trans_id, cash, pay_code, status) VALUES ? ";
		
		var values =[[cash, invoice_type, description, user_id, trans_id, new_cash, pay_code, status]];
		
		db_mysql.insert( query, values, function(result){
			callback(result);
		})

	})	
}

function update_user_cash ( user_id, cash, game_id, invoice_type, callback  ){
	
	get_user_with_id( user_id, function( user ){
	
		if( user == 0 )
			return;
		
		var old_cash = user[0].cash,
			new_cash = Number(old_cash) + Number(cash);
		
		var query = 'UPDATE users SET cash = ? WHERE id=? AND status=?';
		
//		if ( cash < 0 ){
//			invoice_type = '7';
//		}
//		else if ( cash > 0 ){
//			invoice_type = '8';
//		}
//		
//		if ( typeof game_id != 'number' ){
//			if ( game_id.charAt(0) == 0 ){
//				invoice_type = '9';
//				game_id = game_id.charAt(1);
//			}
//		}
		
		
		db_mysql.update( query, new_cash, user_id, function(result){
			if (result.affectedRows > 0 ){
				insert_into_transactions(user_id, cash, game_id, invoice_type, new_cash, function(result){
					callback(result);
				});
			}
			
		})
		
	})
	
	
	
}


function bets_amount_is_valid_with_id( user_id, amount, callback){
	
	get_user_with_id( user_id, function( result ){
		
		if ( result == null){
			callback(false);
		}
		else if ( result[0].cash > amount ){
			callback( result );
		}
		else{
			callback(false);
		}
			
	});
}

//////////// rps function ////////////////

function update_status ( token, user_id, status, callback){
	
	var field = { user_id: user_id, token: token};
	var data = {status : status};
	
	onlineGamerController.updateData(field, data, function (result) {
		callback(result);
	})

	// db_mongo.find_and_update( field, data, function(result){
	//
	// 	callback(result);
	// })
}

function update_online_gamer( filter,data, callback){
	
	onlineGamerController.updateData(filter, data, function (result) {
		callback(result);
	})
}

function update_game_data_for_enter_game ( token, game_id, amount, callback){
	
}

//
//function check_user_is_waiting( token, game_id, amount, callback){
//	
//	var search = { game_id: game_id, status: 3};
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
//	}
//		
//	
//	
//	
//}

function check_any_user_waiting_backgammon ( token, game_id, amount, callback){
	
	var search = { game_id: game_id, status: 3 };
	
}


function check_any_user_waiting ( token, game_id, amount, callback){
	
	var search = { game_id: game_id, status: 3};
				
	onlineGamerController.filterData({token:token}, function( my_result){
		
		var my_data = my_result._responses[0].r[0];
		
		onlineGamerController.filterData(search, function (result) {
		var x = result._responses[0].r;

		var i = 0;

		x.forEach( function(item){
			
			if ( item.token != token && item.user_id != my_data.user_id && item.game_data.amount == amount ){

				var op_data = item;
				
				if ( game_id == 5 ){
					var game_data = { select: [], amount: amount, side: 'me' };
				}
				else if (game_id == 6 ){
					var places = '1.B.2,12.B.5,17.B.3,19.B.5,24.W.2,13.W.5,8.W.3,6.W.5';
//					var places = '7.W.5,6.W.2,5.W.3,4.W.3,3.W.2,18.B.5,19.B.3,20.B.2,21.B.3,22.B.2';
//					var places = '1.W.5,6.W.2,5.W.3,4.W.3,3.W.2,20.B.5,21.B.3,22.B.1,23.B.1,19.B.3,24.B.2';
//					var places = '1.W.2,24.B.2,0.W.13,25.B.13';
//					var places = '1.W.5,2.W.2,3.W.2,4.B.1,5.W.3,26.W.1,21.B.1,20.B.4,19.B.2,18.B.2,17.B.2,16.B.2,';
					
					var time = 'WHITE';
					var game_data = { moves: [], 
									 places: places, 
									 amount: amount, 
									 side: 'me',
									 time: time
									}
				}
				
				var update = {op_user_id: my_data.user_id , status: 4, game_data: game_data };
				var op_search = { token: op_data.token };
				onlineGamerController.updateData(op_search, update, function (result) {
					var search = { token: token};
					
					if ( game_id == 5 ){
						var game_data = { select: [], amount: amount, side: 'op' };
					}
					else if (game_id == 6 ){
						var game_data = { moves: [], places: places, amount: amount, side: 'op', time: time };
					}
					
//					var game_data = { select: [], amount: amount, side: 'op' }
					var update = {op_user_id: op_data.user_id , status: 4, game_data: game_data };
					onlineGamerController.updateData(search, update, function (result) {
						get_user_with_id(op_data.user_id, function(op_user){
							callback(op_user);
						});
					});

				});
			}
			else{
				
				i += 1;
			}
				
			
			
		});
			
		if ( i == x.length){
			
			return callback('No Player');
		}
		
	});
		
		
	});
	
	
}



function search_player( search, callback ){
	
	
	 onlineGamerController.changeData(search, function (cursor){
	 		
		if ( cursor.new_val != null){
			if ( cursor.new_val.status == 4 ){
				
				get_user_with_id(cursor.new_val.op_user_id, function(op_user){
					callback(op_user);
					return;
				});
			}
		}
		
	 });
}

function play_hand_rps ( search, callback){
	
	onlineGamerController.changeData(search, function (cursor){
	 		
		if ( cursor.new_val != null){
			if ( cursor.new_val.status == 7 ){
				
				callback(cursor.new_val.op_user_id);
				return;
				
//				get_user_with_id(cursor.new_val.op_user_id, function(op_user){
//					callback(op_user);
//					return;
//				});
			}

		}
		
	 });
	
}

function get_play_game_rps_data ( search, callback){
	
	get_last_select_rps( search, function(my_select){
		
		
		
	});
	
}

function get_last_select_rps ( search, callback){
	
	onlineGamerController.filterData(search, function(result){
		
		var data = result._responses[0].r[0];
		
		game_data = data.game_data;
		
		callback(game_data.select[game_data.select.length-1]);
		
	});
}


function get_data_with_token ( token, callback ){
	
	onlineGamerController.filterData(token, function(result){
		
		var data = result._responses[0].r[0];
		
		callback(data);
		
	});
}

function waiting( search, callback ){
	
	 onlineGamerController.changeData(search, function (cursor){
	 		
		if ( cursor.new_val != null){
			if ( cursor.new_val.status == 4 ){
				
				get_user_with_id(cursor.new_val.op_user_id, function(op_user){
					callback(op_user);
					return;
				});
			}

		}
		
	 });
}

function test ( callback ){
	db_mongo.change_stream( "1", function(result){
		callback('stream');
	})
}

function set_palyer( my, op, callback){
	
	var my_field = { token: my.token};
	var op_field = { token: op.token};
	var my_data = { op_user_id: op.user_id, game_data: [], status: 4 };
	var op_data = { op_user_id: my.uid, game_data: [], status: 4 };
	db_mongo.find_and_update( my_field, my_data, function(result){
		
		db_mongo.find_and_update( op_field, op_data, function(op_result){
			
			callback( op_result );
			
		});
	});
	
	
}

function check_status_for_enter_game( token, game_id , callback ){
	
	var search = {token: token, game_id: game_id, status: 4 };
	
	db_mongo.find( search, function(result){
		callback(result);
	});
	
	
}

function check_cursor( data, cursor, callback){
	
	if ( cursor.new_val != null){
		value = cursor.new_val;
			if ( value.status == 4 && value.game_id == 5){
				
				get_user_with_id(value.op_user_id, function(op_user){
					get_user_with_id(value.op_user_id, function(my_user){
						data.my_data = my_user[0];
						data.op_data = op_user[0];
						data.side = 'me';
						
						enter_game.check_enter_game_by_game_id(data, function(result){
							callback(JSON.stringify(result));
						})
					})
				});
			}

		}
	
	
}

function get_data_with_filter ( filter, callback){
	
	onlineGamerController.filterData(filter, function( result ){
		
		if(result._responses[0] != undefined){
			callback(result._responses[0].r[0]);
		}
		
	});
	
}

function get_data ( filter, callback){
	
	onlineGamerController.filterData( filter, function( result){
		
		if ( result._responses.length != 0 )
			callback( result._responses[0].r);
		else
			callback(0);
	})
	
}

function check_data_is_correct_then_update( filter, update, status, callback ){
	
	get_data_with_filter( filter, function(find){
		
		if ( find.status == status ){
			update_online_gamer( filter, update, function(result){
				callback(1);
			})
		}
		else{
			callback(0);
		}
	})
	
	
}

function get_score_with_user_id_rps ( my_data, callback){
	
	var my = 0, op = 0, my_score=0, op_score=0;
	
	get_my_and_op_data( my_data, function(data){
		
		my_select = data.my_data.game_data.select;
		op_select = data.op_data.game_data.select;
		
		for( var i = 0; i< my_select.length; i++){

			my = Number(my_select[i]);
			op = Number(op_select[i]);
			
			if ( my != op ){
				my += 1;
			
				if ( my > 3 )
					my -= 3;
			
				if( my == op )
					op_score++;
				else
					my_score++;
			}
		}
		
		callback({my_score : my_score, op_score: op_score});
		
	})
	
	
	
}

function get_my_and_op_data( data, callback){
	
	var my_filter = { token		: data.token};
	var op_filter = { user_id	: data.op_user_id, 
					  op_user_id: data.user_id,
					  game_id	: data.game_id,
					};
	
	
	
	onlineGamerController.filterData( my_filter, function(my_result){
		onlineGamerController.filterData( op_filter, function(op_result){
			
			if( my_result._responses[0] != undefined && op_result._responses[0] != undefined ){
				
				callback( {
					my_data: my_result._responses[0].r[0],
					op_data: op_result._responses[0].r[0],
						  })
			}
			else {
				setTimeout( function(){
					
					onlineGamerController.filterData( op_filter, function(op_result){
						
						if ( my_result._responses[0] != undefined && op_result._responses[0] != undefined ){
							callback( {
								my_data: my_result._responses[0].r[0],
								op_data: op_result._responses[0].r[0],
							  })
						}
					})
					
					
				}, 200)
			}
		})
	})
	
}

function get_data_row ( filter, callback){
	
	onlineGamerController.filterData( filter, function(result){
		if( result._responses[0] != undefined ){
				callback(result._responses[0].r[0]);
			}
	})
	
}


function waiting_after_time_and_play( data, time, callback){
	
	setTimeout( function(){
		
		var filter = { token: data.token, status : 6, game_data: data.game_data }
		
		onlineGamerController.filterData(filter, function(result){
			
			if( result._responses[0] != undefined ){
				var my_data = result._responses[0].r[0];
			
				var select = my_data.game_data.select;
				var amount = my_data.game_data.amount;

				data.selected = '2';
				select.push(data.selected);

				game_data = { select: select, amount: amount};

				var update = {game_data: game_data, status:7};
				var filter = { token: data.token};

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
		
		
	}, time);
	
}

function reset_data_rps ( data, callback){
	
	var update = {
		op_user_id	: '',
		game_data 	: {select: [], amount: 0, side: '', score: 0},
		status		: 2,
	};
	
	var filter = { token: data.token };
	
	onlineGamerController.updateData( filter, update, function(result){
		callback(result);
	});
}

function get_game_list( filter, callback){
	
	onlineGamerController.filterData(filter, function(result){
		
		if(result._responses[0] != undefined){
			callback(result._responses[0].r);
		}
		 
	})
}

function remove_online_gamer ( filter, callback){
	
	onlineGamerController.deleteData( filter, function(result){
		callback(result);
	})
	
}

//////////////////////// backgammon ////////////////////////

function enter_game ( data, callback ){
	
	
	enter_game.check_enter_game_by_game_id(data, function(result){
		callback(result);
	})
	
}



module.exports = {
    get_user_with_id: get_user_with_id,
    auth_is_exist	: auth_is_exist,
    bets_amount_is_valid_with_id	: bets_amount_is_valid_with_id,
    update_status	: update_status,
    check_any_user_waiting	: check_any_user_waiting,
    set_palyer	: set_palyer,
    check_status_for_enter_game	: check_status_for_enter_game,
    test	: test,
    search_player	: search_player,
    waiting	: waiting,
    play_hand_rps	: play_hand_rps,
    get_last_select_rps	: get_last_select_rps,
    get_data_with_token	: get_data_with_token,
    check_cursor	: check_cursor,
    waiting_after_time_and_play	: waiting_after_time_and_play,
    get_data	: get_data,
    get_data_with_filter	: get_data_with_filter,
    get_score_with_user_id_rps	: get_score_with_user_id_rps,
    get_my_and_op_data	: get_my_and_op_data,
    update_user_cash	: update_user_cash,
    get_game_name_with_id	: get_game_name_with_id,
    get_data_row	: get_data_row,
    reset_data_rps	: reset_data_rps,
    update_online_gamer	: update_online_gamer,
    remove_online_gamer	: remove_online_gamer,
    get_game_list	: get_game_list,
    get_percent_play_with_game_id	: get_percent_play_with_game_id,
    get_casino_affiliate	: get_casino_affiliate,
    get_online_gamer_row_with_token	: get_online_gamer_row_with_token,
    check_data_is_correct_then_update	: check_data_is_correct_then_update,
    enter_game	: enter_game,
};