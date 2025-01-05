<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\management\ProfileConversion;

class ProfileConversionController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objProfileConversion = new ProfileConversion();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['profile_conversion_count'] = $objProfileConversion->get_profile_conversion_count($from_date, $to_date);
        $data['profile_conversion_bank_count'] = $objProfileConversion->get_bank_wise_profile_conversion_count($from_date, $to_date);
        $data['profile_conversion_status_count'] = $objProfileConversion->get_status_wise_profile_conversion_count($from_date, $to_date);

        return view('management.dashboards.profile_conversion')->with('profile_conversion', $data);
    }

    public function dashboard_profile_conversion_process(Request $request){

        $objProfileConversion = new ProfileConversion();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['profile_conversion_count'] = $objProfileConversion->get_profile_conversion_count($from_date, $to_date);
        $data['profile_conversion_bank_count'] = $objProfileConversion->get_bank_wise_profile_conversion_count($from_date, $to_date);
        $data['profile_conversion_status_count'] = $objProfileConversion->get_status_wise_profile_conversion_count($from_date, $to_date);

        return view('management.dashboards.profile_conversion')->with('profile_conversion', $data);
    }

    public function get_bank_wise_status_wise_profile_conversion_count(Request $request){

        $objProfileConversion = new ProfileConversion();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objProfileConversion->get_bank_wise_status_wise_profile_conversion_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_profile_conversion_count(Request $request){

        $objProfileConversion = new ProfileConversion();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objProfileConversion->get_status_wise_bank_wise_profile_conversion_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_profile_conversion_detail(Request $request){

        $objProfileConversion = new ProfileConversion();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['profile_conversion_detail'] = $objProfileConversion->get_profile_conversion_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }

}
