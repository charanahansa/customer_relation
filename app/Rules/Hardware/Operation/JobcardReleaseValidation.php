<?php

namespace App\Rules\Hardware\Operation;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Hardware\Operation\TerminalReleaseProcess;

class JobcardReleaseValidation implements Rule {

    protected $error_message = '';

    public function __construct(){

    }

    public function passes($attribute, $value){

        $objTerminalReleaseProcess = new TerminalReleaseProcess();
        $release_terminal_array = array();
        $proper_order_status = FALSE;

        $jobcard_information = $value;
        $jobcard_information = explode(chr(13), $jobcard_information);

        $icount = 1;
        foreach($jobcard_information as $jobcard_row){

            $proper_order_status = TRUE;
            $jc_row = rtrim(ltrim($jobcard_row));

            $isReleaseJobcardValidationStatus = $objTerminalReleaseProcess->isReleaseJobcardNumber($jc_row);
            if( $isReleaseJobcardValidationStatus == TRUE ){

                $release_terminal_array[$icount] = $jc_row;
                $icount++;
            }
        }

        if( $proper_order_status == FALSE ){

            $this->error_message = ' These jobcard numbers are not entered proper way.';

            return FALSE;
        }else{

            if( count($release_terminal_array) >= 1){

                $jobcards_list = '';
                foreach($release_terminal_array as $jobcards){

                    $jobcards_list .= $jobcards . ', ';
                }
                $jobcards_list = rtrim($jobcards_list, ", ");

                $this->error_message = ' These Jobcard numbers are ' . $jobcards_list . ' already released.';

                return FALSE;
            }else{

                return TRUE;
            }
        }
    }

    public function message(){

        return $this->error_message;
    }

}
