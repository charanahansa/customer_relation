<?php

namespace App\Http\Controllers\Tmc\Courier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use  App\Models\Tmc\Operation\TerminalIn;
use  App\Models\Tmc\Operation\Breakdown;
use  App\Models\Tmc\Operation\NewInstallation;
use  App\Models\Tmc\Operation\ReInitilization;
use  App\Models\Tmc\Operation\SoftwareUpdation;
use  App\Models\Tmc\Operation\TerminalReplacement;
use  App\Models\Tmc\Backup\BackupProcess;

use App\Models\Tmc\Courier\CourierProcess;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class CourierInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$data['sent_table'] = NULL;
		$data['receive_table'] = NULL;
		$data['attributes'] = $this->get_courier_inquire_attributes(NULL, NULL);

		return view('tmc.courier.courier_inquire')->with('CI', $data);
	}

	private function get_courier_inquire_attributes($process, $request){

		$attributes['search'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE)){


			$attributes['search'] = $request->search;

		}else{

			$attributes['search'] = $request->search;
		}

		return $attributes;
	}

	public function courier_inquire_process(Request $request){

		$validation_result = $this->validation($request);
		if( $validation_result['validation_result'] == TRUE ){

			$courier_process_resultset = $this->process($request);
			$courier_process_resultset['validation_result'] = TRUE;

			$data['sent_table'] = $courier_process_resultset['sent_table']['dataset'];
			$data['receive_table'] = $courier_process_resultset['receive_table'];

		}else{

			$data['sent_table'] = NULL;
			$data['receive_table'] = NULL;

			$courier_process_resultset['dataset'] = NULL;
			$courier_process_resultset['validation_result'] = FALSE;
            $courier_process_resultset['process_status'] = FALSE;
            $courier_process_resultset['front_end_message'] = "Validation Process is Failed";
            $courier_process_resultset['back_end_message'] = "";
		}

		$data['attributes'] = $this->get_courier_inquire_attributes($courier_process_resultset, $request);

		return view('tmc.courier.courier_inquire')->with('CI', $data);
	}

	private function validation($request){

		try{

            $front_end_message = '';

            $input['search'] = $request->search;

            $rules['search'] = array('required');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validation_result;
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  '';

            return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Courier Inquire Controller - Courier Inquire Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

			return $process_result;
		}
	}

	private function process($request){

		$objCourierProcess = new CourierProcess();

		$objTerminalIn = new TerminalIn();
		$objBreakdown = new Breakdown();
		$objNewInstallation = new NewInstallation();
		$objReInitilization = new ReInitilization();
		$objSoftwareUpdation = new SoftwareUpdation();
		$objTerminalReplacement = new TerminalReplacement();
		$objBackupProcess = new BackupProcess();

		$courier_detail['breakdown'] = $objBreakdown->get_courier_inquire_detail($request->search);
		$courier_detail['new_installation'] = $objNewInstallation->get_courier_inquire_detail($request->search);
		$courier_detail['re_initilization'] = $objReInitilization->get_courier_inquire_detail($request->search);
		$courier_detail['software_updation'] = $objSoftwareUpdation->get_courier_inquire_detail($request->search);
		$courier_detail['terminal_replacement'] = $objTerminalReplacement->get_courier_inquire_detail($request->search);
		$courier_detail['backup_removal'] = $objBackupProcess->get_courier_inquire_detail($request->search);

		$courier_process_resultset['sent_table'] = $objCourierProcess->courier_inquire_process($courier_detail);
		$courier_process_resultset['receive_table'] = $objTerminalIn->get_courier_inquire_detail($request->search);

		return $courier_process_resultset;
	}


}
