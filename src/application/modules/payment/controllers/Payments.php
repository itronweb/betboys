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

    public $MerchantID = "4fa5977c-4806-11e8-8ec8-005056a205be";

    public $validation_rules = ['credit' => [['field' => 'amount',
                                              'rules' => 'numeric|trim|htmlspecialchars',
                                              'label' => 'مبلغ'],]];

    public function __construct(){
      parent::__construct();
      $this->load->eloquent('settings/Setting');
    }

    public function credit(){

      $companyname = 'mahbet.xyz';
      $interface_url = "http://mahbet.xyz";
      $base_url = $this->config->config['base_url'];
      $this->checkAuth(true);
      //$this->load->library('pay');
      $types = $paymethod = $this->input->post('ptype');
      if(isset($types)){
        //  print_r($types);
        $typeArr = explode('-', $types);
        $typeid = $typeArr['0'];
        $type = $typeArr['1'];
        //$name = 'amount_' . $type;
        $amount = $this->input->post('amount');
      }
      /*
      $typeid = $this->input->post('typeid');
                  $amount = $this->input->post('amount_42');
                  if($typeid == 42){
                     echo('<form name=\'myForm\' action=\'https://perfectmoney.is/api/step1.asp\' method=\'POST\'><input type=\'hidden\' name=\'PAYEE_ACCOUNT\' value=\'U24475268\'><input type=\'hidden\' name=\'PAYEE_NAME\' value=\'perfect_bet\'><input type=\'hidden\' name=\'PAYMENT_ID\' value=\'' . time() . '\'><input type=\'hidden\' name=\'PAYMENT_AMOUNT\' value=\'' . $amount . '\'><input type=\'hidden\' name=\'PAYMENT_UNITS\' value=\'USD\'><input type=\'hidden\' name=\'STATUS_URL\' value=\'' . site_url('payment/credit') . '\'><input type=\'hidden\' name=\'PAYMENT_URL\' value=\'' . site_url('payment/credit') . '\'><input type=\'hidden\' name=\'PAYMENT_URL_METHOD\' value=\'POST\'><input type=\'hidden\' name=\'NOPAYMENT_URL\' value=\'' . site_url('payment/credit') . '\'><input type=\'hidden\' name=\'NOPAYMENT_URL_METHOD\' value=\'POST\'><input type=\'hidden\' name=\'SUGGESTED_MEMO\' value=\'\'><input type=\'hidden\' name=\'BAGGAGE_FIELDS\' value=\'IDENT\'><br></form><script type=\'text/javascript\'>window.onload = function(){document.forms[\'myForm\'].submit();}</script>');exit();
                  }
                  */
      $description = "شارژ حساب کاربری";
      /////// $type = 1 ------------> pardakht online shetab
      /////// $type = 2 -------- > pardakht online
      /////// $type = 3 ------------> cart be cart
      /////// $type = 42 ------------> pardakht perfect money
      /////// $type = 52 ------------> pardakht tavasot vaseet zarinpal
      if(isset($_POST['TransId'])){
        $send_data = ["api"      => '26ea1084a64f72ac3ee627e8a4e5ede7d012e36bc53b035eb4789bb3b81b8919',
                      "trans_id" => $_POST['TransId'],];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://market-shope.com/api/verify');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send_data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($res);
        if($result->status == 1){
          $amount = floor($result->amount * 0.1);
          $this->load->eloquent('Transaction');
          $C = Transaction::where('pay_code', $result->refId)->count('*');
          if(!$C){
            $cash = $this->__getUserCash();
            Transaction::create(['trans_id'     => $_POST['TransId'],
                                 'price'        => $amount,
                                 'invoice_type' => 1,
                                 'cash'         => $cash + $amount,
                                 'pay_code'     => $result->refId,
                                 'card_number'  => $result->user_card_no,
                                 'user_id'      => $this->user->id,
                                 'status'       => 1,
                                 'description'  => 'افزایش موجودی حساب توسط درگاه کرون']);
            $this->sentinel->getUserRepository()->where('id', $this->user->id)->update(['cash' => $cash + $amount]);
          }
        }
        header("Location: credit");
      }
      elseif($this->formValidate(false)){

        $status = false;
        $invoice_type = "1";
        if($type == '3'){

          $dbs = new DB();
          $getway_crown = $dbs->multi_select('gateway', '*', ['paymethodid',
                                                              'status'], [1,
                                                                          1]);
          $crown_merchentID = $getway_crown[0]['api_key'];
          $this->load->eloquent('Transaction');
          $cash = $this->__getUserCash();
          $trans_id = rand(10000000, 99999999);
          $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'? "https": "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
          $send_data = ["api"          => '26ea1084a64f72ac3ee627e8a4e5ede7d012e36bc53b035eb4789bb3b81b8919',
                        "amount"       => $amount * 10,
                        "callback_url" => $link,
                        "card_id"      => '215',
                        "order_id"     => $trans_id,
                        "mobile"       => '09125823696',];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'http://market-shope.com/api/new_order');
          curl_setopt($ch, CURLOPT_TIMEOUT, 15);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send_data));
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $res = curl_exec($ch);
          curl_close($ch);
          $result = json_decode($res);
          if($result->status == 1){
            $go = $result->redirect_url;
            header("Location: $go");
            echo 'Redirect to ' . $go . PHP_EOL;
            exit;
          }
          else{
            echo "error " . $result->status . ' : ' . $result->message . PHP_EOL;
            $this->smart->view('credit');
          }
        }
        elseif($type == '2'){
          $redirect = "$base_url/payment/verifyZarrinpal";
          $data = ['amount'      => $amount,
                   'description' => $description];
          $db = new DB();
          $getway = $db->multi_select('gateway', '*', ['status',
                                                       'paymethodid',
                                                       'id'], ['1',
                                                               '2',
                                                               "$typeid"], 'sort ASC');
          $api_key = $getway['api_key'];
          $customer_card = null;
          $domain = $getway['domain'];
          $result_request = $this->pay->zarrinpall_request($data, $redirect, $api_key);
          if($result_request->Status == 100){
            $status = true;
            $invoice_key = $result_request->Authority;
            $go = "https://www.zarinpal.com/pg/StartPay/" . $invoice_key;
          }
          $transid = $api_key . "-" . $domain;
        }
        elseif($type == '3'){
          $customer_card = $this->input->post('customer_card');
          $transid = $invoice_key = $this->input->post('pay_code');
          $description = 'شارژ حساب کاربری با روش کارت به کارت';
          $status = 3;
          $invoice_type = 20;
          $status = (empty($customer_card) || empty($invoice_key))? false: 3;
        }
        elseif($type == '52'){

          $user_id = $this->user->id;
          $token = $this->user->game_token;
          $trans_id = time() . "" . $user_id;
          $description = "شارژ حساب کاربری توسط درگاه واسط زرین پال";
          $redirect = $interface_url . "/index.php?id=" . $user_id . "&&type=" . $types . "&&trans=" . $trans_id . "&&token=" . $token;
          $data = ['amount'      => $amount,
                   'description' => $description];
          $db = new DB();
          $this->load->eloquent('Transaction');
          $cash = $this->__getUserCash();
          $amount = ($amount / 10);
          Transaction::create(['trans_id'     => $trans_id,
                               'price'        => $amount,
                               'invoice_type' => $invoice_type,
                               'payment_id'   => $paymethod,
                               'cash'         => $cash + $amount,
                               'user_id'      => $this->user->id,
                               'pay_code'     => null,
                               //
                               'card_number'  => null,
                               //
                               'description'  => $description,]);
          header("Location:" . $redirect);
          var_dump($redirect);
          die();
        }
        elseif($type == '422'){

          $ev_number = $this->input->post('ev_number');
          $ev_code = $this->input->post('ev_code');
          $systemurl = $this->config->config['base_url'];
          $user_id = $this->user->id;
          $customer_card = null;
          $description = 'پرداخت توسط وچرپرفکت مانی به سایت 4ubets';
          $invoiceid = $invoice_key = "PERFECTVOUCHERID-" . time() . '' . $type . '' . $user_id; //ID-322223
          $transid = $invoice_key . "-" . $companyname;
          $token = $this->user->game_token;
          $redirect = $interface_url . "/index.php?id=" . $user_id . "&&type=" . $types . "&&trans=" . $transid . "&&token=" . $token;
          $this->load->eloquent('Transaction');
          $cash = $this->__getUserCash();
          $pay_code = $ev_number . "_" . $ev_code;
          Transaction::create(['trans_id'     => $transid,
                               //
                               'price'        => 0,
                               'invoice_type' => $invoice_type,
                               'cash'         => $cash,
                               'user_id'      => $user_id,
                               'pay_code'     => $pay_code,
                               //
                               'payment_id'   => $paymethod,
                               //
                               'card_number'  => $customer_card,
                               'description'  => $description,]);
          $game_token = $this->user->game_token;
          header("Location:" . $redirect);
          var_dump($redirect);
          die();
        }
        elseif($type == '42'){
          $user_id = $this->user->id;
          $token = $this->user->game_token;
          $description = "شارژ حساب کاربری توسط درگاه پرفکت مانی";
          $invoice_key = "PERFECTID-" . time() . '' . $type . '' . $user_id;
          $transid = $invoice_key . "-" . $companyname;
          $db = new DB();
          $amount_new = $db->multi_select('change_unit', 'amount', ['fromid',
                                                                    'toid'], [3,
                                                                              2]);
          $amount_change = $amount_new[0]['amount'] * $amount;
          $cash = $this->__getUserCash();
          $cash = $cash + $amount_change;
          //$redirect = $interface_url . "/index.php?id=".$user_id."&&type=".$types."&&trans=".$transid."&&token=".$token;
          $this->load->eloquent('Transaction');
          Transaction::create(['trans_id'     => $transid,
                               'price'        => $amount_change,
                               'invoice_type' => $invoice_type,
                               'payment_id'   => $paymethod,
                               'cash'         => $cash,
                               'user_id'      => $user_id,
                               'pay_code'     => $invoice_key,
                               //
                               'card_number'  => null,
                               //
                               'description'  => $description,]);
          //header("Location:".$redirect);
          echo('<form name=\'myForm\' action=\'https://perfectmoney.is/api/step1.asp\' method=\'POST\'><input type=\'hidden\' name=\'PAYEE_ACCOUNT\' value=\'U24475268\'><input type=\'hidden\' name=\'PAYEE_NAME\' value=\'perfect_bet\'><input type=\'hidden\' name=\'PAYMENT_ID\' value=\'' . $transid . '\'><input type=\'hidden\' name=\'PAYMENT_AMOUNT\' value=\'' . $amount . '\'><input type=\'hidden\' name=\'PAYMENT_UNITS\' value=\'USD\'><input type=\'hidden\' name=\'STATUS_URL\' value=\'' . site_url('payment/credit') . '\'><input type=\'hidden\' name=\'PAYMENT_URL\' value=\'' . site_url('payment/credit') . '\'><input type=\'hidden\' name=\'PAYMENT_URL_METHOD\' value=\'POST\'><input type=\'hidden\' name=\'NOPAYMENT_URL\' value=\'' . site_url('payment/credit') . '\'><input type=\'hidden\' name=\'NOPAYMENT_URL_METHOD\' value=\'POST\'><input type=\'hidden\' name=\'SUGGESTED_MEMO\' value=\'\'><input type=\'hidden\' name=\'BAGGAGE_FIELDS\' value=\'IDENT\'><br></form><script type=\'text/javascript\'>window.onload = function(){document.forms[\'myForm\'].submit();}</script>');
          exit();
          //var_dump($redirect);
          die();
        }
        elseif($type == '62'){

          $user_id = $this->user->id;
          $token = $this->user->game_token;
          $invoice_key = "PARSIGRAMID-" . time() . '' . $type . '' . $user_id;
          $trans_id = $invoice_key . "-" . $companyname;
          $description = "شارژ حساب کاربری توسط درگاه پارسی گرام";
          $redirect = $interface_url . "/index.php?id=" . $user_id . "&&type=" . $types . "&&trans=" . $trans_id . "&&token=" . $token;
          $data = ['amount'      => $amount,
                   'description' => $description];
          $db = new DB();
          $this->load->eloquent('Transaction');
          $cash = $this->__getUserCash();
          Transaction::create(['trans_id'     => $trans_id,
                               'price'        => $amount,
                               'invoice_type' => $invoice_type,
                               'payment_id'   => $paymethod,
                               'cash'         => $cash + $amount,
                               'user_id'      => $this->user->id,
                               'pay_code'     => null,
                               //
                               'card_number'  => null,
                               //
                               'description'  => $description,]);
          header("Location:" . $redirect);
          var_dump($redirect);
          die();
        }
        elseif($type == '622'){

          $ev_number = $this->input->post('ev_number');
          $ev_code = $this->input->post('ev_code');
          $user_id = $this->user->id;
          $token = $this->user->game_token;
          $invoice_key = "PARSIGRAMVOUCHERID-" . time() . '' . $type . '' . $user_id; //ID-322223
          $trans_id = $invoice_key . "-" . $companyname;
          $description = "شارژ حساب کاربری توسط وچر پارسی گرام";
          $pay_code = $ev_number . "_" . $ev_code;
          $redirect = $interface_url . "/index.php?id=" . $user_id . "&&type=" . $types . "&&trans=" . $trans_id . "&&token=" . $token;
          $db = new DB();
          $this->load->eloquent('Transaction');
          $cash = $this->__getUserCash();
          Transaction::create(['trans_id'     => $trans_id,
                               'price'        => 0,
                               'invoice_type' => $invoice_type,
                               'payment_id'   => $paymethod,
                               'cash'         => $cash,
                               'user_id'      => $user_id,
                               'pay_code'     => $pay_code,
                               //
                               'card_number'  => null,
                               //
                               'description'  => $description,]);
          header("Location:" . $redirect);
          var_dump($redirect);
          die();
        }  elseif ($type == '624') {

            $tron_id = $this->input->post('tron_id');
            $amount = (float)$this->input->post('amount_624');
            sleep(5);
            $this->load->eloquent('Transaction');
            if (!Transaction::where('trans_id',$tron_id)->where('status', 1)->first()){
                $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
                $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
                $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
                $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
                $transaction = $tron->getTransaction($tron_id);
                if (isset($transaction['ret'][0]['contractRet']) && strtoupper($transaction['ret'][0]['contractRet']) == "SUCCESS" && isset($transaction['raw_data']['contract']) && count($transaction['raw_data']['contract'])){
                    $contract = $transaction['raw_data']['contract'];
                    $contract = end($contract)['parameter']['value'];
                    $this->load->eloquent('transaction');
                    if ($tron->fromTron($contract['call_value']) == $amount && $contract['contract_address'] == $tron->toHex("TCRS2UbeKyNQo8Syv2TWbAuNCC8rUijdJ7")){
                        $user_id = $this->user->id;
                        $invoice_key = "TRON-" . time() . '' . $type . '' . $user_id; //ID-322223
                        $description = "شارژ حساب کاربری توسط ترون";
                        $db = new DB();
                        $amount_new = $db->multi_select('change_unit', 'amount', ['fromid',
                            'toid'], [4,
                            2]);
                        $amount_change = $amount_new[0]['amount'] * $amount;
                        $cash = $this->__getUserCash();
                        $cash = $cash + $amount_change;
                        $this->load->eloquent('Transaction');
                        $au = Transaction::create(['trans_id'     => $tron_id,
                            'price'        => $amount_change,
                            'invoice_type' => $invoice_type,
                            'payment_id'   => $paymethod,
                            'cash'         => $cash,
                            'user_id'      => $user_id,
                            'pay_code'     => $invoice_key,
                            //
                            'card_number'  => null,
                            //
                            'description'  => $description,]);
                        $au->update(['status' => 1, "trans_id" => $transfer['txid']]);
                        $cash = $this->__getUserCash();
                        $this->sentinel->getUserRepository()->where('id', $this->user->id)->update(['cash' => $cash + $au->price]);
                        $this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $tron_id, 'success', 'پرداخت انجام شد', 'payment/credit');
                    }
                    else
                        $this->message->set_message('مشکل در پرداخت', 'fail', 'پرداخت ناموفق بود', 'payment/credit');
                }else
                    $this->message->set_message('مشکل در پرداخت', 'fail', 'پرداخت ناموفق بود', 'payment/credit');
            }else
                $this->message->set_message('مشکل در پرداخت', 'fail', 'تراکنش از قبل وریفای شده است.', 'payment/credit');
            /*$fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
            $tron->getTransaction("758c347abdce8cbe8b80bff324870b7b300d92fa42c35e91121b183f83dd7b50");
            $tron->setAddress($tron_address);
            $tron->setPrivateKey($tron_key);

            $transfer = $tron->send( 'TSsDWJbNMqJNZuXDC7JhuPSdrAoUZCTu2p', $amount);
            $user_id = $this->user->id;
            $invoice_key = "TRON-" . time() . '' . $type . '' . $user_id; //ID-322223
            $trans_id = $invoice_key . "-" . $companyname;
            $description = "شارژ حساب کاربری توسط ترون";
            $pay_code = $tron_address . "_" . $tron_key;
            $db = new DB();
            $amount_new = $db->multi_select('change_unit', 'amount', ['fromid',
                'toid'], [4,
                2]);
            $amount_change = $amount_new[0]['amount'] * $amount;
            $cash = $this->__getUserCash();
            $cash = $cash + $amount_change;
            $this->load->eloquent('Transaction');
            $au = Transaction::create(['trans_id'     => $transid,
                'price'        => $amount_change,
                'invoice_type' => $invoice_type,
                'payment_id'   => $paymethod,
                'cash'         => $cash,
                'user_id'      => $user_id,
                'pay_code'     => $invoice_key,
                //
                'card_number'  => null,
                //
                'description'  => $description,]);
            if (isset($transfer['result']) && $transfer['result']) {
                $au->update(['status' => 1, "trans_id" => $transfer['txid']]);
                $cash = $this->__getUserCash();
                $this->sentinel->getUserRepository()->where('id', $this->user->id)->update(['cash' => $cash + $au->price]);
//                $this->load->library('email');
//                $site_name = Setting::findByCode('site_name')->value;
//                $this->email->from('noreply@landabet.com', $site_name);
//                $this->email->to($this->user->email);
//                $this->email->subject($site_name . ' - شارژ حساب کاربری');
//                $this->email->message("حساب کاربری شما با موفقیت به مبلغ $amount تومان شارژ گردید.  .\n\n شماره پیگیری تراکنش: $au->trans_id \n با تشکر - تیم" . $site_name);
//                $this->email->send();
                $this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->trans_id, 'success', 'پرداخت انجام شد', 'dashboard');
            }else{
                $this->message->set_message('مشکل در پرداخت', 'fail', 'پرداخت انجام نشد', 'dashboard');
            }*/
            echo('<script type=\'text/javascript\'>window.location.replace("'."$base_url/payment/credit".'");</script>');
            exit();
        }
        else{
          $status = false;
        }
        if($status){
          $this->load->eloquent('Transaction');
          $cash = $this->__getUserCash();
          $amount = ($amount / 10);
          Transaction::create(['trans_id'     => $transid,
                               //
                               'price'        => $amount,
                               'invoice_type' => $invoice_type,
                               'payment_id'   => $payment_id,
                               'cash'         => $cash + $amount,
                               'user_id'      => $this->user->id,
                               'pay_code'     => $invoice_key,
                               //
                               'payment_id'   => $paymethod,
                               //
                               'card_number'  => $customer_card,
                               'description'  => $description,]);
          if($status === true) header('location:' . $go);
          elseif($status == 3) $this->message->set_message('اطلاعات شما برای مدیریت ارسال شد', 'success', 'درصورت تایید تراکنش، مبلغ به حساب شما افزوده می شود', 'dashboard')->redirect();
        }
        else{
          //$this->message->set_message('مشکل در اتصال به درگاه' , 'fail' , 'پرداخت انجام نشد' , 'dashboard')->redirect();
        }
        //exit();
      }
      $db = new DB();
      $getway = $db->multi_select('gateway', '*', ['status'], ['1'], 'sort ASC');
      $unit_amount = $this->get_change_unit_amount();
      $this->smart->assign(['totalStake'  => $this->getTotalStake(),
                            'getway'      => $getway,
                            'unit_amount' => $unit_amount,
                            'base_unit'   => 'تومان',]);
      $this->smart->view('credit');
    }

    public function verify_credit(){
      $this->checkAuth(true);
      $this->load->library('pay');
      $this->load->eloquent('transaction');
      # از دیتابیس کد تراکنش و مبلغ را استخراج میکنیم
      $au = Transaction::where('pay_code', $_POST['invoice_key'])->where('status', 0)->first();
      if(!isset($_POST['invoice_key'])){
        $this->message->set_message('اطلاعات ارسالی نامعتبر', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      }
      if(!$au){
        $this->message->set_message('درخواست نامعتبر یا غیرمجاز.', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      }
      $pay_info = $au->trans_id;
      $pay_infos = explode('-', $pay_info);
      $api_key = $pay_infos['0'];
      $domain = $pay_infos['1'];
      $amount = $au->price;
      if($_POST['invoice_key'] != $au->pay_code){
        die('کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.');
      }
      $res = $this->pay->check($_POST['invoice_key'], $api_key, $domain);
      $res = json_decode($res, 1);
      if($res['status'] == 1){
        if($amount == $res['amount'] / 10){
          $au->update(['status' => 1]);
          $cash = $this->__getUserCash();
          $this->sentinel->getUserRepository()->where('id', $this->user->id)->update(['cash' => $cash + $au->price]);
          //				$this->load->library('email');
          //				$site_name = Setting::findByCode('site_name')->value;
          //				$this->email->from('noreply@snapbet.org' , $site_name);
          //				$this->email->to($this->user->email);
          //				$this->email->subject($site_name . ' - شارژ حساب کاربری');
          //				$this->email->message("حساب کاربری شما با موفقیت به مبلغ $amount تومان شارژ گردید.  .\n\n شماره پیگیری تراکنش: $au->trans_id \n با تشکر - تیم" . $site_name);
          //				$this->email->send();
          $this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->pay_code, 'success', 'پرداخت انجام شد', 'dashboard')->redirect();
          // redirect to site
        }
      }
      else{
        $this->message->set_message('مشکل در پرداخت', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      }
    }

    public function verify_payment(){
      $this->checkAuth(true);
      if(!isset($_POST['transid'])) die('اطلاعات ارسالی معتبر نمیباشد.');
      $this->load->library('zahedipal');
      $this->load->eloquent('transaction');
      # از دیتابیس کد تراکنش و مبلغ را استخراج میکنیم
      $au = Transaction::where('trans_id', $_POST['transid'])->where('status', 0)->first();
      if(!$au) $this->message->set_message('درخواست نامعتبر یا غیرمجاز.', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      $amount = $au->price;
      if($_POST['transid'] != $au->trans_id) die('کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.');
      $data = ['pin'     => '7D5DFA0DD71196C1136E',
               // شناسه درگاه
               'amount'  => $amount,
               // مبلغ به تومان
               'transid' => $au->trans_id
               // کد تراکنش که در محل اول دریافت و در دیتابیس ذخیره کردیم
      ];
      $res = $this->zahedipal->zpCurl($data, true);
      if(empty($res)){

        $au->update(['status' => 2]);
        $this->message->set_message('پرداخت انجام نشده است .', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      }
      elseif($res == 1){
        $au->update(['status' => 1]);
        $cash = $this->__getUserCash();
        $this->sentinel->getUserRepository()->where('id', $this->user->id)->update(['cash' => $cash + $au->price]);
        $this->load->library('email');
        $site_name = Setting::findByCode('site_name')->value;
        $this->email->from('noreply@landabet.com', $site_name);
        $this->email->to($this->user->email);
        $this->email->subject($site_name . ' - شارژ حساب کاربری');
        $this->email->message("حساب کاربری شما با موفقیت به مبلغ $amount تومان شارژ گردید.  .\n\n شماره پیگیری تراکنش: $au->trans_id \n با تشکر - تیم" . $site_name);
        $this->email->send();
        $this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->trans_id, 'success', 'پرداخت انجام شد', 'dashboard')->redirect();
      }
      else
        echo $this->zahedipal->zpErr($res);
    }

    //ُSezarCo | Crown Payment Gateway
    public function verifySezar(){
      $this->checkAuth(true);
      $this->load->eloquent('Transaction');
      //var_dump([ 'user_id' => $this->user->id , 'trans_id' => $_GET['trans_id'] ]);
      # از دیتابیس کد تراکنش و مبلغ را استخراج میکنیم
      $au = Transaction::where('user_id', $this->user->id)->where('status', 0)->where('trans_id', $_GET['transid'])->first();
      if(!$au){
        $this->message->set_message('درخواست نامعتبر یا غیرمجاز.', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      }
      $trans_id = $au->trans_id;
      $Crown_transAPI = "https://crowngate.ir/webservice/transAPI.php?id={$au->trans_id}";
      $getCrown_transAPI = file_get_contents($Crown_transAPI);
      echo $au->trans_id;
      if($getCrown_transAPI == 2){
        $cash = $this->__getUserCash();
        $amount = $au->price;
        $au->update(['status' => 1]);
        $this->sentinel->getUserRepository()->where('id', $this->user->id)->update(['cash' => $cash + $amount]);
        $this->message->set_message('پرداخت با موفقیت انجام شد', 'success', 'پرداخت موفق', 'payment/credit')->redirect();
      }
      else{
        $this->message->set_message($getCrown_transAPI . "<br>" . $au->trans_id . '<br>مشکلی در ثبت پرداخت به وجود امده است', 'fail', 'پرداخت ناموفق', 'payment/credit')->redirect();
      }
    }

    /**
     * My transaction logs
     * @param type $page
     */
    public function transactions($page = 0){
      $this->checkAuth(true);
      $this->load->eloquent('transaction');
      $transactions = Transaction::where('user_id', $this->user->id)->where('status', 1)->take($this->config->item('per_page'))->skip($this->config->item('per_page') * ($page))->orderBy('id', 'desc')->get();
      //Pagination configs
      $config["base_url"] = site_url() . "payment/transactions";
      $config["total_rows"] = $transactions->count();
      $config["uri_segment"] = 3;
      // Now init the configs for pagination class
      $this->pagination->initialize($config);
      $this->smart->assign(['transactions'       => $transactions,
                            'title'              => 'تراکنش های مالی من',
                            'cash'               => $this->__getUserCash(),
                            'transaction_states' => 1,
                            'paging'             => $this->pagination->create_links(),
                            'totalStake'         => $this->getTotalStake(),]);
      $this->smart->view('transactions');
    }

    public function perfect(){
      //$this->load->eloquent('Transaction');
      $db = new DB();
      $gateway = $db->multi_select('gateway', '*', ['paymethodid',
                                                    'status'], [42,
                                                                1]);
      $extravalue = $gateway[0]['extravalue'];
      $api_key = $gateway[0]['api_key'];
      $string = $_POST['PAYMENT_ID'] . ':' . $_POST['PAYEE_ACCOUNT'] . ':' . $_POST['PAYMENT_AMOUNT'] . ':' . $_POST['PAYMENT_UNITS'] . ':' . $_POST['PAYMENT_BATCH_NUM'] . ':' . $_POST['PAYER_ACCOUNT'] . ':' . strtoupper(md5($extravalue)) . ':' . $_POST['TIMESTAMPGMT'];
      $hash = strtoupper(md5($string));
      if($hash == $_POST['V2_HASH']){


        //$this->checkAuth(true);
        $this->load->library('pay');
        $this->load->eloquent('Transaction');
        $this->load->eloquent('User');
        $au = Transaction::where('pay_code', $_POST['PAYMENT_ID'])->where('status', 0)->first();
        $user_cash = $db->multi_select('users', 'cash', ['id',
                                                         'status'], [$au->user_id,
                                                                     1]);
        $cash = $user_cash[0]['cash'];
        $new_cash = $cash + $au->price;
        $retrun_amount = $this->chenge_unit($_POST['PAYMENT_AMOUNT'], 3);
        if($retrun_amount == $au->price && $_POST['PAYEE_ACCOUNT'] == $api_key && $_POST['PAYMENT_UNITS'] == 'USD'){

          Transaction::where('pay_code', $_POST['PAYMENT_ID'])->update(['status' => 1,
                                                                        'cash'   => $new_cash]);
          $value = ['cash' => [$new_cash,
                               'i']];
          $where = ['id' => [$au->user_id,
                             'i']];
          $db->update('users', $value, $where);
        }
      }
    }

    public function transactionOk(){

      $this->checkAuth(true);
      $this->load->library('pay');
      $this->load->eloquent('transaction');
      $this->load->eloquent('User');
      $au = Transaction::where('pay_code', $_GET['invoiceid'])->where('status', 1)->first();
      if(isset($au) && $au != null){

        $this->message->set_message('پرداخت با موفقیت انجام شده است . کد رهگیری تراکنش : ' . $au->pay_code, 'success', 'پرداخت انجام شد', 'dashboard')->redirect();
      }
      else{
        $this->message->set_message('مشکل در پرداخت', 'fail', 'پرداخت انجام نشد', 'dashboard')->redirect();
      }
    }

    public function transactionNo(){

      $this->message->set_message('مشکل در پرداخت', 'fail', 'کد تراکنش ارسالی  با کد تراکنش ذخیره شده یکسان نیست.', 'dashboard')->redirect();
    }

    public function chenge_unit($amount, $from){

      $db = new DB();
      $amount_new = $db->multi_select('change_unit', 'amount', ['fromid',
                                                                'toid'], [$from,
                                                                          2]);
      $amount_change = $amount_new[0]['amount'] * $amount;
      return $amount_change;
    }

    public function get_change_unit_amount(){

      $db = new DB();
      $query = "SELECT gateway.paymethodid as id, gateway.id as gateway_id, change_unit.amount as amount, unit.name_fa as name_fa, paymethod.min_amount as min_amount FROM paymethod 
					JOIN gateway ON paymethodid = paymethod.id 
					JOIN change_unit ON paymethod.unit_id = change_unit.fromid
                    JOIN unit ON paymethod.unit_id = unit.id";
      $unit = $db->get_query($query);
      $return_value = [];
      if($unit != 0){

        foreach($unit as $key => $value){
          $return_value[$value['id']] = $value;
        }
      }
      return $return_value;
    }

  }

?>


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script>
  $(document).ready(function () {
    if ($("#prfctmoney") != null) {
      $("#prfctmoney").click();
    }
  });

</script>