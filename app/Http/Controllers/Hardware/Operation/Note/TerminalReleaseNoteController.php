<?php

namespace App\Http\Controllers\Hardware\Operation\Note;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\User;
use App\Models\Hardware\Operation\JobCardProcess;
use App\Models\Hardware\Operation\TerminalReleaseProcess;

use App\Rules\ZeroValidation;
use App\Rules\Hardware\Operation\JobcardReleaseValidation;

class TerminalReleaseNoteController extends Controller {

    public function index(){

        $objUser = new User();
        $objTerminalReleaseProcess = new TerminalReleaseProcess();

        $data['jobcard_table'] = $objTerminalReleaseProcess->getTemporaryJobcardReleaseTable(Auth::user()->id);
        $data['officer'] = $objUser->getActiveTmcAndFieldOfficers();
        $data['attributes'] = $this->getTerminalReleaseProcessAttributes(NULL, NULL);

        return view('hardware.operation.note.terminal_release')->with('TRP', $data);;
    }

    private function getTerminalReleaseProcessAttributes($process, $request){

        $attributes['jobcard_numbers'] = '';
        $attributes['officer'] = 0;
        $attributes['remark'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        $input = $request->input();
        if(is_null($input) == FALSE){

            $attributes['jobcard_numbers'] = $input['jobcard_numbers'];
            $attributes['officer'] = $input['officer'];
            $attributes['remark'] = $input['remark'];
        }

        if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

            $attributes['process_status'] = TRUE;
			$attributes['validation_messages'] = new MessageBag();

			$message = $process['front_end_message'];
			$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

			return $attributes;

        }else{

			$attributes['process_status'] = FALSE;
			$attributes['validation_messages'] = $process['validation_messages'];

            $error_message = '';
            foreach( $process['validation_messages']->all() as $error ){

                $error_message = '<li>'. $error .'</li>';
            }

			$message = $process['front_end_message'] .' <br> ' . $error_message;
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

			return $attributes;
        }
    }

    public function terminal_release_process(Request $request){

        $objTerminalReleaseProcess = new TerminalReleaseProcess();

        if($request->submit == 'Get Infor'){

            $jobcard_validation_result = $this->jobcardValidationProcess($request);
            if( $jobcard_validation_result['validation_result'] == TRUE){

                $infor_result = $this->getInformation($request);

                $infor_result['validation_result'] = $jobcard_validation_result['validation_result'];
                $infor_result['validation_messages'] = $jobcard_validation_result['validation_messages'];

                $data['jobcard_table'] = $objTerminalReleaseProcess->getTemporaryJobcardReleaseTable(Auth::user()->id);
                $data['attributes'] = $this->getTerminalReleaseProcessAttributes($infor_result, $request);

            }else{

                $data['jobcard_table'] = $objTerminalReleaseProcess->getTemporaryJobcardReleaseTable(Auth::user()->id);
			    $jobcard_validation_result['process_status'] = FALSE;

                $data['attributes'] = $this->getTerminalReleaseProcessAttributes($jobcard_validation_result, $request);
            }
        }

        if($request->submit == 'Release'){

            $jobcard_release_validation_result = $this->jobcardReleaseValidationProcess($request);
            if( $jobcard_release_validation_result['validation_result'] ){

                $release_jobcard_result = $this->releaseJobcards($request);

                 $release_jobcard_result['validation_result'] = $jobcard_release_validation_result['validation_result'];
                 $release_jobcard_result['validation_messages'] = $jobcard_release_validation_result['validation_messages'];

                $data['jobcard_table'] = $objTerminalReleaseProcess->getTemporaryJobcardReleaseTable(Auth::user()->id);
                $data['attributes'] = $this->getTerminalReleaseProcessAttributes($release_jobcard_result, $request);

            }else{

                $data['jobcard_table'] = $objTerminalReleaseProcess->getTemporaryJobcardReleaseTable(Auth::user()->id);

			    $jobcard_release_validation_result['process_status'] = FALSE;
                $data['attributes'] = $this->getTerminalReleaseProcessAttributes($jobcard_release_validation_result, $request);
            }

        }

        $objUser = new User();
        $data['officer'] =$objUser->getActiveTmcAndFieldOfficers();

        return view('hardware.operation.note.terminal_release')->with('TRP', $data);;
    }

    private function jobcardValidationProcess($request){

        //try{

			$front_end_message = " ";

			$input['jobcard_numbers'] = $request->jobcard_numbers;

			$rules['jobcard_numbers'] = array('required', new JobcardReleaseValidation());

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Terminal Release Note Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Terminal Release Note Controller - Validation Function Fault';

		// 	return $process_result;
		// }
    }

    private function getInformation($request){

        //try{

            $objTerminalReleaseProcess = new TerminalReleaseProcess();

            $jobcard_infor = $this->getReleaseJobcardsInformation($request);

            $process_result = $objTerminalReleaseProcess->saveJobcards($jobcard_infor);

            return $process_result;

		// }catch(\Exception $e){

        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal Release Note Controller -> Terminal Release Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

    }

