//npm ws
const WebSocket = require('ws');
const fs = require('fs');
const https = require('https');
//npm mysql
const mysql = require('mysql');
//npm crypto-js
var CryptoJS = require("crypto-js");
var AES = require("crypto-js/aes");
var SHA256 = require("crypto-js/sha256");
//md5 encryption
var md5 = require("md5");

//each round will wait 8 second for bet
const roundTime = 6000;
const roundTimee = 2000;

//global
var player = [], fakeNames = ['ebrahim', 'کرونا', 'ali', 'reza', 'ahmad', 'javad', 'perspolis', 'spin', 'احمد زوقی', 'iran', 'bet', 'elham', 'mahdi-1', 'raha', 'hamid', 'به امید تو خدا', 'اميرحافظ', 'این دست بردم!!!', 'اميررضا', 'arash', 'alipoor', 'hossein', 'kazim', 'saeid', 'artin', 'آتنا', 'آرتين', 'جنترمن دکتر', 'آرش', 'آرمان', 'karim', 'bagher', 'آرين', 'آوا', 'آيدا', 'آيلين', 'آيناز', 'باران', 'بنيامين', 'بهار', 'بهاره', 'بيتا', 'پارسا', 'پرهام', 'پريا', 'پريسا', 'پوريا', 'پويا', 'تينا', 'ثنا', 'جواد', 'حامد', 'حانيه', 'حديث', 'حديثه', 'حسام', 'حسن', 'حسين', 'حميدرضا', 'حنانه', 'دانيال', 'درسا', 'دينا', 'razeyh', 'رضا', 'رقيه', 'رها', 'ريحانه', 'زهرا', 'زهره', 'زينب', 'سارا', 'سارينا', 'sobhan', 'ستايش', 'sajad', 'سحر', 'سعيد', 'samira', 'somayeh', 'سوگند', 'سهيل', 'سينا', 'tehran', 'شقايق', 'شيدا', 'شيوا', 'صبا', 'طاها', 'عاطفه', 'abass', 'عرشيا', 'akbar', 'aboli', 'علي', 'علي اصغر', 'علي اكبر', 'عليرضا', 'غزل', 'فاطمه', 'فاطمه ', 'pooyan', 'فائزه', 'فرشته', 'كوثر', 'كيان', 'كيانا', 'كيميا', 'ليلا', 'ماني', 'ماهان', 'مائده', 'مبين', 'مبينا', 'متين', 'مجتبي', 'محدثه', 'محسن', 'محمد', 'محمدامين', 'محمدپارسا', 'محمدجواد', 'محمدحسين', 'محمدرضا', 'محمدطاها', 'محمدعلي', 'محمدمتين', 'محمدمهدي', 'محمدياسين', 'محيا', 'مرتضي', 'مرضيه', 'مريم', 'مصطفي', 'معصومه', 'مليكا', 'مهدي', 'مهديس', 'مهديه', 'مهران', 'مهسا', 'مهشيد', 'مهلا', 'ميلاد', 'مينا', 'نازنين', 'نازنين زهرا', 'ندا', 'نرجس', 'نرجس', 'نرگس', 'نسترن', 'نگار', 'نگين', 'نيايش', 'نيلوفر', 'نيما', 'هادي', 'هانيه', 'هستي', 'هليا', 'ياسمن', 'ياسمين', 'ياسين', 'يسنا', 'يگانه', 'يلدا', 'يوسف', 'يونس'];
for (let pi = 0; pi < fakeNames.length; pi++) player.push({uid: 0, username: fakeNames[pi], credit: 0, cash: 0});
var player_playing = [],
  winner = [],
  chat = [],
  history = [],
  connection,
  json,
  status = "waiting",
  timer = 0,
  rate = 1,
  key = "00000" + randomString(50),
  lastHash = randomString(64),
  timeStart,
  waitStart,
  crash_num,
  md5_code,
  cur_game_id;

//var cert = fs.readFileSync('cert.crt', 'utf8');
//var key = fs.readFileSync('key.key', 'utf8');


//database connection, will add on for other site


var db_go2bet = mysql.createPool({
  host: "localhost",
  user: "vip90_sgbs",
  password: "Kmcu80!9",
  database: "vip90_sgbs",
});

//var server = new https.createServer({

//});

//var server = new https.createServer();


//const WebSocketServer = require("ws").Server;
//const wss = new WebSocketServer({ port: 4000 });
var server = new https.createServer({});

