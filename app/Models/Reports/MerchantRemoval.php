<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MerchantRemoval extends Model {

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

	public function get_status(){

		$sql_query = 'select * from status where ref = "breakdown" ';
		$result = DB::select($sql_query);

		return $result;
	}

    public function get_report($from_date, $to_date, $total_filter){

		$sql_query = "  select		r.ticketno, r.tdate, r.bank, r.removed_by, r.return_to, 
                                    rd.tid, rd.mid, rd.model, rd.serialno, rd.merchant,
                                    r.remark, r.status, r.saved_by, r.saved_on, r.edit_by, r.edit_on
                        from		merchant_removal r 
                                        inner join merchant_removal_detail rd on r.ticketno = rd.ticketno				
						where		r.cancel = 0 && r.tdate between ? and ? " . $total_filter . " 
						order by    r.tdate desc, r.ticketno desc; ";

		//echo $sql_query;

		$result = DB::select($sql_query,[$from_date, $to_date]);

		return $result;

	}



}
