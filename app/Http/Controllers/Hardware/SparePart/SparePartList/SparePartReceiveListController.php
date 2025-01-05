<?php

namespace App\Http\Controllers\Hardware\SparePart\SparePartList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\SparePart\SparePartReceiveProcess;
use App\Models\Hardware\SparePart\Buyer;
use App\Models\Hardware\SparePart\Bin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class SparePartReceiveListController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objSparePartReceiveProcess = new SparePartReceiveProcess();
		$objBuyer = new Buyer();
		$objBin = new Bin();

		$data['buyer'] = $objBuyer->get_buyers_active_list();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part'] = $objSparePartReceiveProcess->get_spare_part();
		$data['data_table'] = $objSparePartReceiveProcess->get_spare_part_receive_list('');
		$data['attributes'] = $this->get_spare_part_receive_list_attributes(NULL, NULL) ;

		return view('hardware.spare_part.spare_part_list.spare_part_receive_list')->with('SPRL', $data);
	}

	private function get_spare_part_receive_list_attributes($process, $request){

        $attributes['from_date'] = date("Y/m/d");
		$attributes['to_date'] = date("Y/m/d");
		$attributes['buyer_id'] = 0;
		$attributes['bin_id'] = 0;
		$attributes['part_type'] = '';
		$attributes['spare_part'] = 0;

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if((is_null($process) == TRUE) && (is_null($request) == FALSE)){

			$attributes['from_date'] = $request->from_date;
			$attributes['to_date'] = $request->to_date;
			$attributes['buyer_id'] = $request->buyer;
			$attributes['bin_id'] = $request->bin;
			$attributes['part_type'] = $request->part_type;
			$attributes['spare_part'] = $request->spare_part;

			return $attributes;
        }

	}

	public function spare_part_receive_list_process(Request $request){

		$query_filter = " ";

		if($request->buyer != 0){

			$query_filter .= " && b.buyer_id = '". $request->buyer ."'  ";
		}

		if($request->part_type != ''){

			$query_filter .= " && part_type = '". $request->part_type ."'  ";
		}

		if($request->bin != 0){

			$query_filter .= " && bn.bin_id = '". $request->bin ."'  ";
		}

		if($request->spare_part != 0){

			$query_filter .= " && sprn.spare_part_id = ". $request->spare_part ."  ";
		}

		if( ($request->from_date != '') && ($request->to_date != '') ){

			$query_filter .= " && spr_date between '". $request->from_date ."' and '". $request->to_date ."'  ";
		}

		$objSparePartReceiveProcess = new SparePartReceiveProcess();
		$objBuyer = new Buyer();
		$objBin = new Bin();

		$data['buyer'] = $objBuyer->get_buyers_active_list();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part'] = $objSparePartReceiveProcess->get_spare_part();
		$data['data_table'] = $objSparePartReceiveProcess->get_spare_part_receive_list($query_filter);
		$data['attributes'] = $this->get_spare_part_receive_list_attributes(NULL, $request) ;

		return view('hardware.spare_part.spare_part_list.spare_part_receive_list')->with('SPRL', $data);
	}

}