//var server = new https.createServer();


const WebSocketServer = require("ws").Server;
const wss = new WebSocketServer({port: 4000});


var busttimeout, timeStarted;

wss.on('connection', (ws) => {

//wss.on('connection', (ws) => {

  //connection = ws.accept(null, ws.origin);

  //var info = JSON.stringify(ws);
  //console.log("client connected, with info: %s ", info);

  ws.on('message', function incoming(message) {

    request = JSON.parse(message);
    console.log(message);

    switch (request.command) {
      case "auth":
        var user = ws;
        user.uid = request.uid;
        user.from = request.from;

        authUser(request.uid, request.from, function (err, result) {

          user.admin = result.admin;
          delete result.admin;
          ws.send(JSON.stringify(result));

          user.username = result.name;
          user.cash = result.chips;
          user.credit = result.credit;


          /*
          for(key in user)
          {
            console.log("key: "+ key);
          }
          */

          player.push(user);

          console.log("players online: " + player.length);
        });

        break;
      case "cash_in":
        var time, now = (new Date)['getTime']();
        if (status == "waiting") {
          time = roundTime - (now - waitStart);
        } else {
          time = now - timeStart;
        }
        // goes to game_status, main game page
        var result = {
          command: "game_status",
          chats: chat,
          crashes: history,
          uid: request.uid,
          status: status,
          time: time,
          amount: crash_num,
          md5: md5_code,
        };

        ws.send(JSON.stringify(result));
        break;
      case "play":
        addPlayerToNextRound(request, ws);
        break;
      case "bust":
        let ad = 0;
        for (let i = 0; i < player.length; i++)
          if (player[i].uid == request.uid) {
            ad = player[i].admin;
            break;
          }
        if (ad && status == "started") {
          var ts = Date.now() - timeStarted;
          console.log(ts);
          bustGame(calculateCrashnum(ts), ts, 1);
        }
        break;
      case "cancel":
        removePlayerFromNextRound(ws);
        break;
      case "finish":
        var index = findPlayerPlaying(request.uid, request.from);
        playerCashout(index);
        break;
      case "chat":
        addtoChatHistory(ws);
        break;
      default:
    }

  });

  ws.on('close', function close() {


    var index = findUserWithSocket(ws);

    var dc_user = player[index];

    // console.log('user: disconnected');

    if (index > -1) {
      player.splice(index, 1);
    }
  });

});


/*

server variable and setting ends here

*/


/*

game functions here

*/
Idle();

function Idle() {
  md5_code = md5(lastHash);
  var query = "INSERT INTO crash ( md5 ) VALUES ('" + md5_code + "')";

  db_go2bet.getConnection(function (err, connection) {

    connection.query(query, function (error, row) {
      if (error) return;

      var query2 = "SELECT max(id) AS max_id FROM crash";

      connection.query(query2, function (err, id_row) {
        if (err) return;

        cur_game_id = id_row[0].max_id;

        console.log("the next game id is: " + cur_game_id);

        console.log("Waiting for players to bet");
        waitStart = (new Date)['getTime']();
        var player_size = player.length;

        var message = {
          command: "waiting",
          time: roundTime,
          hash: lastHash,
          md5: md5_code,
        };

        if (player_size > 0) {
          for (i = 0; i < player_size; i++) {
            if (player[i].uid)
              player[i].send(JSON.stringify(message));
          }
        }

        //reset everything for next round
        status = "waiting";
        let uindex, countFake = Math.floor(Math.random() * 68);
        for (i = 0; i < countFake; i++) {
          setTimeout(function () {
            uindex = Math.floor(Math.random() * 169);
            addPlayerToNextRound({
              uid: 0,
              cashout: Math.ceil(Math.random() * 800) + 115,
              amount: (Math.ceil(Math.random() * Math.pow(10, (uindex % 3) + 1)) + Math.ceil(Math.random() * 9 * 10)) * 1000
            }, 0, uindex + 1);
          }, Math.floor(Math.random() * 5700));

        }
        setTimeout(function () {

          startGame();

        }, roundTime);

      });
    });

    connection.release();//release the connection
  });
}

