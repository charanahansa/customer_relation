<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\BaseSoftwareInstallation;

class BaseSoftwareInstallationController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objBaseSoftwareInstallation = new BaseSoftwareInstallation();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['base_software_installation_count'] = $objBaseSoftwareInstallation->get_base_software_installation_count($from_date, $to_date);
        $data['base_software_installation_bank_count'] = $objBaseSoftwareInstallation->get_bank_wise_base_software_installation_count($from_date, $to_date);
        $data['base_software_installation_status_count'] = $objBaseSoftwareInstallation->get_status_wise_base_software_installation_count($from_date, $to_date);

        return view('management.dashboards.base_software_installation')->with('base_software_installation', $data);
    }

    public function dashboard_base_software_installation_process(Request $request){

        $objBaseSoftwareInstallation = new BaseSoftwareInstallation();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['base_software_installation_count'] = $objBaseSoftwareInstallation->get_base_software_installation_count($from_date, $to_date);
        $data['base_software_installation_bank_count'] = $objBaseSoftwareInstallation->get_bank_wise_base_software_installation_count($from_date, $to_date);
        $data['base_software_installation_status_count'] = $objBaseSoftwareInstallation->get_status_wise_base_software_installation_count($from_date, $to_date);

        return view('management.dashboards.base_software_installation')->with('base_software_installation', $data);
    }

    public function get_bank_wise_status_wise_base_software_installation_count(Request $request){

        $objBaseSoftwareInstallation = new BaseSoftwareInstallation();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objBaseSoftwareInstallation->get_bank_wise_status_wise_base_software_installation_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_base_software_installation_count(Request $request){

        $objBaseSoftwareInstallation = new BaseSoftwareInstallation();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objBaseSoftwareInstallation->get_status_wise_bank_wise_base_software_installation_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_base_software_installation_detail(Request $request){

        $objBaseSoftwareInstallation = new BaseSoftwareInstallation();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['base_software_installation_detail'] = $objBaseSoftwareInstallation->get_base_software_installation_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }

}
