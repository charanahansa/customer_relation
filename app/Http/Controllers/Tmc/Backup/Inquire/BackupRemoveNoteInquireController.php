<?php

namespace App\Http\Controllers\Tmc\Backup\Inquire;

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

class BackupRemoveNoteInquireController extends Controller {

	public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();

		$data['report_table'] = array();
		$data['model'] = $objModel->get_models();
		$data['bank'] = $objBank->get_bank();
		$data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
		$data['sub_status'] = $objSubStatus->getAllBackupRemoveSubStatus();
		$data['status'] = $objStatus->getBackupRemoveStatus();

		return view('tmc.backup.inquire.backup_remove_note_inquire')->with('BRN', $data);
	}

	public function backupRemoveNoteInquireProcess(Request $request){

		$input = $request->input();
		$query_part = '';

		if( $input['bank'] != 0 ){

			$query_part .= " && brn.bank = '". $input['bank'] . "' ";
		}

		if( $input['model'] != 0 ){

			$query_part .= " && ( (brn.backup_model = '". $input['model'] . "') || (brn.replaced_model = '". $input['model'] . "')  ) ";
		}

		if( $input['officer'] != 0 ){

			$query_part .= " && brn.officer = '". $input['officer'] . "' ";
		}

		if( $input['status'] != "0" ){

			$query_part .= " && brn.status = '". $input['status'] . "' ";
		}

		if( $input['sub_status'] != 0 ){

			$query_part .= " && brn.sub_status = '". $input['sub_status'] . "' ";
		}

		if( $input['serial_number'] != 0 ){

			$query_part .= " && ( (brn.backup_serialno = '". $input['serial_number'] . "') || (brn.replaced_serialno = '". $input['serial_number'] . "')  ) ";
		}

		if( ($input['from_date'] != 0) && ($input['to_date'] != 0) ){

			$query_part = " && brn.brn_date between '". $input['from_date'] ."' and '". $input['to_date'] ."'  ";
		}

		if( $input['tid'] != 0 ){

			$query_part .= " && brn.tid = '". $input['tid'] . "' ";
		}

		$objUser = new User();
        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
		$objBackupProcess = new BackupProcess();

		$data['report_table'] =$objBackupProcess->getBackupInquireResult($query_part);

        if( $request->submit == 'Inquire' ){

            $data['model'] = $objModel->get_models();
            $data['bank'] = $objBank->get_bank();
            $data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
            $data['sub_status'] = $objSubStatus->getAllBackupRemoveSubStatus();
            $data['status'] = $objStatus->getBackupRemoveStatus();

            return view('tmc.backup.inquire.backup_remove_note_inquire')->with('BRN', $data);
        }

        if( $request->submit == 'Excell' ){


            $this->prepareExcellSheet($data['report_table']);
        }

	}

    private function prepareExcellSheet($resultset){

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$col_count = 1;

		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Ticket No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Date'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Bank'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Tid'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Merchant'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'BIN No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Backup Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Backup Model'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Replaced Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Replaced Model'); $col_count++;
        $sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Received Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Received Model'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Contact No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Contact Person'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Replacement'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Replacement Ref No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Fs Officer'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Courier'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Remark'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Sub Status'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Status'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Done Date Time'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Cancel'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Cancel Reason'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Cancel On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Cancel By'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Saved On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Saved By'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Last Edit On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '1', 'Last Edit By'); $col_count++;

        /*  Heading */

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		$last_heading_cell_address = $this->getExcelColumn($col_count) . '1';

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:' . $last_heading_cell_address)->applyFromArray($style);
		$sheet->getStyle('A1:' . $last_heading_cell_address)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

        $icount = 2;
		$column_count = 1;
        foreach($resultset as $row){

            $col_count = 1;

			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->brn_no);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->brn_date);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->bank);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tid);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->merchant);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->bin_no);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->backup_serialno);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->backup_model);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->replaced_serialno);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->replaced_model);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->contact_number);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->contact_person);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, '');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, '');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->officer_name);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->courier_name);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->sub_status);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->status));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->done_date_time);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->cancel));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->cancel_reason);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->cancel_on);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->cancel_by);  $col_count++;
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

		$spreadsheet->getActiveSheet()->getStyle('A1:'. $last_cell_address)->getBorders()->applyFromArray($border_styleArray);
		$sheet->getStyle('A1:'. $last_cell_address)->applyFromArray($style);

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
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AE')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AJ')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AK')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
		$filename = 'Backup_Remove_Inquire';

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
