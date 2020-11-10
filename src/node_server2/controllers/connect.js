// JavaScript Document

var express         = require('express');


var app = express();
var expressWs = require('express-ws')(app);
var websocket_connect = require('./websocket_connect');
var onlineGamerController = require('./databaseController');


class connect {
	
	
	
	constructor(){
		this.value = '';
		onlineGamerController.changeData( {}, (cursor)=>{
			console.log('constructor');
			
			this.set_value(cursor);
			
//			this.value = cursor;
//			console.log(this.value);
//			console.log(this.ws);
//			console.log(this.ws != undefined);
//			if ( this.ws != undefined){
////				this.ws.send({test:'test'});
//			}
			
		});
		
		
		
	}
	
	set_value(cursor){
		this.value = cursor;
	}
	

	get_value(){
		return this.value;
	}
	
	change (callback){
		console.log('change');
		
		callback(this.get_value());
//		console.log(ws);
//		this.ws = ws;
		
//		callback(this.get_value());
		
//		this.ws.send(JSON.stringify({test: 'test'}));
//		websocket_connect.get_msg( msg, function (json) {
//				
//			
//
//				if ( json != null)
//					ws.send(json);
//
//			});
		
	}
	
}

module.exports = connect;



//
//module.exports.connect = function ( app, cursor, callback){
//	
//
//	app.ws('/connect', function (ws, req) {
//
//		ws.on('message', function (msg) {
//
//
//			callback(cursor);
//
//			websocket_connect.get_msg( msg, function (json) {
//
//			 if ( json != null)
//				ws.send(json);
//
//			});
//		});
//
//
//	});
//	
//	
//}
