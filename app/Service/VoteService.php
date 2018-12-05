<?php
namespace App\Service;
use App\Model\NodeVote;

class VoteService{
	public static function newVote($data){
		return NodeVote::create($data);
	}

	public static function getVoteByUserId($user_id){
		return NodeVote::where(['user_id' => $user_id])->get();
	}

	public static function getVoteNumberByStatus($status){
		return NodeVote::where(['vote_status' => $status])->sum('vote_number');
	}

	public static function getVoteDataByNodeId($node_id){
		return NodeVote::where(['node_id' => $node_id, 'vote_status'=> 1])->get();
	}

	public static function getVoteDataByOrderNumber($order_number){
		return NodeVote::where(['order_number' => $order_number])->first();
	}

	public static function updataVoteData($order_number, $data){
		return NodeVote::where(['order_number' => $order_number])->update($data);
	}

	public static function getVoteDataByStatus($vote_status){
		return NodeVote::where(['vote_status'=> $vote_status])->get();
	}
}