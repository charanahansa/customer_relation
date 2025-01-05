<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class NewInstallation extends Model{

    use HasFactory;

	public function get_new_installation_count($from_date, $to_date){

        $sql_query=" select     count(ticketno) as 'new_installation_count'
                     from       newinstall
                     where      cancel = 0 && tdate between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_new_installation_count($from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       newinstall
                     where      cancel = 0 && tdate between ? and ?
                     group by   bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_new_installation_count($from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       newinstall
                     where      cancel = 0 && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_new_installation_count($bank, $from_date, $to_date){

        $sql_query=" select     status, count(ticketno) as 'count'
                     from       newinstall
                     where      cancel = 0 &&  bank = ? && tdate between ? and ?
                     group by   status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_new_installation_count($status, $from_date, $to_date){

        $sql_query=" select     bank, count(ticketno) as 'count'
                     from       newinstall
                     where      cancel = 0 &&  status = ? && tdate between ? and ?
                     group by   bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_new_installation_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     ticketno, concat(tdate, ' ', cast(saved_on as time)) as 'tdate', bank, tid, model, merchant, o.officer_name, status, done_date_time
                     from       newinstall b left outer join officers o on b.officer = o.id
                     where      cancel = 0 && bank = ? && status = ? && tdate between ? and ?; ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }

    public function get_new_installation($ticketno){

        $sql_query = " select		n.ticketno, n.tdate as 'date', n.bank, n.tid, n.mid, n.model, n.serialno, n.merchant,
                                    n.contactno, n.contact_person, n.remark, ftl.remark as 'ftl_remark', fs.remark as 'fs_remark', n.status
                       from		    newinstall n
                                        left outer join newinstall_ftl_view ftl on n.ticketno = ftl.ticketno
                                        left outer join newinstall_fs_view fs on n.ticketno = fs.ticketno
                       where		n.ticketno = ? ";

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
