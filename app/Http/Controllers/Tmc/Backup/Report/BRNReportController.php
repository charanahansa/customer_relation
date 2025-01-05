<?php

namespace App\Http\Controllers\Tmc\Backup\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Master\Status;
use App\Models\Master\SubStatus;

class BRNReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
		$objModel = new TerminalModel();
		$objUser = new User();
		$objSubStatus = new SubStatus();
		$objStatus = new Status();

		$data['bank'] = $objBank->get_bank();
		$data['model'] = $objModel->get_models();
		$data['officers'] = $objUser->getActiveFieldOfficers();
		$data['sub_status'] = $objSubStatus->getAllBackupRemoveSubStatus();
		$data['status'] = $objStatus->getBackupRemoveStatus();

        return view('tmc.backup.report.backup_remove_note_report')->with('BR', $data);
    }

}
