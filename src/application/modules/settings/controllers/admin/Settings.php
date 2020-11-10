<?php

class Settings extends Admin_Controller {

    public $validation_rules = array(
        'index' => array(
            ['field' => 'site_name' , 'rules' => 'trim|required|htmlspecialchars' , 'label' => 'عنوان سایت' ] ,
            ['field' => 'homepage' , 'rules' => 'numeric|required|htmlspecialchars' , 'label' => 'صفحه اصلی' ] ,
            ['field' => 'custom_error_page' , 'rules' => 'htmlspecialchars' , 'label' => 'صفحه خطا' ] ,
            ['field' => 'site_status' , 'rules' => 'numeric|htmlspecialchars' , 'label' => 'وضعیت سایت' ] ,
            ['field' => 'footer' , 'rules' => 'htmlspecialchars' , 'label' => 'متن فوتر سایت' ] ,
        )
    );

    public function __construct () {
        parent::__construct();
        $this->load->eloquent('Setting');
        $this->load->eloquent('content/Page');
    }

    public function trans () {
        
        if ( $this->input->post('fa') ) {
            $fa = addslashes($this->input->post('fa'));
            $en = addslashes($this->input->post('en'));
            $txt = "\$lang['$en'] = '$fa'; \n";
            file_put_contents(FCPATH . 'application/language/persian/team_lang.php' , $txt , FILE_APPEND | LOCK_EX);
            redirect(ADMIN_PATH.'/settings/settings/trans');
        }
        else {
            $this->smart->assign(['title'=>'ترجمه نام تیم‌ها']);
            $this->smart->view('trans');
        }
    }

    public function index () {
        // Init
        $Pages = Page::all();
        $site_name = Setting::findByCode('site_name');
        $homepage = Setting::findByCode('homepage');
        $site_status = Setting::findByCode('site_status');
        $footer = Setting::findByCode('footer');
        $theme = Setting::findByCode('theme');
        // Get themes from theme directory
        $available_themes = new \League\Flysystem\Adapter\Local(FCPATH . 'themes/default');
        $themes = $available_themes->listContents();
        $custom_error_page = Setting::findByCode('custom_error_page');
        $this->smart->assign([
            'title' => 'تنظیمات کلی سیستم' ,
            'Pages' => $Pages ,
            'server_signature' => $this->input->server('SERVER_SIGNATURE') ,
            'display_errors' => ini_get('display_errors') ? 'فعال' : 'غیرفعال' ,
            'post_max_size' => ini_get('post_max_size') ,
            'site_name' => $site_name->value ,
            'homepage' => $homepage ,
            'site_status' => $site_status->value ,
            'footer' => $footer->value ,
            'themes' => $themes ,
            'current_theme' => $theme->value ,
            'custom_error_page' => $custom_error_page ,
        ]);
        // Process Form
        if ( $this->formValidate(FALSE) ) {
            $data = [
                'site_name' => $this->input->post('site_name') ,
                'custom_error_page' => $this->input->post('custom_error_page') ,
                'homepage' => $this->input->post('homepage') ,
                'site_status' => $this->input->post('site_status') ,
                'footer' => $this->input->post('footer') ,
                'theme' => $this->input->post('theme') ,
            ];

            // Insert or update data to the db
            // if inserted

            foreach ( $data as $key => $val ) {
                $affected_row = Setting::where('code' , $key)->update(['value' => $val ]);
            }
            if ( $affected_row ) {
                $this->message->set_message('اطلاعات با موفقیت بروزرسانی شد' , 'success' , 'بروزرسانی' , ADMIN_PATH . '/settings')->redirect();
            }// else if insertion failed
            else {
                $this->message->set_message('ذخیره سازی انجام نشد. مجدد تلاش کنید' , 'fail' , 'خطا در ذخیره سازی' , ADMIN_PATH . '/settings')->redirect();
            }
            redirect(ADMIN_PATH . '/settings');
        }
        $this->smart->view('edit');
    }

}
