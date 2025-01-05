<?php

namespace App\Http\Controllers\Hardware\SparePart\SparePartList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Hardware\SparePart\SparePartProcess;
use App\Models\Hardware\SparePart\SparePartRequestProcess;

use App\Models\Management\Breakdown;
use App\Mail\Breakdown\breakdown_individual;
use Illuminate\Support\Facades\Mail;

class SparePartListController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objSparePartProcess = new SparePartProcess();
        $objSparePartRequestProcess = new SparePartRequestProcess();

        $data['model'] = $objSparePartProcess->get_models();
        $data['spare_part'] = $objSparePartRequestProcess->get_spare_part();
        $data['report_table'] = $objSparePartProcess->get_spare_part_list();
        $data['attributes'] = $this->get_spare_part_add_note_attributes(NULL);

		return view('hardware.spare_part.spare_part_list.spare_part_list')->with('SPL', $data);
	}

    private function get_spare_part_add_note_attributes($request){

		$attributes['spare_part'] = '';
		$attributes['model'] = 0;

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($request) == TRUE)){

            return $attributes;
        }

		if(is_null($request) == FALSE){

	        $attributes['spare_part'] = $request->spare_part;
			$attributes['model'] = $request->model;

			return $attributes;
		}
    }

	public function spare_part_list_process(Request $request){

		$objSparePartProcess = new SparePartProcess();
        $objSparePartRequestProcess = new SparePartRequestProcess();

		if($request->spare_part !== 0){

			$data['report_table'] = $objSparePartProcess->get_spare_part_list_filter_by_spare_part($request->spare_part);

		}else if($request->model !== 0){

			$data['report_table'] = $objSparePartProcess->get_spare_part_list_filter_by_model($request->model);
		}


		$data['model'] = $objSparePartProcess->get_models();
        $data['spare_part'] = $objSparePartRequestProcess->get_spare_part();
		$data['attributes'] = $this->get_spare_part_add_note_attributes($request);

		return view('hardware.spare_part.spare_part_list.spare_part_list')->with('SPL', $data);

	}

    private function breakdown_email_individual(){

        $objBreakdown = new Breakdown();

        $result = $objBreakdown->get_email_request();
        foreach($result  as $first_row){

            $breakdown_result = $objBreakdown->get_breakdown($first_row->ticket_no);
            foreach($breakdown_result as $second_row){

                $breakdown_email['ticketno'] = $second_row->ticketno;
                $breakdown_email['date'] = $second_row->tdate;
                $breakdown_email['bank'] = $second_row->bank;
                $breakdown_email['tid'] = $second_row->tid;
                $breakdown_email['model'] = $second_row->model;
                $breakdown_email['merchant'] = $second_row->merchant;
                $breakdown_email['fault'] = $second_row->fault;
                $breakdown_email['contact_number'] = $second_row->contactno;
                $breakdown_email['contact_person'] = $second_row->contact_person;
                $breakdown_email['relevent_detail'] = $second_row->relevant_detail;
                $breakdown_email['action_taken'] = $second_row->action_taken;
                $breakdown_email['fault_serialno'] = $second_row->fault_serialno;
                $breakdown_email['replaced_serialno'] = $second_row->replaced_serialno;
                if($first_row->role == 1){

                    $breakdown_email['remark'] = $second_row->cc_remark;
                }

                if($first_row->role == 4){

                    $breakdown_email['remark'] = $second_row->fs_remark;
                }

                if($first_row->role == 6){

                    $breakdown_email['remark'] = $second_row->ftl_remark;
                }
                $breakdown_email['status'] = $second_row->status;


                // Get Email Addresses
                $bank_email_result = $objBreakdown->get_client_email_address($second_row->bank);

                static $to_email_address = array();
                $icount = 1;
                foreach($bank_email_result as $eml){

                    $to_email_address[$icount] = $eml->EMAIL;
                    $icount ++;
                }

                \Mail::to($to_email_address)->cc('support@epiclanka.net')->send(new \App\Mail\Breakdown\breakdown_individual($breakdown_email));



                $objBreakdown->update_email_request($first_row->email_request_id);

            }
        }

    }

}
