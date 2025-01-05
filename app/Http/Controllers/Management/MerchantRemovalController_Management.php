<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\management\MerchantRemoval;

class MerchantRemovalController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objMerchantRemoval = new MerchantRemoval();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['merchant_removal_count'] = $objMerchantRemoval->get_merchant_removal_count($from_date, $to_date);
        $data['merchant_removal_bank_count'] = $objMerchantRemoval->get_bank_wise_merchant_removal_count($from_date, $to_date);
        $data['merchant_removal_status_count'] = $objMerchantRemoval->get_status_wise_merchant_removal_count($from_date, $to_date);

        return view('management.dashboards.merchant_removal')->with('merchant_removal', $data);
    }

    public function dashboard_merchant_removal_process(Request $request){

        $objMerchantRemoval = new MerchantRemoval();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['merchant_removal_count'] = $objMerchantRemoval->get_merchant_removal_count($from_date, $to_date);
        $data['merchant_removal_bank_count'] = $objMerchantRemoval->get_bank_wise_merchant_removal_count($from_date, $to_date);
        $data['merchant_removal_status_count'] = $objMerchantRemoval->get_status_wise_merchant_removal_count($from_date, $to_date);

        return view('management.dashboards.merchant_removal')->with('merchant_removal', $data);
    }

    public function get_bank_wise_status_wise_merchant_removal_count(Request $request){

        $objMerchantRemoval = new MerchantRemoval();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objMerchantRemoval->get_bank_wise_status_wise_merchant_removal_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_merchant_removal_count(Request $request){

        $objMerchantRemoval = new MerchantRemoval();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objMerchantRemoval->get_status_wise_bank_wise_merchant_removal_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_merchant_removal_detail(Request $request){

        $objMerchantRemoval = new MerchantRemoval();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['merchant_removal_detail'] = $objMerchantRemoval->get_merchant_removal_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }


}
