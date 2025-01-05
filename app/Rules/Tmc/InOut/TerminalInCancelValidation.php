<?php

namespace App\Rules\Tmc\InOut;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\InOut\TerminalInProcess;

class TerminalInCancelValidation implements Rule {

	protected $message = '';
   
    public function __construct(){
        
    }

    public function passes($attribute, $value){
        
		$objTerminalInProcess = new TerminalInProcess();

		$cancel_result = $objTerminalInProcess->isCancelTerminalInNoteNumber($value);
		if($cancel_result == TRUE){

			$this->message = 'This is Cancelled Terminal In Note';
			return FALSE;

		}else{

			return TRUE;
		}

    }

    
    public function message(){

        return $this->message;
    }
}
