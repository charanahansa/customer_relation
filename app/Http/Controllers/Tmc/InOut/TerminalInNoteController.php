<?php

namespace App\Http\Controllers\Tmc\InOut;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Models\Master\Bank;
use App\Models\Tmc\InOut\TerminalInProcess;

use Illuminate\Validation\Rule;
use App\Rules\ZeroValidation;
use App\Rules\Tmc\InOut\TerminalInCancelValidation;
use App\Rules\Tmc\InOut\TerminalInConfirmValidation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App;

class TerminalInNoteController extends Controller {

	public function index(){

		$objBank = new Bank();
		$objTerminalInProcess = new TerminalInProcess();

		$data['bank'] = $objBank->get_bank();
		$data['attributes'] = $this->getTerminalInNoteAttributes(NULL, NULL);

		return view('tmc.inout.terminal_in_note')->with('TIN', $data);
	}

	private function getTerminalInNoteAttributes($process, $request){

		$attributes['ticket_number'] = '#Auto#';
        $attributes['ticket_date'] = '';
		$attributes['bank'] = "0";
		$attributes['remark'] = '';
		$attributes['cancel_reason'] = '';
		$attributes['terminal_serial'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		$objTerminalInProcess = new TerminalInProcess();
		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$terminal_out_note_result = $objTerminalInProcess->getTerminalInNote($process['ticket_number']);
			$terminal_out_note_detail_result = $objTerminalInProcess->getTerminalInNoteDetail($process['ticket_number']);

			foreach($terminal_out_note_result as $row){

				$attributes['ticket_number'] = $row->ticketno;
		        $attributes['ticket_date'] = $row->tdate;
				$attributes['bank'] = $row->bank;
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
				$attributes['bank'] = $input['bank'];
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

	public function terminal_in_note_process(Request $request){

		$objBank = new Bank();
		$objTerminalInProcess = new TerminalInProcess();

		$terminal_in_note_process_result = '';

		if( ($request->submit == 'Save') || ($request->submit == 'Confirm') ){

			$terminal_in_note_process_result = $this->saveTerminalInNoteProcess($request);

			if($request->submit == 'Confirm'){

				if($terminal_in_note_process_result['process_status'] == TRUE){

					$terminal_in_note_process_result = $this->confirmTerminalOutNoteProcess($request);
				}
			}
		}

		if($request->submit == 'Cancel'){

			$terminal_in_note_process_result = $this->cancelTerminalInNoteProcess($request);
		}

		if($request->submit == 'Reset'){

			$terminal_in_note_process_result = NULL;
			$request = NULL;
		}

		$data['bank'] = $objBank->get_bank();
		$data['attributes'] = $this->getTerminalInNoteAttributes($terminal_in_note_process_result, $request);

		return view('tmc.inout.terminal_in_note')->with('TIN', $data);
	}

	private function saveTerminalInNoteProcess($request){

		$tin_validation_process_result = $this->terminalInNoteValidationProcess($request);

		if($tin_validation_process_result['validation_result'] == TRUE){

			$saving_process_result = $this->tinSavingProcess($request);

			$saving_process_result['validation_result'] = $tin_validation_process_result['validation_result'];
			$saving_process_result['validation_messages'] = $tin_validation_process_result['validation_messages'];

			return $saving_process_result;

		}else{

			$tin_validation_process_result['ticket_number'] = $request->ticket_number;
			$tin_validation_process_result['process_status'] = FALSE;

			return $tin_validation_process_result;
		}
	}

	private function terminalInNoteValidationProcess($request){

		try{

			$front_end_message = '';

			/* ----------------------------------------------  Inputs ----------------------------------------------- */
			$input['ticket_number'] = $request->ticket_number;
			$input['ticket_date'] = $request->ticket_date;
			$input['bank'] = $request->bank;
            $input['remark'] = $request->remark;

			/* ----------------------------------------------  Rules ----------------------------------------------- */

			$rules['ticket_number'] = array('required', new TerminalInCancelValidation(), new TerminalInConfirmValidation());
			$rules['ticket_date'] = array('required', 'date');
			$rules['bank'] = array('required', new ZeroValidation('Bank', $request->bank));
            $rules['remark'] = array('required', 'max:500');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Terminal In Note Controller - Validation Process ';

            return $process_result;


		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Terminal In Note Controller - Validation Function Fault';

			return $process_result;
		}
	}

	private function tinSavingProcess($request){

		//try{

			$objTerminalInProcess = new TerminalInProcess();

			$data['terminal_in_note'] = $this->getTerminalInNoteTable($request);
			$data['terminal_in_note_detail'] = $this->getTerminalInNoteDetailTable($request);

			$tin_saving_process_result = $objTerminalInProcess->saveTerminalInNote($data);

			return $tin_saving_process_result;

		// }catch(\Exception $e){

		// 	$process_result['ticket_number'] = $request->ticket_number;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal In Note Controller -> Terminal In Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function getTerminalInNoteTable($request){

		$tin['ticketno'] = $request->ticket_number;
		$tin['tdate'] = $request->ticket_date;
		$tin['bank'] = $request->bank;
		$tin['jobcard_no'] = NULL;
		$tin['fault'] = NULL;
		$tin['receive_type'] = NULL;
		$tin['officer'] = NULL;
		$tin['refno'] = NULL;
		$tin['referance'] = NULL;
		$tin['podno'] = NULL;
		$tin['return_podno'] = NULL;
		$tin['remark'] = $request->remark;
		$tin['confirm'] = 0;
		$tin['cancel'] = 0;
		$tin['cancel_reason'] = "";

		if( $request->ticket_number == '#Auto#' ){

			$tin['saved_by'] = Auth::user()->name;
			$tin['saved_on'] = date('Y-m-d G:i:s');
			$tin['saved_ip'] = request()->ip();

		}else{

			$tin['edit_by'] = Auth::user()->name;
			$tin['edit_on'] = date('Y-m-d G:i:s');
			$tin['edit_ip'] = request()->ip();
		}

		return $tin;
	}

	private function getTerminalInNoteDetailTable($request){

		$objTerminalInProcess = new TerminalInProcess();

		$terminal_serials = rtrim(ltrim($request->terminal_serial));
		$terminal_serials = explode(chr(13), $terminal_serials);

		$order_number = 1;
		foreach($terminal_serials as $serial_number){

			$tin_detail[$order_number]['ticketno'] = $request->ticket_number;
			$tin_detail[$order_number]['ono'] = $order_number;
			$tin_detail[$order_number]['serialno'] = rtrim(ltrim($serial_number));
			$tin_detail[$order_number]['model'] = $objTerminalInProcess->getModel($serial_number);
			$tin_detail[$order_number]['printer_serial'] = '-';
			$tin_detail[$order_number]['item_id'] = '0';
			$tin_detail[$order_number]['item_description'] = '-';

			$order_number++;
		}

		return $tin_detail;
	}

	private function confirmTerminalOutNoteProcess($request){

		$tin_confirm_validation_process_result = $this->terminalInNoteConfirmValidationProcess($request);

		if($tin_confirm_validation_process_result['validation_result'] == TRUE){

			$confirm_process_result = $this->tinConfirmProcess($request);

			$confirm_process_result['validation_result'] = $tin_confirm_validation_process_result['validation_result'];
			$confirm_process_result['validation_messages'] = $tin_confirm_validation_process_result['validation_messages'];

			return $confirm_process_result;

		}else{

			$tin_confirm_validation_process_result['ticket_number'] = $request->ticket_number;
			$tin_confirm_validation_process_result['process_status'] = FALSE;

			return $tin_confirm_validation_process_result;
		}
	}

	private function terminalInNoteConfirmValidationProcess($request){

		try{

			$front_end_message = '';

			$input['ticket_number'] = $request->ticket_number;

			$rules['ticket_number'] = array('required', Rule::notIn(['#Auto#']), new TerminalInCancelValidation(), new TerminalInConfirmValidation());

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Terminal In Note Controller - Confirm Validation Process ';

            return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Terminal In Note Controller - Confirm Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

			return $process_result;
		}
	}

	private function tinConfirmProcess($request){

		try{

			$objTerminalInProcess = new TerminalInProcess();

			$data['confirm_row'] = $this->getConfirmRow($request);
			$data['terminal_log'] = $this->getTerminalLogDetail($request);
            $data['tmc_bin'] = $this->getTmcBinDetail($request);

			$terminal_in_note_confirm_result = $objTerminalInProcess->confirmTerminalOutNote($data, $request->ticket_number);

			return $terminal_in_note_confirm_result;

		}catch(\Exception $e){

			$process_result['ticket_number'] = $request->ticket_number;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Terminal In Note Controller -> Terminal In Note Confirm Process <br> ' . $e->getLine();

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

		$objTerminalInProcess = new TerminalInProcess();
		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$arr[$icount]['serialno'] = rtrim(ltrim($terminal));
			$arr[$icount]['model'] = $objTerminalInProcess->getModel($terminal);
			$arr[$icount]['bank'] = $request->bank;
			$arr[$icount]['ownership'] = NULL;
			$arr[$icount]['delivery_no'] = NULL;
			$arr[$icount]['delivery_date'] = NULL;
			$arr[$icount]['maintain'] = NULL;
			$arr[$icount]['warrenty'] = NULL;
			$arr[$icount]['warenty_month'] = NULL;
			$arr[$icount]['tid'] = NULL;
			$arr[$icount]['merchant'] = NULL;
			$arr[$icount]['ref'] = 'terminal_in';
			$arr[$icount]['refno'] = $request->ticket_number;
			$arr[$icount]['refdate'] = date('Y-m-d G:i:s');

			$icount++;
		}

		return $arr;
	}

    private function getTmcBinDetail($request){

		$objTerminalInProcess = new TerminalInProcess();
		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$arr[$icount]['serial_number'] = rtrim(ltrim($terminal));
			$arr[$icount]['model'] = $objTerminalInProcess->getModel($terminal);
			$arr[$icount]['bank'] = $request->bank;
			$arr[$icount]['in_workflow_id'] = 13;
			$arr[$icount]['in_workflow_name'] = 'Terminal In';
			$arr[$icount]['in_workflow_number'] = $request->ticket_number;
			$arr[$icount]['in_workflow_date'] = $request->ticket_date;
			$arr[$icount]['released'] = 0;
			$arr[$icount]['released_workflow_id'] = 0;
			$arr[$icount]['released_workflow_name'] = NULL;
			$arr[$icount]['released_workflow_number'] = NULL;
			$arr[$icount]['released_workflow_date'] = NULL;

			$icount++;
		}

		return $arr;
	}

	private function cancelTerminalInNoteProcess($request){

		$tin_cancel_validation_process_result = $this->terminalInNoteCancelValidationProcess($request);

		if($tin_cancel_validation_process_result['validation_result'] == TRUE){

			$cancel_process_result = $this->tinCancelProcess($request);

			$cancel_process_result['validation_result'] = $tin_cancel_validation_process_result['validation_result'];
			$cancel_process_result['validation_messages'] = $tin_cancel_validation_process_result['validation_messages'];

			return $cancel_process_result;

		}else{

			$tin_cancel_validation_process_result['ticket_number'] = $request->ticket_number;
			$tin_cancel_validation_process_result['process_status'] = FALSE;

			return $tin_cancel_validation_process_result;
		}
	}

	private function terminalInNoteCancelValidationProcess($request){

		//try{

			$front_end_message = '';

			$input['ticket_number'] = $request->ticket_number;
			$input['cancel_reason'] = $request->cancel_reason;

			$rules['ticket_number'] = array('required', Rule::notIn(['#Auto#']), new TerminalInCancelValidation(), new TerminalInConfirmValidation());
			$rules['cancel_reason'] = array('required', 'max:75');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Terminal In Note Controller - Cancel Validation Process ';

            return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Terminal In Note Controller - Cancel Validation Process <br> Line ' . $e->getLine()  . '<br> Code ' .$e->getCode() . '<br> File ' .$e->getFile();

		// 	return $process_result;
		// }
	}

	private function tinCancelProcess($request){

		//try{

			$objTerminalInProcess = new TerminalInProcess();

			$data['cancel_row'] = $this->getCancelRow($request);

			$tin_cancel_process_result = $objTerminalInProcess->cancelTerminalInNote($data, $request->ticket_number);

			return $tin_cancel_process_result;

		// }catch(\Exception $e){

		// 	$process_result['ticket_number'] = $request->ticket_number;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal In Note Controller -> Terminal In Note Saving Process <br> ' . $e->getLine();

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

	public function terminal_in_note_print_document(Request $request){

		$html_code = $this->getHtmlCode($request->print_ticket_no);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html_code);
        $pdf->setPaper('A4', 'portrait');

		//echo $html_code;

		return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));
	}

