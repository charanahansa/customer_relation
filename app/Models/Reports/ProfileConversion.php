<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProfileConversion extends Model {

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

	public function get_from_applications(){

		$sql_query = "  select		from_application
                        from		vc_profile_convesion
                        where		cancel = 0
                        group by	from_application
                        order by	from_application; ";

		$result = DB::select($sql_query);

		return $result;
	}

    public function get_to_applications(){

		$sql_query = " select		to_application
                       from		    vc_profile_convesion
                       where		cancel = 0
                       group by	    to_application
                       order by	    to_application; ";

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

		$sql_query = "  select		ticketno, tdate, bank, from_tid, from_model, from_application, to_tid, to_model, to_application, 
                                    merchant, o.officer_name, remark, status, saved_by, saved_on, edit_by, edit_on
                        from		vc_profile_convesion pc left outer join officers o on pc.vo_officer = o.id		
						where		cancel = 0 && tdate between ? and ? " . $total_filter . " 
						order by    tdate desc, ticketno desc; ";

		//echo $sql_query;

		$result = DB::select($sql_query,[$from_date, $to_date]);

		return $result;
	}

}
