<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class ReInitialization extends Model {

    use HasFactory;


	public function get_re_initialization_count($from_date, $to_date){

        $sql_query=" select     count(ticketno) as 're_initialization_count'
                     from       re_initialization
                     where      cancel = 0 && tdate between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_re_initialization_count($from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       re_initialization
                     where      cancel = 0 && tdate between ? and ?
                     group by   bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_re_initialization_count($from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       re_initialization
                     where      cancel = 0 && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_re_initialization_count($bank, $from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       re_initialization
                     where      cancel = 0 &&  bank = ? && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_re_initialization_count($status, $from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       re_initialization
                     where      cancel = 0 &&  status = ? && tdate between ? and ?
                     group by   bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_re_initialization_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     ticketno, concat(tdate, ' ', cast(saved_on as time)) as 'tdate', bank, tid, model, merchant, o.officer_name, status, done_date_time
                     from       re_initialization b left outer join officers o on b.officer = o.id
                     where      cancel = 0 && bank = ? && status = ? && tdate between ? and ?; ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }

    public function get_re_initialization_detail_for_individual_email($ticketno){

        $sql_query = " select		r.ticketno, r.tdate as 'date', r.bank, r.tid, r.model, r.serialno, r.merchant, r.contactno, r.contact_person,
                                    r.remark, ftl.remark as 'ftl_remark', fs.remark as 'fs_remark', vc.remark as 'vc_remark',
                                    r.status
                       from		    re_initialization r
                                        left outer join re_initialization_ftl_view ftl on ftl.ticketno = r.ticketno
                                        left outer join re_initialization_fs_view fs on fs.ticketno = r.ticketno
                                        left outer join re_initialization_vc_view vc on vc.ticketno = r.ticketno
                       where		r.ticketno = ? ";

        $result = DB::select($sql_query,[$ticketno]);

        return $result;
    }

    public function get_client_email_address($bank){

        $result = DB::table('bank_officer')->select('EMAIL')
                                           ->where('bank', $bank)
                                           ->where('active', 1)
                                           ->where('bd_email', 1)
                                           ->get();
        return $result;
    }

    public function update_email_request($email_request_id){

        $update_array['email_sent'] = 1;
        $update_array['sent_on'] = date("Y/m/d H:i:s");

        DB::table('email_request')->where('email_request_id', $email_request_id)->update($update_array);
    }


}
