<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class ProfileConversion extends Model{

    use HasFactory;

	public function get_profile_conversion_count($from_date, $to_date){

        $sql_query=" select     count(ticketno) as 'profile_conversion_count'
                     from       vc_profile_convesion
                     where      cancel = 0 && tdate between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_profile_conversion_count($from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       vc_profile_convesion
                     where      cancel = 0 && tdate between ? and ?
                     group by   bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_profile_conversion_count($from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       vc_profile_convesion
                     where      cancel = 0 && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_profile_conversion_count($bank, $from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       vc_profile_convesion
                     where      cancel = 0 &&  bank = ? && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_profile_conversion_count($status, $from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       vc_profile_convesion
                     where      cancel = 0 &&  status = ? && tdate between ? and ?
                     group by   bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_profile_conversion_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     ticketno, tdate, bank, to_tid as 'tid', to_model as 'model', merchant, o.officer_name, status
                     from       vc_profile_convesion c left outer join officers o on c.vo_officer  = o.id
                     where      cancel = 0 && bank = ? && status = ? && tdate between ? and ?; ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }
}
