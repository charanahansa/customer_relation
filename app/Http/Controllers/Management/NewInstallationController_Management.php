<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\management\NewInstallation;

class NewInstallationController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objNew = new NewInstallation();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['new_installation_count'] = $objNew->get_new_installation_count($from_date, $to_date);
        $data['new_installation_bank_count'] = $objNew->get_bank_wise_new_installation_count($from_date, $to_date);
        $data['new_installation_status_count'] = $objNew->get_status_wise_new_installation_count($from_date, $to_date);

        return view('management.dashboards.new_installation')->with('new_installation', $data);
    }

    public function dashboard_new_installation_process(Request $request){

        $objNew = new NewInstallation();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['new_installation_count'] = $objNew->get_new_installation_count($from_date, $to_date);
        $data['new_installation_bank_count'] = $objNew->get_bank_wise_new_installation_count($from_date, $to_date);
        $data['new_installation_status_count'] = $objNew->get_status_wise_new_installation_count($from_date, $to_date);

        return view('management.dashboards.new_installation')->with('new_installation', $data);
    }

    public function get_bank_wise_status_wise_new_installation_count(Request $request){

        $objNew = new NewInstallation();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objNew->get_bank_wise_status_wise_new_installation_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_new_installation_count(Request $request){

        $objNew = new NewInstallation();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objNew->get_status_wise_bank_wise_new_installation_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_new_installation_detail(Request $request){

        $objNew = new NewInstallation();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['new_installation_detail'] = $objNew->get_new_installation_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }

}
