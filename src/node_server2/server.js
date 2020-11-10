/**
 * Created by Muhammad-ASUS on 8/10/2018.
 */

var express         = require('express');
var bodyParser      = require('body-parser');
var http            = require('http');
var WebSocketServer = require('websocket').server;
var fs              = require('fs');
var util            = require('util');
const request       = require("request");
var urljoin         = require('url-join');
var viewEngine      = require('express-json-views');
var rethinkdb		= require('./models/db_rethink');
var querystring		= require('querystring');
var schedule 		= require('node-schedule');
var find 			= require('arraysearch').Finder

var config 	= require('./config');
var r 		= require('rethinkdb');
var databaseController = require('./controllers/databaseController');

const hostname      = '136.243.255.205';
const port          = 3001;
const baseUrl       = 'betboys.xyz';

var app = express();
var url_encode = bodyParser.urlencoded({ extended: false});
var json_parser = bodyParser.json();


app.set('views', __dirname + '/views');
app.set('view engine', 'json');

var auth = require('./router/auth');

//app.use('/', auth);
app.use('/api/user/Newgame/', auth);

databaseController.connectDB( function( conn ){
								   
	   databaseController.createDatabase(conn, config.rethinkdb.db)
			.then(function() {
                    return databaseController.createTable(conn, 'online_gamer');
                })
			.catch(function(err) {
				console.log('Error connecting to RethinkDB: ' + err);
			});
	
	
					   
});



var expressWs = require('express-ws')(app);
var websocket_connect = require('./controllers/websocket_connect');

var connect = require('./controllers/connect');

/////////////////// 1 ////////////////
//databaseController.changeData({}, function(cursor){
//	
//	app.ws('/connect', function (ws, req) {
//		
//		ws.on('message', function (msg) {
//		
//			websocket_connect.get_msg( msg, function (json) {
//				
//				
//				
//				
//				if ( json != null)
//					ws.send(json);
//
//			});
//
//		});
//	});
//});

////////////////// 2 ///////////////////
//
//app.ws('/connect', function (ws, req) {
//	
////	databaseController.changeData({user_id: 3805}, function(cursor){
////		ws.on('message', function (msg) {
////			
////		});
////		if ( cursor.new_val.status == 4){
////			ws.send('111111111');	
////		}
////		
////	})
//	
//	ws.on('message', function (msg) {
//		
//			websocket_connect.get_msg( msg, function (json) {
//				
//				
//				
//				
//				if ( json != null)
//					ws.send(json);
//
//			});
//
//		});
//
//	});

/////////////////////  3  //////////////////////

	
var aWss = expressWs.getWss('/connect');


setInterval(function () {
  aWss.clients.forEach(function (client) {
	  
//    client.send('hello');
  });
}, 5000);


app.ws('/connect', function (ws, req) {
	
	
	var new_change = true;
	
	ws.onclose = function(event){
		
		console.log('connection is closed');

		var target = event.target;
		var events = target._events;
		
//		aWss.clients.forEach(function (client) {
//
//		    	client.send('hello');
//		  });

		events.message(JSON.stringify({
			token: 0
		}))
		
		ws.on('message', function (msg) {
			
		})
		
		
		
		
		
	}
	
		
	ws.on('message', function (msg) {

		msg_jsons = JSON.parse(msg);
		
		if ( msg_jsons.token == 0 ){
			
//			aWss.clients.forEach(function (client) {
////
//		  	});
		}
		else if ( msg_jsons.token != 0 ){
			
//			var test = aWss.options.server;
//			var handle = test._handle;
//			var owner = handle.owner;
//
//			var clients = aWss.clients;
//			var clients_websocket = clients;
			
			
//			aWss.clients.forEach(function (client) {
//
//			    	client.send('hello');
//			  });
			
			
			
			
			
			
			if (new_change === true ){
				new_change = false;
				var msg_json = JSON.parse(msg);
				var token = msg_json.token;
				command = msg_json;
				var cancel_job = false;
				var j = schedule.scheduleJob('*/5 * * * * *', function(){
					  
						if ( ws.readyState == 3 && cancel_job === false ){
							
							j.cancel();
							
							websocket_connect.connection_close ( msg_json, function(result){
								
							})
						}
				
				
					});
				
				
				
				
	//			var filter = { user_id: msg_json.uid};
				var filter = {};
				databaseController.changeData( filter , function(cursor){
					
				if ( cursor.new_val != null ){
					if ( cursor.new_val.token == token && cursor.new_val.user_id == msg_json.uid ){
						if(ws.readyState === ws.OPEN){

							websocket_connect.check_cursor(msg, cursor, function(json){

								ws.send(json);
							})
						}
						else {
//							websocket_connect.connection_close ( msg_json, function(result){
//							
//								
//							})
						}

					}
					else if ( cursor.new_val.status == 3 && cursor.new_val.user_id != msg_json.uid){
						if(ws.readyState === ws.OPEN){

							websocket_connect.get_game_list(msg, cursor, function(json){

								ws.send(json);
							})
						}	
					}

				}
					
					

				});
			}
			
			

				websocket_connect.get_msg( msg, function (json) {

					if ( json != null)
						ws.send(json);

				});

		}
		
		
	});
});

// var privateKey  = fs.readFileSync('privatekey.pem');
// var certificate = fs.readFileSync('certificate.pem');


// var options = {key: privateKey, cert: certificate};

// //var httpsServer = http.createServer(credentials, app).listen(443);

// //http.createServer(app).listen(80);
// http.createServer(options, app).listen(3001);

app.listen( port, hostname );