<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\management\ReInitialization;

class ReInitializationController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objReInitialization = new ReInitialization();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['re_initialization_count'] = $objReInitialization->get_re_initialization_count($from_date, $to_date);
        $data['re_initialization_bank_count'] = $objReInitialization->get_bank_wise_re_initialization_count($from_date, $to_date);
        $data['re_initialization_status_count'] = $objReInitialization->get_status_wise_re_initialization_count($from_date, $to_date);

        return view('management.dashboards.re_initialization')->with('re_initialization', $data);
    }

    public function dashboard_re_initialization_process(Request $request){

        $objReInitialization = new ReInitialization();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['re_initialization_count'] = $objReInitialization->get_re_initialization_count($from_date, $to_date);
        $data['re_initialization_bank_count'] = $objReInitialization->get_bank_wise_re_initialization_count($from_date, $to_date);
        $data['re_initialization_status_count'] = $objReInitialization->get_status_wise_re_initialization_count($from_date, $to_date);

        return view('management.dashboards.re_initialization')->with('re_initialization', $data);
    }

    public function get_bank_wise_status_wise_re_initialization_count(Request $request){

        $objReInitialization = new ReInitialization();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objReInitialization->get_bank_wise_status_wise_re_initialization_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_re_initialization_count(Request $request){

        $objReInitialization = new ReInitialization();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objReInitialization->get_status_wise_bank_wise_re_initialization_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_re_initialization_detail(Request $request){

        $objReInitialization = new ReInitialization();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['re_initialization_detail'] = $objReInitialization->get_re_initialization_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }

}
