// JavaScript Document


var express     = require('express');
var bodyParser  = require('body-parser');
var randomstring = require("randomstring");

var game = require('../game.js');


function calculate_move ( data, callback ){
	
	
	var game_data 	= data.game_data,
		dic_1		= game_data.dic.dic_1,
		dic_2		= game_data.dic.dic_2,
		places		= game_data.places;
	
	
//	dic_1 = 5;
//	dic_2 = 3;
	convert_place_to_array( places, function(place_array){
		
		var place = place_array.place;
		var place_color = place_array.place_color;
		var place_value = place_array.place_value;
		
		if ( game_data.time == 'WHITE'){
			var search = 'W';
		}
		else if ( game_data.time == 'BLACK'){
			var search = 'B';
		}
		
//		dic_1 = dic_2 = 4;
		
		if ( dic_1 == dic_2 ){
			var moves = 4;
		}
		else if ( dic_1 != dic_2 ){
			var moves = 2;
		}
			
		
//		if ( dic_1 != dic_2 ){
			if ( ! check_has_remove_key( place_color, place_value, search) ){

				var white_place = find_color( place_color, search);

	//			var choice = create_all_moves ( white_place, dic_1, dic_2 );

				var choice = create_all_moves ( white_place, place_value, dic_1, dic_2, search, moves );

				callback( control_can_moves( choice, place_color, place_value, search, moves) );

			}
			else {
				var white_place = find_color( place_color, search);

	//			var choice = create_all_moves ( white_place, dic_1, dic_2 );
	//			var choice = create_all_moves ( white_place, dic_1, dic_2, search );

				var choice = create_all_moves_with_remove_key ( white_place, place_value, dic_1, dic_2, search, moves );
				

				callback( control_can_moves( choice, place_color, place_value, search, moves) );
			}
//		}
//		else if ( dic_1 == dic_2 ){
//			if ( ! check_has_remove_key( place_color, place_value, search) ){
//				
//				var white_place = find_color( place_color, search);
//				
//				var choice = create_all_moves ( white_place, dic_1, dic_2, search );
//			}
//		}
//		
		

		
		
	});
	
	
	
}

function convert_place_to_array ( places, callback){
	
	var place_array = places.split(',');
	
	var place = [];
	var place_color = [];
	var place_value = [];
	for( var i=0; i< place_array.length; i++ ){
		
		var this_place = place_array[i].split('.');
		
		place_color[this_place[0]] = this_place[1];
		place_value[this_place[0]] = this_place[2];
		
		place[this_place[0]] = this_place[1] + this_place[2];
		
	}
	
	callback( {place: place, place_color: place_color, place_value: place_value} );
	
}

function convert_array_to_place ( color, value){
	
	
	var str = '';
	
	color.forEach( function(item, key){
		
		
		if ( item != undefined ){
			
			if ( str != ''){
				str = str + ',';
			}
			
			str = str + key + '.' + item + '.' + value[key];
			
			
		}
		
		
		
	});
	
	return str;
	
}

function find_color ( place_color, search ){
	
	white_place = Array();
	
	place_color.forEach( function(item, key){

			if ( item == search){
				white_place.push(key);
			}
		});
	
	return white_place;
	
}

function create_all_moves ( place, value, dic_1, dic_2, search ){
	
	var new_place = Array();
	
	place.forEach( function(item){
		
		if ( item != 0 && item != 25 ){
			new_place.push(item);
		}
		
	})
	
	if ( dic_1 != dic_2 ){
		return create_all_moves_2( new_place, value, dic_1, dic_2, search);
	}
	else if ( dic_1 == dic_2 ){
		return create_all_moves_4( new_place, value, dic_1, search);
	}
}

function create_all_moves1 ( place, dic_1, dic_2, search ){
	
	var new_place = Array();
	
	place.forEach( function(item){
		
		if ( item != 0 && item != 25 ){
			new_place.push(item);
		}
		
	})
	
	if ( dic_1 != dic_2 ){
		return create_all_moves_2( new_place, dic_1, dic_2, search);
	}
	else if ( dic_1 == dic_2 ){
		return create_all_moves_4( new_place, dic_1, search);
	}
}

function create_all_moves_2 ( place, value, dic_1, dic_2, search ){
	
	var choice = Array();
	
	var new_place = place;
	var new_value = value;
	
	var new_place1 = place;
	var new_value1 = value;
	
	for( var i=0; i< place.length; i++){
//		
			var to_1 = sum_key(place[i], dic_1, search);
			var to_2 = sum_key(to_1, dic_2, search);
			var from_1 = Number(place[i]);
			var from_2 = Number( to_1 );
		
			choice.push({from_1, to_1});
		
			var new_1 = create_new_move_and_color( new_place, new_value, from_1, to_1);
			
			var new_place_1 = new_1.new_place;
			var new_value_1 = new_1.new_value;
		
			for( var j=0; j<new_place_1.length; j++){

					var from_2 = Number(new_place_1[j]);
					var to_2 = sum_key(new_place_1[j], dic_2, search);
					
					choice.push({from_1, to_1, from_2, to_2});	
					

			}

			var to_1 = sum_key(place[i], dic_2, search);
			var to_2 = sum_key(to_1, dic_1, search);
			var from_1 = Number(place[i]);
			var from_2 = Number( to_1 );
			
			choice.push({from_1, to_1});
			
			var new_11 = create_new_move_and_color( new_place1, new_value1, from_1, to_1);
			
			var new_place_11 = new_11.new_place;
			var new_value_11 = new_11.new_value;
			
			for( var j=0; j<new_place_11.length; j++){
				
					var from_2 = Number(new_place_11[j]);
					var to_2 = sum_key(new_place_11[j], dic_1, search);
					
					choice.push({from_1, to_1, from_2, to_2});	
					


			}
		
		}
	
//	var from_1 = 25, to_1 = 24, from_2 = 23, to_2 = 21;
//	choice.push({from_1, to_1, from_2, to_2});
	
	return choice;
}

function create_all_moves_21 ( place, dic_1, dic_2, search ){
	
	var choice = Array();
	
	for( var i=0; i< place.length; i++){
//		
			var to_1 = sum_key(place[i], dic_1, search);
			var to_2 = sum_key(to_1, dic_2, search);
			var from_1 = Number(place[i]);
			var from_2 = Number( to_1 );
		
			if ( to_2 == -1 ){
				choice.push({from_1, to_1});		
			}
			else {
				choice.push({from_1, to_1, from_2, to_2});	
			}
//			choice.push({from_1, to_1, from_2, to_2});
			for( var j=0; j<place.length; j++){

	//			if ( i != j ){
					var from_2 = Number(place[j]);
					var to_2 = sum_key(place[j], dic_2, search);
					if ( to_2 == -1 ){
						choice.push({from_1, to_1});		
					}
					else {
						choice.push({from_1, to_1, from_2, to_2});	
					}
//					choice.push({from_1, to_1, from_2, to_2});
	//			}


			}

			var to_1 = sum_key(place[i], dic_2, search);
			var to_2 = sum_key(to_1, dic_1, search);
			var from_1 = Number(place[i]);
			var from_2 = Number( to_1 );
			
			if ( to_2 == -1 ){
				choice.push({from_1, to_1});		
			}
			else {
				choice.push({from_1, to_1, from_2, to_2});	
			}
			
//			choice.push({from_1, to_1, from_2, to_2});
			for( var j=0; j<place.length; j++){

	//			if ( i != j ){
					var from_2 = Number(place[j]);
					var to_2 = sum_key(place[j], dic_1, search);
					if ( to_2 == -1 ){
						choice.push({from_1, to_1});		
					}
					else {
						choice.push({from_1, to_1, from_2, to_2});	
					}
					
	//			}


			}
		



		}
	
//	var from_1 = 25, to_1 = 24, from_2 = 23, to_2 = 21;
//	choice.push({from_1, to_1, from_2, to_2});
	
	return choice;
}


