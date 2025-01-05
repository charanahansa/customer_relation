<?php

namespace App\Rules\fsp;

use Illuminate\Contracts\Validation\Rule;

class SubStatusValidation implements Rule {

    protected $status = '';
    protected $workflow_id = '';

    public function __construct($status, $workflow_id){
        
        $this->status = $status;
        $this->workflow_id = $workflow_id;
    }
    
    public function passes($attribute, $value){

        if( ($this->status == 'hold') || ($this->status == 'inprogress') || ($this->status == 'closed')){

            if(  $this->workflow_id == 1){

                if($value == 34){

                    return FALSE;
    
                }else{
    
                    return TRUE;
                }
    
            }elseif( $this->workflow_id == 2){

                if($value == 17){

                    return FALSE;
    
                }else{
    
                    return TRUE;
                }
    
            }elseif( $this->workflow_id == 3){

                if($value == 6){

                    return FALSE;
    
                }else{
    
                    return TRUE;
                }
    
            }elseif( $this->workflow_id == 4){

                if($value == 6){

                    return FALSE;
    
                }else{
    
                    return TRUE;
                }
    
            }elseif( $this->workflow_id == 5){

                if($value == 20){

                    return FALSE;
    
                }else{
    
                    return TRUE;
                }
    
            }elseif( $this->workflow_id == 6){

                if($value == 26){

                    return FALSE;
    
                }else{
    
                    return TRUE;
                }
    
            }else{
    
            }

            
        }

        if( $this->status == 'done' ){

            return TRUE;
        }
    }
   
    public function message(){

        return 'Sub Status should not be Done. Please verify with Status';
    }
}
