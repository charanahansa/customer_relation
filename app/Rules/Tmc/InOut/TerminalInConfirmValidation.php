<?php

namespace App\Rules\Tmc\InOut;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\InOut\TerminalInProcess;

class TerminalInConfirmValidation implements Rule {

    protected $message = '';

    public function __construct(){
        
    }
    
    public function passes($attribute, $value) {
        
		$objTerminalInProcess = new TerminalInProcess();

		$confirm_result = $objTerminalInProcess->isConfirmTerminalInNoteNumber($value);
		if($confirm_result == TRUE){

			$this->message = 'This is Confirmed Terminal In Note';
			return FALSE;

		}else{

			return TRUE;
		}

    }
   
    public function message(){

        return $this->message;
    }
}
