<?php

namespace App\Http\Controllers\tmc\hardware\inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\SparePart\SparePartProcess;
use App\Http\Controllers\Hardware\Quotation\Inquire\QuotationInquireController as Hardware_QuotationInquireController;
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

        return view('tmc.hardware.inquire.quotation_inquire')->with('QI', $data);
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

	public function tmc_quotation_inquire_process(Request $request){

		$objHardware_QuotationInquireController = new Hardware_QuotationInquireController();
		$objSparePartProcess = new SparePartProcess();
        $objBank = new Bank();

        $result = $objHardware_QuotationInquireController->get_quotation_inquire_information($request);

        $data['report_table'] = $result;
        $data['bank'] = $objBank->get_bank();
        $data['model'] = $objSparePartProcess->get_models();
        $data['attributes'] = $this->get_quotation_inquire_attributes(NULL, $request);

		return view('tmc.hardware.inquire.quotation_inquire')->with('QI', $data);

	}

}
