<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once APPPATH . 'config/db.php';
/**
 * Front Users Controller
 *
 *
 * @copyright   Copyright (c) 2016
 * @license     MIT License
 *
 */
class Users extends Public_Controller {

    public $strength = 8;

    protected $saltLength = 22;

    public $validation_rules = array(
        'login' => array(
            ['field' => 'email' , 'rules' => 'valid_email|trim|required|htmlspecialchars' , 'label' => '&#1575;&#1740;&#1605;&#1740;&#1604;' ] ,
            ['field' => 'password' , 'rules' => 'trim|required|htmlspecialchars' , 'label' => '&#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' ]
        ) ,
        'register' => array(
            ['field' => 'password' , 'rules' => 'trim|required' , 'label' => 'رمزعبور' ] ,
            ['field' => 'email' , 'rules' => 'trim|required' , 'label' => 'ایمیل' ] ,
            ['field' => 'first_name' , 'rules' => 'trim|required' , 'label' => 'نام' ] ,
            ['field' => 'last_name' , 'rules' => 'trim|required' , 'label' => 'نام خانوادگی' ] ,
            ['field' => 'mobile' , 'rules' => 'trim' , 'label' => 'موبایل' ]
        ) ,
        'resetPasswordByEmail' => array(
            ['field' => 'password' , 'rules' => 'trim|required' , 'label' => '&#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' ] ,
        ) ,
        'changePass' => array(
            ['field' => 'OldPassword' , 'rules' => 'trim|required' , 'label' => '&#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' ] ,
            ['field' => 'NewPassword' , 'rules' => 'trim|required' , 'label' => '&#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585; &#1580;&#1583;&#1740;&#1583;' ] ,
            ['field' => 'ConfirmPassword' , 'rules' => 'trim|required' , 'label' => '&#1578;&#1575;&#1740;&#1740;&#1583; &#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' ] ,
        ) ,
        'forgot_password' => array(
            ['field' => 'email' , 'rules' => 'trim|htmlspecialchars|required|valid_email' , 'label' => '&#1588;&#1606;&#1575;&#1587;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ] ,
        ) ,
        'withdraw' => array(
            ['field' => 'amount' , 'rules' => 'trim|htmlspecialchars|required|callback_check_cash|callback_once_per_day' , 'label' => '&#1605;&#1576;&#1604;&#1594;' ] ,
            //['field' => 'account_holder' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => '&#1606;&#1575;&#1605; &#1589;&#1575;&#1581;&#1576; &#1581;&#1587;&#1575;&#1576;' ] ,
            //['field' => 'bank_name' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => '&#1606;&#1575;&#1605; &#1576;&#1575;&#1606;&#1705;' ] ,
            //['field' => 'card_no' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => '&#1588;&#1605;&#1575;&#1585;&#1607; &#1705;&#1575;&#1585;&#1578;' ] ,
            //['field' => 'sheba' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => '&#1588;&#1605;&#1575;&#1585;&#1607; &#1588;&#1576;&#1575;' ] ,
            // ['field' => 'webmoney' , 'rules' => 'htmlspecialchars|trim' , 'label' => '&#1588;&#1605;&#1575;&#1585;&#1607; &#1581;&#1587;&#1575;&#1576; &#1608;&#1576; &#1605;&#1575;&#1606;&#1740;' ] ,
            ['field' => 'tron_address' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => 'آدرس حساب ترون' ] ,
            // ['field' => 'account_number' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => '&#1588;&#1605;&#1575;&#1585;&#1607; &#1581;&#1587;&#1575;&#1576;' ] ,
        ) ,
        'resetPassword' => array(
            ['field' => 'email' , 'rules' => 'trim|htmlspecialchars|required|valid_email' , 'label' => '&#1588;&#1606;&#1575;&#1587;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ] ,
        ) ,
        'editProfile' => array(
            ['field' => 'first_name' , 'rules' => 'trim|htmlspecialchars|required|max_length[255]' , 'label' => '&#1606;&#1575;&#1605;' ] ,
            ['field' => 'last_name' , 'rules' => 'trim|htmlspecialchars|required|max_length[255]' , 'label' => '&#1606;&#1575;&#1605; &#1582;&#1575;&#1606;&#1608;&#1575;&#1583;&#1711;&#1740;' ] ,
            ['field' => 'mobile' , 'rules' => 'trim|required|htmlspecialchars|max_length[15]' , 'label' => '&#1588;&#1605;&#1575;&#1585;&#1607; &#1578;&#1605;&#1575;&#1587;' ] ,
            ['field' => 'address' , 'rules' => 'trim|htmlspecialchars|max_length[500]' , 'label' => '&#1570;&#1583;&#1585;&#1587;' ] ,
        ) ,
        'verify' => array(
            ['field' => 'melli', 'rules' => 'htmlspecialchars|trim'],
            ['field' => 'bank', 'rules' => 'htmlspecialchars|trim'],
        )
    );

    function __construct () {
        parent::__construct();
        $this->load->sentinel();
        $this->load->eloquent('User');
        $this->load->eloquent('settings/Setting');

        //        $this->load->library('email');
    }

    /**
     * &#1608;&#1585;&#1608;&#1583; &#1576;&#1607; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740; &#1608; &#1604;&#1575;&#1711;&#1740;&#1606; &#1705;&#1575;&#1585;&#1576;&#1585;
     */
    function login () {
        // If redirect session var set redirect to home page

        $db = new DB();

        $redirect_to = '/dashboard';
        if ( !$redirect_to ) {
            $redirect_to = '/';
        }

        if( Setting::findByCode('login_cancel')->value == 1 ){
            $this->logout();
        }
        // If user is already logged in redirect to desired location
        if ( $this->sentinel->check() ) {
            redirect($redirect_to);
        }
        $this->smart->assign(array( 'title' => '&#1608;&#1585;&#1608;&#1583; &#1576;&#1607; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ));

        // Form Validation & Process Form
        if ( $this->formValidate() ) {
            $credentials = [
                "email" => $this->input->post('email') ,
                "password" => $this->input->post('password')
            ];
            $remember = $this->input->post('remember_me');
            $check_login = $this->sentinel->authenticate($credentials , $remember);

            if ( $check_login ) {
                if($check_login->status == 0)
                    $this->logout();
                else{
                    $token = $this->create_token();

                    $value = ['game_token' => [ $token,'s'] ];
                    $where = ['id' => [ $check_login->id, 'i']];
                    $db->update( 'users', $value, $where );
                    setcookie('code', $token, time() + (86400), "/");

                    if ( $this->uri->segment(1) == ADMIN_PATH )
                        redirect(site_url(ADMIN_PATH));
                    else
                        redirect($redirect_to);
                }

            } else {
                $this->message->set_message('&#1575;&#1740;&#1605;&#1740;&#1604; &#1608; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1608;&#1575;&#1585;&#1583; &#1588;&#1583;&#1607; &#1607;&#1605; &#1582;&#1608;&#1575;&#1606;&#1740; &#1606;&#1583;&#1575;&#1585;&#1583;' , 'fail' , '&#1608;&#1585;&#1608;&#1583; &#1576;&#1607; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/login')->redirect();
            }
        }
        // If the user was attempting to log into the admin panel use the admin theme
        if ( $this->uri->segment(1) == ADMIN_PATH ) {
            $this->smart->assign(array( 'title' => '&#1608;&#1585;&#1608;&#1583; &#1576;&#1607; &#1662;&#1606;&#1604; &#1605;&#1583;&#1740;&#1585;&#1740;&#1578;&#1740;' ));
            $this->smart->load('default' , TRUE);
            $this->smart->setLayout('login_layout');
            $this->smart->view("login");
        }
        else {
            $this->smart->assign(array(
                    'loginaction' => site_url("users/login") ,
                    'registeraction' => site_url("users/register")
                )
            );
            $this->smart->view("login");
        }
    }

    /**
     * &#1579;&#1576;&#1578; &#1606;&#1575;&#1605; &#1705;&#1575;&#1585;&#1576;&#1585; &#1576;&#1575; &#1575;&#1587;&#1578;&#1601;&#1575;&#1583;&#1607; &#1575;&#1586; &#1578;&#1575;&#1740;&#1740;&#1583; &#1575;&#1740;&#1605;&#1740;&#1604;
     */
    public function register ( $user_aff = null ) {

        if( Setting::findByCode('registeration_cancel')->value == 1 ){
            $this->logout();
        }

        $this->checkAuth(false);
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            if ( $this->formValidate() ) {
                if ( $this->input->post('password') != $this->input->post('confirmPassword') ) {
                    $this->message->set_message('رمزعبور و تایید رمزعبور یکسان نیست.' , 'warning' , 'تغییر کلمه عبور' , 'users/register')->redirect();
                    exit();
                }
                $credentials = [
                    'username' => $this->input->post('email') ,
                    'email' => $this->input->post('email') ,
                    'password' => $this->input->post('password') ,
                    'mobile' => $this->input->post('mobile') ,
                    'first_name' => $this->input->post('first_name') ,
                    'last_name' => $this->input->post('last_name') ,
                    'instagram' => $this->input->post('instagram') ,
                ];
                $email = $this->input->post('email');
                $pass = $this->input->post('password');
                //register user
                if ( $this->sentinel->validForCreation($credentials) ) {
                    $user = $this->sentinel->registerAndActivate($credentials);

                    if ( $user ) {

                        $user->roles()->sync(array( 6 ));
                        /**
                         * Affiliate for user
                         */
                        if ( $user_aff ) {
                            $this->load->eloquent('Affiliate');
                            Affiliate::create([
                                'user_id' => $user_aff ,
                                'invited_user_id' => $user->id ,
                            ]);
                        }
                        //creating activation row for user
                        $site_name = Setting::findByCode('site_name')->value;
                        $this->load->library('email');
                        //                        $this->email->from('noreply@betfars.com' , $site_name);
                        $this->email->from('info@pikbet.net' , $site_name);
                        $this->email->to($user->email);
                        $this->email->subject($site_name . ' - فعالسازی حساب کاربری');
                        $this->email->message("از ثبت نام شما در وب سایت متشکریم\n از این پس با اطلاعات زیر می توانید در سایت وارد شوید:.\n ایمیل: $email \n کلمه عبور: $pass \n\nبا تشکر .\n\n تیم " . $site_name);
                        $this->email->send();
                        $this->sentinel->login($user);
                        $this->message->set_message('ثبت نام کامل شد' , 'success' , 'ثبت نام حساب کاربری' , 'dashboard')->redirect();
                    }
                    else
                        $this->message->set_message('مشکل در ثبت کاربر' , 'warning' , 'ثبت نام حساب کاربری' , 'users/register')->redirect();
                }
            }
            else {
                $this->message->set_message('&#1582;&#1591;&#1575; &#1583;&#1585; &#1601;&#1740;&#1604;&#1583;&#1607;&#1575;&#1740; &#1608;&#1585;&#1608;&#1583;&#1740;' . validation_errors() , 'fail' , '&#1579;&#1576;&#1578; &#1606;&#1575;&#1605; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/register')->redirect();
            }
        }
        $this->smart->assign([
            'title' => 'ثبت نام' ,
            'action' => 'users/register/' . $user_aff ,
            'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('register');
    }

    public function changePass () {
        $this->checkAuth(true);
        if ( $this->input->post('submitbtn') ) {
            if ( $this->formValidate(false) ) {
                //&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;
                if ( $this->input->post('NewPassword') == $this->input->post('ConfirmPassword') ) {
                    $credentials['password'] = $this->input->post('OldPassword');
                    $credentials['email'] = $this->user->email;
                    if ( $this->sentinel->validateCredentials($this->user , $credentials) ) {
                        $reminder = $this->sentinel->getReminderRepository()->create($this->user);
                        $this->sentinel->getReminderRepository()->complete($this->user , $reminder->code , $this->input->post('NewPassword'));
                        $this->message->set_message('&#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1576;&#1585;&#1608;&#1586;&#1585;&#1587;&#1575;&#1606;&#1740; &#1588;&#1583;' , 'success' , '&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1585;&#1605;&#1586; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'dashboard')->redirect();
                    }
                    else {
                        $this->message->set_message('&#1585;&#1605;&#1586;&#1593;&#1576;&#1608;&#1585; &#1601;&#1593;&#1604;&#1740; &#1606;&#1575;&#1583;&#1585;&#1587;&#1578; &#1575;&#1587;&#1578;.' , 'warning' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' , 'users/changePass')->redirect();
                    }
                }
                else {
                    $this->message->set_message('&#1585;&#1605;&#1586;&#1593;&#1576;&#1608;&#1585; &#1608; &#1578;&#1575;&#1740;&#1740;&#1583; &#1585;&#1605;&#1586;&#1593;&#1576;&#1608;&#1585; &#1740;&#1705;&#1587;&#1575;&#1606; &#1606;&#1740;&#1587;&#1578;.' , 'warning' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' , 'users/changePass')->redirect();
                }
            }
            else {
                $this->message->set_message('&#1582;&#1591;&#1575; &#1583;&#1585; &#1583;&#1575;&#1583;&#1607; &#1607;&#1575;&#1740; &#1608;&#1585;&#1608;&#1583;&#1740;. ' . validation_errors() , 'fail' , ' &#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1585;&#1605;&#1586; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/changePass')->redirect();
            }
        }
        else {
            $this->smart->assign(array(
                    'title' => '&#1578;&#1594;&#1740;&#1740;&#1585; &#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;' ,
                    'totalStake' => $this->getTotalStake(),
                )
            );
            $this->smart->view("changePass");
        }
    }

    /**
     * &#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740; &#1608; &#1662;&#1585;&#1608;&#1601;&#1575;&#1740;&#1604;
     */
    public function editProfile () {
        $this->checkAuth(true);
        if ( $this->input->post('submitbtn') ) {
            if ( $this->formValidate(false) ) {
                $credentials = [
                    "first_name" => $this->input->post('first_name' , true) ,
                    "last_name" => $this->input->post('last_name' , true) ,
                    "address" => $this->input->post('address' , true) ,
                    "mobile" => $this->input->post('mobile' , true) ,
                    "job" => $this->input->post('job' , true) ,
                    "email" => $this->input->post('email' , true) ,
                    "website" => $this->input->post('website' , true) ,
                    "degree" => $this->input->post('degree' , true) ,
                ];
                //&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;
                //                if ( $this->input->post('password') ) {
                //                    if ( $this->input->post('password') == $this->input->post('password_confirm') ) {
                //                        $reminder = $this->sentinel->getReminderRepository()->create($this->user);
                //                        $this->sentinel->getReminderRepository()->complete($this->user , $reminder->code , $this->input->post('password'));
                //                    }
                //                    else {
                //                        $this->message->set_message('&#1585;&#1605;&#1586;&#1593;&#1576;&#1608;&#1585; &#1608; &#1578;&#1575;&#1740;&#1740;&#1583; &#1585;&#1605;&#1586;&#1593;&#1576;&#1608;&#1585; &#1740;&#1705;&#1587;&#1575;&#1606; &#1606;&#1740;&#1587;&#1578;.' , 'warning' , '&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/editProfile')->redirect();
                //                    }
                //                }
                //&#1576;&#1585;&#1608;&#1586;&#1585;&#1587;&#1575;&#1606;&#1740; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1608;&#1575;&#1585;&#1583; &#1588;&#1583;&#1607; &#1578;&#1608;&#1587;&#1591; &#1705;&#1575;&#1585;&#1576;&#1585;
                $userrr = $this->sentinel->getUserRepository();
                if ( $userrr->update($this->user , $credentials) ) {

                    $this->message->set_message('&#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1576;&#1585;&#1608;&#1586;&#1585;&#1587;&#1575;&#1606;&#1740; &#1588;&#1583;' , 'success' , '&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'project/innovations/submit-form')->redirect();
                }
                else {
                    $this->message->set_message('&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1576;&#1575; &#1605;&#1588;&#1705;&#1604; &#1605;&#1608;&#1575;&#1580;&#1607; &#1588;&#1583;. &#1605;&#1580;&#1583;&#1583; &#1578;&#1604;&#1575;&#1588; &#1705;&#1606;&#1740;&#1583;' , 'fail' , '&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/editProfile')->redirect();
                }
            }
            else {
                $this->message->set_message('&#1582;&#1591;&#1575; &#1583;&#1585; &#1583;&#1575;&#1583;&#1607; &#1607;&#1575;&#1740; &#1608;&#1585;&#1608;&#1583;&#1740;. ' . validation_errors() , 'fail' , ' &#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/editProfile')->redirect();
            }
        }
        else {
            $this->smart->assign(array(
                    'title' => '&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1575;&#1591;&#1604;&#1575;&#1593;&#1575;&#1578; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ,
                    'action' => base_url('users/editProfile') ,
                )
            );
            $this->smart->view("information_form");
        }
    }

    /**
     * &#1582;&#1585;&#1608;&#1580; &#1575;&#1586; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;
     */
    public function logout () {
        if ( $user = $this->sentinel->check() ) {
            $db = new DB();

            if(isset($_COOKIE['code'])){

                $code = $_COOKIE['code'];
                $game_token = $db->select('users', 'game_token', 'id', $user->id);

                if ( $code == $game_token[0]['game_token']){
                    $value = ['game_token' => [ NULL, 's'] ];
                    $where = ['id' => [$user->id, 'i'] ];

                    $db->update( 'users', $value, $where );
                    setcookie('code', '', time() - (3600), "/");
                }
            }

            $this->sentinel->logout($user);
            //			session_destroy();

            if ( $this->uri->segment(1) == ADMIN_PATH ) {
                redirect(ADMIN_PATH . '/users/login');
            }
            else {
                redirect('users/login');
            }
        }
        else
            redirect('users/login');
    }

    /**
     * &#1617;this method, create a reminder and send the reminder code for user by email,
     * then user must use the new code for set the new password
     * NOTE: this function works for admin side
     */
    function forgot_password () {
        $this->load->repository('ReminderRepository');
        // If user was in admin panel load admin view
        if ( $this->uri->segment(1) == ADMIN_PATH ) {

            if ( $this->formValidate(false) ) {
                $email = $this->input->post('email');
                $User = $this->sentinel->getUserRepository();
                $User = $User->findByCredentials(['email' => $email ]);
                $Reminder = new Mirook\Users\Reminders\ReminderRepository($User);
                // Create reminders
                if ( $User ) {
                    // Check if reminder code already exist for user, if not so create reminder first
                    if ( !$this->sentinel->getReminderRepository()->exists($User) ) {
                        $new_reminder = $Reminder->create($User);
                        // send reminder code to the user mobile number
                        if ( $this->__sendSmsReminder($User , $new_reminder) )
                            $this->message->set_message('&#1705;&#1583; &#1575;&#1593;&#1578;&#1576;&#1575;&#1585;&#1587;&#1606;&#1580;&#1740; &#1576;&#1607; &#1575;&#1740;&#1605;&#1740;&#1604; &#1588;&#1605;&#1575; &#1575;&#1585;&#1587;&#1575;&#1604; &#1588;&#1583;. &#1604;&#1591;&#1601;&#1575; &#1705;&#1583; &#1605;&#1585;&#1576;&#1608;&#1591;&#1607; &#1585;&#1575; &#1583;&#1585; &#1601;&#1740;&#1604;&#1583; &#1586;&#1740;&#1585; &#1608;&#1575;&#1585;&#1583; &#1705;&#1606;&#1740;&#1583;' , 'success' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , ADMIN_PATH . '/users/users/resetPasswordBySms/' . $User->id)->redirect();
                    }
                    else {
                        $this->message->set_message('&#1604;&#1591;&#1601;&#1575; &#1705;&#1583; &#1575;&#1585;&#1587;&#1575;&#1604; &#1588;&#1583;&#1607; &#1576;&#1607; &#1578;&#1604;&#1601;&#1606; &#1607;&#1605;&#1585;&#1575;&#1607; &#1582;&#1608;&#1583; &#1585;&#1575; &#1583;&#1585; &#1601;&#1740;&#1604;&#1583; &#1586;&#1740;&#1585; &#1608;&#1575;&#1585;&#1583; &#1705;&#1606;&#1740;&#1583;' , 'success' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , ADMIN_PATH . '/users/users/resetPasswordBySms/' . $User->id)->redirect();
                    }
                }
            }

            $this->smart->assign(array( 'title' => '&#1608;&#1585;&#1608;&#1583; &#1576;&#1607; &#1662;&#1606;&#1604; &#1605;&#1583;&#1740;&#1585;&#1740;&#1578;&#1740;' ));
            $this->smart->setLayout('login_layout');
            $this->smart->load('default' , TRUE);
            $this->smart->view("admin/login");
        }
        else {
            $this->smart->view("users/forgot_password");
        }
    }

    /**
     * &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1587;&#1605;&#1578; &#1601;&#1585;&#1575;&#1606;&#1578; &#1576;&#1607; &#1589;&#1608;&#1585;&#1578; &#1575;&#1740;&#1580;&#1705;&#1587;
     */
    public function resetPassword ()
    {
        if ($this->formValidate(false)) {
            $email = $this->input->post('email');
            $User = $this->sentinel->getUserRepository();
            $User = $User->findByCredentials(['email' => $email]);
            $Reminder = $this->sentinel->getReminderRepository();
            if (isset($User->email))
                $exist = (trim(strtolower($email)) == trim(strtolower($User->email))) ? true : false;
            else
                $exist = false;
            // Create reminders
            //            $mail = $this->sendMail($User);
            //            $mail = $this->sendMail($User);
            //            die();
            if (isset($User->email)) {

                // Check if reminder code already exist for user, if not so create reminder first
                if ( $exist === true ) {

                    $reset_code = $this->createResetPassCode();
                    $reset_code = substr_replace($reset_code, $User->id.'Z', 4, 0);

                    $message = new stdClass();
                    $message->to = $User->email;
                    $message->subject = '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;';
                    $message->mail_message = $this->createHTMLPageForResetPassword( $reset_code );

                    $mail = $this->sendMail($message);

                    if ( $mail === true ){

                        $User->update(array('remember_link'=> $reset_code ));

                        $this->message->set_message('&#1740;&#1705; &#1575;&#1740;&#1605;&#1740;&#1604; &#1580;&#1607;&#1578; &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1576;&#1585;&#1575;&#1740; &#1588;&#1605;&#1575; &#1575;&#1585;&#1587;&#1575;&#1604; &#1588;&#1583;.', 'success', '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;', 'users/login')->redirect();
                    }
                    else if ( $mail === false ) {
                        $this->message->set_message('&#1575;&#1740;&#1605;&#1740;&#1604; &#1608;&#1575;&#1585;&#1583; &#1588;&#1583;&#1607; &#1606;&#1575;&#1605;&#1593;&#1578;&#1576;&#1585; &#1605;&#1740; &#1576;&#1575;&#1588;&#1583;', 'success', '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;', 'users/login')->redirect();
                    }

                }
                else if ( $exist === false ){
                    $this->message->set_message('&#1575;&#1740;&#1605;&#1740;&#1604; &#1608;&#1575;&#1585;&#1583; &#1588;&#1583;&#1607; &#1740;&#1575;&#1601;&#1578; &#1606;&#1588;&#1583;.' , 'success' , '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , 'users/login')->redirect();
                }
            }
            else {
                $this->message->set_message('&#1575;&#1740;&#1605;&#1740;&#1604; &#1608;&#1575;&#1585;&#1583; &#1588;&#1583;&#1607; &#1740;&#1575;&#1601;&#1578; &#1606;&#1588;&#1583;.' , 'success' , '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , 'users/login')->redirect();
            }
        }else {
            $this->smart->view('reset_password');
        }
    }



    public function sendMail ( $message ){
        $this->load->library('email');

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            //            'smtp_user' => 'betfarsInfo@gmail.com',
            //            'smtp_pass' => 'betf@rs123',
            'smtp_user' => '4ubetinfo@gmail.com',
            'smtp_pass' => '4ubet@123',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'smtp_timeout' => 30
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->to($message->to);
        //        $this->email->from('betfarsInfo@gmail.com','Betfars.com');
        $this->email->from('4ubetinfo@gmail.com','4ubet.com');
        $this->email->subject( $message->subject );
        $this->email->message($message->mail_message);

        $result = $this->email->send();

        return $result;


    }

    public function createHTMLPageForResetPassword ( $reset_code ){

        $reset_url = site_url("users/resetPasswordByEmail/$reset_code/");

        $htmlContent = '<h1>&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;</h1>';
        $htmlContent .= '<p>&#1576;&#1585;&#1575;&#1740; &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1582;&#1608;&#1583; &#1575;&#1586; &#1604;&#1740;&#1606;&#1705; &#1586;&#1740;&#1585; &#1575;&#1587;&#1578;&#1601;&#1575;&#1583;&#1607; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;</p>';
        $htmlContent .= $reset_url;

        return $htmlContent;
    }

    public function createResetPassCode ( ){

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 50; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);

    }

    public function __sendEmailReminder ( $User , $Reminder ) {
        $this->load->library('email');
        //        $this->email->from('noreply@betfars.com');
        $this->email->from('4ubetinfo@gmail.com');
        $this->email->to($User->email);
        $this->email->subject('&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585;');
        $mail_message = "&#1576;&#1585;&#1575;&#1740; &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1705;&#1604;&#1605;&#1607; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576;&#1578;&#1575;&#1606; &#1583;&#1585; &#1608;&#1576; &#1587;&#1575;&#1740;&#1578;  &#1548; &#1575;&#1586; &#1604;&#1740;&#1606;&#1705; &#1586;&#1740;&#1585; &#1575;&#1587;&#1578;&#1601;&#1575;&#1583;&#1607; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;\n" . site_url('users/resetPasswordByEmail/' . $User->id . '/' . $Reminder->code);
        $this->email->message($mail_message);
        if ( $this->email->send() )
            return true;
        else
            return false;
    }

    /**
     * set reminder and reset password by email
     * @param type $user_id
     * @param type $reminder_code
     */

    public function resetPasswordByEmail ( $reset_code = null ) {

        if ( $this->input->post('user_id') ) {
            $user_id = $this->input->post('user_id');
            $reminder_code = $this->input->post('reminder_code');
        }
        else if ( $reset_code != null ){
            $user_id = substr( $reset_code, 4, strpos( $reset_code,'Z',0)-4 );
        }

        if ( isset( $user_id ) ){
            $user = $this->sentinel->getUserRepository()->find($user_id);

            $this->smart->assign(['title' => '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ]);

            if ( $this->formValidate(FALSE) ){
                if ( $user->remember_link == $reminder_code ){
                    $password = $this->hash($this->input->post('password'));
                    $user->update( array( 'password'=>$password , 'remember_link' => NULL ) );

                    $this->message->set_message('&#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1578;&#1594;&#1740;&#1740;&#1585; &#1740;&#1575;&#1601;&#1578;. &#1581;&#1575;&#1604;&#1575; &#1605;&#1740; &#1578;&#1608;&#1575;&#1606;&#1740;&#1583; &#1576;&#1575; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1580;&#1583;&#1740;&#1583; &#1582;&#1608;&#1583; &#1608;&#1575;&#1585;&#1583; &#1587;&#1575;&#1740;&#1578; &#1588;&#1608;&#1740;&#1583;.' , 'success' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , '/users/login')->redirect();
                }
            }
            else if ( $reset_code == $user->remember_link ){
                $this->smart->assign(array(
                    'reminder_code' => $reset_code ,
                    'user_id' => $user_id ,
                ));
                $this->smart->view('users/set_new_password');
            }
            else {
                $this->message->set_message('&#1705;&#1583; &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1606;&#1575;&#1605;&#1593;&#1578;&#1576;&#1585; &#1575;&#1587;&#1578;' , 'fail' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , '/users/login')->redirect();
            }

        }


        if ( $this->input->post('user_id') ) {
            $user_id = $this->input->post('user_id');
            $reminder_code = $this->input->post('reminder_code');
        }
        $user = $this->sentinel->getUserRepository()->find($user_id);

        $reminder = $this->sentinel->getReminderRepository();
        $this->smart->assign(['title' => '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ]);
        if ( $reminder->exists($user) ) {

            if ( $this->formValidate(FALSE) AND $reminder->complete($user , $reminder_code , $this->input->post('password')) ) {
                $this->message->set_message('&#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1578;&#1594;&#1740;&#1740;&#1585; &#1740;&#1575;&#1601;&#1578;. &#1581;&#1575;&#1604;&#1575; &#1605;&#1740; &#1578;&#1608;&#1575;&#1606;&#1740;&#1583; &#1576;&#1575; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1580;&#1583;&#1740;&#1583; &#1582;&#1608;&#1583; &#1608;&#1575;&#1585;&#1583; &#1587;&#1575;&#1740;&#1578; &#1588;&#1608;&#1740;&#1583;.' , 'success' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , '/users/login')->redirect();
            }
            else {
                $this->smart->assign(array(
                    'reminder_code' => $reminder_code ,
                    'user_id' => $user_id ,
                ));
                $this->smart->view('users/set_new_password');
            }
        }
        else
            $this->message->set_message('&#1705;&#1583; &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1606;&#1575;&#1605;&#1593;&#1578;&#1576;&#1585; &#1575;&#1587;&#1578;' , 'fail' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , '/users/login')->redirect();
    }


    public function resetPasswordByEmail1 ( $user_id = null , $reminder_code = null ) {

        if ( $this->input->post('user_id') ) {
            $user_id = $this->input->post('user_id');
            $reminder_code = $this->input->post('reminder_code');
        }
        $user = $this->sentinel->getUserRepository()->find($user_id);

        $reminder = $this->sentinel->getReminderRepository();
        $this->smart->assign(['title' => '&#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' ]);
        if ( $reminder->exists($user) ) {

            if ( $this->formValidate(FALSE) AND $reminder->complete($user , $reminder_code , $this->input->post('password')) ) {
                $this->message->set_message('&#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1578;&#1594;&#1740;&#1740;&#1585; &#1740;&#1575;&#1601;&#1578;. &#1581;&#1575;&#1604;&#1575; &#1605;&#1740; &#1578;&#1608;&#1575;&#1606;&#1740;&#1583; &#1576;&#1575; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1580;&#1583;&#1740;&#1583; &#1582;&#1608;&#1583; &#1608;&#1575;&#1585;&#1583; &#1587;&#1575;&#1740;&#1578; &#1588;&#1608;&#1740;&#1583;.' , 'success' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , '/users/login')->redirect();
            }
            else {
                $this->smart->assign(array(
                    'reminder_code' => $reminder_code ,
                    'user_id' => $user_id ,
                ));
                $this->smart->view('users/set_new_password');
            }
        }
        else
            $this->message->set_message('&#1705;&#1583; &#1576;&#1575;&#1586;&#1740;&#1575;&#1576;&#1740; &#1606;&#1575;&#1605;&#1593;&#1578;&#1576;&#1585; &#1575;&#1587;&#1578;' , 'fail' , '&#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585;' , '/users/login')->redirect();
    }

    /**
     * Activate user email after registering
     * @param type $user_id
     * @param type $activation_code
     * @return type
     */
    function activate ( $user_id = NULL , $activation_code = NULL ) {
        // Init
        $data = array();

        // Check that user email activation is enabled
        //        if (!$this->settings->users_module->email_activation) {
        //            return show_404();
        //        }

        if ( !$user_id || !$activation_code ) {
            return show_404();
        }
        // Show 404 if user not found
        if ( !$user = $this->sentinel->findById($user_id) ) {
            return show_404();
        }
        if ( $activation = $this->sentinel->getActivationRepository()->exists($user) ) {

            if ( $this->sentinel->getActivationRepository()->complete($user , $activation->code) ) {
                if ( $this->sentinel->login($user) ) {
                    $this->message->set_message('&#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1601;&#1593;&#1575;&#1604; &#1608; &#1575;&#1740;&#1605;&#1740;&#1604; &#1588;&#1605;&#1575; &#1578;&#1575;&#1740;&#1740;&#1583; &#1588;&#1583;.' , 'success' , '&#1601;&#1593;&#1575;&#1604;&#1587;&#1575;&#1586;&#1740; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'dashboard')->redirect();
                }
                else
                    echo '&#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1601;&#1593;&#1575;&#1604; &#1588;&#1583; &#1575;&#1605;&#1575; &#1593;&#1605;&#1604;&#1740;&#1575;&#1578; &#1604;&#1575;&#1711;&#1740;&#1606; &#1589;&#1608;&#1585;&#1578; &#1606;&#1711;&#1585;&#1601;&#1578;. &#1576;&#1585;&#1575;&#1740; &#1608;&#1585;&#1608;&#1583; &#1605;&#1580;&#1583;&#1583; &#1578;&#1604;&#1575;&#1588; &#1705;&#1606;&#1740;&#1583;.';
                exit();
            }
            else {
                $this->message->set_message('&#1601;&#1593;&#1575;&#1604; &#1587;&#1575;&#1586;&#1740; &#1575;&#1606;&#1580;&#1575;&#1605; &#1606;&#1588;&#1583;.' , 'fail' , '&#1601;&#1593;&#1575;&#1604;&#1587;&#1575;&#1586;&#1740; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;' , 'users/login')->redirect();
            }
        }
        else
            redirect('/users/login');
    }

    public function representation () {
        $this->checkAuth(true);

        $this->load->eloquent('Affiliate');
        $this->load->eloquent('payment/Transaction');
        $sub_count = 0;
        $mySub = Affiliate::where('user_id' , $this->user->id)->get();

        if ( $mySub ) {
            //            $trans = Transaction::where('invoice_type' , 5)->where('user_id' , $this->user->id)->get();
            //
            //
            //
            //            $affSum = 0;
            //            foreach ( $trans as $val ):
            //                $affSum += $val->price;
            //            endforeach;

            $sub_count = $mySub->count();
            $this->smart->assign([
                'mySub' => $mySub ,
                //                'affSum' => $affSum ,
                'affSum' => $this->get_affiliate($this->user->id) ,
                //                'affCount' => $trans->count() ,
                'totalStake' => $this->getTotalStake(),
            ]);
        }
        $this->smart->assign(['sub_count' => $sub_count ]);
        $this->smart->view('representation');
    }

    public function get_affiliate ( $user_id ){

        $db = new DB();

        $query = "SELECT SUM(price) as price FROM transactions WHERE (invoice_type LIKE '%9%' OR invoice_type = '5' ) AND user_id = '$user_id'";

        $aff = $db->get_query( $query);

        if ( $aff == 0 )
            return 0;

        return $aff[0]['price'];



    }

    public function messages(){
        $this->checkAuth(true);
        $user_id = $this->user->id;

        $db = new DB();
        $query = "SELECT * FROM message WHERE (user_id =$user_id OR user_id=0) AND status = 1 ";
        $message = $db->get_query($query);

        $query = "SELECT id FROM message WHERE user_id = $user_id AND is_read = '0' AND status = '1'";
        $read = $db->get_query($query);

        $query = "UPDATE message SET is_read = '1' WHERE user_id = $user_id AND is_read = '0' AND status = '1' ";
        $db->get_insert_query($query);

        $this->smart->assign([
            'message' => $message,
            'badget' => $read,
            'cash' => $this->__getUserCash() ,
            'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('Message');
    }


    public function withdraw ( $id = null ) {
        $this->checkAuth(true);
        $db = new DB();
        $amount_new = $db->multi_select('change_unit', 'amount', ['fromid',
            'toid'], [4,
            2]);
        $ratio_tron = $amount_new[0]['amount'];
        $pay_code = time() * $this->user->id;

        $this->load->eloquent('settings/Setting');
        $this->load->eloquent('Withdraw');
        $this->load->eloquent('User');

        $min_value = Setting::findByCode('min_value_request')->value;
        $max_value = Setting::findByCode('max_value_request')->value;

        if ( $this->formValidate(false) ) {
            $data = [
                'amount' => $this->input->post('amount') ,
                /*'account_holder' => $this->input->post('account_holder') ,
                'bank_name' => $this->input->post('bank_name') ,
                'card_no' => $this->input->post('card_no') ,
                'sheba' => $this->input->post('sheba') ,*/
                // 'webmoney' => $this->input->post('webmoney') ,
                // 'account_number' => $this->input->post('account_number') ,
                'tron_address' => $this->input->post('tron_address') ,
                'status' => 2 ,
                'user_id' => $this->user->id ,
                'pay_code' => $pay_code,
            ];

            $this->load->eloquent('withdraw');
            $withdraw = Withdraw::create($data);
            $priceeee = $data['amount'];

            //            $email = $this->user->email;
            //            $this->load->library('email');
            //            $this->email->from('ticket@betfars.com' , '&#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1580;&#1575;&#1740;&#1586;&#1607; &#1580;&#1583;&#1740;&#1583;');
            //            $this->email->to($this->sentinel->findById(6)->email);
            //            $this->email->subject('&#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1580;&#1575;&#1740;&#1586;&#1607; &#1580;&#1583;&#1740;&#1583; - &#1576;&#1578; &#1601;&#1575;&#1585;&#1587;');
            //            $this->email->message("&#1588;&#1605;&#1575; &#1740;&#1705; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1580;&#1575;&#1740;&#1586;&#1607; &#1580;&#1583;&#1740;&#1583; &#1575;&#1586; &#1587;&#1608;&#1740; &#1705;&#1575;&#1585;&#1576;&#1585;&#1575;&#1606; &#1583;&#1575;&#1585;&#1740;&#1583;:.\n &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1583;&#1607;&#1606;&#1583;&#1607;: $email \n &#1605;&#1576;&#1604;&#1594;: $priceeee \n");
            //            $this->email->send();
            $user = $this->sentinel->getUserRepository();
            $currentCash = $this->__getUserCash();
            if ( $user->update($this->user , array( 'cash' => $currentCash - $data['amount'] )) ) {
                $amount_tron = $data['amount'] / $ratio_tron;
                $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
                $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
                $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
                $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
                $tron->setAddress("TWrnYf4QjJaJaJJu2wANujiohtFu5jDBem");
                $tron->setPrivateKey("a167adb0de6bf29337caefe50f2a39829eb85f08af0d4b84e0ce96bdf384dc32");
                $transfer = $tron->send( /*'TSsDWJbNMqJNZuXDC7JhuPSdrAoUZCTu2p'*/ $data['tron_address'], $amount_tron);

                if (isset($transfer['result']) && $transfer['result']) {
                    $this->load->eloquent('payment/Transaction');
                    $withdraw->update(array('status'=> 1 ));
                    Transaction::create([
                        'trans_id' => $transfer['txid'] ,
                        'price' => $data['amount'] ,
                        'invoice_type' => 4 ,
                        'cash' => $currentCash - $data['amount'] ,
                        'user_id' => $this->user->id ,
                        'description' => 'درخواست جایزه' ,
                        'pay_code' => $pay_code,
                        'status' => 1,
                    ]);
                    /*Transaction::create([
                        'trans_id' => $this->user->id ,
                        'price' => $data['amount'] ,
                        'invoice_type' => 4 ,
                        'cash' => $currentCash - $data['amount'] ,
                        'user_id' => $this->user->id ,
                        'description' => 'درخواست جایزه' ,
                        'pay_code' => $pay_code,
                    ]);*/
                    $this->message->set_message('درخواست شما ثبت و به حسابتان واریز شد.' , 'success' , 'درخواست جایزه' , 'users/withdraw')->redirect();
                }else{
                    $this->message->set_message('مشکل در برداشت', 'fail', 'درخواست جایزه', 'users/withdraw')->redirect();
                }

            }
        }

        /*elseif( $id != null){
            $changeStatus = Withdraw::where('id' , $id)->where('status',2)->where('user_id',$this->user->id)->get();
            if(isset($changeStatus[0])){

                $get_pay_code = Withdraw::where('id' , $id)->where('status',2)->where('user_id',$this->user->id)->get();
                $withdraw_pay_code = $get_pay_code[0]['pay_code'];

                $amount = $changeStatus[0]->amount;
                $this->user->cash += $amount ;
                $backamount = [ 'cash' => $this->user->cash];
                $updatecash = $this->sentinel->getUserRepository();
                $updatecash->update($this->user , $backamount);
                Withdraw::where('id' , $id)->update(array('status'=> 3 ));



                $this->load->eloquent('payment/Transaction');
                Transaction::where('pay_code' , $withdraw_pay_code)->update(array('status'=> 2));


                Transaction::create([
                    'trans_id' => $this->user->id ,
                    'price' => $amount ,
                    'invoice_type' => 21 ,
                    'cash' => $this->user->cash ,
                    'user_id' => $this->user->id ,
                    'description' => '&#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1576;&#1585;&#1583;&#1575;&#1588;&#1578; &#1575;&#1586; &#1591;&#1585;&#1601; &#1705;&#1575;&#1585;&#1576;&#1585; &#1604;&#1594;&#1608; &#1588;&#1583;.' ,
                    'pay_code' => $pay_code,
                    'status' => 2,
                ]);
                $this->message->set_message('&#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1576;&#1585;&#1583;&#1575;&#1588;&#1578; &#1575;&#1586; &#1591;&#1585;&#1601; &#1705;&#1575;&#1585;&#1576;&#1585; &#1604;&#1594;&#1608; &#1588;&#1583;.' , 'success' , '&#1604;&#1594;&#1608; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1580;&#1575;&#1740;&#1586;&#1607;' , 'users/withdraw')->redirect();

            }
        }*/




        $withdrawList = Withdraw::where('user_id' , $this->user->id)->get();
        $this->smart->assign([
            'cash' => $this->__getUserCash() ,
            'withdrawList' => $withdrawList ,
            'action' => site_url('users/withdraw') ,
            'totalStake' => $this->getTotalStake(),
            'max_value' => $max_value,
            'min_value' => $min_value,
            'ratio_tron' => $ratio_tron,
        ]);
        $this->smart->view('Withdraw');

    }


    /*
    * Form Validation callback to check that the provided mobile number exists or not.
    */

    function email_exists ( $email ) {
        $credentials = ['email' => $email ];
        $User = $this->sentinel->getUserRepository()->where($credentials)->first();
        if ( isset($User) ) {
            $this->form_validation->set_message('email_exists' , "&#1575;&#1740;&#1605;&#1740;&#1604; &#1608;&#1575;&#1585;&#1583; &#1588;&#1583;&#1607; &#1583;&#1585;&#1581;&#1575;&#1604; &#1581;&#1575;&#1590;&#1585; &#1583;&#1585; &#1587;&#1575;&#1740;&#1578; &#1579;&#1576;&#1578; &#1606;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;");

            return FALSE;
        }
        else {
            $_POST['user'] = $User;

            return TRUE;
        }
    }

    /*
    * Form Validation callback to check that the provided mobile number exists or not.
    */

    function min_amount ( $amount ) {

        $this->load->eloquent('settings/Setting');

        $min_withdraw = Setting::findByCode('min_value_request')->value;

        if ( $amount <= $min_withdraw ) {
            $this->form_validation->set_message('min_amount' , "&#1581;&#1583;&#1575;&#1602;&#1604; &#1605;&#1576;&#1604;&#1594; &#1602;&#1575;&#1576;&#1604; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; $min_withdraw &#1578;&#1608;&#1605;&#1575;&#1606; &#1575;&#1587;&#1578;");

            return FALSE;
        }
        return TRUE;
    }

    /*
    * Form Validation callback to check that the provided mobile number exists or not.
    */

    function max_amount ( $amount ) {

        $this->load->eloquent('settings/Setting');

        $max_withdraw = Setting::findByCode('max_value_request')->value;

        if ( $amount >= $max_withdraw ) {
            $this->form_validation->set_message('max_amount' , "&#1581;&#1583;&#1575;&#1705;&#1579;&#1585; &#1605;&#1576;&#1604;&#1594; &#1602;&#1575;&#1576;&#1604; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; $max_withdraw  &#1578;&#1608;&#1605;&#1575;&#1606; &#1575;&#1587;&#1578;");

            return FALSE;
        }
        return TRUE;
    }

    function check_cash ( $amount ) {

        $cash = $this->__getUserCash();
        if ( $amount > $cash ) {
            $this->form_validation->set_message('check_cash' , "&#1605;&#1576;&#1604;&#1594; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578;&#1740; &#1575;&#1586; &#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1576;&#1740;&#1588;&#1578;&#1585; &#1575;&#1587;&#1578;.");

            return FALSE;
        }
        return TRUE;
    }

    function once_per_day () {

        $this->load->eloquent('settings/Setting');

        $daily_request = Setting::findByCode('daily_request')->value;

        $new_time = date("Y-m-d");

        $this->load->eloquent('Withdraw');

        $db = new DB();
        $withdraw = $db->multi_select('Withdraw','*',['user_id', 'status'],[$this->user->id ,"2"],'created_at DESC', [ 'created_at' ,$new_time],null);

        if ( $withdraw != 0 ){
            if(sizeof($withdraw) >= $daily_request ){
                $this->form_validation->set_message('once_per_day' , "&#1583;&#1585; &#1607;&#1585; &#1585;&#1608;&#1586; &#1601;&#1602;&#1591;  $daily_request  &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1602;&#1575;&#1576;&#1604; &#1579;&#1576;&#1578; &#1575;&#1587;&#1578;.");

                return FALSE;
            }
        }



        //        if ( date('Y-m-d' , strtotime($withdraw->created_at)) == date('Y-m-d') ) {
        //            $this->form_validation->set_message('once_per_day' , "&#1583;&#1585; &#1607;&#1585; &#1585;&#1608;&#1586; &#1601;&#1602;&#1591; &#1740;&#1705; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1602;&#1575;&#1576;&#1604; &#1579;&#1576;&#1578; &#1575;&#1587;&#1578;.");
        //
        //            return FALSE;
        //        }
        return TRUE;
    }




    ////////////////// Pass ///////////////////
    /**
     * Create a random string for a salt.
     *
     * @return string
     */
    public function createSalt(){
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $this->saltLength);
    }


    public function hash ( $value ){
        $salt = $this->createSalt();

        return $salt.hash('sha256', $salt.$value);
    }

    public function slowEquals($a, $b){
        $diff = strlen($a) ^ strlen($b);

        for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }

        return $diff === 0;
    }



    public function check($value, $hashedValue){
        $salt = substr($hashedValue, 0, $this->saltLength);

        return $this->slowEquals($salt.hash('sha256', $salt.$value), $hashedValue);
    }

    public function help($help_id = 0){
        $help_id = trim($help_id);
        $help_file = in_array($help_id, array('100', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '111', '112')) ? $help_id : 'default';
        $this->smart->view('help/' . $help_file);
    }

    public function profile($help_id = 0){
        $this->smart->view('profile');
    }
    public function verify(){
        $this->checkAuth(true);
        $this->CI = get_instance();
        $this->CI->load->database();
        $user = $this->user->id;

        $thisuser = $this->CI->db->conn_id->query("SELECT * FROM `verify` WHERE user_id='" . $user . "'")->fetch_all(MYSQLI_ASSOC);

        $edit_mode = FALSE;
        $this->load->eloquent('Verify');
        // Process Form
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            if ($this->formValidate(FALSE)) {
                $data = [
                    'user_id' => $user,
                    'melli' => $this->input->post('melli'),
                    'bank' => $this->input->post('bank'),
                ];
                if (!empty($_FILES['melli']['name']) and !empty($_FILES['bank']['name']) ){
                    $data['melli'] = $this->input->imageFile('melli', 'other/verify');
                    $data['bank'] = $this->input->imageFile('bank', 'other/verify');
                }
                if (Verify::Create($data)) {
                    $this->message->set_message('اطلاعات با موفقیت ثبت شد', 'success', 'بروزرسانی', 'users/verify')->redirect();
                } else {
                    $this->message->set_message('ثبت انجام نشد. مجدد تلاش کنید', 'warning', 'خطا در  بروزسانی', 'users/verify')->redirect();
                }
                redirect('/users/profile');
            } else {
                $this->message->set_message('خطا در اطلاعات ثبت شده ی فرم', 'warning', 'خطا در  بروزسانی', 'users/verify')->redirect();
            }
        }

        $this->smart->assign(array(
            'thisuser'	=>	$thisuser,
            // 'userstatus'	=>	$thisuser['status'],
            'edit_mode' => $edit_mode,
            'title'	=>	'احراز هویت',
            'slug'	=>	'verify',
            'action'	=>	site_url('users/verify'),
        ));
        $this->smart->view('verify');
    }

}
?>