<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SubStatus extends Model {

    use HasFactory;

    public function getBreakdownSubStatus(){

        $result = DB::table('breakdown_sub_status')->orderBy('status')->get();

		return $result;
    }

	public function getNewInstallationSubStatus(){

        $result = DB::table('newinstall_sub_status')->orderBy('status')->get();

		return $result;
    }

	public function getReInitializationSubStatus(){

        $result = DB::table('re_initialization_sub_status')->orderBy('status')->get();

		return $result;
    }

	public function getSoftwareUpdateSubStatus(){

        $result = DB::table('software_updation_sub_status')->orderBy('status')->get();

		return $result;
    }

	public function getTerminalReplacementSubStatus(){

        $result = DB::table('terminal_replacement_sub_status')->orderBy('status')->get();

		return $result;
    }

	public function getBackupRemoveSubStatus(){

        $result = DB::table('backup_removal_sub_status')->orderBy('status')->get();

		return $result;
    }

    public function getFtlBreakdownSubStatus(){

        $result = DB::table('breakdown_sub_status')->where('jobrole', 'like', '%ftl%')->get();

		return $result;
    }

	public function getFtlNewInstallationSubStatus(){

        $result = DB::table('newinstall_sub_status')->where('jobrole', 'like', '%ftl%')->get();

		return $result;
    }

	public function getFtlReInitializationSubStatus(){

        $result = DB::table('re_initialization_sub_status')->where('jobrole', 'like', '%ftl%')->get();

		return $result;
    }

    public function getFtlSoftwareUpdateSubStatus(){

        $result = DB::table('software_updation_sub_status')->where('jobrole', 'like', '%ftl%')->get();

		return $result;
    }

	public function getFtlTerminalReplacementSubStatus(){

        $result = DB::table('terminal_replacement_sub_status')->where('jobrole', 'like', '%ftl%')->get();

		return $result;
    }

	public function getFtlBackupRemoveSubStatus(){

        $result = DB::table('backup_removal_sub_status')->where('jobrole', 'like', '%ftl%')->get();

		return $result;
    }

    public function getTmcBackupRemoveSubStatus(){

        $result = DB::table('backup_removal_sub_status')->where('jobrole', 'like', '%tmc%')->get();

		return $result;
    }

	public function getAllBackupRemoveSubStatus(){

        $result = DB::table('backup_removal_sub_status')->get();

		return $result;
    }

	public function getBreakdownSubStatusDescription($sub_status_id){

		$sub_status = DB::table('breakdown_sub_status')->where('id', $sub_status_id)->value('status');

		return $sub_status;
	}

}
