<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NodeBonus extends Model
{
    use SoftDeletes;

    protected $table = 'node_bonus';

    protected $fillable = ['node_id', 'order_number', 'bonus_amount', 'bouns_coin', 'status', 'change'];
}