<?php

namespace App\Http\Controllers\Tmc\Hardware\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Jobcard\Jobcard;

use App\Rules\ZeroValidation;
use App\Rules\Tmc\Jobcard\JobcardCancelValidation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class JobcardCancelController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $data['attributes'] = $this->getJobcardCancelAttributes(NULL, NULL);
        $data['list'] = array();

        return view('tmc.hardware.ticket.jobcard_cancellation')->with('JC', $data);
    }

    private function  getJobcardCancelAttributes($process, $request){

		$attributes['jobcard_number'] = '';
		$attributes['cancel_reason'] = '';

        $attributes['process_message'] = '';
        $attributes['validation_messages'] = new MessageBag();

        if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if( ! is_null($request) ){

            $input = $request->input();
            if(is_null($input) == FALSE){

                $attributes['jobcard_number'] = $input['jobcard_number'];
				$attributes['cancel_reason'] = $input['cancel_reason'];
            }

			if(  $process['process_status'] == TRUE ){

				if( $request->submit == 'Cancel'){

					$message = 'Cancell process is success.';
	            	$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';
				}

			}else{

				$attributes['validation_messages'] = $process['validation_messages'];

				$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            	$attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
			}

            return $attributes;
        }

    }

    public function jobcardCancelProcess(Request $request){

        $objJobcard = new Jobcard();

        if( $request->submit == 'Display'){

            $data['list'] = $objJobcard->getJobcardCancellingResult($request->jobcard_number);

			$jobcard_display_result['jobcard_number'] = $request->jobcard_number;
			$jobcard_display_result['process_status'] = TRUE;
			$jobcard_display_result['validation_result'] = TRUE;
			$jobcard_display_result['validation_messages'] = new MessageBag();

            $data['attributes'] = $this->getJobcardCancelAttributes($jobcard_display_result, $request);

            return view('tmc.hardware.ticket.jobcard_cancellation')->with('JC', $data);
        }

		if( $request->submit == 'Cancel'){

			$validation_result = $this->validationProcess($request);
			if($validation_result['validation_result'] ){

				$data['jobcard'] = $this->getJobcardCancellationDetail($request);
				$data['terminal_in_note'] = $this->getTerminalInNoteCancellationDetail($request);

				$jobcard_cancel_result = $objJobcard->cancelJobcard($data);

				$jobcard_cancel_result['jobcard_number'] = $request->jobcard_number;
				$jobcard_cancel_result['validation_result'] = $validation_result['validation_result'];
				$jobcard_cancel_result['validation_messages'] = $validation_result['validation_messages'];

				$data['attributes'] = $this->getJobcardCancelAttributes($jobcard_cancel_result, $request);

			}else{

				$validation_result['process_status'] = FALSE;
				$validation_result['jobcard_number'] = $request->jobcard_number;

				$data['attributes'] = $this->getJobcardCancelAttributes($validation_result, $request);
			}

			$data['list'] = $objJobcard->getJobcardCancellingResult($request->jobcard_number);


            return view('tmc.hardware.ticket.jobcard_cancellation')->with('JC', $data);
        }


    }

	private function validationProcess($request){

        //try{

			$front_end_message = " ";

			$input['Jobcard Number'] = $request->jobcard_number;
            $input['Cancel Reason'] = $request->cancel_reason;

			$rules['Jobcard Number'] = array('required', 'max:11', new JobcardCancelValidation());
            $rules['Cancel Reason'] = array('required', 'max:100');

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Jobcard Cancellation Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Jobcard Cancellation Controller - Validation Function Fault';

		// 	return $process_result;
		// }

    }

	private function getJobcardCancellationDetail(Request $request){

		$data['jobcard_number'] =  $request->jobcard_number;
		$data['status'] =  'Cancel';
		$data['cancel'] = 1;
		$data['cancel_reason'] = $request->cancel_reason;
		$data['cancel_by'] = Auth::user()->name;
		$data['cancel_on'] = date('Y-m-d G:i:s');
		$data['cancel_ip'] = Request()->ip();

		return $data;
	}

	private function getTerminalInNoteCancellationDetail(Request $request){

		$data['jobcard_number'] =  $request->jobcard_number;
		$data['status'] =  'Cancel';
		$data['confirm'] = 0;
		$data['cancel'] = 1;
		$data['cancel_reason'] = $request->cancel_reason;
		$data['cancel_by'] = Auth::user()->name;
		$data['cancel_on'] = date('Y-m-d G:i:s');
		$data['cancel_ip'] = Request()->ip();
		$data['confirm_by'] = NULL;
		$data['confirm_on'] = NULL;
		$data['confirm_ip'] = NULL;

		return $data;
	}


}