function startGame() {
  console.log("game start");
  //send a message to all player to notify them game has started
  winner = [];

  status = "started";

  var player_size = player.length;
  var message = {
    command: "started",
    players: player_playing,
    md5: md5_code,
  };

  var i;

  if (player_size > 0) {
    for (i = 0; i < player_size; i++) {
      if (player[i].uid)
        player[i].send(JSON.stringify(message));
    }
  }

  //game mechanices here
  rate = 1;
  //var timeout = generateTime();
  var result = generateResult();
  crash_num = result.crash;
  var hash = result.hash;
  console.log("this game should crash at: " + crash_num + ", with hash: " + hash);

  var timeout = calculateTimeout(crash_num);


  timeStart = (new Date)['getTime']();

  console.log("it will take: " + timeout + "s to crash");
  busttimeout = setTimeout(function () {
    bustGame(crash_num, timeout)
  }, timeout);

  timeStarted = Date.now();

  //set time out for each player cashout setting
  var playing_length = player_playing.length;
  if (playing_length > 0) {
    for (i = 0; i < playing_length; i++) {
      var this_player = player_playing[i];
      if ((this_player.cashout / 100) > crash_num) {
        continue;
      }
      var player_timeout = calculateTimeout(this_player.cashout / 100);

      autoCashout(i, player_timeout);
    }
  }

}

function bustGame(crash_num, timeout, manual) {
  if (manual) clearTimeout(busttimeout);
  console.log("busted manualy");
  console.log("busted! calculating result");
  player_playing = [];

  var player_size = player.length;
  var message = {
    command: "busted",
    amount: crash_num * 100,
    time: timeout,
    md5: md5_code,
    hash: lastHash,
  };

  if (player_size > 0) {
    for (i = 0; i < player_size; i++) {
      if (player[i].uid)
        player[i].send(JSON.stringify(message));
    }
  }

  status = "busted";

  var record = message;
  record.crash = crash_num * 100;

  history.push(record);
  if (history.length > 100) {
    history.shift();
  }

  //wait 3 second to goes to next phase
  setTimeout(function () {

    console.log("game ended, accept bets now");
    Idle();

  }, roundTimee);

  //var query = "UPDATE crash SET crash.busted ='" + crash_num + "', hash = '" + lastHash + "' WHERE id = (SELECT max(crashes.id) FROM crash as crashes)";
  var query = "UPDATE crash SET crash.busted ='" + crash_num + "', hash = '" + lastHash + "' ORDER BY id DESC LIMIT 1";

  db_go2bet.getConnection(function (err, connection) {

    connection.query(query, function (error, row) {
      if (error) return;

    });

    connection.release();//release the connection

  });
}


function addPlayerToNextRound(request, ws, fake) {

  if (!fake) fake = false;
  else fake--;
  var amount = request.amount;

  //add player to the list
  var temp_player, index, found_player;
  if (fake === false)
    index = findUserWithSocket(ws);
  else index = fake;
  if (index == -1) {
    return;
  }
  found_player = player[index];
  if (!found_player.from) found_player.from = 0;
  temp_player = {
    uid: found_player.uid,
    name: found_player.username,
    amount: request.amount,
    cashout: request.cashout,
    from: found_player.from,
    in: true,
  };
  console.log("got user id: " + found_player.uid);

  let addToPlaying = function (tmp_player) {
    player_playing.push(tmp_player);

    //send message back to all player
    var player_size = player.length;
    var message = tmp_player;
    message.command = "play";

    if (player_size > 0) {
      for (i = 0; i < player_size; i++) {
        if (player[i].uid)
          player[i].send(JSON.stringify(message));
      }
    }
  };

  //reject for double click or network delay
  if (fake === false) {
    var playing_check = findPlayerPlaying(temp_player.uid, temp_player.from);
    if (playing_check !== -1) {
      return;
    }

    //reject if the player does not have enough cash
    if ((found_player.cash - amount) < 0) {
      return;
    }

    var query = "UPDATE users SET cash = cash - " + amount + " WHERE id = " + temp_player.uid;

    var database = getDatabase(found_player.from);
    console.log("this player is from site: " + found_player.from);


    database.getConnection(function (err, connection) {

      connection.query(query, function (error, row) {
        if (error) return;

        addToPlaying(temp_player);

        player[index].cash = player[index].credit - amount;

        var result = {
          command: "chips",
          chips: player[index].cash,
        };

        player[index].send(JSON.stringify(result));


        //insert into bet record
        var bet_query = "INSERT INTO crash_bet ( user_id, amount, auto_cashout, crash_id ) VALUES ('" + temp_player.uid + "', '" + temp_player.amount + "', '" + (temp_player.cashout / 100) + "', '" + cur_game_id + "')";

        connection.query(bet_query, function (err, rows) {
          if (err) return;
          var currentdate = new Date();
          var datetime = currentdate.getUTCFullYear() + "-" + (currentdate.getUTCMonth() + 1) + "-" + currentdate.getUTCDate() + " " + currentdate.getUTCHours() + ":" + currentdate.getUTCMinutes() + ":" + currentdate.getUTCSeconds();
          var transid = cur_game_id + currentdate.getUTCFullYear() + (currentdate.getUTCMonth() + 1) + currentdate.getUTCDate() + currentdate.getUTCHours() + currentdate.getUTCMinutes() + currentdate.getUTCSeconds();
          var settle_query = "INSERT INTO transactions ( price, invoice_type, description, user_id, created_at, updated_at, trans_id, cash, status) VALUES ('" + temp_player.amount + "', '78', 'شروع بازی انفجار:" + cur_game_id + "','" + temp_player.uid + "', '" + datetime + "', '" + datetime + "','" + transid + "', '" + player[index].credit + "','1')";

          connection.query(settle_query, function (er, settle_row) {
            if (er) return;

            console.log("~Sezar ROBOT => inserted into transaction: " + settle_query);
          });
        });
        //insert into settle

      });

      connection.release();//release the connection

    });
  } else
    addToPlaying(temp_player);

}

