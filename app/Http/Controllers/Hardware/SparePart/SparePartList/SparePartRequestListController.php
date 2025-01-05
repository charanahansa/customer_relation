<?php
namespace App\Http\Controllers\Hardware\SparePart\SparePartList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Hardware\SparePart\Bin;
use App\Models\Hardware\SparePart\HwBin;
use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\SparePart\SparePartRequestProcess;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class SparePartRequestListController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartProcess = new SparePartProcess();
		$objSparePartRequestProcess = new SparePartRequestProcess();

		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part_issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['spare_part'] = $objSparePartProcess->get_spare_part();
		$data['data_table'] = $objSparePartRequestProcess->get_spare_part_request_list('', 4);
        $data['data_table'] = array();
		$data['attributes'] = $this->get_spare_part_request_attributes(NULL);

		return view('hardware.spare_part.spare_part_list.spare_part_request_list')->with('SPR', $data);
	}

	private function get_spare_part_request_attributes($request){

		$attributes['part_type'] = '';
		$attributes['issue_type'] = 4;
		$attributes['bin_id'] = 0;
		$attributes['bank'] = '0';
		$attributes['officer_id'] = 0;
        $attributes['spare_part'] = 0;
        $attributes['from_date'] = '';
		$attributes['to_date'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if( (is_null($request) == TRUE)){

            return $attributes;
        }

		if(is_null($request) == FALSE){

			$attributes['part_type'] = '';
			$attributes['issue_type'] = $request->issue_type;
			$attributes['bin_id'] = $request->bin_id;
			$attributes['bank'] = $request->bank;
			$attributes['officer_id'] = $request->officer_id;
	        $attributes['spare_part'] = $request->spare_part;
	        $attributes['from_date'] = $request->from_date;
			$attributes['to_date'] = $request->to_date;

			// echo '<pre>';
			// print_r($attributes);
			// echo '</pre>';

			return $attributes;
		}

	}

	public function spare_part_request_list_process(Request $request){

		$query_filter = " ";

		// ---------------------------------------------- Part Type -------------------------------------------
		$query_filter .= " && part_type = '". $request->part_type ."' ";

		// ---------------------------------------------- Spare Part -------------------------------------------
		if($request->spare_part != 0){

			$query_filter .= " && spare_part_id = ". $request->spare_part ."  ";
		}

		// ---------------------------------------------- Date Period -------------------------------------------
		if( ($request->from_date != '') && ($request->to_date != '') ){

			$query_filter .= " && spr_date between '". $request->from_date ."' and '". $request->to_date ."'  ";
		}

		// ---------------------------------------------- Issue Type -------------------------------------------
		if($request->issue_type == 1){

			$query_filter .= " && issue_type = '". $request->issue_type ."'";
		}

		if($request->issue_type == 2){

			$query_filter .= " && issue_type = '". $request->issue_type ."' ";
		}

		if($request->issue_type == 3){

			$query_filter .= " && issue_type = '". $request->issue_type ."' ";
		}

		if($request->issue_type == 4){

			$query_filter .= " && issue_type = '". $request->issue_type ."'";
		}

		// ---------------------------------------------- Bank -------------------------------------------
		if($request->bank != '0'){

			$query_filter .= " && sprn.bank = '". $request->bank ."' ";
		}

		// ---------------------------------------------- Officer -------------------------------------------
		if($request->officer_id != 0){

			$query_filter .= " && sprn.user_id = '". $request->officer_id ."' ";
		}

		// ---------------------------------------------- Bin -------------------------------------------
		if($request->bin != 0){

			$query_filter .= " && sprn.to_bin_id = '". $request->bin_id ."'  ";
		}

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartProcess = new SparePartProcess();
		$objSparePartRequestProcess = new SparePartRequestProcess();

		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part_issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['spare_part'] = $objSparePartProcess->get_spare_part();
		$data['data_table'] = $objSparePartRequestProcess->get_spare_part_request_list($query_filter, $request->issue_type);
		$data['attributes'] = $this->get_spare_part_request_attributes($request);

		return view('hardware.spare_part.spare_part_list.spare_part_request_list')->with('SPR', $data);
	}

	public function get_sp_bin_qty(Request $request){

		$objSparePartRequestProcess = new SparePartRequestProcess();
		$objHwBin = new HwBin();

		$spr_result= $objSparePartRequestProcess->get_spare_part_request_note($request->spare_part_request_id);
		foreach($spr_result as $row){

			$bin_qty_result = $objHwBin->get_bin_spare_part_quantity_total_bin_wise($row->spare_part_id);
		}

		return response()->json($bin_qty_result);
	}

}
