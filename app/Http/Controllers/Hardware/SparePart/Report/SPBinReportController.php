<?php

namespace App\Http\Controllers\Hardware\SparePart\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Hardware\SparePart\Bin;
use App\Models\Hardware\SparePart\HwBin;
use App\Models\Hardware\SparePart\SparePartProcess;

class SPBinReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

		$objBin = new Bin();
		$objHwBin = new HwBin();
		$objSparePartProcess = new SparePartProcess();

		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
		$data['attributes'] = $this->get_spare_part_bin_attributes(NULL, NULL);
		$data['data_table'] = $objHwBin->get_spare_part_bin_report('');

        return view('Hardware.spare_part.report.sp_bin_report')->with('SBR', $data);
    }

	private function get_spare_part_bin_attributes($process, $request){

		$attributes['bin_id'] = 0;
		$attributes['spare_part_id'] = 0;

		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if((is_null($process) == TRUE) && (is_null($request) == FALSE)){

			$attributes['bin_id'] = $request->bin_id;
			$attributes['spare_part_id'] = $request->spare_part_id;

			return $attributes;
		}

	}

	public function sp_bin_report_process(Request $request){

		$query_filter = " ";

		if($request->spare_part_id != 0){

			$query_filter .= " && spare_part_id = ". $request->spare_part_id ."  ";
		}

		if($request->bin_id != 0){

			$query_filter .= " && b.bin_id = ". $request->bin_id ."  ";
		}

		echo $query_filter;

		$objBin = new Bin();
		$objHwBin = new HwBin();
		$objSparePartProcess = new SparePartProcess();

		$data['bin'] = $objBin->get_bin_active_list();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
		$data['attributes'] = $this->get_spare_part_bin_attributes(NULL, $request);
		$data['data_table'] = $objHwBin->get_spare_part_bin_report($query_filter);

        return view('Hardware.spare_part.report.sp_bin_report')->with('SBR', $data);;

	}

}
