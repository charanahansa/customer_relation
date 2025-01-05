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
use App\Models\Hardware\SparePart\SparePartIssueProcess;
use App\Models\Hardware\SparePart\SparePartRequestProcess;

use App\Rules\ZeroValidation;
use App\Rules\CurrencyValidation;
use App\Rules\Hardware\SparePart\BinQuantityValidation;
use App\Rules\Hardware\SparePart\SparePartRequestValidation;
use App\Rules\Hardware\SparePart\SparePartRequestRejectValidation;

class SparePartIssueNoteController extends Controller {

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
		$data['attributes'] = $this->get_spare_part_issue_note_attributes(NULL, NULL);

		return view('Hardware.spare_part.note.spare_part_issue_note')->with('SPI', $data);
	}

	private function get_spare_part_issue_note_attributes($process, $request){

		$attributes['spi_id'] = '#Auto#';
        $attributes['spi_date'] = date("Y/m/d");
		$attributes['part_type'] = '';
		$attributes['issue_type'] = 0;
		$attributes['from_bin_id'] = 0;
		$attributes['to_bin_id'] = 0;
		$attributes['bank'] = 0;
		$attributes['user_id'] = 0;
		$attributes['remark'] = '';
		$attributes['spare_part_id'] = 0;
		$attributes['spare_part_name'] = '';
		$attributes['quantity'] = '';

		$attributes['process_status'] = FALSE;
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if((is_null($process) == FALSE) && (is_null($request) == TRUE)){


			$objSparePartRequestProcess = new SparePartRequestProcess();
			$objSparePartProcess = new SparePartProcess();
			$spr_resultset = $objSparePartRequestProcess->get_spare_part_request_note($process['spr_id']);
			foreach($spr_resultset as $row){

				$attributes['spi_id'] = '#Auto#';
				$attributes['spi_date'] = date("Y/m/d");
				$attributes['part_type'] = $row->part_type;
				$attributes['issue_type'] = $row->issue_type;

				$attributes['from_bin_id'] = $row->from_bin_id;
				$attributes['to_bin_id'] = $row->to_bin_id;
				$attributes['bank'] = $row->bank;
				$attributes['user_id'] = $row->user_id;

				$attributes['remark'] = $row->remark;
				$attributes['spare_part_id'] = $row->spare_part_id;
				$attributes['spare_part_name'] = $objSparePartProcess->get_spare_part_name($row->spare_part_id);
				$attributes['quantity'] = $row->quantity;
			}


            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$objSparePartIssueProcess = new SparePartIssueProcess();
			$objSparePartProcess = new SparePartProcess();

			$spi_resultset = $objSparePartIssueProcess->get_spare_part_issue_note($process['spi_id']);
			foreach($spi_resultset as $row){

				$attributes['spi_id'] = $row->spi_id;
		        $attributes['spi_date'] = $row->spi_date;
				$attributes['part_type'] = $row->part_type;
				$attributes['issue_type'] = $row->issue_type;

				$attributes['from_bin_id'] = $row->from_bin_id;
				$attributes['to_bin_id'] = $row->to_bin_id;
				$attributes['bank'] = $row->bank;
				$attributes['user_id'] = $row->user_id;

				$attributes['remark'] = $row->remark;
				$attributes['spare_part_id'] = $row->spare_part_id;
				$attributes['spare_part_name'] = $objSparePartProcess->get_spare_part_name($row->spare_part_id);
				$attributes['quantity'] = $row->quantity;

			}

			$attributes['process_status'] = TRUE;
			$attributes['validation_messages'] = new MessageBag();

			$message = $process['front_end_message'];
			$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

			return $attributes;

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				//$attributes['spi_id'] = $input['spi_id'];
		        $attributes['spi_date'] = $input['spi_date'];
				$attributes['part_type'] = $input['part_type'];
				$attributes['issue_type'] = $input['issue_type'];
				$attributes['from_bin_id'] = $input['from_bin_id'];
				$attributes['to_bin_id'] = $input['to_bin_id'];
				$attributes['bank'] = $input['bank'];
				$attributes['user_id'] = $input['user_id'];
				$attributes['remark'] = $input['remark'];
				$attributes['spare_part_id'] = $input['spare_part_id'];
				$attributes['spare_part_name'] = $input['part_type'];
				$attributes['quantity'] = $input['quantity'];
			}

			$attributes['process_status'] = FALSE;
			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

			return $attributes;
		}

	}

	public function spare_part_issue_note_process(Request $request){

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartProcess = new SparePartProcess();

		$validation_result = $this->validation_process($request);
		if($validation_result['validation_result'] == TRUE){

			$spi_saving_result = $this->saving_process($request);

			$spi_saving_result['validation_result'] = $validation_result['validation_result'];
            $spi_saving_result['validation_messages'] = $validation_result['validation_messages'];

            $data['attributes'] = $this->get_spare_part_issue_note_attributes($spi_saving_result, $request);

		}else{

			$validation_result['spi_id'] = '';
			$validation_result['process_status'] = FALSE;

			$data['attributes'] = $this->get_spare_part_issue_note_attributes($validation_result, $request);
		}

		$data['bank'] = $objBank->get_bank();
		$data['user'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
        $data['input'] = $request->input();

		if(isset($request->spare_part_request_id)){

			$information['data'] = $data;

			return json_encode($information);
			//return response()->json($information);
			exit;

		}else{

			return view('Hardware.spare_part.note.spare_part_issue_note')->with('SPI', $data);
		}
	}

	private function validation_process($request){

		//try{

			$front_end_message = " ";

			$input['spi_date'] = $request->spi_date;
	        $input['from_bin_id'] = $request->from_bin_id;
			$input['spare_part'] = $request->spare_part_id;
			$input['quantity']= $request->quantity;
            $input['remark'] = $request->remark;

			$rules['spi_date'] = array('required', 'date');
	        $rules['from_bin_id'] = array( new ZeroValidation('From Bin', $request->from_bin_id));
			$rules['spare_part'] = array( new ZeroValidation('Spare Part', $request->spare_part_id));
			$rules['quantity']= array('required', 'numeric', new BinQuantityValidation($request->from_bin_id, $request->spare_part_id));
            $rules['remark'] = array('required', 'max:500');

			if($request->spare_part_issue_type == 1){

				$input['to_bin_id'] = $request->to_bin_id;
				$rules['to_bin_id'] = array( new ZeroValidation('From Bin', $request->to_bin_id));
			}

			if($request->spare_part_issue_type == 2){

				$input['bank'] = $request->bank;
				$rules['bank'] = array( new ZeroValidation('Bank', $request->bank));
			}

			if($request->spare_part_issue_type == 3){

				$input['user_id'] = $request->user_id;
				$rules['user_id'] = array( new ZeroValidation('Officer', $request->user_id));
			}

            if(isset($request->spare_part_request_id)){

                $input['spr_id'] = $request->spare_part_request_id;
                $rules['spr_id'] = array( new SparePartRequestValidation(), new SparePartRequestRejectValidation());
            }

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Spare Part Receive Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Spare Part Receive Controller - Validation Function Fault';

		// 	return $process_result;
		// }
	}

	private function saving_process($request){

		//try{

			$objSparePartIssueProcess = new SparePartIssueProcess();

			$data['spare_part_issue_note'] = $this->spare_part_issue_note($request);
			$data['spare_part_request_note'] = $this->spare_part_request_note($request);
			$data['hw_bin'] = $this->hardware_bin($request);

			$spare_part_saving_process_result = $objSparePartIssueProcess->save_spare_part_issue_note($data, $request);

			return $spare_part_saving_process_result;

		// }catch(\Exception $e){

		// 	$process_result['spi_id'] = $request->spi_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Receive Note Controller -> Spare Part Receive Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function spare_part_issue_note($request){

		$objSparePartProcess = new SparePartProcess();

		$spi_table['spi_id'] = $request->spi_id;
		$spi_table['spi_date'] = $request->spi_date;
		$spi_table['part_type'] = $request->part_type;
		$spi_table['issue_type'] = $request->issue_type;

		$spi_table['from_bin_id'] = $request->from_bin_id;
		$spi_table['to_bin_id'] = $request->to_bin_id;
		$spi_table['bank'] = $request->bank;
		$spi_table['user_id'] = $request->user_id;

		$spi_table['spare_part_id'] = $request->spare_part_id;
		$spi_table['spare_part_name'] = $objSparePartProcess->get_spare_part_name($request->spare_part_id);
		$spi_table['quantity'] = $request->quantity;
		$spi_table['remark'] = $request->remark;

		if(isset($request->got_old_spare_part)){

			$spi_table['got_old_spare_part'] = $request->got_old_spare_part;
		}else{

			$spi_table['got_old_spare_part'] = 0;
		}

		$spi_table['saved_by'] = Auth::id();
		$spi_table['saved_on'] = now();

		return $spi_table;
	}

	private function hardware_bin($request){

		$objSparePartProcess = new SparePartProcess();

		$hw_bin['bin_id'] = $request->to_bin_id;
		$hw_bin['in_id'] = $request->spi_id;
		$hw_bin['in_ref'] = 'SPI';
		$hw_bin['spare_part_id'] = $request->spare_part_id;
		$hw_bin['spare_part_name'] = $objSparePartProcess->get_spare_part_name($request->spare_part_id);
		$hw_bin['spare_part_serial'] = 0;
		$hw_bin['quantity'] = 1;
		$hw_bin['remark'] = $request->remark;
		$hw_bin['out_id'] = 0;
		$hw_bin['out_ref'] = '';

		return $hw_bin;
	}

	private function spare_part_request_note($request){

		$spr_table['spi_id'] = 0;
		$spr_table['issue'] = 1;
		$spr_table['issued_by'] = Auth::id();
		$spr_table['issued_on'] = now();

		return $spr_table;
	}

    public function spare_part_issue_note_load($spr_id){

		$objBank = new Bank();
		$objUser = new User();
		$objBin = new Bin();
		$objSparePartProcess = new SparePartProcess();

		$process_result['spr_id'] = $spr_id;
		$process_result['process_status'] = TRUE;
		$process_result['front_end_message'] = " ";
		$process_result['back_end_message'] = " ";

		$data['bank'] = $objBank->get_bank();
		$data['user'] = $objUser->get_active_users();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['issue_type'] = $objSparePartProcess->get_spare_part_issue_type();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
		$data['attributes'] = $this->get_spare_part_issue_note_attributes($process_result, NULL);

		return view('Hardware.spare_part.note.spare_part_issue_note')->with('SPI', $data);
	}
}
