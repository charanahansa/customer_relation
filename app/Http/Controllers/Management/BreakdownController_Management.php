<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\Breakdown;


class BreakdownController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objBreakdown = new Breakdown();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $data['breakdown_count'] = $objBreakdown->get_breakdown_count($from_date, $to_date);
        $data['breakdown_bank_count'] = $objBreakdown->get_bank_wise_breakdown_count($from_date, $to_date);
        $data['breakdown_status_count'] = $objBreakdown->get_status_wise_breakdown_count($from_date, $to_date);

        $selected_dates = array($from_date, $to_date);
        $data['selected_date'] =$selected_dates;

        return view('management.dashboards.breakdown')->with('breakdown', $data);
    }

    public function dashboard_breakdown_process(Request $request){

        $objBreakdown = new Breakdown();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['breakdown_count'] = $objBreakdown->get_breakdown_count($from_date, $to_date);
        $data['breakdown_bank_count'] = $objBreakdown->get_bank_wise_breakdown_count($from_date, $to_date);
        $data['breakdown_status_count'] = $objBreakdown->get_status_wise_breakdown_count($from_date, $to_date);

        return view('management.dashboards.breakdown')->with('breakdown', $data);
    }

    public function get_bank_wise_status_wise_breakdown_count(Request $request){

        $objBreakdown = new Breakdown();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objBreakdown->get_bank_wise_status_wise_breakdown_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_breakdown_count(Request $request){

        $objBreakdown = new Breakdown();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objBreakdown->get_status_wise_bank_wise_breakdown_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_breakdown_detail(Request $request){

        $objBreakdown = new Breakdown();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['breakdown_detail'] = $objBreakdown->get_breakdown_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }

	public function display_breakdown_report(){



	}

}
