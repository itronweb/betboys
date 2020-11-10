<?php 

require_once('../application/config/db.php');


class ConfigGoalServe{
	
	
	/////////////////////////////
	public $inplay_sport;
	public $upcoming_sport;
	public $result_sport;
	public $upcoming_soccer_array;
	public $soccer_array = [ 'soccer', 'tennis', 'basket' ];
//	public $upcoming_soccer_array = [ 'soccer', 'tennis', 'basket', 'hockey', 'handball', 'volleyball', 'football' ,'baseball', 'cricket', 'rugby', 'boxing', 'esports', 'futsal' ];
//	 
	////////// URL /////////////
	
	public $soccer_upcoming_url = "http://www.goalserve.com/getfeed/{api}/getodds/soccer?cat=soccer_10&date_start={date_start}";
	
	//public $soccer_inplay_url = "http://inplay.goalserve.com/inplay-{soccer}.json";
	
	 public $soccer_inplay_url = "http://sezarserver.ir/api/sport/inplay.php?sport={soccer}";
	
	public $xml_results_home = "http://www.goalserve.com/getfeed/{api}/{soccer}/home";
	public $xml_results_day = "http://www.goalserve.com/getfeed/{api}/{soccer}/d-{day}";
	
	// public $json_result = "http://inplay.goalserve.com/results/{date}/{id}.json";
	public $json_result = "http://sezarserver.ir/api/sport/resaults/{date}/{id}.json";
	
	
	
	
	/*     */	
	public $results_status = ['ft', 'susp.', 'finished', 'cancelled', 'retired', 'interrupted', 'after over time'];
	/*     */	
	public $upcoming_status = ['not started', 'ns'];
	
	public $inplay_stats = ['1st half', '2nd half', 'set 1', 'set 2', 'set 3', 'set 4', 'set 5', '1st quarter', '2nd quarter', '3rd quarter', '4th quarter', '5th quarter', 'paused', 'not started'];
	/*     */
	public $change_label_soccer = [
		'Match Winner' => '1x2',
		'Fulltime Result' => '1x2',
		'Fulltime Result' => '1x2',
		'Goals Over/Under' =>'Over/Under',
		'3-Way Handicap' => 'Europian Handicap',  
		'3_way_handicap' => 'Europian Handicap',
		'Double Chance - 1st Half' => 'Double Chance 1st Half',
		'Double Chance - 2nd Half' => 'Double Chance 2nd Half',
		'Both Teams To Score - 1st Half' => 'Both Teams To Score 1st Half',
		'Both Teams To Score - 2nd Half' => 'Both Teams To Score 2nd Half',
		'Total - Home' => 'Total Home',
		'Total - Away' => 'Total Away',
		
	];
	
	public $change_label_tennis = [
		'Home/Away' => '1x2',
		'Match Winner' => '1x2',
		'Over/Under (1st Set)' => 'Over Under 1st Set',
	];
	
	public $change_label_hockey = [
		'3Way Result' => '1x2',
	];
	
	public $change_label_basket = [
		'3Way Result' => '1x2',
		'To Win Match' => '1x2',
		
		'3Way Result - 1st Qtr'					=> '3Way Result 1st Qtr',
		'3Way Result - 2nd Qtr'					=> '3Way Result 1st Qtr',
		'3Way Result - 3rd Qtr'					=> '3Way Result 1st Qtr',
		'3Way Result - 4th Qtr'					=> '3Way Result 1st Qtr',
		'Home Team Total Goals(1st Half)'		=> 'Home Team Total Goals 1st Half',
		'Away Team Total Goals(1st Half)' 		=> 'Away Team Total Goals 1st Half',
		'Home Team Total Goals(2nd Half)' 		=> 'Home Team Total Goals 2nd Half',
		'Away Team Total Goals(2nd Half)'		=> 'Away Team Total Goals 2nd Half',
		'Home Team Total Points (1st Quarter)' 	=> 'Home Team Total Points 1st Quarter',
		'Home Team Total Points (2nd Quarter)' 	=> 'Home Team Total Points 2nd Quarter',
		'Home Team Total Points (3rd Quarter)' 	=> 'Home Team Total Points 3rd Quarter',
		'Home Team Total Points (4th Quarter)' 	=> 'Home Team Total Points 4th Quarter',
		'Away Team Total Points (1st Quarter)' 	=> 'Away Team Total Points 1st Quarter',
		'Away Team Total Points (2nd Quarter)' 	=> 'Away Team Total Points 2nd Quarter',
		'Away Team Total Points (3rd Quarter)' 	=> 'Away Team Total Points 3rd Quarter',
		'Away Team Total Points (4th Quarter)' 	=> 'Away Team Total Points 4th Quarter',
		'Odd/Even (1st Half)' 					=> 'Odd Even 1st Half',
		'Odd/Even (2nd Half)' 					=> 'Odd Even 2nd Half',
		'Odd/Even (1st Quarter)' 				=> 'Odd Even 1st Quarter',
		'Odd/Even (2nd Quarter)' 				=> 'Odd Even 2nd Quarter',
		'Odd/Even (3rd Quarter)' 				=> 'Odd Even 3rd Quarter',
		'Odd/Even (4th Quarter)' 				=> 'Odd Even 4th Quarter',
		'Home Odd/Even' 						=> 'Home Odd Even',
		'Away Odd/Even' 						=> 'Away Odd Even',
		'Home Odd/Even (1st Half)' 				=> 'Home Odd Even 1st Half',
		'Home Odd/Even (2nd Half)' 				=> 'Home Odd Even 2nd Half',
		'Away Odd/Even (1st Half)' 				=> 'Away Odd Even 1st Half',
		'Away Odd/Even (2nd Half)' 				=> 'Away Odd Even 2nd Half',
		'Home Odd/Even (1st Quarter)' 			=> 'Home Odd Even 1st Quarter',
		'Home Odd/Even (2nd Quarter)' 			=> 'Home Odd Even 2nd Quarter',
		'Home Odd/Even (3rd Quarter)' 			=> 'Home Odd Even 3rd Quarter',
		'Home Odd/Even (4th Quarter)' 			=> 'Home Odd Even 4th Quarter',
		'Away Odd/Even (1st Quarter)' 			=> 'Away Odd Even 1st Quarter',
		'Away Odd/Even (2nd Quarter)' 			=> 'Away Odd Even 2nd Quarter',
		'Away Odd/Even (3rd Quarter)' 			=> 'Away Odd Even 3rd Quarter',
		'Away Odd/Even (4th Quarter)' 			=> 'Away Odd Even 4th Quarter',
		'Double Chance (1st Quarter)' 			=> 'Double Chance 1st Quarter',
		'Double Chance (2nd Quarter)' 			=> 'Double Chance 2nd Quarter',
		'Double Chance (3rd Quarter)' 			=> 'Double Chance 3rd Quarter',
		'Double Chance (4th Quarter)' 			=> 'Double Chance 4th Quarter',
	];
	
