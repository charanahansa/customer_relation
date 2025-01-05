<?php

namespace App\Rules\Tmc\Backup;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\Backup\BackupProcess;

class CancelValidation implements Rule {

	protected $message = '';

    public function __construct(){
        
    }
    
    public function passes($attribute, $value){

		$objBackupProcess = new BackupProcess();

		$cancel_result = $objBackupProcess->is_cancel_backup_receive_note($value);
		if($cancel_result == TRUE){

			$this->message = 'This is a Cancel Backup Receive Note';
			return FALSE;

		}else{

			return TRUE;
		}
        
    }


    public function message(){

        return $this->message;
    }
}
