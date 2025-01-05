<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TerminalInOut extends Model {

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

    public function get_report($from_date, $to_date, $total_filter){

		$sql_query = " select	tp.ticketno, tp.tdate, 'In' as 'InOut', tp.bank, td.serialno, td.model, tp.remark, 
                                tp.saved_by, tp.saved_on, tp.edit_by, tp.edit_on
                       from		terminal_in_process  tp 
                                    inner join terminal_in_process_detail td on tp.ticketno = td.ticketno
                       where	tp.cancel = 0 && tp.confirm = 1 && td.serialno <> '-' && tp.tdate between ? and ? " . $total_filter . " 
                       union
                       select	t.ticketno, t.tdate, 'Out' as 'InOut', t.bank, tt.serialno, tt.model, t.remark, 
                                t.saved_by, t.saved_on, t.edit_by, t.edit_on
                       from		terminal_out_process t 
                                    inner join terminal_out_process_detail tt on t.ticketno = tt.ticketno
                       where	t.cancel = 0 && t.confirm = 1 && t.tdate between ? and ?  " . $total_filter . " 
                       order by tdate desc, ticketno desc ";

		//echo $sql_query;

		$result = DB::select($sql_query,[$from_date, $to_date, $from_date, $to_date]);

		return $result;

	}
}
