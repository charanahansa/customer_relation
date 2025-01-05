<?php

namespace App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class JobCardProcess extends Model {

	use HasFactory;

    public function jobcard_inquire($jobcard_number){

        $sql_query = " 	select		t.jobcard_no, tmc_jc_date as 'jobcard_date', t.bank, t.serialno, t.model, q.qt_no, s.status_name as 'status', Released, Released_Date
                        from		hw_terminals t
                                        left outer join quatation q on t.jobcard_no = q.jobcard_no
                                        left outer join hw_status s on t.status = s.status_id
                        where       t.jobcard_no = ?
                        order by	tmc_jc_date desc, t.jobcard_no desc  ";

      $result = DB::connection('mysql2')->select($sql_query, [$jobcard_number]);

      return $result;
    }

    public function jobcard_inquire_by_serial_number($serial_number){

        $sql_query = " 	select		t.jobcard_no, tmc_jc_date as 'jobcard_date', t.bank, t.serialno, t.model, q.qt_no, s.status_name as 'status', Released, Released_Date
                        from		hw_terminals t
                                        left outer join quatation q on t.jobcard_no = q.jobcard_no
                                        left outer join hw_status s on t.status = s.status_id
                        where       t.serialno = ?
                        order by	tmc_jc_date desc, t.jobcard_no desc;  ";

		$result = DB::connection('mysql2')->select($sql_query, [$serial_number]);

		return $result;
    }

    public function jobcard_inquire_by_quotation_number($quotation_number){

        $sql_query = " 	select		t.jobcard_no, tmc_jc_date as 'jobcard_date', t.bank, t.serialno, t.model, q.qt_no, s.status_name as 'status', Released, Released_Date
                        from		hw_terminals t
                                        left outer join quatation q on t.jobcard_no = q.jobcard_no
                                        left outer join hw_status s on t.status = s.status_id
                        where       q.qt_no = ?
                        order by	tmc_jc_date desc, t.jobcard_no desc;  ";

		$result = DB::connection('mysql2')->select($sql_query, [$quotation_number]);

		return $result;
    }

    public function jobcard_inquire_by_date_period($from_date, $to_date){

        $sql_query = " 	select		t.jobcard_no, tmc_jc_date as 'jobcard_date', t.bank, t.serialno, t.model, q.qt_no, s.status_name as 'status', Released, Released_Date
                        from		hw_terminals t
                                        left outer join quatation q on t.jobcard_no = q.jobcard_no
                                        left outer join hw_status s on t.status = s.status_id
                        where       t.tmc_jc_date between ? and  ?
                        order by	tmc_jc_date desc, t.jobcard_no desc;  ";

		$result = DB::connection('mysql2')->select($sql_query, [$from_date, $to_date]);

		return $result;
    }

    public function get_jobcard_detail_for_insurance_claim_report($jobcard_no){

		$sql_query = " 	select distinct		p.jobcard_no, q.qt_date, t.serialno, t.model, p.reason
						from				hw_repair_addpart p
                                                inner join hw_terminals t on p.jobcard_no = t.jobcard_no
                                                inner join quatation q on p.jobcard_no = q.jobcard_no
						where				p.reason <> 'null' && p.reason <> '' && q.cancel = 0 && p.jobcard_no = ?
						limit 1  ";

		$result = DB::connection('mysql2')->select($sql_query, [$jobcard_no]);

		return $result;
	}

	public function get_jobcard_detail_report($from_date, $to_date, $total_filter){

		$sql_query = " select 		t.jobcard_no, tmc_jc_date, t.bank, t.serialno, t.model, accepted_officer, userneg, prod_no, ptid, rev,
									hs.service_name, rs.chargeable_state,
									rs.remark as 'service_remark', u2.name as 'service_add_by', rs.saved_date as 'service_add_on',
									within_e_warranty, within_s_warranty, s.status_name,
									q.QT_NO, q.QT_DATE, q.PRICE,
									Released, Released_Date, u.name as 'released_by', R_To, r.remark as 'released_remark'
					   from			hw_terminals t
										left outer join quotation_view q on q.JOBCARD_NO = t.jobcard_no
										left outer join hw_repair_services rs on rs.jobcard_no = t.jobcard_no
										left outer join hw_services hs on hs.service_id = rs.service_id
										left outer join hw_status s on s.status_id = t.status
										left outer join hw_release_terminal r on t.jobcard_no = r.Jobcardno
										left outer join users u on r.R_By = u.id
										left outer join users u2 on rs.officer = u2.id
					   where		tmc_jc_date between ? and ? ". $total_filter ."
					   order by		t.jobcard_no desc  ";

		//echo $sql_query;

		$result = DB::connection('mysql2')->select($sql_query, [$from_date, $to_date]);

		return $result;
	}

	public function get_jobcard_fault_detail($jobcard_no, $total_filter){

		$sql_query = " select 		t.jobcard_no, f.Fault_id, fm.Fault_Name, f.remark, u.name, f.Saved_Date
					   from			hw_terminals t
										left outer join hw_repair_fault f on t.jobcard_no = f.jobcard_no
										left outer join hw_terminal_fault fm on f.Fault_id = fm.Fault_ID
										left outer join users u on f.officer = u.id
					   where		t.jobcard_no = ? ". $total_filter ."
					   order by		t.jobcard_no  ";

		$result = DB::connection('mysql2')->select($sql_query, [$jobcard_no]);

		return $result;
	}

