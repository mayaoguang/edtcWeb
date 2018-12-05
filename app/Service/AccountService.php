<?php
namespace App\Service;
use App\Model\EdtcUser;
use App\Model\UserRecord;

class AccountService
{
	public static function getAmountById($user_id){
		return EdtcUser::where('id',$user_id)->first(['amount', 'lock_amount']);
	}

	public static function getAmountByTel($tel_number){
		return EdtcUser::where('tel_number',$tel_number)->first(['amount', 'lock_amount']);
	}

	public static function setAmountById($user_id, $new_amount){
		return EdtcUser::where('id',$user_id)->update(['amount' => $new_amount]);
	}

	public static function setLockAmountById($user_id, $lock_amount){
		return EdtcUser::where('id',$user_id)->update(['lock_amount' => $lock_amount]);
	}

	public static function getIdByTelNumber($tel_number){
		return EdtcUser::where('tel_number',$tel_number)->first();
	}

	public static function setSecretByTelNumber($tel_number, $secret){
		return EdtcUser::where(['tel_number' => $tel_number])->update(['user_secret' => $secret]);
	}

	public static function setTelNumber($old_tel_number, $tel_number, $secret){
		return EdtcUser::where(['tel_number' => $old_tel_number])->update(['tel_number' => $tel_number, 'user_secret' => $secret]);
	}

	public static function lodaing($tel_number, $secret){
		return EdtcUser::where(['tel_number' => $tel_number, 'secret' => $secret])->first();
	}

	public static function newUser($data){
		return EdtcUser::create($data);
	}

	public static function newRecord($data){
		return UserRecord::create($data);
	}

	public static function getRecordByUserId($user_id){
		return UserRecord::where(['user_id' => $user_id])->get();
	}

	public static function checkSecret($tel_number, $secret){
		$user_secret = EdtcUser::where('tel_number', $tel_number)->first()->user_secret;
		if($user_secret != md5($secret)){
			return 117;
		}
		return null;
	}

	public static function getTotalBonus(){
		return UserRecord::where(['operation_type' => 7])->sum('number');
	}
}