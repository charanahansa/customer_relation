<?php

namespace App\Http\Controllers\tmc\hardware\inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Hardware\Quotation\Inquire\QuotationMultiInquireController as Hardware_QuotationMultiInquireController;

class QuotationMultiInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        return view('tmc.hardware.inquire.quotation_multi_inquire');
    }

	public function tmc_quotation_multi_inquire_process(Request $request){

		$objHardware_QuotationMultiInquireController = new Hardware_QuotationMultiInquireController();
		$objHardware_QuotationMultiInquireController->quotation_multi_inquire_process($request);
	}

}