	public function get_jobcard_add_spare_part_detail($jobcard_no, $total_filter){

		$sql_query = " select 		t.jobcard_no, ap.Part_ID, p.part_name, u.name, ap.remark, ap.chargeable_state, ap.reason, Saved_Date
					   from			hw_terminals t
										left outer join hw_repair_addpart ap on t.jobcard_no = ap.jobcard_no
										left outer join hw_parts p on ap.Part_ID = p.Part_ID
										left outer join users u on ap.officer = u.id
					   where		t.jobcard_no = ? ". $total_filter ."
					   order by		t.jobcard_no  ";

		$result = DB::connection('mysql2')->select($sql_query, [$jobcard_no]);

		return $result;
	}

	public function get_jobcard_spare_part_usage_detail($jobcard_no, $total_filter){

		$sql_query = " select 		t.jobcard_no, tmc_jc_date, t.bank, serialno, t.model, accepted_officer, userneg,
									spare_part_id, spare_part_name, issue
					   from			hw_terminals t
										left outer join tech_operation.spare_part_request_note sprn on t.jobcard_no = sprn.reference_number
					   where		t.jobcard_no = ? && workflow_id = 12 ". $total_filter ."
					   order by		t.jobcard_no  ";

		$result = DB::connection('mysql2')->select($sql_query, [$jobcard_no]);

		return $result;
	}

	public function get_jobcard_removed_spare_part_detail($jobcard_no, $total_filter){

		$sql_query = " select 		t.jobcard_no, tmc_jc_date, bank, serialno, t.model, accepted_officer, userneg,
									r.Part_ID, p.part_name, u.name, r.Saved_Date
					   from			hw_terminals t
										left outer join hw_repair_removeparts r on t.jobcard_no = r.jobcard_no
										left outer join hw_parts p on r.Part_ID = p.Part_ID
										left outer join users u on r.officer = u.id
						where		t.jobcard_no = ? ". $total_filter ."
						order by	t.jobcard_no ";

		$result = DB::connection('mysql2')->select($sql_query, [$jobcard_no]);

		return $result;
	}

	public function create_tmp_table(){

		DB::select('DROP TABLE IF EXISTS `tmp_jobcard_report`;');

		$sql_command = " CREATE TABLE `tmp_jobcard_report` (
						`jobcard_no` int(11) NOT NULL,
						`order_no` int(11) NOT NULL,
						`jobcard_date` date DEFAULT NULL,
						`bank` varchar(10) DEFAULT NULL,
						`serialno` varchar(20) DEFAULT NULL,
						`model` varchar(20) DEFAULT NULL,
						`accepted_officer` varchar(35) DEFAULT NULL,
						`user_negligent` int(11) DEFAULT NULL,
						`prod_no` varchar(30) DEFAULT NULL,
						`ptid` varchar(30) DEFAULT NULL,
						`revision_no` varchar(30) DEFAULT NULL,
						`service_name` varchar(30) DEFAULT NULL,
						`chargeable_state` int(11) DEFAULT NULL,
						`service_remark` varchar(30) DEFAULT NULL,
						`service_add_by` varchar(30) DEFAULT NULL,
						`service_add_on` varchar(30) DEFAULT NULL,
						`within_e_warranty` int(11) DEFAULT NULL,
						`within_s_warranty` int(11) DEFAULT NULL,
						`status_name` varchar(30) DEFAULT NULL,

						`qt_no` varchar(30) DEFAULT NULL,
						`qt_date` date DEFAULT NULL,
						`qt_amount` decimal(18,2) DEFAULT NULL,

						`released` int(11) DEFAULT NULL,
						`released_on` date DEFAULT NULL,
						`released_by` varchar(30) DEFAULT NULL,
						`released_to` varchar(30) DEFAULT NULL,
						`released_remark` varchar(200) DEFAULT NULL,

						`fault_id` int(11) DEFAULT NULL,
						`fault_name` varchar(100) DEFAULT NULL,
						`fault_remark` varchar(200) DEFAULT NULL,
						`fault_add_by` varchar(30) DEFAULT NULL,
						`fault_add_on` datetime DEFAULT NULL,

						`ap_id` int(11) DEFAULT NULL,
						`ap_name` varchar(100) DEFAULT NULL,
						`ap_chargeable_state` int(11) DEFAULT NULL,
						`ap_remark` varchar(100) DEFAULT NULL,
						`ap_reason` varchar(100) DEFAULT NULL,
						`ap_add_by` varchar(30) DEFAULT NULL,
						`ap_add_on` datetime DEFAULT NULL,

						`rp_id` int(11) DEFAULT NULL,
						`rp_name` varchar(200) DEFAULT NULL,
						`rp_issue` int(11) DEFAULT NULL,

						`rm_part_id` int(11) DEFAULT NULL,
						`rm_part_name` varchar(200) DEFAULT NULL,
						`removed_by` varchar(30) DEFAULT NULL,
						`removed_on` datetime DEFAULT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
				";

		DB::select($sql_command);
	}

	public function insert_tmp_jobcard_report_table($arr){

		DB::table('tmp_jobcard_report')->insert($arr);
	}

	public function update_tmp_jobcard_report_table($arr, $icount){

		DB::table('tmp_jobcard_report')->where('jobcard_no', $arr['jobcard_no'])->where('order_no', $icount)->update($arr);
	}

	public function get_tmp_jobcard_report_table($filter){

		$sql_query = " select		*
					   from			tmp_jobcard_report
					   where		1=1  ". $filter ."
					   order by		jobcard_no desc, order_no";

		$resultset = DB::select($sql_query);

		return $resultset;
	}

    public function getJobcard($jobcard_number){

		$result =  DB::connection('mysql2')->table('hw_terminals')->where('jobcard_no', $jobcard_number)->first();

        return $result;
	}

    public function getRemovedSparepartDetail($jobcard_number){

		$result = DB::connection('mysql2')->table('hw_repair_removeparts')->where('jobcard_no', $jobcard_number)->get();

		return $result;
	}

}
