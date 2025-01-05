<?php

namespace App\Models\FieldService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Breakdown extends Model {

    use HasFactory;

	public function saveBreakdownFTL($data){

		DB::beginTransaction();

		//try{

			$input = $data['input'];
			$ftl = $data['ftl'];
			$main = $data['main'];
			$history = $data['history'];
			$trn = $data['trn'];
			$tpn = $data['tpn'];
			$status = $data['status'];
			$sms = $data['sms'];
			$email = $data['email'];

			$ticket_number = $input['ticket_number'];

			// Backup Remove History
			for ($x = 1; $x <= count($history); $x++) {

                DB::table('breakdown_history')->insert($history[$x]);
            }

			// Breakdown FTL
			if(  DB::table('breakdown_ftl_view')->where('ticketno', $ticket_number)->exists() ){

				DB::table('breakdown_ftl_view')->where('ticketno', $ticket_number)->update($ftl);
			}else{

				DB::table('breakdown_ftl_view')->insert($ftl);
			}

			// Breakdown
			DB::table('breakdown')->where('ticketno', $ticket_number)->update($main);

			// Terminal Request Note
			$trn_settle_status = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
																   ->where('ref', 'breakdown')
																   ->where('settle', 1)
																   ->where('cancel', 0)
																   ->exists();
			if( $trn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") || ($input['status'] == 'done') ){

					DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'breakdown')->update($trn);

				}else{

					$isExistTrnResult = DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'breakdown')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'breakdown')->update($trn);

					}else{

						DB::table('terminal_request_note')->insert($trn);
						DB::table('lastno')->increment('terminal_request');
					}
				}
			}

			// Terminal Programme Note
			$tpn_settle_status = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
																	 ->where('ref', 'breakdown')
																	 ->where('settle', 1)
																	 ->where('cancel', 0)
																	 ->exists();
			if( $tpn_settle_status == FALSE ){

				if( $input['status'] == 'done'){

					$tpn_cancel_status = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
																			 ->where('ref', 'breakdown')
																			 ->where('settle', 0)
																			 ->where('cancel', 0)
																			 ->exists();
					if( $tpn_cancel_status ){

						// Cancel Process
						DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
															->where('ref', 'breakdown')
															->where('settle', 0)
															->where('cancel', 0)
															->update($tpn);
					}
				}
			}

			// Status Updation Part

			DB::table('breakdown')->where('ticketno', $ticket_number)->update($status);
			DB::table('breakdown_tp_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('breakdown_fs_view')->where('ticketno', $ticket_number)->update($status);
			unset($status['status']);
			DB::table('breakdown_tmc_view')->where('ticketno', $ticket_number)->update($status);

			// SMS Request
			if($sms['office_mobile'] != ''){

				DB::table('sms_request')->insert($sms);
			}

			//Email Request
			if($input['submit'] == 'Email'){

				DB::table('email_request')->insert($email);
			}


			DB::commit();

			$process_result['ticket_no'] = $ticket_number;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['ticket_no'] = $ticket_number;
        //  $process_result['process_status'] = FALSE;
        //  $process_result['front_end_message'] = $e->getMessage();
        //  $process_result['back_end_message'] = 'Backup Process Model -> Backup Process Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

    public function getBreakdownTicketForAllocate($orderby){

		$sql_query = " select 		*
					   from			breakdown
					   where  		cancel = 0 && status = 'inprogress' && officer = 'Not' &&  courier = 'Not'
					   ". $orderby ."     ";

		$result = DB::select($sql_query);

		return $result;
    }

    public function getBreakdownDetailCC($ticket_number){

        $result = DB::table('breakdown')->where('ticketno', $ticket_number)->get();

		return $result;
    }

	public function getBreakdownDetailFTL($ticket_number){

		$result = DB::table('breakdown_ftl_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

    public function getBreakdownDetailTmc($ticket_number){

		$result = DB::table('breakdown_tmc_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getBreakdownFieldService($ticket_number){

		$result = DB::table('breakdown_fs_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getBreakdownFieldFaults($ticket_number){

		$result = DB::table('breakdown_fs_view_faults')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getBreakdownFieldActionTaken($ticket_number){

		$result = DB::table('breakdown_fs_view_action_taken')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getBreakdownSparePartRequest($ticket_number){

		$result = DB::table('spare_part_request_note')->where('workflow_id', 1)->where('reference_number', $ticket_number)->get();

		return $result;
	}

	public function isAllocatedTicketNumber($ticket_number){

		$result = DB::table('breakdown_ftl_view')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function getBreakdownHistory($ticket_number){

		$result = DB::table('breakdown_history')->where('ticketno', $ticket_number)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function getBreakdownInquireResult($query_part){

		// echo $query_part;
		// echo '<br>';

		$sql_query = " select		b.ticketno, b.tdate, b.bank, tid, mid, b.merchant as 'cc_merchant', e.error as 'fault',
									b.model, b.fault_serialno as 'cc_fault_serialno', b.replaced_serialno as 'cc_replaced_serialno',
									b.contactno as 'cc_contactno', b.contact_person as 'cc_contact_person',
									o.officer_name as 'cc_officer', caller, receiver,
									replacement, refno as 'replacement_refno',
									co.officer_name as 'courier', of.officer_name as 'field_officer', bo.officer_name,
									rd.relevant_detail as 'cc_relevant_detail', act.action_taken as 'cc_action_taken', b.remark as 'cc_remark', call_to_merchant,
									ss.status as 'sub_status', b.status, b.done_date_time, b.email, b.email_on, b.cancel, b.cancel_reason, b.cancel_on, b.cancel_by,
									b.saved_by, b.saved_on, b.edit_by as 'cc_last_edit_by', b.edit_on as 'cc_last_edit_on'
					   from			breakdown b
										left outer join officers o on b.call_handler = o.id
										left outer join officers of on b.officer = of.id
										left outer join error e on b.error = e.eno
										left outer join breakdown_sub_status ss on b.sub_status = ss.id
										left outer join relevent_detail rd on b.relevant_detail = rd.rno
										left outer join action_taken act on b.action_taken = act.ano
										left outer join bank_officer bo on b.bank_officer = bo.id
										left outer join courier co on co.id = b.courier
						where		b.cancel = 0 " . $query_part . "
						order by    b.tdate desc, b.ticketno desc LIMIT 50;  ";

		$result = DB::select($sql_query);

		return $result;
	}



}