function create_all_moves_4 ( place, value, dic_1, search ){
	
	var choice = Array();
	
	var new_place = place;
	var new_value = value;
	
	for( var i=0; i< place.length; i++){
//		
			var to_1 = sum_key(place[i], dic_1, search);
//			var to_2 = sum_key(to_1, dic_2, search);
			var from_1 = Number(place[i]);
//			var from_2 = Number( to_1 );
			choice.push({from_1, to_1});
		
		
		
			var new_1 = create_new_move_and_color( new_place, new_value, from_1, to_1);
			
			var new_place_1 = new_1.new_place;
			var new_value_1 = new_1.new_value;
		
			for( var j=0; j<new_place_1.length; j++){

	
				var from_2 = Number(new_place_1[j]);
				var to_2 = sum_key(new_place_1[j], dic_1, search);
				choice.push({from_1, to_1, from_2, to_2});
				
				var new_2 = create_new_move_and_color( new_place_1, new_value_1, from_2, to_2);
			
				var new_place_2 = new_2.new_place;
				var new_value_2 = new_2.new_value;
				
				for ( var k=0; k<new_place_2.length; k++){
					var from_3 = Number(new_place_2[k]);
					var to_3 = sum_key(new_place_2[k], dic_1, search);
					choice.push({from_1, to_1, from_2, to_2, from_3, to_3});
					
					var new_3 = create_new_move_and_color( new_place_2, new_value_2, from_3, to_3);
			
					var new_place_3 = new_3.new_place;
					var new_value_3 = new_3.new_value;
					
					for ( var m=0; m<new_place_3.length; m++){
						var from_4 = Number(new_place_3[m]);
						var to_4 = sum_key(new_place_3[m], dic_1, search);

						choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});


					}
					
				}


			}

		}
	
//	var from_1 = 25, to_1 = 24, from_2 = 23, to_2 = 21;
//	choice.push({from_1, to_1, from_2, to_2});
	return choice;
}

function create_new_move_and_color ( place, value, from, to ){
	
	var new_place = place.slice();
	var new_value = value.slice();
		
	new_value[from] = Number( new_value[from] ) - Number(1);

	if ( new_value[from] == 0 ){
		
		var index = new_place.indexOf(from);

		if (index > -1) {
			new_place.splice(index,1);
		}
	}


	if ( new_value[to] == undefined )
		new_value[to] = 1;
	else
		new_value[to] = Number(new_value[to]) + Number(1);


//	var insert_to = true;
//	if ( to == 0 || to == 25 ){
//		
//		if ( check_place_color( new_place, 'W') !== false ) 
//			insert_to = false;
//		
//	}
	
//	if ( insert_to === true ){
		var index = new_place.indexOf(to);
	
		if (index == -1) {
			new_place.push(to);
		}
//	}
	
	
	return { new_place: new_place, new_value: new_value};
}


function create_all_moves_41 ( place, dic_1, search ){
	
	var choice = Array();
	
	var new_place = place;
	
	for( var i=0; i< place.length; i++){
//		
			var to_1 = sum_key(place[i], dic_1, search);
//			var to_2 = sum_key(to_1, dic_2, search);
			var from_1 = Number(place[i]);
//			var from_2 = Number( to_1 );
//			choice.push({from_1, to_1, from_2, to_2});
			var new_place_1 = new_place.slice();
			new_place_1.push(to_1);
		
			for( var j=0; j<new_place_1.length; j++){

	
				var from_2 = Number(new_place_1[j]);
				var to_2 = sum_key(new_place_1[j], dic_1, search);
//					choice.push({from_1, to_1, from_2, to_2});
	
				var new_place_2 = new_place_1.slice();
				new_place_2.push(to_2);
				
				for ( var k=0; k<new_place_2.length; k++){
					var from_3 = Number(new_place_2[k]);
					var to_3 = sum_key(new_place_2[k], dic_1, search);
					
					var new_place_3 = new_place_2.slice();
					new_place_3.push( to_3);
					
					for ( var m=0; m<new_place_3.length; m++){
						var from_4 = Number(new_place_3[m]);
						var to_4 = sum_key(new_place_3[m], dic_1, search);

						choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});


					}
					
				}


			}

		}
	
//	var from_1 = 25, to_1 = 24, from_2 = 23, to_2 = 21;
//	choice.push({from_1, to_1, from_2, to_2});
	
	return choice;
}

function create_all_moves_2_4 ( place, dic_1, search, moves ){
	
	var choice = Array();
	
	var new_place = place;
	
	for( var i=0; i< place.length; i++){
//		
			var to_1 = sum_key(place[i], dic_1, search);
//			var to_2 = sum_key(to_1, dic_2, search);
			var from_1 = Number(place[i]);
//			var from_2 = Number( to_1 );
//			choice.push({from_1, to_1, from_2, to_2});
			var new_place_1 = new_place.slice();
			new_place_1.push(to_1);
			
			for( var j=0; j<new_place_1.length; j++){

	
				var from_2 = Number(new_place_1[j]);
				var to_2 = sum_key(new_place_1[j], dic_1, search);
//					choice.push({from_1, to_1, from_2, to_2});
	
				var new_place_2 = new_place_1.slice();
				new_place_2.push(to_2);
				
				for ( var k=0; k<new_place_2.length; k++){
					var from_3 = Number(new_place_2[k]);
					var to_3 = sum_key(new_place_2[k], dic_1, search);
					
					var new_place_3 = new_place_2.slice();
					new_place_3.push( to_3);
					
					for ( var m=0; m<new_place_3.length; m++){
						var from_4 = Number(new_place_3[m]);
						var to_4 = sum_key(new_place_3[m], dic_1, search);

//						choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});
						
						var new_choice = check_insert_to_choice({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4}, moves );
						
						if ( new_choice != '' ){
							choice.push( new_choice );
						}
						
					}
					
				}


			}

		}
	
//	var from_1 = 25, to_1 = 24, from_2 = 23, to_2 = 21;
//	choice.push({from_1, to_1, from_2, to_2});
	
	return choice;
}


