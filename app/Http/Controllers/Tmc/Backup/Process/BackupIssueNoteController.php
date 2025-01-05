<?php

namespace App\Http\Controllers\Tmc\Backup\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class BackupIssueNoteController extends Controller {

    public function genarate_backup_issue_note_array($row){

        $bin['bin_no'] = '#Auto#';
        $bin['bin_date'] = $row->saved_on;
        $bin['bank'] = $row->bank;
        $bin['tid'] = $row->tid;
        $bin['model'] = $row->model;
        $bin['merchant'] = $row->merchant;
        $bin['backup_serialno'] = $row->e_out_serialno;
        $bin['original_serialno'] = $row->e_in_serialno;
        $bin['officer'] = $row->officer;
        $bin['courier'] = $row->courier;
        $bin['workflow_id'] = $row->workflow_id;
        $bin['workflow_ref_no'] = $row->ticketno;
        $bin['remark'] = '';
        $bin['removed'] = 0;
        $bin['brn_id'] = 0;
        $bin['cancel'] = 0;
        $bin['cancel_reason'] = '';
        $bin['saved_by'] = $row->saved_by;
        $bin['saved_on'] = Now();
        $bin['saved_ip'] = "-";

        return $bin;
    }

    public function update_backup_issue_note($row){

        $bin_update['removed'] = 1;
        $bin_update['brn_id'] = 0;
        $bin_update['edit_by'] = $row->saved_by;
        $bin_update['edit_on'] = Now();
        $bin_update['edit_ip'] = "-";

        return $bin_update;
    }
    
}
