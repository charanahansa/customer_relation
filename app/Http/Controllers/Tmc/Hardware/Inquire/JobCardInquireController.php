<?php

namespace App\Http\Controllers\Tmc\Hardware\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Tmc\Jobcard\Jobcard;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JobCardInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

        $objBank = new Bank();

        $data['bank'] = $objBank->get_bank();
        $data['report_table'] = array();

		return view('tmc.hardware.inquire.jobcard_inquire')->with('JI', $data);
	}

    public function tmc_jobcard_inquire_process(Request $request){

        $input = $request->input();
		$query_part = '';

		if( $input['bank'] != "0" ){

			$query_part .= " && j.bank = '". $input['bank'] . "' ";
		}

        if( $input['lot_number'] != "" ){

			$query_part .= " && j.lot_number = '". $input['lot_number'] . "' ";
		}

		if( $input['box_number'] != "" ){

			$query_part .= " && j.box_number = '". $input['box_number'] . "' ";
		}

        if( $request->search_value != '@' ){

            if( $request->search_parameter == 'job_card'){

                $query_part .= " && j.jobcard_no = '". $input['search_value'] . "' ";
            }

            if( $request->search_parameter == 'quotation'){

                $query_part .= " && q.qt_no = '". $input['search_value'] . "' ";
            }

            if( $request->search_parameter == 'serial_number'){

                $query_part .= " && j.serialno = '". $input['search_value'] . "' ";
            }

        }

		if( ($input['from_date'] != "") && ($input['to_date'] != "") ){

			$query_part .= " && j.jc_date between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

        $objBank = new Bank();
        $objJobcard = new Jobcard();

        $data['bank'] = $objBank->get_bank();

        $report = array();
        $icount = 1;
        $result = $objJobcard->getJobcardInquireResult($query_part);
        foreach($result as $row){

            $report[$icount]['jobcard_no'] = $row->jobcard_no;
            $report[$icount]['jc_date'] = $row->jc_date;
            $report[$icount]['bank'] = $row->bank;
            $report[$icount]['serialno'] = $row->serialno;
            $report[$icount]['model'] = $row->model;
            $report[$icount]['lot_number'] = $row->lot_number;
            $report[$icount]['box_number'] = $row->box_number;
            $report[$icount]['qt_no'] = $row->qt_no;
            $report[$icount]['qt_date'] = $row->qt_date;
            $report[$icount]['net_price'] = $row->net_price;
            $report[$icount]['status'] = $row->status;
            $report[$icount]['Released_Date'] = $row->Released_Date;
            $report[$icount]['Out_Date'] = $row->Released_Date;

            $icount++;
        }

        $data['report_table'] = $report;

        if( $request->submit == 'Excel' ){

            $this->prepareExcelSheet( $data['report_table']);

        }else{

            return view('tmc.hardware.inquire.jobcard_inquire')->with('JI', $data);
        }
    }

    private function prepareExcelSheet($data_table){

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Job Card No.');
        $sheet->setCellValue('C1', 'JC Date');
        $sheet->setCellValue('D1', 'Bank');
        $sheet->setCellValue('E1', 'Serial No. ');
        $sheet->setCellValue('F1', 'Model ');
        $sheet->setCellValue('G1', 'Lot No. ');
        $sheet->setCellValue('H1', 'Box No. ');
        $sheet->setCellValue('I1', 'Qt No.');
        $sheet->setCellValue('J1', 'Qt Date');
        $sheet->setCellValue('K1', 'Qt Amt');
        $sheet->setCellValue('L1', 'Status');
        $sheet->setCellValue('M1', 'Hw Released Date');
        $sheet->setCellValue('N1', 'Tmc Released Date');

        //set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:N1')->applyFromArray($style);
		$sheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:N1')->getBorders()->applyFromArray($border_styleArray);

        $icount = 2;
		foreach($data_table as $row){

            $sheet->setCellValue('A' . $icount, ($icount -1));
            $sheet->setCellValue('B' . $icount, $row['jobcard_no']);
            $sheet->setCellValue('C' . $icount, $row['jc_date']);
            $sheet->setCellValue('D' . $icount, $row['bank']);
            $sheet->setCellValue('E' . $icount, $row['serialno']);
            $sheet->setCellValue('F' . $icount, $row['model']);
            $sheet->setCellValue('G' . $icount, $row['lot_number']);
            $sheet->setCellValue('H' . $icount, $row['box_number']);
            $sheet->setCellValue('I' . $icount, $row['qt_no']);
            $sheet->setCellValue('J' . $icount, $row['qt_date']);
            $sheet->setCellValue('K' . $icount, $row['net_price']);
            $sheet->setCellValue('L' . $icount, $row['status']);
            $sheet->setCellValue('M' . $icount, $row['Released_Date']);
            $sheet->setCellValue('N' . $icount, $row['Out_Date']);

            $icount++;
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

		$last_cell_address = 'N' . $icount;

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

        $writer = new Xlsx($spreadsheet);
		$filename = 'Jobcard_Report';

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output'); // download file


    }

}
