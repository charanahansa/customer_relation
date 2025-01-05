<?php

namespace App\Http\Controllers\Hardware\SparePart\Note;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\SparePart\Buyer;
use App\Models\Hardware\SparePart\Bin;
use App\Models\Hardware\SparePart\SparePartReceiveProcess;

use App\Rules\ZeroValidation;
use App\Rules\CurrencyValidation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class SparePartReceiveNoteController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objBuyer = new Buyer();
		$objSparePartProcess = new SparePartProcess();
		$objBin = new Bin();

		$data['buyer'] = $objBuyer->get_buyers_active_list();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
		$data['bin'] = $objBin->get_bin_active_list();
		$data['spr_note'] = NULL;
		$data['attributes'] = $this->get_spare_part_receive_note_attributes(NULL, NULL);

		return view('Hardware.spare_part.note.spare_part_receive_note')->with('SPR', $data);;
	}

	private function get_spare_part_receive_note_attributes($process, $request){

		$attributes['spr_id'] = '#Auto#';
        $attributes['spr_date'] = date("Y/m/d");
		$attributes['buyer_id'] = 0;
		$attributes['buyer_name'] = 0;
		$attributes['bin_id'] = 0;
		$attributes['bin_name'] = 0;
		$attributes['part_type'] = '';
		$attributes['jobcard_no'] = '';
		$attributes['spare_part_id'] = '';
		$attributes['spare_part_name'] = '';
		$attributes['price'] = 0;
		$attributes['quantity'] = '';
        $attributes['remark'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			$objSparePartReceiveProcess = new SparePartReceiveProcess();
			$objBin = new Bin();
			$objBuyer = new Buyer();

			$spr_resultset = $objSparePartReceiveProcess->get_spare_part_receive_note($process['spr_id']);
			foreach($spr_resultset as $row){

				$attributes['spr_id'] = $row->spr_id;
		        $attributes['spr_date'] = $row->spr_date;
				$attributes['buyer_id'] = $row->buyer_id;
				$attributes['buyer_name'] = $objBuyer->get_buyer_name($row->buyer_id);
				$attributes['bin_id'] = $row->bin_id;
				$attributes['bin_name'] = $objBin->get_bin_name($row->bin_id);
				$attributes['part_type'] = $row->part_type;
				$attributes['jobcard_no'] = $row->jobcard_no;
				$attributes['spare_part_id'] = $row->spare_part_id;
				$attributes['spare_part_name'] = $row->spare_part_name;
				$attributes['price'] = $row->price;
				$attributes['quantity'] = $row->quantity;
		        $attributes['remark'] = $row->remark;
			}

			$attributes['validation_messages'] = new MessageBag();

			$message = $process['front_end_message'];
			$attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

			return $attributes;

		}else{

			$input = $request->input();
            if(is_null($input) == FALSE){

				//$attributes['spr_id'] = $input['spr_id'];
		        $attributes['spr_date'] = $input['spr_date'];
				$attributes['buyer_id'] = $input['buyer'];
				$attributes['bin_id'] = $input['bin'];
				$attributes['part_type'] = $input['part_type'];
				$attributes['jobcard_no'] = $input['jobcard_no'];
				$attributes['spare_part_id'] = $input['spare_part'];
				$attributes['spare_part_name'] = 'Test';
				$attributes['price'] = $input['price'];
				$attributes['quantity'] = $input['quantity'];
		        $attributes['remark'] = $input['remark'];
			}

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';

			return $attributes;

		}
    }

	public function spare_part_receive_note_process(Request $request){

		$objBuyer = new Buyer();
		$objSparePartProcess = new SparePartProcess();
		$objBin = new Bin();

		$validation_result = $this->validation_process($request);
		if($validation_result['validation_result'] == TRUE){

			$spr_saving_result = $this->saving_process($request);

			$spr_saving_result['validation_result'] = $validation_result['validation_result'];
            $spr_saving_result['validation_messages'] = $validation_result['validation_messages'];

            $data['attributes'] = $this->get_spare_part_receive_note_attributes($spr_saving_result, $request);

		}else{

			$validation_result['spr_id'] = '';
			$validation_result['process_status'] = FALSE;

			$data['attributes'] = $this->get_spare_part_receive_note_attributes($validation_result, $request);
		}

		$data['buyer'] = $objBuyer->get_buyers_active_list();
		$data['spare_part'] = $objSparePartProcess->get_spare_part_active_list();
		$data['bin'] = $objBin->get_bin_active_list();

		return view('Hardware.spare_part.note.spare_part_receive_note')->with('SPR', $data);
	}

	private function validation_process($request){

		//try{

			$front_end_message = " ";

			$input['buyer'] = $request->buyer;
	        $input['bin'] = $request->bin;
			$input['spare_part'] = $request->spare_part;
			$input['price']= $request->price;
			$input['quantity']= $request->quantity;
            $input['remark'] = $request->remark;

			$rules['buyer'] =  array( new ZeroValidation('Buyer', $request->buyer));
	        $rules['bin'] = array( new ZeroValidation('Bin', $request->bin));
			$rules['spare_part'] = array( new ZeroValidation('Spare Part', $request->spare_part));
			$rules['price'] = array('required', 'numeric', new CurrencyValidation(0));
			$rules['quantity']= array('required', 'numeric', new CurrencyValidation(0));
            $rules['remark'] = array('max:500');

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Spare Part Receive Controller - Validation Process ';

	        return $process_result;

		// }catch(\Exception $e){

		// 	$process_result['validation_result'] = FALSE;
        //     $process_result['validation_messages'] = new MessageBag();
        //     $process_result['front_end_message'] =  $e->getMessage();
        //     $process_result['back_end_message'] =  'Spare Part Receive Controller - Validation Function Fault';

		// 	return $process_result;
		// }
	}

	private function saving_process($request){

		//try{

			$objSparePartReceiveProcess = new SparePartReceiveProcess();

			$data['spare_part_receive_note'] = $this->spare_part_receive_note($request);
			$data['hw_bin'] = $this->hardware_bin($request);

			$spare_part_saving_process_result = $objSparePartReceiveProcess->save_spare_part_receive_note($data);

			return $spare_part_saving_process_result;

		// }catch(\Exception $e){

		// 	$process_result['spr_id'] = $request->spr_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Receive Note Controller -> Spare Part Receive Note Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	private function spare_part_receive_note($request){

		$objSparePartProcess = new SparePartProcess();

		$spr_table['spr_id'] = $request->spr_id;
		$spr_table['spr_date'] = $request->spr_date;
		$spr_table['part_type'] = $request->part_type;
		$spr_table['buyer_id'] = $request->buyer;
		$spr_table['bin_id'] = $request->bin;
		$spr_table['jobcard_no'] = $request->jobcard_no;
		$spr_table['remark'] = $request->remark;
		$spr_table['spare_part_id'] = $request->spare_part;
		$spr_table['spare_part_name'] = $objSparePartProcess->get_spare_part_name($request->spare_part);
		$spr_table['quantity'] = $request->quantity;
		$spr_table['price'] = $request->price;
		$spr_table['saved_by'] = Auth::id();
		$spr_table['saved_on'] = now();

		return $spr_table;
	}

	private function hardware_bin($request){

		$objSparePartProcess = new SparePartProcess();

		$hw_bin['bin_id'] = $request->bin;
		$hw_bin['in_id'] = $request->spr_id;
		$hw_bin['in_ref'] = 'SPR';
		$hw_bin['spare_part_id'] = $request->spare_part;
		$hw_bin['spare_part_name'] = $objSparePartProcess->get_spare_part_name($request->spare_part);
		$hw_bin['spare_part_serial'] = 0;
		$hw_bin['quantity'] = 1;
		$hw_bin['remark'] = $request->remark;
		$hw_bin['out_id'] = 0;
		$hw_bin['out_ref'] = '';

		return $hw_bin;
	}

}
