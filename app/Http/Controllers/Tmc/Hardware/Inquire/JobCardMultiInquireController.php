<?php

namespace App\Http\Controllers\tmc\hardware\inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Hardware\Operation\JobcardMultiInquireController as Hardware_JobcardMultiInquireController;

class JobCardMultiInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        return view('tmc.hardware.inquire.jobcard_multi_inquire');
    }

	public function tmc_jobcard_multi_inquire_process(Request $request){

		$objHardware_JobcardMultiInquireController = new Hardware_JobcardMultiInquireController();
		$objHardware_JobcardMultiInquireController->jobcard_multi_inquire_process($request);

	}


}
