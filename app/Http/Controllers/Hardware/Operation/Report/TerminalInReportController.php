<?php

namespace App\Http\Controllers\Hardware\Operation\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Hardware\Operation\TerminalInProcess;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TerminalInReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
        $objModel = new TerminalModel();

		$data['bank'] = $objBank->get_bank();
		$data['model'] = $objModel->get_models();

        return view('hardware.operation.report.terminal_in_report')->with('tip', $data);
    }

    public function terminal_in_report_process(Request $request){

		$objTerminalInProcess = new TerminalInProcess();

		$input = $request->input();

		$serial_no_filter = '';
		$jobcardno_filter = '';
		$bank_filter = '';
		$model_filter = '';

		//Serial No.
		if( isset($input['from_serialno']) && isset($input['to_serialno']) ){

			$serial_no_filter = " && serialno between '". $input['from_serialno'] ."' and '". $input['to_serialno'] ."'  ";
		}

		//Job Card No.
		if( isset($input['from_jobcardno']) && isset($input['to_jobcardno']) ){

			$jobcardno_filter = " && jobcard_no between '". $input['from_jobcardno'] ."' and '". $input['to_jobcardno'] ."'  ";
		}

		// Bank
		if(isset($input['bank'])){

			$bank_array = $input['bank'];
			$bank_list = '';
			foreach($bank_array as $bank){

				$bank_list .= "'" . $bank . "', ";
			}
			$bank_list = substr(rtrim($bank_list),0,-1);
			$bank_filter = " && bank in (". $bank_list .") ";

		}

		// Model
		if(isset($input['model'])){

			$model_array = $input['model'];
			$model_list = '';
			foreach($model_array as $model){

				$model_list .= "'" . $model . "', ";
			}
			$model_list = substr(rtrim($model_list),0,-1);
			$model_filter = " && model in (". $model_list .") ";
		}

		$total_filter = $serial_no_filter . $jobcardno_filter . $bank_filter . $model_filter;

		$result = $objTerminalInProcess->get_report_resultset($input['from_date'], $input['to_date'], $total_filter);

		$this->genarate_excel_file($result);
	}

	private function genarate_excel_file($resultset){

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'JobCard No');
		$sheet->setCellValue('B1', 'Date');
		$sheet->setCellValue('C1', 'Bank');
		$sheet->setCellValue('D1', 'Serial No.');
		$sheet->setCellValue('E1', 'Model');
		$sheet->setCellValue('F1', 'Officer');

		/*  Heading */

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:F1')->applyFromArray($style);
		$sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getBorders()->applyFromArray($border_styleArray);

		$icount = 2;
		foreach($resultset as $row){

			$sheet->setCellValue('A' . $icount, $row->jobcard_no);
			$sheet->setCellValue('B' . $icount, $row->tmc_jc_date);
			$sheet->setCellValue('C' . $icount, $row->bank);
            $sheet->setCellValue('D' . $icount, $row->serialno);
            $sheet->setCellValue('E' . $icount, $row->model);
            $sheet->setCellValue('F' . $icount, $row->accepted_officer);

			$icount++;
		}

		$sheet->getStyle('A:F')->getAlignment()->setHorizontal('left');

		$style= array(
			'font'  => array(
					'bold'  => false,
					'size'  => 9,
					'name'  => 'Consolas'
				)
		);
		$sheet->getStyle('A2:F'.$icount)->applyFromArray($style);

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:F'.$icount)->getBorders()->applyFromArray($border_styleArray);

		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);

		$writer = new Xlsx($spreadsheet);
		$filename = 'Terminal_In_Report';

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output'); // download file
	}


}
