<?php

namespace App\Models\management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalRepair extends Model {

    use HasFactory;

    public function get_terminal_replacement_count($from_date, $to_date){

        $sql_query=" select     count(jobcard_no) as 'repair_count'
                     from       hw_terminals
                     where		tmc_jc_date between  ?  and ? ";
      $result = DB::connection('mysql2')->select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_terminal_repair_count($from_date, $to_date){

        $sql_query=" select     bank, count(jobcard_no) as 'count'
                     from       hw_terminals
                     where		tmc_jc_date between  ?  and ?
                     group by	bank ";
        $result = DB::connection('mysql2')->select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_terminal_repair_count($from_date, $to_date){

        $sql_query=" select     hs.status_name as 'status', count(jobcard_no) as 'count'
                     from       hw_terminals ht inner join hw_status hs on ht.status = hs.status_id
                     where	    tmc_jc_date between  ?  and ?
                     group by   hs.status_name ";

        $result = DB::connection('mysql2')->select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function getNotAcceptedCount(){

        $result = DB::table('jobcard')->where('status', 'awaiting')->get();

		return $result;
    }

    public function getToBeInvestigatedCount(){

        $result = DB::connection('mysql2')->table('hw_terminals')->where('status', 3)->where('Released', 0)->get();

		return $result;
    }

    public function getTestingCount(){

        $result = DB::connection('mysql2')->table('hw_terminals')->where('status', 9)->where('Released', 0)->get();

		return $result;
    }

    public function getDoneCount(){

        $result = DB::connection('mysql2')->table('hw_terminals')->where('status', 10)->where('Released', 0)->get();

		return $result;
    }

    public function getQuotedCount(){

        $result = DB::connection('mysql2')->table('hw_terminals')->where('quatation', 'Chargeable Quotation')->where('Released', 0)->get();

		return $result;
    }

    public function getSparepartPendingTerminalCount(){

        $result = DB::connection('mysql2')->table('hw_terminals')->where('status', 8)->where('Released', 0)->get();

		return $result;
    }

    public function getDismantalTerminalCount(){

        $result = DB::connection('mysql2')->table('hw_terminals')->where('status', 6)->where('Released', 0)->get();

		return $result;
    }

    public function getTodayAcceptedTerminalCount(){

        $sql_query=" select		*
                     from		hw_terminals
                     where		DATE(saved_at) = CURDATE() ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTodayReleasedTerminalCount(){

        $sql_query=" select		*
                     from		hw_release_terminal
                     where		DATE(R_Date) = CURDATE() ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getJobcardNotAcceptedTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    jobcard
                       where		status = 'awaiting'
                       group by	    bank
                       order by     bank ";

        $result = DB::select($sql_query);

        return $result;
    }

    public function getJobcardNotAcceptedTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    jobcard
                       where		status = 'awaiting'
                       group by	    model
                       order by     model ";

        $result = DB::select($sql_query);

        return $result;
    }

    public function getToBeInvestigatedTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 3 && Released = 0
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getToBeInvestigatedTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 3 && Released = 0
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTodayAcceptedTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		DATE(saved_at) = CURDATE()
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTodayAcceptedTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		DATE(saved_at) = CURDATE()
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTodayReleasedTerminalCountBankWise(){

        $sql_query = " select		t.bank,  count(t.jobcard_no) as 'jcount'
                       from		    hw_release_terminal r inner join hw_terminals t on r.Jobcardno = t.jobcard_no
                       where		DATE(R_Date) = CURDATE()
                       group by	    t.bank; ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTodayReleasedTerminalCountModelWise(){

        $sql_query = " select		t.model,  count(t.jobcard_no) as 'jcount'
                       from		    hw_release_terminal r inner join hw_terminals t on r.Jobcardno = t.jobcard_no
                       where		DATE(R_Date) = CURDATE()
                       group by	    t.model; ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getQuotedTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		quatation = 'Chargeable Quotation' && Released = 0
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getQuotedTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		quatation = 'Chargeable Quotation' && Released = 0
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getSparepartPendingTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 8 && Released = 0
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getSparepartPendingTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 8 && Released = 0
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTestingTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 9 && Released = 0
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getTestingTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 9 && Released = 0
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getDoneTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 10 && Released = 0
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getDoneTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 10 && Released = 0
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getDismantledTerminalCountBankWise(){

        $sql_query = " select		bank, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 6 && Released = 0
                       group by	    bank
                       order by     bank ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

    public function getDismantledTerminalCountModelWise(){

        $sql_query = " select		model, count(jobcard_no) as 'jcount'
                       from		    hw_terminals
                       where		status = 6 && Released = 0
                       group by	    model
                       order by     model ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }


}
