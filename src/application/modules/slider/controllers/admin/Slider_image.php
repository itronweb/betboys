<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Slider Controller
 *
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
*
 */
class Slider_image extends Admin_Controller {

    public $validation_rules = array(
        'edit' => array(
           // ['field' => 'image', 'rules' => 'trim|required|htmlspecialchars', 'label' => 'فایل عکس'],
            ['field' => 'subtitle', 'rules' => 'trim|htmlspecialchars', 'label' => 'زیرنویس عکس'],
            ['field' => 'describtion', 'rules' => 'htmlspecialchars', 'label' => 'توضیحات'],
            ['field' => 'status', 'rules' => 'required|numeric', 'label' => 'وضعیت'],
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->eloquent('Slider');
    }

    public function index() {
        $data = array();
        $Sliders = Slider::all();
        $this->smart->assign(
                [
                    'title' => 'اسلایدرها',
                    'Sliders' => $Sliders,
                ]
        );
        $this->smart->view('index');
    }

    function edit($slider_id = null) {
        // Init
        $edit_mode = FALSE;
        $this->smart->assign([
            'edit_mode' => $edit_mode,
            'title' => 'اسلایدرها',
        ]);

        // Edit Mode 
        if ($slider_id) {
            $edit_mode = TRUE;
            $this->smart->assign([
                'edit_mode' => $edit_mode,
                'Slider' => Slider::find($slider_id)
            ]);
        }
        // Process Form
        if ($this->formValidate(FALSE)) {
            $data = [
                'subtitle' => $this->input->post('subtitle'),
                'describtion' => $this->input->post('describtion'),
                'status' => $this->input->post('status'),
                'link' => $this->input->post('link'),
            ];
            if (!empty($_FILES['image']['name']))
                $data['image'] = $this->input->imageFile('image', 'slider');
            // Insert or update data to the db
            // if inserted
            if (Slider::updateOrCreate(['id' => $slider_id], $data)) {
                if (!$edit_mode) {
                    $this->message->set_message('اطلاعات با موفقیت ذخیره شد', 'success', 'ثبت رکورد جدید', ADMIN_PATH . '/slider/slider-image/edit')->redirect();
                } else
                    $this->message->set_message('اطلاعات با موفقیت بروزرسانی شد', 'success', 'بروزرسانی', ADMIN_PATH . '/slider/slider-image/edit')->redirect();
            }// else if insertion failed
            else {
                if ($edit_mode)
                    $this->message->set_message('ذخیره سازی انجام نشد. مجدد تلاش کنید', 'fail', 'خطا در ذخیره سازی', ADMIN_PATH . '/slider/slider-image/edit')->redirect();

                else {
                    $this->message->set_message('بروزرسانی انجام نشد. مجدد تلاش کنید', 'fail', 'خطا در  بروزسانی', ADMIN_PATH . '/slider/slider-image/edit')->redirect();
                }
            }
            redirect(ADMIN_PATH . '/slider/slider-image');
        }
        $this->smart->view('edit');
    }

    function delete($slider_id = null) {
        if ($Slider = Slider::find($slider_id)) {
            if ($Slider->delete())
                $this->message->set_message(' تصویر اسلایدر مربوطه حذف گردید', 'success', 'حذف تصویر اسلایدر  ', ADMIN_PATH . '/slider/slider-image')->redirect();
        }
        else {
            show_404();
        }
    }

}
