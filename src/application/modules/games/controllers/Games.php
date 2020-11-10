<?php
require_once APPPATH . 'config/db.php';
class Games extends Public_Controller{

public function index(){
$db = new DB();
		$casino_games = $db->select('casino','*','status','1');
				 /////////////////////////////
		
		$casino_games = $db->multi_select('casino','*',['status'],['1'],'sort ASC limit 9');
		
		usort($casino_games,'multiarray_sort');

        $this->smart->assign([
            'matches' => array_slice($finalObject , 0 , 15) ,
//			'matches' => array() ,
//            'resultsToday' => $resultsToday,
			'userTest' => $this->getUser(),
			'slideshow' => $slideshow,
			'casino_games' => $casino_games,
        ]);
               $this->smart->view('casino');  
        }
		}

?>