function check_insert_to_choice ( item, moves ){
	
	var choice = Array();
	var continues = true;
	
	for ( var i=1; i<=moves; i++ ){
		
		var from = eval('item.from_'+i);
		var to	 = eval('item.to_'+i);
		
		if ( check_from ( from ) && continues === true ){
			
			choice['from_'+i] = from;
			choice['to_'+i] = to;
		}
		else{
			continues = false;
		}
		
	}
	
	var return_choice = {};

	choice.forEach( function(item, key){
		return_choice.key = item;
	})
	
	
	return choice;
	
//	if ( moves > 1 ){
//		
//		if ( check_from ( item.from_1 ) ){
//			choice.from_1 	= item.from_1;
//			choice.to_1		= item.to_1;
//		}
//		
//	}
//	if ( moves > 2 ){
//		
//		if ( check_from( item.from_2) ){
//			choice.from_2	= item.from_2;
//			choice.to_2		= item.to_2;
//		}
//	}
//	
//	if ( moves == 1 ){
//		if (  ){
//			choice.push({from_1, to_1});
//		}
//	}
//	else if ( moves == 2){
//		if ( check_from ( item.from_1 ) && check_from( item.from_2 ) ){
//			choice.push({from_1, to_1,from_2, to_2});	
//		}
//		else if ( check_from ( item.from_1 ) ){
//			choice.push({from_1, to_1});	
//		}
//
//	}
//	else if ( moves == 3){
//		if ( check_from ( from_1 ) ){
//			choice.push({from_1, to_1,from_2, to_2,from_3, to_3});
//		}
//
//	}
//	else if ( moves == 4){
//		if ( check_from ( from_1 ) ){
//			choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});
//		}
//
//	}

	
	
}


function check_from ( from, search ){
	
	if ( search == 'B' && from == 25 )
		return false;
	else if ( search == 'W' && from == 0 )
		return false;
	else
		return true;
}

function create_all_moves_with_remove_key ( place, value, dic_1, dic_2, search, move_length ){
	
	var choice = Array();
	
//	value = place[ place.length - 1 ];
	
	
	if ( search == 'W'){
		var start_key = 26;
		var color_keys = 125;
	}
	else if ( search == 'B'){
		var start_key = 27;
		var color_keys = 100;
	}

	
	if ( move_length == 2 && value[start_key] == 1 ){
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_1, search),
			from_2 	= to_1,
			to_2	= sum_key(to_1, dic_2, search);
		
		choice.push({from_1, to_1});
		choice.push( {from_1, to_1, from_2, to_2});
		for ( var i=0; i< place.length; i++ ){
			var from_2 	= place[i],
				to_2	= sum_key( place[i], dic_2, search);
			
			choice.push( {from_1, to_1, from_2, to_2});
				
		}
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_2, search),
			from_2 	= to_1,
			to_2	= sum_key(to_1, dic_1, search);
		
		choice.push({from_1, to_1});
		choice.push( {from_1, to_1, from_2, to_2});
		for ( var i=0; i< place.length; i++ ){
			var from_2 	= place[i],
				to_2	= sum_key( place[i], dic_1, search);
			
			choice.push( {from_1, to_1, from_2, to_2});
				
		}
		
	}
	else if ( move_length == 2 && value[start_key] > 1 ){
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_1, search),
			from_2 	= start_key,
			to_2	= sum_key(color_keys, dic_2, search);
		
		choice.push( {from_1, to_1});
		choice.push( {from_1, to_1, from_2, to_2});
		
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_2, search),
			from_2 	= start_key,
			to_2	= sum_key(color_keys, dic_1, search);
		
		choice.push( {from_1, to_1});
		choice.push( {from_1, to_1, from_2, to_2});
		
	}
	
	else if ( move_length == 4 && value[start_key] == 1 ){
		
		var new_place = place;
		var new_value = value;
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_1, search); 

		choice.push({from_1, to_1});
		
		var new_1 = create_new_move_and_color( new_place, new_value, from_1, to_1);
			
		var new_place_1 = new_1.new_place;
		var new_value_1 = new_1.new_value;

		for( var j=0; j<new_place_1.length; j++){


			var from_2 = Number(new_place_1[j]);
			var to_2 = sum_key(new_place_1[j], dic_1, search);
//					choice.push({from_1, to_1, from_2, to_2});
			choice.push({from_1, to_1,from_2, to_2});
			
			var new_2 = create_new_move_and_color( new_place_1, new_value_1, from_2, to_2);
			
			var new_place_2 = new_2.new_place;
			var new_value_2 = new_2.new_value;

			for ( var k=0; k<new_place_2.length; k++){
				var from_3 = Number(new_place_2[k]);
				var to_3 = sum_key(new_place_2[k], dic_1, search);
				choice.push({from_1, to_1,from_2, to_2,from_3, to_3});

				var new_3 = create_new_move_and_color( new_place_2, new_value_2, from_3, to_3);
			
				var new_place_3 = new_3.new_place;
				var new_value_3 = new_3.new_value;
				
				for ( var m=0; m<new_place_3.length; m++){
					var from_4 = Number(new_place_3[m]);
					var to_4 = sum_key(new_place_3[m], dic_1, search);

					choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});


				}

			}


		}

		
		
	}
	else if ( move_length == 4 && value[start_key] == 2 ){
		
		var new_place = place;
		var new_value = value;
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_1, search),
			from_2	= start_key,
			to_2	= sum_key(color_keys, dic_1, search);

		var new_1 = create_new_move_and_color( new_place, new_value, from_1, to_1);
			
		var new_place_1 = new_1.new_place;
		var new_value_1 = new_1.new_value;
		
		var new_2 = create_new_move_and_color( new_place_1, new_value_1, from_1, to_2);
			
		var new_place_2 = new_2.new_place;
		var new_value_2 = new_2.new_value;

		choice.push({from_1, to_1});
		choice.push({from_1, to_1,from_2, to_2});

		for ( var k=0; k<new_place_2.length; k++){
			var from_3 = Number(new_place_2[k]);
			var to_3 = sum_key(new_place_2[k], dic_1, search);
			
			choice.push({from_1, to_1,from_2, to_2,from_3, to_3});

			var new_3 = create_new_move_and_color( new_place_2, new_value_2, from_3, to_3);
			
			var new_place_3 = new_3.new_place;
			var new_value_3 = new_3.new_value;

			for ( var m=0; m<new_place_3.length; m++){
				var from_4 = Number(new_place_3[m]);
				var to_4 = sum_key(new_place_3[m], dic_1, search);

				choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});


			}

		}		
	}
	else if ( move_length == 4 && value[start_key] == 3 ){
		
		var new_place = place;
		var new_value = value;
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_1, search),
			from_2	= start_key,
			to_2	= sum_key(color_keys, dic_1, search),
			from_3	= start_key,
			to_3	= sum_key(color_keys, dic_1, search);

		
		var new_1 = create_new_move_and_color( new_place, new_value, from_1, to_1);
			
		var new_place_1 = new_1.new_place;
		var new_value_1 = new_1.new_value;
		
		var new_2 = create_new_move_and_color( new_place_1, new_value_1, from_2, to_2);
			
		var new_place_2 = new_2.new_place;
		var new_value_2 = new_2.new_value;
		
		var new_3 = create_new_move_and_color( new_place_2, new_value_2, from_3, to_3);
			
		var new_place_3 = new_3.new_place;
		var new_value_3 = new_3.new_value;
		
		
