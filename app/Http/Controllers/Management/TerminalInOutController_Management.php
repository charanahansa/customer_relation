<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\TerminalInOut;

class TerminalInOutController_Management extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objTerminalInOut = new TerminalInOut();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['terminal_in_count'] = $objTerminalInOut->get_terminal_in_count($from_date, $to_date);
        $data['terminal_in_bank_count'] = $objTerminalInOut->get_bank_wise_terminal_in_qty($from_date, $to_date);
        $data['terminal_out_count'] = $objTerminalInOut->get_terminal_out_count($from_date, $to_date);
        $data['terminal_out_bank_count'] = $objTerminalInOut->get_bank_wise_terminal_out_qty($from_date, $to_date);

        return view('management.dashboards.terminal_in_out')->with('terminal_in_out', $data);
    }

    public function dashboard_terminal_in_out_process(Request $request){

        $objTerminalInOut = new TerminalInOut();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;
        $data['terminal_in_count'] = $objTerminalInOut->get_terminal_in_count($from_date, $to_date);
        $data['terminal_in_bank_count'] = $objTerminalInOut->get_bank_wise_terminal_in_qty($from_date, $to_date);
        $data['terminal_out_count'] = $objTerminalInOut->get_terminal_out_count($from_date, $to_date);
        $data['terminal_out_bank_count'] = $objTerminalInOut->get_bank_wise_terminal_out_qty($from_date, $to_date);

        return view('management.dashboards.terminal_in_out')->with('terminal_in_out', $data);
    }

}
