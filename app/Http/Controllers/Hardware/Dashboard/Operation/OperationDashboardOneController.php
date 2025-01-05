<?php

namespace App\Http\Controllers\Hardware\Dashboard\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Management\TerminalRepair;

class OperationDashboardOneController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

       $objTerminalRepair = new TerminalRepair();

        $from_date = date("Y/m/d");
        $to_date =  date("Y/m/d");

        $data['repair_count'] = $objTerminalRepair->get_terminal_replacement_count($from_date, $to_date);
        $data['repair_bank_count'] = $objTerminalRepair->get_bank_wise_terminal_repair_count($from_date, $to_date);
        $data['repair_status_count'] = $objTerminalRepair->get_status_wise_terminal_repair_count($from_date, $to_date);

        $selected_dates = array($from_date, $to_date);
        $data['selected_date'] =$selected_dates;

        return view('hardware.dashboard.operation.operation_dashboard_one')->with('repair', $data);
    }

    public function repair_operation_dashboard_one_process (Request $request){

        $objTerminalRepair = new TerminalRepair();

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $selected_dates = array($from_date, $to_date);

        $data['selected_date'] =$selected_dates;

        $data['repair_count'] = $objTerminalRepair->get_terminal_replacement_count($from_date, $to_date);
        $data['repair_bank_count'] = $objTerminalRepair->get_bank_wise_terminal_repair_count($from_date, $to_date);
        $data['repair_status_count'] = $objTerminalRepair->get_status_wise_terminal_repair_count($from_date, $to_date);

        return view('hardware.dashboard.operation.operation_dashboard_one')->with('repair', $data);
    }

}
