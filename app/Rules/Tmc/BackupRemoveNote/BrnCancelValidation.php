<?php

namespace App\Rules\Tmc\BackupRemoveNote;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\Backup\BackupProcess;

class BrnCancelValidation implements Rule {

    protected $message = '';
   
    public function __construct(){
        
    }


    public function passes($attribute, $value){
        
        $objBackupProcess = new BackupProcess();

		$cancel_result = $objBackupProcess->is_cancel_backup_remove_note($value);
		if($cancel_result == TRUE){

			$this->message = 'This is a Cancel Backup Remove Note';
			return FALSE;

		}else{

			return TRUE;
		}
    }
    
    public function message(){

        return $this->message;
    }
}
