<?php
namespace App\Service;
use App\Model\NodeDetails;

class NodeService{
	public static function getNodeById($node_id){
		return NodeDetails::where('id',$node_id)->first();
	}

	public static function getOwnerById($node_id){
		return NodeDetails::where(['id' => $node_id])->first()->user_id;
	}

	public static function getNodeNameById($node_id){
		return NodeDetails::where(['id' => $node_id])->first()->node_name;
	}

	public static function getBonusDataById($node_id){
		return NodeDetails::where(['id' => $node_id])->first(['over_bonus', 'bonus_expected']);
	}

	public static function newNode($data){
		return NodeDetails::create($data);
	}

	public static function getNodeList(){
		return NodeDetails::get();
	}

	public static function getBeforeList(){
		return NodeDetails::where(['is_before' => true])->get();
	}

	public static function getNodeNameByCity($city){
		return NodeDetails::where(['address' => $city])->get(['id','node_name']);
	}

	public static function getVoteAccountById($node_id){
		return NodeDetails::where(['id' => $node_id])->first()->vote_account;
	}

	public static function setVoteAccount($node_id, $account){
		return NodeDetails::where('id', $node_id)->update(['vote_account' => $account]);
	}

	public static function setOverBonus($node_id, $over_bonus){
		return NodeDetails::where('id', $node_id)->update(['over_bonus' => $over_bonus]);
	}
}