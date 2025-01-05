<?php

namespace App\Rules\Hardware\SparePart;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Hardware\SparePart\SparePartRequestProcess;

class SparePartRequestValidation implements Rule {

    protected $message = NULL;

    public function __construct(){

    }

    public function passes($attribute, $value){

        $objSparePartRequestProcess = new  SparePartRequestProcess();
        $result = $objSparePartRequestProcess->is_settle_spare_part_request_number($value);
        if($result){

            return TRUE;

        }else{

            $this->message = 'Already spare parts issued for this spare part request No.';

            return FALSE;
        }
    }

    public function message(){

        return $this->message;
    }
}
