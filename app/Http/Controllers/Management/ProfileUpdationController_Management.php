<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\ProfileUpdation;

class ProfileUpdationController_Management extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $ProfileUpdation = new ProfileUpdation();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['profile_updation_count'] = $ProfileUpdation->get_profile_updation_count($from_date, $to_date);
        $data['profile_updation_bank_count'] = $ProfileUpdation->get_bank_wise_profile_updation_count($from_date, $to_date);
        $data['profile_updation_status_count'] = $ProfileUpdation->get_status_wise_profile_updation_count($from_date, $to_date);

        return view('management.dashboards.profile_updation')->with('profile_updation', $data);
    }

    public function dashboard_profile_updation_process(Request $request){

        $ProfileUpdation = new ProfileUpdation();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['profile_updation_count'] = $ProfileUpdation->get_profile_updation_count($from_date, $to_date);
        $data['profile_updation_bank_count'] = $ProfileUpdation->get_bank_wise_profile_updation_count($from_date, $to_date);
        $data['profile_updation_status_count'] = $ProfileUpdation->get_status_wise_profile_updation_count($from_date, $to_date);

        return view('management.dashboards.profile_updation')->with('profile_updation', $data);
    }

    public function get_bank_wise_status_wise_profile_updation_count(Request $request){

        $ProfileUpdation = new ProfileUpdation();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $ProfileUpdation->get_bank_wise_status_wise_profile_updation_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_profile_updation_count(Request $request){

        $ProfileUpdation = new ProfileUpdation();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $ProfileUpdation->get_status_wise_bank_wise_profile_updation_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_profile_updation_detail(Request $request){

        $ProfileUpdation = new ProfileUpdation();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['profile_updation_detail'] = $ProfileUpdation->get_profile_updation_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }


}
