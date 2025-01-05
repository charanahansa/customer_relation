<?php

namespace App\Rules\fsp;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Operation\HelpNote;

class CourierValidation implements Rule {

    protected $ticket_number = '';
    protected $workflow_short_name = '';
    protected $courier_status = '';
    
    public function __construct($ticket_number, $workflow_short_name, $courier_status){

        $this->workflow_short_name = $workflow_short_name;
        $this->ticket_number = $ticket_number;
        $this->courier_status = $courier_status;
    }

    public function passes($attribute, $value){     

        $objHelpNote = new HelpNote();
        $result = $objHelpNote->isSettledTerminalRequestNote($this->ticket_number, $this->workflow_short_name);
        if($result == TRUE){

            if( $this->courier_status != "Not" ){

                return TRUE;

            }else{

                return FALSE;
            }

        }else{

            return TRUE;
        }
    }

    
    public function message(){

        return 'Can not change the courier, because TMC programe the terminal.';
    }
}
