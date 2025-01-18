<?php

namespace App\Http\Controllers\Field_Service\Field_Service_Allocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Operation\HelpNote;
use App\Models\FieldService\Breakdown;
use App\Models\FieldService\NewInstallation;
use App\Models\FieldService\ReInitialization;
use App\Models\FieldService\SoftwareUpdate;
use App\Models\FieldService\TerminalReplacement;
use App\Models\FieldService\BackupRemoveProcess;

use App\Models\Master\Fault;
use App\Models\Master\RelevantDetail;
use App\Models\Master\CourierProvider;
use App\Models\Master\ActionTaken;
use App\Models\Master\BankOfficer;
use App\Models\Master\SubStatus;
use App\Models\Master\Status;
use App\Models\Master\Workflow;
use App\Models\User;

use App\Rules\ZeroValidation;
use App\Rules\NotValidation;
use App\Rules\fsp\CourierValidation;
use App\Rules\fsp\SubStatusValidation;
use App\Rules\fsp\OfficerCourierValidation;
use App\Traits\ZoneName;

class FieldServiceAllocationController extends Controller {

    use ZoneName;

    public function getForm(Request $request){

        $objWorkflow = new Workflow();
        $objCourierProvider = new CourierProvider();
        $objBankOfficer = new BankOfficer();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
        $objUser = new User();

        $data['workflow'] = $objWorkflow->get_workflow();
        $data['officer'] = $objUser->getActiveFieldOfficers();
        $data['courier_provider'] = $objCourierProvider->getActiveCourierProviders();
        $data['bank_officer'] = $objBankOfficer->getBankOfficer($request->relevant_bank);

        if(  $request->workflow_id == 1){

			$objBreakdown = new Breakdown();
            $objFault = new Fault();
            $objRelevantDetail = new RelevantDetail();
            $objActionTaken = new ActionTaken();

            $data['fault'] = $objFault->getFaults();
            $data['relevant_detail'] = $objRelevantDetail->getRelevantDetail();
            $data['action_taken'] = $objActionTaken->getActionTaken();
			$data['field_service'] = $objBreakdown->getBreakdownFieldService($request->ticket_number);
			$data['field_fault'] = $objBreakdown->getBreakdownFieldFaults($request->ticket_number);
			$data['field_action_taken'] = $objBreakdown->getBreakdownFieldActionTaken($request->ticket_number);
			$data['field_spare_part'] = $objBreakdown->getBreakdownSparePartRequest($request->ticket_number);
			$data['history'] = $objBreakdown->getBreakdownHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getBreakdownSubStatus();

        }elseif( $request->workflow_id == 2){

            $objNewInstallation = new NewInstallation();

			$data['field_service'] = $objNewInstallation->getNewInstallationFieldService($request->ticket_number);
			$data['history'] = $objNewInstallation->getNewInstallationHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getNewInstallationSubStatus();

        }elseif( $request->workflow_id == 3){

            $objReInitialization = new ReInitialization();

			$data['field_service'] = $objReInitialization->getReInitializationFieldService('3205');
			$data['history'] = $objReInitialization->getReInitializationHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getReInitializationSubStatus();

        }elseif( $request->workflow_id == 4){

            $objSoftwareUpdate = new SoftwareUpdate();

			$data['field_service'] = $objSoftwareUpdate->getSoftwareUpdateFieldService('2538');
			$data['history'] = $objSoftwareUpdate->getSoftwareUpdateHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getSoftwareUpdateSubStatus();

        }elseif( $request->workflow_id == 5){

            $objTerminalReplacement = new TerminalReplacement();

			$data['field_service'] = $objTerminalReplacement->getTerminalReplacementFieldService('961');
			$data['history'] = $objTerminalReplacement->getTerminalReplacementHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getTerminalReplacementSubStatus();

        }elseif( $request->workflow_id == 6){

			$objBackupRemoveProcess = new BackupRemoveProcess();

			$data['field_service'] = $objBackupRemoveProcess->getBackupRemoveProcessFieldService('203');
			$data['history'] = $objBackupRemoveProcess->getBackupRemoveProcessHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getBackupRemoveSubStatus();
        }

		$process['ticket_no'] = $request->ticket_number;
        $process['workflow_id'] = $request->workflow_id;
        $data['zone_name'] = $this->getZoneName($request->workflow_id, $request->ticket_number);

        $data['status'] =  $objStatus->getBreakdownStatus();
        $data['attributes'] = $this->getFieldServiceAllocateAttributes($process, NULL);

        return view('fsp.field_service_allocate.fs_allocate')->with('FSA', $data);
    }

