<?php

namespace App\Http\Controllers\Hardware\SparePart\SparePartList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Hardware\SparePart\Bin;
use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\SparePart\SparePartIssueProcess;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class SparePartIssueListController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartIssueProcess = new SparePartIssueProcess();
		$objSparePartProcess = new SparePartProcess();

		$data['bank'] = $objBank->get_bank();
		$data['user'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part'] = $objSparePartProcess->get_spare_part();
		$data['issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['data_table'] = $objSparePartIssueProcess->get_spare_part_issue_list('');
		$data['attributes'] = $this->get_spare_part_issue_list_attributes(NULL) ;

		return view('hardware.spare_part.spare_part_list.spare_part_issue_list')->with('SPIL', $data);
	}

    private function get_spare_part_issue_list_attributes($request){

		$attributes['part_type'] = '';
		$attributes['issue_type'] = 0;
		$attributes['from_bin_id'] = 0;
		$attributes['to_bin_id'] = 0;
		$attributes['bank'] = 0;
		$attributes['user_id'] = 0;
		$attributes['spare_part'] = 0;
		$attributes['from_date'] = '';
		$attributes['to_date'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if(is_null($request) == TRUE){

            return $attributes;
        }

		if(is_null($request) == FALSE){


			$attributes['part_type'] = $request->part_type;
			$attributes['issue_type'] = $request->issue_type;
			$attributes['from_bin_id'] = $request->from_bin_id;
			$attributes['to_bin_id'] = $request->to_bin_id;
			$attributes['bank'] = $request->bank;
			$attributes['user_id'] = $request->user_id;
			$attributes['spare_part'] = $request->spare_part;
			$attributes['from_date'] = $request->from_date;
			$attributes['to_date'] = $request->to_date;

			return $attributes;
		}

	}

	public function spare_part_issue_list_process(Request $request){

		$query_filter = " ";


		$query_filter .= " && part_type = '". $request->part_type ."' ";

		if($request->spare_part != 0){

			$query_filter .= " && spare_part_id = ". $request->spare_part ."  ";
		}

		if( ($request->from_date != '') && ($request->to_date != '') ){

			$query_filter .= " && spi_date between '". $request->from_date ."' and '". $request->to_date ."'  ";
		}

		if($request->issue_type == 1){

			if($request->bin == 0){

				$query_filter .= " && issue_type = '". $request->issue_type ."'";

			}else{

				$query_filter .= " && issue_type = '". $request->issue_type ."' && to_bin_id = '". $request->bin_id ."'  ";
			}
		}

		if($request->issue_type == 2){

			if($request->bank == 0){

				$query_filter .= " && issue_type = '". $request->issue_type ."' ";

			}else{

				$query_filter .= " && issue_type = '". $request->issue_type ."' && bank = '". $request->bank ."'  ";
			}
		}

		if($request->issue_type == 3){

			if($request->user_id == 0){

				$query_filter .= " && issue_type = '". $request->issue_type ."' ";

			}else{

				$query_filter .= " && issue_type = '". $request->issue_type ."' && spin.user_id = '". $request->user_id ."' ";
			}
		}


		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartIssueProcess = new SparePartIssueProcess();
		$objSparePartProcess = new SparePartProcess();

		$data['bank'] = $objBank->get_bank();
		$data['user'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part'] = $objSparePartProcess->get_spare_part();
		$data['issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['data_table'] = $objSparePartIssueProcess->get_spare_part_issue_list($query_filter);
		$data['attributes'] = $this->get_spare_part_issue_list_attributes($request) ;

		return view('hardware.spare_part.spare_part_list.spare_part_issue_list')->with('SPIL', $data);



	}


}
