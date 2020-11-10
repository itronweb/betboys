/**
 * Created by Muhammad-ASUS on 8/10/2018.
 */



var mysql = require('mysql'); 




var db_config = {
    host: "localhost",
    user: "vip90_sgbs", 
    password: "Kmcu80!9", 
    database: "vip90_sgbs",
    // connectionLimit : 60,
    // multipleStatements: true
};

var con;

function handleDisconnect() {
  con = mysql.createConnection(db_config); // Recreate the connection, since
                                                  // the old one cannot be reused.

  con.connect(function(err) {              // The server is either down
    if(err) {                                     // or restarting (takes a while sometimes).
      console.log('error when connecting to db:', err);
      setTimeout(handleDisconnect, 2000); // We introduce a delay before attempting to reconnect,
    }                                     // to avoid a hot loop, and to allow our node script to
  });                                     // process asynchronous requests in the meantime.
                                          // If you're also serving http, display a 503 error.
  con.on('error', function(err) {
    console.log('db error', err);
    if(err.code === 'PROTOCOL_CONNECTION_LOST') { // Connection to the MySQL server is usually
      handleDisconnect();                         // lost due to either server restart, or a
    } else {                                      // connnection idle timeout (the wait_timeout
      throw err;                                  // server variable configures this)
    }
  });
}

handleDisconnect();


function select_1 ( query, arr1, callback ){
    con.query(query, [arr1], function (err, result) {
        if (err) throw err;

        callback( result );
    });
}


function select_2 ( query, arr1, arr2, callback ){

    con.query(query, [arr1, arr2], function (err, result) {
        if (err) throw err;

        callback( result );

    });




}


function update( query, arr1, arr2, callback){
	
	con.query(query, [arr1, arr2, '1'], function (err, result) {
        if (err) throw err;

        callback( result );
    });
	
}

function insert( query, values, callback){

	con.query(query, [values], function (err, result) {
        if (err) throw err;

        callback( result );
    });
	
}


module.exports = {
    select_1: select_1,
    select_2: select_2,
    update: update,
    insert: insert,
};
