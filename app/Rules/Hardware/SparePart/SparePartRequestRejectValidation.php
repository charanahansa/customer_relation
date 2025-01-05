<?php

namespace App\Rules\Hardware\SparePart;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Hardware\SparePart\SparePartRequestProcess;

class SparePartRequestRejectValidation implements Rule {

    protected $message = NULL;
   
    public function __construct(){

        
    }
    
    public function passes($attribute, $value){

        $objSparePartRequestProcess = new  SparePartRequestProcess();
        $result = $objSparePartRequestProcess->is_reject_spare_part_request_number($value);
        if($result){

            return TRUE;

        }else{

            $this->message = 'This spare part request No. is already rejected.';

            //$this->message = 'Result :- ' . $result;
            return FALSE;
        }
        
    }

    
    public function message(){

        return $this->message;
    }
}
