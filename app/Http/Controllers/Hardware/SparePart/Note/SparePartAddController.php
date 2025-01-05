<?php

namespace App\Http\Controllers\Hardware\SparePart\Note;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Hardware\SparePart\SparePartProcess;
use Illuminate\Support\Carbon;

class SparePartAddController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objSparePartProcess = new SparePartProcess();

        $data['model'] = $objSparePartProcess->get_models();
        $data['attributes'] = $this->get_spare_part_add_note_attributes(NULL, NULL);

		return view('Hardware.spare_part.note.spare_part_add_note')->with('SPA', $data);
	}

    public function load_bulk(){

        $objSparePartProcess = new SparePartProcess();

        $data['model'] = $objSparePartProcess->get_models();
        $data['attributes'] = $this->get_spare_part_add_note_attributes(NULL, NULL);

		return view('Hardware.spare_part.note.spare_part_add_note_bulk')->with('SPA', $data);
    }

    private function get_spare_part_add_note_attributes($process, $request){


		$attributes['spare_part_id'] = '#Auto#';
        $attributes['add_date'] = date("Y/m/d");
		$attributes['spare_part_no'] = '';
		$attributes['spare_part_name'] = '';
		$attributes['model'] = 0;
        $attributes['spare_part_category'] = '';
		$attributes['price'] = 0;
		$attributes['quantity'] = '';
		$attributes['active'] = 1;
        $attributes['remark'] = '';
        $attributes['spare_part_detail'] = '';
		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }
    }

    public function spare_part_add_process_individual(Request $request){



    }

    public function spare_part_add_process_bulk(Request $request){

        $spare_part_detail = $request->spare_part_detail;
		$spare_part_detail_array = explode(chr(13), $spare_part_detail);
		foreach($spare_part_detail_array as $row){

			$row_arr = explode(chr(9), $row);

            $spare_part_detail = array();

			$spare_part_detail['part_no'] = $row_arr[0];
			$spare_part_detail['part_name'] = $row_arr[1];
			$spare_part_detail['Part_Catogory'] = $row_arr[2];
			$spare_part_detail['main_qty'] = $row_arr[3];
			$spare_part_detail['price'] = $row_arr[4];
            $spare_part_detail['active'] = 1;
			$spare_part_detail['Model'] = $request->model;
			$spare_part_detail['remark'] = $request->remark;
            $spare_part_detail['add_by'] = Auth::user()->id;
			$spare_part_detail['add_on'] = Carbon::now()->toDateTimeString();

            $spare_part_dvalidation_result = $this->spare_part_add_validation_process($spare_part_detail);

            if($spare_part_dvalidation_result['validation_result'] == TRUE){

                $objSparePartProcess = new SparePartProcess();
                $objSparePartProcess->spare_part_add_process($spare_part_detail);

            }
		}

		$objSparePartProcess = new SparePartProcess();

        $data['model'] = $objSparePartProcess->get_models();
        $data['attributes'] = $this->get_spare_part_add_note_attributes(NULL, NULL);

		$data['attributes']['spare_part_detail'] = $request->spare_part_detail;

		return view('Hardware.spare_part.note.spare_part_add_note_bulk')->with('SPA', $data);

    }

    private function spare_part_add_validation_process($spare_part_row){

        try{

			$front_end_message = " ";

			$input['spare_part_no'] = $spare_part_row['part_no'];
	        $input['spare_part_name'] = $spare_part_row['part_name'];
            $input['spare_part_category'] = $spare_part_row['Part_Catogory'];
            $input['spare_part_quantity'] = $spare_part_row['main_qty'];
            $input['spare_part_price'] = $spare_part_row['price'];
            $input['model'] = $spare_part_row['Model'];
            $input['remark'] = $spare_part_row['remark'];

			$rules['spare_part_no'] = array('required', 'max:20');
	        $rules['spare_part_name'] = array('required','max:100');
            $rules['spare_part_category'] =  array('required','max:20');
            $rules['spare_part_quantity'] =  array('required');
            $rules['spare_part_price'] =  array('required');
            $rules['model'] = array('required');
            $rules['remark'] = array('max:100');

			$validator = Validator::make($input, $rules);
	        $validation_result = $validator->passes();
	        if($validation_result == FALSE){

	            $front_end_message = 'Please Check Your Inputs';
	        }

	        $process_result['validation_result'] =  $validation_result;
	        $process_result['validation_messages'] =  $validator->errors();
	        $process_result['front_end_message'] = $front_end_message;
	        $process_result['back_end_message'] =  'Spare Part Request Controller - Validation Process ';

	        return $process_result;

		}catch(\Exception $e){

			$process_result['validation_result'] = FALSE;
            $process_result['validation_messages'] = new MessageBag();
            $process_result['front_end_message'] =  $e->getMessage();
            $process_result['back_end_message'] =  'Spare Part Request Controller - Validation Function Fault';

			return $process_result;
		}
    }

}
