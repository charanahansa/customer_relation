<?php

namespace App\Rules\fsp;

use Illuminate\Contracts\Validation\Rule;

class OfficerCourierValidation implements Rule {

    protected $responsible = array();

    public function __construct($responsible){

        $this->responsible = $responsible;
    }

    public function passes($attribute, $value){

        $field_officer_result = ($this->responsible['field_officer'] == "Not") ? 0 : 1;
        $courier_provider_result = ($this->responsible['courier_provider'] == "Not") ? 0 : 1;
        $bank_officer_result = ($this->responsible['bank_officer'] == "Not") ? 0 : 1;

        $total_result = $field_officer_result + $courier_provider_result + $bank_officer_result;

        if( $total_result == 0 ){

            return  FALSE;
        }

        if( $total_result >= 1){

            return TRUE;
        }
    }

    public function message(){

        echo 'Field Officer :- '. $this->responsible['field_officer'] == "Not";
        echo '<br>Courier Provider :- '. $this->responsible['courier_provider'] == "Not";
        echo '<br>Bank Officer :- '. $this->responsible['courier_provider'] == "Not";

        return 'Please select the Officer or Courier provider or Bank Officer';
    }
}