//		var new_place_3 = new_place.slice();
//		new_place_3.push( to_1 );
//		new_place_3.push( to_2 );
//		new_place_3.push( to_3 );

		choice.push({from_1, to_1,from_2, to_2,from_3, to_3});
		
		for ( var m=0; m<new_place_3.length; m++){
			var from_4 = Number(new_place_3[m]);
			var to_4 = sum_key(new_place_3[m], dic_1, search);

			choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});
		}	
	}
	else if ( move_length == 4 && value[start_key] > 3 ){
		var new_place = place;
		
		
		var from_1 	= start_key,
			to_1 	= sum_key(color_keys, dic_1, search),
			from_2	= start_key,
			to_2	= sum_key(color_keys, dic_1, search),
			from_3	= start_key,
			to_3	= sum_key(color_keys, dic_1, search),
			from_4	= start_key,
			to_4	= sum_key(color_keys, dic_1, search);
		
		choice.push({from_1, to_1,from_2, to_2,from_3, to_3,from_4, to_4});
	}
	
//	var from_1 = 25, to_1 = 24, from_2 = 23, to_2 = 21;
//	choice.push({from_1, to_1, from_2, to_2});
	
	return choice;
}

function sum_key ( place, dic, search ){
	
//	if ( place == 0 || (25  <= place && place < 40 )){
//			return -1;
//		}
	
	if ( place == 100 )
		place = 0;
	else if ( place == 125 )
		place = 25;
	
	if( search == 'W'){
		
		var num = Number(place) - Number(dic);
		if ( num < 0 )
			return 0;
		else
			return num;
	}
	else if ( search == 'B'){
		var num = Number(dic) + Number(place);
		if ( num > 25 )
			return 25;
		else
			return num;
	}
}

function control_can_moves ( choice, place_color, place_value, search, moves ){
	
	if ( search == 'W'){
		var op_search = 'B';
	}
	else if ( search == 'B'){
		var op_search = 'W';
	}
	
	var remove_key = Array();

	remove_key = control_can_moves_2 ( choice, place_color, place_value, search, moves );
	
	for (var i = remove_key.length -1; i >= 0; i--){
		choice.splice(remove_key[i],1);
	}
	
	return choice;
	
}

function control_can_moves_exist ( color, value, from, to, search){
	
	var res = { res: 10 };
	var new_color = color.splice();
	var new_value = value.splice();
	
	if ( search == 'W'){
		var search_place = 0;
	}
	else if ( search == 'B'){
		var search_place = 25;
	}
		
	
	if ( from == search_place )
		res = { res: 0 };
	else if ( Number(to) != search_place ){
		// check key can moves
		res = check_key_can_moves( color, value, from, to, search );
	}
	else if ( Number(to) == search_place ){
		// check can exit key
		
		res = check_key_can_exist( color, value, from, to, search );
		
		if (res.res == 1 ){
			console.log(res);
		}
		
	}
	

	return res;
	
	
	
	
}

function check_key_can_moves ( color, value, from, to, search ){
	
	var response;
	
	if ( color[to] != undefined ){
		
		if ( color[to] != search && Number(value[to]) > 1 ){
			response = { res: 0, color: color };
		}
		else if ( color[to] == search ){

			if ( Number(value[from]) == 1 ){
				value[from] = 0;
				color[from] = '';
			}
			else
				value[from] = Number( value[from] ) - Number(1);

			if ( value[to] == undefined || Number(value[to]) == 0)
				value[to] = 1;
			else
				value[to] = Number(value[to]) + Number(1);

			response = { res: 1, value: value, color: color };
		}
		else if ( color[to] != search && Number( value[to] ) == 1 ){
			if ( Number(value[from]) == 1 ){
				value[from] = Number(0);
				color[from] = '';
			}
			else
				value[from] = Number( value[from] ) - Number(1);

			value[to] = Number(1);
			color[to] = search;

			response = { res: 1, value: value, color: color };
		}
		else {
			if ( Number(value[from]) == 1 ){
				value[from] = 0;
				color[from] = '';
			}
			else
				value[from] = Number( value[from] ) - Number(1);

			if ( value[to] == undefined || Number(value[to]) == 0)
				value[to] = 1;
			else
				value[to] = Number(value[to]) + Number(1);

			color[to] = search;


			response = { res: 1, value: value, color: color };
		}
	}
	else if ( color[to] == undefined ){
		
		if ( Number(value[from]) == 1 ){
				value[from] = 0;
				color[from] = '';
		}
		else
			value[from] = Number( value[from] ) - Number(1);

		if ( value[to] == undefined || Number(value[to]) == 0)
			value[to] = 1;
		else
			value[to] = Number(value[to]) + Number(1);

		color[to] = search;


		response = { res: 1, value: value, color: color };
		
	}
	
	
	
	return response;
	
}

function check_key_can_exist ( color, value, from, to, search ){
	
	
	if ( search == 'B' ){
		var first_number = 19;
		var second_number = 25;
	}
	else if ( search == 'W'){
		var first_number = 0;
		var second_number = 6;
	}
	
	var can_exist = true;

	color.forEach( function(item, key){
		if ( item == search ){

		}
		
		if (item == search &&  !(first_number <= key && key <= second_number) ){	
			can_exist = false
		}
			
		
	});
	
	
	if ( can_exist === false )
		res = { res: 0, color: color}
	else
		res = { res: 1, color: color, value: value};
	
	return res;
	
	
}

function control_can_moves_2 ( choice, place_color, place_value, search, moves ){
	
	
	var remove_key = Array();
	
	choice.forEach( function(item, key){
		
		var color = place_color.slice();
		var value = place_value.slice();
		
		var continues = true;
		
		for ( var i=1; i<=moves; i++){
		
			
			
			if ( item.from_1 == 7 && item.from_2 == 7 && item.from_3 == 7 && item.from_4 == 1){
				console.log(i);
				console.log(color);	
				console.log(color[to]);	
			}
			var from = eval('item.from_'+i);
			var to = eval('item.to_'+i);
//		
			
			if ( continues === true ){
				var res = control_can_moves_exist( color, value, from, to, search);

				if ( item.from_1 == 7 && item.from_2 == 7 && item.from_3 == 7 && item.from_4 == 1){
					console.log(res);	
				}
				
				if ( res.res == 0 && continues === true ){
					remove_key.push( key );
//					console.log(item);
					continues = false;
				}
				else if ( res.res == 1 && continues === true ){
					color = res.color;
					value = res.value;
				}
				else if ( res.res == 2 ){

				}
			}
				
			
//			if( place_color[to] != undefined && continues === true ){
//				
//				if ( place_color[to] != search && Number(place_value[to]) > 1 ){
//	//					choice.splice(key, 1);
//					remove_key.push( key );
//					continues = false;
//				}
//			}
//			
//			else if ( place_color[to] == undefined && (Number(to) <= 0 || Number(to) >= 25) && continues === true  ){
////				
//				if ( check_place ( place_color, place_value, item, i, search) === false){
//					continues = false;
//					remove_key.push( key );
//				}
//	
//				
//			}
//			else if ( place_color[to] == undefined && (Number(to) <= 0 || Number(to) >= 25) && continues === true  ){
////				
//				if ( check_place ( place_color, place_value, item, i, search) === false){
////					continues = false;
////					remove_key.push( key );
//				}
//	
//				
//			}
//			
			
			
		}
		

	});
	
	return remove_key;
	
	
}