	public $change_label_handball = [
		'Home/Away' => '1x2',
		'3Way Result' => '1x2',
		
		'1x2 (1st Inning)' => '1x2 1st Inning',
		'1x2 (2nd Inning)' => '1x2 2nd Inning',
	];
	
	public $change_label_volleyball = [
		'Home/Away' => '1x2',
		'Over/Under (1st Set)' => 'Over Under 1st Set',
		'Over/Under (2nd Set)' => 'Over Under 2nd Set',
		'Over/Under (3rd Set)' => 'Over Under 3rd Set',
		'Over/Under (4th Set)' => 'Over Under 4th Set',
		'Over/Under (5th Set)' => 'Over Under 5th Set',
		'Total - Home'			=> 'Total Home',
		'Total - Away'			=> 'Total Away',
		'Home/Away (1st Set)'	=> 'Home Away 1st Set',
		'Home/Away (2nd Set)'	=> 'Home Away 2nd Set',
		'Home/Away (3rd Set)'	=> 'Home Away 3rd Set',
		'Home/Away (4th Set)'	=> 'Home Away 4th Set',
		'Home/Away (5th Set)'	=> 'Home Away 5th Set',
		'Odd/Even (1st Set)'	=> 'Odd Even 1st Set',
		'Odd/Even (2nd Set)'	=> 'Odd Even 2nd Set',
		'Odd/Even (3rd Set)'	=> 'Odd Even 3rd Set',
		'Odd/Even (4th Set)'	=> 'Odd Even 4th Set',
		'Odd/Even (5th Set)'	=> 'Odd Even 5th Set',
		'Asian Handicap (Sets)'	=> 'Asian Handicap Sets',
		'Asian Handicap (1st Set)'	=> 'Asian Handicap 1st Set',
		'Asian Handicap (2nd Set)'	=> 'Asian Handicap 2nd Set',
		'Asian Handicap (3rd Set)'	=> 'Asian Handicap 3rd Set',
		'Asian Handicap (4th Set)'	=> 'Asian Handicap 4th Set',
		'Asian Handicap (5th Set)'	=> 'Asian Handicap 5th Set',
	];
	
	public $change_label_football = [
		'Home/Away' => '1x2',
	];
	
	public $change_label_baseball = [
		'Home/Away' => '1x2',
	];
	
	public $change_label_cricket = [
		'3Way Result' => '1x2',
	];
	
	public $change_label_rugby = [
		'3Way Result' => '1x2',
	];
	
	public $change_label_boxing = [
		'3Way Result' => '1x2',
	];
	
	public $change_label_esports = [
		'Home/Away' => '1x2',
	];
	
	public $change_label_futsal = [
//		'Home/Away' => '1x2',
		'3Way Result' => '1x2',
	];
	
	
	public $remove_label = ['1X2 Rest 1st Half', '1X2 Rest','Goal Line (0-2)',
							'1st Goal', 
							'2nd Goal', 
							'3rd Goal', 
							'4th Goal', 
							'5th Goal', 
							'6th Goal', 
							'7th Goal', 
							'8th Goal', 
							'9th Goal', 
							'10th Goal', 
							'11th Goal', 
							'12th Goal', 
							'13th Goal', 
							'14th Goal', 
							'15th Goal', 
						   
						   ];
	
