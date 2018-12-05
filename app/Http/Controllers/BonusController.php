<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\BonusService;
use App\Service\NodeService;
use App\Service\AccountService;
use App\Service\SmsManageService;
use App\Service\VoteService;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
	public function bonusExpected(Request $request){
		$node_id = $request->get('node_id');

		$bonus_data = NodeService::getBonusDataById($node_id);
        return $bonus_data;
	}

	public function bonus(Request $request){
		$tel_number = $request->get('tel_number');
        $secret = $request->get('secret');
        $verification_code = $request->get('verification_code');
        $bonus_amount = $request->get('bonus_amount');
        $node_id = $request->get('node_id');

        try{
        	$node_data = NodeService::getNodeById($node_id);
        }catch(Exception $e){
        	return bonusMsg(504);
        }
        
        if($bonus_amount < $node_data['bonus_expected'] * 0.01){
        	return bonusMsg(502);
        }

        $res_check = AccountService::checkSecret($tel_number, $secret);
        if($res_check){
        	return accountMsg($res_check);
        }
/*
        $res_check = SmsManageService::checkSmscode($tel_number, $verification_code);
        if($res_check){
        	return accountMsg($res_check);
        }
*/
        $coin_rate = COIN_RATE;
        $bouns_coin = bcdiv($bonus_amount, $coin_rate, 4);	//获取分红代币数量
        $node_owner_id = $node_data['user_id'];	//获取分红节点的用户id
        $owner_amount = AccountService::getAmountById($node_owner_id);	//获取分红节点的用户余额
        if($owner_amount['amount'] < $bouns_coin){
        	return bonusMsg(501);
        }

        $total_ticket_age = 0;
        $ticket_age = [];
        $vote_data = VoteService::getVoteDataByNodeId($node_id);
        $vote_account = $node_data['vote_account'];
        for($index = 0; $index < count($vote_data); $index++){
        	$vote_number = $vote_data[$index]['vote_number'];
        	$hours = ceil((time() - $vote_data[$index]['vote_timestamp']) / 3600);
        	$age = bcdiv(bcmul($vote_number, $hours, 4), $vote_account, 4);

        	if(array_key_exists($vote_data[$index]['user_id'], $ticket_age)){
        		$ticket_age[$vote_data[$index]['user_id']] = bcadd($ticket_age[$vote_data[$index]['user_id']], $age, 4) ;
        	}
        	else{
        		$ticket_age[$vote_data[$index]['user_id']] = $age;
        	}
        	$total_ticket_age = bcadd($total_ticket_age, $age, 4);
        }

        $change = $bouns_coin;
        foreach ($ticket_age as $key => $value) {
        	$coins = bcdiv(bcmul($value, $bouns_coin, 4), $total_ticket_age, 4);
        	$bouns_detail[$key] = $coins;
        	$change = bcsub($change, $coins, 4);
        }

        $order_number = buildOrderNo();

        DB::beginTransaction();
        try{
        	$amount_chang = AccountService::setAmountById($node_owner_id, $owner_amount['amount'] - $bouns_coin);
			if(!$amount_chang){
				return bonusMsg(506);
			}else{
				$record_data['operation_type'] = 6;
				$record_data['user_id'] = $node_owner_id;
				$record_data['number'] = -$bouns_coin;
                $record_data['status'] = 1;
				$record_data['operation_id'] = $order_number;
				AccountService::newRecord($record_data);
			}

        	foreach ($bouns_detail as $key => $value) {
        		$amount = AccountService::getAmountById($key);
        		$amount_chang = AccountService::setAmountById($key, $amount['amount'] + $value);
        		if(!$amount_chang){
					return bonusMsg(507);
				}else{
					$record_data['operation_type'] = 7;
					$record_data['user_id'] = $key;
					$record_data['number'] = $value;
                    $record_data['status'] = 1;
					$record_data['operation_id'] = $order_number;
					AccountService::newRecord($record_data);
				}
        	}
        	$bonus['node_id'] = $node_id;
	        $bonus['change'] = $change;
	        $bonus['order_number'] = $order_number;
	        $bonus['bonus_amount'] = $bonus_amount;
	        $bonus['bouns_coin'] = $bouns_coin;
	        $bonus['status'] = 1;
	        BonusService::newBonus($bonus);
	        NodeService::setOverBonus($node_id, $node_data['over_bonus'] - $bonus_amount);
			DB::commit();
        }catch(Exception $e){
        	DB::rollBack();
            return bonusMsg(508);
        }

		return bonusMsg(505);
	}

	public function getRate(){
		return COIN_RATE;
	}

    public function totalBonus(){
        $res_data['total_bonus'] = AccountService::getTotalBonus();
        return $res_data;
    }
}


























