<?php

namespace App\Http\Controllers\Tmc\Backup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Hardware\SparePart\SparePartProcess;

class BackupReceiveNoteListController extends Controller {

	public function index(){

		

		$data['model'] = $objBackupProcess->get_models();
		$data['attributes'] = $this->get_backup_receive_note_attributes(NULL, NULL);

		return view('tmc.backup.backup_receive_note')->with('BRN', $data);
	}
    
}
