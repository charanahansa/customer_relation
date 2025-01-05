<?php

namespace App\Rules\Tmc\InOut;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\InOut\TerminalOutProcess;

class TerminalOutConfirmValidation implements Rule {

	protected $message = '';

    public function __construct(){

        
    }

    
    public function passes($attribute, $value) {
        
		$objTerminalOutProcess = new TerminalOutProcess();

		$confirm_result = $objTerminalOutProcess->isConfirmTerminalOutNoteNumber($value);
		if($confirm_result == TRUE){

			$this->message = 'This is Confirmed Terminal Out Note';
			return FALSE;

		}else{

			return TRUE;
		}

    }
   
    public function message(){

        return $this->message;
    }
}
