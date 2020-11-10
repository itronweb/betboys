// JavaScript Document

var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var onlineGamerController = require('./databaseController');
var db_mysql = require('../models/db_mysql');


var game = require('./game');
var enter_game = require('./game_command/game_enter_game');


function check_cursor( data, cursor, callback){
	
	if ( cursor.new_val != null){
		value = cursor.new_val;
			if ( value.status == 4 && value.game_id == 5){
				
				game.get_user_with_id(value.op_user_id, function(op_user){
					game.get_user_with_id(value.op_user_id, function(my_user){
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

module.exports = {
	check_cursor : check_cursor
    
};