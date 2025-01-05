<?php

namespace App\Models\Tmc\Backup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class BackupOperation extends Model {

    use HasFactory;

	public function is_backup_serial_number($serial_number){

		$result = DB::table('terminal_log')->where('bank', 'EPIC')
										   ->where('ownership', 'EPIC')
										   ->where('serialno', $serial_number)
										   ->exists();
		return $result;
	}

	public function is_issued_backup_serial_number($serial_number){

		$result = DB::table('backup_issue_note')->where('cancel', 0)
										   		->where('bk_removed', 0)
										   		->where('backup_serialno', $serial_number)
										   		->exists();
		return $result;
	}

	public function get_backup_issue_note_number($serial_number){

		$result = DB::table('backup_issue_note')->where('cancel', 0)
										   		->where('bk_removed', 0)
										   		->where('backup_serialno', $serial_number)
												->orderBy('bin_date', 'desc')
										   		->get();
		return $result;
	}

	public function get_backup_issue_note_detail($backup_issue_note_no){

		$result = DB::table('backup_issue_note')->where('bin_no', $backup_issue_note_no)->get();

		return $result;
	}
}