    private function getReleaseJobcardsInformation($request){

        $objJobCardProcess = new JobCardProcess();
        $jobcard_infor =  array();

        $jobcard_information = $request->jobcard_numbers;
        $jobcard_information = explode(chr(13), $jobcard_information);

        $icount = 1;
        foreach($jobcard_information as $jobcard_row){

            $jc_row = rtrim(ltrim($jobcard_row));

            $jobcard_result = $objJobCardProcess->jobcard_inquire($jc_row);
            foreach($jobcard_result as $row){

                $jobcard_infor[$icount]['jobcard_no'] = $row->jobcard_no;
                $jobcard_infor[$icount]['user_id'] = Auth::user()->id;
                $jobcard_infor[$icount]['jobcard_date'] = $row->jobcard_date;
                $jobcard_infor[$icount]['bank'] = $row->bank;
                $jobcard_infor[$icount]['serialno'] = $row->serialno;
                $jobcard_infor[$icount]['model'] = $row->model;
                $jobcard_infor[$icount]['qt_no'] = $row->qt_no;
                $jobcard_infor[$icount]['status'] = $row->status;

                $icount++;
            }
        }

        return $jobcard_infor;
    }

    public function removeJobcards(Request $request){

        try{

            $objTerminalReleaseProcess = new TerminalReleaseProcess();

            $remove_faults_result = $objTerminalReleaseProcess->removeJobcards($request->jobcard_no);

            return response()->json($remove_faults_result);

		}catch(\Exception $e){

			$process_result['jobcard_no'] = $request->jobcard_no;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Jobcard Action Controller -> Fault Add Process <br> ' . $e->getLine();

            return response()->json($process_result);
		}

    }

    private function jobcardReleaseValidationProcess($request){

        //try{

			$front_end_message = " ";

			$input['officer'] = $request->officer;
            $input['remark'] = $request->remark;

			$rules['officer'] = array('required', new ZeroValidation('Officer', $request->officer));
            $rules['remark'] = array('max:200');

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Terminal Release Note Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Terminal Release Note Controller - Validation Function Fault';

		// 	return $process_result;
		// }
    }

    public function releaseJobcards($request){

        //try{

			$objTerminalReleaseProcess = new TerminalReleaseProcess();

			$data['list'] = $this->getReleaseJobcardsList($request);
            $data['update_jobcard'] = $this->getUpdateJobcardArray();

			$release_jobcard_result = $objTerminalReleaseProcess->releaseJobcards($data);

			return $release_jobcard_result;

		// }catch(\Exception $e){

		// 	$process_result['spr_id'] = $request->spr_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Receive Note Controller -> Spare Part Receive Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

    }

    private function getReleaseJobcardsList($request){

        $release_jobcard = array();
        $release_jobcards_for_old_table = array();
        $tmc_bin  = array();
        $objTerminalReleaseProcess = new TerminalReleaseProcess();

        $icount = 1;
        $jobcard_result = $objTerminalReleaseProcess->getTemporaryJobcardReleaseTable(Auth::user()->id);
        foreach($jobcard_result as $row){

            $release_jobcard[$icount]['jobcard_no'] = $row->jobcard_no;
            $release_jobcard[$icount]['release_date'] = date("Y/m/d G:i:s");
            $release_jobcard[$icount]['release_by'] = Auth::user()->id;
            $release_jobcard[$icount]['release_to'] = $request->officer;
            $release_jobcard[$icount]['remark'] = $request->remark;

            $release_jobcards_for_old_table[$icount]['Jobcardno'] = $row->jobcard_no;
            $release_jobcards_for_old_table[$icount]['Serial_No'] = $row->serialno;
            $release_jobcards_for_old_table[$icount]['Ref_No'] = '-';
            $release_jobcards_for_old_table[$icount]['R_Date'] = date("Y/m/d G:i:s");
            $release_jobcards_for_old_table[$icount]['R_By'] = Auth::user()->id;
            $release_jobcards_for_old_table[$icount]['R_To'] = Auth::user()->name;
            $release_jobcards_for_old_table[$icount]['remark'] = $request->remark;

            $objJobCardProcess = new JobCardProcess();
            $jobcard_result = $objJobCardProcess->getJobcard( $row->jobcard_no);

			$tmc_bin[$icount]['serial_number'] = $row->serialno;
			$tmc_bin[$icount]['model'] = $jobcard_result->model;
			$tmc_bin[$icount]['bank'] = $jobcard_result->bank;
			$tmc_bin[$icount]['in_workflow_id'] = 12;
			$tmc_bin[$icount]['in_workflow_name'] = 'Terminal Repair';
			$tmc_bin[$icount]['in_workflow_number'] = '';
			$tmc_bin[$icount]['in_workflow_date'] = date("Y/m/d G:i:s");
			$tmc_bin[$icount]['released'] = 0;
			$tmc_bin[$icount]['released_workflow_id'] = 0;
			$tmc_bin[$icount]['released_workflow_name'] = NULL;
			$tmc_bin[$icount]['released_workflow_number'] = NULL;
			$tmc_bin[$icount]['released_workflow_date'] = NULL;

            $icount++;
        }

        $list['release_jobcard'] = $release_jobcard;
        $list['release_jobcards_for_old_table'] = $release_jobcards_for_old_table;
        $list['tmc_bin'] = $tmc_bin;

        return $list;
    }

    private function getUpdateJobcardArray(){

        $jobcard_array['Released_Date'] = now();
		$jobcard_array['Released'] = 1;

        return $jobcard_array;
    }




}
