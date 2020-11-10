<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH . 'config/db.php';
/**
 * Description of Payments
 *
 *  *
 */
class Payments extends Public_Controller {

    public $MerchantID = "3793da3788071c47f03c3f6d12abb402";
	
	public $validation_rules = array(
        'credit' => array(
            ['field' => 'amount' , 'rules' => 'numeric|trim|htmlspecialchars' , 'label' => 'مبلغ' ] ,
        )
    );

    public function __construct () {
        parent::__construct();
        $this->load->eloquent('settings/Setting');
    }

	public function credit() {
		
		$companyname = '4ubets';
	//	$interface_url = "http://localhost";
		$interface_url = "http://4ubets.com";
		$base_url = $this->config->config['base_url'];
		$this->checkAuth(true);
        $this->load->library('pay');
        
		$types = $paymethod = $this->input->post('ptype');
		if(isset($types)){
			$typeArr = explode( '-', $types);
			$typeid = $typeArr['0'];
			$type = $typeArr['1'];
			$name = 'amount_' . $type;
			$amount = $this->input->post($name);
		} 
		
		
		$description = "شارژ حساب کاربری";
	
				
			/////// $type = 1 ------------> pardakht online shetab
			/////// $type = 2 -------- > pardakht online  
			/////// $type = 3 ------------> cart be cart  
			/////// $type = 42 ------------> pardakht perfect money  
			/////// $type = 52 ------------> pardakht tavasot vaseet zarinpal 
		
		
		if ( $this->formValidate(FALSE) ) {
				
			$status = false;
			$invoice_type = "1";
			if ($type == '1'){

				$db = new DB();
				$getway = $db->multi_select('gateway','*',['status', 'paymethodid','id'],['1' ,'1',"$typeid"],'sort ASC');
				$api_key = $getway[0]['api_key'];
				$domain = $getway[0]['domain'];
				$customer_card = null;

				$redirect = site_url('payment/verify-credit');

			$result_request = $this->pay->request($amount,urlencode($redirect),$api_key,$domain);
//                var_dump($result_request);
				$result_request = json_decode($result_request,1);
//                var_dump($result_request);
				if($result_request['status'] == 1){
					$status = true;
					$invoice_key = $result_request['invoice_key'];
					$go = $domain."/invoice/pay/".$invoice_key;
				}
				$transid = $api_key."-".$domain;
			}
			elseif($type == '2'){
				$redirect = "$base_url/payment/verifyZarrinpal";
				$data = ['amount' => $amount,
						'description' => $description];

				$db = new DB();
				$getway = $db->multi_select('gateway','*',['status', 'paymethodid','id'],['1' ,'2',"$typeid"],'sort ASC');
				$api_key = $getway['api_key'];
				$customer_card = null;
				$domain = $getway['domain'];

				$result_request = $this->pay->zarrinpall_request($data,$redirect,$api_key);
				if ($result_request->Status == 100) {
					$status = true;
					$invoice_key = $result_request->Authority;
					$go = "https://crown.sezarco.ir/pg/StartPay/".$invoice_key;
				}
				$transid = $api_key."-".$domain;

			}
			elseif ( $type == '3' ){
				$customer_card = $this->input->post('customer_card');
				$transid = $invoice_key = $this->input->post('pay_code');
				$description = 'شارژ حساب کاربری با روش کارت به کارت';
				$status = 3;
				$invoice_type = 20;
				$status = (empty($customer_card) || empty($invoice_key)) ? false : 3;
			}
			elseif($type == '52'){
				
				$user_id =$this->user->id;
				$token =$this->user->game_token;
				$trans_id =time()."".$user_id;
				$description = "شارژ حساب کاربری توسط درگاه واسط زرین پال";
				
				$redirect = $interface_url . "/peyment_interface/index.php?id=".$user_id."&&type=".$types."&&trans=".$trans_id."&&token=".$token;
				
				$data = ['amount' => $amount,
						'description' => $description];

				$db = new DB();
				
				$this->load->eloquent('Transaction');
				$cash = $this->__getUserCash();
				$amount = ($amount/10);
			
				Transaction::create([
					'trans_id' => $trans_id,
					'price' => $amount ,
					'invoice_type' => $invoice_type,
					'payment_id' => $paymethod,
					'cash' => $cash + $amount ,
					'user_id' => $this->user->id ,
					'pay_code' => null ,//
					'card_number' => null,//
					'description' => $description ,
				]);
				
				header("Location:".$redirect);
				var_dump($redirect);
				die();

			}elseif ( $type == '422' ){
				
				$ev_number = $this->input->post('ev_number');
				$ev_code = $this->input->post('ev_code');
				$systemurl = $this->config->config['base_url'];
				$user_id =$this->user->id ;
				$customer_card = null;
				$description = 'پرداخت توسط وچرپرفکت مانی به سایت 4ubets';
				$invoiceid = $invoice_key = "PERFECTVOUCHERID-".time().''.$type.''.$user_id; //ID-322223
				$transid = $invoice_key. "-" . $companyname;
				
				$token = $this->user->game_token;
				
				$redirect = $interface_url . "/peyment_interface/index.php?id=".$user_id."&&type=".$types."&&trans=".$transid."&&token=".$token;

				$this->load->eloquent('Transaction');
				$cash = $this->__getUserCash();
				
				$pay_code = $ev_number."_".$ev_code;
				
				Transaction::create([
					'trans_id' => $transid ,//
					'price' => 0 ,
					'invoice_type' => $invoice_type,
					'cash' => $cash ,
					'user_id' => $user_id ,
					'pay_code' => $pay_code ,//
					'payment_id' => $paymethod ,//
					'card_number' => $customer_card,
					'description' => $description ,
				]);
				
				$game_token = $this->user->game_token;
				
				header("Location:".$redirect);
				var_dump($redirect);
				die();
				
			}
			elseif($type == '42'){
				
				$user_id = $this->user->id;
				$token =$this->user->game_token;
				$description = "شارژ حساب کاربری توسط درگاه پرفکت مانی";
				$invoice_key = "PERFECTID-".time().''.$type.''.$user_id;
				$transid = $invoice_key. "-" . $companyname;
				$db = new DB();
				
				$amount_new = $db->multi_select('change_unit' , 'amount' , ['fromid' , 'toid'] , [3 ,2]);
		
				$amount_change = $amount_new[0]['amount'] * $amount ;
				$cash = $this->__getUserCash();
				$cash = $cash + $amount_change ;
				$redirect = $interface_url . "/peyment_interface/index.php?id=".$user_id."&&type=".$types."&&trans=".$transid."&&token=".$token;
				
				$this->load->eloquent('Transaction');
				
				
				Transaction::create([
					'trans_id' => $transid,
					'price' => $amount_change ,
					'invoice_type' => $invoice_type,
					'payment_id' => $paymethod,
					'cash' => $cash ,
					'user_id' => $user_id ,
					'pay_code' => $invoice_key ,//
					'card_number' => null,//
					'description' => $description ,
				]);
				
				header("Location:".$redirect);
				var_dump($redirect);
				die();
				

			}
			elseif($type == '62'){
				
				$user_id =$this->user->id;
				$token =$this->user->game_token;
				$invoice_key = "PARSIGRAMID-".time().''.$type.''.$user_id;
				$trans_id = $invoice_key. "-" . $companyname;
				$description = "شارژ حساب کاربری توسط درگاه پارسی گرام";
				
				$redirect = $interface_url . "/peyment_interface/index.php?id=".$user_id."&&type=".$types."&&trans=".$trans_id."&&token=".$token;
				
				
				$data = ['amount' => $amount,
						'description' => $description];

				$db = new DB();
				$this->load->eloquent('Transaction');
				$cash = $this->__getUserCash();
				
				Transaction::create([
					'trans_id' => $trans_id,
					'price' => $amount ,
					'invoice_type' => $invoice_type,
					'payment_id' => $paymethod,
					'cash' => $cash + $amount ,
					'user_id' => $this->user->id ,
					'pay_code' => null ,//
					'card_number' => null,//
					'description' => $description ,
				]);
				
				header("Location:".$redirect);
				var_dump($redirect);
				die();
				

			}
			elseif($type == '622'){
				
				$ev_number = $this->input->post('ev_number');
				$ev_code = $this->input->post('ev_code');
				$user_id =$this->user->id;
				$token =$this->user->game_token;
				$invoice_key = "PARSIGRAMVOUCHERID-".time().''.$type.''.$user_id; //ID-322223
				$trans_id = $invoice_key. "-" . $companyname;
				$description = "شارژ حساب کاربری توسط وچر پارسی گرام";
				$pay_code = $ev_number."_".$ev_code;
				
				$redirect = $interface_url . "/peyment_interface/index.php?id=".$user_id."&&type=".$types."&&trans=".$trans_id."&&token=".$token;
				

				$db = new DB();
				$this->load->eloquent('Transaction');
				$cash = $this->__getUserCash();
				
				Transaction::create([
					'trans_id' => $trans_id,
					'price' => 0 ,
					'invoice_type' => $invoice_type,
					'payment_id' => $paymethod,
					'cash' => $cash ,
					'user_id' => $user_id ,
					'pay_code' => $pay_code ,//
					'card_number' => null,//
					'description' => $description ,
				]);
				
				header("Location:".$redirect);
				var_dump($redirect);
				die();
				

			}
			else{
				$status = false;
			}
			if($status){
				$this->load->eloquent('Transaction');
				$cash = $this->__getUserCash();
				$amount = ($amount/10);

				
				Transaction::create([
					'trans_id' => $transid ,//
					'price' => $amount ,
					'invoice_type' => $invoice_type,
					'payment_id' => $payment_id,
					'cash' => $cash + $amount ,
					'user_id' => $this->user->id ,
					'pay_code' => $invoice_key ,//
					'payment_id' => $paymethod ,//
					'card_number' => $customer_card,
					'description' => $description ,
				]);
				if ( $status === true )
					header('location:'.$go);
				else if ( $status == 3 )
					$this->message->set_message('اطلاعات شما برای مدیریت ارسال شد' , 'success' , 'درصورت تایید تراکنش، مبلغ به حساب شما افزوده می شود' , 'dashboard')->redirect();
			}
			else{
				$this->message->set_message('مشکل در اتصال به درگاه' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
			}


			//exit();

			}

         $db = new DB();
		$getway = $db->multi_select('gateway','*',['status'],['1'],'sort ASC');
		
		$unit_amount = $this->get_change_unit_amount();
		
			$this->smart->assign(array(
				'totalStake' => $this->getTotalStake(),
				'getway' => $getway,
				'unit_amount' => $unit_amount,
				'base_unit'		=> 'تومان',
                    )
            );
		$this->smart->view('credit');

	}

	public function verify_credit () {
        $this->checkAuth(true);

		$this->load->library('pay');
        $this->load->eloquent('transaction');

        # از دیتابیس کد تراکنش و مبلغ را استخراج میکنیم
        $au = Transaction::where('pay_code' , $_POST['invoice_key'])->where('status' , 0)->first();


        if ( !isset($_POST['invoice_key']) ){
           $this->message->set_message('اطلاعات ارسالی نامعتبر' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
		}


        if ( !$au ){
			$this->message->set_message('درخواست نامعتبر یا غیرمجاز.' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();

		}

        $pay_info = $au->trans_id;
		$pay_infos = explode( '-', $pay_info);
		$api_key = $pay_infos['0'];
		$domain = $pay_infos['1'];
        $amount = $au->price;

        if ( $_POST['invoice_key'] != $au->pay_code ){
			die('کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.');
		}

        $res = $this->pay->check($_POST['invoice_key'],$api_key,$domain);
		$res = json_decode($res,1);
		if($res['status'] == 1){
			if($amount == $res['amount']/10){
				$au->update(array( 'status' => 1 ));
				$cash = $this->__getUserCash();
				$this->sentinel->getUserRepository()->where('id' , $this->user->id)->update(array( 'cash' => $cash + $au->price ));
//				$this->load->library('email');
//				$site_name = Setting::findByCode('site_name')->value;
//				$this->email->from('noreply@snapbet.org' , $site_name);
//				$this->email->to($this->user->email);
//				$this->email->subject($site_name . ' - شارژ حساب کاربری');
//				$this->email->message("حساب کاربری شما با موفقیت به مبلغ $amount تومان شارژ گردید.  .\n\n شماره پیگیری تراکنش: $au->trans_id \n با تشکر - تیم" . $site_name);
//				$this->email->send();
				$this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->pay_code , 'success' , 'پرداخت انجام شد' , 'dashboard')->redirect();
				// redirect to site
			}
		}else{
			$this->message->set_message('مشکل در پرداخت' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
		}
    }

    public function verify_payment () {
        $this->checkAuth(true);


        if ( !isset($_POST['transid']) )
            die('اطلاعات ارسالی معتبر نمیباشد.');

        $this->load->library('zahedipal');
        $this->load->eloquent('transaction');

        # از دیتابیس کد تراکنش و مبلغ را استخراج میکنیم
        $au = Transaction::where('trans_id' , $_POST['transid'])->where('status' , 0)->first();

        if ( !$au )
            $this->message->set_message('درخواست نامعتبر یا غیرمجاز.' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
        $amount = $au->price;


        if ( $_POST['transid'] != $au->trans_id )
            die('کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.');

        $data = array(
            'pin' => '7D5DFA0DD71196C1136E' , // شناسه درگاه
            'amount' => $amount , // مبلغ به تومان
            'transid' => $au->trans_id // کد تراکنش که در محل اول دریافت و در دیتابیس ذخیره کردیم
        );
        $res = $this->zahedipal->zpCurl($data , true);
        if ( empty($res) ) {

            $au->update(array( 'status' => 2 ));
            $this->message->set_message('پرداخت انجام نشده است .' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
        }
        else if ( $res == 1 ) {
            $au->update(array( 'status' => 1 ));
            $cash = $this->__getUserCash();
            $this->sentinel->getUserRepository()->where('id' , $this->user->id)->update(array( 'cash' => $cash + $au->price ));
            $this->load->library('email');
            $site_name = Setting::findByCode('site_name')->value;
            $this->email->from('noreply@landabet.com' , $site_name);
            $this->email->to($this->user->email);
            $this->email->subject($site_name . ' - شارژ حساب کاربری');
            $this->email->message("حساب کاربری شما با موفقیت به مبلغ $amount تومان شارژ گردید.  .\n\n شماره پیگیری تراکنش: $au->trans_id \n با تشکر - تیم" . $site_name);

            $this->email->send();
            $this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->trans_id , 'success' , 'پرداخت انجام شد' , 'dashboard')->redirect();
        }
        else
            echo $this->zahedipal->zpErr($res);
    }

	public function verifyZarrinpal () {
        $this->checkAuth(true);

		$this->load->library('pay');
        $this->load->eloquent('transaction');

		$au = Transaction::where('trans_id' , $_GET['Authority'])->where('status' , 0)->first();

		if ( !$au )
            $this->message->set_message('درخواست نامعتبر یا غیرمجاز.' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
        $amount = $au->price;

		if ( $_GET['Authority'] != $au->trans_id )
            die('کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.');

		$result = $this->pay->zarrinpall_check($_GET,$amount);

		if($result && $result->Status == 100){
			echo 'Transaction success. RefID:'.$result->RefID;
			$au->update(array( 'status' => 1 ));
			$cash = $this->__getUserCash();
			$this->sentinel->getUserRepository()->where('id' , $this->user->id)->update(array( 'cash' => $cash + $au->price ));
			$this->load->library('email');
			$site_name = Setting::findByCode('site_name')->value;
			$this->email->from('noreply@snapbet.org' , $site_name);
			$this->email->to($this->user->email);
			$this->email->subject($site_name . ' - شارژ حساب کاربری');
			$this->email->message("حساب کاربری شما با موفقیت به مبلغ $amount تومان شارژ گردید.  .\n\n شماره پیگیری تراکنش: $au->trans_id \n با تشکر - تیم" . $site_name);
			$this->email->send();
			$this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->trans_id , 'success' , 'پرداخت انجام شد' , 'dashboard')->redirect();
		}
		else if ($result){
			$this->smart->assign(array('error' => "پرداخت ناموفق $result->Status"));
		}
		else{
			$this->smart->assign(array('error' => 'پرداخت ناموفق'));
		}
		$this->smart->assign(array(
				'totalStake' => $this->getTotalStake(),
                    )
            );
			$this->smart->view('credit');

    }
    /**
     * My transaction logs
     * @param type $page
     */
    public function transactions ( $page = 0 ) {
        $this->checkAuth(true);
        $this->load->eloquent('transaction');

        $transactions = Transaction::where('user_id' , $this->user->id)->where('status' , 1)->take($this->config->item('per_page'))->skip($this->config->item('per_page') * ($page ))->orderBy('id' , 'desc')->get();

        //Pagination configs
        $config["base_url"] = site_url() . "payment/transactions";
        $config["total_rows"] = $transactions->count();
        $config["uri_segment"] = 3;
        // Now init the configs for pagination class
        $this->pagination->initialize($config);
        $this->smart->assign([
            'transactions' => $transactions ,
            'title' => 'تراکنش های مالی من' ,
            'cash' => $this->__getUserCash() ,
            'transaction_states' => 1 ,
            'paging' => $this->pagination->create_links() ,
			'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('transactions');
    }
		
	public function perfect ( ){
		//$this->load->eloquent('Transaction');
		
		$db = new DB();
		$gateway = $db->multi_select('gateway' , '*' , ['paymethodid' , 'status'] , [42 ,1]);
		
		$extravalue = $gateway[0]['extravalue'];
		$api_key = $gateway[0]['api_key'];
		

		$string=
		  $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
		  $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
		  $_POST['PAYMENT_BATCH_NUM'].':'.
		  $_POST['PAYER_ACCOUNT'].':'.strtoupper(md5($extravalue)).':'. 
		  $_POST['TIMESTAMPGMT'];

		$hash = strtoupper(md5($string));

		if($hash==$_POST['V2_HASH']){ 
		
			
			//$this->checkAuth(true);

			$this->load->library('pay');
			$this->load->eloquent('Transaction');
			$this->load->eloquent('User');
			

			$au = Transaction::where('pay_code' , $_POST['PAYMENT_ID'])->where('status' , 0)->first();

			$user_cash = $db->multi_select('users' , 'cash' , ['id' , 'status'] , [$au->user_id , 1 ]);
		
		
			
			$cash = $user_cash[0]['cash'] ;
			
			$new_cash = $cash + $au->price ;

			$retrun_amount = $this->chenge_unit( $_POST['PAYMENT_AMOUNT'], 3 );
			
			if( $retrun_amount == $au->price && $_POST['PAYEE_ACCOUNT'] == $api_key && $_POST['PAYMENT_UNITS'] == 'USD'){
				
				Transaction::where('pay_code' , $_POST['PAYMENT_ID'])->update(array('status'=> 1 , 'cash' => $new_cash ));
				
				$value = ['cash' => [ $new_cash,'i'] ];
				$where = ['id' => [ $au->user_id, 'i']];
				$db->update( 'users', $value, $where );
				
			}
		
		}
		
	}
	
	public function transactionOk(){
	
		$this->checkAuth(true);

		$this->load->library('pay');
        $this->load->eloquent('transaction');
        $this->load->eloquent('User');

		$au = Transaction::where('pay_code' , $_GET['invoiceid'])->where('status' , 1)->first();
			
		if (isset($au) && $au != NULL ){
			
			$this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->pay_code , 'success' , 'پرداخت انجام شد' , 'dashboard')->redirect();
			
		}else{
			$this->message->set_message('مشکل در پرداخت' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
			
		}

	}
	
	public function transactionNo (){
			
		$this->message->set_message('مشکل در پرداخت' , 'fail' , 'کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.' , 'dashboard')->redirect();

	}
	
	public function chenge_unit ( $amount, $from){
		
		$db = new DB();
				
		$amount_new = $db->multi_select('change_unit' , 'amount' , ['fromid' , 'toid'] , [$from ,2]);
		
		$amount_change = $amount_new[0]['amount'] * $amount;
		
		return $amount_change;
		
	}
	
	public function get_change_unit_amount(){
		
		$db = new DB();
		$query = "SELECT gateway.paymethodid as id, gateway.id as gateway_id, change_unit.amount as amount, unit.name_fa as name_fa, paymethod.min_amount as min_amount FROM paymethod 
					JOIN gateway ON paymethodid = paymethod.id 
					JOIN change_unit ON paymethod.unit_id = change_unit.fromid
                    JOIN unit ON paymethod.unit_id = unit.id";
		
		$unit = $db->get_query( $query );
		
		$return_value = array();
		
		if ( $unit != 0 ){
			
			foreach ( $unit as $key=>$value ){
				$return_value[ $value['id'] ] =  $value ;	
			}
			
		}
		
		return $return_value;
		
	}
	
}
?>


	
	<script
	  src="https://code.jquery.com/jquery-3.3.1.js"></script>
				<script>
		$( document ).ready(function() {			
			if($("#prfctmoney") != null ){
				$("#prfctmoney").click();
			}
		});
			
	</script>