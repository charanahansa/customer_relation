<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class MerchantRemoval extends Model{

    use HasFactory;

	public function get_merchant_removal_count($from_date, $to_date){

        $sql_query=" select     count(r.ticketno) as 'merchant_removal_count'
                     from       merchant_removal r inner join merchant_removal_detail rd on r.ticketno = rd.ticketno
                     where      r.cancel=0 && r.tdate between ? and  ?; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_merchant_removal_count($from_date, $to_date){

        $sql_query=" select     r.bank, count(r.ticketno) as 'count'
                     from       merchant_removal r inner join merchant_removal_detail rd on r.ticketno = rd.ticketno
                     where      r.cancel=0 && r.tdate between ? and  ?
                     group by   r.bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_merchant_removal_count($from_date, $to_date){

        $sql_query=" select     r.status, count(r.ticketno) as 'count'
                     from       merchant_removal r inner join merchant_removal_detail rd on r.ticketno = rd.ticketno
                     where      r.cancel=0 && r.tdate between ? and  ?
                     group by   r.status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_merchant_removal_count($bank, $from_date, $to_date){

        $sql_query=" select     r.status, count(r.ticketno) as 'count'
                     from       merchant_removal r inner join merchant_removal_detail rd on r.ticketno = rd.ticketno
                     where      r.cancel=0 && r.bank = ? && r.tdate between ? and ?
                     group by   r.status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_merchant_removal_count($status, $from_date, $to_date){

        $sql_query=" select     r.bank, count(r.ticketno) as 'count'
                     from       merchant_removal r inner join merchant_removal_detail rd on r.ticketno = rd.ticketno
                     where      r.cancel=0 && r.status = ? && r.tdate between ? and  ?
                     group by   r.bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_merchant_removal_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     r.ticketno, r.tdate, r.bank, rd.tid, rd.model, rd.merchant, '-' as 'officer_name', r.status
                     from       merchant_removal r
                                    inner join merchant_removal_detail rd on r.ticketno = rd.ticketno
                                    left outer join officers o on rd.officer = o.id
                     where      r.cancel=0 && r.bank = ? && r.status = ? && r.tdate between ? and  ? ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }



}
