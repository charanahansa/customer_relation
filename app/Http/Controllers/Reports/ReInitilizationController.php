<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reports\ReInitilization;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReInitilizationController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objReInitilization = new ReInitilization();

		$data['bank'] = $objReInitilization->get_bank();
		$data['model'] = $objReInitilization->get_models();
		$data['officers'] = $objReInitilization->get_officers();
		$data['sub_status'] = $objReInitilization->get_sub_status();
		$data['status'] = $objReInitilization->get_status();

        return view('reports.re_initilization_report')->with('New', $data);
    }

    public function re_initilization_report_process(Request $request){

		$objReInitilization = new ReInitilization();

		$input = $request->input();

		$tid_filter = '';
		$serialno_filter = '';
		$bank_filter = '';
		$model_filter = '';
		$officer_filter = '';
		$sub_status_filter = '';
		$status_filter = '';


		// Tid
		if( isset($input['from_tid']) && isset($input['to_tid']) ){

			$tid_filter = " && b.tid between '". $input['from_tid'] ."' and '". $input['to_tid'] ."'  ";
		}

		// Serial No.
		if( isset($input['from_serialno']) && isset($input['to_serialno']) ){

			$serialno_filter = " && b.serialno between '". $input['from_serialno'] ."' and '". $input['to_serialno'] ."'  ";
		}

		// Bank
		if(isset($input['bank'])){

			$bank_array = $input['bank'];
			$bank_list = '';
			foreach($bank_array as $bank){

				$bank_list .= "'" . $bank . "', ";
			}
			$bank_list = substr(rtrim($bank_list),0,-1);
			$bank_filter = " && b.bank in (". $bank_list .") ";

		}

		// Model
		if(isset($input['model'])){

			$model_array = $input['model'];
			$model_list = '';
			foreach($model_array as $model){

				$model_list .= "'" . $model . "', ";
			}
			$model_list = substr(rtrim($model_list),0,-1);
			$model_filter = " && b.model in (". $model_list .") ";
		}

		// Officers
		if(isset($input['officer'])){

			$officer_array = $input['officer'];
			$officer_list = '';
			foreach($officer_array as $officer){

				$officer_list .= "'" . $officer . "', ";
			}
			$officer_list = substr(rtrim($officer_list),0,-1);
			$officer_filter = " && b.officer in (". $officer_list .") ";
		}

		// Sub Status
		if(isset($input['sub_status'])){

			$sub_status_array = $input['sub_status'];
			$sub_status_list = '';
			foreach($sub_status_array as $sub_status){

				$sub_status_list .= "'" . $sub_status . "', ";
			}
			$sub_status_list = substr(rtrim($sub_status_list),0,-1);
			$sub_status_filter = " && b.sub_status in (". $sub_status_list .") ";
		}

		// Status
		if(isset($input['status'])){

			$status_array = $input['status'];
			$status_list = '';
			foreach($status_array as $status){

				$status_list .= "'" . $status . "', ";
			}
			$status_list = substr(rtrim($status_list),0,-1);
			$status_filter = " && b.status in (". $status_list .") ";
		}

		// Tables
		$tables = $input['tables'];

		$total_filter = $tid_filter . $serialno_filter . $bank_filter . $model_filter;
		$total_filter .= $officer_filter . $sub_status_filter . $status_filter;

		$result = $objReInitilization->get_report($input['from_date'], $input['to_date'], $total_filter, $tables);

		$this->genarate_excel_file($result, $tables);

	}

	private function genarate_excel_file($resultset, $tables){

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$col_count = 1;

		$sheet->mergeCells('A1:D1');
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Ticket No'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Date'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Bank'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Tid'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Merchant'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Model'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Serial No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Contact No.'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Contact Person'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Reasons'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Courier'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Fs Officer'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Bank Officer'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Remark'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Sub Status'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Status'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Done Date Time'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Email'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Email_On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Cancel'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Cancel Reason'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Cancel On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Cancel By'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Saved On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Saved By'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Last Edit On'); $col_count++;
		$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'Last Edit By'); $col_count++;

		// Field Service Team Lead
		if(in_array("re_initialization_ftl_view", $tables)){

			//$col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Date'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Contact No');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Officer');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Courier');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Contactno');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Bank Officer Name');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Remark');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Sub Status');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Status');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Done Date Time');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Email');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Email On');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Saved By');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Saved On');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Last_edit By');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FTL Last_edit On');  $col_count++;

		}

		// Terminal Management Coordinator
		if(in_array("re_initialization_tmc_view", $tables)){

			//$col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Date'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Contact No'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Contact Person'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Model'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Serial No.'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Merchant'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Pod No.'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Officer'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Courier'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Collect From Courier'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Remark'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Sub Status'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Saved By'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Saved On'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Last_edit By'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TMC Last_edit On'); $col_count++;

		}

		// Terminal Programmer
		if(in_array("re_initialization_tp_view", $tables)){

			//$col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Date'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Contact No'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Contact Person'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Model'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Serial No.'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Sim No.'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Pod No.'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Remark'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Officer'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Sub Status'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Saved By'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Saved On'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Last_edit By'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'TP Last_edit On'); $col_count++;

		}

		// Field Service Officer
		if(in_array("re_initialization_fs_view", $tables)){

			//$col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Date'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Model'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Serial No.'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Contact No'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Contact Person'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Remark'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Sub Status'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Status'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Done Date Time'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Email'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Email On'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Saved By'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Saved On'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Last_edit By'); $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . '2', 'FS Last_edit On');
		}



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
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tid);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->merchant);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->model);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->serialno);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->contactno);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->contact_person);  $col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, 'Reason');  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->courier);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->officer_name);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->bank_officer);  $col_count++;

			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->remark);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->sub_status);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->status));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->done_date_time);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->email));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->email_on);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->cancel));  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->cancel_reason);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->cancel_on);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->cancel_by);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->saved_on);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->saved_by);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->edit_by);  $col_count++;
			$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->edit_on);  $col_count++;

			// Field Service Team Lead
			if(in_array("re_initialization_ftl_view", $tables)){

				//$col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_date);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_contactno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_officer);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_courier);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_contactno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_bank_officer_name);  $col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_remark);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_sub_status);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->ftl_status));  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_done_date_time);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->ftl_email));  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_email_on);  $col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_saved_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_saved_on);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_last_edit_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ftl_last_edit_on);  $col_count++;

			}

			// Terminal Management Coordinator
			if(in_array("re_initialization_tmc_view", $tables)){

				//$col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_date);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_contactno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_contact_person);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_model);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_serialno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_merchant);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->pod_no);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_officer);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->courier);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->collect_from_courier));  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_remark);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_sub_status);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_saved_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_saved_on);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_edit_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tmc_edit_on);  $col_count++;

			}

			// Terminal Programmer
			if(in_array("re_initialization_tp_view", $tables)){

				//$col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_date);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_contactno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_contact_person);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_model);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_serialno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_sim_no);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_pod_no);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_remark);  $col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_officer);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_sub_status);  $col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_saved_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_saved_on);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_edit_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->tp_edit_on);  $col_count++;

			}

			// Field Service Officer
			if(in_array("re_initialization_fs_view", $tables)){

				//$col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_date);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_model);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_serialno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_contactno);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_contact_person);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_remark);  $col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_sub_status);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, ucfirst($row->fs_status));  $col_count++;
                $sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_done_date_time);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_email);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_email_on);  $col_count++;

				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_saved_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_saved_on);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_edit_by);  $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fs_edit_on);  $col_count++;
			}

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

		// Field Service Team Lead
		if(in_array("re_initialization_ftl_view", $tables)){

			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AM')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AN')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AO')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AP')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AQ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AR')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AS')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AT')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AU')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AV')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AW')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AX')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AY')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AZ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BB')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BC')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BE')->setAutoSize(true);
		}

		// Terminal Management Coordinator
		if(in_array("re_initialization_tmc_view", $tables)){

			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BG')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BH')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BI')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BJ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BK')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BL')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BM')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BN')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BO')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BP')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BQ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BR')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BS')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BT')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BU')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BV')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BW')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BX')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BY')->setAutoSize(true);
		}

		// Terminal Programmer
		if(in_array("re_initialization_tp_view", $tables)){

			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('BZ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CA')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CB')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CC')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CD')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CE')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CF')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CG')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CH')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CI')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CJ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CK')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CL')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CM')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CN')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CO')->setAutoSize(true);
		}

		// Field Service Officer
		if(in_array("re_initialization_fs_view", $tables)){

			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CP')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CQ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CR')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CS')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CT')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CU')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CV')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CW')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CX')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CY')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('CZ')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DA')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DB')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DC')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DD')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DE')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DF')->setAutoSize(true);
			$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('DG')->setAutoSize(true);

		}

		$writer = new Xlsx($spreadsheet);
		$filename = 'ReInitilization_Report';

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
