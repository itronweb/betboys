<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct () {

        date_default_timezone_set('Asia/Tehran');
        parent::__construct();
		
        if ( $get_message = $this->message->get_message() ) {
            $this->smart->assign([
                'system_message' => $get_message ,
            ]);
        }
        else {
            $this->smart->assign([
                'system_message' => '' ,
            ]);
        }
        $this->cms_parameters = array();
        $this->cms_base_route = '';

        // Check if to force ssl on controller
		
        if ( in_uri($this->config->item('ssl_pages')) ) {
            force_ssl();
        }
        else {
            //remove_ssl();
        }

		
        $this->load->sentinel();
    }

    /**
     * 
     * sets form validation rules from $validation_rules array,
     * it gets method name and uses it for getting rules array from rules,
     * it loads global rules before this
     * @param Bool $attach_global_validation set global validation rules or not
     * @return boolean
     */
    public function formValidate ( $attach_global_validation = TRUE ) {
        $rule_collections = array_slice(func_get_args() , 1);
        if ( isset($this->validation_rules) && !empty($this->validation_rules) ) {
            $this->load->library('form_validation');
            $method_name = $this->router->fetch_method();
            if ( $attach_global_validation AND key_exists('__global__' , $this->validation_rules) ) {
                $this->form_validation->set_rules($this->validation_rules['__global__']);
            }
            if ( key_exists($method_name , $this->validation_rules) ) {
                $this->form_validation->set_rules($this->validation_rules[$method_name]);
            }
            if ( count($rule_collections) > 0 ) {
                foreach ( $rule_collections as $rule_collection ) {
                    if ( key_exists($rule_collection , $this->validation_rules) ) {
                        $this->form_validation->set_rules($this->validation_rules[$rule_collection]);
                    }
                }
            }
            if ( $this->form_validation->run() === TRUE ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * ارسال پیامک از طریق وب سرویس پارسا پیامک
     * @param type $numbers
     * @param type $message
     * @return type
     */
    public function __sendSms ( $numbers = array() , $message = '' ) {

        $client = new SoapClient("http://parsasms.com/webservice/v2.asmx?WSDL");

        $params = array(
            'username' => 'a.behzadi' ,
            'password' => 'mor8825172' ,
            'senderNumbers' => array( '10005000200010' ) ,
            'recipientNumbers' => $numbers ,
            //'sendDate'=> $sendDate,
            'messageBodies' => array( $message )
        );

        $results = $client->SendSMS($params);
        return $results;
    }

    /**
     * نمایش پیغام
     * @param varchar $message
     * @param varchar $type
     * @param varchar $title
     * @param varchar $link_url
     * @param varchar $link_text
     */
    public function message ( $message , $type , $title , $link_url ) {
        $this->template->set_message($message , $type , $title , $link_url);
        redirect($link_url);
    }

    public function announcements () {
        $this->load->eloquent('Contacts/Contact');
        $this->load->eloquent('Contacts/Seen');
//        Seen::where(['contact_id' => $val->id, 'user_id' => $this->user->id])->count() > 0
        $Contacts = Contact::getAnnouncement();
        $Contacts_personal = Contact::getAnnounceFront($this->user->id);
        $Contacts = $Contacts->merge($Contacts_personal);
        foreach ( $Contacts as $key => $val ):
            if ( Seen::where(['contact_id' => $val->id , 'user_id' => $this->user->id ])->count() > 0 ) {
                $Contacts[$key]['seen_status'] = 1;
            }
            else {
                $Contacts[$key]['seen_status'] = 0;
            }
        endforeach;
        return $Contacts;
    }

    /**
     * نمایش خطای 404 در شرایط تعیین شده
     * @param type $condition
     */
    public function show_404_on ( $condition ) {
        if ( $condition ) {
            show_404();
        }
    }

}