function removePlayerFromNextRound(ws) {
  var index = findUserWithSocket(ws);
  if (index == -1) {
    return;
  }

  var user = player[index];
  var cache = [];
  cache = null;
  //console.log(JSON.stringify(user));

  var i = findPlayerPlaying(user.uid, user.from);
  if (i == -1) {
    return;
  }
  var cancel_user = player_playing[i];


  console.log('~Sezar ROBOT => user: %s cancelled bet', cancel_user);

  if (i > -1) {
    player_playing.splice(i, 1);
  }

  player[index].cash = player[index].cash + cancel_user.amount;

  //send back the user info to all player
  var message = cancel_user;
  message.command = "cancel";

  var player_size = player.length;

  if (player_size > 0) {
    for (var j = 0; j < player_size; j++) {
      if (player[j].uid)
        player[j].send(JSON.stringify(message));
    }
  }

  var database = getDatabase(user.from);

  var query = "UPDATE users SET cash = cash + " + cancel_user.amount + " WHERE id = " + user.uid;

  database.getConnection(function (err, connection) {

    connection.query(query, function (error, row) {
      if (error) return;
      player[index].cash = player[index].cash + cancel_user.amount;

      var result = {
        command: "chips",
        chips: player[index].cash,
      };

      player[index].send(JSON.stringify(result));

      //update bet record
      var bet_query = "UPDATE crash_bet SET won = " + cancel_user.amount + " WHERE user_id = " + cancel_user.uid + " AND crash_id = " + cur_game_id;

      connection.query(bet_query, function (err, rows) {
        if (err) return;
        //insert into settle
        var currentdate = new Date();
        var datetime = currentdate.getUTCFullYear() + "-" + (currentdate.getUTCMonth() + 1) + "-" + currentdate.getUTCDate() + " " + currentdate.getUTCHours() + ":" + currentdate.getUTCMinutes() + ":" + currentdate.getUTCSeconds();
        var transid = cur_game_id + currentdate.getUTCFullYear() + (currentdate.getUTCMonth() + 1) + currentdate.getUTCDate() + currentdate.getUTCHours() + currentdate.getUTCMinutes() + currentdate.getUTCSeconds();
        var settle_query = "INSERT INTO transactions ( price, invoice_type, description, user_id, created_at, updated_at, trans_id, cash, status) VALUES ('" + cancel_user.amount + "', '78', 'لغو شرط در بازی انفجار :" + cur_game_id + "','" + user.uid + "', '" + datetime + "', '" + datetime + "', '" + transid + "', '" + player[index].credit + "', '1')";

        connection.query(settle_query, function (er, settle_row) {
          if (er) return;

          console.log("~Sezar ROBOT => inserted into settle: " + settle_query);
        });
      });

    });



    connection.release();//release the connection

  });
}

