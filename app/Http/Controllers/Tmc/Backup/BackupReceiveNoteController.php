<?php

namespace App\Http\Controllers\Tmc\Backup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\TerminalModel;
use App\Models\Tmc\Backup\BackupProcess;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Rules\ZeroValidation;
use App\Rules\Tmc\Backup\CancelValidation;
use App\Rules\Tmc\Backup\ConfirmValidation;

class BackupReceiveNoteController extends Controller {

	public function index(){

		$objModel = new TerminalModel();
		$objBackupProcess = new BackupProcess();

		$data['model'] = $objModel->get_models();
		$data['attributes'] = $this->get_backup_receive_note_attributes(NULL, NULL);

		return view('tmc.backup.note.backup_receive_note')->with('BRN', $data);
	}

	private function get_backup_receive_note_attributes($process, $request){

		$attributes['brn_id'] = '#Auto#';
        $attributes['date'] = '';
        $attributes['model'] = 0;
		$attributes['delivery_no'] = '';
		$attributes['remark'] = '';
		$attributes['terminal_serial'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		$objBackupProcess = new BackupProcess();
		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$backup_process_result = $objBackupProcess->get_backup_receive_note($process['brn_id']);
			$backup_process_detail_result = $objBackupProcess->get_backup_receive_note_detail($process['brn_id']);

			foreach($backup_process_result as $row){

				$attributes['brn_id'] = $row->brn_id;
		        $attributes['date'] = $row->brn_date;
		        $attributes['model'] = $row->model;
				$attributes['delivery_no'] = $row->delivery_no;
				$attributes['remark'] = $row->remark;
			}

			foreach($backup_process_detail_result as $row){

				$attributes['terminal_serial'] .= $row->serial_no . chr(13);
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				$attributes['brn_id'] = $input['brn_id'];
		        $attributes['date'] = $input['brn_date'];
		        $attributes['model'] = $input['model'];
				$attributes['delivery_no'] = $input['delivery_number'];
				$attributes['remark'] = $input['remark'];

				$attributes['terminal_serial'] .= $input['terminal_serial'];
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
		}


		return $attributes;
	}

	public function backup_receive_note_process(Request $request){

		$objBackupProcess = new BackupProcess();

		$backup_receive_note_process_result = '';

		if($request->submit == 'Save'){

			$backup_receive_note_process_result = $this->backup_receive_note_saving_process($request);
		}

		if($request->submit == 'Confirm'){

			$backup_receive_note_process_result = $this->backup_receive_note_saving_process($request);
			if($backup_receive_note_process_result['process_status'] == TRUE){

				$backup_receive_note_process_result = $this->backup_receive_note_confirming_process($request);
			}
		}

		if($request->submit == 'Cancel'){

			$backup_receive_note_process_result = $this->backup_receive_note_cancelling_process($request);
		}

		if($request->submit == 'Reset'){

			$backup_receive_note_process_result = NULL;
			$request = NULL;
		}

		$data['model'] = $objBackupProcess->get_models();
		$data['attributes'] = $this->get_backup_receive_note_attributes($backup_receive_note_process_result, $request);

		return view('tmc.backup.backup_receive_note')->with('BRN', $data);
	}

	private function backup_receive_note_saving_process($request){

		$brn_validation_process_result = $this->brn_validation_process($request);

		if($brn_validation_process_result['validation_result'] == TRUE){

			$saving_process_result = $this->brn_saving_process($request);

			$saving_process_result['validation_result'] = $brn_validation_process_result['validation_result'];
			$saving_process_result['validation_messages'] = $brn_validation_process_result['validation_messages'];

			return $saving_process_result;

		}else{

			$brn_validation_process_result['brn_id'] = $request->brn_id;
			$brn_validation_process_result['process_status'] = FALSE;

			return $brn_validation_process_result;
		}

	}

	private function brn_validation_process($request){

		try{

			$front_end_message = '';

			/* ----------------------------------------------  Inputs ----------------------------------------------- */
			$input['brn_id'] = $request->brn_id;
			$input['brn_date'] = $request->brn_date;
            $input['model'] = $request->model;
			$input['delivery_no'] = $request->delivery_number;
            $input['remark'] = $request->remark;

			/* ----------------------------------------------  Rules ----------------------------------------------- */
			$rules['brn_id'] = array('required', new CancelValidation(), new ConfirmValidation());
			$rules['brn_date'] = array('required', 'date');    
            $rules['model'] = array('required', new ZeroValidation('Model', $request->model));     
			$input['delivery_no'] = array('max:10');      
            $rules['remark'] = array('max:200');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Backup Receive Note Controller - Validation Process ';

            return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Backup Receive Note Controller - Validation Function Fault';

			return $process_result;
		}
	}

	private function brn_saving_process($request){

		try{

			$objBackupProcess = new BackupProcess();

			$data['backup_receive_note'] = $this->backup_receive_note_table($request);
			$data['backup_receive_note_detail'] = $this->backup_receive_note_detail_table($request);

			$backup_saving_process_result = $objBackupProcess->save_backup_receive_note($data);

			return $backup_saving_process_result;

		}catch(\Exception $e){

			$process_result['brn_id'] = $request->brn_id;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Receive Note Controller -> Backup Receive Note Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	private function backup_receive_note_table($request){

		$arr['brn_id'] = $request->brn_id;
		$arr['brn_date'] = $request->brn_date;
		$arr['model'] = $request->model;
		$arr['delivery_no'] = $request->delivery_number;
		$arr['remark'] = $request->remark;
		$arr['cancel'] = 0;
		$arr['confirm'] = 0;
		$arr['saved_by'] = Auth::id();
		$arr['saved_on'] = now();

		return $arr;
	}

	private function backup_receive_note_detail_table($request){

		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$arr[$icount]['serial_no'] = rtrim(ltrim($terminal));
			$icount++;
		}

		return $arr;
	}

	private function backup_receive_note_confirming_process($request){

		$brn_confirm_validation_process_result = $this->brn_confirm_validation_process($request);

		if($brn_confirm_validation_process_result['validation_result'] == TRUE){

			$confirm_process_result = $this->brn_confirm_process($request);

			$confirm_process_result['validation_result'] = $brn_confirm_validation_process_result['validation_result'];
			$confirm_process_result['validation_messages'] = $brn_confirm_validation_process_result['validation_messages'];

			return $confirm_process_result;

		}else{

			$brn_confirm_validation_process_result['brn_id'] = $request->brn_id;
			$brn_confirm_validation_process_result['process_status'] = FALSE;

			return $brn_confirm_validation_process_result;
		}
	}

	private function brn_confirm_validation_process($request){

		try{

			$front_end_message = '';

			$input['brn_id'] = $request->brn_id;

			$rules['brn_id'] = array('required', new CancelValidation(), new ConfirmValidation());

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Backup Receive Note Controller - Cancel Validation Process ';

            return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Backup Receive Note Controller - Cancel Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

			return $process_result;
		}
	}

	private function brn_confirm_process($request){

		try{

			$objBackupProcess = new BackupProcess();

			$data['confirm_row'] = $this->backup_receive_note_confirm_row($request);
			$data['terminal_log'] = $this->terminal_log_table($request);

			$backup_confirm_process_result = $objBackupProcess->confirm_backup_receive_note($data, $request->brn_id);

			return $backup_confirm_process_result;

		}catch(\Exception $e){

			$process_result['brn_id'] = $request->brn_id;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Receive Note Controller -> Backup Receive Note Confirm Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	private function backup_receive_note_confirm_row($request){

		$arr['brn_id'] = $request->brn_id;
		$arr['confirm'] = 1;
		$arr['confirm_by'] = Auth::id();
		$arr['confirm_on'] = now();

		return $arr;
	}

	private function terminal_log_table($request){

		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$arr[$icount]['serialno'] = rtrim(ltrim($terminal));
			$arr[$icount]['model'] = $request->model;
			$arr[$icount]['bank'] = 'EPIC';
			$arr[$icount]['ownership'] = 'EPIC';

			$icount++;
		}

		return $arr;
	}

	private function backup_receive_note_cancelling_process($request){

		$brn_cancel_validation_process_result = $this->brn_cancel_validation_process($request);

		if($brn_cancel_validation_process_result['validation_result'] == TRUE){

			$cancel_process_result = $this->brn_cancel_process($request);

			$cancel_process_result['validation_result'] = $brn_cancel_validation_process_result['validation_result'];
			$cancel_process_result['validation_messages'] = $brn_cancel_validation_process_result['validation_messages'];

			return $cancel_process_result;

		}else{

			$brn_cancel_validation_process_result['brn_id'] = $request->brn_id;
			$brn_cancel_validation_process_result['process_status'] = FALSE;

			return $brn_cancel_validation_process_result;
		}
	}

	private function brn_cancel_validation_process($request){

		//try{

			$front_end_message = '';

			$input['brn_id'] = $request->brn_id;

			$rules['brn_id'] = array('required', new CancelValidation(), new ConfirmValidation());

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Backup Receive Note Controller - Cancel Validation Process ';

            return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Backup Receive Note Controller - Cancel Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

		// 	return $process_result;
		// }
	}

	private function brn_cancel_process($request){

		//try{

			$objBackupProcess = new BackupProcess();

			$data['cancel_row'] = $this->backup_receive_note_cancel_row($request);

			$backup_cancel_process_result = $objBackupProcess->cancel_backup_receive_note($data, $request->brn_id);

			return $backup_cancel_process_result;

		// }catch(\Exception $e){

		// 	$process_result['brn_id'] = $request->brn_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Backup Receive Note Controller -> Backup Receive Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function backup_receive_note_cancel_row($request){

		$arr['brn_id'] = $request->brn_id;
		$arr['cancel'] = 1;
		$arr['cancel_by'] = Auth::id();
		$arr['cancel_on'] = now();

		return $arr;
	}

    
}
