<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EdtcBrands extends Model
{
    use SoftDeletes;

    protected $table = 'edtc_brands';

    protected $fillable = ['brands_name', 'brand_introduction', 'logo', 'status'];
}