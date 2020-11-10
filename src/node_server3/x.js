const WebSocket = require('ws');

const WebSocketServer = require("ws").Server;
const wss = new WebSocketServer({ port: 3000 });
var ws = new WebSocket('ws://136.243.255.205:3000/');
ws.on('open', function() {
    ws.send(JSON.stringify({
          jsonrpc: '2.0',
          id: 1,
          method: 'sum',
          params: [2, 1]
    }));
 
    ws.send(JSON.stringify({
      jsonrpc: '2.0',
      method: 'rpc.on',
      params: ['testEvent']
    }));
});