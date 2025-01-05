<?php

namespace App\Http\Controllers\Tmc\Backup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Http\Controllers\Vericentre\VericentreController;

use App\Models\User;
use App\Models\Tmc\Backup\BackupProcess;
use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;

use App\Rules\ZeroValidation;
use App\Rules\Tmc\BackupRemoveNote\BrnCancelValidation;

class BackupRemoveNoteController extends Controller {

	public function index(){

		$objBank = new Bank();
		$objModel = new TerminalModel();
		$objBackupProcess = new BackupProcess();

		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['sub_status'] = $objBackupProcess->get_sub_status();
		$data['status'] = $objBackupProcess->get_status();
		$data['attributes'] = $this->get_backup_remove_note_attributes(NULL, NULL);

		return view('tmc.backup.backup_remove_note')->with('BRN', $data);
	}

	private function get_backup_remove_note_attributes($process, $request){

		$objBackupProcess = new BackupProcess();

		$attributes['brn_no'] = '#Auto#';
        $attributes['brn_date'] = '';
		$attributes['bank'] = 0;
		$attributes['tid'] = '';
		$attributes['merchant'] = '';
		$attributes['backup_serialno'] = '';
        $attributes['backup_model'] = 0;
		$attributes['replaced_serialno'] = '';
        $attributes['replaced_model'] = 0;
		$attributes['remark'] = '';
		$attributes['contact_number'] = '';
		$attributes['contact_person'] = '';
		$attributes['sub_status'] = 0;
		$attributes['status'] = '';
		$attributes['done_date_time'] = '';

		$attributes['history'] = array();
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$brn_resultset = $objBackupProcess->get_backup_remove_note($process['brn_no']);
			foreach($brn_resultset as $row){

				$attributes['brn_no'] = $row->brn_no;
				$attributes['brn_date'] = $row->brn_date;
				$attributes['bank'] = $row->bank;
				$attributes['tid'] = $row->tid;
				$attributes['merchant'] = $row->merchant;
				$attributes['backup_serialno'] = $row->backup_serialno;
				$attributes['backup_model'] = $row->backup_model;
				$attributes['replaced_serialno'] = $row->replaced_serialno;
				$attributes['replaced_model'] = $row->replaced_model;
				$attributes['remark'] = $row->remark;
				$attributes['contact_number'] = $row->contact_number;
				$attributes['contact_person'] = $row->contact_person;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;

			}

			$attributes['history'] = $objBackupProcess->get_backup_remove_note_history($request->brn_no);
			$attributes['validation_messages'] = new MessageBag();

			$message = $process['front_end_message'];
			$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

			return $attributes;

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				$attributes['brn_no'] = $input['brn_no'];
				$attributes['brn_date'] = $input['brn_date'];
				$attributes['bank'] = $input['bank'];
				$attributes['tid'] = $input['tid'];
				$attributes['merchant'] = $input['merchant'];
				$attributes['backup_serialno'] = $input['backup_serialno'];
				$attributes['backup_model'] = $input['backup_model'];
				$attributes['replaced_serialno'] = $input['replaced_serialno'];
				$attributes['replaced_model'] = $input['replaced_model'];
				$attributes['remark'] = $input['remark'];
				$attributes['contact_number'] = $input['contact_number'];
				$attributes['contact_person'] = $input['contact_person'];
				$attributes['sub_status'] = $input['sub_status'];
				$attributes['status'] = $input['status'];
				$attributes['done_date_time'] = $input['done_date_time'];
			}

			$attributes['history'] = $objBackupProcess->get_backup_remove_note_history($request->brn_no);

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

