<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\BackupRemoval;

class BackupRemovalController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objBackupRemoval = new BackupRemoval();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['backup_removal_count'] = $objBackupRemoval->get_backup_removal_count($from_date, $to_date);
        $data['backup_removal_bank_count'] = $objBackupRemoval->get_bank_wise_backup_removal_count($from_date, $to_date);
        $data['backup_removal_status_count'] = $objBackupRemoval->get_status_wise_backup_removal_count($from_date, $to_date);

        return view('management.dashboards.backup_removal')->with('backup_removal', $data);
    }

    public function dashboard_backup_removal_process(Request $request){

        $objBackupRemoval = new BackupRemoval();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['backup_removal_count'] = $objBackupRemoval->get_backup_removal_count($from_date, $to_date);
        $data['backup_removal_bank_count'] = $objBackupRemoval->get_bank_wise_backup_removal_count($from_date, $to_date);
        $data['backup_removal_status_count'] = $objBackupRemoval->get_status_wise_backup_removal_count($from_date, $to_date);

        return view('management.dashboards.backup_removal')->with('backup_removal', $data);
    }

    public function get_bank_wise_status_wise_backup_removal_count(Request $request){

        $objBackupRemoval = new BackupRemoval();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objBackupRemoval->get_bank_wise_status_wise_backup_removal_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_backup_removal_count(Request $request){

        $objBackupRemoval = new BackupRemoval();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objBackupRemoval->get_status_wise_bank_wise_backup_removal_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_backup_removal_detail(Request $request){

        $objBackupRemoval = new BackupRemoval();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['backup_removal_detail'] = $objBackupRemoval->get_backup_removal_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }


}