	public $remove_label_soccer = ['1X2 Rest 1st Half', '1X2 Rest',
								   
								   
								   'Alternative Match Goals',
								   'Alternative 1st Half Goals',
								   'Alternative 3-Way Handicap',
								   '3-Way Handicap',
								   '1st Half 3-Way Handicap',
								   '2nd Half 3-Way Handicap',
								   'Alternative 1st Half 3-Way Handicap',
								   'Alternative 2nd Half 3-Way Handicap',
								  
								   'X No Bet',
								   
								   '1st Goal', 
									'2nd Goal', 
								   '3rd Goal', 
								   'Last Team to Score',
								   
								   '1st Corner', 
									'2nd Corner', 
								   '3rd Corner', 
								   'Last Corner',
								   
								   'Goal Line (0-0)',
								   'Alternative Goal Line (0-0)',
								    'Asian Handicap (0-0)',
								   'Alternative Asian Handicap (0-0)',
								   
								   'First 10 min Winner',
								   'Corners 1x2',
								   'Corners Over Under',
								   '2-Way Corners',
								   '1st Half Corners',
								   '2nd Half Corners',
								   'Alternative Total Corners',
								   'Asian Corners',
								   '1st Half Asian Corners',
								   'Total Corners',
								   'Home to Score in Both Halves',
								   'Away to Score in Both Halves',
								   '80 mins Result',
								   '50 mins Result',
								   '2nd Goal Method',
								   'To Score 2 or More',
								   '2nd Goalscorer',
								   
								  ];
	public $remove_label_tennis = ['1X2 Rest 1st Half', 
								   '1X2 Rest',
								  
									'Handicap Result',
									'Over/Under',
									'Over/Under by Games in Match',
									'Asian Handicap (Games)',
									'Over Under 1st Set',
									'Over Under 2nd Set',
									'Over Under 3rd Set',
									'Over Under 4th Set',
									'Correct Score',
									'Correct Score 1st Half',
									'Correct Score 2nd Half',
								  
									'Next Two Games - Either Game to Deuce',
									'Next Two Games - Winner',
									'Next Break of Serve',
									'Alternative Match Handicap',
								   
								   'Set Betting',
									'Tie-break',
									'Set / Match',
									'Tie-break (1st Set)',
								   
								   'Home/Away (1st Set)',
								   'Tie Break in Match',

									'Go The Distance?',
									'Tie Break in Match?',

									'Set 1 Winner',
									'Set 2 Winner',
									'Set 3 Winner',
									'Set 4 Winner',
									'Set 5 Winner',

									'Total Games in Set 1',
									'Total Games in Set 2',
									'Total Games in Set 3',
									'Total Games in Set 4',
									'Total Games in Set 5',
									'Total Games in Set 6',

									'Set 1 Score',
									'Set 2 Score',
									'Set 3 Score',
									'Set 4 Score',
									'Set 5 Score',
									'Set 6 Score',

									'Total Games in Match',
									'Match Handicap',
									'Alternative Total Games in Match',
									'Tie Break in Match',
									'Home Games Won',
									'Away Games Won',


									'1st Game Winner',
									'2nd Game Winner',
									'3rd Game Winner',

									'1st Game Score',
									'2nd Game Score',
									'3rd Game Score',
									'4th Game Score',								   

									'1st Game to Deuce',
									'2nd Game to Deuce',
									'3rd Game to Deuce',
									'4th Game to Deuce',
								   
								   'Point Betting - 1st Game',
								   'Point Betting - 2nd Game',
								   'Point Betting - 3rd Game',
								   
								   '1st Game Total Points',
								   '2nd Game Total Points',
								   '3rd Game Total Points',



									'Point Winner - Point 1, 1st Game',
									'Point Winner - Point 2, 1st Game',
									'Point Winner - Point 3, 1st Game',
									'Point Winner - Point 4, 1st Game',
									'Point Winner - Point 5, 1st Game',
									'Point Winner - Point 6, 1st Game',
								    'Point Winner - Point 1, 2nd Game',
									'Point Winner - Point 2, 2nd Game',
									'Point Winner - Point 3, 2nd Game',
									'Point Winner - Point 4, 2nd Game',
									'Point Winner - Point 5, 2nd Game',
									'Point Winner - Point 6, 2nd Game',
									'Point Winner - Point 1, 3rd Game',
									'Point Winner - Point 2, 3rd Game',
									'Point Winner - Point 3, 3rd Game',
									'Point Winner - Point 4, 3rd Game',
									'Point Winner - Point 5, 3rd Game',
									'Point Winner - Point 6, 3rd Game',
								   	

								   'Match Total Games Odd/Even',								  
								   'Match Result and Total Games',
								   
								   "1st Game to Have Break Point",								  
								   "2nd Game to Have Break Point",								  
								   "3rd Game to Have Break Point",	
								   
								   "Win in Straight Sets",								  
								   "Setcast",								  
								   "Home to Win a Set",							  
								   "Away to Win a Set",					  
								   "Home to Win From Behind in Sets",					  
								   "Away to Win From Behind in Sets",					  
								   "Double Result",					  
								   "Total Sets",		
								   
								   "16th Game Total Points",					  
								   "16th Game to Have Break Point",					  
								   "Point Betting - 16th Game",					  
								   "16th Game Winner",					  
								   "16th Game to Deuce",					  
								   "16th Game Score",					  
								  ];
	
