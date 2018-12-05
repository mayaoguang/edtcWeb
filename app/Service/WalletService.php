<?php
namespace App\Service;
use App\Model\UserGetCoin;

class WalletService
{
	public static function newGetCoin($data){
		return UserGetCoin::create($data);
	}
}