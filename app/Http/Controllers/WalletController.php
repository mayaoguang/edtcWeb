<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\AccountService;
use App\Service\ChainService;
use App\Service\WalletService;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
	public function getEthAddress(Request $request){
		$user_id = $request->get('user_id');

		$res_data['user_data'] = ChainService::getAddressByUserId($user_id);
		if($res_data){
			return $res_data;
		}else{
			return walletMsg(400);
		}
	}

	public function withdrawCoin(Request $request){
		$tel_number = $request->get('tel_number');
        $secret = $request->get('secret');
        $verification_code = $request->get('verification_code');
        $to = $request->get('to');
        $coin_amount = $request->get('coin_amount');

        $res_check = AccountService::checkSecret($tel_number, $secret);
        if($res_check){
        	return accountMsg($res_check);
        }

        $res_check = SmsManageService::checkSmscode($tel_number, $verification_code);
        if($res_check){
        	return accountMsg($res_check);
        }

        $owner_amount = AccountService::getAmountByTel($tel_number);
        if($owner_amount < $coin_amount){
        	return walletMsg(401);
        }

        $data['order_number'] = buildOrderNo();
        DB::beginTransaction();
        try{
        	$data['to'] = $to;
	        $data['coin_amount'] = $coin_amount;
	        $data['status'] = 1;
	        $data['fee'] = FEE;
	        $data['user_id'] = AccountService::getIdByTelNumber($tel_number);

        	$amount_chang = AccountService::setAmountById($data['user_id'], $owner_amount - $coin_amount);
			if(!$amount_chang){
				return walletMsg(402);
			}else{
				$record_data['operation_type'] = 2;
				$record_data['user_id'] = $data['user_id'];
				$record_data['number'] = -$coin_amount;
				$record_data['status'] = 0;
				$record_data['operation_id'] = $data['order_number'];
				AccountService::newRecord($record_data);
			}

			WalletService::newGetCoin($data);
			DB::commit();
        }catch(Exception $e){
        	DB::rollBack();
        }
        return walletMsg(403);
	}

	public function getFee(){
		return FEE;
	}
}