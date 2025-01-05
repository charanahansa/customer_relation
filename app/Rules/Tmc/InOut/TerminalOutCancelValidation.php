<?php

namespace App\Rules\Tmc\InOut;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\InOut\TerminalOutProcess;

class TerminalOutCancelValidation implements Rule {

	protected $message = '';

    public function __construct() {

    }

    public function passes($attribute, $value) {
        
		$objTerminalOutProcess = new TerminalOutProcess();

		$cancel_result = $objTerminalOutProcess->isCancelTerminalOutNoteNumber($value);
		if($cancel_result == TRUE){

			$this->message = 'This is Cancelled Terminal Out Note';
			return FALSE;

		}else{

			return TRUE;
		}

    }
   
    public function message(){

        return $this->message;
    }

}
