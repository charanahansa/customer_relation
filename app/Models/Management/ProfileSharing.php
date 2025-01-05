<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class ProfileSharing extends Model{

    use HasFactory;

	public function get_profile_sharing_count($from_date, $to_date){

        $sql_query=" select     count(sd.ticketno) as 'profile_sharing_count'
                     from       vc_sharing s inner join vc_sharing_detail sd on s.batchno = sd.batchno
                     where      s.cancel=0 && sd.tdate between ? and  ?; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_profile_sharing_count($from_date, $to_date){

        $sql_query=" select     s.bank, count(sd.ticketno) as 'count'
                     from       vc_sharing s inner join vc_sharing_detail sd on s.batchno = sd.batchno
                     where      s.cancel=0 && s.tdate between ? and  ?
                     group by   s.bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_profile_sharing_count($from_date, $to_date){

        $sql_query=" select     sd.status, count(sd.ticketno) as 'count'
                     from       vc_sharing s inner join vc_sharing_detail sd on s.batchno = sd.batchno
                     where      s.cancel=0 && s.tdate between ? and  ?
                     group by   sd.status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_profile_sharing_count($bank, $from_date, $to_date){

        $sql_query=" select     s.status, count(sd.ticketno) as 'count'
                     from       vc_sharing s inner join vc_sharing_detail sd on s.batchno = sd.batchno
                     where      s.cancel=0 && s.bank = ? && s.tdate between ? and ?
                     group by   s.status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_profile_sharing_count($status, $from_date, $to_date){

        $sql_query=" select     s.bank, count(sd.ticketno) as 'count'
                     from       vc_sharing s inner join vc_sharing_detail sd on s.batchno = sd.batchno
                     where      s.cancel=0 && s.status = ? && s.tdate between ? and ?
                     group by   s.bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_profile_sharing_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     sd.ticketno, s.tdate, s.bank, sd.main_tid as 'tid', sd.sharing_tid, sd.merchant, o.officer_name, sd.status
                     from       vc_sharing s
                                    inner join vc_sharing_detail sd on s.batchno = sd.batchno
                                    left outer join officers o on sd.officer = o.id
                     where      s.cancel=0 && s.bank = ? && sd.status = ? && s.tdate between ? and  ? ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }


}
