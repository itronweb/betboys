// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');
var onlineGamerController = require('../databaseController');
var randomstring = require("randomstring");


module.exports.backgammon_dic = function backgammon_dic( data, callback){
	
	var game_data = data.game_data;
	
	var dic_1 = this.random_dic();
	var dic_2 = this.random_dic();
	
//	dic_1 = 4;
//	dic_2 = 4;
//	
	var dic = { dic_1: dic_1, dic_2: dic_2};
	
	if ( (game_data.side == 'me' && game_data.time == 'WHITE') || (game_data.side == 'op' && game_data.time == 'BLACK')){
		
		callback({
				command: 'game_dice',
				turn	: game_data.time,
				dice_1 : dic_1,
				dice_2 : dic_2,
			});
		
		this.update_status( data, dic );
		
	}
	
}

module.exports.random_dic = function random_dic(){
	
	return	randomstring.generate({
			  length: 1,
			  charset: '123456'
			});
	
	
}

module.exports.update_status = function update_status(data, dic){
	
	data.game_data.dic = dic;
	var filter_my = { token: data.token},
		update_my = { game_data: data.game_data },
		filter_op = { user_id: data.op_user_id, game_id: data.game_id, op_user_id: data.user_id},
		update_op = { status: 6 };
	
	game.update_online_gamer( filter_my, update_my, function(result){
		game.update_online_gamer( filter_op, update_op, function(result){
			
		});
	});
	
	
	
}
