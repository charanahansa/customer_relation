<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProfileSharing extends Model {

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

		$sql_query = "  select		sd.ticketno, sd.tdate, s.bank, sd.main_tid, sd.based_tid, sd.based_mid, sd.sharing_tid, sd.sharing_mid, sd.currency, 
                                    sd.merchant, sd.address, o.officer_name, sd.remark, sd.status, sd.saved_by, sd.saved_on, sd.edit_by, sd.edit_on
                        from		vc_sharing s 
                                            inner join vc_sharing_detail sd on s.batchno = sd.batchno
                                            left outer join officers o on sd.officer = o.id					
						where		s.cancel = 0 && sd.tdate between ? and ? " . $total_filter . " 
						order by    sd.tdate desc, sd.ticketno desc; ";

		//echo $sql_query;

		$result = DB::select($sql_query,[$from_date, $to_date]);

		return $result;

	}

    
}
