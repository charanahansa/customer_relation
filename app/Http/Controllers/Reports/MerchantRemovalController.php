<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reports\MerchantRemoval;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MerchantRemovalController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objMerchantRemoval = new MerchantRemoval();

		$data['bank'] = $objMerchantRemoval->get_bank();
		$data['model'] = $objMerchantRemoval->get_models();
		$data['status'] = $objMerchantRemoval->get_status();

        return view('reports.merchant_removal_report')->with('MR', $data);
    }

    public function merchant_removal_report_process(Request $request){

		$objMerchantRemoval = new MerchantRemoval();

		$input = $request->input();

		$removed_by_filter = '';
        $return_to_filter = '';
        $tid_filter = '';
        $serialno_filter = '';
        $merchant_filter = '';

		$bank_filter = '';
		$officer_filter = '';
		$status_filter = '';


        //Removed By
        if( isset($input['removed_by']) ){

            $removed_by_filter = " && r.removed_by = '". $input['removed_by'] ."' ";
        }

        //Return To
        if( isset($input['return_to']) ){

            $return_to_filter = " && r.return_to = '". $input['return_to'] ."' ";
        }

		// Tid
		if( isset($input['from_tid']) && isset($input['to_tid']) ){

			$tid_filter = " && rd.tid between '". $input['from_tid'] ."' and '". $input['to_tid'] ."'  ";
		}

		// Serial No.
		if( isset($input['from_serialno']) && isset($input['to_serialno']) ){

			$serialno_filter = " && rd.serialno between '". $input['from_serialno'] ."' and '". $input['to_serialno'] ."'  ";
		}

		// Merchant
		if( isset($input['merchant']) ){

			$merchant_filter = " && rd.merchant like '%". $input['merchant'] ."%' ";
		}

		// Bank
		if(isset($input['bank'])){

			$bank_array = $input['bank'];
			$bank_list = '';
			foreach($bank_array as $bank){

				$bank_list .= "'" . $bank . "', ";
			}
			$bank_list = substr(rtrim($bank_list),0,-1);
			$bank_filter = " && r.bank in (". $bank_list .") ";

		}

		// Status
		if(isset($input['status'])){

			$status_array = $input['status'];
			$status_list = '';
			foreach($status_array as $status){

				$status_list .= "'" . $status . "', ";
			}
			$status_list = substr(rtrim($status_list),0,-1);
			$status_filter = " && r.status in (". $status_list .") ";
		}

		$total_filter = $removed_by_filter . $return_to_filter . $tid_filter . $serialno_filter;
		$total_filter .= $bank_filter . $merchant_filter .  $status_filter;

		$result = $objMerchantRemoval->get_report($input['from_date'], $input['to_date'], $total_filter);

		$this->genarate_excel_file($result);

	}

	private function genarate_excel_file($resultset){

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$col_count = 1;

		$sheet->mergeCells('A1:D1');
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Ticket No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Date'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Bank'); $col_count++;
        $sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Removed By'); $col_count++;
        $sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Return To'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Tid'); $col_count++;
        $sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Mid'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Model'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Merchant'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Remark'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Status'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Saved On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Saved By'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Last Edit On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Last Edit By'); $col_count++;


		/*  Heading */

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		$last_heading_cell_address = $this->getExcelColumn($col_count) . '2';


		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A2:' . $last_heading_cell_address)->applyFromArray($style);
		$sheet->getStyle('A2:' . $last_heading_cell_address)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');



		$icount = 3;
		$column_count = 1;
		foreach($resultset as $row){

			$col_count = 1;

			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ticketno);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tdate);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->bank);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->removed_by));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->return_to));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tid);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->mid);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->model);  $col_count++;
            $sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->serialno);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->merchant);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->remark);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->status));  $col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->saved_on);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->saved_by);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->edit_by);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->edit_on);  $col_count++;

			$icount++;
			$column_count = $col_count;
		}

		$style= array(
			'font'  => array(
					'bold'  => false,
					'size'  => 9,
					'name'  => 'Consolas'
				)
		);

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);

		$last_cell_address = $this->getExcelColumn($column_count) . $icount;

		$spreadsheet->getActiveSheet()->getStyle('A3:'. $last_cell_address)->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A3:'. $last_cell_address)->applyFromArray($style);

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:' . $last_cell_address)->getBorders()->applyFromArray($border_styleArray);


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
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);

		$writer = new Xlsx($spreadsheet);
		$filename = 'Merchant_Removal_Report';

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
