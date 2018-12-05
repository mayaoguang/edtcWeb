<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\NodeService;
use App\Service\AccountService;
use App\Service\VoteService;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
	public function voteConfirm(Request $request){
		$data = $request->all();

		$amount = AccountService::getAmountById($data['user_id']);
		if($amount['amount'] < $data['vote_number']){
			return voteMsg(300);
		}

		DB::beginTransaction();
		try{
			$amount_chang = AccountService::setAmountById($data['user_id'], $amount['amount'] - $data['vote_number']);
			$lock_res = AccountService::setLockAmountById($data['user_id'], $amount['lock_amount'] + $data['vote_number']);
			if(!$amount_chang){
				return voteMsg(302);
			}

			$vote_data['user_id'] = $data['user_id'];
			$vote_data['node_id'] = $data['node_id'];
			$vote_data['vote_number'] = $data['vote_number'];
			$vote_data['vote_status'] = 1;
			$vote_data['vote_timestamp'] = time();
			$vote_data['order_number'] = buildOrderNo();

			$res = VoteService::newVote($vote_data);
			if($res){
				$res_msg = voteMsg(301);
				$res_msg['data'] = $res;
			}

			$vote_account = NodeService::getVoteAccountById($data['node_id']);
			$account_change = NodeService::setVoteAccount($data['node_id'], $vote_account + $data['vote_number']);

			$record_data['operation_type'] = 5;
			$record_data['user_id'] = $data['user_id'];
			$record_data['number'] = -$data['vote_number'];
			$record_data['status'] = 1;
			$record_data['operation_id'] = $vote_data['order_number'];
			AccountService::newRecord($record_data);

			DB::commit();
		}catch(Exception $e){
        	DB::rollBack();
            $res_msg = bonusMsg(303);
        }
		return $res_msg;
	}

	public function cancelVote(Request $request){
		$order_number = $request->get('order_number');

		$vote_data = VoteService::getVoteDataByOrderNumber($order_number);
		if($vote_data['vote_status'] == 2){
			return voteMsg(306);
		}elseif ($vote_data['vote_status'] == 3) {
			return voteMsg(307);
		}
		
		$updata['unlock_time'] = time() + UNLOCK_PERIOD;
		$updata['vote_status'] = 2;
		DB::beginTransaction();
		try{
			VoteService::updataVoteData($vote_data['order_number'], $updata);
			$record_data['operation_type'] = 10;
			$record_data['user_id'] = $vote_data['user_id'];
			$record_data['number'] = $vote_data['vote_number'];
			$record_data['status'] = 0;
			$record_data['operation_id'] = $vote_data['order_number'];
			AccountService::newRecord($record_data);
			DB::commit();
			$res_msg = voteMsg(305);
		}catch(Exception $e){
        	DB::rollBack();
            $res_msg = voteMsg(304);
        }
		return $res_msg;
	}

	public function userVoteList(Request $request){
		$user_id = $request->get('user_id');
		$vote_list = VoteService::getVoteByUserId($user_id);

		$vote_length = count($vote_list);
		for($index = 0; $index < $vote_length; $index++){
			$node_name = NodeService::getNodeNameById($vote_list[$index]['node_id']);
			$vote_list[$index]['node_name'] = $node_name;
		}
		return $vote_list;
	}

	public function totalVote(Request $request){
		$res_data['total_vote'] = VoteService::getVoteNumberByStatus(1);
		return $res_data;
	}
}

























