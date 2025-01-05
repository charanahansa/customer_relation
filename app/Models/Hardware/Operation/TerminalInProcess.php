<?php

namespace App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalInProcess extends Model {

    use HasFactory;

    public function get_report_resultset($from_date, $to_date, $total_filter){

        $sql_query = " select		jobcard_no, tmc_jc_date, bank, serialno, model, accepted_officer
                       from		    hw_terminals
                       where		tmc_jc_date between ? and ? " . $total_filter . "
                       order by     tmc_jc_date desc, jobcard_no desc ";

		$result =  DB::connection('mysql2')->select($sql_query,[$from_date, $to_date]);

		return $result;
    }


}
