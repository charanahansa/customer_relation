<?php

namespace App\Http\Controllers\Field_Service\Field_Service_Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Master\SubStatus;
use App\Models\Master\TerminalModel;
use App\Models\Master\Fault;
use App\Models\Master\Status;
use App\Models\FieldService\SoftwareUpdate;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FsSoftwareUpdationInquireController extends Controller {

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
		$data['sub_status'] = $objSubStatus->getSoftwareUpdateSubStatus();
		$data['status'] = $objStatus->getBackupRemoveStatus();

		return view('fsp.field_service_inquire.software_update_inquire')->with('SW', $data);
	}

    public function fsSoftwareUpdateInquireProcess(Request $request){

		$input = $request->input();
		$query_part = '';

		if( $input['bank'] != "0" ){

			$query_part .= " && su.bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] != "0" ){

			$query_part .= " && ( (sud.model = '". $input['model'] . "') ) ";
		}

		if( $input['officer'] != "0" ){

			$query_part .= " && sud.officer = '". $input['officer'] . "' ";
		}

        if( $input['merchant'] != "" ){

			$query_part .= " && sud.merchant like '%". $input['merchant'] . "%' ";
		}

		if( $input['status'] != "0" ){

			$query_part .= " && sud.status = '". $input['status'] . "' ";
		}

		if( $input['sub_status'] != "0" ){

			$query_part .= " && sud.sub_status = '". $input['sub_status'] . "' ";
		}

		if( $input['serial_number'] != "" ){

			$query_part .= " && sud.serialno = '". $input['serial_number'] . "' ";
		}

		if( ($input['from_date'] != "") && ($input['to_date'] != "") ){

			$query_part .= " && su.tdate between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

		if( $input['tid'] != "" ){

			$query_part .= " && sud.tid = '". $input['tid'] . "' ";
		}

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objFault = new Fault();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
		$objSoftwareUpdate = new SoftwareUpdate();

		$data['report_table'] =$objSoftwareUpdate->getSoftwareUpdateInquireResult($query_part);

        if( $request->submit == 'Inquire' ){

            $data['model'] = $objModel->get_models();
            $data['bank'] = $objBank->get_bank();
            $data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
            $data['fault'] = $objFault->getFaults();
            $data['sub_status'] = $objSubStatus->getBreakdownSubStatus();
            $data['status'] = $objStatus->getBackupRemoveStatus();

            return view('fsp.field_service_inquire.software_update_inquire')->with('SW', $data);
        }

        if( $request->submit == 'Excell' ){


            $this->prepareExcellSheet($data['report_table']);
        }

	}
    

    
}
