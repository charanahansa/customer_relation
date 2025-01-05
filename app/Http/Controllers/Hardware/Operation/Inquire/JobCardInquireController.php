<?php

namespace App\Http\Controllers\Hardware\Operation\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\Operation\JobCardProcess;

class JobCardInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $data['report_table'] = array();

		return view('Hardware.operation.jobcard_inquire')->with('JI', $data);
	}

    public function jobcard_inquire_process(Request $request){

        $data = $this->jobcard_numbers_inquire_process($request);

        return view('Hardware.operation.jobcard_inquire')->with('JI', $data);
    }

    /*
        ------------------------------------ This Function is used By Tmc ---------------------------------
    */

    public function jobcard_numbers_inquire_process($request){


        $objJobCardProcess = new JobCardProcess();

        if($request->search_parameter == 'job_card'){

            $data['report_table'] = $objJobCardProcess->jobcard_inquire($request->search_value);
        }

        if($request->search_parameter == 'serial_number'){

            $data['report_table'] = $objJobCardProcess->jobcard_inquire_by_serial_number($request->search_value);
        }

        if($request->search_parameter == 'quotation'){

            $data['report_table'] = $objJobCardProcess->jobcard_inquire_by_quotation_number($request->search_value);
        }

        if( isset($request->from_date) && isset($request->to_date)){

            $data['report_table'] = $objJobCardProcess->jobcard_inquire_by_date_period($request->from_date, $request->to_date);
        }

        return $data;
    }

}
