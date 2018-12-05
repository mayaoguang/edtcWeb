<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NodeDetails extends Model
{
    use SoftDeletes;

    protected $table = 'node_details';

    protected $fillable = ['user_id', 'node_name', 'brands_id', 'type', 'approve_floor', 'vote_account', 'bonus_expected', 'over_bonus', 'address', 'node_description', 'founder_name', 'founder_photo', 'idcard_1', 'idcard_2', 'idcard_3', 'tel_number', 'founder_description', 'license', 'logo', 'team_member', 'check_status', 'vote_status', 'approve_status', 'cancel_time', 'bonus_status', 'is_before'];
}