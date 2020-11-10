<?php
class Payments extends Public_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->eloquent('settings/setting');
        $this->load->eloquent('Carttocart');
		$this->load->sentinel();
		$this->load->helper('cookie');
		if(!$this->sentinel->check()){
			if(isset($_COOKIE['auto_login']) AND !empty($_COOKIE['auto_login'])){
				$auto_login = $_COOKIE['auto_login'];
				$auto_login = explode('{{TkStar_Cookie}}', $auto_login);
				if(isset($auto_login[0]) AND isset($auto_login[1]) AND !empty($auto_login[0]) AND !empty($auto_login[1])){
					$credentials = array('email' => $auto_login[0], 'password' => $auto_login[1]);
					$auth_user = $this->sentinel->authenticate($credentials);
					if($auth_user){
						header('Location: ' . base_url($_SERVER['REQUEST_URI']));exit();
					}
				}
			}elseif(isset($_COOKIE['always_id_for_casino']) AND !empty($_COOKIE['always_id_for_casino'])){
				$username = $_COOKIE['username'];
				$password = $_COOKIE['password'];
				if(!empty($username) AND !empty($password)){
					$credentials = array('email' => $username, 'password' => $password);
					$auth_user = $this->sentinel->authenticate($credentials);
					if($auth_user){
						header('Location: ' . base_url($_SERVER['REQUEST_URI']));exit();
					}
				}
			}
		}
		if(!empty($this->message->get_message())){
			define('get_message', $this->message->get_message());
			$this->message->clear_message();
		}else{
			define('get_message', '');
			$this->message->clear_message();
		}
    }
	public function verify(){
		if(isset($_POST['PAYEE_ACCOUNT']) AND isset($_POST['PAYMENT_AMOUNT']) AND isset($_POST['PAYMENT_UNITS']) AND isset($_POST['PAYMENT_ID']) AND isset($_POST['PAYMENT_BATCH_NUM'])){
		    if($_POST['PAYMENT_BATCH_NUM'] == '' OR empty($_POST['PAYMENT_BATCH_NUM'])){
				header('Location: http://' . $_SERVER['SERVER_NAME'] . '/payment/transactions/?pay=false');exit();
		    }else{
		        $invoice_id = $_POST['PAYMENT_ID'];
                $this->CI = get_instance();
                $this->CI->load->database();
                $invoice = $this->CI->db->conn_id->query("SELECT * FROM `transactions` WHERE `id` = '" . $invoice_id . "'");
    			foreach($invoice as $i){
    				$i = (object)$i;
    				if($i->status == '0' OR $i->status == 0){
    					$this->CI->db->conn_id->query("UPDATE `users` SET `cash` = `cash` + " . $i->price . " WHERE `id` = '" . $i->user_id . "'");
    					$this->CI->db->conn_id->query("UPDATE `transactions` SET `status` = '1' WHERE `id` = '" . $invoice_id . "'");
    					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/payment/transactions/?pay=true');exit();
    				}else{
    					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/payment/transactions/?pay=false');exit();
    				}
    			}
		    }
		}else{
    		$invoice_id = $_GET['invoice_id'];
    		$status = $_GET['status'];
    		if($status === 'OK'){
                $this->CI = get_instance();
                $this->CI->load->database();
                $invoice = $this->CI->db->conn_id->query("SELECT * FROM `transactions` WHERE `id` = '" . $invoice_id . "'");
    			foreach($invoice as $i){
    				$i = (object)$i;
    				if($i->status == '0' OR $i->status == 0){
                        $this->CI->db->conn_id->query("UPDATE `users` SET `cash` = `cash` + " . $i->price . " WHERE `id` = '" . $i->user_id . "'");
    					$this->CI->db->conn_id->query("UPDATE `transactions` SET `status` = '1' WHERE `id` = '" . $invoice_id . "'");
    					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/payment/transactions/?pay=true');exit();
    				}else{
    					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/payment/transactions/?pay=false');exit();
    				}
    			}
    		}else{
    			header('Location: http://' . $_SERVER['SERVER_NAME'] . '/payment/transactions/?pay=false');exit();
    		}
		}
	}
    public function credit () {
        $this->checkAuth(true);
        $this->load->library('zahedipal');
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$current_dollar = 13000;
            $this->CI = get_instance();
            $this->CI->load->database();
            $type = $this->input->post('type');
            if($type == 'perfect_money'){
                $amount = $this->input->post('amount');       
                if($amount <= 4999){
    				$this->message->set_message('حداقل مبلغ قابل پرداخت 5,000 تومان می باشد', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                }else{
                    $price_type = $this->input->post('price_type');
                    if(!in_array($price_type, array('usd', 'eur', 'btc'))){
    					$this->message->set_message('نوع پرداخت انتخاب شده معتبر نمی باشد', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                    }else{
                        $wallet = $price_type == 'usd' ? Wallets['USD'] : ($price_type == 'eur' ? Wallets['EUR'] : Wallets['BTC']);
                        $unit = strtoupper($price_type);
                        $amount_dollar = $amount / $current_dollar;
                        $amount_dollar = round($amount_dollar, 2);
                        $this->CI->db->conn_id->query("INSERT INTO `transactions` (`id`, `price`, `invoice_type`, `description`, `user_id`, `transaction_states`, `created_at`, `updated_at`, `trans_id`, `cash`, `status`, `savano`) VALUES (NULL, '" . $amount . "', '1', 'افزایش موجودی حساب از طریق درگاه پرفکت مانی', '" . $this->user->id . "', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '', '" . $this->user->cash . "', '0', '')");
                        echo('<form name="my_form" action="https://perfectmoney.is/api/step1.asp" method="POST"><input type="hidden" name="PAYEE_ACCOUNT" value="' . $wallet . '"><input type="hidden" name="PAYEE_NAME" value="Pay to ' . $_SERVER['SERVER_NAME'] . '"><input type="hidden" name="PAYMENT_AMOUNT" value="' . $amount_dollar . '"><input type="hidden" name="PAYMENT_UNITS" value="' . $unit . '"><input type="hidden" name="STATUS_URL" value="' . site_url('payment/verify') . '"><input type="hidden" name="PAYMENT_URL" value="' . site_url('payment/verify') . '"><input type="hidden" name="NOPAYMENT_URL" value="' . site_url('payment/verify') . '"><input type="hidden" name="PAYMENT_ID" value="' . $this->CI->db->conn_id->insert_id . '"></form><script type="text/javascript">document.forms["my_form"].submit();</script>');die;
                    }
                }
            }elseif($type == 'ev_perfect_money'){
                $ev_code = $this->input->post('ev_code');
                $ev_act = $this->input->post('ev_act');
                if(empty($ev_code)){
					$this->message->set_message('وارد کردن کد ووچر الزامیست', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                }elseif(empty($ev_act)){
					$this->message->set_message('وارد کردن کد فعالسازی ووچر الزامیست', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                }else{
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLTOP_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('AccountID' => PerfectMoney['ID'], 'PassPhrase' => PerfectMoney['Pass'], 'Payee_Account' => Wallets['USD'], 'ev_number' => $ev_code, 'ev_code' => $ev_act)));
                    curl_setopt($ch, CURLOPT_URL, 'https://perfectmoney.is/acct/ev_activate.asp');
                    $result = curl_exec($ch);
                    $dd = new DOMDocument;
                    $dd->White_Space = false;
                    $dd->loadHTML($result);
                    $inputs = $dd->getElementsByTagName('input');
                    $message = array();
                    foreach($inputs as $input){
                        $message[$input->getAttribute('name')] = $input->getAttribute('value');
                    }
                    if(isset($message['ERROR'])){
                        if($message['ERROR'] = 'Invalid ev_number'){
        					$this->message->set_message('کد ووچر وارد شده معتبر نمی باشد', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                        }elseif($message['ERROR'] == 'Invalid ev_code'){
        					$this->message->set_message('کد فعالسازی ووچر نامعتبر است', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                        }elseif($message['ERROR'] == 'Invalid ev_number or ev_code'){
        					$this->message->set_message('ووچر وارد شده معتبر نمی باشد. ممکن است این ووچر قبلا استفاده شده باشد', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                        }else{
        					$this->message->set_message('خطایی پیش آمده است ! لطفا مجددا تلاش کنید', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
                        }
                    }else{
                        $ev_price = $message['VOUCHER_AMOUNT'];
						$ev_price_toman = $ev_price * $current_dollar;
						$ev_price_toman = round($ev_price_toman, 2);
                        $this->CI->db->conn_id->query("INSERT INTO `transactions` (`id`, `price`, `invoice_type`, `description`, `user_id`, `transaction_states`, `created_at`, `updated_at`, `trans_id`, `cash`, `status`, `savano`) VALUES (NULL, '" . $ev_price_toman . "', '1', 'افزایش موجودی حساب از طریق ووچر پرفکت مانی', '" . $this->user->id . "', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '', '" . $this->user->cash . "', '0', '')");
                        $this->CI->db->conn_id->query("UPDATE `users` SET `cash` = `cash` + " . $ev_price_toman . " WHERE `id` = '" . $this->user->id . "'");
						$this->message->set_message('موجودی حساب شما با موفقیت به مبلغ ' . number_format($ev_price_toman) . ' تومان افزایش یافت', 'success', 'افزایش موجودی', 'payment/credit')->redirect();
                    }
                }
			}elseif($type == 'kart'){
				$card_no = $this->input->post('card_no');
				$trans_id = $this->input->post('trans_id');
				$price = $this->input->post('price');
				if(empty($card_no)){
					$this->message->set_message('وارد کردن 4 رقم آخر شماره کارت الزامیست', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
				}elseif(strlen($card_no) != 4){
					$this->message->set_message('لطفا تنها 4 رقم آخر از شماره کارت خود را وارد کنید', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
				}elseif(empty($trans_id) OR !is_string($trans_id)){
					$this->message->set_message('خطایی پیش آمده است ! لطفا مجددا تلاش کنید', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
				}elseif(empty($price) OR !is_string($price)){
					$this->message->set_message('خطایی پیش آمده است ! لطفا مجددا تلاش کنید', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
				}else{
					$price = str_replace(array(','), array(''), $price);
					$this->CI->db->conn_id->query("INSERT INTO `kart_to_karts` (`id`, `user`, `price`, `trans_id`, `card_no`, `status`) VALUES (NULL, '" . $this->user->id . "', '" . $price . "', '" . $trans_id . "', '" . $card_no . "', '0')");
					$text = 'کارت به کارت جدید' . "\n\n" . 'چهار رقم آخر شماره کارت: ' . $card_no . "\n" . 'کد پیگیری: ' . $trans_id . "\n" . 'مبلغ: ' . number_format($price) . ' تومان';
					$ch = curl_init();
					$keyboard = array('inline_keyboard' => array(array(array('text' => '🌐 لیست همه کارت به کارت ها 🌐', 'url' => base_url('AdministratorPanel/settings/settings/karts'))), array(array('text' => '❌ حذف ❌', 'url' => base_url('AdministratorPanel/settings/settings/deleteKart/' . $this->CI->db->conn_id->insert_id))), array(array('text' => '✅ تایید ✅', 'url' => base_url('AdministratorPanel/settings/settings/acceptKart/' . $this->CI->db->conn_id->insert_id)), array('text' => '🚫 لغو 🚫', 'url' => base_url('AdministratorPanel/settings/settings/denyKart/' . $this->CI->db->conn_id->insert_id)))));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot868187509:AAG1zVaImXWqrZl6sYBLwHgwCAA0-YqCaMc/sendMessage?chat_id=184657197&text=' . urlencode($text) . '&reply_markup=' . json_encode($keyboard, JSON_UNESCAPED_UNICODE));
					curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					curl_exec($ch);
					$this->message->set_message('درخواست شما با موفقیت ثبت شد. به زودی موجودی شما افزایش خواهد یافت', 'success', 'افزایش موجودی', 'payment/credit')->redirect();
				}
            }else{
			    $this->message->set_message('درگاه پرداخت انتخاب شده معتبر نمی باشد', 'fail', 'افزایش موجودی', 'payment/credit')->redirect();
            }
        } else {
            $this->smart->view('credit');
        }
    }
    public function transactions(){
        $this->checkAuth(true);
        $this->load->eloquent('transaction');
		$this->CI = get_instance();
		$this->CI->load->database();
		$all_transactions = array();
		$query_baccarat_transactions = $this->CI->db->conn_id->query("SELECT * FROM `baccarats_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$query_blackjack_transactions = $this->CI->db->conn_id->query("SELECT * FROM `blackjacks_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$query_plinko_transactions = $this->CI->db->conn_id->query("SELECT * FROM `plinkoes_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$query_roulette_transactions = $this->CI->db->conn_id->query("SELECT * FROM `roulettes_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$query_craps_transactions = $this->CI->db->conn_id->query("SELECT * FROM `craps_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$high_low_transactions = $this->CI->db->conn_id->query("SELECT * FROM `high_low_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$fortune_wheel_transactions = $this->CI->db->conn_id->query("SELECT * FROM `fortune_wheel_table` WHERE `user` = '" . $this->user->id . "'")->fetch_all(MYSQLI_ASSOC);
		$query_crash_transactions = $this->CI->db->conn_id->query("SELECT * FROM `crash_table` WHERE `users` != '{}' AND `users` != '[]' AND `play` = '0'")->fetch_all(MYSQLI_ASSOC);
        $transactions = Transaction::where('user_id', $this->user->id)->orderBy('id', 'desc')->get();
		foreach($query_baccarat_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی باکارات - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($query_blackjack_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی بلک جک (21) - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($query_plinko_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی توپ و سبد - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($query_roulette_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی رویال رولت - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($query_craps_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی زمین و تاس (کرپس) - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($high_low_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی کمتر بیشتر - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($fortune_wheel_transactions as $transaction){
			$transaction = (object)$transaction;
			if($transaction->price == 0 OR $transaction->price == '0'){
				continue;
			}else{
				$all_transactions[$transaction->time] = array('trans_id' => 1000000 . $transaction->time, 'created_at' => $transaction->time, 'description' => 'بازی گردونه شانس - کازینو', 'price' => $transaction->price, 'status' => '1');
			}
		}
		foreach($query_crash_transactions as $crash){
			$crash = (object)$crash;
			$users = $crash->users;
			$users = json_decode($users, true);
			if(count($users) <= 0){
				continue;
			}else{
				$users = (object)$users;
				foreach($users as $user_id => $user_odd){
					if($user_id == $this->user->id){
						$user_odd = (object)$user_odd;
						$price = ($user_odd->win == '1' OR $user_odd->win == 1) ? $user_odd->price : $user_odd->price - ($user_odd->price * 2);
						$all_transactions[$crash->finish_time] = array('trans_id' => 1000000 . $crash->finish_time, 'created_at' => $crash->finish_time, 'description' => 'بازی انفجار - کازینو', 'price' => $price, 'status' => '1');
					}else{
						continue;
					}
				}
			}
		}
		foreach($transactions as $transaction){
			$transaction = (object)$transaction;
			$all_transactions[strtotime($transaction->created_at)] = array('trans_id' => $transaction->trans_id, 'created_at' => strtotime($transaction->created_at), 'description' => $transaction->description, 'price' => $transaction->price, 'status' => $transaction->status);
		}
        $this->smart->assign(array('transactions' => $all_transactions, 'cart_transaction' => $cart_transaction, 'title' => 'سابقه تراکنش ها', 'cash' => $this->__getUserCash(), 'transaction_states' => 1, '_GET' => $_GET));
        $this->smart->view('transactions');
    }
}
?>