<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProfileUpdation extends Model {

    use HasFactory;

    public function get_bank(){

		$sql_query = "select * from bank";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_applications(){

		$sql_query = " select		application
                       from		    vc_profile_updation
                       where		cancel = 0 && application <> ''
                       group by	    application
                       order by	    application; ";

		$result = DB::select($sql_query);

		return $result;
	}

	public function get_officers(){

		$sql_query = "select * from officers  where action_station = 'vc'";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_status(){

		$sql_query = 'select * from status where ref = "breakdown" ';
		$result = DB::select($sql_query);

		return $result;
	}

    public function get_report($from_date, $to_date, $total_filter){

		$sql_query = "  select		ticketno, tdate, bank, tid, mid, application, merchant, o.officer_name, remark, status, 
                                    saved_by, saved_on, edit_by, edit_on
                        from		vc_profile_updation pu
                                        left outer join officers o on pu.vo_officer = o.id			
						where		cancel = 0 && tdate between ? and ? " . $total_filter . " 
						order by    tdate desc, ticketno desc; ";

		//echo $sql_query;

		$result = DB::select($sql_query,[$from_date, $to_date]);

		return $result;

	}

}