function control_can_moves_21 ( choice, place_color, place_value, search, moves ){
	
	
	var remove_key = Array();
	
	choice.forEach( function(item, key){

		var continues = true;
		for ( var i=1; i<=moves; i++){
		
			
			var from = eval('item.from_'+i);
			var to = eval('item.to_'+i);
//		
			
			if( place_color[to] != undefined && continues === true ){
				
				if ( place_color[to] != search && Number(place_value[to]) > 1 ){
	//					choice.splice(key, 1);
					remove_key.push( key );
					continues = false;
				}
			}
			
			else if ( place_color[to] == undefined && (Number(to) <= 0 || Number(to) >= 25) && continues === true  ){
//				
				if ( check_place ( place_color, place_value, item, i, search) === false){
					continues = false;
					remove_key.push( key );
				}
	
				
			}
//			else if ( place_color[to] == undefined && (Number(to) <= 0 || Number(to) >= 25) && continues === true  ){
////				
//				if ( check_place ( place_color, place_value, item, i, search) === false){
////					continues = false;
////					remove_key.push( key );
//				}
//	
//				
//			}
//			
			
			
		}
		

	});
	
	return remove_key;
	
	
}

function control_can_moves_4 ( choice, place_color, place_value, search, moves ){
	
	
	var remove_key = Array();
	
	choice.forEach( function(item, key){

		var continues = true;
		for ( var i=1; i<=moves; i++){
		
			
			var from = eval('item.from_'+i);
			var to = eval('item.to_'+i);
//			
			
			if( place_color[to] != undefined && continues === true ){
				
				if ( place_color[to] != search && Number(place_value[to]) > 1 ){
	
					remove_key.push( key );
					continues = false;
				}
			}
			
			else if ( place_color[to] == undefined && (Number(to) <= 0 || Number(to) >= 24) && continues === true  ){
				
				if ( check_place ( place_color, place_value, item, i, search) === false){
					continues = false;
					remove_key.push( key );
				}
	
				
			}
			
			
			
		}
		

	});
	
	return remove_key;
	
}


function check_place ( color, value, item, moves, search){
	
	
	place_color = color.slice();
	place_value = value.slice();
	var continues = true;
	for ( var i=1; i<=moves; i++){
			
		var from = eval('item.from_'+i);
		var to = eval('item.to_'+i);
		
		if ( place_value[from] == 1 ){
			place_value[from] = 0;
			place_value[to] = Number(place_value[to]) + Number(1);
			place_color[to] = place_color[from];
			place_color[from] = '';
			
			
//			if ( (Number(to) <= 0 || Number(to) >= 24 ) )
			if (check_place_color( place_color, search) === false){
				continues = false;
			}
			
		}
//		else if ( place_value[from] > 1 ){
//			place_value[from] = 0;
//			place_value[to] = Number(place_value[to]) + Number(1);
//			place_color[to] = place_color[from];
//			place_value[from] = Number(place_value[to]) - Number(1);
//			if (check_place_color( place_color, search) === false){
//				continues = false;
//			}
//		}
//		else{
//			if (check_place_color( place_color, search) === false){
//				continues = false;
//			}
//		}
		
		
		
	}
	
	if ( continues === false ){
		return false;
	}
	else{
		return check_place_color( place_color, search);	
	}
	
	
	
	
	
	
	
}

function check_place_color( place, search){
	
	
	if ( search == 'B' ){
		var first_number = 19;
		var second_number = 24;
	}
	else if ( search == 'W'){
		var first_number = 1;
		var second_number = 6;
	}
	
	var game_continue = true;
	place.forEach( function(item, key){
		if (item == search &&  !(first_number <= item && item <= second_number) ){	
			game_continue = false
		}
			
		
	});
	
	if ( game_continue === false )
		return false;
	else 
		return true;
	
}

function check_win( place, value, search ){
	if ( search == 'W' ){
		var win_number = 0;
	}
	else if ( search == 'B'){
		var win_number = 25;
	}
	
	if ( place[win_number] != undefined && value[win_number] != undefined  ){
		if (place[win_number] == search && value[win_number] == 15)
			return true;
	}
	else
		return false;
	
}

function move_selected ( moves, place_color, place_value, search ){
	
	if ( search == 'W'){
		var op_search = 'B';
	}
	else if ( search == 'B'){
		var op_search = 'W';
	}
	moves.forEach( function(move){
					
		if( place_color[move.from] == search ){
			place_value[move.from] = Number(place_value[move.from])-1;

			if ( place_value[move.from] < 1 ){
				place_color[move.from] = undefined;
			}

			if ( place_color[move.to] == search){
				place_value[move.to] = Number(place_value[move.to]) + 1;
			}
			else if ( place_color[move.to] == undefined ){
				place_value[move.to] = 1;
				place_color[move.to] = search;
			}
			else if ( place_color[move.to] == op_search && place_value[move.to] == 1 ){
				place_color[move.to] = search;
				place_value[move.to] = 1;
				
				if ( op_search == 'W'){
					place_color[26] = 'W';
					if ( place_value[26] == undefined)
						place_value[26] = 1;
					else 
						place_value[26] = Number(place_value[26]) + Number(1);
				}
				else if ( op_search == 'B'){
					place_color[27] = 'B';
					if( place_value[27] == undefined )
						place_value[27] = 1;
					else
						place_value[27] = Number(place_value[27]) + Number(1);
				}
				
				
			}

		}
		
	});
	
	return { color: place_color, value: place_value};
}

function check_has_remove_key ( place, value, search ){

	if ( (place[26] == search && value[26] > 0) || (place[27] == search && value[27] > 0)  )
		return true;
	else
		return false;
}

function create_moves_code ( dic_1, dic_2, choice ){
	
	var chars = '1234567890ABCDEFGHIJKLMNOPRSTUVYZWX';
	var posibilities = '';

	var test_arr = Array();

	if ( dic_1 != dic_2){
		return create_moves_code_2( choice, posibilities, chars );	
	}
	else if ( dic_1 == dic_2 ){
		return create_moves_code_4( choice, posibilities, chars );	
	}
	
}

