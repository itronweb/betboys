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
var player = [{uid: 0, username: "ابراهيم", credit: 0, cash: 0}, {uid: 0, username: "ابوالفضل", credit: 0, cash: 0}, {
    uid: 0,
    username: "احسان",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "احمد", credit: 0, cash: 0}, {uid: 0, username: "احمدرضا", credit: 0, cash: 0}, {uid: 0, username: "اسرا", credit: 0, cash: 0}, {
    uid: 0,
    username: "اسما",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "اسماء", credit: 0, cash: 0}, {uid: 0, username: "اشكان", credit: 0, cash: 0}, {uid: 0, username: "النا", credit: 0, cash: 0}, {
    uid: 0,
    username: "الناز",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "الهام", credit: 0, cash: 0}, {uid: 0, username: "الهه", credit: 0, cash: 0}, {uid: 0, username: "الينا", credit: 0, cash: 0}, {
    uid: 0,
    username: "اميد",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "امير", credit: 0, cash: 0}, {uid: 0, username: "اميرحافظ", credit: 0, cash: 0}, {
    uid: 0,
    username: "اميرحسين",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "اميررضا", credit: 0, cash: 0}, {uid: 0, username: "اميرعباس", credit: 0, cash: 0}, {
    uid: 0,
    username: "اميرعلي",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "اميرمحمد", credit: 0, cash: 0}, {uid: 0, username: "اميرمهدي", credit: 0, cash: 0}, {
    uid: 0,
    username: "امين",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "ايليا", credit: 0, cash: 0}, {uid: 0, username: "آتنا", credit: 0, cash: 0}, {uid: 0, username: "آرتين", credit: 0, cash: 0}, {
    uid: 0,
    username: "آرزو",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "آرش", credit: 0, cash: 0}, {uid: 0, username: "آرمان", credit: 0, cash: 0}, {uid: 0, username: "آرمين", credit: 0, cash: 0}, {
    uid: 0,
    username: "آريا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "آرين", credit: 0, cash: 0}, {uid: 0, username: "آوا", credit: 0, cash: 0}, {uid: 0, username: "آيدا", credit: 0, cash: 0}, {
    uid: 0,
    username: "آيلين",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "آيناز", credit: 0, cash: 0}, {uid: 0, username: "باران", credit: 0, cash: 0}, {uid: 0, username: "بنيامين", credit: 0, cash: 0}, {
    uid: 0,
    username: "بهار",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "بهاره", credit: 0, cash: 0}, {uid: 0, username: "بيتا", credit: 0, cash: 0}, {uid: 0, username: "پارسا", credit: 0, cash: 0}, {
    uid: 0,
    username: "پرهام",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "پريا", credit: 0, cash: 0}, {uid: 0, username: "پريسا", credit: 0, cash: 0}, {uid: 0, username: "پوريا", credit: 0, cash: 0}, {
    uid: 0,
    username: "پويا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "تينا", credit: 0, cash: 0}, {uid: 0, username: "ثنا", credit: 0, cash: 0}, {uid: 0, username: "جواد", credit: 0, cash: 0}, {
    uid: 0,
    username: "حامد",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "حانيه", credit: 0, cash: 0}, {uid: 0, username: "حديث", credit: 0, cash: 0}, {uid: 0, username: "حديثه", credit: 0, cash: 0}, {
    uid: 0,
    username: "حسام",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "حسن", credit: 0, cash: 0}, {uid: 0, username: "حسين", credit: 0, cash: 0}, {uid: 0, username: "حميدرضا", credit: 0, cash: 0}, {
    uid: 0,
    username: "حنانه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "دانيال", credit: 0, cash: 0}, {uid: 0, username: "درسا", credit: 0, cash: 0}, {uid: 0, username: "دينا", credit: 0, cash: 0}, {
    uid: 0,
    username: "راضيه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "رضا", credit: 0, cash: 0}, {uid: 0, username: "رقيه", credit: 0, cash: 0}, {uid: 0, username: "رها", credit: 0, cash: 0}, {
    uid: 0,
    username: "ريحانه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "زهرا", credit: 0, cash: 0}, {uid: 0, username: "زهره", credit: 0, cash: 0}, {uid: 0, username: "زينب", credit: 0, cash: 0}, {
    uid: 0,
    username: "سارا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "سارينا", credit: 0, cash: 0}, {uid: 0, username: "سبحان", credit: 0, cash: 0}, {uid: 0, username: "ستايش", credit: 0, cash: 0}, {
    uid: 0,
    username: "سجاد",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "سحر", credit: 0, cash: 0}, {uid: 0, username: "سعيد", credit: 0, cash: 0}, {uid: 0, username: "سميرا", credit: 0, cash: 0}, {
    uid: 0,
    username: "سميه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "سوگند", credit: 0, cash: 0}, {uid: 0, username: "سهيل", credit: 0, cash: 0}, {uid: 0, username: "سينا", credit: 0, cash: 0}, {
    uid: 0,
    username: "شايان",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "شقايق", credit: 0, cash: 0}, {uid: 0, username: "شيدا", credit: 0, cash: 0}, {uid: 0, username: "شيوا", credit: 0, cash: 0}, {
    uid: 0,
    username: "صبا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "طاها", credit: 0, cash: 0}, {uid: 0, username: "عاطفه", credit: 0, cash: 0}, {uid: 0, username: "عباس", credit: 0, cash: 0}, {
    uid: 0,
    username: "عرشيا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "عرفان", credit: 0, cash: 0}, {uid: 0, username: "عسل", credit: 0, cash: 0}, {uid: 0, username: "علي", credit: 0, cash: 0}, {
    uid: 0,
    username: "علي اصغر",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "علي اكبر", credit: 0, cash: 0}, {uid: 0, username: "عليرضا", credit: 0, cash: 0}, {uid: 0, username: "غزل", credit: 0, cash: 0}, {
    uid: 0,
    username: "فاطمه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "فاطمه زهرا", credit: 0, cash: 0}, {uid: 0, username: "فاطيما", credit: 0, cash: 0}, {
    uid: 0,
    username: "فائزه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "فرشته", credit: 0, cash: 0}, {uid: 0, username: "كوثر", credit: 0, cash: 0}, {uid: 0, username: "كيان", credit: 0, cash: 0}, {
    uid: 0,
    username: "كيانا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "كيميا", credit: 0, cash: 0}, {uid: 0, username: "ليلا", credit: 0, cash: 0}, {uid: 0, username: "ماني", credit: 0, cash: 0}, {
    uid: 0,
    username: "ماهان",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "مائده", credit: 0, cash: 0}, {uid: 0, username: "مبين", credit: 0, cash: 0}, {uid: 0, username: "مبينا", credit: 0, cash: 0}, {
    uid: 0,
    username: "متين",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "مجتبي", credit: 0, cash: 0}, {uid: 0, username: "محدثه", credit: 0, cash: 0}, {uid: 0, username: "محسن", credit: 0, cash: 0}, {
    uid: 0,
    username: "محمد",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "محمدامين", credit: 0, cash: 0}, {uid: 0, username: "محمدپارسا", credit: 0, cash: 0}, {
    uid: 0,
    username: "محمدجواد",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "محمدحسين", credit: 0, cash: 0}, {uid: 0, username: "محمدرضا", credit: 0, cash: 0}, {
    uid: 0,
    username: "محمدطاها",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "محمدعلي", credit: 0, cash: 0}, {uid: 0, username: "محمدمتين", credit: 0, cash: 0}, {
    uid: 0,
    username: "محمدمهدي",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "محمدياسين", credit: 0, cash: 0}, {uid: 0, username: "محيا", credit: 0, cash: 0}, {
    uid: 0,
    username: "مرتضي",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "مرضيه", credit: 0, cash: 0}, {uid: 0, username: "مريم", credit: 0, cash: 0}, {uid: 0, username: "مصطفي", credit: 0, cash: 0}, {
    uid: 0,
    username: "معصومه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "مليكا", credit: 0, cash: 0}, {uid: 0, username: "مهدي", credit: 0, cash: 0}, {uid: 0, username: "مهديس", credit: 0, cash: 0}, {
    uid: 0,
    username: "مهديه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "مهران", credit: 0, cash: 0}, {uid: 0, username: "مهسا", credit: 0, cash: 0}, {uid: 0, username: "مهشيد", credit: 0, cash: 0}, {
    uid: 0,
    username: "مهلا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "ميلاد", credit: 0, cash: 0}, {uid: 0, username: "مينا", credit: 0, cash: 0}, {uid: 0, username: "نازنين", credit: 0, cash: 0}, {
    uid: 0,
    username: "نازنين زهرا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "ندا", credit: 0, cash: 0}, {uid: 0, username: "نرجس", credit: 0, cash: 0}, {uid: 0, username: "نرجس", credit: 0, cash: 0}, {
    uid: 0,
    username: "نرگس",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "نسترن", credit: 0, cash: 0}, {uid: 0, username: "نگار", credit: 0, cash: 0}, {uid: 0, username: "نگين", credit: 0, cash: 0}, {
    uid: 0,
    username: "نيايش",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "نيلوفر", credit: 0, cash: 0}, {uid: 0, username: "نيما", credit: 0, cash: 0}, {uid: 0, username: "هادي", credit: 0, cash: 0}, {
    uid: 0,
    username: "هانيه",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "هستي", credit: 0, cash: 0}, {uid: 0, username: "هليا", credit: 0, cash: 0}, {uid: 0, username: "ياسمن", credit: 0, cash: 0}, {
    uid: 0,
    username: "ياسمين",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "ياسين", credit: 0, cash: 0}, {uid: 0, username: "يسنا", credit: 0, cash: 0}, {uid: 0, username: "يگانه", credit: 0, cash: 0}, {
    uid: 0,
    username: "يلدا",
    credit: 0,
    cash: 0
  }, {uid: 0, username: "يوسف", credit: 0, cash: 0}, {uid: 0, username: "يونس", credit: 0, cash: 0}],
  player_playing = [],
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
  user: "vip90_sgbd",
  password: "Kmcu80!9",
  database: "vip90_sgbs",
});

