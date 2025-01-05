<?php

namespace App\Http\Controllers\Field_Service\Field_Service_Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Master\SubStatus;
use App\Models\Master\TerminalModel;
use App\Models\Master\Fault;
use App\Models\Master\Status;
use App\Models\FieldService\ReInitialization;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FsReInitializationInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objFault = new Fault();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();

		$data['report_table'] = array();
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
        $data['fault'] = $objFault->getFaults();
		$data['sub_status'] = $objSubStatus->getReInitializationSubStatus();
		$data['status'] = $objStatus->getBackupRemoveStatus();

		return view('fsp.field_service_inquire.re_initilization_inquire')->with('RE', $data);
	}

    public function fsReInitializationInquireProcess(Request $request){

		$input = $request->input();
		$query_part = '';

		if( $input['bank'] != "0" ){

			$query_part .= " && r.bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] != "0" ){

			$query_part .= " && ( (r.model = '". $input['model'] . "') ) ";
		}

		if( $input['officer'] != "0" ){

			$query_part .= " && r.officer = '". $input['officer'] . "' ";
		}

        if( $input['merchant'] != "" ){

			$query_part .= " && r.merchant like '%". $input['merchant'] . "%' ";
		}

		if( $input['status'] != "0" ){

			$query_part .= " && r.status = '". $input['status'] . "' ";
		}

		if( $input['sub_status'] != "0" ){

			$query_part .= " && r.sub_status = '". $input['sub_status'] . "' ";
		}

		if( $input['serial_number'] != "" ){

			$query_part .= " && r.serialno = '". $input['serial_number'] . "' ";
		}

		if( ($input['from_date'] != "") && ($input['to_date'] != "") ){

			$query_part .= " && r.tdate between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

		if( $input['tid'] != "" ){

			$query_part .= " && r.tid = '". $input['tid'] . "' ";
		}

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objFault = new Fault();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
		$objReInitialization = new ReInitialization();

		$data['report_table'] =$objReInitialization->getReInitializationInquireResult($query_part);

        if( $request->submit == 'Inquire' ){

            $data['model'] = $objModel->get_models();
            $data['bank'] = $objBank->get_bank();
            $data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
            $data['fault'] = $objFault->getFaults();
            $data['sub_status'] = $objSubStatus->getReInitializationSubStatus();
            $data['status'] = $objStatus->getBackupRemoveStatus();

            return view('fsp.field_service_inquire.re_initilization_inquire')->with('RE', $data);
        }

        if( $request->submit == 'Excell' ){

            $this->prepareExcellSheet($data['report_table']);
        }

	}
    
}
