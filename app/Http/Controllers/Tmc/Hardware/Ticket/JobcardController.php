<?php

namespace App\Http\Controllers\Tmc\Hardware\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Jobcard\Jobcard;
use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;

use App\Rules\ZeroValidation;
use App\Rules\Tmc\Jobcard\JobcardCancelValidation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class JobcardController extends Controller {

	public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objBank = new Bank();
		$objModel = new TerminalModel();

        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objModel->get_models();
        $data['attributes'] = $this->getJobcardAttributes(NULL, NULL);

        return view('tmc.hardware.ticket.jobcard')->with('JC', $data);
    }

	private function  getJobcardAttributes($process, $request){

		$attributes['ticket_number'] = '#Auto#';
		$attributes['jobcard_number'] = '#Auto#';
		$attributes['ticket_date'] = '';
		$attributes['lot_number'] = '';
        $attributes['box_number'] = '';
		$attributes['bank'] = '0';
		$attributes['serial_number'] = '';
        $attributes['model'] = '0';
		$attributes['remark'] = '';

        $attributes['process_message'] = '';
        $attributes['validation_messages'] = new MessageBag();

		$objJobcard = new Jobcard();
		$jobcard_setting_result = $objJobcard->getJobcardSetting();
		foreach($jobcard_setting_result as $row){

			$attributes['ticket_date'] = date('Y/m/d');
            $attributes['ticket_date'] = $row->jc_date;
			$attributes['lot_number'] = $row->lot_number;
            $attributes['box_number'] = $row->box_number;
			$attributes['bank'] = $row->bank;
	        $attributes['model'] = $row->model;
		}

        if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$terminal_in_note_result = $objJobcard->getTerminalInNote($process['ticket_number']);
			$terminal_in_note_detail_result = $objJobcard->getTerminalInNoteDetail($process['ticket_number']);

			foreach($terminal_in_note_result as $row){

				$attributes['ticket_number'] = $row->ticketno;
				$attributes['jobcard_number'] = $row->jobcard_no;
				$attributes['ticket_date'] = $row->tdate;
				$attributes['lot_number'] = $row->lot_number;
				$attributes['bank'] = $row->bank;
				$attributes['remark'] = $row->remark;
			}

			foreach($terminal_in_note_detail_result as $row){

				$attributes['serial_number'] = $row->serialno;
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

				$attributes['ticket_number'] = $input['ticket_number'];
				$attributes['jobcard_number'] = $input['jobcard_number'];
				$attributes['ticket_date'] = $input['ticket_date'];
				$attributes['lot_number'] = $input['lot_number'];
                $attributes['box_number'] = $input['box_number'];
				$attributes['bank'] = $input['bank'];
				$attributes['serial_number'] = $input['serial_number'];
		        $attributes['model'] = $input['model'];
				$attributes['remark'] = $input['remark'];

			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
		}

		return $attributes;
	}

	public function jobcardProcess(Request $request){

		$objBank = new Bank();
		$objModel = new TerminalModel();

        $data['report_table'] = array();
        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objModel->get_models();

		if($request->submit == 'Reset'){

			$data['attributes'] = $this->getJobcardAttributes(NULL, NULL);
		}

		if($request->submit == 'Save'){

			$validation_result = $this->jobcardValidationProcess($request);
			if($validation_result['validation_result'] == TRUE){

				$saving_result = $this->savingProcess($request);

				$saving_result['validation_result'] = $validation_result['validation_result'];
				$saving_result['validation_messages'] = $validation_result['validation_messages'];

				$data['attributes'] = $this->getJobcardAttributes($saving_result, $request);

			}else{

				$validation_result['ticket_number'] = '';
				$validation_result['process_status'] = FALSE;

				$data['attributes'] = $this->getJobcardAttributes($validation_result, $request);
			}
		}

		return view('tmc.hardware.ticket.jobcard')->with('JC', $data);
	}

	private function jobcardValidationProcess($request){

		//try{

			$front_end_message = " ";

			$input['Date'] = $request->ticket_date;
			$input['Lot Number'] = $request->lot_number;
            $input['Box Number'] = $request->box_number;
			$input['Bank'] = $request->bank;
			$input['Serial Number'] = $request->serial_number;
			$input['Model'] = $request->model;
			$input['remark']= $request->remark;

			$rules['Date'] = array('required', 'date');
			$rules['Lot Number'] = array('required', 'numeric');
            $rules['Box Number'] = array('required', 'numeric');
			$rules['Bank'] = array( new ZeroValidation('Bank', $request->bank));
			$rules['Serial Number'] = array('required', 'max:12');
			$rules['Model'] = array( new ZeroValidation('Backup Model', $request->model));
			$rules['remark'] = array('max:300');

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Jobcard Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Jobcard Controller - Validation Function Fault';

		// 	return $process_result;
		// }

	}

	private function savingProcess($request){

		//try{

			$objJobcard = new Jobcard();

			$data['terminal_in_note'] = $this->getTerminalInNoteTable($request);
			$data['terminal_in_note_detail'] = $this->getTerminalInNoteDetailTable($request);
			$data['jobcard'] = $this->getJobcardTable($request);
			$data['jobcard_detail'] = $this->getJobcardDetailTable($request);

			$saving_process_result = $objJobcard->saveTerminalInNoteAndJobcard($data);

			return $saving_process_result;

		// }catch(\Exception $e){

		// 	$process_result['ticket_number'] = $request->ticket_number;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Jobcard Controller -> Jobcard Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function getTerminalInNoteTable($request){

		$tin['ticketno'] = $request->ticket_number;
		$tin['tdate'] = $request->ticket_date;
		$tin['bank'] = $request->bank;
		$tin['jobcard_no'] = $request->jobcard_number;
		$tin['lot_number'] = $request->lot_number;
        $tin['box_number'] = $request->box_number;
		$tin['fault'] = 'fault';
		$tin['receive_type'] = 'terminal';
		$tin['officer'] = 'CC';
		$tin['refno'] = '';
		$tin['referance'] = 'breakdown';
		$tin['podno'] = '';
		$tin['return_podno'] = '';

		if( $request->remark == '' ){

			$tin['remark'] =  ' ';
		}else{

			$tin['remark'] =  $request->remark;
		}

		$tin['confirm'] = 1;
		$tin['cancel'] = 0;
		$tin['cancel_reason'] = "";

		if( $request->ticket_number == '#Auto#' ){

			$tin['saved_by'] = Auth::user()->name;
			$tin['saved_on'] = date('Y-m-d G:i:s');
			$tin['saved_ip'] = request()->ip();

		}else{

			$tin['edit_by'] = Auth::user()->name;
			$tin['edit_on'] = date('Y-m-d G:i:s');
			$tin['edit_ip'] = request()->ip();
		}

		$tin['confirm_by'] = Auth::user()->name;
		$tin['confirm_on'] = date('Y-m-d G:i:s');
		$tin['confirm_ip'] = request()->ip();

		return $tin;
	}

	private function getTerminalInNoteDetailTable($request){

		$tin_detail['ticketno'] = $request->ticket_number;
		$tin_detail['ono'] = 1;
		$tin_detail['serialno'] = $request->serial_number;
		$tin_detail['model'] = $request->model;
		$tin_detail['printer_serial'] = '-';
		$tin_detail['item_id'] = '0';
		$tin_detail['item_description'] = '-';

		return $tin_detail;
	}

	private function getJobcardTable($request){

		$jobcard['jobcard_no'] = $request->jobcard_number;
		$jobcard['jc_date'] = $request->ticket_date;
		$jobcard['lot_number'] = $request->lot_number;
        $jobcard['box_number'] = $request->box_number;
		$jobcard['type'] = 1;
		$jobcard['bank'] = $request->bank;
		$jobcard['serialno'] = $request->serial_number;
		$jobcard['model'] = $request->model;
		$jobcard['tid'] = '';
		$jobcard['merchant'] = '';
		$jobcard['received_from'] = 'CC';
		$jobcard['remark'] = $request->remark;
		$jobcard['refno'] = '';
		$jobcard['referance'] = 'breakdown';

		$jobcard['saved_by'] = Auth::user()->name;
		$jobcard['saved_on'] = date('Y-m-d G:i:s');
		$jobcard['saved_ip'] = request()->ip();

		return $jobcard;
	}

	private function getJobcardDetailTable($request){

		$jobcard_detail['jobcard_no'] = $request->jobcard_number;
		$jobcard_detail['ono'] = 1;
		$jobcard_detail['fault_no'] = 87;
		$jobcard_detail['fault_description'] = 'For Investigation';

		return $jobcard_detail;
	}

	public function jobcardPrintDocument(Request $request){

		$objJobcard = new Jobcard();
		$terminal_in_note_result = $objJobcard->getTerminalInNote($request->ticket_no);

		foreach($terminal_in_note_result as $row){

			$data['ticket_number'] = $row->ticketno;
			$data['jobcard_number'] = $row->jobcard_no;
			$data['ticket_date'] = $row->tdate;
			$data['bank'] = $row->bank;
			$data['merchant'] = ' - ';
            $data['lot'] = 1;
            $data['lot_number'] = $row->lot_number;
            $data['box_number'] = $row->box_number;
			$data['fault'] = 'For Investigation';
			$data['officer'] = 'Card Center';
		}

		$terminal_in_note_detail_result = $objJobcard->getTerminalInNoteDetail($request->ticket_no);
		foreach($terminal_in_note_detail_result as $row){

			$data['serial_number'] = $row->serialno;
		}

		return view('tmc.hardware.ticket.barcode')->with('JPD', $data);

	}

}
