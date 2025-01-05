<?php

namespace App\Http\Controllers\Hardware\Quotation\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\Operation\Quotation;
use App\Models\Master\Bank;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class QuotationInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objSparePartProcess = new SparePartProcess();
        $objBank = new Bank();

        $data['report_table'] = array();
        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objSparePartProcess->get_models();
        $data['attributes'] = $this->get_quotation_inquire_attributes(NULL, NULL);

        return view('hardware.quotation.inquire.quotation_inquire')->with('QI', $data);
    }

    private function get_quotation_inquire_attributes($process, $request){

        $attributes['search_value'] = '';
        $attributes['model'] = 0;
        $attributes['bank'] = 0;
        $attributes['from_date'] = '';
        $attributes['to_date'] = '';
        $attributes['process_message'] = '';
        $attributes['validation_messages'] = new MessageBag();

        if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if((is_null($process) == TRUE) && (is_null($request) == FALSE)){

            $attributes['search_value'] = $request->search_value;
            $attributes['model'] = $request->model;
            $attributes['bank'] = $request->bank;
            $attributes['from_date'] = $request->from_date;
            $attributes['to_date'] = $request->to_date;

            return $attributes;
        }
    }

    public function quotation_inquire_process(Request $request){

        $objSparePartProcess = new SparePartProcess();
        $objBank = new Bank();

        $result = $this->get_quotation_inquire_information($request);

        $data['report_table'] = $result;
        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objSparePartProcess->get_models();
        $data['attributes'] = $this->get_quotation_inquire_attributes(NULL, $request);

        return view('Hardware.Quotation.Inquire.quotation_inquire')->with('QI', $data);

    }

    public function get_quotation_inquire_information($request){


        $query_string  = "";

        if($request->search_value != ''){

            if($request->search_parameter == 'job_card'){

                $query_string .= " && jobcard_no = '". $request->search_value ."' ";
            }

            if($request->search_parameter == 'quotation'){

                $query_string .= " && qt_no = '". $request->search_value ."' ";
            }

            if($request->search_parameter == 'serial_number'){

                $query_string .= " && serial_no = '". $request->search_value ."' ";
            }
        }

        if($request->bank != 0){

            $query_string .= " && bank = '". $request->bank ."' ";
        }

        if($request->model != 0){

            $query_string .= " && model = '". $request->model ."' ";
        }

        if( isset($request->from_date) && isset($request->to_date)){

            $query_string .= " && qt_date between '". $request->from_date ."' and '". $request->to_date ."' ";
        }


        $objQuotation = new Quotation();
        $result = $objQuotation->get_quotation_inquire_process($query_string);

        return $result;
    }

}
