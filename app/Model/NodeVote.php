<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NodeVote extends Model
{
    use SoftDeletes;

    protected $table = 'node_vote';

    protected $fillable = ['user_id', 'node_id', 'vote_number', 'vote_status', 'unlock_time', 'vote_timestamp', 'order_number'];
}