function create_moves_code_2 ( choice, posibilities, chars ){
	
	var last_select = '';
	
	var test_arr = Array();
	
	choice.forEach( function(item){

		var select_1 = chars.charAt(item.from_1)+chars.charAt(item.to_1),
			select_2 = chars.charAt(item.from_2)+chars.charAt(item.to_2);


		if ( select_2 != 11){
			if ( test_arr[chars.charAt(item.from_1)] != undefined ){
	//							posibilities = posibilities + '.';
			}
			else if ( test_arr[chars.charAt(item.from_1)] == undefined){
				test_arr.push(chars.charAt(item.from_1));
			}

	//
			if ( last_select == select_1 ){
				posibilities = posibilities + ',' + select_2;
			}
			else {
				if ( posibilities == ''){
					posibilities = select_1 + ';' + select_2;
				}
				else {
					posibilities = posibilities + '.' + select_1 + ';' + select_2 ;
				}
			}

			last_select = select_1;
		}
		else{
			if ( test_arr[chars.charAt(item.from_1)] != undefined ){
	//							posibilities = posibilities + '.';
			}
			else if ( test_arr[chars.charAt(item.from_1)] == undefined){
				test_arr.push(chars.charAt(item.from_1));
			}



	//
			if ( last_select == select_1 ){
				posibilities = posibilities + ',' ;
			}
			else {
				if ( posibilities == ''){
					posibilities = select_1 ;
				}
				else {
					posibilities = posibilities + '.' + select_1 ;
				}
			}

			last_select = select_1;
		}
		
	});
	
	posibilities = posibilities + '.';
	return posibilities;
}

function create_moves_code_4 ( choice, posibilities, chars ){
	
	var last_select_1 = '',
		last_select_2 = '',
		last_select_3 = '';
	
	var test_arr = Array();
	
	choice.forEach( function(item){

		var select_1 = chars.charAt(item.from_1)+chars.charAt(item.to_1),
			select_2 = chars.charAt(item.from_2)+chars.charAt(item.to_2),
			select_3 = chars.charAt(item.from_3)+chars.charAt(item.to_3),
			select_4 = chars.charAt(item.from_4)+chars.charAt(item.to_4);


		if ( test_arr[chars.charAt(item.from_1)] != undefined ){
//							posibilities = posibilities + '.';
		}
		else if ( test_arr[chars.charAt(item.from_1)] == undefined){
			test_arr.push(chars.charAt(item.from_1));
		}



//
		if ( last_select_1 == select_1 && last_select_2 == select_2 && last_select_3 == select_3){
			posibilities = posibilities + select_4;
		}
		else if ( last_select_1 == select_1 && last_select_2==select_2 && last_select_3!=select_3 ){
			posibilities = posibilities + ';' + select_3 + ' ' + select_4;
		}
		else if ( last_select_1 == select_1 && last_select_2 != select_2 ){
			
			if ( select_1 != 11 && select_2 != 11 && select_3 != 11 && select_4 != 11)
				posibilities = posibilities + ',' + select_2 +';'+select_3+' '+select_4 ;
			else if ( select_1 != 11 && select_2 != 11 && select_3 != 11 && select_4 == 11)
				posibilities = posibilities + ',' + select_2 +';'+select_3 ;
			else if ( select_1 != 11 && select_2 != 11 && select_3 == 11 && select_4 == 11)
				posibilities = posibilities + ',' + select_2 ;
		}
		else {
			if ( posibilities == ''){
				
				if ( select_1 != 11 && select_2 != 11 && select_3 != 11 && select_4 != 11)
					posibilities = select_1 + ';' + select_2 +';'+select_3+' '+select_4;
				else if ( select_1 != 11 && select_2 != 11 && select_3 != 11 && select_4 == 11)
					posibilities = select_1 + ';' + select_2 +';'+select_3;
				else if ( select_1 != 11 && select_2 != 11 && select_3 == 11 && select_4 == 11)
					posibilities = select_1 + ';' + select_2;
				else if ( select_1 != 11 && select_2 == 11 && select_3 == 11 && select_4 == 11)
					posibilities = select_1;
			}
			else {
				if ( select_1 != 11 && select_2 != 11 && select_3 != 11 && select_4 != 11)
					posibilities = posibilities + '.' + select_1 + ';' + select_2 +';'+select_3+' '+select_4;
				else if ( select_1 != 11 && select_2 != 11 && select_3 != 11 && select_4 == 11)
					posibilities = posibilities + '.' + select_1 + ';' + select_2 +';'+select_3;
				else if ( select_1 != 11 && select_2 != 11 && select_3 == 11 && select_4 == 11)
					posibilities = posibilities + '.' + select_1 + ';' + select_2;
				else if ( select_1 != 11 && select_2 == 11 && select_3 == 11 && select_4 == 11)
					posibilities = posibilities + '.' + select_1;
//				posibilities = posibilities + '.' + select_1 + ';' + select_2 +';'+select_3+' '+select_4 ;
			}
		}

		last_select_1 = select_1;
		last_select_2 = select_2;
		last_select_3 = select_3;


	});
	
	return posibilities;
}

