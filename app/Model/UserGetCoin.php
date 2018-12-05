<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGetCoin extends Model
{
    use SoftDeletes;

    protected $table = 'user_get_coin';

    protected $fillable = ['order_number', 'user_id', 'to', 'coin_amount', 'status', 'fee', 'tx_hash'];
}