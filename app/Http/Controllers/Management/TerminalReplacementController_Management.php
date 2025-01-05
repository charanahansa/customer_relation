<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\TerminalReplacement;

class TerminalReplacementController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objTerminalReplacement = new TerminalReplacement();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['terminal_replacement_count'] = $objTerminalReplacement->get_terminal_replacement_count($from_date, $to_date);
        $data['terminal_replacement_bank_count'] = $objTerminalReplacement->get_bank_wise_terminal_replacement_count($from_date, $to_date);
        $data['terminal_replacement_status_count'] = $objTerminalReplacement->get_status_wise_terminal_replacement_count($from_date, $to_date);

        return view('management.dashboards.terminal_replacement')->with('terminal_replacement', $data);
    }

    public function dashboard_terminal_replacement_process(Request $request){

        $objTerminalReplacement = new TerminalReplacement();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['terminal_replacement_count'] = $objTerminalReplacement->get_terminal_replacement_count($from_date, $to_date);
        $data['terminal_replacement_bank_count'] = $objTerminalReplacement->get_bank_wise_terminal_replacement_count($from_date, $to_date);
        $data['terminal_replacement_status_count'] = $objTerminalReplacement->get_status_wise_terminal_replacement_count($from_date, $to_date);

        return view('management.dashboards.terminal_replacement')->with('terminal_replacement', $data);
    }

    public function get_bank_wise_status_wise_terminal_replacement_count(Request $request){

        $objTerminalReplacement = new TerminalReplacement();

        $bank = $request->bank;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['bank_status_count'] = $objTerminalReplacement->get_bank_wise_status_wise_terminal_replacement_count($bank, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_status_wise_bank_wise_terminal_replacement_count(Request $request){

        $objTerminalReplacement = new TerminalReplacement();

        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['status_bank_count'] = $objTerminalReplacement->get_status_wise_bank_wise_terminal_replacement_count($status, $from_date, $to_date);

        echo json_encode($data);
    }

    public function get_terminal_replacement_detail(Request $request){

        $objTerminalReplacement = new TerminalReplacement();

        $bank = $request->bank;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['terminal_replacement_detail'] = $objTerminalReplacement->get_terminal_replacement_detail($bank, $status, $from_date, $to_date);

        echo json_encode($data);
    }


}
