<?php

namespace App\Models\FieldService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class BackupRemoveProcess extends Model {

    use HasFactory;

	public function saveBackupRemoveFTL($data){

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

			// History
			for ($x = 1; $x <= count($history); $x++) {

                DB::table('backup_remove_note_history')->insert($history[$x]);
            }

			// FTL
			if(  DB::table('backup_removal_ftl_view')->where('ticketno', $ticket_number)->exists() ){

				DB::table('backup_removal_ftl_view')->where('ticketno', $ticket_number)->update($ftl);
			}else{

				DB::table('backup_removal_ftl_view')->insert($ftl);
			}

			// Backup Remove Note
			DB::table('backup_remove_note')->where('brn_no', $ticket_number)->update($main);

			// Officer Allocate Note
			DB::table('officer_allocate_note')->where('ticketno', $ticket_number)->where('ref', 'backup_removal')->where('settle', 0)->where('cancel', 0)->update($oln);

			// Terminal Request Note
			$trn_settle_status = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
																   ->where('ref', 'backup_removal')
																   ->where('settle', 1)
																   ->where('cancel', 0)
																   ->exists();
			if( $trn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") ){

					DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'backup_removal')->update($trn);

				}else{

					$isExistTrnResult = DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'backup_removal')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'backup_removal')->update($trn);

					}else{

						DB::table('terminal_request_note')->insert($trn);
						DB::table('lastno')->increment('terminal_request');
					}
				}
			}

			// Status Updation Part
			DB::table('backup_remove_note')->where('brn_no', $ticket_number)->update($status);
			DB::table('backup_remove_note_fs_view')->where('ticketno', $ticket_number)->update($status);
			unset($status['status']);
			DB::table('backup_remove_note_tp_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('backup_remove_note_tmc_view')->where('ticketno', $ticket_number)->update($status);

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

    public function getBackupRemoveTicketForAllocate($orderby){

        $sql_query = " select	  b.brn_no, b.brn_date, b.bank, b.tid,  b.backup_model, b.merchant, b.status
                       from		  officer_allocate_note a inner join backup_remove_note b on a.ticketno=b.brn_no
                       where      a.settle=0 && a.cancel=0 && a.ref = 'backup_removal' && b.status <>'done'
                       ". $orderby ." ";

        $result = DB::select($sql_query);

		return $result;
    }

	public function getBackupRemoveProcessHistory($ticket_number){

		$result = DB::table('backup_remove_note_history')->where('ticketno', $ticket_number)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function getBackupRemoveProcessDetail($ticket_number){

		$result = DB::table('backup_remove_note')->where('brn_no', $ticket_number)->get();

		return $result;
	}

	public function getBackupRemoveProcessDetailFTL($ticket_number){

		$result = DB::table('backup_removal_ftl_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

    public function getBackupRemoveProcessDetailTmc($ticket_number){

		$result = DB::table('backup_remove_note_tmc_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function isAllocatedTicketNumber($ticket_number){

		$result = DB::table('backup_removal_ftl_view')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function getBackupRemoveProcessFieldService($ticket_number){

		$result = DB::table('backup_remove_note_fs_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getBackupRemoveInquireResult($query_part){

		$sql_query = " select		brn.brn_no, brn.brn_date, brn.bin_no, brn.bank, brn.tid, brn.merchant, brn.backup_serialno, brn.backup_model, brn.replaced_model, brn.replaced_serialno,
									brn.contact_number, brn.contact_person, brn.remark, o.officer_name as 'field_officer', co.officer_name as 'courier', ss.status as 'sub_status', brn.status, brn.done_date_time,
									brn.cancel, brn.cancel_reason, brn.cancel_on, brn.cancel_by,
									brn.saved_by, brn.saved_on, brn.edit_by, brn.edit_on
					   from			backup_remove_note brn
										left outer join officers o on brn.officer = o.id
										left outer join courier co on brn.courier = co.id
										inner join backup_removal_sub_status ss on brn.sub_status = ss.id
						where		brn.cancel = 0 ". $query_part ."
						order by 	brn.brn_date desc, brn.brn_no desc  ";

		$result = DB::select($sql_query);

		return $result;
	}

}
