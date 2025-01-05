<?php

namespace App\Http\Controllers\Hardware\Operation\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Master\HardwareFault;
use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\Operation\HardwareServices;
use App\Models\Hardware\Operation\HardwareStatus;
use App\Models\Hardware\Operation\JobCardProcess;
use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JobcardReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
        $objModel = new TerminalModel();
        $objHardwareFault = new HardwareFault();
        $objSparePart = new SparePartProcess();
        $objHardwareServices = new HardwareServices();
		$objHardwareStatus = new HardwareStatus();

		$data['bank'] = $objBank->get_bank();
		$data['model'] = $objModel->get_models();
        $data['fault'] = $objHardwareFault->get_faults();
        $data['spare_part'] = $objSparePart->get_spare_part_active_list();
        $data['services'] = $objHardwareServices->get_service_list();
		$data['status'] = $objHardwareStatus->get_status_list();

        return view('hardware.operation.report.jobcard_report')->with('JR', $data);
    }

    public function jobcard_report_process(Request $request){

        $objJobcardProcess = new JobcardProcess();

		$input = $request->input();
		$filtering_enable = array();

		/* ----------------------------------------------------- Filtering Process ------------------------------------------------------- */

		//Serial No.
		$serial_no_filter = '';
		if( isset($input['from_serialno']) && isset($input['to_serialno']) ){

			$serial_no_filter = " && serialno between '". $input['from_serialno'] ."' and '". $input['to_serialno'] ."'  ";
		}

        //Jobcard No.
		$jobcard_number_filter = '';
		if( isset($input['from_jobcard_no']) && isset($input['to_jobcard_no']) ){

			$jobcard_number_filter = " && t.jobcard_no between '". $input['from_jobcard_no'] ."' and '". $input['to_jobcard_no'] ."'  ";
		}

		// Prod No. Filter
		$prod_number_filter = '';
		if(isset($input['prod_number'])){

			$prod_number_filter = " && t.prod_no = '". $input['prod_number'] ."' ";
		}

		// PTID Filter
		$ptid_filter = '';
		if(isset($input['ptid'])){

			$ptid_filter = " && t.ptid = '". $input['ptid'] ."' ";
		}

		// Revision Number Filter
		$revision_number_filter = '';
		if(isset($input['revision_number'])){

			$revision_number_filter = " && t.rev = '". $input['revision_number'] ."' ";
		}

		// User Negligant
		$user_negligent_filter = '';
		if($input['user_negligent'] == 1){

			$user_negligent_filter = " && t.userneg = '". $input['user_negligent'] ."' ";
		}

		// Epic Warranty
		$epic_warranty_filter = '';
		if($input['epic_warranty'] == 1){

			$epic_warranty_filter = " && t.within_e_warranty = '". $input['epic_warranty'] ."' ";
		}

		// Seller Warranty
		$seller_warranty_filter = '';
		if($input['seller_warranty'] == 1){

			$seller_warranty_filter = " && t.within_s_warranty = '". $input['seller_warranty'] ."' ";
		}

		// Quotation
		$quotation_filter = '';
		if($input['quotation'] == 1){

			$quotation_filter = " && q.QT_NO is not null ";
		}

		// Bank
		$bank_filter = '';
		if(isset($input['bank'])){

			$bank_array = $input['bank'];
			$bank_list = '';
			foreach($bank_array as $bank){

				$bank_list .= "'" . $bank . "', ";
			}
			$bank_list = substr(rtrim($bank_list),0,-1);
			$bank_filter = " && t.bank in (". $bank_list .") ";

		}

		// Model
		$model_filter = '';
		if(isset($input['model'])){

			$model_array = $input['model'];
			$model_list = '';
			foreach($model_array as $model){

				$model_list .= "'" . $model . "', ";
			}
			$model_list = substr(rtrim($model_list),0,-1);
			$model_filter = " && t.model in (". $model_list .") ";
		}

		// Services
		$services_filter = '';
		if(isset($input['services'])){

			$services_array = $input['services'];
			$services_list = '';
			foreach($services_array as $services){

				$services_list .= "'" . $services . "', ";
			}
			$services_list = substr(rtrim($services_list),0,-1);
			$services_filter = " && rs.service_id in (". $services_list .") ";
		}

		// Status
		$status_filter = '';
		if(isset($input['status'])){

			$status_array = $input['status'];
			$status_list = '';
			foreach($status_array as $status){

				$status_list .= "'" . $status . "', ";
			}
			$status_list = substr(rtrim($status_list),0,-1);
			$status_filter = " && t.status in (". $status_list .") ";
		}

		// Fault
		$fault_filter = '';
		$tmp_fault_filter = '';
		$filtering_enable['fault'] = FALSE;
		if(isset($input['fault'])){

			$fault_array = $input['fault'];
			$fault_list = '';
			foreach($fault_array as $fault){

				$fault_list .= "". $fault . ", ";
			}
			$fault_list = substr(rtrim($fault_list),0,-1);
			$fault_filter = " && f.Fault_id in (". $fault_list .") ";
			$tmp_fault_filter = " && fault_id in (". $fault_list .") ";
			$filtering_enable['fault'] = TRUE;
		}

		// Spare Part
		$spare_part_filter = '';
		$tmp_spare_part_filter = '';
		$filtering_enable['spare_part'] = FALSE;
		if(isset($input['spare_part'])){

			$spare_part_array = $input['spare_part'];
			$spare_part_list = '';
			foreach($spare_part_array as $spare_part){

				$spare_part_list .= "'" . $spare_part . "', ";
			}
			$spare_part_list = substr(rtrim($spare_part_list),0,-1);
			$spare_part_filter = " && ap.Part_ID in (". $spare_part_list .") ";
			$tmp_spare_part_filter = " && ap_id in (". $spare_part_list .")";
			$filtering_enable['spare_part'] = TRUE;
		}

		// Spare Part Usage
		$spare_part_usage_filter = '';
		$tmp_spare_part_usage_filter = '';
		$filtering_enable['spare_part_usage'] = FALSE;
		if(isset($input['spare_part_usage'])){

			$spare_part_usage_array = $input['spare_part_usage'];
			$spare_part_usage_list = '';
			foreach($spare_part_usage_array as $spare_part_usage){

				$spare_part_usage_list .= "'" . $spare_part_usage . "', ";
			}
			$spare_part_usage_list = substr(rtrim($spare_part_usage_list),0,-1);
			$spare_part_usage_filter = " && spare_part_id in (". $spare_part_usage_list .") ";
			$tmp_spare_part_usage_filter = " && rp_id in (". $spare_part_usage_list .") ";
			$filtering_enable['spare_part_usage'] = TRUE;
		}

		//Remove Spare Part
		$spare_part_removed_filter = '';
		$tmp_spare_part_removed_filter = '';
		$filtering_enable['spare_part_removed'] = FALSE;
		if(isset($input['spare_part_removed'])){

			$spare_part_removed_array = $input['spare_part_removed'];
			$spare_part_removed_list = '';
			foreach($spare_part_removed_array as $spare_part_removed){

				$spare_part_removed_list .= "'" . $spare_part_removed . "', ";
			}
			$spare_part_removed_list = substr(rtrim($spare_part_removed_list),0,-1);
			$spare_part_removed_filter = " && r.Part_ID in (". $spare_part_removed_list .") ";
			$tmp_spare_part_removed_filter = " && rm_part_id in (". $spare_part_removed_list .") ";
			$filtering_enable['spare_part_removed'] = TRUE;
		}

		$main_filter = $jobcard_number_filter . $serial_no_filter;
		$main_filter .= $prod_number_filter . $ptid_filter . $revision_number_filter . $user_negligent_filter;
		$main_filter .= $epic_warranty_filter . $seller_warranty_filter . $quotation_filter;
		$main_filter .= $bank_filter . $model_filter . $services_filter . $status_filter;

		$list_filter = $tmp_fault_filter . $tmp_spare_part_filter . $tmp_spare_part_usage_filter . $tmp_spare_part_removed_filter;

		/* ----------------------------------------------------- Data Saving Process ------------------------------------------------------- */

		$objJobcardProcess->create_tmp_table();

		$main_resultset = $objJobcardProcess->get_jobcard_detail_report($input['from_date'], $input['to_date'], $main_filter);
		foreach($main_resultset as $row){

			// Get Faults
			$fault_resultset = $objJobcardProcess->get_jobcard_fault_detail($row->jobcard_no, $fault_filter);
			$fault_count = count($fault_resultset);

			// Get Add Spare Part Details
			$spare_part_resultset = $objJobcardProcess->get_jobcard_add_spare_part_detail($row->jobcard_no, $spare_part_filter);
			$spare_part_count = count($spare_part_resultset);

			// Get Spare Part Usage Detail
			$spare_part_usage_resultset = $objJobcardProcess->get_jobcard_spare_part_usage_detail($row->jobcard_no, $spare_part_usage_filter);
			$spare_part_usage_count = count($spare_part_usage_resultset);

			// Get Spare Part Removed Detail
			$removed_spare_part_resultset = $objJobcardProcess->get_jobcard_removed_spare_part_detail($row->jobcard_no, $spare_part_removed_filter);
			$removed_spare_part_count = count($removed_spare_part_resultset);

			$max_count = max(array($fault_count, $spare_part_count, $spare_part_usage_count, $removed_spare_part_count));

			//echo 'JobCard No. :- ' . $row->jobcard_no . ' , max count :- ' . $max_count . '<br>';


			for ($i=1; $i <= $max_count; $i++) {

				$arr = array();

				$arr['jobcard_no'] = $row->jobcard_no;
				$arr['order_no'] = $i;

				$objJobcardProcess->insert_tmp_jobcard_report_table($arr);

				if($i == 1){

					$arr['jobcard_date'] = $row->tmc_jc_date;
					$arr['bank'] = $row->bank;
					$arr['serialno'] = $row->serialno;
					$arr['model'] = $row->model;
					$arr['accepted_officer'] = $row->accepted_officer;
					$arr['user_negligent'] = $row->userneg;
					$arr['prod_no'] = $row->prod_no;
					$arr['ptid'] = $row->ptid;
					$arr['revision_no'] = $row->rev;
					$arr['service_name'] = $row->service_name;
					$arr['chargeable_state'] = $row->chargeable_state;
					$arr['service_remark'] = $row->service_remark;
					$arr['service_add_by'] = $row->service_add_by;
					$arr['service_add_on'] = $row->service_add_on;
					$arr['within_e_warranty'] = $row->within_e_warranty;
					$arr['within_s_warranty'] = $row->within_s_warranty;
					$arr['status_name'] = $row->status_name;

					$arr['qt_no'] = $row->QT_NO;
					$arr['qt_date'] = $row->QT_DATE;
					$arr['qt_amount'] = $row->PRICE;

					$arr['released'] = $row->Released;
					$arr['released_by'] = $row->released_by;
					$arr['released_on'] = $row->Released_Date;
					$arr['released_to'] = $row->R_To;
					$arr['released_remark'] = $row->released_remark;

					$objJobcardProcess->update_tmp_jobcard_report_table($arr, 1);
				}

				unset($arr);
			}

			// Add Faults
			$icount = 1;
			foreach($fault_resultset as $rowFault){

				$fault_array = array();

				$fault_array['jobcard_no'] = $row->jobcard_no;
				$fault_array['fault_id'] = $rowFault->Fault_id;
				$fault_array['fault_name'] = $rowFault->Fault_Name;
				$fault_array['fault_remark'] = $rowFault->remark;
				$fault_array['fault_add_by'] = $rowFault->name;
				$fault_array['fault_add_on'] = $rowFault->Saved_Date;

				$objJobcardProcess->update_tmp_jobcard_report_table($fault_array, $icount);
				$icount++;
				unset($fault_array);
			}

			// Add Spare Parts
			$icount = 1;
			foreach($spare_part_resultset as $rowSparePart){

				$spare_part_array = array();

				$spare_part_array['jobcard_no'] = $row->jobcard_no;
				$spare_part_array['ap_id'] = $rowSparePart->Part_ID;
				$spare_part_array['ap_name'] = $rowSparePart->part_name;
				$spare_part_array['ap_chargeable_state'] = $rowSparePart->chargeable_state;
				$spare_part_array['ap_remark'] = $rowSparePart->remark;
				$spare_part_array['ap_reason'] = $rowSparePart->reason;
				$spare_part_array['ap_add_by'] = $rowSparePart->name;
				$spare_part_array['ap_add_on'] = $rowSparePart->Saved_Date;

				$objJobcardProcess->update_tmp_jobcard_report_table($spare_part_array, $icount);
				$icount++;
				unset($spare_part_array);
			}

			//Spare Part Usage
			$icount = 1;
			foreach($spare_part_usage_resultset as $rowSpUsage){

				$spare_part_usage_array = array();

				$spare_part_usage_array['jobcard_no'] = $row->jobcard_no;
				$spare_part_usage_array['rp_id'] = $rowSpUsage->spare_part_id;
				$spare_part_usage_array['rp_name'] = $rowSpUsage->spare_part_name;
				$spare_part_usage_array['rp_issue'] = $rowSpUsage->issue;

				$objJobcardProcess->update_tmp_jobcard_report_table($spare_part_usage_array, $icount);
				$icount++;
				unset($spare_part_usage_array);
			}

			//Remove Spare Part
			$icount = 1;
			foreach($removed_spare_part_resultset as $rowRmSP){

				$spare_part_usage_array = array();

				$spare_part_usage_array['jobcard_no'] = $row->jobcard_no;
				$spare_part_usage_array['rm_part_id'] = $rowRmSP->Part_ID;
				$spare_part_usage_array['rm_part_name'] = $rowRmSP->part_name;
				$spare_part_usage_array['removed_by'] = $rowRmSP->name;
				$spare_part_usage_array['removed_on'] = $rowRmSP->Saved_Date;

				$objJobcardProcess->update_tmp_jobcard_report_table($spare_part_usage_array, $icount);
				$icount++;
				unset($spare_part_usage_array);
			}
		}

		//echo $list_filter;

		/* ----------------------------------------------------- Prepare Excel Sheet ------------------------------------------------------- */

		$resultset = $objJobcardProcess->get_tmp_jobcard_report_table($list_filter);

		$this->genarate_excel_file($resultset, $filtering_enable);

	}

	private function genarate_excel_file($resultset, $filtering_enable){

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'JobCard No');
		$sheet->setCellValue('B1', 'Date');
		$sheet->setCellValue('C1', 'Bank');
		$sheet->setCellValue('D1', 'Serial No.');
		$sheet->setCellValue('E1', 'Model');
		$sheet->setCellValue('F1', 'Accepted Officer');
		$sheet->setCellValue('G1', 'User Negligent');
		$sheet->setCellValue('H1', 'Prod No.');
		$sheet->setCellValue('I1', 'Ptid');
		$sheet->setCellValue('J1', 'Rev No.');

		$sheet->setCellValue('K1', 'Service');
		$sheet->setCellValue('L1', 'Chargeable State');
		$sheet->setCellValue('M1', 'Service Remark');
		$sheet->setCellValue('N1', 'Service Add By');
		$sheet->setCellValue('O1', 'Service Add On');

		$sheet->setCellValue('P1', 'Within Epic Warranty');
		$sheet->setCellValue('Q1', 'Within Seller Warranty');
		$sheet->setCellValue('R1', 'Status');

		$sheet->setCellValue('S1', 'Qt No.');
		$sheet->setCellValue('T1', 'Qt Date');
		$sheet->setCellValue('U1', 'Qt Amount');

		$sheet->setCellValue('V1', 'Released');
		$sheet->setCellValue('W1', 'Released Date');
		$sheet->setCellValue('X1', 'Released Officer');
		$sheet->setCellValue('Y1', 'Released To');
		$sheet->setCellValue('Z1', 'Released Remark');

		$FE = $filtering_enable;
		if( ($FE['fault'] == FALSE) && ($FE['spare_part'] == FALSE) && ($FE['spare_part_usage'] == FALSE) && ($FE['spare_part_removed'] == FALSE) ){

			$sheet->setCellValue('AA1', 'Fault');
			$sheet->setCellValue('AB1', 'Fault Remark');
			$sheet->setCellValue('AC1', 'Add By');
			$sheet->setCellValue('AD1', 'Add On');

			$sheet->setCellValue('AE1', 'Add Part');
			$sheet->setCellValue('AF1', 'Chargeable Status');
			$sheet->setCellValue('AG1', 'Add Remark');
			$sheet->setCellValue('AH1', 'Add Reason');
			$sheet->setCellValue('AI1', 'Add By');
			$sheet->setCellValue('AJ1', 'Add On');

			$sheet->setCellValue('AK1', 'Requested Part');
			$sheet->setCellValue('AL1', 'Part Issue');

			$sheet->setCellValue('AM1', 'Removed Spare Part');
			$sheet->setCellValue('AN1', 'Removed By');
			$sheet->setCellValue('AO1', 'Removed On');

			$col_count = 41;

		}else{

			$col_count = 27;

			if($filtering_enable['fault'] == TRUE){

				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Fault');$col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Fault Remark');$col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add By');$col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add On');$col_count++;

			}

			if($filtering_enable['spare_part'] == TRUE){

				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add Part'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Chargeable Status'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add Remark'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add Reason'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add By'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Add On'); $col_count++;
			}

			if($filtering_enable['spare_part_usage'] == TRUE){

				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Requested Part'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Part Issue'); $col_count++;
			}

			if($filtering_enable['spare_part_removed'] == TRUE){

				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Removed Spare Part'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Removed By'); $col_count++;
				$sheet->setCellValue($this->getExcelColumn($col_count) . 1, 'Removed On'); $col_count++;
			}


		}

		/*  Heading */

		$last_heading_cell_address = $this->getExcelColumn($col_count) . '1';

		//set up the style in an array
		$style= array(
			'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Consolas'
				)
		);

		//apply the style on column A row 1 to Column B row 1
		$sheet->getStyle('A1:'. $last_heading_cell_address)->applyFromArray($style);
		$sheet->getStyle('A1:'. $last_heading_cell_address)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('49fc03');

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:'. $last_heading_cell_address)->getBorders()->applyFromArray($border_styleArray);

		// echo '1 :- ' . $filtering_enable['fault'] . '<br>';
		// echo '2 :- ' . $filtering_enable['spare_part'] . '<br>';
		// echo '3 :- ' . $filtering_enable['spare_part_usage'] . '<br>';
		// echo '4 :- ' . $filtering_enable['spare_part_removed'] . '<br>';

		$icount = 2;
		foreach($resultset as $row){

			$sheet->setCellValue('A' . $icount, $row->jobcard_no);
			$sheet->setCellValue('B' . $icount, $row->jobcard_date);
			$sheet->setCellValue('C' . $icount, $row->bank);
            $sheet->setCellValue('D' . $icount, $row->serialno);
            $sheet->setCellValue('E' . $icount, $row->model);
            $sheet->setCellValue('F' . $icount, $row->accepted_officer);
			$sheet->setCellValue('G' . $icount, $this->YesNo($row->user_negligent));
			$sheet->setCellValue('H' . $icount, $row->prod_no);
			$sheet->setCellValue('I' . $icount, $row->ptid);
			$sheet->setCellValue('J' . $icount, $row->revision_no);

			$sheet->setCellValue('K' . $icount, $row->service_name);
			$sheet->setCellValue('L' . $icount, $this->YesNo($row->chargeable_state));
			$sheet->setCellValue('M' . $icount, $row->service_remark);
			$sheet->setCellValue('N' . $icount, $row->service_add_by);
			$sheet->setCellValue('O' . $icount, $row->service_add_on);

			$sheet->setCellValue('P' . $icount, $this->YesNo($row->within_e_warranty));
			$sheet->setCellValue('Q' . $icount, $this->YesNo($row->within_s_warranty));
			$sheet->setCellValue('R' . $icount, $row->status_name);

			$sheet->setCellValue('S' . $icount, $row->qt_no);
			$sheet->setCellValue('T' . $icount, $row->qt_date);
			$sheet->setCellValue('U' . $icount, $row->qt_amount);

			$sheet->setCellValue('V' . $icount, $this->YesNo($row->released));
			$sheet->setCellValue('W' . $icount, $row->released_on);
			$sheet->setCellValue('X' . $icount, $row->released_by);
			$sheet->setCellValue('Y' . $icount, $row->released_to);
			$sheet->setCellValue('Z' . $icount, $row->released_remark);

			$FE = $filtering_enable;
			if( ($FE['fault'] == FALSE) && ($FE['spare_part'] == FALSE) && ($FE['spare_part_usage'] == FALSE) && ($FE['spare_part_removed'] == FALSE) ){

				$sheet->setCellValue('AA' . $icount, $row->fault_name);
				$sheet->setCellValue('AB' . $icount, $row->fault_remark);
				$sheet->setCellValue('AC' . $icount, $row->fault_add_by);
				$sheet->setCellValue('AD' . $icount, $row->fault_add_on);

				$sheet->setCellValue('AE' . $icount, $row->ap_name);
				$sheet->setCellValue('AF' . $icount, $this->YesNo($row->chargeable_state));
				$sheet->setCellValue('AG' . $icount, $row->ap_remark);
				$sheet->setCellValue('AH' . $icount, $row->ap_reason);
				$sheet->setCellValue('AI' . $icount, $row->ap_add_by);
				$sheet->setCellValue('AJ' . $icount, $row->ap_add_on);

				$sheet->setCellValue('AK' . $icount, $row->rp_name);
				$sheet->setCellValue('AL' . $icount, $this->YesNo($row->rp_issue));

				$sheet->setCellValue('AM' . $icount, $row->rm_part_name);
				$sheet->setCellValue('AN' . $icount, $row->removed_by);
				$sheet->setCellValue('AO' . $icount, $row->removed_on);

			}else{

				$col_count = 27;

				if($filtering_enable['fault'] == TRUE){

					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fault_name); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fault_remark); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fault_add_by); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->fault_add_on); $col_count++;
				}

				if($filtering_enable['spare_part'] == TRUE){

					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ap_name); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->chargeable_state)); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ap_remark); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ap_reason); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ap_add_by); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->ap_add_on); $col_count++;
				}

				if($filtering_enable['spare_part_usage'] == TRUE){

					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->rp_name); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $this->YesNo($row->rp_issue)); $col_count++;
				}

				if($filtering_enable['spare_part_removed'] == TRUE){

					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->rm_part_name); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->removed_by); $col_count++;
					$sheet->setCellValue($this->getExcelColumn($col_count) . $icount, $row->removed_on); $col_count++;
				}
			}

			if($row->jobcard_date == ''){

				$sheet->setCellValue('A' . $icount, '');
				$sheet->setCellValue('G' . $icount, '');
				$sheet->setCellValue('L' . $icount, '');
				$sheet->setCellValue('P' . $icount, '');
				$sheet->setCellValue('Q' . $icount, '');
				$sheet->setCellValue('V' . $icount, '');
				$sheet->setCellValue('AF' . $icount, '');
				$sheet->setCellValue('AL' . $icount, '');
			}

			$icount++;
		}

		$last_heading_cell_address = $this->getExcelColumn($col_count);

		$sheet->getStyle('A:T')->getAlignment()->setHorizontal('left');
		$sheet->getStyle('U:U')->getAlignment()->setHorizontal('right');
		$sheet->getStyle('V:' . $last_heading_cell_address)->getAlignment()->setHorizontal('left');

		$sheet->getStyle('U:U')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

		$border_styleArray =array(
			'allBorders' => array(
				'borderStyle' => Border::BORDER_THIN,
				'color' => array( 'rgb' => '#FF0003')
			),
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:'. $last_heading_cell_address . $icount)->getBorders()->applyFromArray($border_styleArray);

		$style= array(
			'font'  => array(
					'bold'  => false,
					'size'  => 9,
					'name'  => 'Consolas'
				)
		);
		$sheet->getStyle('A2:'. $last_heading_cell_address . $icount)->applyFromArray($style);

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
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AF')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AG')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AH')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AI')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AJ')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AK')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AL')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AM')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AN')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AO')->setAutoSize(true);

		$writer = new Xlsx($spreadsheet);
		$filename = 'JobCard_Report';

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output'); // download file
	}

	private function getExcelColumn($number){

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

	private function YesNo($result){

		if($result == 1){

			return 'Yes';
		}

		if($result == 0){

			return 'No';
		}

		return '0';
	}




}
