<?php

namespace App\Models\Tmc\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class TerminalReplacement extends Model {

    use HasFactory;

	public function get_courier_inquire_detail($search){

		$sql_query = " 	select 		t.ticketno, t.tdate, 'sent' as 'sent', t.pod_no, tr.bank, tr.replaced_tid, t.serialno, t.model, t.collect_from_courier, t.merchant, t.contactno, t.remark
						from 		terminal_replacement_tmc_view t join terminal_replacement tr ON t.ticketno = tr.ticketno
						where 		t.cancel = 0 && (t.courier <> 'Not') && (t.pod_no <> '') && (t.pod_no like ? )";
					   
		$result = DB::select($sql_query, [$search]);

		return $result;
	}





	
}
