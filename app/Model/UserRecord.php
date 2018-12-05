<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRecord extends Model
{
    use SoftDeletes;

    protected $table = 'user_record';

    protected $fillable = ['operation_type', 'user_id', 'number', 'operation_id', 'status'];
}