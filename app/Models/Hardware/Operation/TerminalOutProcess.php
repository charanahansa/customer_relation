<?php

namespace App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalOutProcess extends Model {

    use HasFactory;

    public function get_report_resultset($from_date, $to_date, $total_filter){

        $sql_query = " select		jobcard_no, tmc_jc_date, bank, serialno, model, R_Date, u.name, R_To, r.remark
                       from		    hw_terminals t 
                                        inner join hw_release_terminal r on t.jobcard_no = r.Jobcardno
                                        inner join users u on r.R_By = u.id
                       where		tmc_jc_date between ? and ? " . $total_filter . " 
                       order by     tmc_jc_date desc, jobcard_no desc; ";

		$result =  DB::connection('mysql2')->select($sql_query,[$from_date, $to_date]);

		return $result;
    }



}
