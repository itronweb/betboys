<?php

class Message {

    /**
     * 
     * @param type $message
     * @param type $type
     * @param type $title
     * @param type $link_url
     */
    public function set_message($message, $type, $title, $link_url) {
        $ci = & get_instance();
        $this->link_url = base_url($link_url);
        $ci->session->set_flashdata('message_' . $this->link_url, array(
            'message' => $message,
            'type' => $type,
            'title' => $title,
            'link_url' => $link_url
        ));
        return $this;
    }

    public function redirect(){
        redirect($this->link_url);
    }
    /**
     * 
     * @return boolean
     */
    public function get_message() {
        $ci = & get_instance();
        if (is_array($flashdata = $ci->session->flashdata('message_' . current_url()))) {
            return $flashdata;
        } else {
            return false;
        }
    }

}
