<?php

namespace App\Http\Controllers\Tmc\InOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\User;
use App\Models\Tmc\Courier\CourierProcess;
use App\Models\Tmc\InOut\TerminalOutProcess;

use Illuminate\Validation\Rule;
use App\Rules\ZeroValidation;
use App\Rules\Tmc\InOut\TerminalOutCancelValidation;
use App\Rules\Tmc\InOut\TerminalOutConfirmValidation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App;
use Codedge\Fpdf\Fpdf\Fpdf;

class TerminalOutNoteController extends Controller {

	public function index(){

		$objBank = new Bank();
		$objUser = new User();
		$objCourier = new CourierProcess();
		$objTerminalOutProcess = new TerminalOutProcess();

		$data['bank'] = $objBank->get_bank();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['officer'] = $objUser->getActiveFieldOfficers();
		$data['source'] = $objTerminalOutProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');
		$data['attributes'] = $this->getTerminalOutNoteAttributes(NULL, NULL);

		return view('tmc.inout.terminal_out_note')->with('TON', $data);
	}

	private function getTerminalOutNoteAttributes($process, $request){

		$attributes['ticket_number'] = '#Auto#';
        $attributes['ticket_date'] = '';
		$attributes['source'] = "0";
		$attributes['type'] = "0";
		$attributes['bank'] = "0";
		$attributes['officer'] = "0";
		$attributes['pod_number'] = '';
		$attributes['courier'] = 'Not';
		$attributes['remark'] = '';
		$attributes['cancel_reason'] = '';
		$attributes['terminal_serial'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		$objTerminalOutProcess = new TerminalOutProcess();
		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$terminal_out_note_result = $objTerminalOutProcess->getTerminalOutNote($process['ticket_number']);
			$terminal_out_note_detail_result = $objTerminalOutProcess->getTerminalOutNoteDetail($process['ticket_number']);

			foreach($terminal_out_note_result as $row){

				$attributes['ticket_number'] = $row->ticketno;
		        $attributes['ticket_date'] = $row->tdate;
				$attributes['source'] = $row->source;
				$attributes['type'] = $row->type;
				$attributes['bank'] = $row->bank;
				$attributes['officer'] = $row->officer;
				$attributes['pod_number'] = $row->pod_no;
				$attributes['courier'] = $row->courier;
				$attributes['remark'] = $row->remark;
				$attributes['cancel_reason'] = $row->cancel_reason;
			}

			foreach($terminal_out_note_detail_result as $row){

				$attributes['terminal_serial'] .= $row->serialno . chr(13);
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			if( ($process['front_end_message'] == '') || ($process['back_end_message'] == '') ){

				$attributes['process_message'] = '';

			}else{

				$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            	$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';
			}


		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				$attributes['ticket_number'] = $input['ticket_number'];
		        $attributes['ticket_date'] = $input['ticket_date'];
				$attributes['source'] = $input['source'];
				$attributes['type'] = $input['type'];
				$attributes['bank'] = $input['bank'];
				$attributes['officer'] = $input['officer'];
				$attributes['pod_number'] = $input['pod_number'];
				$attributes['courier'] = $input['courier'];
				$attributes['remark'] = $input['remark'];
				$attributes['cancel_reason'] = $input['cancel_reason'];

				$attributes['terminal_serial'] = $input['terminal_serial'];
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
		}

		return $attributes;
	}

	public function terminal_out_note_process(Request $request){

		$objBank = new Bank();
		$objUser = new User();
		$objCourier = new CourierProcess();

		$objTerminalOutProcess = new TerminalOutProcess();

		$terminal_out_note_process_result = '';

		if( ($request->submit == 'Save') || ($request->submit == 'Confirm') ){

			$terminal_out_note_process_result = $this->saveTerminalOutNoteProcess($request);

			if($request->submit == 'Confirm'){

				if($terminal_out_note_process_result['process_status'] == TRUE){

					$terminal_out_note_process_result = $this->confirmTerminalOutNoteProcess($request);
				}
			}
		}

		if($request->submit == 'Cancel'){

			$terminal_out_note_process_result = $this->cancelTerminalOutNoteProcess($request);
		}

		if($request->submit == 'Reset'){

			$terminal_out_note_process_result = NULL;
			$request = NULL;
		}

		$data['bank'] = $objBank->get_bank();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['officer'] = $objUser->getActiveFieldOfficers();
		$data['source'] = $objTerminalOutProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');
		$data['attributes'] = $this->getTerminalOutNoteAttributes($terminal_out_note_process_result, $request);

		return view('tmc.inout.terminal_out_note')->with('TON', $data);
	}

	private function saveTerminalOutNoteProcess($request){

		$ton_validation_process_result = $this->terminalOutNoteValidationProcess($request);

		if($ton_validation_process_result['validation_result'] == TRUE){

			$saving_process_result = $this->tonSavingProcess($request);

			$saving_process_result['validation_result'] = $ton_validation_process_result['validation_result'];
			$saving_process_result['validation_messages'] = $ton_validation_process_result['validation_messages'];

			return $saving_process_result;

		}else{

			$ton_validation_process_result['ticket_number'] = $request->ticket_number;
			$ton_validation_process_result['process_status'] = FALSE;

			return $ton_validation_process_result;
		}
	}

	private function terminalOutNoteValidationProcess($request){

		try{

			$front_end_message = '';

			/* ----------------------------------------------  Inputs ----------------------------------------------- */
			$input['ticket_number'] = $request->ticket_number;
			$input['ticket_date'] = $request->ticket_date;
			$input['source'] = $request->source;
			$input['type'] = $request->type;

			if( $request->source == 2 ){

				$input['bank'] = $request->bank;
			}

			if( $request->source == 3 ){

				$input['officer'] = $request->officer;
			}

			$input['pod_number'] = $request->delivery_number;
            $input['remark'] = $request->remark;

			/* ----------------------------------------------  Rules ----------------------------------------------- */

			$rules['ticket_number'] = array('required', new TerminalOutCancelValidation(), new TerminalOutConfirmValidation());
			$rules['ticket_date'] = array('required', 'date');
			$rules['source'] = array('required', new ZeroValidation('Source', $request->source));
			$rules['type'] = array('required', new ZeroValidation('Type', $request->type));

			if( $request->source == 2 ){

				$rules['bank'] = array('required', new ZeroValidation('Bank', $request->bank));
			}

			if( $request->source == 3 ){

				$rules['officer'] = array('required', new ZeroValidation('Officer', $request->officer));
			}

			$rules['pod_number'] = array('max:10');
            $rules['remark'] = array('required', 'max:500');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Terminal Out Note Controller - Validation Process ';

            return $process_result;


		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Terminal Out Note Controller - Validation Function Fault';

			return $process_result;
		}
	}

	private function tonSavingProcess($request){

		try{

			$objTerminalOutProcess = new TerminalOutProcess();

			$data['terminal_out_note'] = $this->getTerminalOutNoteTable($request);
			$data['terminal_out_note_detail'] = $this->getTerminalOutNoteDetailTable($request);

			$ton_saving_process_result = $objTerminalOutProcess->saveTerminalOutNote($data);

			return $ton_saving_process_result;

		}catch(\Exception $e){

			$process_result['ticket_number'] = $request->ticket_number;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Terminal Out Note Controller -> Terminal Out Note Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	private function getTerminalOutNoteTable($request){

		$ton['ticketno'] = $request->ticket_number;
		$ton['tdate'] = $request->ticket_date;
		$ton['source'] = $request->source;
		$ton['type'] = $request->type;

		if( $request->source == 2 ){

			$ton['bank'] = $request->bank;
			$ton['officer'] = NULL;
			$ton['pod_no'] = NULL;
			$ton['courier'] = 'Not';
		}

		if( $request->source == 3 ){

			$ton['bank'] = NULL;
			$ton['officer'] = $request->officer;
			$ton['pod_no'] = $request->pod_number;
			$ton['courier'] = $request->courier;
		}

		$ton['remark'] = $request->remark;
		$ton['confirm'] = 0;
		$ton['cancel'] = 0;
		$ton['cancel_reason'] = "";

		$objTerminalOutProcess = new TerminalOutProcess();

		if( $request->ticket_number == '#Auto#' ){

			$ton['saved_by'] = Auth::user()->name;
			$ton['saved_on'] = date('Y-m-d G:i:s');
			$ton['saved_ip'] = request()->ip();

		}else{

			$ton['edit_by'] = Auth::user()->name;
			$ton['edit_on'] = date('Y-m-d G:i:s');
			$ton['edit_ip'] = request()->ip();
		}

		return $ton;
	}

	private function getTerminalOutNoteDetailTable($request){

		$objTerminalOutProcess = new TerminalOutProcess();

		$terminal_serials = rtrim(ltrim($request->terminal_serial));
		$terminal_serials = explode(chr(13), $terminal_serials);

		$order_number = 1;
		foreach($terminal_serials as $serial_number){

			$ton_detail[$order_number]['ticketno'] = $request->ticket_number;
			$ton_detail[$order_number]['ono'] = $order_number;
			$ton_detail[$order_number]['serialno'] = rtrim(ltrim($serial_number));
			$ton_detail[$order_number]['model'] = $objTerminalOutProcess->getModel($serial_number);
			$ton_detail[$order_number]['spare_part_id'] = 0;
			$ton_detail[$order_number]['spare_part_name'] = '';
			$ton_detail[$order_number]['quantity'] = 0;

			$order_number++;
		}

		return $ton_detail;
	}

	private function confirmTerminalOutNoteProcess($request){

		$ton_confirm_validation_process_result = $this->terminalOutNoteConfirmValidationProcess($request);

		if($ton_confirm_validation_process_result['validation_result'] == TRUE){

			$confirm_process_result = $this->tonConfirmProcess($request);

			$confirm_process_result['validation_result'] = $ton_confirm_validation_process_result['validation_result'];
			$confirm_process_result['validation_messages'] = $ton_confirm_validation_process_result['validation_messages'];

			return $confirm_process_result;

		}else{

			$ton_confirm_validation_process_result['ticket_number'] = $request->ticket_number;
			$ton_confirm_validation_process_result['process_status'] = FALSE;

			return $ton_confirm_validation_process_result;
		}
	}

	private function terminalOutNoteConfirmValidationProcess($request){

		try{

			$front_end_message = '';

			$input['ticket_number'] = $request->ticket_number;

			$rules['ticket_number'] = array('required', Rule::notIn(['#Auto#']), new TerminalOutCancelValidation(), new TerminalOutConfirmValidation());

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Terminal Out Note Controller - Confirm Validation Process ';

            return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Terminal Out Note Controller - Confirm Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

			return $process_result;
		}
	}

	private function tonConfirmProcess($request){

		try{

			$objTerminalOutProcess = new TerminalOutProcess();

			$data['confirm_row'] = $this->getConfirmRow($request);
			$data['terminal_log'] = $this->getTerminalLogDetail($request);
            $data['tmc_bin'] = $this->getTmcBinDetail($request);

			$terminal_out_note_confirm_result = $objTerminalOutProcess->confirmTerminalOutNote($data, $request->ticket_number);

			return $terminal_out_note_confirm_result;

		}catch(\Exception $e){

			$process_result['ticket_number'] = $request->ticket_number;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Terminal Out Note Controller -> Terminal Out Note Confirm Process <br> ' . $e->getLine();

            return $process_result;
		}

	}

	private function getConfirmRow($request){

		$confirm_row['ticketno'] = $request->ticket_number;
		$confirm_row['confirm'] = 1;
		$confirm_row['confirm_by'] = Auth::user()->name;
		$confirm_row['confirm_on'] = date('Y-m-d G:i:s');
		$confirm_row['confirm_ip'] = request()->ip();

		return $confirm_row;
	}

	private function getTerminalLogDetail($request){

		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$arr[$icount]['serialno'] = rtrim(ltrim($terminal));
			$arr[$icount]['ref'] = 'terminal_out';
			$arr[$icount]['refno'] = $request->ticket_number;
			$arr[$icount]['refdate'] = date('Y-m-d G:i:s');

			$icount++;
		}

		return $arr;
	}

    private function getTmcBinDetail($request){

		$objTerminalOutProcess = new TerminalOutProcess();
		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$arr[$icount]['serial_number'] = rtrim(ltrim($terminal));
			$arr[$icount]['model'] = $objTerminalOutProcess->getModel($terminal);
			$arr[$icount]['bank'] = $request->bank;
			$arr[$icount]['released'] = 1;
			$arr[$icount]['released_workflow_id'] = 13;
			$arr[$icount]['released_workflow_name'] = 'Terminal Out';
			$arr[$icount]['released_workflow_number'] = $request->ticket_number;
			$arr[$icount]['released_workflow_date'] = $request->ticket_date;

			$icount++;
		}

		return $arr;
	}

	private function cancelTerminalOutNoteProcess($request){

		$ton_cancel_validation_process_result = $this->terminalOutNoteCancelValidationProcess($request);

		if($ton_cancel_validation_process_result['validation_result'] == TRUE){

			$cancel_process_result = $this->tonCancelProcess($request);

			$cancel_process_result['validation_result'] = $ton_cancel_validation_process_result['validation_result'];
			$cancel_process_result['validation_messages'] = $ton_cancel_validation_process_result['validation_messages'];

			return $cancel_process_result;

		}else{

			$ton_cancel_validation_process_result['ticket_number'] = $request->ticket_number;
			$ton_cancel_validation_process_result['process_status'] = FALSE;

			return $ton_cancel_validation_process_result;
		}
	}

	private function terminalOutNoteCancelValidationProcess($request){

		//try{

			$front_end_message = '';

			$input['ticket_number'] = $request->ticket_number;
			$input['cancel_reason'] = $request->cancel_reason;

			$rules['ticket_number'] = array('required', Rule::notIn(['#Auto#']), new TerminalOutCancelValidation(), new TerminalOutConfirmValidation());
			$rules['cancel_reason'] = array('required', 'max:75');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Terminal Out Note Controller - Cancel Validation Process ';

            return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Terminal Out Note Controller - Cancel Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

		// 	return $process_result;
		// }
	}

	private function tonCancelProcess($request){

		//try{

			$objTerminalOutProcess = new TerminalOutProcess();

			$data['cancel_row'] = $this->getCancelRow($request);

			$ton_cancel_process_result = $objTerminalOutProcess->cancelTerminalOutNote($data, $request->ticket_number);

			return $ton_cancel_process_result;

		// }catch(\Exception $e){

		// 	$process_result['ticket_number'] = $request->ticket_number;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal Out Note Controller -> Terminal Out Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function getCancelRow($request){

		$cancel_row['ticketno'] = $request->ticket_number;
		$cancel_row['cancel'] = 1;
		$cancel_row['cancel_reason'] = $request->cancel_reason;
		$cancel_row['cancel_by'] = Auth::user()->name;
		$cancel_row['cancel_on'] = date('Y-m-d G:i:s');
		$cancel_row['cancel_ip'] = request()->ip();

		return $cancel_row;
	}

	public function terminal_out_note_courier_slip(Request $request){

		$objFpdf = new FPDF();
		$objTerminalOutProcess = new TerminalOutProcess();

		$terminal_out_result = $objTerminalOutProcess->getTerminalOutNoteWithOfficerDetail($request->slip_ticket_no);

		foreach($terminal_out_result as $row){

			$objFpdf->AddPage();
		    $objFpdf->SetFont('Arial','B',8);

		    $jobno='JOB NO. ' . $row->ticketno;

		    $n=chr(10);
		    echo $n;
		    $merchant2 = explode($n, $row->address);

		    $objFpdf->SetXY(50,35 );
		    $objFpdf->Cell(0,0, $row->tdate,0,0,'L');
		    $objFpdf->SetXY(50,39 );
		    $objFpdf->Cell(0,0,$jobno ,0,0,'L');
			$objFpdf->SetXY(50,43 );
			$objFpdf->Cell(0,0, '[Terminal Out]' ,0,0,'L');

		    $objFpdf->SetXY(28,50 );
		    $objFpdf->Cell(0,0, $row->officer_name ,0,0,'L');

		    $x=28;
		    $y=55;
		    foreach($merchant2 as $m){

		        $objFpdf->SetXY($x,$y );
		        $objFpdf->Cell(0,0,$m ,0,0,'L');
		        $y=$y+5;
		    }

		    //Corrected
		    $objFpdf->SetXY(38,102 );
		    $objFpdf->Cell(0,0, $row->phone ,0,0,'L');

		}

        $objFpdf->Output();

        exit;
	}

	public function terminal_out_note_print_document(Request $request){

		$html_code = $this->getHtmlCode($request->print_ticket_no);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html_code);
        $pdf->setPaper('A4', 'portrait');

		//echo $html_code;

		return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));

	}

	private function getHtmlCode($ticket_no){

        $ticket_number = '';
        $ticket_date = '';
        $bank = '';
        $remark = '';
        $ticket_count = 0;

		$html_first_code = '';
		$html_second_code = '';
		$html_third_code = '';
		$html_fourth_code = '';
		$html_fifth_code = '';

        $objTerminalOutProcess = new TerminalOutProcess();
        $objBank = new Bank();

        $terminal_out_result = $objTerminalOutProcess->getTerminalOutNote($ticket_no);
        foreach($terminal_out_result as  $row){

            $ticket_number = $row->ticketno;
            $ticket_date = $row->tdate;
            $bank = $objBank->getBankName($row->bank);
            $remark = $row->remark;
        }

        $terminal_out_detail_result = $objTerminalOutProcess->getTerminalOutNoteDetail($ticket_no);
        $ticket_count = count($terminal_out_detail_result);

        $html_first_code = '    <!DOCTYPE html>
                                <html>
                                    <head>
                                    </head>
                                <body> ';

        $html_second_code = ' <h2 style="text-align: center;">Terminal Out Note</h2>

                                <hr>

                                <table style="width: 100%;">
                                    <tr>
                                      <td style="font-family: Consolas; font-size: 12px; width: 50%; text-align: left;">Ref No. '. $ticket_number .' </td>
                                      <td style="font-family: Consolas; font-size: 12px; width: 50%; text-align: right;">Date :- '. $ticket_date .'   </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Consolas; font-size: 12px; width: 50%; text-align: left;">Bank :- '. $bank .' </td>
                                        <td style="font-family: Consolas; font-size: 12px; width: 50%; text-align: right;">Terminal Count :- '. $ticket_count .'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Consolas; font-size: 12px; width: 100%; text-align: left;">Remark :-  '. $remark .' </td>
                                    </tr>
                                 </table>

                                 <hr>

								 ';

        $html_third_code = ' <table style="width: 100%;  border-collapse: collapse;">
                                <tr>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">No.</th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">Serial No.</th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 10%; text-align: left;">Model</th>
                                    <th></th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">No.</th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">Serial No.</th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 10%; text-align: left;">Model</th>
                                    <th></th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">No.</th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">Serial No.</th>
                                    <th style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 10%; text-align: left;">Model</th>
                                    <th></th>
                                </tr>';


        $serial_number_array = array();
        $model_array = array();
        $icount = 1;
        foreach($terminal_out_detail_result as $row){

            $serial_number_array[$icount] = $row->serialno;
            $model_array[$icount] = $row->model;

            $icount++;
        }

        //$icount = 1;
        for ($icount = 1; $icount <= count($serial_number_array); $icount++) {

            $first_serial_number = $serial_number_array[$icount];
            $first_model = $model_array[$icount];
            $one_counter = $icount++;
            if( count($serial_number_array) < $icount){

				$serial_number_array[$icount] = '';
				$model_array[$icount] = '';
			}

            $second_serial_number = $serial_number_array[$icount];
            $second_model = $model_array[$icount];
            $second_counter = $icount++;
            if( count($serial_number_array) < $icount){

				$serial_number_array[$icount] = '';
				$model_array[$icount] = '';
			}

            $third_serial_number = $serial_number_array[$icount];
            $third_model = $model_array[$icount];
            $third_counter = $icount++;
			$icount--;
			if( count($serial_number_array) < $icount){

				$serial_number_array[$icount] = '';
				$model_array[$icount] = '';
			}

            $html_fourth_code .= ' <tr>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">'. $one_counter .'</td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 10%; text-align: left;">'. $first_serial_number .'</td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 15%; text-align: left;">'. $first_model .'</td>
                                        <td style="width: 5%;"></td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">'. $second_counter .'</td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 10%; text-align: left;">'. $second_serial_number .'</td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 15%; text-align: left;">'. $second_model .'</td>
                                        <td style="width: 5%;"></td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 5%; text-align: left;">'. $third_counter .'</td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 10%; text-align: left;">'. $third_serial_number .'</td>
                                        <td style="border: 1px solid black;font-family: Consolas; font-size: 10px; width: 15%; text-align: left;">'. $third_model .'</td>
                                        <td style="width: 5%;"></td>
                                    </tr> ';

            if( count($serial_number_array) < $icount) break;
        }

		$html_fifth_code .= ' </table> <p style="font-family: Consolas; font-size: 11px;"> ';

		$result = $objTerminalOutProcess->getModelWiseTerminalCount($ticket_no);
		foreach($result as $row){

			$html_fifth_code .= $row->model . ' :- ' . $row->terminal_count . ', ';
		}
		$html_fifth_code = substr(rtrim($html_fifth_code),0,-1);
		$html_fifth_code .= ' </p> <br><br>';

		$html_fifth_code .= ' <table style="width: 100%;  border-collapse: collapse;">
								<tr>
									<th style="font-family: Consolas; font-size: 10px; width: 5%;">Name</th>
									<th style="font-family: Consolas; font-size: 10px; width: 15%";>---------------------------------</th>
									<th style="font-family: Consolas; font-size: 10px; width: 5%;">Date</th>
									<th style="font-family: Consolas; font-size: 10px; width: 15%";>---------------------------------</th>
									<th style="font-family: Consolas; font-size: 10px; width: 5%;">Signature</th>
									<th style="font-family: Consolas; font-size: 10px; width: 15%";>---------------------------------</th>
								</tr>
							  </table>' ;

        return  $html_first_code . $html_second_code . $html_third_code . $html_fourth_code . $html_fifth_code . ' </body> </html>';
    }

	public function getTerminalOutNote(Request $request){

		$objBank = new Bank();
		$objUser = new User();
		$objCourier = new CourierProcess();
		$objTerminalOutProcess = new TerminalOutProcess();

		$data['bank'] = $objBank->get_bank();
		$data['courier'] = $objCourier->getActiveCourierProviderList();
		$data['officer'] = $objUser->getActiveFieldOfficers();
		$data['source'] = $objTerminalOutProcess->getSparePartIssueType();
		$data['type'] = array('Terminal', 'Spare Part');

		$process_result['ticket_number'] = $request->ticket_number;
        $process_result['process_status'] = TRUE;
		$process_result['validation_result'] = TRUE;
		$process_result['validation_messages'] = new MessageBag();
		$process_result['front_end_message'] = '';
        $process_result['back_end_message'] = '';

		$data['attributes'] = $this->getTerminalOutNoteAttributes($process_result, NULL);

		return view('tmc.inout.terminal_out_note')->with('TON', $data);
	}


}