function playerCashout(index) {
  var player_info = player_playing[index];
  if (typeof player_info == "undefined") {
    return;
  }
  console.log("this player has already cashed out, " + JSON.stringify(player_info));
  if (player_info.in != true) {
    return;
  }
  player_playing[index].in = false;

  var result = calculateWinning(player_info);

  var player_size = player.length;
  var message = player_info;
  message.command = "finish";
  message.current = result.cashout * 100;
  message.won = result.won;

  winner.push(message);

  console.log("player cash out : " + JSON.stringify(message));

  if (player_size > 0) {
    for (i = 0; i < player_size; i++) {
      if (player[i].uid)
        player[i].send(JSON.stringify(message));
    }
  }
  if (player_info.uid)
    giveCredit(message);

}


function autoCashout(index, timeout) {
  setTimeout(function () {
    playerCashout(index);
  }, timeout);
}

function calculateWinning(player_info) {
  console.log(JSON.stringify(player_info));
  var ts = (new Date)['getTime']();
  console.log("time stamp: " + ts + ", start at: " + timeStart);
  var rate = Math.pow(Math.E, 6e-5 * (ts - timeStart))['toFixed'](2);
  console.log("time difference is: " + (ts - timeStart) + ", rate: " + rate);
  var winning = rate * player_info.amount;

  return {cashout: rate, won: winning};
}

function giveCredit(player_info) {
  var uid = player_info.uid;
  //var bet = player_info.amount;
  var rate = player_info.current / 100;
  var won = player_info.won;
  var from = player_info.from;

  var query = "UPDATE users SET cash = cash + " + won + " WHERE id = " + uid;
  console.log("this player is from site: " + from);

  var database = getDatabase(from);

  database.getConnection(function (err, connection) {

    connection.query(query, function (error, row) {
      if (error) return;

      //user variable instead of request from database everytime
      var index = findUserWithID(uid, from);
      var socket = player[index];

      // player[index].cash = player[index].cash + won;
      var result = {
        command: "credit",
        credit: player[index].cash,
      };

      socket.send(JSON.stringify(result));

      //update bet record
      var bet_query = "UPDATE crash_bet SET cashout = '" + (player_info.cashout / 100) + "', won = '" + won + "' WHERE user_id = '" + uid + "' AND crash_id = '" + cur_game_id + "'";

      connection.query(bet_query, function (err, rows) {
        if (err) return;
        //insert into settle
        var currentdate = new Date();
        var datetime = currentdate.getUTCFullYear() + "-" + (currentdate.getUTCMonth() + 1) + "-" + currentdate.getUTCDate() + " " + currentdate.getUTCHours() + ":" + currentdate.getUTCMinutes() + ":" + currentdate.getUTCSeconds();
        var transid = cur_game_id + currentdate.getUTCFullYear() + (currentdate.getUTCMonth() + 1) + currentdate.getUTCDate() + currentdate.getUTCHours() + currentdate.getUTCMinutes() + currentdate.getUTCSeconds();
        var settle_query = "INSERT INTO transactions ( price, invoice_type, description, user_id, created_at, updated_at, trans_id, cash, status) VALUES ( '" + won + "','88', 'برد در بازی انفجار :" + cur_game_id + "','" + uid + "', '" + datetime + "', '" + datetime + "', '" + transid + "' , '" + player[index].credit + "', '1')";

        connection.query(settle_query, function (er, settle_row) {
          if (er) return;

          console.log("inserted into Transactions: " + settle_query);
        });


      });
    });

    connection.release();//release the connection

  });
}

/*

game mechanic here

*/
function generateTime() {
  //60% chance
  var dec_rate = Math.random() / Math.random();
  var inc_rate = 0.6;

  console.log("factor now is: " + factor * dec_rate * inc_rate);

  return factor * dec_rate * inc_rate;

}

/*

client connection functions here

*/
function findUserWithSocket(socket) {
  var length = player.length;
  var i;

  for (var i = 0; i < length; i++) {
    var sender = player[i]._sender;
    var dt = player[i].data;

    if (socket._sender == sender) {
      return i;
    }
  }

  return -1;
}

function findUserWithID(uid, from) {

  var length = player.length;
  var i;
  for (i = 0; i < length; i++) {
    if (player[i].uid == uid && player[i].from == from) {
      return i;
    }
  }

  return -1;
}

function findPlayerPlaying(id, from) {
  var length = player_playing.length, i;
  for (i = 0; i < length; i++) {
    if (player_playing[i].uid == id && player_playing[i].from == from) {
      return i;
    }
  }

  return -1;
}

