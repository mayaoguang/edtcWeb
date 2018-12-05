<?php
namespace App\Service;
use App\Model\ChainAddressPool;

class ChainService
{
	public static function getAddressByUserId($user_id){
		return ChainAddressPool::where(['user_id' => $user_id])->first()->address;
	}
}