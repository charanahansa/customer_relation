<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReInitilization extends Model{

    use HasFactory;

    public function get_bank(){

		$sql_query = "select * from bank";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_models(){

		$sql_query = "select * from model";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_officers(){

		$sql_query = "select * from officers";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_sub_status(){

		$sql_query = "select * from newinstall_sub_status";
		$result = DB::select($sql_query);

		return $result;
	}

	public function get_status(){

		$sql_query = 'select * from status where ref = "breakdown" ';
		$result = DB::select($sql_query);

		return $result;
	}

    public function get_report($from_date, $to_date, $total_filter, $tables){

		$ftl_query = '';
		$ftl_join = '';

		$tmc_query = '';
		$tmc_join = '';
		$tmc_where = '';

		$tp_query = '';
		$tp_join = '';

		$fs_query = '';
		$fs_join = '';

		// Field Service Team Lead
		if(in_array("re_initialization_ftl_view", $tables)){

			$ftl_query = " , ftl.tdate as 'ftl_date', ftl.contactno as 'ftl_contactno', 
			                 o_ftl.officer_name as 'ftl_officer', co_ftl.officer_name as 'ftl_courier', bo_ftl.officer_name as 'ftl_bank_officer_name', 
                             ftl.remark as 'ftl_remark', ss_ftl.status as 'ftl_sub_status', ftl.status as 'ftl_status', ftl.done_date_time as 'ftl_done_date_time', 
                             ftl.email as 'ftl_email', ftl.email_on as 'ftl_email_on',
                             ftl.saved_by as 'ftl_saved_by', ftl.saved_on as 'ftl_saved_on', ftl.edit_by as 'ftl_last_edit_by', ftl.edit_on as 'ftl_last_edit_on' ";

			$ftl_join = "   left outer join re_initialization_ftl_view ftl on ftl.ticketno = r.ticketno 
                            left outer join re_initialization_sub_status ss_ftl on ftl.sub_status = ss_ftl.id 
                            left outer join officers o_ftl on ftl.officer = o_ftl.id 
                            left outer join bank_officer bo_ftl on ftl.bank_officer = bo_ftl.id
                            left outer join courier co_ftl on co_ftl.id = ftl.courier	  ";
		}

		// Terminal Management Coordinator
		if(in_array("re_initialization_tmc_view", $tables)){

			$tmc_query = " 	, tmc.tdate as 'tmc_date', tmc.contactno as 'tmc_contactno', tmc.contact_person as 'tmc_contact_person',
                            tmc.model as 'tmc_model', tmc.serialno as 'tmc_serialno', tmc.merchant as 'tmc_merchant', tmc.pod_no,
                            o_tmc.officer_name as 'tmc_officer', co_tmc.officer_name as 'courier', tmc.collect_from_courier,
                            tmc.remark as 'tmc_remark', ss_tmc.status as 'tmc_sub_status',
                            tmc.saved_by as 'tmc_saved_by', tmc.saved_on as 'tmc_saved_on',
                            tmc.edit_by as 'tmc_edit_by', tmc.edit_on as 'tmc_edit_on' ";

			$tmc_join = " 	left outer join re_initialization_tmc_view tmc on tmc.ticketno = r.ticketno 
                            left outer join officers o_tmc on tmc.officer = o_tmc.id 
                            left outer join re_initialization_sub_status ss_tmc on tmc.sub_status = ss_tmc.id
                            left outer join courier co_tmc on co_tmc.id = tmc.courier ";
			
			$tmc_where = " && (tmc.cancel = 0 or tmc.cancel is null) ";
		}

		// Terminal Programmer
		if(in_array("re_initialization_tp_view", $tables)){

			$tp_query = " , tp.tdate as 'tp_date', tp.contactno as 'tp_contactno', tp.contact_person as 'tp_contact_person', tp.model as 'tp_model', tp.serialno as 'tp_serialno', 
                            tp.sim_no as 'tp_sim_no', tp.pod_no as 'tp_pod_no', tp.remark as 'tp_remark',
                            o_tp.officer_name as 'tp_officer', ss_tp.status as 'tp_sub_status', 
                            tp.saved_by as 'tp_saved_by', tp.saved_on as 'tp_saved_on', tp.edit_by as 'tp_edit_by', tp.edit_on as 'tp_edit_on' ";

			$tp_join = " left outer join re_initialization_tp_view tp on tp.ticketno = r.ticketno 
                         left outer join officers o_tp on tp.officer = o_tp.id 
                         left outer join re_initialization_sub_status ss_tp on tp.sub_status = ss_tp.id  ";
		}

		// Field Service Officer
		if(in_array("re_initialization_fs_view", $tables)){

			$fs_query = " , fs.tdate as 'fs_date', fs.model as 'fs_model', fs.serialno as 'fs_serialno', fs.merchant as 'fs_merchant',
                            fs.contactno as 'fs_contactno', fs.contact_person as 'fs_contact_person', 
                            fs.remark as 'fs_remark', ss_fs.status as 'fs_sub_status', fs.status as 'fs_status', fs.done_date_time as 'fs_done_date_time',
                            fs.email as 'fs_email', fs.email_on as 'fs_email_on', fs.saved_by as 'fs_saved_by', fs.saved_on as 'fs_saved_on', fs.edit_by as 'fs_edit_by', fs.edit_on as 'fs_edit_on' ";

			$fs_join = "    left outer join re_initialization_fs_view fs on fs.ticketno = r.ticketno 
                            left outer join re_initialization_sub_status ss_fs on fs.sub_status = ss_fs.id";
		}

		$sql_query = "  select		r.ticketno, r.tdate, r.bank, r.tid, r.model, r.serialno, r.merchant, o.officer_name, co.officer_name, bo.officer_name as 'bank_officer', 
                                    r.contactno, r.contact_person, r.remark, '' as 'Reasons', ss.status as 'sub_status', r.status, r.done_date_time,
                                    r.email, r.email_on, r.cancel, r.cancel_reason, r.cancel_by, r.cancel_on,
                                    r.saved_by, r.saved_on, r.edit_by, r.edit_on

									" . $ftl_query ."
									" . $tmc_query ."
									" . $tp_query ."
									" . $fs_query ."
					
                        from		re_initialization r
                                        left outer join officers o on r.officer = o.id
                                        left outer join courier co on r.courier = co.id
                                        left outer join re_initialization_sub_status ss on r.sub_status = ss.id
                                        left outer join bank_officer bo on r.bank_officer = bo.id

									". $ftl_join ."
									". $tmc_join ."
									". $tp_join ."
									". $fs_join ."
						
						where		r.cancel = 0 && r.tdate between ? and ? " . $total_filter . " 
									" . $tmc_where . "
						order by    r.tdate desc, r.ticketno desc ";

		//echo $sql_query;

		$result = DB::select($sql_query,[$from_date, $to_date]);

		return $result;

	}


}
