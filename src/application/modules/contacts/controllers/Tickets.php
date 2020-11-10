<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Ticket Controller
 *
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 * @link http://www.mirook.ir exclusive CMS for mirookSoft Co.
 */
class Tickets extends Public_Controller {

    public $validation_rules = array(
        'submit_ticket' => array(
            ['field' => 'subject' , 'rules' => 'required|htmlspecialchars|max_length[100]' ,
                'label' => 'موضوع' ] ,
            ['field' => 'content' , 'rules' => 'required|trim|max_length[1000]' , 'label' => 'متن پیام' ] ,
        ) ,
        'submit_reply' => array(
            ['field' => 'content' , 'rules' => 'htmlspecialchars|required|trim|max_length[1000]' , 'label' => 'متن پیام' ] ,
        )
    );

    public function __construct () {
        parent::__construct();
        $this->load->eloquent('Ticket');
        $this->load->eloquent('Ticket_reply');
    }

    public function new_ticket ( $user_id = null ) {
        $this->smart->assign(
                [
                    'title' => 'ارسال تیکت پشتیبانی' ,
                    'Roles' => $this->sentinel->getRoleRepository()->all() ,
                    'user_id' => $user_id ,
                    'action' => site_url('contacts/tickets/submit-ticket') ,
					'totalStake' => $this->getTotalStake(),
                ]
        );
        $this->smart->view('new_ticket');
    }

    public function ticket_list () {
        $this->checkAuth(true);
//        $is_admin = $this->sentinel->getUser()->getRoles()->contains('slug' , SUPER_ADMIN);
        $user_id = $this->user->id;
        $Tickets = Ticket::where('user_id' , $this->user->id)->get();
        $this->smart->assign(
                [
                    'title' => 'ارسال تیکت پشتیبانی' ,
                    'Tickets' => $Tickets ,
                    'action' => site_url('contacts/tickets/submit-ticket') ,
					'totalStake' => $this->getTotalStake(),
                ]
        );
        $this->smart->view('list_ticket');
    }
	
	public function get_user_insta ( $userAvatar ){
		$db = new DB();
		$insta 		 = $db->multi_select('ig_accounts', 'avatar', ['username'], [$userAvatar]);
		
		if ( $insta == 0 )
			return false;
		
		return ['Sezar_Avatar'=> $insta[0]['avatar']];
	}
	
