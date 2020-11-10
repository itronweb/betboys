<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Content_field_types Controller
 *
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 *
 */
class Dashboard extends Public_Controller {

    function __construct () {
        parent::__construct();
    }

    public function index () {

        $this->checkAuth(true);
        setcookie('uid', $this->user->id, time() + (86400), "/");
        setcookie('un', $this->user->username, time() + (86400), "/");
        $this->load->eloquent('bets/Bet');
        $this->load->eloquent('bets/Bet_form');
        $this->load->eloquent('payment/transaction');
		
        $totalStake = 0;
        $totalGift = 0;
        $giftCount = 0;
        $withdrawSum = 0;
        $creditSum = 0;
        $Obj = Bet::where('user_id' , $this->user->id)->get();
        foreach ( $Obj as $row ):
            $totalStake += $row->stake;
            if ( $row->status == 1 ):
                $totalGift += $row->stake;
                $giftCount++;
            endif;
        endforeach;
        $withdraw = Transaction::where(array( 'user_id' => $this->user->id , 'invoice_type' => 4 , 'status' => 1 ))->orWhere(array( 'user_id' => $this->user->id , 'invoice_type' => 1 , 'status' => 1 ))->get();
        foreach ( $withdraw as $log ):
            if ( $log->invoice_type == 4 ) {
                $withdrawSum += $log->price;
            }
            if ( $log->invoice_type == 1 ) {
                $creditSum += $log->price;
            }
        endforeach;
        $this->smart->assign(array(
            'title' => 'میزکار' ,
            'totalStake' => $totalStake ,
            'giftCount' => $giftCount ,
            'totalGift' => $totalGift ,
            'totalBetCount' => $Obj->count() ,
            'withdrawSum' => $withdrawSum ,
            'creditSum' => $creditSum ,
        ));


        $this->smart->view('dashboard');
    }
	public function getTotalStake () {
		$this->load->eloquent('bets/Bet');
		$Obj = Bet::where('user_id' , $this->user->id)->get();
		$totalStake = 0;
        foreach ( $Obj as $row ):
            $totalStake += $row->stake;
        endforeach;
		
		return($totalStake);
	}

}
