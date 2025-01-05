<?php

namespace App\Http\Controllers\Maintainance\Inquire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Bank;
use App\Models\Maintainance\MaintainanceAddProcess;

use Illuminate\Support\MessageBag;

class MaintainanceAddInquireController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objBank = new Bank();

        $data['report_table'] = array();
        $data['bank'] = $objBank->get_bank();
        $data['attributes'] = $this->get_maintainance_add_inquire_attributes(NULL, NULL);

        return view('maintainance.inquire.maintainance_add_inquire')->with('MAI', $data);
    }

    private function get_maintainance_add_inquire_attributes($process, $request){

        $attributes['search_value'] = '';
        $attributes['bank'] = 0;
        $attributes['from_date'] = '';
        $attributes['to_date'] = '';

        $attributes['process_message'] = '';
        $attributes['validation_messages'] = new MessageBag();

        if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }

        if((is_null($process) == TRUE) && (is_null($request) == FALSE)){

            $attributes['search_value'] = $request->search_value;
            $attributes['bank'] = $request->bank;
            $attributes['from_date'] = $request->from_date;
            $attributes['to_date'] = $request->to_date;

            return $attributes;
        }
    }

    public function maintainance_add_inquire_process(Request $request){

        $objBank = new Bank();

        $result = $this->maintainance_inquire_information($request);

        $data['report_table'] = $result;
        $data['bank'] = $objBank->get_bank();
        $data['attributes'] = $this->get_maintainance_add_inquire_attributes(NULL, $request);

        return view('maintainance.inquire.maintainance_add_inquire')->with('MAI', $data);

    }

    private function maintainance_inquire_information($request){

        $query_string  = "";

        if($request->search_value != ''){

            $query_string .= " && referance_number = '". $request->search_value ."' ";
        }

        if($request->bank != 0){

            $query_string .= " && bank = '". $request->bank ."' ";
        }

        if( isset($request->from_date) && isset($request->to_date)){

            $query_string .= " && ma_date between '". $request->from_date ."' and '". $request->to_date ."' ";
        }

        $objMaintainanceAddProcess = new MaintainanceAddProcess();
        $result = $objMaintainanceAddProcess->get_maintainance_add_inquire_process($query_string);

        return $result;

    }

}