	public $remove_label_basket = [
								   '1X2 Rest 1st Half',
								   '1X2 Rest', 
								   '1x2 Margin',
								   
								   'Total 2-Way',
								   'Handicap 2-Way',

								    'Will There Be Overtime?',
								    'Winning Margin',
								    'Winning Margin 3-Way',
								    'Home Winning Margin (14-Way)',
								    'Home Winning Margin (12-Way)',
								    'Away Winning Margin (14-Way)',
		
									
		                            '1st Half Total Odd/Even',
		
								    'Overtime',
									'over/under',
									'Over/Under',
									'Over/Under 1st Qtr',
									'Over/Under 2nd Qtr',
									'Over/Under 3rd Qtr',
									'Over/Under 4th Qtr',
									'Over/Under 1st Half',
									'Over/Under 2nd Half',

								   'Handicap 2-Way',
								   'Total 2-Way',
								   'Total - Home',
								   'Total - Away',

								   'Double Result',
								   'Double Chance',
								   'Double Chance - 1st Half',
								   'Double Chance - 1st Half',
								   'Double Chance - 2nd Half',
								   'Double Chance 1st Quarter',
								   'Double Chance 2nd Quarter',
								   'Double Chance 3rd Quarter',
								   'Double Chance 4th Quarter',
								   'Double Chance (1st Quarter)',
								   'Double Chance (2nd Quarter)',
								   'Double Chance (3rd Quarter)',
								   'Double Chance (4th Quarter)',

								    'Asian Handicap',
								    'Asian Handicap (1st Half)',
								    'Asian Handicap (2nd Half)',
									'Asian Handicap First Half',
								    'Asian Handicap 1st Qtr',
									'Asian Handicap 2nd Qtr',
									'Asian Handicap 3rd Qtr',
									'Asian Handicap 4th Qtr',
								   
									'Highest Scoring Quarter',
									'Highest Scoring Half',
								   
								    '3Way Result 1st Qtr',
									'3Way Result 2nd Qtr',
									'3Way Result 3rd Qtr',
									'3Way Result 4th Qtr',
								    '3Way Result - 1st Qtr',
									'3Way Result - 2nd Qtr',
									'3Way Result - 3rd Qtr',
									'3Way Result - 4th Qtr',
									
									'1st Half 3Way Result',
									'2nd Half 3Way Result',
									'3rd Half 3Way Result',
									
								   'Home Team Total Goals 1st Half',
								   'Home Team Total Goals 2nd Half',
								   'Home Team Total Goals(1st Half)',
								   'Home Team Total Goals(2nd Half)',
		
								   'Away Team Total Goals 1st Half',
								   'Away Team Total Goals 2nd Half',
								   'Away Team Total Goals(1st Half)',
								   'Away Team Total Goals(2nd Half)',
								   
								   'Home Team Total Points (1st Quarter)',
								   'Home Team Total Points (2nd Quarter)',
								   'Home Team Total Points (3rd Quarter)',
								   'Home Team Total Points (4th Quarter)',
								   'Home Team Total Points 1st Quarter',
								   'Home Team Total Points 2nd Quarter',
								   'Home Team Total Points 3rd Quarter',
								   'Home Team Total Points 4th Quarter',
		
								   'Away Team Total Points (1st Quarter)',
								   'Away Team Total Points (2nd Quarter)',
								   'Away Team Total Points (3rd Quarter)',
								   'Away Team Total Points (4th Quarter)',
								   'Away Team Total Points 1st Quarter',
								   'Away Team Total Points 2nd Quarter',
								   'Away Team Total Points 3rd Quarter',
								   'Away Team Total Points 4th Quarter',
								   
		
								   'HT/FT (Including OT)',
								   'Odd/Even (Including OT)',
								   'Odd/Even 1st Half',
								   'Odd Even 2nd Half',
								   'Odd/Even (2nd Half)',
								   'Odd Even 1st Quarter',
								   'Odd Even 2nd Quarter',
								   'Odd Even 3rd Quarter',
								   'Odd Even 4th Quarter',
								   'Odd/Even (1st Quarter)',
								   'Odd/Even (2nd Quarter)',
								   'Odd/Even (3rd Quarter)',
								   'Odd/Even (4th Quarter)',
								   
								   'Home Odd Even',
								   'Home Odd Even 1st Half',
								   'Home Odd Even 2nd Half',
								   'Home Odd/Even (1st Half)',
								   'Home Odd/Even (2nd Half)',
								   'Home Odd Even 1st Quarter',
								   'Home Odd Even 2nd Quarter',
								   'Home Odd Even 3rd Quarter',
								   'Home Odd Even 4th Quarter',
								   'Home Odd/Even (1st Quarter)',
								   'Home Odd/Even (2nd Quarter)',
								   'Home Odd/Even (3rd Quarter)',
								   'Home Odd/Even (4th Quarter)',
									
								   'Away Odd Even',
								   'Away Odd/Even (1st Half)',
								   'Away Odd/Even (2nd Half)',
								   'Away Odd Even 1st Half',
								   'Away Odd Even 2nd Half',
								   'Away Odd Even 1st Quarter',
								   'Away Odd Even 2nd Quarter',
								   'Away Odd Even 3rd Quarter',
								   'Away Odd Even 4th Quarter',
								   'Away Odd/Even 1st Quarter',
								   'Away Odd/Even 2nd Quarter',
								   'Away Odd/Even 3rd Quarter',
								   'Away Odd/Even 4th Quarter',
								   'Away Odd/Even (1st Quarter)',
								   'Away Odd/Even (2nd Quarter)',
								   'Away Odd/Even (3rd Quarter)',
								   'Away Odd/Even (4th Quarter)',
								   
								   '1st Half - Handicap',
								   '2nd Half - Handicap',
								   
								    '1st Half - Total (2-Way)',
								    '2nd Half - Total (2-Way)',
		                            '1st Half - Total (3-Way)',
									'2nd Half - Total (3-Way)',
								   
								    '1st Half - Winner (2-Way)',
								    '2nd Half - Winner (2-Way)',
									'1st Half - Winner (3-Way)',
									'2nd Half - Winner (3-Way)',
								   
								   '1st Quarter - Winner (2-Way)',
								   '2nd Quarter - Winner (2-Way)',
								   '3rd Quarter - Winner (2-Way)',
								   '4th Quarter - Winner (2-Way)',
								   
								   '1st Quarter - Total Odd/Even',
								   '2nd Quarter - Total Odd/Even',
								   '3rd Quarter - Total Odd/Even',
								   '4th Quarter - Total Odd/Even',
								   
								   '1st Half - Race to 5 Points',
								   '1st Half - Race to 10 Points',
								   '1st Half - Race to 15 Points',
								   '1st Half - Race to 20 Points',
								   '1st Half - Race to 25 Points',
								   '1st Half - Race to 30 Points',
								   
								   '2nd Half - Race to 5 Points',
								   '2nd Half - Race to 10 Points',
								   '2nd Half - Race to 15 Points',
								   '2nd Half - Race to 20 Points',
								   '2nd Half - Race to 25 Points',
								   '2nd Half - Race to 30 Points',
								   
								   '1st Quarter - Race to 5 Points',
								   '1st Quarter - Race to 10 Points',
								   '1st Quarter - Race to 15 Points',
								   '1st Quarter - Race to 20 Points',
								   '1st Quarter - Race to 20 points',
								   '1st Quarter - Race to 25 Points',
								   '1st Quarter - Race to 30 Points',
								   
								   '2nd Quarter - Race to 5 Points',
								   '2nd Quarter - Race to 10 Points',
								   '2nd Quarter - Race to 15 Points',
								   '2nd Quarter - Race to 20 Points',
								   '2nd Quarter - Race to 20 points',
								   '2nd Quarter - Race to 25 Points',
								   '2nd Quarter - Race to 30 Points',
								   
								   '3rd Quarter - Race to 5 Points',
								   '3rd Quarter - Race to 10 Points',
								   '3rd Quarter - Race to 15 Points',
								   '3rd Quarter - Race to 20 points',
								   '3rd Quarter - Race to 20 Points',
								   '3rd Quarter - Race to 25 Points',
								   '3rd Quarter - Race to 30 Points',
								   
								   '4th Quarter - Race to 5 Points',
								   '4th Quarter - Race to 10 Points',
								   '4th Quarter - Race to 15 Points',
								   '4th Quarter - Race to 20 Points',
								   '4th Quarter - Race to 20 points',
								   '4th Quarter - Race to 25 Points',
								   '4th Quarter - Race to 30 Points',
								   
									'1st Quarter - Total (3-Way)',
									'2nd Quarter - Total (3-Way)',
									'3rd Quarter - Total (3-Way)',
									'4th Quarter - Total (3-Way)',

									'1st Quarter - Handicap',
									'2nd Quarter - Handicap',
									'3rd Quarter - Handicap',
									'4th Quarter - Handicap',

									'1st Quarter - Total (2-Way)',
									'2nd Quarter - Total (2-Way)',
									'3rd Quarter - Total (2-Way)',
									'4th Quarter - Total (2-Way)',
        
								   
									'1st Quarter - Total Odd/Even',
									'2nd Quarter - Total Odd/Even',
									'3rd Quarter - Total Odd/Even',
									'4th Quarter - Total Odd/Even',

									'1st Quarter - Team Totals',
									'2nd Quarter - Team Totals',
									'3rd Quarter - Team Totals',
									'4th Quarter - Team Totals',

									'1st Half - Total Odd/Even',
									'2nd Half - Total Odd/Even',

									'1st Half - Team Totals',
									'2nd Half - Team Totals',

									'Team Totals',
									'Game Total Odd/Even',

									'1st Quarter - Winner (3-Way)',
									'2nd Quarter - Winner (3-Way)',
									'3rd Quarter - Winner (3-Way)',
									'4th Quarter - Winner (3-Way)',

									'1st Quarter Margin of Victory',
									'2nd Quarter Margin of Victory',
									'3rd Quarter Margin of Victory',
									'4th Quarter Margin of Victory',
								   

								   
								  ];
	
