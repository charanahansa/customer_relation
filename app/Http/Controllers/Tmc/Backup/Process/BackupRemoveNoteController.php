<?php

namespace App\Http\Controllers\Tmc\Backup\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Backup\BackupProcess;

class BackupRemoveNoteController extends Controller {

    public function genarate_backup_remove_note($row, $flag_id){

        $objBackupProcess = new BackupProcess();

        $brn['brn_no'] = 0;
        $brn['brn_date'] = $row->saved_on;
        $brn['bin_no'] = 0;
        $brn['bank'] = $row->bank;
        $brn['tid'] = $row->tid;
        $brn['model'] = $row->model;
        $brn['merchant'] = $row->merchant;
        $brn['contactno'] = $row->contact_no;
        $brn['contact_person'] = $row->contact_person;

        if( $flag_id == 1){

            $brn['backup_serialno'] =  $row->e_in_serialno;
            $brn['replaced_serialno'] =  $row->e_out_serialno;
        }

        if( $flag_id == 2 ){

            $brn['backup_serialno'] =  $row->e_out_serialno;
            $brn['replaced_serialno'] =  $row->e_in_serialno;
        }

        $brn['original_serialno'] = NULL;
        $brn['remark'] = "";
        $brn['officer'] = $row->officer;
        $brn['courier'] = $row->courier;
        $brn['workflow_id'] = $row->workflow_id;
        $brn['workflow_refno'] = $row->ticketno;
        $brn['sub_status'] = 20;
        $brn['status'] = 'done';
        $brn['done_date_time'] = $row->saved_on;
        $brn['saved_by'] = $row->saved_by;
        $brn['saved_on'] = $row->saved_on;
        $brn['saved_ip'] = "";
        $brn['edit_by'] = NULL;
        $brn['edit_on'] = NULL;
        $brn['edit_ip'] = NULL;
        $brn['cancel'] = 0;
        $brn['cancel_reason'] = '';
        $brn['cancel_on'] = NULL;
        $brn['cancel_ip'] = NULL;
        $brn['cancel_by'] = NULL;

        return $brn;
    }

    public function genarate_backup_remove_note_fs_view($row, $flag_id){

        $objBackupProcess = new BackupProcess();

        $brn_fs['ticketno'] = 0;
		$brn_fs['tdate'] = $row->saved_on;

        $brn_fs['model'] = $row->model;
        $brn_fs['serialno'] = $row->e_out_serialno;

        if( $flag_id == 1){

            $brn_fs['removed_model'] = $row->model;
		    $brn_fs['removed_serialno'] = $row->e_in_serialno;
        }

        if( $flag_id == 2 ){

            $brn_fs['removed_model'] = $row->model;
		    $brn_fs['removed_serialno'] = $row->e_out_serialno;
        }
		
		$brn_fs['merchant'] = $row->merchant;
		$brn_fs['contactno'] = $row->contact_no;
		$brn_fs['contact_person'] = $row->contact_person;
        $brn_fs['workflow_id'] = $row->workflow_id;
		$brn_fs['workflow_ref_no'] = $row->ticketno;
		$brn_fs['remark'] = '';
		$brn_fs['sub_status'] = 20;
		$brn_fs['status'] = 'done';
        $brn_fs['done_date_time'] = $row->saved_on;
		$brn_fs['email'] = 0;
		$brn_fs['email_on'] = NULL;
        $brn_fs['saved_by'] = $row->saved_by;
        $brn_fs['saved_on'] = $row->saved_on;
        $brn_fs['saved_ip'] = "";
        $brn_fs['edit_by'] = NULL;
        $brn_fs['edit_on'] = NULL;
        $brn_fs['edit_ip'] = NULL;

        return $brn_fs;
    }
    
}
