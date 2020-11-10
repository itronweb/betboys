// JavaScript Document

"use strict";
var rethinkdb = require('rethinkdb');
var db = require('./db_rethink');
var async = require('async');

class online_gamer {
	
	insert( data, callback){
		var gamer = new db();
		
		gamer.connectToDb(function (err, connection){
			if(err) {
				return callback(true,"Error connecting to database");
			  }
			
			rethinkdb.db('vip90_sgbs').table('online_gamer').insert( data ).run(connection,function(err,result) {
				
			  connection.close();
			  if(err) {
				return callback(true,"Error happens while adding new polls");
			  }
			  callback(null,result);
			});
		});
		
	}
	
	find ( data, callback){
		var gamer = new db();
		
		gamer.connectToDb(function (err, connection){
			if(err) {
				return callback(true,"Error connecting to database");
			  }
			
			rethinkdb.db('vip90_sgbs').table('online_gamer').filter(data).run(connection,function(err,result) {
				
			  
			  if(err) {
				return callback(true,"Error happens while adding new polls");
			  }
			  callback(null,result);
			});
		});
	}
}


module.exports = online_gamer;