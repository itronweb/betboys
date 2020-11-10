<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

require_once APPPATH . 'config/db.php';

class Public_Controller extends MY_Controller {

    /**
     * the logged in user object
     * @var array()
     */
    public $user;

    function __construct () {
		

        parent::__construct();
		
		$this->load->eloquent('settings/Setting');
		
        if ( !($this->user = $this->sentinel->check() ) ) {
            $is_logged_in = false;
        } else {
            $is_logged_in = true;
        }
		
		////////////////////////////////
//		// bazdid site
		$this->load->helper('jdf');
//		$this->counter();
		$user_online = $this->userOnline();
		$user_onlins = sizeof($user_online);
		//////////////////////////////////
		if($is_logged_in){
			if($this->user->status == 2){
				// $pages = ['/users/verify','/users/logout','/users/login','/users/register','users/captcha','/users/captcha/...','/assets/default/live/fonts/IRANSansWeb.ttf'];
				$newarray = [0,1,2,3,4,5,6,7,8,9];
				$pages = ["/users/verify","/users/login","/users/register","/users/logout","/contacts/tickets/ticket-list","/contacts/tickets/new-ticket",'/contacts/tickets/view-ticket/'];
				if(in_array($_SERVER['REQUEST_URI'],$pages)){

				} else {
					redirect('users/verify');
				}
			}
		}

//		session_start();
        
        //added
        $this->load->helper('admin_helper');
        $this->load->eloquent('content/Comment');
        $this->load->eloquent('Contacts/Contact');
        $this->load->eloquent('Contacts/Ticket');
		$this->load->eloquent('menu/Menu');
		$this->load->eloquent('menu/Menu_group');

        if( Setting::findByCode('login_cancel')->value == 1 ){
			
//			redirect('users/logout');
		}
		
		$footer_1 = Menu::where('group_id',3)->orderBy('weight' , 'asc')->get();
        $footer_2 = Menu::where('group_id',4)->orderBy('weight' , 'asc')->get();
		$user_id_hash = null;
		
        if ( isset($this->user->id) ) {
		
//			if ( $this->user->game_token != $_COOKIE['code'] ){
//				$is_logged_in = false;
//			}
//			$token = $this->create_token();
//			$hashid = new Hashids('Ghorayshi');
//			$user_id_hash = $hashid->encode($this->user->id,70,72);
//			setcookie('code', $user_id_hash, time() + (3600), "/");
			
            $Contacts = $this->announcements($this->user->id);
            $AnnounceCount = 0;
            $support_badge = Ticket::where('user_id' , $this->user->id)->where('status' , 2)->get()->count();
            foreach ( $Contacts as $val ):
                if ( $val->seen_status == 0 )
                    $AnnounceCount += 1;
            endforeach;
        
		//////////////// Message //////////////////

			$db = new DB();
			$user_id = $this->user->id;
			$query = "SELECT id FROM message WHERE user_id = $user_id AND is_read = '0' AND status = '1'";
			$read = $db->get_query($query);
			/////////////////////////////////////////////
		}
//        $this->load->helper('admin_helper');
        if ( !$this->sentinel->guest() ) {
            $this->smart->assign(
                    [
                        'is_admin' => $this->sentinel->getUser()->getRoles()->contains('slug' , SUPER_ADMIN) ,
                        'is_operator' => $this->sentinel->getUser()->getRoles()->contains('slug' , 'sh_operator') ,
                        'user_role' => $this->sentinel->getUser()->getRoles() ,
                        'AnnounceCount' => $AnnounceCount ,
                        'is_logged_in' => $is_logged_in ,
                        'user' => $this->user ,
                        'support_badge' => $support_badge ,
						'message_budget' => ($read == 0) ? 0 : sizeof( $read ),
                    ]
            );
        }
        else {
            $this->smart->assign([
                'is_logged_in' => $is_logged_in ,
                'user' => $this->user ,
                'support_badge' => 0 ,
            ]);
        }
		
		$this->smart->assign([
                'footer_right' => $footer_1 ,
                'footer_mid' => $footer_2,
				'user_id_hash' => $user_id_hash,
				'instagram' => Setting::findByCode('instagram')->value,
				'telegram' => Setting::findByCode('telegram')->value,
				'facebook' => Setting::findByCode('facebook')->value,
            ]);
        //end of added
        //Get theme for site
        $site_theme = Setting::findByCode('theme')->value;
        if ( isset($site_theme) ) {
            $this->smart->load($site_theme , false);
        }
        else {
            $this->smart->load('mirook-2015' , false);
        }
        if ( Setting::findByCode('site_status')->value == 0 ) {
            $this->smart->setLayout('maintenance_mode');
        }
    }
	