	public $remove_label_rugby = ['1X2 Rest 1st Half', 
								  '1X2 Rest',
								  'Asian Handicap',
									'HT/FT Double',
									'Over/Under',
								  
									'Handicap Result',
									'Highest Scoring Half',
									'Asian Handicap First Half',
									'Asian Handicap Second Half',
								 ];
	
	public $remove_label_volleyball = [ '1X2 Rest 1st Half', 
									    '1X2 Rest',
									    'HT/FT Double',
										'Over/Under by Games in Match',
									    'Asian Handicap (Games)',
									    'HT/FT Double',
									    'Over/Under by Games in Match',
									   
									    'Correct Score',
									    'Total Home',
									    'Total Away',
									    'Odd/Even',
									    'Odd Even 1st Set',
									    'Odd Even 2nd Set',
									    'Odd Even 3rd Set',
									    'Odd Even 4th Set',
									    'Odd Even 5th Set',
									    'Over Under',
									    'Over Under 1st Set',
									    'Over Under 2nd Set',
									    'Over Under 3rd Set',
									    'Over Under 4th Set',
									    'Over Under 5th Set',
									    'Home Away',
									    'Home Away 1st Set',
									    'Home Away 2nd Set',
									    'Home Away 3rd Set',
									    'Home Away 4th Set',
									    'Home Away 5th Set',
									    'Asian Handicap',
									    'Asian Handicap 1st Set',
									    'Asian Handicap 2nd Set',
									    'Asian Handicap 3rd Set',
									    'Asian Handicap 4th Set',
									    'Asian Handicap 5th Set',
									    'Asian Handicap Sets',
									    'Asian Handicap (Games)',
									  ];
	
	public $remove_label_handball = ['1X2 Rest 1st Half', 
									 '1X2 Rest',
									'Asian Handicap',
									'Asian Handicap First Half',
									'Asian Handicap Second Half',
									'Asian Handicap (1st Half)',
									'Asian Handicap (2nd Half)',
									'Odd/Even',
									'Odd/Even 1st Half',
									'Odd/Even 2nd Half',
									'Odd/Even (1st Half)',
									'Odd/Even (2nd Half)',
									'Odd/Even (Including OT)',
									 'HT/FT (Including OT',
									'A Run (1st Inning)',
									'First Team To Score',
									'Team with Highest Scoring Innings',
									'Total Hits',
									'HT/FT (Including OT)',
									 
									'1st Half 3Way Result',
									'2nd Half 3Way Result',
									'Over/Under',
									'Over/Under 1st Half',
									'Over/Under 2nd Half',
									'HT/FT Double',
									'Highest Scoring Half',
									'Double Chance',
									'Double Chance - 1st Half',
									'Double Chance - 2nd Half',
									];
	
	public $remove_label_cricket = ['1X2 Rest 1st Half', '1X2 Rest', 
									'Most Match Sixes',
									'To Win the Toss',
									'Highest Openning Partnership',
									'Team to Make Highest 1st 6 Overs Score',
									'1st Over Total Runs',
									'A Fifty to be Scored in the Match',
									'A Hundred to be Scored in the Match',
									'1st Wicket Method',
								   ];
	
