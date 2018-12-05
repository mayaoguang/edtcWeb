<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EdtcBrandsType extends Model
{
    use SoftDeletes;

    protected $table = 'edtc_brands_type';

    protected $fillable = ['brands_id', 'type', 'type_number', 'status'];
}