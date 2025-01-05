<?php

namespace App\Http\Controllers\Tmc\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Master\Workflow;
use App\Models\Tmc\InOut\TerminalInProcess;

class TmcBinInquireController extends Controller {

    public function index(){

        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objWorkflow = new Workflow();

		$data['report_table'] = array();
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
        $data['workflow'] = $objWorkflow->get_workflow();

		return view('tmc.inquire.tmc_bin_inquire')->with('TBI', $data);
    }

    public function tmcBinInquireProcess(Request $request){

        $input = $request->input();
		$query_part = '';

        if( $input['workflow_id'] !== "0" ){

			$query_part .= " && in_workflow_id = '". $input['workflow_id'] . "' ";
		}

		if( $input['bank'] !== "0" ){

			$query_part .= " && bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] !== "0" ){

			$query_part .= " && model = '". $input['model'] . "' ";
		}

		if( ! empty($input['serial_number']) ){

			$query_part .= " && serial_number = '". $input['serial_number'] . "' ";
		}

		if( ( ! empty($input['from_date'])) && ( ! empty($input['to_date'])) ){

			$query_part .= " && in_workflow_date between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objWorkflow = new Workflow();
        $objTerminalInProcess = new TerminalInProcess();

		$data['report_table'] = $objTerminalInProcess->getTmcBin($query_part);
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
        $data['workflow'] = $objWorkflow->get_workflow();

		return view('tmc.inquire.tmc_bin_inquire')->with('TBI', $data);
    }

}
