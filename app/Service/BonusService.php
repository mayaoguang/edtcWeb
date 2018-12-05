<?php
namespace App\Service;
use App\Model\NodeBonus;

class BonusService{
	public static function newBonus($data){
		return NodeBonus::create($data);
	}
}