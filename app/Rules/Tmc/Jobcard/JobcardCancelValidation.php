<?php

namespace App\Rules\Tmc\Jobcard;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\Jobcard\Jobcard;

class JobcardCancelValidation implements Rule {

    protected $message = '';
   
    public function __construct(){
        
    }

    public function passes($attribute, $value){
        
        $objJobcard = new Jobcard();

		$cancel_result = $objJobcard->isCancelJobcardNumber($value);
		if($cancel_result == TRUE){

			$this->message = 'This is a Cancelled Jobcard Number';
			return FALSE;

		}else{

			return TRUE;
		}
    }
    
    public function message(){

        return $this->message;
    }
}