function status_5( data, callback){

	game.get_user_with_id(data.op_user_id, function(op_user){
		game.get_user_with_id(data.user_id, function(my_user){
			data.my_data = my_user[0];
			data.op_data = op_user[0];

			data.amount = data.game_data.amount;

			game.get_my_and_op_data( data, function(result){

				var game_data 	= data.game_data,
					dic_1		= game_data.dic.dic_1,
					dic_2		= game_data.dic.dic_2,
					places		= game_data.places;
				
				calculate_move(data, function(choice){
				
					var posibilities = create_moves_code ( dic_1, dic_2, choice );
					
//					if ( posibilities != ''){
//						
						waiting_status_5( data.token, function(results){
							
							if (results == 1 ){
								
								var random_moves = choice[1];
								
								
								if ( random_moves != undefined )
									var moves = convert_random_moves( random_moves );
								else if ( random_moves == undefined )
									var moves = [];
								
								var command = {
									game_id: 6,
									moves: moves,
									token: data.token,
									uid: data.user_id
								}
								play_backgammon( command, function(result){
										
								})
								
							}
						})
//					}
					
					
//						var chars = '1234567890ABCDEFGHIJKLMNOPRSTUVYZWX';
//						var posibilities = '';
//						var last_select = '';
//
//						var test_arr = Array();
//
//						choice.forEach( function(item){
//
//							var select_1 = chars.charAt(item.from_1)+chars.charAt(item.to_1),
//								select_2 = chars.charAt(item.from_2)+chars.charAt(item.to_2);
//
//
//							if ( test_arr[chars.charAt(item.from_1)] != undefined ){
//	//							posibilities = posibilities + '.';
//							}
//							else if ( test_arr[chars.charAt(item.from_1)] == undefined){
//								test_arr.push(chars.charAt(item.from_1));
//							}
//
//
//
//	//
//							if ( last_select == select_1 ){
//								posibilities = posibilities + ',' + select_2;
//							}
//							else {
//								if ( posibilities == ''){
//									posibilities = select_1 + ';' + select_2;
//								}
//								else {
//									posibilities = posibilities + '.' + select_1 + ';' + select_2 ;
//								}
//							}
//
//							last_select = select_1;
//	//						
//
//
//						});


						if ( data.game_data.side == 'me'){
							var user_1 	= data.my_data,
								user_2 	= data.op_data,
								row_1 	= data.my,
								row_2 	= data.op;
						}
						else if ( data.game_data.side == 'op') {
							var user_1 	= data.op_data,
								user_2 	= data.my_data,
								row_1 	= data.op,
								row_2 	= data.my;
						}

					
					callback({
						command: 'game_dice',
						turn	: data.game_data.time,
						white_dice : 1,
						black_dice : 0,
						dice_1 : dic_1,
						dice_2 : dic_2,
						posibilities : '00',
						places	: "1.B.4"

					});	

						callback({
//							command		: "game_status",
							command		: "game_status",
							places		: data.game_data.places,
			//						turn" :'BLACK',
							turn		: data.game_data.time,
							amount		: data.amount,
			//						double":$Data['double'],
							double		 :'false',

			//----------			------------AGE POR BASHE BAZI DOUBLE KHAHD SHOD 
			//						double":0,//age bkhy shart double bashe  dar ghyr insort bayad  "double":'',
			//						double_level":2,
			//						double_waiting" :1,
			//						double_offered" :1,

							uid_1		:user_1.id,
							photo_1		:'',
			//						level_1":4,
							chips_1		:user_1.cash,
							name_1		:user_1.username,
			//						double_uid_1":2,
			//						score_1":60000000,

							uid_2		:user_2.id,
							photo_2		:'',
			//						level_2":0,
							chips_2		:user_2.cash,
							name_2		:user_2.username,
			//						double_uid_2":2,
			//						score_2":80000000,

							dice_1		 : dic_1,
							dice_2		 : dic_2,
			//						posibilities :"23;25,2425262728",
			//						posibilities :"23;25;31",
			//						posibilities	 :"27,24,28,29;29;30",
			//						posibilities	 :"29;9a,24;45;46,bc,bd.20;0A",
	//						posibilities	 :"29;9A;AB;BC,20;0B.24;45,26;68.23;34",
	//						posibilities	 : "75;74,96,DA,OL",
							posibilities	 : posibilities,
//							posibilities	 : "73;73;73 7395;95 95,95,D0,OK.95;",
//							
			//								posibilities	 :"D8;85,D7;74,D9;93.D9;95",
			//						posibilities	 :"C7;73.C9;93.C7,C9",


							status		 :4,

						});
					
//					if ( posibilities == '' ){
//						
//						my_data = result.my_data;
//						op_data = result.op_data;
//						
//						var op_game_data = op_data.game_data;
//						var my_game_data = my_data.game_data;
//						
//						if ( my_game_data.time == 'WHITE'){
//							var time = 'BLACK';
//						}
//						else if ( my_game_data.time == 'BLACK'){
//							var time = 'WHITE';
//						}
//						
//						op_game_data.time = time;
//						my_game_data.time = time;
//						
//						op_filter = {token: op_data.token};
//						op_update = { game_data: op_game_data, status: 5};
//						my_filter = {token: data.token};
//						my_update = { game_data: my_game_data, status: 7};
//						
//						game.update_online_gamer( my_filter, my_update, function(result){
//							game.update_online_gamer(op_filter, op_update, function(result){
//								
//							})
//						})
//						
//					}
//					
					
					
				});
				
				

				


				




				});
			});
		
	});
	
}


function status_6 ( data, callback){
	
	game.get_my_and_op_data( data, function(result){
		
		var op_data = result.op_data,
			my_data = result.my_data;
		
		var dic_1 = op_data.game_data.dic.dic_1,
			dic_2 = op_data.game_data.dic.dic_2;
		
		game.get_user_with_id(op_data.user_id, function(op_user){
			
			game.get_user_with_id(my_data.user_id, function(my_user){
				
				data.my_data = my_user[0];
				data.op_data = op_user[0];
				data.my = my_data;
				data.op = op_data;
				
				if ( data.game_data.side == 'me'){
					var user_1 	= data.my_data,
						user_2 	= data.op_data,
						row_1 	= data.my,
						row_2 	= data.op;
				}
				else if ( data.game_data.side == 'op') {
					var user_1 	= data.op_data,
						user_2 	= data.my_data,
						row_1 	= data.op,
						row_2 	= data.my;
				}
				
				
				callback({
					command		: "game_status",
					places		: my_data.game_data.places,
//					turn		: data.game_data.time,
					amount		: data.amount,
	
					double		 :'false',
					uid_1		:user_1.id,
					photo_1		:'',
					chips_1		:user_1.cash,
					name_1		:user_1.username,
					uid_2		:user_2.id,
					photo_2		:'',

					name_2		:user_2.username,

					dice_1		 : dic_1,
					dice_2		 : dic_2,
					posibilities	 : '',
					status		 :4,

				});

		
		callback({
				command: 'game_dice',
				turn	: data.game_data.time,
				white_dice : 1,
				black_dice : 0,
				dice_1 : dic_1,
				dice_2 : dic_2,
				posibilities : '00',
				places	: "1.B.4"
			
			});	
				
				
			})
		})
		
		
		
		
		
	});
	
	
	
	
	
}

function waiting_status_5 ( token, callback){
	var i = 0;
				
	var id = setInterval( function(){

		game.get_data_with_filter( {token: token}, function(result){
			if ( result.status != 5 ){
				clearInterval(id);
			}

		});

		 i = i+1;


		if ( i == 20 ){
			callback(1);
		}

	}, 1000);
}


function convert_random_moves ( moves ){
	
	if ( Object.keys(moves).length == 2 ){
		var arr = [ {i:1, from: moves.from_1, to: moves.to_1}];
	}
	else if ( Object.keys(moves).length == 4 ){
		var arr = [ {i:1, from: moves.from_1, to: moves.to_1},  
				    {i:2, from: moves.from_2, to: moves.to_2},
				  ];
	}
	else if ( Object.keys(moves).length == 6 ){
		var arr = [ {i:1, from: moves.from_1, to: moves.to_1},  
				    {i:2, from: moves.from_2, to: moves.to_2},
				    {i:3, from: moves.from_3, to: moves.to_3},
				  ];
	}
	else if ( Object.keys(moves).length == 8 ){
		var arr = [ {i:1, from: moves.from_1, to: moves.to_1},  
				    {i:2, from: moves.from_2, to: moves.to_2},
				    {i:3, from: moves.from_3, to: moves.to_3},
				    {i:4, from: moves.from_4, to: moves.to_4},
				  ];
		
	}
	
	
	return arr;
	
	
}

