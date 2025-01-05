<?php

namespace App\Http\Controllers\Tmc\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Tmc\Courier\CourierProcess;
use App\Models\Tmc\InOut\TerminalOutProcess;

class TerminalOutNoteInquireController extends Controller {

	public function index(){

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
		$objCourier = new CourierProcess();
		$objTerminalOutProcess = new TerminalOutProcess();

		$data['report_table'] = array();
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['source'] = $objTerminalOutProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');
	
		return view('tmc.inquire.terminal_out_note_inquire')->with('TOI', $data);
	}

	public function terminal_out_note_inquire_process(Request $request){

		$input = $request->input();
		$query_part = '';

		if( $input['bank'] !== "0" ){

			$query_part .= " && top.bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] !== "0" ){

			$query_part .= " && tod.model = '". $input['model'] . "' ";
		}

		if( $input['source'] !== "0" ){

			$query_part .= " && top.source = '". $input['source'] . "' ";
		}

		if( $input['type'] !== "0" ){

			$query_part .= " && top.type = '". $input['type'] . "' ";
		}

		if( $input['officer'] !== "0" ){

			$query_part .= " && top.officer = '". $input['officer'] . "' ";
		}

		if( $input['courier'] !== "Not" ){

			$query_part .= " && top.courier = '". $input['courier'] . "' ";
		}

		if( ! empty($input['serial_number']) ){

			$query_part .= " && tod.serialno = '". $input['serial_number'] . "' ";
		}

		if( ! empty($input['pod_number']) ){

			$query_part .= " && top.pod_no = '". $input['pod_number'] . "' ";
		}

		if( ( ! empty($input['from_date'])) && ( ! empty($input['to_date'])) ){

			$query_part .= " && top.tdate between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
		$objCourier = new CourierProcess();
		$objTerminalOutProcess = new TerminalOutProcess();

		$data['report_table'] = $objTerminalOutProcess->getTerminalOutNoteInquireResult($query_part);
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['source'] = $objTerminalOutProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');
	
		return view('tmc.inquire.terminal_out_note_inquire')->with('TOI', $data);
	}

	
    
}
