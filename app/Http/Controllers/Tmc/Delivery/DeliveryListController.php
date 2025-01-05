<?php

namespace App\Http\Controllers\Tmc\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Delivery\Delivery;
use Illuminate\Support\MessageBag;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DeliveryListController extends Controller {

    public function index(){

        $objDelivery = new Delivery();

		$data['report_table'] = $objDelivery->getDeliveryList();
        $data['bank'] = $objDelivery->getDeliveryBankList();
		$data['model'] = $objDelivery->getDeliveryModelList();
        $data['attributes'] = $this->getDeliveryListAttributes(NULL, NULL);

		return view('tmc.delivery.delivery_list')->with('DI', $data);
	}

    private function getDeliveryListAttributes($process, $request){

        $attributes['bank'] = "Not";
        $attributes['from_date'] = '';
        $attributes['to_date'] = '';
        $attributes['delivery_number'] = '';
        $attributes['invoice_number'] = '';
        $attributes['serial_number'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if( is_null($request) == FALSE ){

            $input = $request->input();
            if(is_null($input) == FALSE){

                $attributes['bank'] = $request->bank;
                $attributes['from_date'] = $request->from_date;
                $attributes['to_date'] = $request->to_bank;
                $attributes['delivery_number'] = $request->delivery_number;
                $attributes['invoice_number'] = $request->invoice_number;
                $attributes['serial_number'] = $request->serial_number;
            }

            return $attributes;
        }

    }

    public function deliveryListProcess(Request $request){

        $input = $request->input();
		$query_part = '';

		if( $input['bank'] !== "0" ){

			$query_part .= " AND BankCode = '". $input['bank'] . "' ";
		}

        if( $input['invoice_number'] != "" ){

			$query_part .= " AND invoiceNo = '". $input['invoice_number'] . "' ";
		}

        if( $input['delivery_number'] != "" ){

			$query_part .= " AND DH.DocNo = '". $input['delivery_number'] . "' ";
		}

        if( $input['serial_number'] != "" ){

			$query_part .= " AND DS.serial = '". rtrim(ltrim($input['serial_number'])) . "' ";
		}

        if( ( ! empty($input['from_date'])) && ( ! empty($input['to_date'])) ){

			$query_part .= " AND sysdate between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

        $objDelivery = new Delivery();

        $data['report_table'] = $objDelivery->getDeliveryList($query_part);
        $data['bank'] = $objDelivery->getDeliveryBankList();
		$data['model'] = $objDelivery->getDeliveryModelList();
        $data['attributes'] = $this->getDeliveryListAttributes(NULL, $request);

		return view('tmc.delivery.delivery_list')->with('DI', $data);

    }

    public function deliveryReportProcess(Request $request){

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Delivery No');
		$sheet->setCellValue('B1', 'Delivery Date');
		$sheet->setCellValue('C1', 'Bank');
		$sheet->setCellValue('D1', 'Invoice No.');
		$sheet->setCellValue('E1', 'Sales Order No.');
		$sheet->setCellValue('F1', 'Cancel');
		$sheet->setCellValue('G1', 'Model');
		$sheet->setCellValue('H1', 'Serial Number');

        /*  Heading */

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

        $border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);

		$sheet->getStyle('A1:H1')->applyFromArray($style);
		$sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$spreadsheet->getActiveSheet()->getStyle('A1:H1')->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A1:H1')->applyFromArray($style);


        $objDelivery = new Delivery();

        $icount = 2;
        $result = $objDelivery->getDeliveryReport($request->delivery_no, $request->model);
        foreach($result as $row){

            $sheet->setCellValue('A' . $icount, $row->DocNo);
			$sheet->setCellValue('B' . $icount, $row->Deliver_Date);
			$sheet->setCellValue('C' . $icount, $row->BankCode);
			$sheet->setCellValue('D' . $icount, $row->invoiceNo);
			$sheet->setCellValue('E' . $icount, $row->salesorderNo);
			$sheet->setCellValue('F' . $icount, $row->Cancel);
			$sheet->setCellValue('G' . $icount, $row->ItemCode);
			$sheet->setCellValue('H' . $icount, $row->serial);

            $icount++;
        }

        $style= array(
			'font'  => array(
					'bold'  => false,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

        $sheet->getStyle('A2:H' . $icount)->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle('A2:H' . $icount)->getBorders()->applyFromArray($border_styleArray);

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
		$filename = 'Delivery_Report';

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

}
