<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Contact_us Controller
 *
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 * @link http://www.mirook.ir exclusive CMS for mirookSoft Co.
 */
class Contact_us extends Public_Controller {

    public $validation_rules = array(
        'submit_contact' => array(
            ['field' => 'name_family' , 'rules' => 'trim|required|htmlspecialchars|max_length[100]' , 'label' => 'نام و نام خانوادگی' ] ,
            ['field' => 'email' , 'rules' => 'trim|required|htmlspecialchars' , 'label' => 'ایمیل' ] ,
            ['field' => 'subject' , 'rules' => 'htmlspecialchars|max_length[100]' , 'label' => 'موضوع' ] ,
            ['field' => 'message' , 'rules' => 'htmlspecialchars|required|trim|max_length[1000]' , 'label' => 'متن پیام' ] ,
        )
    );

    public function __construct () {
        parent::__construct();
        $this->load->eloquent('Contact');
    }

    
    public function index () {
        $this->smart->assign(
                [
                    'title' => 'تماس با ما' ,
                    'action' => site_url('contacts/contact-us/submit-contact') ,
                ]
        );
        $this->smart->view('index');
    }

    public function submit_contact () {
        $data = [ ];
        if ( $this->formValidate(FALSE) ) {
            $data = $this->__fetch_from_PostArray();
            $contact = new Contact;
            foreach ( $data as $key => $val ):
                $contact->$key = $val;
            endforeach;
            $contact->is_site_contact = 1;
            if ( $contact->save() )
                $this->message->set_message('پیام شما ارسال شد' , 'success' , 'پیغام ارسال شد' , 'contacts/contact-us/')->redirect();
        }
        redirect('contacts/contact-us');
    }

    public function __fetch_from_PostArray () {
        $data = [ ];
        foreach ( $this->input->post() as $name => $value ) {
            $data[$name] = $value;
        }
        $data['is_ticket'] = 0;
        return $data;
    }

    public function delete ( $contact_id = null ) {
        if ( $Content_categories = Content_category::find($contact_id) ) {
            if ( $Content_categories->delete() )
                $this->message->set_message('دسته بندی محتوای مربوطه حذف گردید' , 'success' , 'حذف دسته بندی محتوا' , ADMIN_PATH . '/content/categories')->redirect();
        }
        else {
            show_404();
        }
    }
 
}