			return $attributes;
		}

	}

	public function backup_remove_note_process(Request $request){

		$objBank = new Bank();
		$objModel = new TerminalModel();
		$objBackupProcess = new BackupProcess();

		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['sub_status'] = $objBackupProcess->get_sub_status();
		$data['status'] = $objBackupProcess->get_status();

		if($request->submit == 'Reset'){

			$data['attributes'] = $this->get_backup_remove_note_attributes(NULL, NULL);
		}

		if($request->submit == 'Save'){

			$validation_result = $this->validation_process($request);
			if($validation_result['validation_result'] == TRUE){

				$brn_saving_result = $this->saving_process($request);

				$brn_saving_result['validation_result'] = $validation_result['validation_result'];
				$brn_saving_result['validation_messages'] = $validation_result['validation_messages'];

				$data['attributes'] = $this->get_backup_remove_note_attributes($brn_saving_result, $request);

			}else{

				$validation_result['brn_no'] = '';
				$validation_result['process_status'] = FALSE;

				$data['attributes'] = $this->get_backup_remove_note_attributes($validation_result, $request);
			}
		}

		if($request->submit == 'Cancel'){

			$validation_result = $this->cancel_validation_process($request);
			if($validation_result['validation_result'] == TRUE){

				$brn_cancel_result = $this->cancel_process($request);

				$brn_cancel_result['validation_result'] = $validation_result['validation_result'];
				$brn_cancel_result['validation_messages'] = $validation_result['validation_messages'];

				$data['attributes'] = $this->get_backup_remove_note_attributes($brn_cancel_result, $request);

			}else{

				$validation_result['brn_no'] = '';
				$validation_result['process_status'] = FALSE;

				$data['attributes'] = $this->get_backup_remove_note_attributes($validation_result, $request);
			}
		}

		return view('tmc.backup.backup_remove_note')->with('BRN', $data);
	}

	private function validation_process($request){

		//try{

			$front_end_message = " ";

			$input['brn_date'] = $request->brn_date;
			$input['bank'] = $request->bank;
			$input['tid']= $request->tid;
            $input['merchant'] = $request->merchant;
			$input['backup_serialno'] = $request->backup_serialno;
			$input['backup_model'] = $request->backup_model;
			$input['replaced_serialno'] = $request->replaced_serialno;
			$input['replaced_model'] = $request->replaced_model;
			$input['contact_number'] = $request->contact_number;
			$input['contact_person'] = $request->contact_person;
			$input['remark']= $request->remark;
			$input['sub_status']= $request->sub_status;
			$input['done_date_time']= $request->done_date_time;

			$rules['brn_date'] = array('required', 'date');
			$rules['bank'] = array( new ZeroValidation('Bank', $request->bank));
			$rules['tid']= array('required', new ZeroValidation('Tid', $request->tid));
            $rules['merchant'] = array('max:130');
			$rules['backup_serialno'] = array('required', 'max:12');
			$rules['backup_model'] = array( new ZeroValidation('Backup Model', $request->backup_model));
			$rules['replaced_serialno'] = array('required', 'max:12');
			$rules['replaced_model'] = array( new ZeroValidation('Replaced Model', $request->replaced_model));
			$rules['contact_number'] = array('max:35');
			$rules['contact_person'] = array('max:30');
			$rules['remark'] = array('max:100');
			$rules['sub_status'] = array( new ZeroValidation('Sub Status', $request->sub_status));

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Backup Remove Note Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Backup Remove Note Controller - Validation Function Fault';

		// 	return $process_result;
		// }
	}

	private function saving_process($request){

		try{

			$objBackupProcess = new BackupProcess();

			$data['backup_remove_note'] = $this->backup_remove_note($request);
			$data['officer_allocate_note'] = $this->officer_allocate_note($request);
			$data['backup_remove_note_history'] = $this->backup_remove_note_history($request);

			$backup_removing_process_result = $objBackupProcess->save_backup_remove_note($data);

			return $backup_removing_process_result;

		}catch(\Exception $e){

			$process_result['brn_no'] = $request->brn_no;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Remove Note Controller -> Backup Remove Note Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	private function backup_remove_note($request){

		$objBackupProcess = new BackupProcess();

		$brn_array['brn_no'] = $request->brn_no;
		$brn_array['brn_date'] = $request->brn_date;
		$brn_array['bin_no'] = 0;
		$brn_array['bank'] = $request->bank;
		$brn_array['tid'] = $request->tid;
		$brn_array['merchant'] = $request->merchant;
		$brn_array['backup_serialno'] = $request->backup_serialno;
		$brn_array['backup_model'] = $request->backup_model;
		$brn_array['replaced_serialno'] = $request->replaced_serialno;
		$brn_array['replaced_model'] = $request->replaced_model;
		$brn_array['original_serialno'] = '';
		$brn_array['workflow_id'] = 0;
		$brn_array['workflow_refno'] = 0;
		$brn_array['contact_number'] = $request->contact_number;
		$brn_array['contact_person'] = $request->contact_person;
		$brn_array['remark'] = $request->remark;
		$brn_array['officer'] = 'Not';
		$brn_array['courier'] = 'Not';
		$brn_array['sub_status'] = $request->sub_status;
		$brn_array['status'] = $request->status;
		$brn_array['done_date_time'] = NULL;
		$brn_array['cancel'] = 0;
		$brn_array['cancel_reason'] = '';

		if($objBackupProcess->exists_backup_remove_note($request->brn_no)){

			$brn_array['edit_by'] = Auth::id();
			$brn_array['edit_on'] = now();

		}else{

			$brn_array['saved_by'] = Auth::id();
			$brn_array['saved_on'] = now();
		}

		return $brn_array;
	}

	private function officer_allocate_note($request){

		$objBackupProcess = new BackupProcess();
		$objUser = new User();

		$oal_array['allocate_no'] = $objBackupProcess->get_officer_allocate_number($request->brn_no);
		$oal_array['ticketno'] = $request->brn_no;
		$oal_array['tdate'] = date('Y-m-d G:i:s');;
		$oal_array['ref'] = 'backup_removal';
		$oal_array['settle'] = 0;
		$oal_array['cancel'] = 0;
		$oal_array['remark'] = '-';

		if($objBackupProcess->exists_offcier_allocate_note( $request->brn_no)){

			$oal_array['edit_by'] = $objUser->get_user_name(Auth::id());;
			$oal_array['edit_on'] =  date('Y-m-d G:i:s');
			$oal_array['edit_ip'] = '-';

		}else{

			$oal_array['saved_by'] = $objUser->get_user_name(Auth::id());;
			$oal_array['saved_on'] =  date('Y-m-d G:i:s');
			$oal_array['saved_ip'] = '-';
		}

		return $oal_array;
	}

	private function backup_remove_note_history($request){

		$objUser = new User();

		$new_array['brn_no'] = $request->brn_no;
		$new_array['date'] = $request->brn_date;
		$new_array['bank'] = $request->bank;
		$new_array['tid'] = $request->tid;
		$new_array['merchant'] = $request->merchant;

		$new_array['Backup Serialno'] = $request->backup_serialno;
		$new_array['Backup Model'] = $request->backup_model;
		$new_array['Replaced Serialno'] = $request->replaced_serialno;
		$new_array['Replaced Model'] = $request->replaced_model;

		$new_array['Contact Number'] = $request->contact_number;
		$new_array['Contact Person'] = $request->contact_person;
		$new_array['Remark'] = $request->remark;

		$new_array['Sub Status'] = $request->sub_status;
		$new_array['Status'] = $request->status;
		$new_array['Done Date Time'] = $request->done_date_time;

		$objBackupProcess = new BackupProcess();
		$result = $objBackupProcess->get_backup_remove_note($request->brn_no);
		if(count($result) >= 1){

			foreach($result as $row){

				$old_array['brn_no'] = $row->brn_no;
				$old_array['date'] = $row->brn_date;
				$old_array['bank'] = $row->bank;
				$old_array['tid'] = $row->tid;
				$old_array['merchant'] = $row->merchant;

				$old_array['Backup Serialno'] = $row->backup_serialno;
				$old_array['Backup Model'] = $row->backup_model;
				$old_array['Replaced Serialno'] = $row->replaced_serialno;
				$old_array['Replaced Model'] = $row->replaced_model;

				$old_array['Contact Number'] = $row->contact_number;
				$old_array['Contact Person'] = $row->contact_person;
				$old_array['Remark'] = $row->remark;

				$old_array['Sub Status'] = $row->sub_status;
				$old_array['Status'] = $row->status;
				$old_array['Done Date Time'] = $row->done_date_time;
			}

		}else{

			$old_array['brn_no'] = '';
			$old_array['date'] = '';
			$old_array['bank'] = '';
			$old_array['tid'] = '';
			$old_array['merchant'] = '';

			$old_array['Backup Serialno'] = '';
			$old_array['Backup Model'] = '';
			$old_array['Replaced Serialno'] = '';
			$old_array['Replaced Model'] = '';

			$old_array['Contact Number'] = '';
			$old_array['Contact Person'] = '';
			$old_array['Remark'] = '';

			$old_array['Sub Status'] = '';
			$old_array['Status'] = '';
			$old_array['Done Date Time'] = '';
		}

		$history_array = array();
		$icount = 1;
		foreach($new_array as $key => $value) {

        	if($new_array[$key]==$old_array[$key]){

            }else{

                $tmp_array['ticketno'] = $request->brn_no;
                $tmp_array['userid'] = $objUser->get_user_name(Auth::id());
                $tmp_array['tdatetime'] = date('Y-m-d G:i:s');
                $tmp_array['field_name'] = $key;
                $tmp_array['old_value'] = $old_array[$key];
                $tmp_array['new_value'] = $new_array[$key];

				$history_array[$icount] = $tmp_array;
				$icount++;
            }
        }

		return $history_array;
	}

	private function cancel_validation_process($request){

		//try{

			$front_end_message = '';

			$input['brn_no'] = $request->brn_no;

			$rules['brn_no'] = array('required', new BrnCancelValidation());

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
        //     $process_result['back_end_message'] =  'Backup Remove Note Controller - Cancel Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

		// 	return $process_result;
		// }

	}

	private function cancel_process($request){

		try{

			$cancel_array['brn_no'] = $request->brn_no;
			$cancel_array['cancel'] = 1;
			$cancel_array['cancel_reason'] = $request->cancel_reason;
			$cancel_array['cancel_by'] = Auth::id();
			$cancel_array['cancel_on'] = now();

			$objBackupProcess = new BackupProcess();
			$backup_removing_process_result = $objBackupProcess->cancel_backup_remove_note($cancel_array);

			return $backup_removing_process_result;

		}catch(\Exception $e){

			$process_result['brn_no'] = $request->brn_no;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Remove Note Controller -> Backup Remove Note Cancel Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	public function get_merchant_detail(Request $request){

		$merchant_detail = 'AAAA, BBBB, CCCC';

		echo $merchant_detail;
	}


}
