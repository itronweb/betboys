///**
// * Created by Muhammad-ASUS on 8/10/2018.
// */
//
//var mongoose = require('mongoose');
//
//mongoose.connect('mongodb://localhost/4ubets');
//
//var db = mongoose.connection;
//
//db.on('error', console.error.bind( console, 'connection error'));
//db.once( 'open', function () {
//   console.log('mongodb is connected');
//	
//	const taskCollection = db.collection('online_gamer');
//      const changeStream = taskCollection.watch();
//
//      changeStream.on('change', (change) => {
//
//      });
//	
//});
//
////const taskCollection = db.collection('online_gamer');
////const changeStream = taskCollection.watch();
//
////changeStream.on('change', function(){
////	
////	console.log('111111111111111')
////});
//
//var online_gamer = mongoose.Schema({
//    game_id:    {type: Number},
//    user_id:    {type: Number, required: true},
//    op_user_id: {type: Number},
//    game_data:  {type: String},
//    amount:     {type: Number, defualt: 0},
//    token:      {type: String, required: true},
//    time_start: {type: Date, defualt: Date.now},
//    status:     {type: Number, defualt: 0},
//
//});
//
//var online_gamer_model = mongoose.model('online_gamer', online_gamer, 'online_gamer');
//
//function insert_online_gamer (insert_data, callback){
//
//    var test = new online_gamer_model(insert_data);
//
//    test.save(function (err) {
//        if (err) return handleError(err);
//		
//    });
//}
//
//function update_online_gamer( insert_data,result, callback){
//
//    result.update(insert_data, function (err) {
//        if (err) return handleError(err);
//		
//    });
//
//}
//
//function find_online_gamer ( insert_data, callback){
//
//    var search = {
//        user_id: insert_data.user_id,
//        game_id: insert_data.game_id,
////        status: 2,
//    }
//
//    online_gamer_model.findOne( search, function (err, result) {
//        if (err) return handleError(err);
//
//        if ( result == null){
//            console.log('insert');
//            insert_online_gamer(insert_data);
//        }
//        else{
//            console.log('update');
//            update_online_gamer( insert_data, result);
//        }
//        
//        callback(search);
//
//
//    });
//
//}
//
//function find ( search, callback){
//	
//	online_gamer_model.findOne( search, function (err, result) {
//        if (err) return handleError(err);
//
//		callback(result);
//        
//    });
//
//}
//
//function find_multi ( search, callback){
//	
//	online_gamer_model.find( search, function (err, result) {
//        if (err) return handleError(err);
//
//		callback(result);
//        
//    });
//
//}
//
//function find_and_update ( field, data, callback ){
//	online_gamer_model.findOneAndUpdate( field, {$set:data},  function(err, result){
//		if(err){
//			return handleError(err);
//		}
//
//		
//	   	callback( result );
//	});
//}
//
//
//function change_stream ( data, callback ){
////	 online_gamer_model.watch(pipeline).on('change', data => 
////    {
////        console.log('change_data');
////    });
//	console.log('2222222222');
//}
//
////function auth_is_exist ( insert_data, callback){
////	
////	var search = {
////		user_id: insert_data.uid,
////		token 	: insert_data.token,
////		game_id	: insert_data.game_id, 
////	}
////	
////	online_gamer_model.findOne( search, function( err, result){
////		if (err) return handleError(err);
////		
////		if ( result == null )
////			callback('false');
////		else
////			callback(result);
////	});
////}
//
//
//module.exports = {
//    insert_online_gamer : insert_online_gamer,
//    find_online_gamer   : find_online_gamer,
//    update_online_gamer : update_online_gamer,
////    auth_is_exist 		: auth_is_exist,
//    find 				: find,
//    find_and_update 	: find_and_update,
//    find_multi 	: find_multi,
//    change_stream 	: change_stream,
//};
//
