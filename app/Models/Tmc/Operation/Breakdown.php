<?php

namespace App\Models\Tmc\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Breakdown extends Model {

    use HasFactory;

	public function get_courier_inquire_detail($search){

		$sql_query = " select 		b.ticketno,t.tdate, 'sent' as 'sent', t.pod_no,b.bank,b.tid,t.serialno,t.model,t.collect_from_courier,t.merchant,t.contactno,t.remark
        			   from			breakdown_tmc_view t join breakdown b ON t.ticketno = b.ticketno
					   where 		t.cancel = 0 && (b.courier <> 'Not') && (t.pod_no <> '') && t.pod_no like ? ";
		$result = DB::select($sql_query, [$search]);

		return $result;
	}

	
}
