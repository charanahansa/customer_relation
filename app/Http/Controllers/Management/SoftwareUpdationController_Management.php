<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\management\SoftwareUpdation;

class SoftwareUpdationController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objSoftwareUpdation = new SoftwareUpdation();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['software_updation_count'] = $objSoftwareUpdation->get_software_updation_count($from_date, $to_date);
        $data['software_updation_bank_count'] = $objSoftwareUpdation->get_bank_wise_software_updation_count($from_date, $to_date);
        $data['software_updation_status_count'] = $objSoftwareUpdation->get_status_wise_software_updation_count($from_date, $to_date);

        return view('management.dashboards.software_updation')->with('software_updation', $data);
    }

    public function dashboard_software_updation_process(Request $request){

        $objSoftwareUpdation = new SoftwareUpdation();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['software_updation_count'] = $objSoftwareUpdation->get_software_updation_count($from_date, $to_date);
        $data['software_updation_bank_count'] = $objSoftwareUpdation->get_bank_wise_software_updation_count($from_date, $to_date);
        $data['software_updation_status_count'] = $objSoftwareUpdation->get_status_wise_software_updation_count($from_date, $to_date);

        return view('management.dashboards.software_updation')->with('software_updation', $data);
    }

    public function get_bank_wise_status_wise_software_updation_count(Request $request){

        $objSoftwareUpdation = new SoftwareUpdation();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objSoftwareUpdation->get_bank_wise_status_wise_software_updation_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_software_updation_count(Request $request){

        $objSoftwareUpdation = new SoftwareUpdation();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objSoftwareUpdation->get_status_wise_bank_wise_software_updation_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_software_updation_detail(Request $request){

        $objSoftwareUpdation = new SoftwareUpdation();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['software_updation_detail'] = $objSoftwareUpdation->get_software_updation_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }

}
