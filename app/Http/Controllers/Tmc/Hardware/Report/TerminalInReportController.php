<?php

namespace App\Http\Controllers\Tmc\Hardware\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;

class TerminalInReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
        $objModel = new TerminalModel();

		$data['bank'] = $objBank->get_bank();
		$data['model'] = $objModel->get_models();

        return view('tmc.hardware.report.terminal_in_report')->with('tip', $data);
    }


}
