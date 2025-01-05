<?php

namespace App\Models\FieldService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalReplacement extends Model {

    use HasFactory;

	public function saveTerminalReplacementFTL($data){

		DB::beginTransaction();

		//try{

			$input = $data['input'];
			$ftl = $data['ftl'];
			$main = $data['main'];
			$history = $data['history'];
			$oln = $data['oln'];
			$trn = $data['trn'];
			$status = $data['status'];
			$sms = $data['sms'];
			$email = $data['email'];

			$ticket_number = $input['ticket_number'];

			// Terminal Replacement History
			for ($x = 1; $x <= count($history); $x++) {

                DB::table('terminal_replacement_history')->insert($history[$x]);
            }

			// Terminal Replacement FTL
			if(  DB::table('terminal_replacement_ftl_view')->where('ticketno', $ticket_number)->exists() ){

				DB::table('terminal_replacement_ftl_view')->where('ticketno', $ticket_number)->update($ftl);
			}else{

				DB::table('terminal_replacement_ftl_view')->insert($ftl);
			}

			// Terminal Replacement
			DB::table('terminal_replacement')->where('ticketno', $ticket_number)->update($main);

			// Officer Allocate Note
			DB::table('officer_allocate_note')->where('ticketno', $ticket_number)
											  ->where('ref', 'terminal_replacement')
											  ->where('settle', 0)
											  ->where('cancel', 0)
											  ->update($oln);

			// Terminal Request Note
			$trn_settle_status = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
																   ->where('ref', 'terminal_replacement')
																   ->where('settle', 1)
																   ->where('cancel', 0)
																   ->exists();
			if( $trn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") ){

					DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'terminal_replacement')->update($trn);

				}else{

					$isExistTrnResult = DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'terminal_replacement')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'terminal_replacement')->update($trn);

					}else{

						DB::table('terminal_request_note')->insert($trn);
						DB::table('lastno')->increment('terminal_request');
					}
				}
			}

			// Status Updation Part
			unset($status['status']);
			DB::table('terminal_replacement_fs_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('terminal_replacement_tp_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('terminal_replacement_tmc_view')->where('ticketno', $ticket_number)->update($status);

			unset($status['edit_by']);
			unset($status['edit_on']);
			unset($status['edit_ip']);
			DB::table('terminal_replacement')->where('ticketno', $ticket_number)->update($status);

			// SMS Request
			DB::table('sms_request')->insert($sms);

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

    public function getTerminalReplacementTicketForAllocate($orderby){

        $sql_query = " select	t.ticketno, t.tdate, t.bank, t.based_tid,  t.based_model, t.merchant, t.status
                       from		officer_allocate_note a inner join terminal_replacement t on a.ticketno=t.ticketno
                       where	a.settle=0 && a.cancel=0 && a.ref = 'terminal_replacement' && t.cancel=0 && t.status <>'done'
					   ". $orderby ." ";

        $result = DB::select($sql_query);

		return $result;
    }

	public function getTerminalReplacementHistory($ticket_number){

		$result = DB::table('terminal_replacement_history')->where('ticketno', $ticket_number)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function getTerminalReplacementDetail($ticket_number){

        $result = DB::table('terminal_replacement')->where('ticketno', $ticket_number)->get();

		return $result;
    }

	public function getTerminalReplacementDetailFTL($ticket_number){

		$result = DB::table('terminal_replacement_ftl_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

    public function getTerminalReplacementDetailTmc($ticket_number){

		$result = DB::table('terminal_replacement_tmc_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function isAllocatedTicketNumber($ticket_number){

		$result = DB::table('terminal_replacement_ftl_view')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function getTerminalReplacementFieldService($ticket_number){

		$result = DB::table('terminal_replacement_fs_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getTerminalReplacementInquireResult($query_part){

		$sql_query = "  select		tr.ticketno, tr.tdate, tr.bank, tr.category,
									tr.based_ref_no, tr.based_tid, tr.based_serialno, tr.based_model, tr.replaced_ref_no, tr.replaced_tid, tr.replaced_serialno, tr.replaced_model,
									tr.merchant, tr.contactno, tr.contact_person, o.officer_name as 'field_officer', co.officer_name as 'courier', bo.officer_name as 'bank_officer',
									tr.remark, ss.status as 'sub_status', tr.status, tr.done_date_time,
									tr.saved_by, tr.saved_on, tr.edit_by, tr.edit_on
						from		terminal_replacement tr
										left outer join officers o on tr.officer = o.id
										left outer join courier co on tr.courier = co.id
										left outer join bank_officer bo on tr.bank_officer = bo.id
										left outer join terminal_replacement_sub_status ss on tr.sub_status = ss.id
						where		tr.cancel = 0 ". $query_part ."
						order by    tr.tdate desc, tr.ticketno desc ";

		$result = DB::select($sql_query);

		return $result;
	}

}
