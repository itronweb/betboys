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
            ['field' => 'email' , 'rules' => 'valid_email|trim|required|htmlspecialchars' , 'label' => 'ایمیل' ] ,
            ['field' => 'password' , 'rules' => 'trim|required|htmlspecialchars' , 'label' => 'کلمه عبور' ]
        ) ,
        'register' => array(
            ['field' => 'password' , 'rules' => 'trim|required' , 'label' => 'کلمه عبور' ] ,
            ['field' => 'email' , 'rules' => 'callback_email_exists|valid_email|trim|required' , 'label' => 'ایمیل' ] ,
            ['field' => 'first_name' , 'rules' => 'trim|required' , 'label' => 'نام' ] ,
            ['field' => 'last_name' , 'rules' => 'trim|required' , 'label' => 'نام خانوادگی' ] ,
            ['field' => 'mobile' , 'rules' => 'trim' , 'label' => 'شماره همراه' ] ,
        ) ,
        'resetPasswordByEmail' => array(
            ['field' => 'password' , 'rules' => 'trim|required' , 'label' => 'کلمه عبور' ] ,
        ) ,
        'changePass' => array(
            ['field' => 'OldPassword' , 'rules' => 'trim|required' , 'label' => 'کلمه عبور' ] ,
            ['field' => 'NewPassword' , 'rules' => 'trim|required' , 'label' => 'کلمه عبور جدید' ] ,
            ['field' => 'ConfirmPassword' , 'rules' => 'trim|required' , 'label' => 'تایید کلمه عبور' ] ,
        ) ,
        'forgot_password' => array(
            ['field' => 'email' , 'rules' => 'trim|htmlspecialchars|required|valid_email' , 'label' => 'شناسه کاربری' ] ,
        ) ,
        'withdraw' => array(
            ['field' => 'amount' , 'rules' => 'trim|htmlspecialchars|required|callback_min_amount|callback_max_amount|callback_check_cash|callback_once_per_day' , 'label' => 'مبلغ' ] ,
            ['field' => 'account_holder' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => 'نام صاحب حساب' ] ,
            ['field' => 'bank_name' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => 'نام بانک' ] ,
            ['field' => 'card_no' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => 'شماره کارت' ] ,
            ['field' => 'sheba' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => 'شماره شبا' ] ,
            ['field' => 'webmoney' , 'rules' => 'htmlspecialchars|trim' , 'label' => 'شماره حساب وب مانی' ] ,
            ['field' => 'account_number' , 'rules' => 'htmlspecialchars|trim|required' , 'label' => 'شماره حساب' ] ,
        ) ,
        'resetPassword' => array(
            ['field' => 'email' , 'rules' => 'trim|htmlspecialchars|required|valid_email' , 'label' => 'شناسه کاربری' ] ,
        ) ,
        'editProfile' => array(
            ['field' => 'first_name' , 'rules' => 'trim|htmlspecialchars|required|max_length[255]' , 'label' => 'نام' ] ,
            ['field' => 'last_name' , 'rules' => 'trim|htmlspecialchars|required|max_length[255]' , 'label' => 'نام خانوادگی' ] ,
            ['field' => 'mobile' , 'rules' => 'trim|required|htmlspecialchars|max_length[15]' , 'label' => 'شماره تماس' ] ,
            ['field' => 'address' , 'rules' => 'trim|htmlspecialchars|max_length[500]' , 'label' => 'آدرس' ] ,
        ) ,
    );

    function __construct () {
        parent::__construct();
        $this->load->sentinel();
        $this->load->eloquent('User');
        $this->load->eloquent('settings/Setting');
		
//        $this->load->library('email');
    }

    /**
     * ورود به حساب کاربری و لاگین کاربر
     */
    function login () {
        // If redirect session var set redirect to home page
		
        $redirect_to = $this->session->userdata('requested_url');
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
        $this->smart->assign(array( 'title' => 'ورود به حساب کاربری' ));

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
					if ( $this->uri->segment(1) == ADMIN_PATH )
						redirect(site_url(ADMIN_PATH));
					else
						redirect($redirect_to);
				}
                
            } else {
                $this->message->set_message('ایمیل و رمز عبور وارد شده هم خوانی ندارد' , 'fail' , 'ورود به حساب کاربری' , 'users/login')->redirect();
            }
        }
        // If the user was attempting to log into the admin panel use the admin theme
        if ( $this->uri->segment(1) == ADMIN_PATH ) {
            $this->smart->assign(array( 'title' => 'ورود به پنل مدیریتی' ));
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
     * ثبت نام کاربر با استفاده از تایید ایمیل
     */
    public function register ( $user_aff = null ) {

		if( Setting::findByCode('registeration_cancel')->value == 1 ){
			$this->logout();
		}
		
        $this->checkAuth(false);
        if ( $this->input->post('submit_btn') ) {
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
                        $this->email->from('noreply@betfars.com' , $site_name);
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
                $this->message->set_message('خطا در فیلدهای ورودی' . validation_errors() , 'fail' , 'ثبت نام حساب کاربری' , 'users/register')->redirect();
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
//تغییر رمز عبور
                if ( $this->input->post('NewPassword') == $this->input->post('ConfirmPassword') ) {
                    $credentials['password'] = $this->input->post('OldPassword');
                    $credentials['email'] = $this->user->email;
                    if ( $this->sentinel->validateCredentials($this->user , $credentials) ) {
                        $reminder = $this->sentinel->getReminderRepository()->create($this->user);
                        $this->sentinel->getReminderRepository()->complete($this->user , $reminder->code , $this->input->post('NewPassword'));
                        $this->message->set_message('اطلاعات شما با موفقیت بروزرسانی شد' , 'success' , 'ویرایش رمز حساب کاربری' , 'dashboard')->redirect();
                    }
                    else {
                        $this->message->set_message('رمزعبور فعلی نادرست است.' , 'warning' , 'تغییر کلمه عبور' , 'users/changePass')->redirect();
                    }
                }
                else {
                    $this->message->set_message('رمزعبور و تایید رمزعبور یکسان نیست.' , 'warning' , 'تغییر کلمه عبور' , 'users/changePass')->redirect();
                }
            }
            else {
                $this->message->set_message('خطا در داده های ورودی. ' . validation_errors() , 'fail' , ' ویرایش رمز حساب کاربری' , 'users/changePass')->redirect();
            }
        }
        else {
            $this->smart->assign(array(
                'title' => 'تغییر کلمه عبور' ,
				'totalStake' => $this->getTotalStake(),
                    )
            );
            $this->smart->view("changePass");
        }
    }

    /**
     * ویرایش اطلاعات حساب کاربری و پروفایل
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
//تغییر رمز عبور
//                if ( $this->input->post('password') ) {
//                    if ( $this->input->post('password') == $this->input->post('password_confirm') ) {
//                        $reminder = $this->sentinel->getReminderRepository()->create($this->user);
//                        $this->sentinel->getReminderRepository()->complete($this->user , $reminder->code , $this->input->post('password'));
//                    }
//                    else {
//                        $this->message->set_message('رمزعبور و تایید رمزعبور یکسان نیست.' , 'warning' , 'ویرایش اطلاعات حساب کاربری' , 'users/editProfile')->redirect();
//                    }
//                }
//بروزرسانی اطلاعات وارد شده توسط کاربر
                $userrr = $this->sentinel->getUserRepository();
                if ( $userrr->update($this->user , $credentials) ) {

                    $this->message->set_message('اطلاعات شما با موفقیت بروزرسانی شد' , 'success' , 'ویرایش اطلاعات حساب کاربری' , 'project/innovations/submit-form')->redirect();
                }
                else {
                    $this->message->set_message('ویرایش با مشکل مواجه شد. مجدد تلاش کنید' , 'fail' , 'ویرایش اطلاعات حساب کاربری' , 'users/editProfile')->redirect();
                }
            }
            else {
                $this->message->set_message('خطا در داده های ورودی. ' . validation_errors() , 'fail' , ' ویرایش اطلاعات حساب کاربری' , 'users/editProfile')->redirect();
            }
        }
        else {
            $this->smart->assign(array(
                'title' => 'ویرایش اطلاعات حساب کاربری' ,
                'action' => base_url('users/editProfile') ,
                    )
            );
            $this->smart->view("information_form");
        }
    }

    /**
     * خروج از حساب کاربری
     */
    public function logout () {
        if ( $user = $this->sentinel->check() ) {
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
            redirect('/');
    }

    /**
     * ّthis method, create a reminder and send the reminder code for user by email,
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
                            $this->message->set_message('کد اعتبارسنجی به ایمیل شما ارسال شد. لطفا کد مربوطه را در فیلد زیر وارد کنید' , 'success' , 'تغییر رمز عبور' , ADMIN_PATH . '/users/users/resetPasswordBySms/' . $User->id)->redirect();
                    }
                    else {
                        $this->message->set_message('لطفا کد ارسال شده به تلفن همراه خود را در فیلد زیر وارد کنید' , 'success' , 'تغییر رمز عبور' , ADMIN_PATH . '/users/users/resetPasswordBySms/' . $User->id)->redirect();
                    }
                }
            }

            $this->smart->assign(array( 'title' => 'ورود به پنل مدیریتی' ));
            $this->smart->setLayout('login_layout');
            $this->smart->load('default' , TRUE);
            $this->smart->view("admin/login");
        }
        else {
            $this->smart->view("users/forgot_password");
        }
    }

    /**
     * بازیابی رمز عبور سمت فرانت به صورت ایجکس
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
					$message->subject = 'بازیابی رمز عبور';
					$message->mail_message = $this->createHTMLPageForResetPassword( $reset_code );
					
					$mail = $this->sendMail($message);
					
					if ( $mail === true ){
						
						$User->update(array('remember_link'=> $reset_code ));
						
						$this->message->set_message('یک ایمیل جهت بازیابی رمز عبور برای شما ارسال شد.', 'success', 'بازیابی رمز عبور', 'users/login')->redirect();
					}
					else if ( $mail === false ) {
						$this->message->set_message('ایمیل وارد شده نامعتبر می باشد', 'success', 'بازیابی رمز عبور', 'users/login')->redirect();
					}

                }
				else if ( $exist === false ){
					$this->message->set_message('ایمیل وارد شده یافت نشد.' , 'success' , 'بازیابی رمز عبور' , 'users/login')->redirect();
				}
            }
			else {
				$this->message->set_message('ایمیل وارد شده یافت نشد.' , 'success' , 'بازیابی رمز عبور' , 'users/login')->redirect();
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
            'smtp_user' => 'betfarsInfo@gmail.com',
            'smtp_pass' => 'betf@rs123',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'smtp_timeout' => 30
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->to($message->to);
        $this->email->from('betfarsInfo@gmail.com','Betfars.com');
        $this->email->subject( $message->subject );
        $this->email->message($message->mail_message);

        $result = $this->email->send();

        return $result;

	}
	
	public function createHTMLPageForResetPassword ( $reset_code ){
		
		$reset_url = site_url("users/resetPasswordByEmail/$reset_code/");
		
		$htmlContent = '<h1>بازیابی رمز عبور</h1>';
		$htmlContent .= '<p>برای بازیابی رمز عبور خود از لینک زیر استفاده نمایید</p>';
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
        $this->email->from('noreply@betfars.com');
        $this->email->to($User->email);
        $this->email->subject('بازیابی کلمه عبور');
        $mail_message = "برای بازیابی کلمه عبور حسابتان در وب سایت  ، از لینک زیر استفاده نمایید\n" . site_url('users/resetPasswordByEmail/' . $User->id . '/' . $Reminder->code);
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
			
			$this->smart->assign(['title' => 'بازیابی رمز عبور حساب کاربری' ]);
			
			if ( $this->formValidate(FALSE) ){
				if ( $user->remember_link == $reminder_code ){
					$password = $this->hash($this->input->post('password'));
					$user->update( array( 'password'=>$password , 'remember_link' => NULL ) );
					
					 $this->message->set_message('رمز عبور حساب شما با موفقیت تغییر یافت. حالا می توانید با رمز عبور جدید خود وارد سایت شوید.' , 'success' , 'تغییر رمز عبور' , '/users/login')->redirect();
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
				$this->message->set_message('کد بازیابی نامعتبر است' , 'fail' , 'تغییر رمز عبور' , '/users/login')->redirect();
			}
			
		}
		
		
        if ( $this->input->post('user_id') ) {
            $user_id = $this->input->post('user_id');
            $reminder_code = $this->input->post('reminder_code');
        }
        $user = $this->sentinel->getUserRepository()->find($user_id);

        $reminder = $this->sentinel->getReminderRepository();
        $this->smart->assign(['title' => 'بازیابی رمز عبور حساب کاربری' ]);
        if ( $reminder->exists($user) ) {

            if ( $this->formValidate(FALSE) AND $reminder->complete($user , $reminder_code , $this->input->post('password')) ) {
                $this->message->set_message('رمز عبور حساب شما با موفقیت تغییر یافت. حالا می توانید با رمز عبور جدید خود وارد سایت شوید.' , 'success' , 'تغییر رمز عبور' , '/users/login')->redirect();
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
            $this->message->set_message('کد بازیابی نامعتبر است' , 'fail' , 'تغییر رمز عبور' , '/users/login')->redirect();
    }

	
    public function resetPasswordByEmail1 ( $user_id = null , $reminder_code = null ) {

        if ( $this->input->post('user_id') ) {
            $user_id = $this->input->post('user_id');
            $reminder_code = $this->input->post('reminder_code');
        }
        $user = $this->sentinel->getUserRepository()->find($user_id);

        $reminder = $this->sentinel->getReminderRepository();
        $this->smart->assign(['title' => 'بازیابی رمز عبور حساب کاربری' ]);
        if ( $reminder->exists($user) ) {

            if ( $this->formValidate(FALSE) AND $reminder->complete($user , $reminder_code , $this->input->post('password')) ) {
                $this->message->set_message('رمز عبور حساب شما با موفقیت تغییر یافت. حالا می توانید با رمز عبور جدید خود وارد سایت شوید.' , 'success' , 'تغییر رمز عبور' , '/users/login')->redirect();
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
            $this->message->set_message('کد بازیابی نامعتبر است' , 'fail' , 'تغییر رمز عبور' , '/users/login')->redirect();
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
                    $this->message->set_message('حساب شما با موفقیت فعال و ایمیل شما تایید شد.' , 'success' , 'فعالسازی حساب کاربری' , 'dashboard')->redirect();
                }
                else
                    echo 'حساب شما فعال شد اما عملیات لاگین صورت نگرفت. برای ورود مجدد تلاش کنید.';
                exit();
            }
            else {
                $this->message->set_message('فعال سازی انجام نشد.' , 'fail' , 'فعالسازی حساب کاربری' , 'users/login')->redirect();
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
            $trans = Transaction::where('invoice_type' , 5)->where('user_id' , $this->user->id)->get();
            $sub_count = $mySub->count();
            $affSum = 0;
            foreach ( $trans as $val ):
                $affSum += $val->price;
            endforeach;
            $this->smart->assign([
                'mySub' => $mySub ,
                'affSum' => $affSum ,
                'affCount' => $trans->count() ,
				'totalStake' => $this->getTotalStake(),
            ]);
        }
        $this->smart->assign(['sub_count' => $sub_count ]);
        $this->smart->view('representation');
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
		$pay_code = time() * $this->user->id;

        $this->load->eloquent('settings/Setting');
		$this->load->eloquent('Withdraw');
		$this->load->eloquent('User');

        $min_value = Setting::findByCode('min_value_request')->value;
        $max_value = Setting::findByCode('max_value_request')->value;

        if ( $this->formValidate(false) ) {
            $data = [
                'amount' => $this->input->post('amount') ,
                'account_holder' => $this->input->post('account_holder') ,
                'bank_name' => $this->input->post('bank_name') ,
                'card_no' => $this->input->post('card_no') ,
                'sheba' => $this->input->post('sheba') ,
                'webmoney' => $this->input->post('webmoney') ,
                'account_number' => $this->input->post('account_number') ,
                'status' => 0 ,
                'user_id' => $this->user->id ,
				'pay_code' => $pay_code,
            ];
			
            $this->load->eloquent('withdraw');
            Withdraw::create($data);
            $priceeee = $data['amount'];

//            $email = $this->user->email;
//            $this->load->library('email');
//            $this->email->from('ticket@betfars.com' , 'درخواست جایزه جدید');
//            $this->email->to($this->sentinel->findById(6)->email);
//            $this->email->subject('درخواست جایزه جدید - بت فارس');
//            $this->email->message("شما یک درخواست جایزه جدید از سوی کاربران دارید:.\n درخواست دهنده: $email \n مبلغ: $priceeee \n");
//            $this->email->send();
            $user = $this->sentinel->getUserRepository();
            $currentCash = $this->__getUserCash();
            if ( $user->update($this->user , array( 'cash' => $currentCash - $data['amount'] )) ) {
				
                $this->load->eloquent('payment/Transaction');
                Transaction::create([
                    'trans_id' => $this->user->id ,
                    'price' => $data['amount'] ,
                    'invoice_type' => 4 ,
                    'cash' => $currentCash - $data['amount'] ,
                    'user_id' => $this->user->id ,
                    'description' => 'درخواست جایزه' ,
					'pay_code' => $pay_code,
                ]);
                $this->message->set_message('درخواست شما ثبت شد.' , 'success' , 'درخواست جایزه' , 'users/withdraw')->redirect();
            }
        }
			
			elseif( $id != null){
				$changeStatus = Withdraw::where('id' , $id)->where('status',0)->where('user_id',$this->user->id)->get();
				if(isset($changeStatus[0])){
					
					$get_pay_code = Withdraw::where('id' , $id)->where('status',0)->where('user_id',$this->user->id)->get();
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
						'description' => 'درخواست برداشت لغو شد.' ,
						'pay_code' => $pay_code,
						'status' => 1,
					]);
					$this->message->set_message('درخواست برداشت لغو شد.' , 'success' , 'لغو درخواست جایزه' , 'users/withdraw')->redirect();

				}
			}
	
	
	
            
            $withdrawList = Withdraw::where('user_id' , $this->user->id)->get();
            $this->smart->assign([
                'cash' => $this->__getUserCash() ,
                'withdrawList' => $withdrawList ,
                'action' => site_url('users/withdraw') ,
				'totalStake' => $this->getTotalStake(),
                'max_value' => $max_value,
                'min_value' => $min_value,
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
            $this->form_validation->set_message('email_exists' , "ایمیل وارد شده درحال حاضر در سایت ثبت نام شده است");

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
            $this->form_validation->set_message('min_amount' , "حداقل مبلغ قابل درخواست $min_withdraw تومان است");

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
            $this->form_validation->set_message('max_amount' , "حداکثر مبلغ قابل درخواست $max_withdraw  تومان است");

            return FALSE;
        }
        return TRUE;
    }

    function check_cash ( $amount ) {

        $cash = $this->__getUserCash();
        if ( $amount > $cash ) {
            $this->form_validation->set_message('check_cash' , "مبلغ درخواستی از موجودی حساب شما بیشتر است.");

            return FALSE;
        }
        return TRUE;
    }

    function once_per_day () {

        $this->load->eloquent('settings/Setting');

        $daily_request = Setting::findByCode('daily_request')->value;

        $this->load->eloquent('Withdraw');
        $withdraw = Withdraw::where('user_id' , $this->user->id)->where('status' , '0')->orderBy('created_at' , 'desc')->get();
		
		if(sizeof($withdraw) >= $daily_request ){
			$this->form_validation->set_message('once_per_day' , "در هر روز فقط  $daily_request  درخواست قابل ثبت است.");

            return FALSE;
		}
		
//        if ( date('Y-m-d' , strtotime($withdraw->created_at)) == date('Y-m-d') ) {
//            $this->form_validation->set_message('once_per_day' , "در هر روز فقط یک درخواست قابل ثبت است.");
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
	public function createSalt()
	{
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle(str_repeat($pool, 5)), 0, $this->saltLength);
	}


		public function hash ( $value ){
			$salt = $this->createSalt();

			return $salt.hash('sha256', $salt.$value);
		}

		public function slowEquals($a, $b)
		{
			$diff = strlen($a) ^ strlen($b);

			for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
				$diff |= ord($a[$i]) ^ ord($b[$i]);
			}

			return $diff === 0;
		}



		 public function check($value, $hashedValue)
		{
			$salt = substr($hashedValue, 0, $this->saltLength);

			return $this->slowEquals($salt.hash('sha256', $salt.$value), $hashedValue);
		}


}
