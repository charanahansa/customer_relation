<?php

namespace App\Http\Controllers\Hardware\SparePart\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Hardware\Operation\JobCardProcess;
use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\SparePart\SparePartRequestProcess;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SPPendingReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
		$objModel = new TerminalModel();
        $objSparePartProcess = new SparePartProcess();

		$data['bank'] = $objBank->get_bank();
        $data['model'] = $objModel->get_models();
        $data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
        $data['attributes'] = $this->getSparepartPendingReportAttributes(NULL);

        return view('Hardware.spare_part.report.sp_pending_report')->with('SPPR', $data);
    }

    private function getSparepartPendingReportAttributes($request){

        $attributes['from_date'] = date('Y-m-d');
        $attributes['to_date'] = date('Y-m-d');
		$attributes['bank'] = 0;
		$attributes['model'] = 0;
		$attributes['spare_part_id'] = 0;

        if( ! is_null($request) ){

            $input = $request->input();
            if(is_null($input) == FALSE){

                $attributes['from_date'] = $input['bank'];;
                $attributes['to_date'] = $input['bank'];;
                $attributes['bank'] = $input['bank'];
                $attributes['model'] = $input['model'];
                $attributes['spare_part_id'] = $input['spare_part_id'];
            }
        }

        return $attributes;
	}

    public function SpPendingReportProcess(Request $request){

        $query_filter = " ";

        if( ($request->from_date != '') && ($request->to_date != '') ){

			$query_filter .= " && sprn.spr_date between '". $request->from_date ."' and '". $request->to_date ."'  ";
		}

		if($request->spare_part_id != 0){

			$query_filter .= " && sprn.spare_part_id = ". $request->spare_part_id ."  ";
		}

        if($request->bank != '0'){

			$query_filter .= " && t.bank = '". $request->bank ."' ";
		}

        if($request->model != '0'){

			$query_filter .= " && t.model = '". $request->model ."' ";
		}

        if($request->jobcard_number != ''){

			$query_filter .= " && sprn.reference_number = '". $request->jobcard_number ."' ";
		}

        $objSparePartProcess = new SparePartProcess();
        $objJobcard = new JobCardProcess();
        $objSparePartRequestProcess =  new SparePartRequestProcess();
        $report_result = $objSparePartRequestProcess->getSparepartPendingReport($query_filter);

        $sppr_array = array();
        foreach($report_result as $key => $row){

            $sppr_row['jobcard_number'] = $row->reference_number;
            $sppr_row['serial_number'] = $row->serialno;
            $sppr_row['model'] = $row->model;
            $sppr_row['bank'] = $row->bank;
            $sppr_row['pending_spare_part'] = $objSparePartRequestProcess->getSparepartRequestForJobcard($row->reference_number);
            $sppr_row['removed_spare_part'] = $objJobcard->getRemovedSparepartDetail($row->reference_number);

            $sppr_array[$key] = $sppr_row;
        }

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

        $col_count = 1;

        $sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Jobcard Number'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Serial Number'); $col_count++;
        $sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Model'); $col_count++;
        $sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Bank'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Pending Spare Part'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Removed Spare Part');

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		$last_heading_cell_address = $this->getExcelColumn($col_count) . '1';
		$sheet->getStyle('A1:' . $last_heading_cell_address)->applyFromArray($style);
		$sheet->getStyle('A1:' . $last_heading_cell_address)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:' . $last_heading_cell_address)->getBorders()->applyFromArray($border_styleArray);

        $icount = 2;
		$col_count = 1;
		foreach($sppr_array as $row){

			$col_count = 1;

            $sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row['jobcard_number']);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row['serial_number']);  $col_count++;
            $sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row['model']);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row['bank']);  $col_count++;

            $pending_spare_part = '';
            foreach($row['pending_spare_part'] as $sp_row){

                $pending_spare_part .= $sp_row->spare_part_name;
            }

            $removed_spare_part = '';
            foreach($row['removed_spare_part'] as $sp_row){

                $removed_spare_part .= $objSparePartProcess->get_spare_part_name($sp_row->Part_ID);
            }

            $sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $pending_spare_part);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $removed_spare_part);  $col_count++;

            $icount++;
        }
		$icount--;
		$col_count--;

        $sheet->getStyle('A')->getAlignment()->setHorizontal('left');

		$last_heading_cell_address = $this->getExcelColumn($col_count) . $icount;
        $spreadsheet->getActiveSheet()->getStyle('A2:' . $last_heading_cell_address)->getBorders()->applyFromArray($border_styleArray);

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
		$filename = 'spare_part_pending_report';

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    function getExcelColumn($number){

		$columnString = "";
		$columnNumber = $number;

		while ($columnNumber > 0){

			$currentLetterNumber = ($columnNumber - 1)% 26;
			$currentLetter = chr($currentLetterNumber + 65);
			$columnString = $currentLetter . $columnString;
			$columnNumber = ($columnNumber - ($currentLetterNumber + 1)) / 26;

		}

		return $columnString;
	}

}
