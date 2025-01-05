<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SoftwareUpdation extends Model {

    use HasFactory;

	public function get_software_updation_count($from_date, $to_date){

        $sql_query=" select     count(st.ticketno) as 'software_updation_count'
                     from       software_updation sb inner join software_updation_detail st on sb.batchno = st.batchno
                     where      sb.cancel=0 && sb.tdate between ? and ? ; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_software_updation_count($from_date, $to_date){

        $sql_query=" select     sb.bank, count(st.ticketno) as 'count'
                     from       software_updation sb inner join software_updation_detail st on sb.batchno = st.batchno
                     where      sb.cancel=0 && sb.tdate between ? and  ?
                     group by   sb.bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_software_updation_count($from_date, $to_date){

        $sql_query=" select     st.status, count(st.ticketno) as 'count'
                     from       software_updation sb inner join software_updation_detail st on sb.batchno = st.batchno
                     where      sb.cancel=0 && sb.tdate between ? and  ?
                     group by   st.status; ";

        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_software_updation_count($bank, $from_date, $to_date){

        $sql_query=" select     st.status, count(st.ticketno) as 'count'
                     from       software_updation sb inner join software_updation_detail st on sb.batchno = st.batchno
                     where      sb.cancel=0 && sb.bank = ? && sb.tdate between ? and  ?
                     group by   st.status; ";

        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_software_updation_count($status, $from_date, $to_date){

        $sql_query=" select     sb.bank, count(st.ticketno) as 'count'
                     from       software_updation sb inner join software_updation_detail st on sb.batchno = st.batchno
                     where      sb.cancel=0 && st.status = ? && sb.tdate between ? and ?
                     group by   sb.bank; ";

        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_software_updation_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     st.ticketno, concat(sb.tdate, ' ', cast(sb.confirm_on as time)) as 'tdate', sb.bank, st.tid, st.model, st.merchant, o.officer_name, st.status, fs.done_date_time
                     from       software_updation sb
                                    inner join software_updation_detail st on sb.batchno = st.batchno
                                    left outer join software_updation_fs_view fs on st.ticketno = fs.ticketno
                                    left outer join officers o on st.officer = o.id
                     where      sb.cancel=0 && sb.bank = ? && st.status = ? && sb.tdate between ? and ? ; ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }

    public function get_software_updation_detail_for_individual_email($ticketno){

        $sql_query = " select distinct 		sd.batchno, sd.ticketno, s.tdate as 'date', s.bank,  sd.tid, sd.mid, sd.model, sd.serialno, sd.merchant, sd.contactno, sd.contact_person, sd.remark,
                                            ftl.remark as 'ftl_remark', fs.remark as 'fs_remark', sd.status
                        from					software_updation_detail sd
                                    left outer join software_updation s on sd.batchno = s.batchno
                                    left outer join software_updation_ftl_view ftl on sd.ticketno = ftl.ticketno
                                    left outer join software_updation_fs_view fs on sd.ticketno = fs.ticketno
                        where		sd.ticketno = ? ";

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
