<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalInOut extends Model {

    use HasFactory;

	public function get_terminal_in_count($from_date, $to_date){

        $sql_query=" select     count(td.serialno) as 'terminal_in_count'
                     from       terminal_in_process t inner join terminal_in_process_detail td on t.ticketno = td.ticketno
                     where      t.cancel=0 && t.confirm = 1 && t.tdate between ? and ?; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_bank_wise_terminal_in_qty($from_date, $to_date){

        $sql_query=" select     t.bank, count(td.serialno) as 'count'
                     from       terminal_in_process t inner join terminal_in_process_detail td on t.ticketno = td.ticketno
                     where      t.cancel=0 && t.confirm=1 && t.tdate between ? and ?
                     group by   t.bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_terminal_out_count($from_date, $to_date){

        $sql_query=" select     count(td.serialno) as 'terminal_out_count'
                     from       terminal_out_process t inner join terminal_out_process_detail td on t.ticketno = td.ticketno
                     where      t.cancel=0 && t.confirm=1 && t.tdate between ? and ?; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_bank_wise_terminal_out_qty($from_date, $to_date){

        $sql_query=" select     t.bank, count(td.serialno) as 'count'
                     from       terminal_out_process t inner join terminal_out_process_detail td on t.ticketno = td.ticketno
                     where      t.cancel=0 && t.confirm=1 && t.tdate between ? and  ?
                     group by   t.bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

}
