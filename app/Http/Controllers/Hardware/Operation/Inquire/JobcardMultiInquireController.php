<?php

namespace App\Http\Controllers\Hardware\Operation\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\Operation\JobCardProcess;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JobcardMultiInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        return view('hardware.operation.jobcard_multi_inquire');
    }

	public function jobcard_multi_inquire_process(Request $request){

		$objJobCardProcess = new JobCardProcess();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$col_count = 1;

		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Job Card No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Job Card Date'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Bank'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Model'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Qt No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Status'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Released'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Released Date'); $col_count++;

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:J1')->applyFromArray($style);
		$sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$jobcard_information = $request->jobcard_numbers;
		$jobcard_information = explode(chr(13), $jobcard_information);

		$icount = 2;
		foreach($jobcard_information as $jobcard_row){

			$jc_row = rtrim(ltrim($jobcard_row));

			$jobcard_result = $objJobCardProcess->jobcard_inquire($jc_row);
			foreach($jobcard_result as $row){

				$col_count = 1;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ($icount - 1)); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->jobcard_no); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->jobcard_date); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->bank); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->serialno); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->model); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->qt_no); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->status); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->Released)); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->Released_Date, 2); $col_count++;

				$icount++;
			}

		}

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => false,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		$spreadsheet->getActiveSheet()->getStyle('A1:J'.$icount)->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A2:J'. $icount)->applyFromArray($style);

		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);

		$writer = new Xlsx($spreadsheet);
		$filename = 'JobCard_Multi_Inquire';

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output'); // download file

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

	function YesNo($result){

		if($result == 1){

			return 'Yes';
		}else{

			return 'No';
		}
	}

}
