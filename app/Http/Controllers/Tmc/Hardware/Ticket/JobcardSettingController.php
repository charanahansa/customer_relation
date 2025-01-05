<?php

namespace App\Http\Controllers\Tmc\Hardware\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Jobcard\Jobcard;
use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;

use App\Rules\ZeroValidation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class JobcardSettingController extends Controller {

	public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objBank = new Bank();
		$objModel = new TerminalModel();

        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objModel->get_models();
        $data['attributes'] = $this->getJobcardSettingAttributes(NULL, NULL);

        return view('tmc.hardware.ticket.jobcard_setting')->with('JS', $data);
    }

	private function  getJobcardSettingAttributes($process, $request){

		$attributes['lot_number'] = '';
        $attributes['box_number'] = '';
        $attributes['jc_date'] = '';
		$attributes['bank'] = '0';
        $attributes['model'] = '0';

        $attributes['process_message'] = '';
        $attributes['validation_messages'] = new MessageBag();

        if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$objJobcard = new Jobcard();

			$jobcard_setting_result = $objJobcard->getJobcardSetting();
			foreach($jobcard_setting_result as $row){

				$attributes['lot_number'] = $row->lot_number;
                $attributes['box_number'] = $row->box_number;
                $attributes['jc_date'] = $row->jc_date;
				$attributes['bank'] = $row->bank;
				$attributes['model'] = $row->model;
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			if( ($process['front_end_message'] == '') || ($process['back_end_message'] == '') ){

				$attributes['process_message'] = '';

			}else{

				$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            	$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';
			}

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				$attributes['lot_number'] = $input['lot_number'];
                $attributes['box_number'] = $input['box_number'];
                $attributes['jc_date'] = $input['jc_date'];
				$attributes['bank'] = $input['bank'];
		        $attributes['model'] = $input['model'];
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
		}

		return $attributes;
	}

	public function jobcardSettingProcess(Request $request){

		$validation_result = $this->jobcardValidationProcess($request);
		if($validation_result['validation_result'] == TRUE){

			$saving_result = $this->savingProcess($request);

			$saving_result['validation_result'] = $validation_result['validation_result'];
			$saving_result['validation_messages'] = $validation_result['validation_messages'];

			$data['attributes'] = $this->getJobcardSettingAttributes($saving_result, $request);

		}else{

			$validation_result['ticket_number'] = '';
			$validation_result['process_status'] = FALSE;

			$data['attributes'] = $this->getJobcardSettingAttributes($validation_result, $request);
		}

		$objBank = new Bank();
		$objModel = new TerminalModel();

        $data['report_table'] = array();
        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objModel->get_models();

		return view('tmc.hardware.ticket.jobcard_setting')->with('JS', $data);
	}

	private function jobcardValidationProcess($request){

		//try{

			$front_end_message = " ";

			$input['Lot Number'] = $request->lot_number;
            $input['Box Number'] = $request->box_number;
            $input['Date'] = $request->jc_date;
			$input['Bank'] = $request->bank;
			$input['Model'] = $request->model;

			$rules['Lot Number'] = array('required', 'numeric');
            $rules['Box Number'] = array('required', 'numeric');
            $rules['Date'] = array('required', 'date');
			$rules['Bank'] = array( new ZeroValidation('Bank', $request->bank));
			$rules['Model'] = array( new ZeroValidation('Model', $request->model));

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Jobcard Setting Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Jobcard Setting Controller - Validation Function Fault';

		// 	return $process_result;
		// }

	}

	private function savingProcess($request){

		//try{

			$objJobcard = new Jobcard();

			$data['jobcard_setting'] = $this->getJobcardSettingTable($request);

			$saving_process_result = $objJobcard->saveJobcardSetting($data);

			return $saving_process_result;

		// }catch(\Exception $e){

		// 	$process_result['ticket_number'] = $request->ticket_number;
        //  $process_result['process_status'] = FALSE;
        //  $process_result['front_end_message'] = $e->getMessage();
        //  $process_result['back_end_message'] = 'Jobcard Setting Controller -> Jobcard Setting Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function getJobcardSettingTable($request){

		$jobcard_setting['lot_number'] = $request->lot_number;
        $jobcard_setting['box_number'] = $request->box_number;
        $jobcard_setting['jc_date'] = $request->jc_date;
		$jobcard_setting['bank'] = $request->bank;
		$jobcard_setting['model'] = $request->model;

		$jobcard_setting['saved_by'] = Auth::user()->name;
		$jobcard_setting['saved_on'] = date('Y-m-d G:i:s');
		$jobcard_setting['saved_ip'] = request()->ip();

		return $jobcard_setting;
	}


}
