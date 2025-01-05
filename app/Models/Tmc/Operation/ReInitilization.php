<?php

namespace App\Models\Tmc\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class ReInitilization extends Model {

    use HasFactory;

	public function get_courier_inquire_detail($search){

		$sql_query = " select 	r.ticketno, t.tdate, 'sent' as 'sent', t.pod_no, r.bank, r.tid, t.serialno, t.model, t.collect_from_courier, t.merchant, t.contactno, t.remark 
        			   from 	re_initialization_tmc_view t join re_initialization r ON t.ticketno = r.ticketno
    				   where   (t.cancel = 0) && (r.courier <> 'Not') && (t.pod_no <> '') && (t.pod_no like ? ) ";
					   
		$result = DB::select($sql_query, [$search]);

		return $result;
	}
	
}
