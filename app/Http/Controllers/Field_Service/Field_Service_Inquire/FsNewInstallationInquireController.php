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
use App\Models\FieldService\NewInstallation;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FsNewInstallationInquireController extends Controller {

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
		$data['sub_status'] = $objSubStatus->getNewInstallationSubStatus();
		$data['status'] = $objStatus->getBackupRemoveStatus();

		return view('fsp.field_service_inquire.newinstall_inquire')->with('BR', $data);
	}

    public function fsNewInstallationInquireProcess(Request $request){

		$input = $request->input();
		$query_part = '';

		if( $input['bank'] != "0" ){

			$query_part .= " && n.bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] != "0" ){

			$query_part .= " && ( (n.model = '". $input['model'] . "') ) ";
		}

		if( $input['officer'] != "0" ){

			$query_part .= " && n.officer = '". $input['officer'] . "' ";
		}

        if( $input['merchant'] != "" ){

			$query_part .= " && n.merchant like '%". $input['merchant'] . "%' ";
		}

		if( $input['status'] != "0" ){

			$query_part .= " && n.status = '". $input['status'] . "' ";
		}

		if( $input['sub_status'] != "0" ){

			$query_part .= " && n.sub_status = '". $input['sub_status'] . "' ";
		}

		if( $input['serial_number'] != "" ){

			$query_part .= " && n.serialno = '". $input['serial_number'] . "' ";
		}

		if( ($input['from_date'] != "") && ($input['to_date'] != "") ){

			$query_part .= " && n.tdate between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

		if( $input['tid'] != "" ){

			$query_part .= " && n.tid = '". $input['tid'] . "' ";
		}

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objFault = new Fault();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
		$objNewInstallation = new NewInstallation();

		$data['report_table'] = $objNewInstallation->getNewInquireResult($query_part);

        if( $request->submit == 'Inquire' ){

            $data['model'] = $objModel->get_models();
            $data['bank'] = $objBank->get_bank();
            $data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
            $data['fault'] = $objFault->getFaults();
            $data['sub_status'] = $objSubStatus->getNewInstallationSubStatus();
            $data['status'] = $objStatus->getBackupRemoveStatus();

            return view('fsp.field_service_inquire.newinstall_inquire')->with('BR', $data);
        }

        if( $request->submit == 'Excell' ){


            $this->prepareExcellSheet($data['report_table']);
        }

	}
    
}