	public $remove_label_esports = ['1X2 Rest 1st Half', '1X2 Rest',
									'Home/Away (map 1)',
									'Home/Away (map 2)',
									'Home/Away (map 3)',
									'Total Maps',
									'Maps Handicap',
									'Total - Home',
									'Total - Away',
									"Correct Score",
									'Asian Handicap',
									'Asian Handicap (map 1)',
									'Asian Handicap (map 2)',
									'Asian Handicap (map 3)',
									'Total Rounds (map 1)',
									'Total Rounds (map 2)',
									'Total Rounds (map 3)',
								   ];
	
	public $remove_label_baseball= ['1X2 Rest 1st Half', 
									'1X2 Rest', 
									'Asian Handicap',
									'Odd/Even (Including OT)',
									'HT/FT (Including OT)',
									'A Run (1st Inning)',
									'1x2 (1st Inning)',
									'Total Hits',
									'Team with Highest Scoring Innings',
									'First Team To Score',
									'Second Team To Score',
									'Over/Under',
								   ];
	
	public $remove_label_football= ['1X2 Rest 1st Half', '1X2 Rest'];
	
	public $remove_label_futsal= ['1X2 Rest 1st Half', '1X2 Rest',
								  
								  	'Home/Away (map 1)',
									'Home/Away (map 2)',
									'Home/Away (map 3)',
									'Total Maps',
									'Maps Handicap',
								  
								  	'Asian Handicap',
									'Asian Handicap (1st Half)',
									'Asian Handicap (2nd Half)',
									'Asian Handicap First Half',
									'Asian Handicap Second Half',
									'Double Chance',
									'Double Chance - 1st Half',
									'Double Chance - 2nd Half',
									'Double Chance - 2nd Half',
									'Total - Home',
									'Total - Away',
									'1st Half 3Way Result',
									'2nd Half 3Way Result',
								  	'Over/Under',
									'Over/Under 1st Half',
									'Over/Under 2nd Half',
									'HT/FT Double',
									'Highest Scoring Half',
									'Double Chance',
									'Double Chance - 1st Half',
									'Double Chance - 2nd Half',
									'Both Teams To Score',
									'Odd/Even',
									'Odd/Even (1st Half)',
									'Odd/Even (2nd Half)',
									'Win to Nil',
									'Win to Nil - Home',
									'Win to Nil - Away',
									'Both Teams To Score',
									'Both Teams To Score - 1st Half',
									'Both Teams To Score - 2nd Half',
									'Home win both halves',
									'Away win both halves',
									'Result/Total Goals',
									'Home team will score in both halves',
									'Away team will score in both halves',
									'To Score in Both Halves',
									
								  
								  
								  
								  
								 ];
	
	public $remove_label_hockey= ['1X2 Rest 1st Half', 
								  '1X2 Rest',
								  
								  'Asian Handicap',
								  'Over/Under',
								  'Both Teams To Score',
								  'Handicap Result',
								  'Correct Score',
								  'Highest Scoring Half',
								  'Double Chance',
								  'Team To Score First',
								  'Team To Score Second',
								  'Odd/Even',
								  'Odd/Even (Including OT)',
								  
								  'Over/Under',
								  'Over/Under (1st Period)',
								  'Over/Under (2nd Period)',
								  'Over/Under (3rd Period)',
								  'Over/Under (4th Period)',
								  
								  'Both Teams To Score (1st Period)',
								  'Both Teams To Score (2nd Period)',
								  'Both Teams To Score (3rd Period)',
								  
								  '3Way Result (1st Period)',
								  '3Way Result (2st Period)',
								  '3Way Result 3rdPeriod)',
								  
								  'Asian Handicap (1st Period)',
								  'Asian Handicap (2nd Period)',
								  'Asian Handicap (3rd Period)',
								  
								  '1x2 (1st Period)',
								  '1x2 (2nd Period)',
								  '1x2 (3rd Period)',
								  
								  'Double Chance (3rd Period)',
								  'Double Chance (2nd Period)',
								  'Double Chance (1st Period)',
								  
								  'Correct Score (1st Period)',
								  'Correct Score (2nd Period)',
								  'Correct Score (3rd Period)',
								  
								  'Both Teams To Score (1st Period)',
								  'Both Teams To Score (2nd Period)',
								  'Both Teams To Score (3rd Period)',
								 
								 
								 
								 ];
	public $remove_label_boxing= ['1X2 Rest 1st Half', '1X2 Rest'];
	
	
	
