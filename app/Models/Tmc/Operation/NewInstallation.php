<?php

namespace App\Models\Tmc\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class NewInstallation extends Model {

    use HasFactory;

	public function get_courier_inquire_detail($search){

		$sql_query = " select 		n.ticketno, t.tdate, 'sent' as 'sent', t.pod_no, n.bank, n.tid, t.serialno, t.model, t.collect_from_courier, t.merchant, t.contactno, t.remark 
        			   from			newinstall_tmc_view t join newinstall n ON t.ticketno = n.ticketno
    				   where 		(t.cancel = 0) && (n.courier <> 'Not') && (t.pod_no <> '') && t.pod_no like ? ";

		$result = DB::select($sql_query, [$search]);

		return $result;
	}

	
}
