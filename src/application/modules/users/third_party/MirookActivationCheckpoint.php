<?php

use Cartalyst\Sentinel\Checkpoints\ActivationCheckpoint;
use Cartalyst\Sentinel\Users\UserInterface;

class MirookActivationCheckpoint extends ActivationCheckpoint {

    /**
     * Checks the throttling status of the given user.
     *
     * @param  string  $action
     * @param  \Cartalyst\Sentinel\Users\UserInterface|null  $user
     * @return bool
     */
    protected function checkActivation ( UserInterface $user = null ) {
        try {
            parent::checkActivation($user);
        } catch ( Exception $e ) {
            $link = "اگر لینک فعالسازی برایتان ارسال نشده و در پوشه اسپم نیز موجود نبود، از لینک زیر استفاده کنید'
                    . '<br><a class='pull-left readmore' href=''>ارسال مجدد لینک تایید ایمیل</a>";
            $CI = & get_instance();
            $CI->message->set_message('کاربر گرامی شما باید برای ورود به سایت، ایمیل خود را تایید نمایید. لطفا به ایمیل خود مراجعه کرده و با استفاده از لینک فعالسازی که از طرف سایت برایتان ارسال شده، ایمیل خود را تایید کنید' . $link , 'fail' , ' ورود' , '/users/login')->redirect();
        }
    }

}