	/*     */	
	public $has_total = ['Goals Over/Under',
				  'Over/Under',
				  'Over/Under by Games in Match',
				  'Over/Under by Games (2nd Set)',
				  'Over/Under (1st Set)',
				  'Corners Over Under',
				  'Goals Over/Under 1st Half',
				  'Total - Home','Total - Away',
				  'Result/Total Goals',
				  'Goals Over/Under 2nd Half',
					
						 
						 'Total Home',
						 'Total Away',
						 'Over Under 1st Set',
						 'Over Under 2nd Set',
						 'Over Under 3rd Set',
						 'Over Under 4th Set',
						 'Over Under 5th Set',
						 'Over Under 1st Half',
						 'Over Under 2nd Half',
						 /* basket */
				  'Over/Under 1st Half',
				  'Over/Under 2nd Half',
				  'Over/Under 1st Qtr',
				  'Over/Under 2nd Qtr',
				  'Over/Under 3rd Qtr',
				  'Over/Under 4th Qtr',
				  'Home Team Total Goals(1st Half)',
				  'Away Team Total Goals(1st Half)',
				  'Home Team Total Goals(2nd Half)',
				  'Away Team Total Goals(2nd Half)',
				  'Home Team Total Points (1st Quarter)',
				  'Away Team Total Points (1st Quarter)',
				  'Home Team Total Points (2nd Quarter)',
				  'Away Team Total Points (2nd Quarter)',
				  'Home Team Total Points (3rd Quarter)',
				  'Away Team Total Points (3rd Quarter)',
				  'Home Team Total Points (4th Quarter)',
				  'Away Team Total Points (4th Quarter)',
						 
						 'Home Team Total Goals 1st Half',
						 'Away Team Total Goals 1st Half',
						 'Home Team Total Goals 2nd Half',
						 'Away Team Total Goals 2nd Half',
						 'Home Team Total Points 1st Quarter',
						 'Away Team Total Points 1st Quarter',
						 'Home Team Total Points 2nd Quarter',
						 'Away Team Total Points 2nd Quarter',
						 'Home Team Total Points 3rd Quarter',
						 'Away Team Total Points 3rd Quarter',
						 'Home Team Total Points 4th Quarter',
						 'Away Team Total Points 4th Quarter',
						 
						 
						 
						 
						 
						 
						 'Over/Under (1st Period)',
						 'Over/Under (2nd Period)',
						 'Over/Under (3rd Period)',
						];
	/*    */
	public $has_handicap = ['Asian Handicap',
							'Handicap_Result',
							'Handicap Result',
							'Handicap Result 1st Half',
							'Asian Handicap First Half',
							
							
							'Asian Handicap (1st Set)',
							'Asian Handicap (2nd Set)',
							'Asian Handicap (Games)',
							'Asian Handicap (Sets)',
							
							/* basket */
							'Asian Handicap 1st Qtr',
							'Asian Handicap 2nd Qtr',
							'Asian Handicap 3rd Qtr',
							'Asian Handicap 4th Qtr',
							'Asian Handicap (2nd Half)',
							
							/* esports */
							'Maps Handicap',
							
							/* volleyball */
							'Asian Handicap Sets',
							'Asian Handicap 1st Set',
							'Asian Handicap 2nd Set',
							'Asian Handicap 3rd Set',
							'Asian Handicap 4th Set',
							'Asian Handicap 5th Set',
							
							
							
							
							
							'Asian Handicap (1st Period)',
							'Asian Handicap (2nd Period)',
							'Asian Handicap (3rd Period)',
							
							
						   ];
	
	public $defualt_bookmaker;
	
