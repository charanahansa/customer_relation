<?php

namespace App\Http\Controllers\Tmc\Backup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Backup\BackupOperation;

use App\Models\Tmc\Operation\Breakdown;
use App\Models\Tmc\Operation\NewInstallation;
use App\Models\Tmc\Operation\ReInitilization;
use App\Models\Tmc\Operation\SoftwareUpdation;
use App\Models\Tmc\Operation\TerminalReplacement;
use App\Models\Tmc\Operation\TerminalIn;
use App\Models\Tmc\Operation\TerminalOut;
use App\Models\Tmc\Operation\BaseSoftware;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BackupOperationController extends Controller {

	public function index(){

		$data['attributes'] = $this->get_backup_location_report_attributes(NULL, NULL);

		return view('tmc.backup.backup_process')->with('BLR', $data);
	}

	private function get_backup_location_report_attributes($process, $request){

		$attributes['backup_serial_numbers'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }
	}

	public function backup_operation_process(Request $request){

		$objBackupOperation = new BackupOperation();
		$backup_issue_note_no = '';

		$backup_serial_exists_result = $objBackupOperation->is_backup_serial_number($request->backup_serial_numbers);
		if($backup_serial_exists_result == TRUE){

			$is_issued_backup_serial_number_result = $objBackupOperation->is_issued_backup_serial_number($request->backup_serial_numbers);
			if($is_issued_backup_serial_number_result){

				$backup_issue_note_result = $objBackupOperation->get_backup_issue_note_number($request->backup_serial_numbers);
				foreach($backup_issue_note_result as $row){

					$backup_issue_note_no = $row->bin_no;
					echo $row->bin_no;
					echo '<br>';
				}

				$arr['update_backup_issue_note'] = $this->update_backup_issue_note($request);
				$arr['backup_issue_note_table'] = $this->backup_issue_note_table($request);
				$arr['backup_remove_note_table'] = $this->backup_remove_note_table($request, $backup_issue_note_no);
				$arr['backup_remove_note_field_service'] = $this->backup_remove_note_field_service_table($request, $backup_issue_note_no);

				echo '<pre>';
				print_r($arr['backup_issue_note_table']);
				echo '<pre>';

				echo '<hr>';

				echo '<pre>';
				print_r($arr['backup_remove_note_table']);
				echo '<pre>';

			}else{

				echo 'is_issued_backup_serial_number_result is FALSE <br>';
			}

		}else{

			echo 'backup_serial_exists_result is FALSE <br>';
		}



	}

	private function update_backup_issue_note($request){

		$sqlarr['bk_removed'] = 1;
        $sqlarr['bk_removed_refno'] = $request->referance_ticketno;
		
		return $sqlarr;
	}


	private function backup_issue_note_table($request){

        $sqlarr['bin_date'] = date('Y-m-d');
        $sqlarr['ref'] = $request->referance;
        $sqlarr['refno'] = $request->referance_ticketno;
        $sqlarr['backup_serialno'] = $request->backup_serial_numbers;
		$sqlarr['officer'] = Auth::user()->officer_id;

		if($request->referance == 'breakdown'){

			$objBreakdown  = new Breakdown();
			$result = $objBreakdown->get_breakdown_detail($request->referance_ticketno);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 'newinstall'){

			$objReInitilization  = new NewInstallation();
			$result = $objReInitilization->get_newinstall_detail($request->referance_ticketno);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 're_initilization'){

			$objReInitilization  = new ReInitilization();
			$result = $objReInitilization->get_re_initialization_detail($request->referance_ticketno);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 'software_updation'){

			$objSoftwareUpdation  = new SoftwareUpdation();
			$result = $objSoftwareUpdation->get_software_updation_detail($request->referance_ticketno);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 'terminal_replacement'){

			$objTerminalReplacement  = new TerminalReplacement();
			$result = $objTerminalReplacement->get_terminal_replacement_detail($request->referance_ticketno);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 'terminal_in'){

			$objTerminalIn  = new TerminalIn();
			$result = $objTerminalIn->get_terminal_in_detail($request->referance_ticketno, $request->backup_serial_numbers);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 'terminal_out'){

			$objTerminalOut  = new TerminalOut();
			$result = $objTerminalOut->get_terminal_in_detail($request->referance_ticketno, $request->backup_serial_numbers);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		if($request->referance == 'base_software'){

			$objBaseSoftware  = new BaseSoftware();
			$result = $objBaseSoftware->get_base_software_installation_detail($request->referance_ticketno, $request->backup_serial_numbers);
			foreach($result as $row){

				$sqlarr['bank'] = $row->bank;
				$sqlarr['tid'] = $row->tid;
				$sqlarr['model'] = $row->model;
				$sqlarr['merchant'] = $row->merchant;
				$sqlarr['original_serialno'] = '';
				$sqlarr['courier'] = 'Not';
			}
		}

		$sqlarr['is_backup'] = 1;
		$sqlarr['bk_removed'] = 0;
        $sqlarr['bk_removed_refno'] = 0;
        $sqlarr['cancel'] = 0;

		$sqlarr['saved_by'] = Auth::user()->name;
        $sqlarr['saved_on'] = date('Y-m-d G:i:s');
        $sqlarr['saved_ip'] = '';

		return $sqlarr;
	}

	private function backup_remove_note_table($request, $backup_issue_note_no){

		$objBackupOperation = new BackupOperation();

		$result = $objBackupOperation->get_backup_issue_note_detail($backup_issue_note_no);
		foreach($result as $row){

			$sqlarr['brn_date'] = date('Y-m-d');
			$sqlarr['bin_no'] = $backup_issue_note_no;
			$sqlarr['bank'] = $row->bank;
			$sqlarr['tid'] = $row->tid;
	        $sqlarr['model'] = $row->model;
			$sqlarr['merchant'] = $row->merchant;
			$sqlarr['contactno'] = '-';
			$sqlarr['contact_person'] = '-';
			$sqlarr['backup_serialno'] = $row->backup_serialno;
			$sqlarr['replaced_serialno'] = '';
			$sqlarr['original_serialno'] = '';
			$sqlarr['remark'] = '-';
			$sqlarr['officer'] = Auth::user()->officer_id;
			$sqlarr['courier'] = 'Not';
			$sqlarr['sub_status'] = 20;
			$sqlarr['status'] = 'done';
			$sqlarr['done_date_time'] = date('Y-m-d G:i:s');
			$sqlarr['cancel'] = 0; 
			$sqlarr['cancel_reason'] = "";
		}

		return $sqlarr;
	}

	private function backup_remove_note_field_service_table($request, $backup_issue_note_no){

		$objBackupOperation = new BackupOperation();

		$sqlarr = array();

		$result = $objBackupOperation->get_backup_issue_note_detail($backup_issue_note_no);
		foreach($result as $row){

			$sqlarr['tdate'] = date('Y-m-d G:i:s');
			$sqlarr['model'] = $row->model;
			$sqlarr['serialno'] = $row->replaced_serial_number;
			$sqlarr['removed_model'] = $row->model;
			$sqlarr['removed_serialno'] = $row->backup_serialno;
			$sqlarr['merchant'] = $row->merchant;
			$sqlarr['contactno'] = '';
			$sqlarr['contact_person'] = '';
			$sqlarr['refno'] = '';
			$sqlarr['referance'] = '';
			$sqlarr['remark'] = '';
			$sqlarr['sub_status'] = 20;
			$sqlarr['status'] = 'done';
			$sqlarr['email'] = (0);
			$sqlarr['email_on'] = (null);

			$sqlarr['saved_by'] = Auth::user()->name;
			$sqlarr['saved_on'] = date('Y-m-d G:i:s');
			$sqlarr['saved_ip'] = '';
		}

		return $sqlarr;
	}
    
}
