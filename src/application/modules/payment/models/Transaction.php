<?php

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Model: Transactions of online payments
 *
 *
 * @copyright   Copyright (c) 2016
 * @license     MIT License
 */
class Transaction extends EloquentModel {

    protected $table = "transactions";
    protected $guarded = [""];

    /**
     * 
     * @return type
     */
    public function user() {
        return $this->belongsTo(CI::$APP->sentinel->getModel());
    }
    
    public function state(){
        return $this->belongsTo('Transaction_state','transaction_states_id','id');
    }

}
