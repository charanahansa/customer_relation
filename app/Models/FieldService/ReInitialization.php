<?php

namespace App\Models\FieldService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class ReInitialization extends Model {

    use HasFactory;

	public function saveReInitializationFTL($data){

		DB::beginTransaction();

		//try{

			$input = $data['input'];
			$ftl = $data['ftl'];
			$main = $data['main'];
			$history = $data['history'];
			$oln = $data['oln'];
			$trn = $data['trn'];
			$tpn = $data['tpn'];
			$status = $data['status'];
			$sms = $data['sms'];
			$email = $data['email'];

			$ticket_number = $input['ticket_number'];

			// Re Initialization History
			for ($x = 1; $x <= count($history); $x++) {

                DB::table('re_initialization_history')->insert($history[$x]);
            }

			// Re Initialization FTL
			if(  DB::table('re_initialization_ftl_view')->where('ticketno', $ticket_number)->exists() ){

				DB::table('re_initialization_ftl_view')->where('ticketno', $ticket_number)->update($ftl);
			}else{

				DB::table('re_initialization_ftl_view')->insert($ftl);
			}

			// Re Initialization
			DB::table('re_initialization')->where('ticketno', $ticket_number)->update($main);

			// Officer Allocate Note
			DB::table('officer_allocate_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->where('settle', 0)->where('cancel', 0)->update($oln);

			// Terminal Request Note
			$trn_settle_status = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
																   ->where('ref', 're_initialization')
																   ->where('settle', 1)
																   ->where('cancel', 0)
																   ->exists();
			if( $trn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") ){

					DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->update($trn);

				}else{

					$isExistTrnResult = DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->update($trn);

					}else{

						DB::table('terminal_request_note')->insert($trn);
						DB::table('lastno')->increment('terminal_request');
					}
				}
			}

			// Terminal Programme Note
			$tpn_settle_status = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
																   ->where('ref', 're_initialization')
																   ->where('settle', 1)
																   ->where('cancel', 0)
																   ->exists();
			if( $tpn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") ){

					DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->update($tpn);

				}else{

					$isExistTrnResult = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 're_initialization')->update($tpn);

					}else{

						DB::table('terminal_programme_note')->insert($tpn);
						DB::table('lastno')->increment('terminal_programme');
					}
				}
			}

			// Status Updation Part
			DB::table('re_initialization')->where('ticketno', $ticket_number)->update($status);
			DB::table('re_initialization_fs_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('re_initialization_vc_view')->where('ticketno', $ticket_number)->update($status);
			unset($status['status']);
			DB::table('re_initialization_tp_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('re_initialization_tmc_view')->where('ticketno', $ticket_number)->update($status);

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

    public function getReInitializationTicketForAllocate($orderby){

        $sql_query = " select		a.ticketno, r.tdate, r.bank, r.tid, r.merchant, r.status
                       from		    officer_allocate_note a inner join re_initialization r on a.ticketno=r.ticketno
                       where		a.settle=0 && a.cancel=0 && a.ref='re_initialization' && r.cancel=0 && r.status not in ('done', 'hold')
					   ". $orderby ." ";

        $result = DB::select($sql_query);

		return $result;
    }

	public function getReInitializationHistory($ticket_number){

		$result = DB::table('re_initialization_history')->where('ticketno', $ticket_number)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function getReInitializationDetail($ticket_number){

        $result = DB::table('re_initialization')->where('ticketno', $ticket_number)->get();

		return $result;
    }

	public function getReInitializationDetailFTL($ticket_number){

		$result = DB::table('re_initialization_ftl_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

    public function getReInitializationDetailTmc($ticket_number){

		$result = DB::table('re_initialization_tmc_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function isAllocatedTicketNumber($ticket_number){

		$result = DB::table('re_initialization_ftl_view')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function getReInitializationFieldService($ticket_number){

		$result = DB::table('re_initialization_fs_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getReInitializationInquireResult($query_part){

		$sql_query = " select		r.ticketno, r.tdate, r.bank, r.tid, r.model, r.serialno, r.merchant, o.officer_name as 'field_officer', co.officer_name as 'courier', bo.officer_name as 'bank_officer',
									r.contactno, r.contact_person, r.remark, '' as 'Reasons', ss.status as 'sub_status', r.status, r.done_date_time,
									r.email, r.email_on, r.cancel, r.cancel_reason, r.cancel_by, r.cancel_on,
									r.saved_by, r.saved_on, r.edit_by, r.edit_on
					   from			re_initialization r
										left outer join officers o on r.officer = o.id
										left outer join courier co on r.courier = co.id
										left outer join re_initialization_sub_status ss on r.sub_status = ss.id
										left outer join bank_officer bo on r.bank_officer = bo.id
					   where		r.cancel = 0 ". $query_part ."
					   order by    r.tdate desc, r.ticketno desc ";

		$result = DB::select($sql_query);

		return $result;
	}

}
