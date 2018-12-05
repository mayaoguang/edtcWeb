<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\AccountService;
use App\Service\SmsManageService;

//AccountController 负责用户注册，登陆，修改密码
class AccountController extends Controller
{
    public function confirmFormat($tel_number, $secret, $secret_confirm){
        if(!preg_match("/1[3458]{1}\d{9}$/",$tel_number)){
            return accountMsg(112);
        }

    	if($secret != $secret_confirm){
        	return accountMsg(102);
        }

        if(strlen($secret) < 8){
        	return accountMsg(100);
        }

       if(!preg_match("/^[a-z\d]*$/i",$secret)){
            return accountMsg(101);
        }
        return null;
    }

    public function registered(Request $request){
        $tel_number = $request->get('tel_number');
        $secret = $request->get('secret');
        $secret_confirm = $request->get('secret_confirm');
        $verification_code = $request->get('verification_code');

        $res_check = SmsManageService::checkSmscode($tel_number, $verification_code);
        if($res_check){
            return accountMsg($res_check);
        }

        $secret_format = $this->confirmFormat($tel_number, $secret, $secret_confirm);
        if($secret_format){
        	return $secret_format;
        }

        $res_data = AccountService::getIdByTelNumber($tel_number);
        if($res_data){
            $res_msg = accountMsg(110);
            return $res_msg;
        }

        $data['user_name'] = null;
        $data['tel_number'] = $tel_number;
        $data['user_secret'] = md5($secret);
        $data['node_status'] = 0;
        $data['user_status'] = 1;
        $data['lock_amount'] = 0;
        $data['amount'] = 0;

        $res_data = AccountService::newUser($data);

        if($res_data){
            $res_msg = accountMsg(106);
        }
        return $res_msg;
    }

    public function resetSecret(Request $request){
    	$tel_number = $request->get('tel_number');
        $secret = $request->get('secret');
        $secret_confirm = $request->get('secret_confirm');
        $verification_code = $request->get('verification_code');
        
        $secret_format = $this->confirmFormat($tel_number, $secret, $secret_confirm);
        if($secret_format){
        	return $secret_format;
        }

        $res_check = SmsManageService::checkSmscode($tel_number, $verification_code);
        if($res_check){
            return accountMsg($res_check);
        }

        $res_data = AccountService::setSecretByTelNumber($tel_number, md5($secret));
        if ($res_data) {
            $res_msg = accountMsg(108);
        }else{
            $res_msg = accountMsg(111);
        }
        return $res_msg;
    }

    public function resetTelNumber(Request $request){
        $old_tel_number = $request->get('old_tel_number');
        $tel_number = $request->get('tel_number');
        $secret = $request->get('secret');
        $secret_confirm = $request->get('secret_confirm');
        $verification_code = $request->get('verification_code');

        $secret_format = $this->confirmFormat($tel_number, $secret, $secret_confirm);
        if($secret_format){
            return $secret_format;
        }

        $res_check = SmsManageService::checkSmscode($old_tel_number, $verification_code);
        if($res_check){
            return accountMsg($res_check);
        }

        $res_data = AccountService::setTelNumber($old_tel_number, $tel_number, md5($secret));
        if ($res_data) {
            $res_msg = accountMsg(108);
        }else{
            $res_msg = accountMsg(111);
        }
        return $res_msg;
    }

    public function getSmsMsg(Request $request){
        $tel_number = $request->get('tel_number');

        $smscode = SmsManageService::generateSmscode(4);
        $res = SmsManageService::sendSmsMessage($tel_number, $smscode);
        return accountMsg($res);
    }

    public function loading(Request $request){
    	$tel_number = $request->get('tel_number');
        $secret = $request->get('secret');

        $res_check = AccountService::checkSecret($tel_number, $secret);
        if($res_check){
            return accountMsg($res_check);
        }

        return accountMsg(107);
    }

    public function userAmount(Request $request){
        $user_id = $request->get('user_id');
        return AccountService::getAmountById($user_id);
    }

    public function userRecord(Request $request){
        $user_id = $request->get('user_id');
        $res_data['record'] = AccountService::getRecordByUserId($user_id);
        return $res_data;
    }
}