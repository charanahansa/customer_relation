<?php

namespace App\Http\Controllers\Tmc\Backup\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\User;
use App\Models\Master\Bank;
use App\Models\Master\SubStatus;
use App\Models\Master\TerminalModel;
use App\Models\Master\Status;
use App\Models\Tmc\Backup\BackupProcess;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BackupLocationReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
        $objBackupProcess = new BackupProcess();

		$data['report_table'] = $objBackupProcess->getBackupLog();
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
		$data['sub_status'] = $objSubStatus->getAllBackupRemoveSubStatus();
		$data['status'] = $objStatus->getBackupRemoveStatus();

		return view('tmc.backup.report.backup_location_report')->with('BLR', $data);
	}

	public function backup_location_report_process(Request $request){

        $input = $request->input();
		$query_part = '';

        if( $input['serial_number'] != 0 ){

			$query_part .= " && ( (brn.backup_serialno = '". $input['serial_number'] . "') || (brn.replaced_serialno = '". $input['serial_number'] . "')  ) ";
		}

		if( $input['tid'] != 0 ){

			$query_part .= " && brn.tid = '". $input['tid'] . "' ";
		}

		if( $input['bank'] != 0 ){

			$query_part .= " && brn.bank = '". $input['bank'] . "' ";
		}

        if( $input['location'] != 0 ){

			$query_part .= " && ( (brn.backup_serialno = '". $input['serial_number'] . "') || (brn.replaced_serialno = '". $input['serial_number'] . "')  ) ";
		}

		if( $input['model'] != 0 ){

			$query_part .= " && ( (brn.backup_model = '". $input['model'] . "') || (brn.replaced_model = '". $input['model'] . "')  ) ";
		}

		if( $input['officer'] != 0 ){

			$query_part .= " && brn.officer = '". $input['officer'] . "' ";
		}

        $objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
        $objBackupProcess = new BackupProcess();

		$data['report_table'] =$objBackupProcess->getBackupLog();

        if( $request->submit == 'Inquire' ){

            $data['model'] = $objModel->get_models();
            $data['bank'] = $objBank->get_bank();
            $data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
            $data['sub_status'] = $objSubStatus->getAllBackupRemoveSubStatus();
            $data['status'] = $objStatus->getBackupRemoveStatus();

            return view('tmc.backup.report.backup_location_report')->with('BLR', $data);
        }

        if( $request->submit == 'Excell' ){


            $this->prepareExcellSheet($data['report_table']);
        }

	}

    private function prepareExcellSheet($resultset){

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A'.'1', 'Serial No.');
		$sheet->setCellValue('B'.'1', 'Model');
        $sheet->setCellValue('C'.'1', 'Active');
		$sheet->setCellValue('D'.'1', 'Bank');
		$sheet->setCellValue('E'.'1', 'Tid');
        $sheet->setCellValue('F'.'1', 'Location');
		$sheet->setCellValue('G'.'1', 'Officer');
		$sheet->setCellValue('H'.'1', 'Workflow');
		$sheet->setCellValue('I'.'1', 'Ticket No.');
		$sheet->setCellValue('J'.'1', 'Date');

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

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:'.'J1')->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('A1:'.'J1')->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A1:'.'J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$icount = 2;
		foreach($resultset as $row){

            $sheet->setCellValue('A'.$icount, $row->serialno);
            $sheet->setCellValue('B'.$icount, $row->model);
            $sheet->setCellValue('C'.$icount, $row->active);
            $sheet->setCellValue('D'.$icount, $row->bank);
            $sheet->setCellValue('E'.$icount, $row->tid);
            $sheet->setCellValue('F'.$icount, $row->location);
            $sheet->setCellValue('G'.$icount, $row->officer_id);
            $sheet->setCellValue('H'.$icount, $row->workflow_id);
            $sheet->setCellValue('I'.$icount, $row->workflow_ref_no);
            $sheet->setCellValue('J'.$icount, $row->workflow_ref_date);

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

		$spreadsheet->getActiveSheet()->getStyle('A2:'.'J' . $icount)->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A2:'.'J' . $icount)->applyFromArray($style);

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
		$filename = 'Backup_Location_Report';

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output'); 		// Download file

    }

}
