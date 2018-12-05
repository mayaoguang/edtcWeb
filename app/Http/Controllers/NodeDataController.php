<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\NodeService;

class NodeDataController extends Controller
{
	public function nodeList(){
		$data = NodeService::getNodeList();
		return $data;
	}

	public function nodeBeforeList(){
		$data = NodeService::getBeforeList();
		return $data;
	}

	public function nodeData(Request $request){
		$node_id = $request->get('node_id');
		$data = NodeService::getNodeById($node_id);
		return $data;
	}

	public function getNodeNameByCity(Request $request){
		$city = $request->get('address');
		$data = NodeService::getNodeNameByCity($city);
		return $data;
	}

	public function getVoteAccount(Request $request){
		$node_id = $request->get('node_id');
		$res_data['vote_account'] = NodeService::getVoteAccountById($node_id);
		return $res_data['vote_account'];
	}
}