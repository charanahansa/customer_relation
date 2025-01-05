<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalReplacement extends Model{

    use HasFactory;

	public function get_terminal_replacement_count($from_date, $to_date){

        $sql_query=" select     count(ticketno) as 'terminal_replacement_count'
                     from       terminal_replacement
                     where      cancel = 0 && tdate between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_terminal_replacement_count($from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       terminal_replacement
                     where      cancel = 0 && tdate between ? and ?
                     group by   bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_terminal_replacement_count($from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       terminal_replacement
                     where      cancel = 0 && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_terminal_replacement_count($bank, $from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       terminal_replacement
                     where      cancel = 0 &&  bank = ? && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_terminal_replacement_count($status, $from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       terminal_replacement
                     where      cancel = 0 &&  status = ? && tdate between ? and ?
                     group by   bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_terminal_replacement_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     ticketno, concat(tdate, ' ', cast(saved_on as time)) as 'tdate', bank, replaced_tid as 'tid', replaced_model as 'model', merchant, o.officer_name, status, done_date_time
                     from       terminal_replacement b left outer join officers o on b.officer = o.id
                     where      cancel = 0 && bank = ? && status = ? && tdate between ? and ?; ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }



}