function play_backgammon( data, callback){
	
	var filter = { token: data.token };
	
	game.get_data_row( filter, function(my_data){
		
		var op_filter = {
			user_id 	: my_data.op_user_id,
			op_user_id	: my_data.user_id,
			game_id		: my_data.game_id,
		};
		game.get_data_row( op_filter, function(op_data){
		
		var places = my_data.game_data.places;
		var amount = my_data.game_data.amount;
		
		
		convert_place_to_array( places, function(place_array){
			
			var place = place_array.place;
			var place_color = place_array.place_color;
			var place_value = place_array.place_value;
			
			var game_data = my_data.game_data;
			var moves = data.moves;
			
			if ( (game_data.side == 'me' && game_data.time == 'WHITE') || game_data.side == 'op' && game_data.time == 'BLACK' ){
				
				if ( game_data.time == 'WHITE'){
					var search = 'W';
				}
				else if ( game_data.time == 'BLACK'){
					var search = 'B';
				}
				
				move = move_selected ( moves, place_color, place_value, search );
				
				place_color = move.color;
				place_value = move.value;
				
				
//				moves.forEach( function(move){
//					
//					if( place_color[move.from] == 'W' ){
//						place_value[move.from] = Number(place_value[move.from])-1;
//						
//						if ( place_value[move.from] < 1 ){
//							place_color[move.from] = undefined;
//						}
//						
//						if ( place_color[move.to] == 'W'){
//							place_value[move.to] = Number(place_value[move.to]) + 1;
//						}
//						else if ( place_color[move.to] == undefined ){
//							place_value[move.to] = 1;
//							place_color[move.to] = 'W';
//						}
//						else if ( place_color[move.to] == 'B' && place_value[move.to] == 1 ){
//							place_color[move.to] = 'W';
//							place_value[move.to] = 1;
//						}
//						
//					}
//				});
				
//				place_color[0] = 'W';
//				place_value[0] = 15;
				if ( check_win ( place_color, place_value, search ) ){
					
					if ( place_value[0] == 15 ){
						var win = 'WHITE';
					}
					else if ( place_value[25] == 15){
						var win = 'BLACK';
					}
					
					if ( my_data.game_data.side == 'me'){
						var uid_1 = my_data.user_id,
							uid_2 = my_data.op_user_id;
						if ( win == 'WHITE'){
							var winner_uid = my_data.user_id;
						}
						else if ( win == 'BLACK'){
							var winner_uid = my_data.op_user_id;
						}
					}
					else if ( my_data.game_data.side == 'op'){
						var uid_1 = my_data.user_id,
							uid_2 = my_data.op_user_id;
						if ( win == 'WHITE'){
							var winner_uid = my_data.op_user_id;
						}
						else if ( win == 'BLACK'){
							var winner_uid = my_data.user_id;
						}
					}
					
					game.get_percent_play_with_game_id( data.game_id, function( get_row ){
						
						var percent = get_row[0].percent_play;
						
						var my_amount = my_data.game_data.amount;
						var amount = ( my_amount * 2 ) - ( (my_amount * percent) / 100 );
						
						my_data.game_data.amount = amount;
						op_data.game_data.amount = amount;
						
						var my_filter = { token: my_data.token},
							my_update = { win:winner_uid, status:15, game_data: my_data.game_data},
							op_filter_1 = { token: op_data.token},
							op_update = { win:winner_uid, status:15, game_data: op_data.game_data};

						game.update_online_gamer( my_filter, my_update,function(result){

							game.update_online_gamer( op_filter_1, op_update,function(result){

							})
						})
					})
					
					
					
					
					
				}
				else{
				
					var new_places = convert_array_to_place( place_color, place_value);
				
					game_data.places = new_places;
					game_data.moves = data.moves;

					if ( game_data.time == 'WHITE' ){
						game_data.time = 'BLACK';
					}
					else if ( game_data.time == 'BLACK'){
						game_data.time = 'WHITE';
					}

					var update = { game_data: game_data,
								  status: 7,
								 };
					game.update_online_gamer( {token: data.token}, update,function(result){

						op_data.game_data.places = new_places;
						op_data.game_data.time = game_data.time;

						game.update_online_gamer( op_filter, {game_data: op_data.game_data, status: 5}, function(result){

						})
					});
				}
				
				
			}
			
			
			
			
		});
		
			
//		forEach( moves in data.moves ){
//			
//		}
		
		game_data = { moves: data.moves, amount: amount};
		
		})
		
	})

}

function affiliate ( user_id, amount ){
	
	game.get_casino_affiliate( user_id, function(row){
			
			
			if ( row.invite_user_id != undefined ){
				
				var price = ((amount / 2) * row.percent.value ) / 100;
				
				game.update_user_cash( row.invite_user_id, price, 6, '9', function( results){

				})
			}
			
								  
								  
		});
	
	
}


function status_16 ( data, callback ){
	
	game.get_percent_play_with_game_id( data.game_id, function( get_row ){
						
			var percent = get_row[0].percent_play;
						
			var my_amount = data.game_data.amount;
			var amount = ( my_amount * 2 ) - ( (my_amount * percent) / 100 );
						
			data.game_data.amount = amount;
						
			var my_filter = { token: data.token},
				my_update = { win:data.user_id, status:15, game_data: data.game_data};
		
			game.update_online_gamer( my_filter, my_update,function(result){
				});
		
			affiliate( data.op_user_id, amount);
			
			})
					
	
	
	
}

function status_17 ( data, callback ){
	
	var filter = { token: data.token };
	
	game.get_data_row( filter, function(my_data){
		
		
		if ( my_data.status != 2 ){
			
			var my_update = {status: 2};
			
			
//			game.remove_online_gamer( filter, function(result){
	//			
	//		});

			var op_filter = { 
				user_id: my_data.op_user_id, 
				game_id: my_data.game_id, 
				op_user_id: my_data.user_id
			};
			var op_update = { status: 16};


			game.update_online_gamer( filter, my_update,function(result){
				callback(result);
					});
			
			game.update_online_gamer( op_filter, op_update,function(result){
				callback(result);
					});
			
			}
			
		})

			
		
//		
		
	
	
}


function status_15 ( data, callback){
	
	var amount = data.game_data.amount;
	
	
	if ( data.win == data.user_id ){
		
		game.update_user_cash( data.user_id, amount, 6, '8', function( results){
			
		})
	}
	else if ( data.win != data.user_id ){
		
		affiliate( data.user_id, amount );
//		game.get_casino_affiliate( data.user_id, function( row){
//			
//			
//			if ( row.invite_user_id != undefined ){
//				
//				var price = ((amount / 2) * row.percent.value ) / 100;
//				
//				game.update_user_cash( row.invite_user_id, price, '06', function( results){
//
//				})
//			}
//			
//								  
//								  
//		});
		
	}
	
	callback({
				command	: 'win',
				uid_1	: data.user_id,
				uid_2 	: data.op_user_id,
				winner_uid : data.win,
				amount	: amount,
				chips_1 : 100000,
				chips_2 : 100000,
			});
	
	game_data = {};
	var my_filter = { token: data.token},
		my_update = { status:2, game_data: game_data};
				
	game.update_online_gamer( my_filter, my_update,function(result){
		});
	
	
}


function reset_data_backgammon ( filter ){
	
	
	update = {
		op_user_id 	: ' ',
		win			: ' ',
		status		: 2	
		
	}
	
	game.update_online_gamer( filter, update,function(result){
		});
	
}
module.exports.find_white = function find_white( data ){
	
	return data == 'W';
}



module.exports = {
	status_5: status_5,
	status_6: status_6,
	status_15: status_15,
	status_16: status_16,
	status_17: status_17,
    convert_place_to_array: convert_place_to_array,
	convert_array_to_place: convert_array_to_place,
	move_selected: move_selected,
	check_win: check_win,
};



