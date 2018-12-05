<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EdtcUser extends Model
{
    use SoftDeletes;

    protected $table = 'edtc_user';

    protected $fillable = ['user_name', 'tel_number', 'user_secret', 'node_status', 'user_status', 'amount', 'lock_amount'];
}