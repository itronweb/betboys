/**
 * Created by Muhammad-ASUS on 8/10/2018.
 */var express = require('express');
var bodyParser = require('body-parser');
var randomstring = require("randomstring");
var db_mysql = require('../models/db_mysql');
var db_mongo = require('../models/db_mongo');
var onlinGamerController = require('./databaseController');

function auth(data, callback) {
	var query = "SELECT id,username,cash,email FROM users WHERE game_token=? AND status=?";
	db_mysql.select_2(query, data.code, 1, function (db_result) {
		result = db_result;
		if (result !== false) {
			if (result[0] !== undefined) {
				if (result[0].id !== undefined) {
					var insert_data = {
						game_id: data.game_id,
						user_id: result[0].id,
						token: randomstring.generate(50),
						status: 2,
					};
					var search_data = {
						game_id: data.game_id,
						user_id: result[0].id
					}
					var auth_callback = {
						"code": 200,
						"result": "ok",
						'test': result[0],
						'test1': data,
						'data': {
							"ip": "136.243.255.205",
							"port": 100,
							"address": "ws://136.243.255.205:3001/connect/",
							"uid": result[0].id,
							"token": insert_data.token,
							"game_id": data.game_id,
							"bet": '1000',
							"bets": ["1K", "5K", "10K", "25K", "50K"],
							"data": {},
							"javascript": ''
						},
						'auth': {
							"login": true,
							"id": result[0].id,
							"name": result[0].username,
							"photo": "",
							"status": "active",
							"language": "fa",
							"amount": result[0].cash,
							"bonus": 0,
							"aff": 0,
							"channel": 0,
							"group": 0,
							"level": 1,
							"verification": 0
						}
					}
					onlinGamerController.connectDB(function (conn) {
						onlinGamerController.filterData(search_data, function (filter_result) {
							if (filter_result._responses.length == 0) {
								onlinGamerController.insertData(insert_data, function (in_result) {
									callback(auth_callback);
								});
							} else if (filter_result._responses.length > 0) {
								var data = filter_result._responses[0].r[0];
								if ((data.status == 5 || data.status == 6 || data.status == 7) && data.game_id == 5) {
									var op_filter = {
										user_id: data.op_user_id,
										game_id: data.game_id
									}
									var status = {status: 30};
									onlinGamerController.updateData(op_filter, status, function (result) {
									});
									var my_filter = {
										user_id: data.user_id,
										game_id: data.game_id
									}
									var status = {status: 31};
									onlinGamerController.updateData(my_filter, status, function (result) {
									});
								} else if (data.status == 4 && data.game_id == 5) {
									var op_filter = {
										user_id: data.op_user_id,
										game_id: data.game_id
									}
									var status = {status: 30};
									onlinGamerController.updateData(op_filter, status, function (result) {
									});
									var my_filter = {
										user_id: data.user_id,
										game_id: data.game_id
									}
									var status = {status: 31};
									onlinGamerController.updateData(my_filter, status, function (result) {
									});
								}
									//						else if ( (data.status == 4 || data.status == 6 || data.status == 7) && data.game_id == 6 ){
									//							console.log( 'auth with status 111111111111111');
									////							return;
								//						}
								else if ((data.status == 5 || data.status == 4 || data.status == 6 || data.status == 7) && data.game_id == 6) {
									var op_filter = {
										user_id: data.op_user_id,
										game_id: data.game_id
									}
									var status = {status: 16};
									onlinGamerController.updateData(op_filter, status, function (result) {
									});
								}
								onlinGamerController.updateData(search_data, insert_data, function (up_result) {
									callback(auth_callback);
								});
							}
						});
					});
				}
			}
		}
	});
}

function connect_auth(data, callback) {
}

module.exports = {
	auth: auth,
};