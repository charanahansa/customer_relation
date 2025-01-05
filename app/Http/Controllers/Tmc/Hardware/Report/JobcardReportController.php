<?php

namespace App\Http\Controllers\Tmc\Hardware\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Master\HardwareFault;
use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\Operation\HardwareServices;
use App\Models\Hardware\Operation\HardwareStatus;

class JobcardReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objHardwareFault = new HardwareFault();
        $objSparePart = new SparePartProcess();
        $objHardwareServices = new HardwareServices();
		$objHardwareStatus = new HardwareStatus();

		$data['bank'] = $objBank->get_bank();
		$data['model'] = $objModel->get_models();
        $data['fault'] = $objHardwareFault->get_faults();
        $data['spare_part'] = $objSparePart->get_spare_part_active_list();
        $data['services'] = $objHardwareServices->get_service_list();
		$data['status'] = $objHardwareStatus->get_status_list();

        return view('tmc.hardware.report.jobcard_report')->with('JR', $data);
    }

}
