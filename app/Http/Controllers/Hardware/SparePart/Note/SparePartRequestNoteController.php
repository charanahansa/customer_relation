<?php

namespace App\Http\Controllers\Hardware\SparePart\Note;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Hardware\SparePart\Bin;
use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\SparePart\SparePartRequestProcess;

use App\Rules\ZeroValidation;
use App\Rules\CurrencyValidation;
use App\Rules\Hardware\SparePart\SparePartRequestRejectValidation;
use App\Rules\Hardware\SparePart\SparePartRequestValidation;
use App\Rules\Hardware\SparePart\BankValidation;

class SparePartRequestNoteController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartProcess = new SparePartProcess();

		$data['bank'] = $objBank->get_bank();
		$data['user'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
		$data['attributes'] = $this->get_spare_part_request_note_attributes(NULL, NULL);

		return view('Hardware.spare_part.note.spare_part_request_note')->with('SPR', $data);
	}

	private function get_spare_part_request_note_attributes($process, $request){

		$objSparePartProcess = new SparePartProcess();

		$attributes['spr_id'] = '#Auto#';
        $attributes['spr_date'] = date("Y/m/d");
		$attributes['part_type'] = '';
		$attributes['issue_type'] = '';
		$attributes['to_bin_id'] = 0;
		$attributes['bank'] = '0';
		$attributes['user_id'] = 0;
		$attributes['remark'] = '';
		$attributes['spare_part_id'] = 0;
		$attributes['spare_part_name'] = '';
		$attributes['quantity'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$objSparePartRequestProcess = new SparePartRequestProcess();

			$spi_resultset = $objSparePartRequestProcess->get_spare_part_request_note($process['spr_id']);
			foreach($spi_resultset as $row){

				$attributes['spr_id'] = $row->spr_id;
		        $attributes['spr_date'] = $row->spr_date;
				$attributes['part_type'] = $row->part_type;
				$attributes['issue_type'] = $row->issue_type;
				$attributes['to_bin_id'] = $row->to_bin_id;
				$attributes['bank'] = $row->bank;
				$attributes['user_id'] = $row->user_id;
				$attributes['remark'] = $row->remark;
				$attributes['spare_part_id'] = $row->spare_part_id;
				$attributes['spare_part_name'] =  $objSparePartProcess->get_spare_part_name($row->spare_part_id);
				$attributes['quantity'] = $row->quantity;

			}

			$attributes['validation_messages'] = new MessageBag();

			$message = $process['front_end_message'];
			$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

			return $attributes;

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				//$attributes['spr_id'] = $input['spr_id'];
		        $attributes['spr_date'] = $input['spr_date'];
				$attributes['part_type'] = $input['part_type'];
				$attributes['issue_type'] = $input['issue_type'];
				$attributes['to_bin_id'] = $input['to_bin_id'];
				$attributes['bank'] = $input['bank'];
				$attributes['user_id'] = $input['user_id'];
				$attributes['remark'] = $input['remark'];
				$attributes['spare_part_id'] = $input['spare_part_id'];
				$attributes['spare_part_name'] = $objSparePartProcess->get_spare_part_name($input['spare_part_id']);
				$attributes['quantity'] = $input['quantity'];
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

			return $attributes;
		}

	}

	public function spare_part_request_note_process(Request $request){

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartProcess = new SparePartProcess();

		$validation_result = $this->validation_process($request);
		if($validation_result['validation_result'] == TRUE){

			$spr_saving_result = $this->saving_process($request);

			$spr_saving_result['validation_result'] = $validation_result['validation_result'];
            $spr_saving_result['validation_messages'] = $validation_result['validation_messages'];

            $data['attributes'] = $this->get_spare_part_request_note_attributes($spr_saving_result, $request);

		}else{

			$validation_result['spr_id'] = '';
			$validation_result['process_status'] = FALSE;

			$data['attributes'] = $this->get_spare_part_request_note_attributes($validation_result, $request);
		}

		$data['bank'] = $objBank->get_bank();
		$data['user'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();

		return view('Hardware.spare_part.note.spare_part_request_note')->with('SPR', $data);
	}

	private function validation_process($request){

		//try{

			$front_end_message = " ";

			$input['spr_date'] = $request->spr_date;
			$input['spare_part_id'] = $request->spare_part_id;
			$input['quantity']= $request->quantity;
            $input['remark'] = $request->remark;

			if($request->issue_type == 1){

				$input['to_bin_id'] = $request->to_bin_id;
				$rules['to_bin_id'] = array( new ZeroValidation('From Bin', $request->to_bin_id));

			}elseif($request->issue_type == 2){

				$input['bank'] = $request->bank;
				$rules['bank'] = array( new BankValidation('Bank', $request->bank));

			}elseif($request->issue_type == 3){

				$input['user'] = $request->user_id;
				$rules['user'] = array( new ZeroValidation('Officer', $request->user_id));

			}

			$rules['spr_date'] = array('required', 'date');
			$rules['spare_part_id'] = array( new ZeroValidation('Spare Part', $request->spare_part_id));
			$rules['quantity']= array('required', 'numeric', new CurrencyValidation(0));
            $rules['remark'] = array('max:500');

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

	private function saving_process($request){

		//try{

			$objSparePartRequestProcess = new SparePartRequestProcess();

			$data['spare_part_request_note'] = $this->spare_part_request_note($request);

			$spare_part_saving_process_result = $objSparePartRequestProcess->save_spare_part_request_note($data);

			return $spare_part_saving_process_result;

		// }catch(\Exception $e){

		// 	$process_result['spr_id'] = $request->spr_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Receive Note Controller -> Spare Part Receive Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function spare_part_request_note($request){

		$objSparePartProcess = new SparePartProcess();

		$spi_table['spr_id'] = $request->spr_id;
		$spi_table['spr_date'] = $request->spr_date;
		$spi_table['part_type'] = $request->part_type;
		$spi_table['issue_type'] = $request->issue_type;
		$spi_table['spi_id'] = 0;
		$spi_table['from_bin_id'] = 1;

		if($request->issue_type == 1){

			$spi_table['to_bin_id'] = $request->to_bin_id;
			$spi_table['bank'] = 0;
			$spi_table['user_id'] = 0;
			$spi_table['workflow_id'] = 0;
			$spi_table['reference_number'] = 0;

		}elseif($request->issue_type == 2){

			$spi_table['to_bin_id'] = 0;
			$spi_table['bank'] = $request->bank;
			$spi_table['user_id'] = 0;
			$spi_table['workflow_id'] = 0;
			$spi_table['reference_number'] = 0;

		}elseif($request->issue_type == 3){

			$spi_table['to_bin_id'] = 0;
			$spi_table['bank'] = 0;
			$spi_table['user_id'] = $request->user_id;
			$spi_table['workflow_id'] = 0;
			$spi_table['reference_number'] = 0;

		}

		$spi_table['spare_part_id'] = $request->spare_part_id;
		$spi_table['spare_part_name'] = $objSparePartProcess->get_spare_part_name($request->spare_part_id);
		$spi_table['quantity'] = $request->quantity;
		$spi_table['remark'] = $request->remark;

		$spi_table['saved_by'] = Auth::id();
		$spi_table['saved_on'] = now();

		$spi_table['issue'] = 0;
        $spi_table['reject'] = 0;
        $spi_table['cancel'] = 0;

		return $spi_table;
	}

	public function get_spare_part_request_note(Request $request){

		$objSparePartRequestProcess = new SparePartRequestProcess();

		$spi_resultset = $objSparePartRequestProcess->get_spare_part_request_note($request->spare_part_request_id);

		return response()->json($spi_resultset);
	}

	public function spare_part_request_note_reject_process(Request $request){

		$validation_result = $this->spare_part_reject_validation_process($request);
		if($validation_result['validation_result'] == TRUE){

			$spi_reject_saving_result = $this->spare_part_reject_saving_process($request);

			$spi_reject_saving_result['validation_result'] = $validation_result['validation_result'];
            $spi_reject_saving_result['validation_messages'] = $validation_result['validation_messages'];

            return response()->json($spi_reject_saving_result);

		}else{

			$validation_result['spr_id'] = '';
			$validation_result['process_status'] = FALSE;

			return response()->json($validation_result);
		}

	}

	private function spare_part_reject_validation_process($request){

		try{

			$front_end_message = " ";

			$input['spr_id'] = $request->spare_part_request_id;

			$rules['spr_id'] = array('required',  new SparePartRequestRejectValidation(), new SparePartRequestValidation());

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

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Spare Part Request Controller - Validation Function Fault';

			return $process_result;
		}

	}

	public function spare_part_reject_saving_process($request){

		$sqlarr['spr_id'] = $request->spare_part_request_id;
		$sqlarr['reject'] = 1;
		$sqlarr['reject_by'] = Auth::id();
		$sqlarr['rejected_on'] = Now();

		$objSparePartRequestProcess = new SparePartRequestProcess();

		$result = $objSparePartRequestProcess->update_spare_part_reject_request_numbers($sqlarr);

		return $result;
	}

}