    private function getFieldServiceAllocateAttributes($process, $request){

        $attributes['ticket_no'] = '';
        $attributes['workflow_id'] = '';
        $attributes['workflow_name'] = '';
        $attributes['bank'] = '';
        $attributes['date'] = '';
        $attributes['tid'] = '';
        $attributes['model'] = '';
        $attributes['merchant'] = '';
        $attributes['fault_description'] = '';
        $attributes['relevant_detail_description'] = '';
        $attributes['cc_remark'] = '';
        $attributes['call_handle_by'] = '';

        $attributes['ftl_date'] = '';
        $attributes['contact_number'] = '';
        $attributes['fault'] = "Not";
        $attributes['relevant_detail'] = "Not";
        $attributes['ftl_remark'] = '';
        $attributes['officer'] = "Not";
        $attributes['courier_provider'] = "Not";
        $attributes['bank_officer'] = "Not";
        $attributes['action_taken'] = "Not";
        $attributes['pod_number'] = '';
        $attributes['pod_date'] = '';
        $attributes['sub_status'] = "Not";
        $attributes['status'] = '';
        $attributes['done'] = '';
        $attributes['done_date_time'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

        $objWorkflow = new Workflow();
        $objUser = new User();

        $attributes['ftl_date'] = Date('Y-m-d');

        if($process['workflow_id'] == 1){

            $objBreakdown = new Breakdown();
            $objFault = new Fault();
            $objRelevantDetail = new RelevantDetail();

            $result = $objBreakdown->getBreakdownDetailCC($process['ticket_no']);
            foreach($result as $row){

                $attributes['ticket_no'] = $row->ticketno;
                $attributes['workflow_id'] = $process['workflow_id'];
                $attributes['workflow_name'] =  $objWorkflow->getWorkflowName($process['workflow_id']);
                $attributes['bank'] = $row->bank;
                $attributes['date'] = $row->tdate;
                $attributes['tid'] = $row->tid;
                $attributes['model'] = $row->model;
                $attributes['merchant'] = $row->merchant;
                $attributes['fault_description'] = $objFault->getFaultDescription($row->error);
                $attributes['relevant_detail_description'] = $objRelevantDetail->getRelevantDetailDescription($row->relevant_detail);
                $attributes['cc_remark'] = $row->remark;
                $attributes['call_handle_by'] = $objUser->getOfficerName($row->call_handler);
                $attributes['contact_number'] = $row->contactno;

				$attributes['fault'] = $row->error;
				$attributes['sub_status'] = 37;
				$attributes['status'] =  $row->status;
            }

			$result = $objBreakdown->getBreakdownDetailFTL($process['ticket_no']);
			foreach($result as $row){

				$attributes['ftl_date'] = $row->tdate;
				$attributes['fault'] = $row->error;
				$attributes['relevant_detail'] = $row->relevant_detail;
				$attributes['ftl_remark'] = $row->remark;
				$attributes['officer'] = $row->officer;
				$attributes['courier_provider'] = $row->courier;
				$attributes['bank_officer'] = $row->bank_officer;
				$attributes['action_taken'] = $row->action_taken;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;
			}

            $result = $objBreakdown->getBreakdownDetailTmc($process['ticket_no']);
			foreach($result as $row){

				$attributes['pod_number'] = $row->pod_no;
        		$attributes['pod_date'] = $row->tdate;
			}

        }

		if($process['workflow_id'] == 2){

			$objNewInstallation = new NewInstallation();

            $result = $objNewInstallation->getNewInstallationDetail($process['ticket_no']);
            foreach($result as $row){

                $attributes['ticket_no'] = $row->ticketno;
                $attributes['workflow_id'] = $process['workflow_id'];
                $attributes['workflow_name'] =  $objWorkflow->getWorkflowName($process['workflow_id']);
                $attributes['bank'] = $row->bank;
                $attributes['date'] = $row->tdate;
                $attributes['tid'] = $row->tid;
                $attributes['model'] = $row->model;
                $attributes['merchant'] = $row->merchant;
                $attributes['cc_remark'] = $row->remark;
				$attributes['sub_status'] = 19;
				$attributes['status'] =  $row->status;
            }

			$result = $objNewInstallation->getNewInstallationDetailFTL($process['ticket_no']);
			foreach($result as $row){

				$attributes['ftl_date'] = $row->tdate;
				$attributes['contact_number'] = $row->contactno;
				$attributes['ftl_remark'] = $row->remark;
				$attributes['officer'] = $row->officer;
				$attributes['courier_provider'] = $row->courier;
				$attributes['bank_officer'] = $row->bank_officer;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;
			}

            $result = $objNewInstallation->getNewInstallationDetailTmc($process['ticket_no']);
			foreach($result as $row){

				$attributes['pod_number'] = $row->pod_no;
        		$attributes['pod_date'] = $row->tdate;
			}
        }

		if($process['workflow_id'] == 3){

			$objReInitialization = new ReInitialization();

            $result = $objReInitialization->getReInitializationDetail($process['ticket_no']);
            foreach($result as $row){

                $attributes['ticket_no'] = $row->ticketno;
                $attributes['workflow_id'] = $process['workflow_id'];
                $attributes['workflow_name'] =  $objWorkflow->getWorkflowName($process['workflow_id']);
                $attributes['bank'] = $row->bank;
                $attributes['date'] = $row->tdate;
                $attributes['tid'] = $row->tid;
                $attributes['model'] = $row->model;
                $attributes['merchant'] = $row->merchant;
                $attributes['cc_remark'] = $row->remark;

				$attributes['sub_status'] = 2;
				$attributes['status'] =  $row->status;
            }

			$result = $objReInitialization->getReInitializationDetailFTL($process['ticket_no']);
			foreach($result as $row){

				$attributes['ftl_date'] = $row->tdate;
				$attributes['contact_number'] = $row->contactno;
				$attributes['ftl_remark'] = $row->remark;
				$attributes['officer'] = $row->officer;
				$attributes['courier_provider'] = $row->courier;
				$attributes['bank_officer'] = $row->bank_officer;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;
			}

            $result = $objReInitialization->getReInitializationDetailTmc($process['ticket_no']);
			foreach($result as $row){

				$attributes['pod_number'] = $row->pod_no;
        		$attributes['pod_date'] = $row->tdate;
			}
        }

        if($process['workflow_id'] == 4){

            $objSoftwareUpdate = new SoftwareUpdate();

            $result = $objSoftwareUpdate->getSoftwareUpdationInfor($process['ticket_no']);
            foreach($result as $row){

                $attributes['ticket_no'] = $row->ticketno;
                $attributes['workflow_id'] = $process['workflow_id'];
                $attributes['workflow_name'] =  $objWorkflow->getWorkflowName($process['workflow_id']);
                $attributes['bank'] = $row->bank;
                $attributes['date'] = $row->tdate;
                $attributes['tid'] = $row->tid;
                $attributes['model'] = $row->model;
                $attributes['merchant'] = $row->merchant;
                $attributes['cc_remark'] = $row->batch_remark;

				$attributes['sub_status'] = 2;
				$attributes['status'] =  $row->status;
            }

			$result = $objSoftwareUpdate->getSoftwareUpdateDetailFTL($process['ticket_no']);
			foreach($result as $row){

				$attributes['ftl_date'] = $row->tdate;
				$attributes['contact_number'] = $row->contactno;
				$attributes['ftl_remark'] = $row->remark;
				$attributes['officer'] = $row->officer;
				$attributes['courier_provider'] = $row->courier;
				$attributes['bank_officer'] = $row->bank_officer;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;
			}

            $result = $objSoftwareUpdate->getSoftwareUpdateDetailTmc($process['ticket_no']);
			foreach($result as $row){

				$attributes['pod_number'] = $row->pod_no;
        		$attributes['pod_date'] = $row->tdate;
			}
        }

		if($process['workflow_id'] == 5){

            $objTerminalReplacement = new TerminalReplacement();

            $result = $objTerminalReplacement->getTerminalReplacementDetail($process['ticket_no']);
            foreach($result as $row){

                $attributes['ticket_no'] = $row->ticketno;
                $attributes['workflow_id'] = $process['workflow_id'];
                $attributes['workflow_name'] =  $objWorkflow->getWorkflowName($process['workflow_id']);
                $attributes['bank'] = $row->bank;
                $attributes['date'] = $row->tdate;
                $attributes['tid'] = $row->based_tid;
                $attributes['model'] = $row->based_model;
                $attributes['merchant'] = $row->merchant;
                $attributes['cc_remark'] = $row->remark;

				$attributes['sub_status'] = 22;
				$attributes['status'] =  $row->status;
            }

			$result = $objTerminalReplacement->getTerminalReplacementDetailFTL($process['ticket_no']);
			foreach($result as $row){

				$attributes['ftl_date'] = $row->tdate;
				$attributes['contact_number'] = $row->contactno;
				$attributes['ftl_remark'] = $row->remark;
				$attributes['officer'] = $row->officer;
				$attributes['courier_provider'] = $row->courier;
				$attributes['bank_officer'] = $row->bank_officer;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;
			}

            $result = $objTerminalReplacement->getTerminalReplacementDetailTmc($process['ticket_no']);
			foreach($result as $row){

				$attributes['pod_number'] = $row->pod_no;
        		$attributes['pod_date'] = $row->tdate;
			}
        }

		if($process['workflow_id'] == 6){

            $objBackupRemoveProcess = new BackupRemoveProcess();

            $result = $objBackupRemoveProcess->getBackupRemoveProcessDetail($process['ticket_no']);
            foreach($result as $row){

                $attributes['ticket_no'] = $row->brn_no;
                $attributes['workflow_id'] = $process['workflow_id'];
                $attributes['workflow_name'] =  $objWorkflow->getWorkflowName($process['workflow_id']);
                $attributes['bank'] = $row->bank;
                $attributes['date'] = $row->brn_date;
                $attributes['tid'] = $row->tid;
                $attributes['model'] = $row->backup_model;
                $attributes['merchant'] = $row->merchant;
                $attributes['cc_remark'] = $row->remark;

				$attributes['sub_status'] = 9;
				$attributes['status'] =  $row->status;
            }

			$result = $objBackupRemoveProcess->getBackupRemoveProcessDetailFTL($process['ticket_no']);
			foreach($result as $row){

				$attributes['ftl_date'] = $row->tdate;
				$attributes['contact_number'] = $row->contactno;
				$attributes['ftl_remark'] = $row->remark;
				$attributes['officer'] = $row->officer;
				$attributes['courier_provider'] = $row->courier;
				$attributes['bank_officer'] = $row->bank_officer;
				$attributes['sub_status'] = $row->sub_status;
				$attributes['status'] = $row->status;
				$attributes['done_date_time'] = $row->done_date_time;
			}

            $result = $objBackupRemoveProcess->getBackupRemoveProcessDetailTmc($process['ticket_no']);
			foreach($result as $row){

				$attributes['pod_number'] = $row->pod_no;
        		$attributes['pod_date'] = $row->tdate;
			}
        }

		if((is_null($process) == FALSE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$attributes['process_status'] = $process['process_status'];
			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

			return $attributes;

		}else{

			$input = $request->input();
	        if(is_null($input) == FALSE){

				$attributes['ftl_date'] = $input['ftl_date'];
				$attributes['contact_number'] = $input['contact_number'];

				if( $request->workflow_id == 1 ){

					$attributes['fault'] = $input['fault'];
					$attributes['action_taken'] = $input['action_taken'];
					$attributes['relevant_detail'] = $input['relevant_detail'];
				}

				$attributes['ftl_remark'] = $input['ftl_remark'];
				$attributes['officer'] = $input['officer'];
				$attributes['courier_provider'] = $input['courier'];
				$attributes['bank_officer'] = $input['bank_officer'];
				$attributes['pod_number'] = $input['pod_number'];
				$attributes['pod_date'] = $input['pod_date'];
				$attributes['sub_status'] = $input['sub_status'];
				$attributes['status'] = $input['status'];
				$attributes['done_date_time'] = $input['done_date_time'];

				// settype($attributes['officer'], "integer");
				// settype($attributes['courier_provider'], "integer");
				// settype($attributes['bank_officer'], "integer");
			}

			$attributes['process_status'] = FALSE;
			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

			return $attributes;
		}
    }

    public function fieldServiceAllocationProcess(Request $request){

        $validation_result = $this->validationProcess($request);
        if($validation_result['validation_result'] ){

			$fs_allocate_result = $this->fsAllocateProcess($request);

			$fs_allocate_result['ticket_no'] = $request->ticket_number;
			$fs_allocate_result['workflow_id'] = $request->workflow_id;
			$fs_allocate_result['validation_result'] = $validation_result['validation_result'];
			$fs_allocate_result['validation_messages'] = $validation_result['validation_messages'];

			$data['attributes'] = $this->getFieldServiceAllocateAttributes($fs_allocate_result, $request);

        }else{

			$validation_result['process_status'] = FALSE;
			$validation_result['ticket_no'] = $request->ticket_number;
			$validation_result['workflow_id'] = $request->workflow_id;

			$data['attributes'] = $this->getFieldServiceAllocateAttributes($validation_result, $request);
        }

        $objWorkflow = new Workflow();
        $objCourierProvider = new CourierProvider();
        $objBankOfficer = new BankOfficer();
        $objSubStatus = new SubStatus();
        $objStatus = new Status();
        $objUser = new User();

        $data['workflow'] = $objWorkflow->get_workflow();
        $data['officer'] = $objUser->getActiveFieldOfficers();
        $data['courier_provider'] = $objCourierProvider->getActiveCourierProviders();
        $data['bank_officer'] = $objBankOfficer->getBankOfficer($request->bank);

        if(  $request->workflow_id == 1){

			$objBreakdown = new Breakdown();
            $objFault = new Fault();
            $objRelevantDetail = new RelevantDetail();
            $objActionTaken = new ActionTaken();

            $data['fault'] = $objFault->getFaults();
            $data['relevant_detail'] = $objRelevantDetail->getRelevantDetail();
            $data['action_taken'] = $objActionTaken->getActionTaken();
			$data['field_service'] = $objBreakdown->getBreakdownFieldService('22121118');
			$data['field_fault'] = $objBreakdown->getBreakdownFieldFaults('22121118');
			$data['field_action_taken'] = $objBreakdown->getBreakdownFieldActionTaken('22121118');
			$data['field_spare_part'] = $objBreakdown->getBreakdownSparePartRequest('22121118');
			$data['history'] = $objBreakdown->getBreakdownHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getBreakdownSubStatus();

        }elseif( $request->workflow_id == 2){

			$objNewInstallation = new NewInstallation();

			$data['field_service'] = $objNewInstallation->getNewInstallationFieldService($request->ticket_number);
			$data['history'] = $objNewInstallation->getNewInstallationHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getNewInstallationSubStatus();

        }elseif( $request->workflow_id == 3){

			$objReInitialization = new ReInitialization();

			$data['field_service'] = $objReInitialization->getReInitializationFieldService($request->ticket_number);
			$data['history'] = $objReInitialization->getReInitializationHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getReInitializationSubStatus();

        }elseif( $request->workflow_id == 4){

			$objSoftwareUpdate = new SoftwareUpdate();

			$data['field_service'] = $objSoftwareUpdate->getSoftwareUpdateFieldService('2538');
			$data['history'] = $objSoftwareUpdate->getSoftwareUpdateHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getSoftwareUpdateSubStatus();

        }elseif( $request->workflow_id == 5){

			$objTerminalReplacement = new TerminalReplacement();

			$data['field_service'] = $objTerminalReplacement->getTerminalReplacementFieldService('961');
			$data['history'] = $objTerminalReplacement->getTerminalReplacementHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getTerminalReplacementSubStatus();

        }elseif( $request->workflow_id == 6){

			$objBackupRemoveProcess = new BackupRemoveProcess();

			$data['field_service'] = $objBackupRemoveProcess->getBackupRemoveProcessFieldService('203');
			$data['history'] = $objBackupRemoveProcess->getBackupRemoveProcessHistory($request->ticket_number);
            $data['sub_status'] = $objSubStatus->getBackupRemoveSubStatus();

        }else{

        }

        $data['status'] =  $objStatus->getBreakdownStatus();


        return view('fsp.field_service_allocate.fs_allocate')->with('FSA', $data);

    }

    private function validationProcess($request){

        //try{

			$front_end_message = " ";

			$objWorkflow = new Workflow();
			$workflow_shortname = $objWorkflow->getWorkflowShortName($request->workflow_id);

            $responsible['field_officer'] = $request->officer;
            $responsible['courier_provider'] = $request->courier;
            $responsible['bank_officer'] = $request->bank_officer;

			$input['Allocated Date'] = $request->ftl_date;
            $input['Report Date'] = $request->report_date;
            $input['Contact Number'] = $request->contact_number;

			if($request->workflow_id == 1){

				$input['Fault'] = $request->fault;
			}

            $input['field_officer'] = $request->officer;
            $input['courier_provider'] = $request->courier;
            $input['bank_officer'] = $request->bank_officer;

			$input['Remark'] = $request->ftl_remark;
			$input['Sub Status'] = $request->sub_status;

			if($request->status == 'done'){

				if($request->workflow_id == 1){

					$input['Action Taken'] = $request->action_taken;
				}
				$input['Done Date Time'] = $request->done_date_time;
			}

			/* ------------------------------------------------------------------------------------------------------------------------------------------ */

			$rules['Allocated Date'] = array('required', 'date' , 'after_or_equal:' . $request->report_date , 'before_or_equal:' . date('Y-m-d') );
            $rules['Report Date'] = array('required', 'date');
            $rules['Contact Number'] = array('max:35');

			if($request->workflow_id == 1){

				$rules['Fault'] = array(new NotValidation('Fault', $request->fault));
			}

            $rules['field_officer'] = array(new OfficerCourierValidation($responsible));
            $rules['courier_provider'] = array(new OfficerCourierValidation($responsible), new CourierValidation($request->ticket_number, $workflow_shortname, $request->courier));
            $rules['bank_officer'] = array(new OfficerCourierValidation($responsible));

			$rules['Remark'] = array('max:300');
			$rules['Sub Status'] = array(new NotValidation('Sub Status', $request->sub_status), new SubStatusValidation($request->status, $request->workflow_id));

			if($request->status == 'done'){

				if($request->workflow_id == 1){

					$rules['Action Taken'] = array(new NotValidation('Action Taken', $request->action_taken));
				}

				$before_date = date('Y/m/d', strtotime($request->ftl_date . ' +1 day'));
				$rules['Done Date Time'] = array('required', 'date' , 'after_or_equal:' . $request->report_date, 'before_or_equal:' . $before_date);
			}

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Field Service Allocation Controller - Validation Process ';

            // echo '<pre>';
            // print_r($process_result['validation_messages']);
            // echo '</pre>';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Field Service Allocation Controller - Validation Function Fault';

		// 	return $process_result;
		// }

    }

	private function fsAllocateProcess($request){

		//try{

			$objWorkflow = new Workflow();
			$workflow_shortname = $objWorkflow->getWorkflowShortName($request->workflow_id);

			$data['ftl'] = $this->getFtlTable($request);
			$data['main'] = $this->getMainTableArray($request);

			if($request->workflow_id != 1){

				$data['oln'] = $this->getOfficerAllocationNoteArray($request);
			}

			$data['trn'] = $this->getTerminalRequestNoteArray($request, $workflow_shortname);

			if( ! ($request->workflow_id == 5) || ($request->workflow_id == 6) ){

				$data['tpn'] = $this->getTerminalProgrammingNoteArray($request, $workflow_shortname);
			}

			$data['status'] = $this->getStatusUpdateArray($request);
			$data['history'] = $this->getFtlHistory($request);
			$data['sms'] = $this->getSmsRequest($request, $workflow_shortname);
			$data['email'] = $this->getEmailRequest($request, $workflow_shortname);
			$data['input'] = $request->input();

			if($request->workflow_id == 1){

				$objBreakdown = new Breakdown();
				$result = $objBreakdown->saveBreakdownFTL($data);
			}

			if($request->workflow_id == 2){

				$objNewInstallation = new NewInstallation();
				$result = $objNewInstallation->saveNewInstallationFTL($data);
			}

			if($request->workflow_id == 3){

				$objReInitialization = new ReInitialization();
				$result = $objReInitialization->saveReInitializationFTL($data);
			}

			if($request->workflow_id == 4){

				$objSoftwareUpdate = new SoftwareUpdate();
				$result = $objSoftwareUpdate->saveSoftwareUpdationFTL($data);
			}

			if($request->workflow_id == 5){

				$objTerminalReplacement = new TerminalReplacement();
				$result = $objTerminalReplacement->saveTerminalReplacementFTL($data);
			}

			if($request->workflow_id == 6){

				$objBackupRemoveProcess = new BackupRemoveProcess();
				$result = $objBackupRemoveProcess->saveBackupRemoveFTL($data);
			}

			return $result;

		// }catch(\Exception $e){

		// 	$process_result['ticket_no'] = $request->ticket_number;
        //  $process_result['process_status'] = FALSE;
        //  $process_result['front_end_message'] = $e->getMessage();
        //  $process_result['back_end_message'] = 'Field Serivce Allocation Controller -> Field Serivce Allocation Saving Process <br> ' . $e->getLine();

        //  return $process_result;
		// }

	}

	private function getFtlTable($request){

		$ftl_array['ticketno'] = $request->ticket_number;
		$ftl_array['tdate'] = $request->ftl_date;
		$ftl_array['contactno'] = $request->contact_number;
		$ftl_array['contact_person'] = $request->contact_person;

		if($request->workflow_id == 1){

			$ftl_array['error'] = $request->fault;
			$ftl_array['relevant_detail'] = $request->relevant_detail;
			$ftl_array['action_taken'] = $request->action_taken;

			unset($ftl_array['contact_person']);
		}

		$ftl_array['officer'] = $request->officer;
		$ftl_array['courier'] = $request->courier;
		$ftl_array['bank_officer'] = $request->bank_officer;

		if( ($request->workflow_id == 5) ){

			unset($ftl_array['bank_officer']);
		}

		$ftl_array['remark'] = $request->ftl_remark;
		$ftl_array['sub_status'] = $request->sub_status;
		$ftl_array['status'] = $request->status;
		$ftl_array['done_date_time'] = $request->done_date_time;
		$ftl_array['email'] = 0;
		$ftl_array['email_on'] = NULL;

		if($request->workflow_id == 1){

			$objBreakdown = new Breakdown();
			$allocated_result = $objBreakdown->isAllocatedTicketNumber($request->ticket_number);
		}

		if($request->workflow_id == 2){

			$objNewInstallation = new NewInstallation();
			$allocated_result = $objNewInstallation->isAllocatedTicketNumber($request->ticket_number);
		}

		if($request->workflow_id == 3){

			$objReInitialization = new ReInitialization();
			$allocated_result = $objReInitialization->isAllocatedTicketNumber($request->ticket_number);
		}

		if($request->workflow_id == 4){

			$objSoftwareUpdate = new SoftwareUpdate();
			$allocated_result = $objSoftwareUpdate->isAllocatedTicketNumber($request->ticket_number);
		}

		if($request->workflow_id == 5){

			$objTerminalReplacement = new TerminalReplacement();
			$allocated_result = $objTerminalReplacement->isAllocatedTicketNumber($request->ticket_number);
		}

		if($request->workflow_id == 6){

			$objBackupRemoveProcess = new BackupRemoveProcess();
			$allocated_result = $objBackupRemoveProcess->isAllocatedTicketNumber($request->ticket_number);
		}

		if( $allocated_result ){

			$ftl_array['edit_by'] = Auth::user()->name;
			$ftl_array['edit_on'] = date('Y-m-d G:i:s');
			$ftl_array['edit_ip'] = Request()->ip();

		}else{

			$ftl_array['saved_by'] = Auth::user()->name;
            $ftl_array['saved_on'] =  date('Y-m-d G:i:s');
            $ftl_array['saved_ip'] = Request()->ip();
		}

		return $ftl_array;
	}

	private function getMainTableArray($request){

		$main_table_array['contact_person'] = $request->contact_person;
		$main_table_array['officer'] = $request->officer;
		$main_table_array['courier'] = $request->courier;
		$main_table_array['bank_officer'] = $request->bank_officer;

		if($request->workflow_id == 1){

			$ftl_array['action_taken'] = $request->action_taken;

			unset($ftl_array['contact_person']);
		}

		$main_table_array['sub_status'] = $request->sub_status;
		$main_table_array['status'] = $request->status;

		if( $request->workflow_id == 4 ){

			$main_table_array['update_date_time'] = $request->done_date_time;

		}else{

			$main_table_array['done_date_time'] = $request->done_date_time;
		}

		$main_table_array['email'] = 0;
		$main_table_array['email_on'] = NULL;

		if( $request->workflow_id != 4 ){

			$main_table_array['edit_by'] = Auth::user()->name;
			$main_table_array['edit_on']= date('Y-m-d G:i:s');
			$main_table_array['edit_ip'] = Request()->ip();
		}

		return $main_table_array;
	}

	private function getOfficerAllocationNoteArray($request){

		$oln_array['settle'] = 1;
		$oln_array['settle_by'] = Auth::user()->name;
		$oln_array['settle_on']= date('Y-m-d G:i:s');
		$oln_array['settle_ip'] = Request()->ip();

		return $oln_array;
	}

	private function getTerminalRequestNoteArray($request, $workflow_shortname){

		// Cancel Terminal Request Note
		if(  ($request->status == 'done') || ($request->status == 'closed') || ($request->courier == "Not" ) ) {

			$trn_array['cancel'] = 1;
			$trn_array['canceled_by'] = Auth::user()->name;
			$trn_array['canceled_on'] = date('Y-m-d G:i:s');
			$trn_array['canceled_ip'] = Request()->ip();

			return $trn_array;
		}

		// Insert or Update Terminal Request Note
		if( $request->courier != "Not") {

			$trn_array['ticketno'] = $request->ticket_number;
			$trn_array['tdate'] = date('Y-m-d G:i:s');
			$trn_array['ref'] = $workflow_shortname;
			$trn_array['settle'] = 0;
			$trn_array['cancel'] = 0;
			$trn_array['remark'] = '-';

			$objHelpNote = new HelpNote();
			if( $objHelpNote->isGenaratedTerminalRequestNote($request->ticket_number, $workflow_shortname) ){

				$trn_array['request_no'] = $objHelpNote->getTerminalRequestNoteNumber($request->ticket_number, $workflow_shortname);

				$trn_array['edit_by'] = Auth::user()->name;
				$trn_array['edit_on'] = date('Y-m-d G:i:s');
				$trn_array['edit_ip'] = Request()->ip();

			}else{

				$trn_array['saved_by'] = Auth::user()->name;
				$trn_array['saved_on'] =  date('Y-m-d G:i:s');
				$trn_array['saved_ip'] = Request()->ip();
			}

			$trn_array['canceled_by'] = NULL;
			$trn_array['canceled_on'] = NULL;
			$trn_array['canceled_ip'] = NULL;

			return $trn_array;
		}
	}

	private function getTerminalProgrammingNoteArray($request, $workflow_shortname){

		if( ($request->status == 'done') || ($request->status == 'closed') ){

			$tpn_array['cancel'] = 1;
			$tpn_array['canceled_by'] = Auth::user()->name;
			$tpn_array['canceled_on'] = date('Y-m-d G:i:s');
			$tpn_array['canceled_ip'] = Request()->ip();

			return $tpn_array;
		}

		if( ($request->workflow_id == 3) || ($request->workflow_id == 4) ){

			if( $request->courier == "Not" ){

				$tpn_array['cancel'] = 1;
				$tpn_array['canceled_by'] = Auth::user()->name;
				$tpn_array['canceled_on'] = date('Y-m-d G:i:s');
				$tpn_array['canceled_ip'] = Request()->ip();

				return $tpn_array;

			}else{

				$tpn_array = array();

				$tpn_array['ticketno'] =  $request->ticket_number;
				$tpn_array['tdate'] = date('Y-m-d G:i:s');;
				$tpn_array['ref'] = $workflow_shortname;
				$tpn_array['settle'] = 0;
				$tpn_array['cancel'] = 0;
				$tpn_array['remark'] = '-';

				$objHelpNote = new HelpNote();
				if( $objHelpNote->isGenaratedTerminalProgrammeNote($request->ticket_number, $workflow_shortname) ){

					$tpn_array['tp_no'] = $objHelpNote->getTerminalProgrammeNoteNumber($request->ticket_number, $workflow_shortname);

					$tpn_array['edit_by'] = Auth::user()->name;
					$tpn_array['edit_on'] = date('Y-m-d G:i:s');
					$tpn_array['edit_ip'] = Request()->ip();

				}else{

					$tpn_array['saved_by'] = Auth::user()->name;
					$tpn_array['saved_on'] =  date('Y-m-d G:i:s');
					$tpn_array['saved_ip'] = Request()->ip();
				}

				$tpn_array['canceled_by'] = NULL;
				$tpn_array['canceled_on'] = NULL;
				$tpn_array['canceled_ip'] = NULL;

				return $tpn_array;
			}
		}

		if( ($request->workflow_id == 2) || ($request->workflow_id == 5) || ($request->workflow_id == 6) ){

			$tpn_array['cancel'] = 0;
			$tpn_array['edit_by'] = Auth::user()->name;
			$tpn_array['edit_on'] = date('Y-m-d G:i:s');
			$tpn_array['edit_ip'] = Request()->ip();

			return $tpn_array;
		}

	}

	private function getStatusUpdateArray($request){

		$status_update_array['sub_status'] = $request->sub_status;
		$status_update_array['status'] = $request->status;
		$status_update_array['edit_by'] = Auth::user()->name;
		$status_update_array['edit_on'] = date('Y-m-d G:i:s');
		$status_update_array['edit_ip'] = Request()->ip();

		return $status_update_array;
	}

	private function getFtlHistory($request){

		$objUser = new User();
		$objCourierProvider = new CourierProvider();
		$objSubStatus = new SubStatus();
		$objFault = new Fault();
		$objRelevantDetail = new RelevantDetail();
		$objActionTaken = new ActionTaken();

		$new_record['FTL Date'] = $request->ftl_date;
		$new_record['FTL Contact Number'] = $request->contact_number;
		$new_record['FTL Officer'] = $objUser->getOfficerName($request->officer);
		$new_record['FTL Courier Provider'] = $objCourierProvider->getCourierName($request->courier);
		$new_record['FTL Bank Officer'] = $objUser->getBankOfficerName($request->bank_officer);
		$new_record['FTL Remark'] = $request->ftl_remark;

		if($request->workflow_id == 1){

			$new_record['FTL Fault'] = $objFault->getFaultDescription($request->fault);
			$new_record['FTL Relevant Detail'] = $objRelevantDetail->getRelevantDetailDescription($request->relevant_detail);
			$new_record['FTL Action Taken'] = $objActionTaken->getActionTakenDescription($request->action_taken);
		}
		$new_record['FTL Sub Status'] = $objSubStatus->getBreakdownSubStatusDescription($request->sub_status);
        $new_record['FTL Status'] = ucfirst($request->status);
        $new_record['FTL Done Date Time'] = $request->done_date_time;

		$result = NULL;

		if($request->workflow_id == 1){

			$objBreakdown = new Breakdown();
			$result = $objBreakdown->getBreakdownDetailFTL($request->ticket_number);
		}

		if($request->workflow_id == 2){

			$objNewInstallation = new NewInstallation();
			$result = $objNewInstallation->getNewInstallationDetailFTL($request->ticket_number);
		}

		if($request->workflow_id == 3){

			$objReInitialization = new ReInitialization();
			$result = $objReInitialization->getReInitializationDetailFTL($request->ticket_number);
		}

		if($request->workflow_id == 4){

			$objSoftwareUpdate = new SoftwareUpdate();
			$result = $objSoftwareUpdate->getSoftwareUpdateDetailFTL($request->ticket_number);
		}

		if($request->workflow_id == 5){

			$objTerminalReplacement = new TerminalReplacement();
			$result = $objTerminalReplacement->getTerminalReplacementDetailFTL($request->ticket_number);
		}

		if($request->workflow_id == 6){

			$objBackupRemoveProcess = new BackupRemoveProcess();
			$result = $objBackupRemoveProcess->getBackupRemoveProcessDetailFTL($request->ticket_number);
		}

		if( count($result) >=1){

			foreach($result as $row){

				$old_record['FTL Date'] = $row->tdate;
				$old_record['FTL Contact Number'] = $row->contactno;
				$old_record['FTL Officer'] = $objUser->getOfficerName($row->officer);
				$old_record['FTL Courier Provider'] = $objCourierProvider->getCourierName($row->courier);
				$old_record['FTL Bank Officer'] = $objUser->getBankOfficerName($row->bank_officer);
				$old_record['FTL Remark'] = $row->remark;

				if($request->workflow_id == 1){

					$old_record['FTL Fault'] = $objFault->getFaultDescription($row->error);
					$old_record['FTL Relevant Detail'] = $objRelevantDetail->getRelevantDetailDescription($row->relevant_detail);
					$old_record['FTL Action Taken'] = $objActionTaken->getActionTakenDescription($row->action_taken);
				}
				$old_record['FTL Sub Status'] = $objSubStatus->getBreakdownSubStatusDescription($row->sub_status);
				$old_record['FTL Status'] = ucfirst($row->status);
				$old_record['FTL Done Date Time'] = $row->done_date_time;
			}

		}else{

			$old_record['FTL Date'] = '';
			$old_record['FTL Contact Number'] = '';
			$old_record['FTL Officer'] = '';
			$old_record['FTL Courier Provider'] = '';
			$old_record['FTL Bank Officer'] = '';
			$old_record['FTL Remark'] = '';

			if($request->workflow_id == 1){

				$old_record['FTL Fault'] = '';
				$old_record['FTL Relevant Detail'] = '';
				$old_record['FTL Action Taken'] = '';
			}
			$old_record['FTL Sub Status'] = '';
			$old_record['FTL Status'] = '';
			$old_record['FTL Done Date Time'] = '';
		}

		$history_array = array();
		$icount = 1;
		foreach($new_record as $key => $value) {

        	if( $new_record[$key] == $old_record[$key] ){

            }else{

                $tmp_array['ticketno'] = $request->ticket_number;
                $tmp_array['userid'] = $objUser->get_user_name(Auth::id());
                $tmp_array['tdatetime'] = date('Y-m-d G:i:s');
                $tmp_array['field_name'] = $key;
                $tmp_array['old_value'] = $old_record[$key];
                $tmp_array['new_value'] = $new_record[$key];

				$history_array[$icount] = $tmp_array;
				$icount++;
            }
        }

		return $history_array;
	}

	private function getSmsRequest($request, $workflow_shortname){

		$objUser = new User();

		$short_service_message = $request->tid . ' | ' . $request->fault_description . ' | ' . $request->contact_number  . ' | ' . $request->merchant ;
		$officer_mobile = $objUser->getOfficerMobileNumber($request->officer);

		$sms_request['ref'] = $workflow_shortname;
		$sms_request['ticketno'] = $request->ticket_number;
		$sms_request['bank'] = $request->bank;
		$sms_request['tid'] = $request->tid;
		$sms_request['office_mobile'] = $officer_mobile;
		$sms_request['message'] = $short_service_message;
		$sms_request['sent'] = 0;
		$sms_request['saved_by'] = Auth::user()->name;
		$sms_request['saved_on'] = date('Y-m-d G:i:s');

		return $sms_request;
	}

	private function getEmailRequest($request, $workflow_shortname){

		$email_request['ticket_no'] = $request->ticket_number;
        $email_request['workflow'] = $workflow_shortname;
        $email_request['role'] = 6;
        $email_request['requested_on'] = date('Y-m-d G:i:s');
        $email_request['requested_by'] = Auth::user()->name;
        $email_request['email_sent'] = 0;
        $email_request['sent_on'] = NULL;

		return $email_request;
	}

}
