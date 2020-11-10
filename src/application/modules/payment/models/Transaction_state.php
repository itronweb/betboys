<?php

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Model: States of transaction for online payments
 *
 *
 * @copyright   Copyright (c) 2016
 * @license     MIT License
 */
class Transaction_state extends EloquentModel {

    protected $table = "transaction_states";
    protected $guarded = [""];

    /**
     * 
     * @return type
     */
    public function user() {
        return $this->belongsTo(CI::$APP->sentinel->getModel());
    }

    public function transaction() {
        return $this->hasMany('Transaction', 'transaction_states_id', 'id');
    }

}
