<?php
namespace App\Service;
use App\Model\EdtcBrandsType;

class BrandsService{
	public static function newBrands($data){
		return EdtcBrandsType::create($data);
	}

	public static function getBrandsTypeById($type_id){
		return EdtcBrandsType::where(['id' => $type_id])->first();
	}
}