// JavaScript Document

//var Promise = require('bluebird');
var r = require('rethinkdb');
var config 	= require('../config');



module.exports.connectDB = function(callback){
	
	r.connect(config.rethinkdb, function(err, conn) {
        if (err) {
            console.log('Could not open a connection to initialize the database: ' + err.message);
        }
        else {
		
			callback( conn );
        }
		
    });
	
}


module.exports.createDatabase = function(conn, databaseName) {
	return r.dbList().run(conn).then(function(list) {
		var dbFound = false;
		for (var i=0; i<list.length; i++) {
			if (list[i] == databaseName) {
				dbFound = true;
				break;
			}
		}
		if (! dbFound) {
			console.log('Creating database...');
			return r.dbCreate(databaseName).run(conn);
		}
		else {
			console.log('Database exists.');
			return Promise.resolve({dbs_exists:true});
		}
	});
};

module.exports.createTable = function(conn, tableName) {
	return r.tableList().run(conn).then(function(list) {
		var tableFound = false;
		for (var i=0; i<list.length; i++) {
			if (list[i] == tableName) {
				tableFound = true;
				break;
			}
		}
		if (! tableFound) {
			console.log('Creating table...');
			return r.tableCreate(tableName).run(conn);
		}
		else {
			console.log('Table exists.');
			return Promise.resolve({table_exists:true});
		}
	});
};

module.exports.insertData = function( data, callback) {
	
	this.connectDB( function(conn){
		
		r.table('online_gamer').insert( data ).run(conn,function(err,result) {

			  conn.close();
			  if(err) {
				return callback(true,"Error happens while adding new polls");
			  }
			  callback(result);

			});
			
        });
};

module.exports.filterData = function( data, callback) {
	
	this.connectDB( function(conn){
		
		r.table('online_gamer').filter( data ).run(conn,function(err,result) {

			  conn.close();
			  if(err) {
				return callback(true,"Error happens while adding new polls");
			  }
			
			  callback(result);
//				console.log(result._responses[0].r);
//				console.log(result._responses[0]);
//				var x = result._responses[0].r;
//
//				x.forEach( function(item){
//					console.log(item.data);
//				});

			});
			
        });
}
	
module.exports.updateData = function( filterData, updateData, callback) {
	
	this.connectDB( function(conn){
		
		r.table('online_gamer').filter( filterData ).update( updateData ).run(conn,function(err,result) {
				
			  conn.close();
			  if(err) {
				return callback(true,"Error happens while adding new polls");
			  }
			  callback(result);
			});
			
        });
}
		
module.exports.deleteData = function( filterData, callback) {
	
	this.connectDB( function(conn){
		
		r.table('online_gamer').filter( filterData ).delete().run(conn,function(err,result) {
				
			  conn.close();
			  if(err) {
				return callback(true,"Error happens while adding new polls");
			  }
			  callback(result);
			});
			
        });
}	
	
module.exports.changeData = function( filterData, callback) {
	
	this.connectDB( function(conn){
		
		r.table('online_gamer').filter( filterData ).changes().run(conn, function( err, cursor){
			
			if (err) {
				throw error;
				return
			}
			
			cursor.on("error", function(error) {
				throw error;
				return;
			});
			
			
			cursor.on("data", function(message) {
//				console.log('change_data');
//				console.log(message);
				if (typeof message.new_val != "undefined" ){
					callback(message);	
				}
				
			})
			
//			callback(cursor);
			
			
//			conn.close();
//			cursor.each(function(err,row) {
//			  console.log(JSON.stringify(row));
//			  
//			  if(Object.keys(row).length > 0) {
//				socket.broadcast.emit("changeFeed",{"id" : row.new_val.id,"polls" : row.new_val.polls});
//			  }

//			});
		});
		
		});
}


module.exports.changeData1 = function( conn,filterData, callback) {
	
	
		r.table('online_gamer').filter( filterData ).changes().run(conn, function( err, cursor){
			
			if (err) {
				throw error;
				return
			}
			callback(cursor);
//			cursor.on("error", function(error) {
//				throw error;
//				return;
//			});
//			
//			cursor.on("data", function(message) {
//				
//				if (typeof message.new_val != "undefined" ){
//					callback(message);	
//				}
//				
//			})
			
//			callback(cursor);
			
			
//			conn.close();
//			cursor.each(function(err,row) {
//			  console.log(JSON.stringify(row));
//			  
//			  if(Object.keys(row).length > 0) {
//				socket.broadcast.emit("changeFeed",{"id" : row.new_val.id,"polls" : row.new_val.polls});
//			  }

//			});
		});
		
		
}
	
	