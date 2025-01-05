<?php

namespace App\Http\Controllers\Hardware\Quotation\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\Operation\Quotation;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;


class QuotationMultiInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        return view('hardware.quotation.inquire.quotation_multi_inquire');
    }

	public function quotation_multi_inquire_process(Request $request){

		$objQuotation = new Quotation();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$col_count = 1;

		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Qt No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Qt Date'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Job Card No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Bank'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Model'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'User Negligant'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Qt Approved'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Cancel'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Price'); $col_count++;

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:K1')->applyFromArray($style);
		$sheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');


		$quotation_information = $request->referance_numbers;
		$quotation_information = explode(chr(13), $quotation_information);

		$icount = 2;
		foreach($quotation_information as $quotation_row){

			$qt_row = rtrim(ltrim($quotation_row));

			if($request->search_parameter == 'job_card'){

				$query_string = " && jobcard_no = '" . $qt_row  ."' ";
				$quotation_result = $objQuotation->get_quotation_inquire_process($query_string);
			}

			if($request->search_parameter == 'quotation'){

				$query_string = " && qt_no = '" . $qt_row  ."' ";
				$quotation_result = $objQuotation->get_quotation_inquire_process($query_string);
			}

			foreach($quotation_result as $row){

				$col_count = 1;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ($icount - 1)); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->qt_no); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->qt_date); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->jobcard_no); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->bank); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->model); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->serial_no); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->user_neg)); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->quotation_approved)); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->cancel)); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, number_format($row->price, 2) ); $col_count++;

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

		$spreadsheet->getActiveSheet()->getStyle('A1:K'.$icount)->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A2:K'. $icount)->applyFromArray($style);

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
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);

		$writer = new Xlsx($spreadsheet);
		$filename = 'Quotation_Multi_Inquire';

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
