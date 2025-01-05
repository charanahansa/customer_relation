<?php

namespace App\Http\Controllers\Maintainance\Note;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Maintainance\MaintainanceRemoveProcess;
use App\Models\Maintainance\MaintainanceCategory;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Rules\ZeroValidation;

class MaintainanceRemoveNoteController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();
        $objMaintainanceCategory = new MaintainanceCategory();

        $data['bank'] = $objBank->get_bank();
        $data['maintainance_category'] = $objMaintainanceCategory->get_maintainance_category();
        $data['attributes'] = $this->get_maintainance_remove_note_attributes(NULL, NULL);
        return view('maintainance.notes.maintainance_remove_note')->with('MRN', $data);
    }

    private function get_maintainance_remove_note_attributes($process, $request){

        $attributes['ma_date'] = '';
        $attributes['bank'] = 0;
        $attributes['category'] = 0;
        $attributes['delivary_no'] = "";
        $attributes['terminal_serial'] = '';
        $attributes['remark'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

		if( ($process['validation_result'] == TRUE) && ($process['process_status'] == TRUE)){

			
			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $message .' </div> ';

		}else{

			$attributes['validation_messages'] = $process['validation_messages'];

			$message = $process['front_end_message'] .' <br> ' . $process['back_end_message'];
            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $message .' </div> ';
		}

        $input = $request->input();
        if(is_null($input) == FALSE){

            $attributes['ma_date'] = $input['ma_date'];
            $attributes['bank'] = $input['bank'];
            $attributes['category'] = $input['category'];
            $attributes['delivary_no'] = $input['delivary_no'];
            $attributes['remark'] = $input['remark'];
            $attributes['terminal_serial'] = $input['terminal_serial'];
        }

		return $attributes;
    }

    public function maintainance_remove_process(Request $request){

        $objBank = new Bank();
        $objMaintainanceCategory = new MaintainanceCategory();

        $data['bank'] = $objBank->get_bank();
        $data['maintainance_category'] = $objMaintainanceCategory->get_maintainance_category();

        if($request->submit == 'Reset'){

            $data['attributes'] = $this->get_maintainance_remove_note_attributes(NULL, NULL);

            return view('maintainance.notes.maintainance_remove_note')->with('MRN', $data);
        }

        $validation_result = $this->validation($request);
        if($validation_result['validation_result'] == TRUE){

            $saving_process_result = $this->saving_process($request);

            $saving_process_result['validation_result'] = $validation_result['validation_result'];
			$saving_process_result['validation_messages'] = $validation_result['validation_messages'];

            $data['attributes'] = $this->get_maintainance_remove_note_attributes($saving_process_result, $request);
            
        }else{

            $validation_result['process_status'] = FALSE;

			$data['attributes'] = $this->get_maintainance_remove_note_attributes($validation_result, $request);
        }

        return view('maintainance.notes.maintainance_remove_note')->with('MRN', $data);

    }

    private function validation($request){

        $front_end_message = '';

        try{

            $input['ma_date'] = $request->ma_date;
            $input['bank'] = $request->bank;
            $input['category'] = $request->category;
            $input['terminal_serial'] = $request->terminal_serial;
            $input['remark'] = $request->remark;

            $rules['ma_date'] = array('required', 'date');;
            $rules['bank'] = array( new ZeroValidation('Bank', $request->bank));
            $rules['category'] = array( new ZeroValidation('Category', $request->category));
            $rules['terminal_serial'] = array('required');
            $rules['remark'] = array('max:100');

            $validator = Validator::make($input, $rules);

            $validation_result = $validator->passes();
            if($validation_result == FALSE){

                $front_end_message = 'Please Check Your Inputs';
            }

            $process['validation_result'] = $validation_result;
            $process['front_end_message'] = $front_end_message;
            $process['back_end_message'] = '';
            $process['validation_messages'] =  $validator->errors();

            return $process;

        }catch(\Exception $e){

            $process['validation_result'] = FALSE;
            $process['front_end_message'] =  $e->getMessage();
            $process['back_end_message'] =  'Maintainance Add Note Controller : Maintainance Add Note Validation Process ' . $e->getLine();;
            $process['validation_messages'] = new MessageBag();

            return $process;
        }

    }

    private function saving_process($request){

        try{

            $objMaintainanceRemoveProcess = new MaintainanceRemoveProcess();

            $data['maintainance_remove_note'] = $this->get_maintainance_remove_notes($request);

            if($request->category == 1){

                $data['terminal_log'] = $this->get_terminal_log($request);
            }

            $saving_result = $objMaintainanceRemoveProcess->save_maintainance_remove_notes($data);

            return $saving_result;

		}catch(\Exception $e){

            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Maintainance Add Note Controller -> Maintainance Add Note Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
    }

    private function get_maintainance_remove_notes($request){

        $terminal_detail = explode(chr(13), $request->terminal_serial);

        $icount = 1;
        foreach($terminal_detail as $terminal){

            //$maintainance_remove_note[$icount]['MRN_id'] = '';

            $maintainance_remove_note[$icount]['mr_date'] = date('Y/m/d');
            $maintainance_remove_note[$icount]['bank'] = $request->bank;
            $maintainance_remove_note[$icount]['bank_mr_id'] = 0;
            $maintainance_remove_note[$icount]['maintainance_category_id'] = $request->category;
            $maintainance_remove_note[$icount]['referance_number'] = ltrim(rtrim($terminal));
            $maintainance_remove_note[$icount]['remark'] = $request->remark;
            $maintainance_remove_note[$icount]['saved_by'] = Auth::user()->id;
            $maintainance_remove_note[$icount]['saved_on'] = now();
            $maintainance_remove_note[$icount]['cancel'] = 0;
            $maintainance_remove_note[$icount]['cancel_by'] = NULL;
            $maintainance_remove_note[$icount]['cancel_on'] = NULL;
            $maintainance_remove_note[$icount]['cancel_reason'] = NULL;

            $icount ++;
        }

        return $maintainance_remove_note;
    }

    private function get_terminal_log($request){

        $terminal_detail = explode(chr(13), $request->terminal_serial);

        $icount = 1;
        foreach($terminal_detail as $terminal){

            $terminal_log[$icount]['serialno'] = ltrim(rtrim($terminal));
            $terminal_log[$icount]['bank'] = $request->bank;
            $terminal_log[$icount]['delivery_no'] = $request->delivary_no;
            $terminal_log[$icount]['maintain'] = 0;

            $icount ++;
        }

        return $terminal_log;
    }
    
}