	public $defualt_bookmaker_soccer = ['bet365'];
	public $defualt_bookmaker_tennis = ['bet365'];
	public $defualt_bookmaker_basket = ['marathon'];
	public $defualt_bookmaker_hockey = ['10bet'];
	public $defualt_bookmaker_handball = ['bet365'];
	public $defualt_bookmaker_volleyball = ['bet365'];
	public $defualt_bookmaker_football = ['10bet'];
	public $defualt_bookmaker_baseball = ['bet365'];
	public $defualt_bookmaker_cricket = ['bet365','marathon'];
	public $defualt_bookmaker_rugby = ['bet365','10bet'];
	public $defualt_bookmaker_boxing = ['bet365','10bet', 'marathon','pncl'];
	public $defualt_bookmaker_esports = ['bet365','10bet', 'marathon','pncl'];
	public $defualt_bookmaker_futsal = ['bet365','10bet', 'marathon','pncl'];
	public function __construct(){
		$db = new DB();
		
		// Tennis
		for ( $i=1; $i<=40; $i++ ){
			for ( $j=1; $j<=40; $j++ ){
				$this->remove_label_tennis[] = "1st Point Winner ".$j."th Game";
				$this->remove_label_tennis[] = "1st Point Winner ".$j."st Game";
				$this->remove_label_tennis[] = "1st Point Winner ".$j."nd Game";
				$this->remove_label_tennis[] = "1st Point Winner ".$j."rd Game";
				$this->remove_label_tennis[] = "Point Winner - Point $i, ".$j."th Game";
				$this->remove_label_tennis[] = "Point Winner - Point $i, ".$j."st Game";
				$this->remove_label_tennis[] = "Point Winner - Point $i, ".$j."nd Game";
				$this->remove_label_tennis[] = "Point Winner - Point $i, ".$j."rd Game";
				$this->remove_label_tennis[] = "Set $i - Race to $j Games";
				$this->remove_label_tennis[] = "Set $i Tie Break Score after $j Points";
				$this->remove_label_tennis[] = $j . "th Game Score after $i points";
				$this->remove_label_tennis[] = $j . "st Game Score after $i points";
				$this->remove_label_tennis[] = $j . "nd Game Score after $i points";
				$this->remove_label_tennis[] = $j . "rd Game Score after $i points";
				$this->remove_label_tennis[] = "Set $i Race to $j Games";
				$this->remove_label_tennis[] = "Set $i Lead after $j Games";
				$this->remove_label_tennis[] = "Set $i Score after $j Games";
				$this->remove_label_tennis[] = "Set $i Tie Break Lead after $j Points";
				
			}
			$this->remove_label_tennis[] = $i . "th Game Score";
			$this->remove_label_tennis[] = $i . "th Game Winner";
			$this->remove_label_tennis[] = $i . "th Game to Deuce";
			$this->remove_label_tennis[] = "Set $i Tie Break Score";
			$this->remove_label_tennis[] = "Set $i Tie Break Winner";
			$this->remove_label_tennis[] = "Point Betting - ". $i ."th Game";
			$this->remove_label_tennis[] = $i . "th Game Total Points";
			$this->remove_label_tennis[] = $i . "th Game Server to Win to 0/15";
			$this->remove_label_tennis[] = $i . "th Game Server to Win to 0/15/30";
			$this->remove_label_tennis[] = $i . "st Game Server to Win to 0/15";
			$this->remove_label_tennis[] = $i . "st Game Server to Win to 0/15/30";
			$this->remove_label_tennis[] = $i . "st Game Winner";
			$this->remove_label_tennis[] = $i . "st Game to Deuce";
			$this->remove_label_tennis[] = $i . "st Game Score";
			$this->remove_label_tennis[] = $i . "st Game Total Points";
			$this->remove_label_tennis[] = "Point Betting - ". $i ."st Game";
			$this->remove_label_tennis[] = $i . "nd Game Server to Win to 0/15";
			$this->remove_label_tennis[] = $i . "nd Game Server to Win to 0/15/30";
			$this->remove_label_tennis[] = $i . "nd Game Winner";
			$this->remove_label_tennis[] = $i . "nd Game to Deuce";
			$this->remove_label_tennis[] = $i . "nd Game Score";
			$this->remove_label_tennis[] = $i . "nd Game Total Points";
			$this->remove_label_tennis[] = "Point Betting - ". $i ."nd Game";
			$this->remove_label_tennis[] = $i . "rd Game Server to Win to 0/15";
			$this->remove_label_tennis[] = $i . "rd Game Server to Win to 0/15/30";
			$this->remove_label_tennis[] = $i . "rd Game Winner";
			$this->remove_label_tennis[] = $i . "rd Game to Deuce";
			$this->remove_label_tennis[] = $i . "rd Game Score";
			$this->remove_label_tennis[] = $i . "rd Game Total Points";
			$this->remove_label_tennis[] = "Point Betting - ". $i ."rd Game";
			$this->remove_label_tennis[] = "Set $i to Break Serve";
			$this->remove_label_tennis[] = $i."st Game to Have Break Point";
			$this->remove_label_tennis[] = $i."nd Game to Have Break Point";
			$this->remove_label_tennis[] = $i."rd Game to Have Break Point";
			$this->remove_label_tennis[] = $i."th Game to Have Break Point";
			$this->remove_label_tennis[] = "Set $i Correct Score Group";
			$this->remove_label_tennis[] = "Set $i Correct Score Any Player";
			$this->remove_label_tennis[] = "Set $i Handicap";
			$this->remove_label_tennis[] = "Set $i Tie Break - First Mini-Break";
			$this->remove_label_tennis[] = "Set $i Tie Break - Total Points";
			$this->remove_label_tennis[] = "Set $i Tie Break Handicap";
			
		}
		 
								  
		// soccer
		for ( $i=0; $i<15; $i++){
			for( $j=0; $j<=15; $j++){
				$this->remove_label_soccer[] = "Goal Line ($i-$j)";
				$this->remove_label_soccer[] = "1st Half Goal Line ($i-$j)";
				$this->remove_label_soccer[] = "2nd Half Goal Line ($i-$j)";
				$this->remove_label_soccer[] = "Alternative Goal Line ($i-$j)";
				$this->remove_label_soccer[] = "Alternative 1st Half Goal Line ($i-$j)";
				$this->remove_label_soccer[] = "Alternative 2nd Half Goal Line ($i-$j)";
				$this->remove_label_soccer[] = "Asian Handicap ($i-$j)";
				$this->remove_label_soccer[] = "1st Half Asian Handicap ($i-$j)";
				$this->remove_label_soccer[] = "2nd Half Asian Handicap ($i-$j)";
				$this->remove_label_soccer[] = "Alternative Asian Handicap ($i-$j)";
				$this->remove_label_soccer[] = "Alternative 1st Half Asian Handicap ($i-$j)";
				$this->remove_label_soccer[] = "Alternative 2nd Half Asian Handicap ($i-$j)";
				$this->remove_label_soccer[] = "Alternative 1st Half Asian ($i-$j)";
				$this->remove_label_soccer[] = "Alternative 2nd Half Asian ($i-$j)";
			}
			$this->remove_label_soccer[] = $i . "th Goal";
			$this->remove_label_soccer[] = $i . "th Corner";
			$this->remove_label_soccer[] = "Race to $i Corners";
		}
		
//		$this->inplay_sport = $db->select( 'sports', 'name_en, name_inplay', 'is_inplay', 1 );
//		$this->upcoming_sport = $db->select( 'sports', 'name_en, name_prematch','is_upcoming', 1);
//		$this->result_sport = $db->select( 'sports', 'name_en, name_result', 'is_result', 1 );
//		$sport = $db->select( 'sports', 'name_en, default_bookmaker' );
		
		
		$db->_mysqli->where( 'is_inplay', 1);
		$this->inplay_sport = $db->_mysqli->get( 'sports', null, 'name_en, name_inplay' );
		
		$db->_mysqli->where( 'is_upcoming', 1);
		$this->upcoming_sport = $db->_mysqli->get( 'sports', null, 'name_en, name_prematch' );
		
		$db->_mysqli->where( 'is_result', 1);
		$this->result_sport = $db->_mysqli->get( 'sports', null, 'name_en, name_result' );
		
		$sport = $db->_mysqli->get( 'sports', null, 'name_en, default_bookmaker' );
		
		
		foreach ( $sport as $sport_value ){
			$bookmaker = explode('-', $sport_value['default_bookmaker']);
			foreach( $bookmaker as $bookmaker_value ){
				$this->defualt_bookmaker[$sport_value['name_en']][] = $bookmaker_value;
			}
			
		}
		
	}
	
	public function get_soccer_config ( $soccer_name ){
		
		$db = new DB();
		
//		$sport_name = $db->multi_select( 'sports', 'name_en, name_inplay', ['is_inplay', 'name_en'], [1, $soccer_name] );
//		
		$db->_mysqli->where( 'is_inplay', 1 );
		$db->_mysqli->where( 'name_en', $soccer_name );
		$sport_name = $db->_mysqli->get( 'sports', null, 'name_en, name_inplay' );
		
		if ( $db->_mysqli->count == 0 )
			return 0;
		return $sport_name[0];
		
		
	} 
	

	
}
?>