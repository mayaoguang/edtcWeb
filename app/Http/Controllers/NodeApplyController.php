<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\BrandsService;
use App\Service\NodeService;

class NodeApplyController extends Controller
{
	public function nodeApply(Request $request){
		$data = $request->all();

		if(strlen($data['node_name']) > 20){
			return nodeMsg(200);
		}
		if($data['bonus_expected'] < 100000){
			return nodeMsg(201);
		}
		if(strlen($data['node_description']) > 500){
			return nodeMsg(202);
		}
		if(strlen($data['founder_name']) > 6){
			return nodeMsg(203);
		}
		if(strlen($data['tel_number']) != 11){
			return nodeMsg(204);
		}
		if(strlen($data['founder_description']) > 500){
			return nodeMsg(205);
		}
		if(strlen($data['team_member']) > 500){
			return nodeMsg(206);
		}

		$brands = BrandsService::getBrandsTypeById($data['type']);
        if(!$brands){
			return nodeMsg(207);
        }

        $data['brands_id'] = $brands['brands_id'];
        $data['approve_floor'] = $brands['type_number'];
        $data['over_bonus'] = $data['bonus_expected'];
        $data['vote_account'] = 0;
        $data['check_status'] = 1;
        $data['vote_status'] = 1;
        $data['approve_status'] = 1;
        $data['bonus_status'] = 0;
        $data['is_before'] = false;

        $node = NodeService::newNode($data);
        return $node;
	}
}