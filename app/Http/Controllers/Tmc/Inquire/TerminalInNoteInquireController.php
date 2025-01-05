<?php

namespace App\Http\Controllers\Tmc\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Tmc\Courier\CourierProcess;
use App\Models\Tmc\InOut\TerminalInProcess;

class TerminalInNoteInquireController extends Controller {

	public function index(){

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
		$objCourier = new CourierProcess();
		$objTerminalInProcess = new TerminalInProcess();

		$data['report_table'] = array();
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['source'] = $objTerminalInProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');
	
		return view('tmc.inquire.terminal_in_note_inquire')->with('TII', $data);
	}
    
	public function terminal_in_note_inquire_process(Request $request){

		$input = $request->input();
		$query_part = '';

		if( $input['bank'] !== "0" ){

			$query_part .= " && tip.bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] !== "0" ){

			$query_part .= " && tid.model = '". $input['model'] . "' ";
		}

		if( $input['receive_type'] !== "0" ){

			$query_part .= " && tip.receive_type = '". $input['receive_type'] . "' ";
		}

		if( $input['officer'] !== "0" ){

			$query_part .= " && tip.officer = '". $input['officer'] . "' ";
		}

		if( $input['courier'] !== "Not" ){

			$query_part .= " && tip.courier = '". $input['courier'] . "' ";
		}

		if( ! empty($input['serial_number']) ){

			$query_part .= " && tid.serialno = '". $input['serial_number'] . "' ";
		}

		if( ! empty($input['pod_number']) ){

			$query_part .= " && tip.pod_no = '". $input['pod_number'] . "' ";
		}

		if( ( ! empty($input['from_date'])) && ( ! empty($input['to_date'])) ){

			$query_part .= " && tip.tdate between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
		$objCourier = new CourierProcess();
		$objTerminalInProcess = new TerminalInProcess();

		$data['report_table'] = $objTerminalInProcess->getTerminalInNoteInquireResult($query_part);
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['source'] = $objTerminalInProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');
	
		return view('tmc.inquire.terminal_in_note_inquire')->with('TII', $data);
	}
}
