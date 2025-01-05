<?php

namespace App\Http\Controllers\Tmc\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\TerminalModel;
use App\Models\Master\Bank;
use App\Models\Tmc\Delivery\Delivery;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Rules\NotValidation;
use App\Rules\Tmc\Backup\CancelValidation;
use App\Rules\Tmc\Backup\ConfirmValidation;

class DeliveryOrderController extends Controller {

    public function index(){

		$objModel = new TerminalModel();
		$objBank = new Bank();

		$data['model'] = $objModel->get_models();
        $data['bank'] = $objBank->get_bank();
		$data['attributes'] = $this->getDeliveryOrderAttributes(NULL, NULL);

		return view('tmc.delivery.delivery_order')->with('DON', $data);
	}

	private function getDeliveryOrderAttributes($process, $request){

		$attributes['delivery_id'] = '';
        $attributes['delivery_date'] = '';
        $attributes['bank'] = "Not";
        $attributes['invoice_number'] = '';
        $attributes['invoice_date'] = '';
        $attributes['sales_order_number'] = '';
        $attributes['sales_order_date'] = '';
        $attributes['model'] = "Not";
		$attributes['remark'] = '';
		$attributes['terminal_serial'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

            $objDelivery = new Delivery();

            $delivery_order_result = $objDelivery->getDeliveryOrder($process['delivery_id']);
			$delivery_order_detail_result = $objDelivery->getDeliveryOrderDetail($process['delivery_id']);

			foreach($delivery_order_result as $row){

				$attributes['delivery_id'] = $row->delivery_id;
		        $attributes['delivery_date'] = $row->delivery_date;
                $attributes['bank'] = $row->bank;
                $attributes['invoice_number'] = $row->invoice_number;
		        $attributes['invoice_date'] = $row->invoice_date;
                $attributes['sales_order_number'] = $row->sales_order_number;
		        $attributes['sales_order_date'] = $row->sales_order_date;
				$attributes['remark'] = $row->remark;
			}

			foreach($delivery_order_detail_result as $row){

				$attributes['terminal_serial'] .= $row->serial_number . chr(13);
                $attributes['model'] = $row->model;
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				$attributes['delivery_id'] = $input['delivery_id'];
		        $attributes['delivery_date'] = $input['delivery_date'];
                $attributes['invoice_number'] = $input['invoice_number'];
		        $attributes['invoice_date'] = $input['invoice_date'];
                $attributes['sales_order_number'] = $input['sales_order_number'];
		        $attributes['sales_order_date'] = $input['sales_order_date'];
		        $attributes['model'] = $input['model'];
                $attributes['bank'] = $input['bank'];
				$attributes['remark'] = $input['remark'];

				$attributes['terminal_serial'] = $input['terminal_serial'];
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
		}


		return $attributes;

    }

    public function deliveryOrderProcess(Request $request){

        $objModel = new TerminalModel();
		$objBank = new Bank();

        $delivery_note_validation_result = $this->deliveryNoteValidationProcess($request);

		if($delivery_note_validation_result['validation_result'] == TRUE){

			$saving_process_result = $this->deliverySavingProcess($request);

			$saving_process_result['validation_result'] = $delivery_note_validation_result['validation_result'];
			$saving_process_result['validation_messages'] = $delivery_note_validation_result['validation_messages'];

            $data['attributes'] = $this->getDeliveryOrderAttributes($saving_process_result, $request);

		}else{

			$delivery_note_validation_result['delivery_id'] = $request->delivery_id;
			$delivery_note_validation_result['process_status'] = FALSE;

            $data['attributes'] = $this->getDeliveryOrderAttributes($delivery_note_validation_result, $request);
		}

        $data['model'] = $objModel->get_models();
        $data['bank'] = $objBank->get_bank();
		
		return view('tmc.delivery.delivery_order')->with('DON', $data);
    }

    private function deliveryNoteValidationProcess($request){

        try{

			$front_end_message = '';

			/* ----------------------------------------------  Inputs ----------------------------------------------- */
			$input['delivery_id'] = $request->delivery_id;
			$input['delivery_date'] = $request->delivery_date;
            $input['invoice_number'] = $request->invoice_number;
			$input['invoice_date'] = $request->invoice_date;
            $input['sales_order_number'] = $request->sales_order_number;
			$input['sales_order_date'] = $request->sales_order_date;
            $input['bank'] = $request->bank;
            $input['model'] = $request->model;
            $input['remark'] = $request->remark;
            $input['terminal_serial'] = $request->terminal_serial;

			/* ----------------------------------------------  Rules ----------------------------------------------- */
			$rules['delivery_id'] = array('required');
			$rules['delivery_date'] = array('required', 'date');
            $rules['invoice_number'] = array('required');
			$rules['invoice_date'] = array('required', 'date');
            $rules['sales_order_number'] = array('required');
			$rules['sales_order_date'] = array('required', 'date');    
            $rules['bank'] = array('required', new NotValidation('Bank', $request->bank));   
            $rules['model'] = array('required', new NotValidation('Model', $request->model));      
            $rules['remark'] = array('max:200');
            $rules['terminal_serial'] = array('required');

			$validator = Validator::make($input, $rules);
            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process_result['validation_result'] = $validator->passes();
            $process_result['validation_messages'] =  $validator->errors();
            $process_result['front_end_message'] = $front_end_message;
            $process_result['back_end_message'] =  'Delivery Order Controller - Validation Process ';

            return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Backup Receive Note Controller - Validation Function Fault';

			return $process_result;
		}
    }

    private function deliverySavingProcess($request){

		try{

			$objDelivery = new Delivery();

			$data['delivery_order'] = $this->getDeliveryOrderTable($request);
			$data['delivery_order_detail'] = $this->getDeliveryOrderDetailTable($request);
            $data['terminal_log'] = $this->getTerminalLogTable($request);

			$delivery_order_saving_result = $objDelivery->saveDeliveryOrder($data);

			return $delivery_order_saving_result;

		}catch(\Exception $e){

			$process_result['brn_id'] = $request->brn_id;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Receive Note Controller -> Backup Receive Note Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	private function getDeliveryOrderTable($request){

        $objDelivery = new Delivery();

		$delivery_order['delivery_id'] = $request->delivery_id;
        $delivery_order['delivery_date'] = $request->delivery_date;
        $delivery_order['bank'] = $request->bank;
        $delivery_order['invoice_number'] = $request->invoice_number;
        $delivery_order['invoice_date'] = $request->invoice_date;
        $delivery_order['sales_order_number'] = $request->sales_order_number;
        $delivery_order['sales_order_date'] = $request->sales_order_date;
        $delivery_order['remark'] = $request->remark;
        $delivery_order['cancel'] = 0;
        $delivery_order['cancel_reason'] = '';

        $exist_result = $objDelivery->isExistsDeliveryOrderNumber($request->delivery_id);
        if( $exist_result ){

            $delivery_order['edit_by'] = Auth::user()->name;
		    $delivery_order['edit_on'] = now();
            $delivery_order['edit_ip'] = '';

        }else{

            $delivery_order['saved_by'] = Auth::user()->name;
		    $delivery_order['saved_on'] = now();
            $delivery_order['saved_ip'] = '';
        }

		return $delivery_order;
	}

	private function getDeliveryOrderDetailTable($request){

		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

            $delivery_order_detail[$icount]['model'] = $request->model;
			$delivery_order_detail[$icount]['serial_number'] = rtrim(ltrim($terminal));
			$icount++;
		}

		return $delivery_order_detail;
	}

    private function getTerminalLogTable($request){

		$terminal_detail = explode(chr(13), $request->terminal_serial);

		$icount = 1;
		foreach($terminal_detail as $terminal){

			$terminal_log_table[$icount]['serialno'] = rtrim(ltrim($terminal));
			$terminal_log_table[$icount]['model'] = $request->model;
			$terminal_log_table[$icount]['bank'] = $request->bank;
			$terminal_log_table[$icount]['ownership'] = '';
            $terminal_log_table[$icount]['delivery_no'] = $request->delivery_id;
			$terminal_log_table[$icount]['delivery_date'] = $request->delivery_date;

			$icount++;
		}

		return $terminal_log_table;
	}
    
}
