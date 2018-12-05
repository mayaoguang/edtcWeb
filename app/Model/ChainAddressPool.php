<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChainAddressPool extends Model
{
    use SoftDeletes;

    protected $table = 'chain_address_pool';

    protected $fillable = ['address', 'user_id', 'state'];
}