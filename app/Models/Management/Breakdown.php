<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Breakdown extends Model{

    use HasFactory;

	public function get_breakdown_count($from_date, $to_date){

        $sql_query=" select     count(ticketno) as 'breakdown_count'
                     from       breakdown
                     where      cancel = 0 && tdate between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_breakdown_count($from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       breakdown
                     where      cancel = 0 && tdate between ? and ?
                     group by   bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_breakdown_count($from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       breakdown
                     where      cancel = 0 && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_breakdown_count($bank, $from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       breakdown
                     where      cancel = 0 &&  bank = ? && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_breakdown_count($status, $from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       breakdown
                     where      cancel = 0 &&  status = ? && tdate between ? and ?
                     group by   bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_breakdown_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     ticketno, concat(tdate, ' ', cast(saved_on as time)) as 'tdate', bank, tid, model, merchant, e.error, o.officer_name, status, done_date_time
                     from       breakdown b
                                    left outer join officers o on b.officer = o.id
                                    inner join error e on b.error = e.eno
                     where      cancel = 0 && bank = ? && status = ? && tdate between ? and ?; ";

$result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

return $result;
    }

    public function get_email_request(){

        $result = DB::table('email_request')->where('email_sent', 0)->orderBy('requested_on')->limit(4)->get();

        return $result;
    }

    public function get_breakdown($ticketno){

        $sql_query = " select		b.ticketno, b.tdate, b.bank, b.tid, b.model, b.merchant, e.error as 'fault', b.contactno, b.contact_person,
                                    r.relevant_detail, a.action_taken, b.fault_serialno, b.replaced_serialno,
                                    b.remark as 'cc_remark', ftl.remark as 'ftl_remark', fs.remark as 'fs_remark', b.status
                       from		    breakdown b
                                    left outer join error e on b.error = e.eno
                                    left outer join relevent_detail r on b.relevant_detail = r.rno
                                    left outer join action_taken a on b.action_taken = a.ano
                                    left outer join breakdown_ftl_view ftl on ftl.ticketno = b.ticketno
                                    left outer join breakdown_fs_view fs on fs.ticketno = b.ticketno
                       where		b.ticketno = ? ";

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
