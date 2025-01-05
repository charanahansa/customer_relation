<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\ProfileSharing;

class ProfileSharingController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objProfileSharing = new ProfileSharing();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['profile_sharing_count'] = $objProfileSharing->get_profile_sharing_count($from_date, $to_date);
        $data['profile_sharing_bank_count'] = $objProfileSharing->get_bank_wise_profile_sharing_count($from_date, $to_date);
        $data['profile_sharing_status_count'] = $objProfileSharing->get_status_wise_profile_sharing_count($from_date, $to_date);

        return view('management.dashboards.profile_sharing')->with('profile_sharing', $data);
    }

    public function dashboard_profile_sharing_process(Request $request){

        $objProfileSharing = new ProfileSharing();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['profile_sharing_count'] = $objProfileSharing->get_profile_sharing_count($from_date, $to_date);
        $data['profile_sharing_bank_count'] = $objProfileSharing->get_bank_wise_profile_sharing_count($from_date, $to_date);
        $data['profile_sharing_status_count'] = $objProfileSharing->get_status_wise_profile_sharing_count($from_date, $to_date);

        return view('management.dashboards.profile_sharing')->with('profile_sharing', $data);
    }

    public function get_bank_wise_status_wise_profile_sharing_count(Request $request){

        $objProfileSharing = new ProfileSharing();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objProfileSharing->get_bank_wise_status_wise_profile_sharing_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_profile_sharing_count(Request $request){

        $objProfileSharing = new ProfileSharing();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objProfileSharing->get_status_wise_bank_wise_profile_sharing_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_profile_sharing_detail(Request $request){

        $objProfileSharing = new ProfileSharing();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['profile_sharing_detail'] = $objProfileSharing->get_profile_sharing_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }


}