function authUser(user_id, from, callback) {
  var query = "SELECT * FROM users WHERE id = " + user_id + " LIMIT 1";
  var database = getDatabase(from);
  console.log("user from: " + from + " request to play");
  console.log("query: %s", query);

  database.getConnection(function (err, connection) {

    connection.query(query, function (error, row) {
      if (error) return;
      connection.query("SELECT * FROM admin WHERE portalid = " + user_id + " LIMIT 1", function (err, res) {
        var result;

        if (row.length > 0) {
          console.log("we got user data: " + row[0]);

          result = {
            command: "auth",
            uid: row[0].id,
            name: row[0].first_name,
            chips: row[0].cash,
            credit: row[0].cash,
            currency: "Toman",
            default_amount: 1000,
            chats: chat,
            winners: winner,
            admin: res.length
          }

        } else {
          console.log("player info not found.");
          result = {
            command: "error",
            message: "error",
          };


        }

        callback(null, result);
      });
    });

    connection.release();//release the connection
  });
}

function addtoChatHistory(ws) {
  var index = findUserWithSocket(ws);
  var uid = player[index].uid;
  var name = player[index].username;
  var now = new Date();
  var hr = now.getHours();
  var min = now.getMinutes();
  var sec = now.getSeconds();
  var time_str = hr + ":" + min + ":" + sec;
  var msg = {
    text: request.text,
    uid: uid,
    name: name,
    time: time_str,
  };
  // var chats_query = "INSERT INTO chats ( user_id, text, date) VALUES ( '" + uid + "', '" + datetime + "', '" + datetime + "')";

  //all chatting record is stored in user side
  chat.push(msg);

  if (chat.length > 100) {
    chat.shift();
  }

  var result = msg;
  result.command = "chat";

  var player_size = player.length;

  if (player_size > 0) {
    for (i = 0; i < player_size; i++) {
      if (player[i].uid)
        player[i].send(JSON.stringify(result));
    }
  }
}

function randomString(length) {
  var chars = '0123456789abcdefghiklmnopqrstuvwxyz'.split('');

  var str = '';
  for (var i = 0; i < length; i++) {
    str += chars[Math.floor(Math.random() * chars.length)];
  }
  return str;
}

/*

mechanic modify from https://jsfiddle.net/aquiffer/g1fexp4q/

	all keys are generated with our own method

*/
function generateResult() {

  var gameHash = (lastHash != "" ? genGameHash(lastHash) : hash);
  var gameCrash = crashPointFromHash((lastHash != "" ? genGameHash(lastHash) : hash));

  lastHash = gameHash;

  return {hash: gameHash, crash: gameCrash};
}


function divisible(hash, mod) {
  // So ABCDEFGHIJ should be chunked like  AB CDEF GHIJ
  var val = 0;

  var o = hash.length % 4;
  for (var i = o > 0 ? o - 4 : 0; i < hash.length; i += 4) {
    val = ((val << 16) + parseInt(hash.substring(i, i + 4), 16)) % mod;
  }

//if it fails, baseBet increases on the next loop, if it works, bet stays the same.
  return val === 0;
}

function genGameHash(serverSeed) {
  console.log("server seed: " + serverSeed);
  return SHA256(serverSeed).toString();
}


function hmac(key, v) {
  var hmacHasher = CryptoJS.HmacSHA1(CryptoJS.algo.SHA256, key);
  //return hmacHasher.finalize(v).toString();
  return hmacHasher.toString();
}


function crashPointFromHash(serverSeed) {
  // The client seed is a sha256 hash of "CSGOCrash"
  var hash = hmac(serverSeed, key);
  // In 1 of 51 games the game crashes instantly.
  if (divisible(hash, 51))
    return 1;

  // Use the most significant 52-bit from the hash to calculate the crash point
  var h = parseInt(hash.slice(0, 52 / 4), 16);
  var e = Math.pow(2, 52);

  return (Math.floor((100 * e - h) / (e - h)) / 100).toFixed(2);
}

function calculateTimeout(crash_num) {
  var time = (Math.log(crash_num) / Math.log(Math.E)) / 6e-5;
  return time;
}

function calculateCrashnum(time) {
  var crash_num = Math.round(Math.exp(time * 6e-5 * Math.log(Math.E)) * 100) / 100;
  return crash_num;
}

function getDatabase(from) {
  switch (from) {

    case "go2bet":
      return db_go2bet;
      break;
    default:
      return db_go2bet;
  }
}