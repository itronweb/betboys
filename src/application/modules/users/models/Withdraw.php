<?php

use Cartalyst\Sentinel\Users\EloquentUser as EloquentUser;

/**
 * Description of User
 *
 *  *
 */
class Withdraw extends EloquentUser {

    /**
     * {@inheritDoc}
     */
    protected $guarded = [ ];
    protected $table = "withdraw";

    /**
     * 
     * @return type
     */
    public function user () {
        return $this->belongsTo(CI::$APP->sentinel->getModel());
    }

}
