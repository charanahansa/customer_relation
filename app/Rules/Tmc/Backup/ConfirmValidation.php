<?php

namespace App\Rules\Tmc\Backup;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Tmc\Backup\BackupProcess;

class ConfirmValidation implements Rule {

	protected $message = '';
    
    public function __construct(){
        
    }

    public function passes($attribute, $value){
        
		$objBackupProcess = new BackupProcess();

		$confirm_result = $objBackupProcess->is_confirm_backup_receive_note($value);
		if($confirm_result == TRUE){

			$this->message = 'This is a Confirm Backup Receive Note';
			return FALSE;

		}else{

			return TRUE;
		}
    }

    public function message(){
		
        return $this->message;
    }
}