	public function create_token(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); 
		$alphaLength = strlen($alphabet) - 1; 
		for ($i = 0; $i < 20; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); 
	}
	
	
	public function get_browser_name($user_agent){
			if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
			elseif (strpos($user_agent, 'Edge')) return 'Microsoft Edge';
			elseif (strpos($user_agent, 'Chrome')) return 'Google Chrome';
			elseif (strpos($user_agent, 'Safari')) return 'Safari';
			elseif (strpos($user_agent, 'Firefox')) return 'Mozila Firefox';
			elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
			else return 'Other';
		}
	
	public function getOS($user_agent_os) { 

				global $user_agent_os;

				$os_platform    =   "Other";

				$os_array       =   array(
										'/windows nt 10/i'     =>  'Windows 10',
										'/windows nt 6.3/i'     =>  'Windows 8',
										'/windows nt 6.2/i'     =>  'Windows 8',
										'/windows nt 6.1/i'     =>  'Windows 7',
										'/windows nt 6.0/i'     =>  'Windows Vista',
										'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
										'/windows nt 5.1/i'     =>  'Windows XP',
										'/windows xp/i'         =>  'Windows XP',
										'/windows nt 5.0/i'     =>  'Windows 2000',
										'/windows me/i'         =>  'Windows ME',
										'/win98/i'              =>  'Windows 98',
										'/win95/i'              =>  'Windows 95',
										'/win16/i'              =>  'Windows 3.11',
										'/macintosh|mac os x/i' =>  'Mac OS',
										'/mac_powerpc/i'        =>  'Mac OS',
										'/linux/i'              =>  'Linux',
										'/ubuntu/i'             =>  'Linux',
										'/iphone/i'             =>  'iPhone',
										'/ipod/i'               =>  'iPhone',
										'/ipad/i'               =>  'iPhone',
										'/android/i'            =>  'Android',
										'/blackberry/i'         =>  'BlackBerry'/* ,
										'/webos/i'              =>  'Mobile' */
									);

				foreach ($os_array as $regex => $value) { 

					if (preg_match($regex, $user_agent_os)) {
						$os_platform    =   $value;
					}

				}   

				return $os_platform;

			}
	
	public function counter(){
		
			if(isset($_SERVER['HTTP_REFERER']))
				{
				$refer = $_SERVER['HTTP_REFERER'];
				}
				else
				{
				$refer = "";
				}
			
			$update_quvisitcount="";
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$browser = $this->get_browser_name($user_agent);

			$user_agent_os = $_SERVER['HTTP_USER_AGENT'];
		
			$os = $this->getOS($user_agent_os);

			$ip =$_SERVER["REMOTE_ADDR"];
			// dailycounter
//				$date = jdate("Y/m/d");
			$date = jdate("Y/m/d");
			
				date_default_timezone_set('Asia/Tehran');
			$date_time = jdate("Y/m/d H:i:s ");
				
			if ( isset($this->user->id)){
			$type =$this->user->id ;}else{
			$type = "ناشناس";}

		
			$db = new DB();
		

		$query_rscounter = "SELECT count(id) as countid FROM `counter` WHERE `ip`='".$ip."' and `date` like '".$date."%' ";
		$rscounter = $db->get_query($query_rscounter);
		
		if($rscounter[0]['countid'] == 0){
			$update_quvisitcount = " , `visitcount`=visitcount+1 ";
		}

	   $qsa = "INSERT INTO `counter`(`ip`, `refer`, `date`, `type`, `portalid`, browser, os) VALUES
									('$ip','$refer','$date_time','$type','0', '$browser', '$os')";
		$db->get_insert_query($qsa);

		$query_rsdailycounter = "SELECT count(id) as countid  FROM `dailycounter` WHERE `date`='".$date."' ";
		$rsdailycounter = $db->get_query($query_rsdailycounter);

		
		if($rsdailycounter[0]['countid'] > 0){
			$update_qu = "UPDATE `dailycounter` SET `pagecount`=pagecount+1 $update_quvisitcount  WHERE `date` LIKE '".$date."' ";
			$db->get_insert_query($update_qu);
		}
		elseif($rsdailycounter[0]['countid'] == 0){
				$ins_dayli = "INSERT INTO `dailycounter`(`date`, `visitcount`, `pagecount`) VALUES ('$date','1','1')";
				$db->get_insert_query($ins_dayli);
			}

	}
	
	public function userOnline(){
		
		$page = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$timeoutseconds = 300; // length of session, 20 minutes is the standard
		$timestamp=time();
		$timeout=$timestamp-$timeoutseconds;
        
		$user_id = isset( $this->user->id ) ? $this->user->id : 'کاربر مهمان';
		$server = $_SERVER['REMOTE_ADDR'];
		
		$db = new DB();
		
		$id = session_id();
		$query = "SELECT * FROM useronline WHERE session_id='$id'";
		$is_id_exist = $db->get_query( $query );
		
		if ( $is_id_exist  != 0 ){
			
			$insertSQL = "UPDATE useronline SET timestamp = '$timestamp', user = '$user_id' WHERE session_id = '$id' ";
		}
		else {
			$insertSQL = "INSERT INTO useronline (user, timestamp, ip, session_id) VALUES ('$user_id', '$timestamp', '$server', '$id')";
		}
	
							   
//		  mysql_select_db($database_cn, $cn);
		
		$db->get_insert_query( $insertSQL );
		
//		  $Result1 = mysql_query($insertSQL, $db) or die("Invalid Parametr!");

		$delSQL = "delete from useronline where timestamp<$timeout";
		$db->get_insert_query( $delSQL );

		$onlineSQL = "SELECT DISTINCT ip FROM useronline";
		
		
		return $db->get_query( $onlineSQL );
		
	}

    /**
     * check the user is logged in or not, if logged, then return the user object, else redirect to the login page
     * @param boolean $auth authentication needed or not
     */
    public function checkAuth ( $auth = true ) {
		if(Setting::findByCode('login_cancel')->value == 1){
			redirect('/users/login');
		}
		
        if ( !($this->user = $this->sentinel->check() ) AND $auth ){
			
            $this->session->set_userdata('requested_url' , current_url());
            redirect('/users/login');
        }
        else
            return $this->user;
    }

    function price_format ( $number ) {
        $number = str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' , '7' , '8' , '9' ) , array( '۰' , '۱' , '۲' , '۳' , '۴' , '۵' , '۶' , '۷' , '۸' , '۹' ) , number_format($number));
        return $number . ' تومان';
    }

    public function __getUserCash ( $user_id = null ) {
        $this->checkAuth(true);
        $User = $this->sentinel->getUser();
        return $User->cash;
    }

    public function __updateUserCash ( $amount , $bet_id ) {

        $user = $this->sentinel->getUserRepository();
        $currentCash = $this->__getUserCash();
        if ( $user->update($this->user , array( 'cash' => $currentCash - $amount )) ) {

            $this->load->eloquent('payment/Transaction');
            Transaction::create([
                'trans_id' => $this->user->id ,
                'price' => $amount ,
                'invoice_type' => 3 ,
                'status' => 1 ,
                'cash' => $currentCash - $amount ,
                'user_id' => $this->user->id ,
                'description' => 'برداشت از حساب برای ثبت شرط به شناسه ' . $bet_id ,
            ]);
            return true;
        }
        return false;
    }

    /**
     * Utility function for search in array
     *  @param string $type
     * @param a rray $arra y
     * @return null || int
     */
    function searchArrayForKey ( $key , $value , $array ) {
        foreach ( $array as $index => $val ) {
			
            if ( $val->$key == $value ) {
                return $index;
            }
        }
        return null;
    }
	
	public function getTotalStake () {
		// Check if is not login return 0
		if(!$this->checkLogin()) return(0);
		// Get this user_id stack from bet table
		$this->load->eloquent('bets/Bet');
		$Obj = Bet::where('user_id' , $this->user->id)->get();
		$array = array('user_id'=>$this->user->id, 'status'=>'0');
		$Obj = Bet::where($array)->get();
//		var_dump($Obj);
		$totalStake = 0;
        foreach ( $Obj as $row ):
            $totalStake += $row->stake;
        endforeach;
		// return stake
		return($totalStake);
	}
	
	public function getUser(){
		return($this->user);
	}
	
	public function test(){
		echo "fljkvnfkjnfk";
	}
	
	public function checkLogin ( $auth = true ) {
        if ( !($this->user = $this->sentinel->check() ) AND $auth) {
			
            $this->session->set_userdata('requested_url' , current_url());
            return(false);
        }
        else
            return(true);
	}
	
}
