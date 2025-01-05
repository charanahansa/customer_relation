<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BackupRemoval extends Model {

    use HasFactory;

    public function get_bank(){

		$sql_query = "select * from bank";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_models(){

		$sql_query = "select * from model";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_officers(){

		$sql_query = "select * from officers";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_sub_status(){

		$sql_query = "select * from newinstall_sub_status";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_status(){

		$sql_query = 'select * from status where ref = "breakdown" ';
		$result = DB::select($sql_query);

		return $result;
	}

    public function getReport($from_date, $to_date, $total_filter, $tables){

		$ftl_query = '';
		$ftl_join = '';

		$tmc_query = '';
		$tmc_join = '';
		$tmc_where = '';

		$tp_query = '';
		$tp_join = '';

		$fs_query = '';
		$fs_join = '';

		$sql_query = " select		brn.brn_no, brn.brn_date, brn.bin_no, brn.bank, brn.tid, brn.merchant, brn.backup_serialno, brn.backup_model, brn.replaced_model, brn.replaced_serialno,
									brn.contact_number, brn.contact_person, brn.remark, o.officer_name, co.officer_name as 'courier_name', ss.status as 'sub_status', brn.status, brn.done_date_time,
									brn.cancel, brn.cancel_reason, brn.cancel_on, brn.cancel_by,
									brn.saved_by, brn.saved_on, brn.edit_by, brn.edit_on

									" . $ftl_query ."
									" . $tmc_query ."
									" . $tp_query ."
									" . $fs_query ."

					   from			backup_remove_note brn
										left outer join officers o on brn.officer = o.id
										left outer join courier co on brn.courier = co.id
										inner join backup_removal_sub_status ss on brn.sub_status = ss.id

									". $ftl_join ."
									". $tmc_join ."
									". $tp_join ."
									". $fs_join ."

						where		brn.cancel = 0 && brn.brn_date between ? and ? " . $total_filter . "
									" . $tmc_where . "
						order by 	brn.brn_date desc, brn.brn_no desc  ";

		//echo $sql_query;

		$result = DB::select($sql_query, [$from_date, $to_date]);

		return $result;
	}




}