    public function view_ticket ( $id ) {
        $Contact = Ticket::find($id);

        $this->show_404_on(!$id);
        $this->show_404_on(!$Contact);
        if ( $Contact->user_id != $this->user->id )
            redirect();
		if($Contact->status == 2)
			$Contact->update(array( 'status' => 1 ));
		$Insta 		= $this->get_user_insta($this->user->instagram);
		$AdminInsta = $this->get_user_insta($this->ticket_replies->user_id);
		
        $this->smart->assign(
                [
                    'title' => 'تیکت ها' ,
                    'Ticket' => $Contact ,
                    'action' => site_url('contacts/tickets/submit-reply/' . $id) ,
                    'logged_in_user_id' => $this->user->id ,
                    'nameusr' => $this->user->first_name.' '.$this->user->last_name ,
                    'sndusr' => $Contact->user_id,
                    'Insta' => !empty($Insta['Sezar_Avatar']) ? $Insta['Sezar_Avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png',
					'totalStake' => $this->getTotalStake(),
                ]
        );
        $this->smart->view('contacts/view_ticket');
    }

    public function submit_ticket () {
        $data = [ ];
        if ( $this->formValidate(FALSE) ) {
            $data['user_id'] = $this->user->id;
            $data['subject'] = $this->input->post('subject');
            $data['status'] = 0;
            $Ticket = Ticket::create($data);

            $reply['user_id'] = $this->user->id;
            $reply['ticket_id'] = $Ticket->id;
            $reply['content'] = $this->input->post('content');

            if ( $ticket_id = Ticket_reply::create($reply) ) {
                $ticket_link = site_url(NO_NEED_LOGIN_PRE . '/contacts/tickets/view-ticket/' . $Ticket->id);
                $tMessage = $reply['content'];
//                $email = $this->user->email;
//                $this->load->library('email');
//                $this->email->from('ticket@landabet.com' , 'تیکت جدید');
//                $this->email->to($this->sentinel->findById(6)->email);
//                $this->email->subject('تیکت جدید - بت فارس');
//                $this->email->message("شما یک تیکت جدید از سوی کاربران دارید:.\n تیکت دهنده: $email \n متن پیام: $tMessage \n برای پاسخ به تیکت روی لینک زیر کلیک کنید: \n $ticket_link>ارسال پاسخ به تیکت</a>");
//                $this->email->send();
                $this->message->set_message('پیام شما ارسال شد' , 'success' , 'ارسال تیکت' , 'contacts/tickets/ticket-list')->redirect();
            }
        }
        else {
            $this->message->set_message('پیام ارسال نشد.' . validation_errors() , 'warning' , 'ارسال تیکت' , 'contacts/tickets/new-ticket')->redirect();
        }
    }

    public function submit_reply ( $id ) {
        $Contact = Ticket::find($id);
		
        $this->show_404_on(!$id);
        $this->show_404_on(!$Contact);
        if ( $Contact->user_id != $this->user->id )
            redirect();
        $data = [ ];
        if ( $this->formValidate(FALSE) ) {
            $reply['user_id'] = $this->user->id;
            $reply['ticket_id'] = $id;
            $reply['content'] = $this->input->post('content' , true);
            if ( Ticket_reply::create($reply) ) {
                $Contact->update(array( 'status' => 0 ));
                $ticket_link = site_url(NO_NEED_LOGIN_PRE . '/contacts/tickets/view-ticket/' . $id);
                $tMessage = $reply['content'];
                $email = $this->user->email;
                $this->load->library('email');
                $this->email->from('ticket@landabet.com' , 'پاسخ جدید به تیکت');
                $this->email->to($this->sentinel->findById(6)->email);
                $this->email->subject('پاسخ جدید - بت فارس');
                $this->email->message("شما یک پاسخ جدید از سوی کاربران دارید:.\n تیکت دهنده: $email \n متن پیام: $tMessage \n برای پاسخ به تیکت روی لینک زیر کلیک کنید: \n $ticket_link>ارسال پاسخ به تیکت</a>");
                $this->email->send();
                $this->message->set_message('پیام شما ارسال شد' , 'success' , 'ارسال تیکت' , 'contacts/tickets/ticket-list')->redirect();
            }
        }
        else {
            $this->message->set_message('پیام ارسال نشد.' . validation_errors() , 'warning' , 'ارسال تیکت' , 'contacts/tickets/new-ticket')->redirect();
        }
    }

    public function set_seen () {
        $id = $this->input->post('input');
        $this->show_404_on(!$id);
        header('Content-type: application/json');
        $saeed = Ticket::find($id);

        $saeed->status = 1;
        $saeed->seen_datetime = date("Y-m-d H:i:s");
        if ( $saeed->save() )
            echo json_encode(array( 'success' => 1 ));
        else
            echo json_encode(array( 'success' => 0 ));
        die();
    }

    public function __fetch_from_PostArray () {
        $data = [ ];
        foreach ( $this->input->post() as $name => $value ) {
            $data[$name] = $value;
        }
        return $data;
    }

    public function delete ( $ticket_id = null ) {
        if ( $Ticket = Contact::find($ticket_id) ) {
            if ( $Ticket->delete() )
                $this->message->set_message('تیکت مربوطه حذف گردید' , 'success' , 'حذف تیکت' , 'contacts/contact-us/tickets')->redirect();
        }
        else {
            show_404();
        }
    }

}
