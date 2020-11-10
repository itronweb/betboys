<?php

class Settings_plugin extends Plugin {

    public function get_param() {
        $name = $this->attribute('name', null);
        if (!$name) {
            return;
        }
        $this->load->eloquent('settings/Setting');
        if ($setting = Setting::findByCode($name)) {
            return $setting->value;
        }
        return;
    }

    public function get_menu_list($param) {
        
    }

    public function get_menu($param) {
        
    }

}
