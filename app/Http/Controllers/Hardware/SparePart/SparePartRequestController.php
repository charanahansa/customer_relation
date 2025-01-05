<?php

namespace App\Http\Controllers\Hardware\SparePart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Hardware\SparePart\SparePartRequestProcess;

use App\Rules\ZeroValidation;

use Illuminate\Support\Carbon;

class SparePartRequestController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index($request_id){

		$objSparePartRequestProcess = new SparePartRequestProcess();

		$data['field_officer'] = $objSparePartRequestProcess->get_field_officers();
		$data['spare_part'] = $objSparePartRequestProcess->get_spare_part();
		$data['spare_part_bin'] = $objSparePartRequestProcess->get_spare_part_bin($request_id);

		$data['attributes'] = $this->get_spare_part_request_attributes($request_id, NULL, NULL);

		return view('Hardware.spare_part.spare_part_request')->with('SPR', $data);
	}

	private function get_spare_part_request_attributes($request_id, $process, $request){

		$objSparePartRequestProcess = new SparePartRequestProcess();

		$attributes['issue_id'] = '#Auto#';
        $attributes['issue_date'] = date("Y/m/d");
		$attributes['request_id'] = $request_id;
		$attributes['officer'] = '';
		$attributes['spare_part'] = '';
        $attributes['spare_part_bin'] = 0;
		$attributes['ticket_no'] = '';
		$attributes['ticket_date'] = '';
		$attributes['serial_number'] = '';
		$attributes['model'] = '';
		$attributes['merchant'] = '';
		$attributes['remark'] = '';
		$attributes['got_old_part'] = 0;
		$attributes['fault'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		$spare_part_request_resultset = $objSparePartRequestProcess->get_spare_part_request_detail($request_id);
		foreach($spare_part_request_resultset as $row){

			$attributes['officer'] = $row->requested_by;
			$attributes['spare_part'] = $row->requested_part_id;
			$attributes['serial_number'] = $row->serial_number;
			$attributes['ticket_no'] = $row->ticket_no;
			$attributes['remark'] = $row->remark;
			$attributes['got_old_part'] = $row->got_old_part;
			$attributes['fault'] = $row->fault;

			$breakdown_ticket_resultset = $objSparePartRequestProcess->get_breakdown_ticket_detail($row->ticket_no);
			foreach($breakdown_ticket_resultset as $brow){

				$attributes['model'] = $brow->model;
				$attributes['merchant'] = $brow->merchant;
				$attributes['ticket_date'] = $brow->tdate;
			}
		}

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $process['front_end_message'] .' </div> ';
            $attributes['validation_messages'] = new MessageBag();
            $attributes['issue_id'] = $process['issue_id'];

            return $attributes;

		}else{

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

            $attributes['validation_messages'] = $process['validation_messages'];

            return $attributes;
		}

	}

	public function spare_part_request_process(Request $request){

		$SPR_validation_result = $this->SPR_validation_process($request);

		if($SPR_validation_result['validation_result'] == TRUE){

			$SPR_saving_process = $this->SPR_saving_process($request);

			$SPR_saving_process['validation_result'] = $SPR_validation_result['validation_result'];
			$SPR_saving_process['validation_messages'] = $SPR_validation_result['validation_messages'];

			$data['attributes'] = $this->get_spare_part_request_attributes($request->request_id, $SPR_saving_process, $request);

		}else{

			$SPR_validation_result ['issue_id'] = $request->issue_id;
			$SPR_validation_result ['process_status'] = FALSE;

			$data['attributes'] = $this->get_spare_part_request_attributes($request->request_id, $SPR_validation_result, $request);
		}

		$objSparePartRequestProcess = new SparePartRequestProcess();

		$data['field_officer'] = $objSparePartRequestProcess->get_field_officers();
		$data['spare_part'] = $objSparePartRequestProcess->get_spare_part();
		$data['spare_part_bin'] = $objSparePartRequestProcess->get_spare_part_bin($request->request_id);

		return view('Hardware.spare_part.spare_part_request')->with('SPR', $data);
	}

	private function SPR_validation_process($request){

		//try{

			$front_end_message = " ";

			$input['spare_part'] = $request->spare_part;
	        $input['remark'] = $request->remark;

			$rules['spare_part'] = array('required',  new ZeroValidation('Spare Part', $request->spare_part));
	        $rules['remark'] = array('max:200');

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Spare Part Request Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Spare Part Request Controller - Validation Function Fault';

		// 	return $process_result;
		// }
	}

	private function SPR_saving_process($request){

		//try{

            $objSparePartRequestProcess = new SparePartRequestProcess();

			$data['process'] = $request->submit;
            $data['part_exchange'] = $this->get_part_exchange_table($request);
			$data['part_request'] = $this->get_part_request_table($request);

            $SPR_saving_process_result = $objSparePartRequestProcess->spare_part_request_updating_process($data);

            return $SPR_saving_process_result;

        // }catch(\Exception $e){

        //     $process['settlement_no'] = $request->settlement_no;
        //     $process['process_status'] = FALSE;
        //     $process['front_end_message'] = $e->getMessage();;
        //     $process['back_end_message'] = " Settlement Controller : Settlement Saving Process.  " . $e->getLine();

        //     return $process;
        // }

	}

	private function get_part_exchange_table($request){

		$data['PeNo'] = $request->issue_id;
		$data['Part_ID'] = $request->spare_part;
        $data['ExQty'] = 1;
		$data['FromStore'] = 'MAIN-From Stores';
		$data['ToStore'] = 'Technical';
		$data['IssueTo'] = 'tech';
        $data['IssuedBy'] = Auth::user()->id;
        $data['issuedfrom'] = $request->spare_part_bin;
        $data['techrequest_no'] = $request->request_id;

		return $data;
	}

	private function get_part_request_table($request){

		if($request->submit == 'Issue'){

			$data['part_issued'] = 1;
			$data['part_rejected'] = 0;
			$data['issued_from_id'] = $request->spare_part_bin;
			$data['issued_part_id'] = $request->spare_part;
			$data['issued_date'] = Carbon::now()->toDateTimeString();
			$data['issued_by'] = Auth::user()->id;

		}else{

			$data['part_issued'] = 0;
			$data['issued_from_id'] = NULL;
			$data['issued_part_id'] = NULL;
			$data['part_rejected'] = 1;
			$data['part_rejected_on'] = Carbon::now()->toDateTimeString();
			$data['part_rejected_by'] = Auth::user()->id;
		}

		$data['got_old_part'] = $request->got_old_part;
        $data['remark'] = $request->remark;

		return $data;
	}



}
