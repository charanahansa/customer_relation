<?php

namespace App\Models\Tmc\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SoftwareUpdation extends Model {

    use HasFactory;

	public function get_courier_inquire_detail($search){

		$sql_query = " 	select	t.ticketno, t.tdate, 'sent' as 'sent', t.pod_no, s.bank, sd.tid, t.serialno, t.model, t.collect_from_courier, t.merchant, t.contactno, t.remark
					   	from		software_updation_tmc_view t 
									inner join software_updation_detail sd on t.ticketno = sd.ticketno
									inner join software_updation s on sd.batchno = s.batchno
						where		t.cancel = 0 && sd.courier <> 'Not' && t.pod_no <> '' && t.pod_no like ? ";
					   
		$result = DB::select($sql_query, [$search]);

		return $result;
	}

	
}