//var server = new https.createServer({

//});

//var server = new https.createServer();


const WebSocketServer = require("ws").Server;
const wss = new WebSocketServer({ port: 4000 });
var server = new https.createServer({});

//var server = new https.createServer();


//const WebSocketServer = require("ws").Server;
//const wss = new WebSocketServer({port: 1002});


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
      if (error) throw error;

      var query2 = "SELECT max(id) AS max_id FROM crash";

      connection.query(query2, function (err, id_row) {
        if (err) throw err;

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
        let uindex, countFake = Math.floor(Math.random() * 15);
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
      if (error) throw error;

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
        if (error) throw error;

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
          if (err) throw err;
        });
        //insert into settle
        var currentdate = new Date();
        var datetime = currentdate.getUTCFullYear() + "-" + (currentdate.getUTCMonth() + 1) + "-" + currentdate.getUTCDate() + " " + currentdate.getUTCHours() + ":" + currentdate.getUTCMinutes() + ":" + currentdate.getUTCSeconds();
        var transid = cur_game_id + currentdate.getUTCFullYear() + (currentdate.getUTCMonth() + 1) + currentdate.getUTCDate() + currentdate.getUTCHours() + currentdate.getUTCMinutes() + currentdate.getUTCSeconds();
        var settle_query = "INSERT INTO transactions ( price, invoice_type, description, user_id, created_at, updated_at, trans_id, cash, status) VALUES ('" + temp_player.amount + "', '78', 'شروع بازی انفجار:" + cur_game_id + "','" + temp_player.uid + "', '" + datetime + "', '" + datetime + "','" + transid + "', '" + player[index].credit + "','1')";

        connection.query(settle_query, function (er, settle_row) {
          if (er) throw er;

          console.log("~Sezar ROBOT => inserted into transaction: " + settle_query);
        });

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
  console.log(JSON.stringify(user));

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
      if (error) throw er;

    });

    player[index].cash = player[index].cash + cancel_user.amount;

    var result = {
      command: "chips",
      chips: player[index].cash,
    };

    player[index].send(JSON.stringify(result));

    //update bet record
    var bet_query = "UPDATE crash_bet SET won = " + cancel_user.amount + " WHERE user_id = " + cancel_user.uid + " AND crash_id = " + cur_game_id;

    connection.query(bet_query, function (err, rows) {
      if (err) throw err;
    });

    //insert into settle
    var currentdate = new Date();
    var datetime = currentdate.getUTCFullYear() + "-" + (currentdate.getUTCMonth() + 1) + "-" + currentdate.getUTCDate() + " " + currentdate.getUTCHours() + ":" + currentdate.getUTCMinutes() + ":" + currentdate.getUTCSeconds();
    var transid = cur_game_id + currentdate.getUTCFullYear() + (currentdate.getUTCMonth() + 1) + currentdate.getUTCDate() + currentdate.getUTCHours() + currentdate.getUTCMinutes() + currentdate.getUTCSeconds();
    var settle_query = "INSERT INTO transactions ( price, invoice_type, description, user_id, created_at, updated_at, trans_id, cash, status) VALUES ('" + cancel_user.amount + "', '78', 'لغو شرط در بازی انفجار :" + cur_game_id + "','" + user.uid + "', '" + datetime + "', '" + datetime + "', '" + transid + "', '" + player[index].credit + "', '1')";

    connection.query(settle_query, function (er, settle_row) {
      if (er) throw er;

      console.log("~Sezar ROBOT => inserted into settle: " + settle_query);
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
      if (error) throw error;

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
        if (err) throw err;


      });
      //insert into settle
      var currentdate = new Date();
      var datetime = currentdate.getUTCFullYear() + "-" + (currentdate.getUTCMonth() + 1) + "-" + currentdate.getUTCDate() + " " + currentdate.getUTCHours() + ":" + currentdate.getUTCMinutes() + ":" + currentdate.getUTCSeconds();
      var transid = cur_game_id + currentdate.getUTCFullYear() + (currentdate.getUTCMonth() + 1) + currentdate.getUTCDate() + currentdate.getUTCHours() + currentdate.getUTCMinutes() + currentdate.getUTCSeconds();
      var settle_query = "INSERT INTO transactions ( price, invoice_type, description, user_id, created_at, updated_at, trans_id, cash, status) VALUES ( '" + won + "','88', 'برد در بازی انفجار :" + cur_game_id + "','" + uid + "', '" + datetime + "', '" + datetime + "', '" + transid + "' , '" + player[index].credit + "', '1')";

      connection.query(settle_query, function (er, settle_row) {
        if (er) throw er;

        console.log("inserted into Transactions: " + settle_query);
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
      if (error) throw error;
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
            admin: 0
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