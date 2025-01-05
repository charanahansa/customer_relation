<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class BackupRemoval extends Model {

    use HasFactory;

	public function get_backup_removal_count($from_date, $to_date){

        $sql_query=" select     count(brn_no) as 'backup_removal_count'
                     from       backup_remove_note
                     where      cancel = 0 && brn_date between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_backup_removal_count($from_date, $to_date){

        $sql_query=" select     bank, count(brn_no) as 'count'
                     from       backup_remove_note
                     where      cancel = 0 && brn_date between ? and ?
                     group by   bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_backup_removal_count($from_date, $to_date){

        $sql_query=" select     status, count(brn_no) as 'count'
                     from       backup_remove_note
                     where      cancel = 0 && brn_date between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_backup_removal_count($bank, $from_date, $to_date){

        $sql_query=" select     status, count(brn_no) as 'count'
                     from       backup_remove_note
                     where      cancel = 0 &&  bank = ? && brn_date between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_backup_removal_count($status, $from_date, $to_date){

        $sql_query=" select     bank, count(brn_no) as 'count'
                     from       backup_remove_note
                     where      cancel = 0 &&  status = ? && brn_date between ? and ?
                     group by   bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_backup_removal_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     brn_no as 'ticketno', brn_date as 'tdate', bank, tid, backup_model, merchant, o.officer_name, status
                     from       backup_remove_note b left outer join officers o on b.officer = o.id
                     where      cancel = 0 && bank = ? && status = ? && brn_date between ? and ?; ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }

}
