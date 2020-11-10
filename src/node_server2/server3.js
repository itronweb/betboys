var http            = require('http');
var WebSocketServer = require('websocket').server;
var fs              = require('fs');
var util            = require('util');
const request       = require("request");
var urljoin         = require('url-join');

var querystring		= require('querystring');


// Defines
const hostname      = '136.243.255.205';        //tel:@pccon
const port          = 3000;
const baseUrl       = 'betboys.xyz';


var server = http.createServer(function(req, res) {
  responseto = res;
  function ApiRequest0(d,u){
    var req;
    var options = {
      hostname: baseUrl,
      port: 80,
      path: u,
      method: 'GET',
      headers: {
          'Content-Type': 'application/json',
      }
    };
	
     var req = http.request(options, function(res) {
      var ResponseBody = "";
      util.log('Status: ' + res.statusCode);
      res.setEncoding('utf8');
      res.on('data', function (chunk) {
        ResponseBody += chunk;
      });
  
      res.on('end', function(){
        responseto.end(ResponseBody); 
      });
    });
  
    req.on('error', function (e) {
      util.log('problem with request: ' + e.message);
    });
    req.write(d);
    req.end();
  };
  try { 
    if(req.method == 'GET')  throw "request method not allowed";
    if(req.url == '/')   throw "bad request";
    if (req.method == 'POST' ) {

      if (req.url === '/api/user/Newgame/language') {
  
        res.setHeader('Access-Control-Allow-Origin', '*');
        res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
        var body = '';
        req.on('data', function (data) {
            body += data;
        });
        req.on('end', function () {
        util.log(`* request to => ${req.url} Body => ${body} *`);
          res.writeHead(200, {'Content-Type': 'application/json'});
  
          ApiRequest0(JSON.stringify(body),`/Newgame/api/RestApi/language0.php?${body}`);   
  
  
  
      //
        });
      
      } else if(req.url === '/api/user/Newgame/auth'){
        res.setHeader('Access-Control-Allow-Origin', '*');
        res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
        var body = '';
        req.on('data', function (data) {
            body += data;
        });
        req.on('end', function () {
        util.log(body);
          util.log(`* request to => ${req.url} Body => ${body} *`);
          res.writeHead(200, {'Content-Type': 'application/json'});
          ApiRequest0(JSON.stringify(body),`/Newgame/api/RestApi/auth0.php?${body}`);    
        });
  
      }
  
    }
  }
  catch(err) {
    responseto.writeHead(200, {'Content-Type': 'application/json'});
    responseto.end(err);
  util.log(`ERROR: ${err} FROM IP: ${req.connection.remoteAddress}`);
  }

 // fs.readFile(__dirname + '/client.html', function(err, data) {
 //   if (err) {
 //     console.log(err);
 //     res.writeHead(500);
 //     return res.end('Error loading client.html');
 //   }
 //   res.writeHead(200);
 //   res.end(data);
 // });
});
server.listen(port, hostname, () => {
  util.log(`Server listening on http://${hostname}:${port}/\n`);
});


wsServer = new WebSocketServer({
  httpServer: server,

  autoAcceptConnections: false
});

function originIsAllowed(origin) {
return true;
}

wsServer.on('request', function(request) {

  if (!originIsAllowed(request.origin)) {
    request.reject();
    util.log(` Connection from ${origin}  ${request.origin}  rejected.`);
  }
  

  var connection = request.accept('onopen', request.origin);

  function ApiRequest(d,u){
    var req;
    var options = {
      hostname: baseUrl,
      port: 80,
      path: u,
      method: 'GET',
      headers: {
          'Content-Type': 'application/json',
      }
    };
     var req = http.request(options, function(res) {
      var ResponseBody = "";
  
      util.log('Status: ' + res.statusCode);
      res.setEncoding('utf8');
      res.on('data', function (chunk) {
        ResponseBody += chunk;
      });
  
      res.on('end', function(){
        connection.sendUTF(ResponseBody); 
      });
    });
  
    req.on('error', function (e) {
      util.log('problem with request: ' + e.message);
    });
    req.write(d);
    req.end();
    util.log(`Get Response from ${baseUrl+u}`);
  };

  connection.on('message', function(message) {
     
      if (message.type === 'utf8') {
          util.log(`\n**Request => ${request.resourceURL.path}\nWebSocket Receive => ${message.utf8Data}`);
          ReciveData = JSON.parse(message.utf8Data);
          	var bodyObj = ReciveData;
		 
		  	var ReqParam = querystring.stringify({'data':JSON.stringify(bodyObj)});
		  
//          	var ReqParam = "";
//          	var objlength = Object.keys(bodyObj).length-1;
//          	var arrayo = Object.keys(bodyObj);
//          	
//          	for (var i = 0; i <= objlength; i++) {
//          		var _and = (objlength === i) ? '' : '&';
//          		ReqParam += arrayo[i]+'='+bodyObj[arrayo[i]]+_and;
//            }
            
            
        if(request.resourceURL.path === '/connect/'){
			try{
//              if(ReciveData.token !== null) throw "No authorization"; 
              if(ReciveData.command == 'auth') {
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/auth.php?${ReqParam}`);
              }else if(ReciveData.command == 'cash_in'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/cash_in.php?${ReqParam}`);
              }else if(ReciveData.command == 'cash_out'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/cash_out.php?${ReqParam}`);
              }else if(ReciveData.command == 'spin'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/spin.php?${ReqParam}`);
              }else if(ReciveData.command == 'deal'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/deal.php?${ReqParam}`);
              }else if(ReciveData.command == 'hit'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/hit.php?${ReqParam}`);
              }else if(ReciveData.command == 'stand'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/stand.php?${ReqParam}`);
              }else if(ReciveData.command == 'cancel_game'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/cancel_game.php?${ReqParam}`);
              }else if(ReciveData.command == 'games_list'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/games_list.php?${ReqParam}`);
              }else if(ReciveData.command == 'friends'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/friends.php?${ReqParam}`);
              }else if(ReciveData.command == 'enter_game'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/enter_game.php?${ReqParam}`);
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/rps_waiting.php?${ReqParam}`);
              }else if(ReciveData.command == 'play'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/play.php?${ReqParam}`);
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/rps_waiting.php?${ReqParam}`);
              }else if(ReciveData.command == 'drawn'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/drawn.php?${ReqParam}`);
              }else if(ReciveData.command == 'double_offer'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/double_offer.php?${ReqParam}`);
              }else if(ReciveData.command == 'double_accept'){
                ApiRequest(JSON.stringify(ReciveData),`/Newgame/api/RestApi/double_accept.php?${ReqParam}`);
              }
            }catch(err){
              connection.sendUTF(`ERROR: ${err}`);
              util.log(`ERROR: ${err}`);
            }
          
       }
      }
  });

 

  connection.on('close', function(reasonCode, description) {
      console.log(` Peer ${connection.remoteAddress} disconnected.`);
  });

});