	private function getHtmlCode($ticket_no = 1407){

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

        $objTerminalInProcess = new TerminalInProcess();
        $objBank = new Bank();

        $terminal_out_result = $objTerminalInProcess->getTerminalInNote($ticket_no);
        foreach($terminal_out_result as  $row){

            $ticket_number = $row->ticketno;
            $ticket_date = $row->tdate;
            $bank = $objBank->getBankName($row->bank);
            $remark = $row->remark;
        }

        $terminal_out_detail_result = $objTerminalInProcess->getTerminalInNoteDetail($ticket_no);
        $ticket_count = count($terminal_out_detail_result);

        $html_first_code = '    <!DOCTYPE html>
                                <html>
                                    <head>
                                    </head>
                                <body> ';

        $html_second_code = ' <h2 style="text-align: center;">Terminal In Note</h2>

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
            if( count($serial_number_array) <= $icount){

				$serial_number_array[$icount] = '';
				$model_array[$icount] = '';
			}

            $second_serial_number = $serial_number_array[$icount];
            $second_model = $model_array[$icount];
            $second_counter = $icount++;
            if( count($serial_number_array) <= $icount){

				$serial_number_array[$icount] = '';
				$model_array[$icount] = '';
			}

            $third_serial_number = $serial_number_array[$icount];
            $third_model = $model_array[$icount];
            $third_counter = $icount++;
			$icount--;
			if( count($serial_number_array) <= $icount){

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

		$result = $objTerminalInProcess->getModelWiseTerminalCount($ticket_no);
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

	public function getTerminalInNote(Request $request){

		$objBank = new Bank();

		$data['bank'] = $objBank->get_bank();

		$process_result['ticket_number'] = $request->ticket_number;
        $process_result['process_status'] = TRUE;
		$process_result['validation_result'] = TRUE;
		$process_result['validation_messages'] = new MessageBag();
		$process_result['front_end_message'] = '';
        $process_result['back_end_message'] = '';

		$data['attributes'] = $this->getTerminalInNoteAttributes($process_result, NULL);

		return view('tmc.inout.terminal_in_note')->with('TIN', $data);
	}

}
