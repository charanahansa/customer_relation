<?php

namespace App\Models\FieldService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SoftwareUpdate extends Model {

    use HasFactory;

	public function saveSoftwareUpdationFTL($data){

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

			// Software Updation History
			for ($x = 1; $x <= count($history); $x++) {

                DB::table('software_updation_history')->insert($history[$x]);
            }

			// Software Updation FTL
			if(  DB::table('software_updation_ftl_view')->where('ticketno', $ticket_number)->exists() ){

				DB::table('software_updation_ftl_view')->where('ticketno', $ticket_number)->update($ftl);
			}else{

				DB::table('software_updation_ftl_view')->insert($ftl);
			}

			// Software Updation
			DB::table('software_updation_detail')->where('ticketno', $ticket_number)->update($main);

			// Officer Allocate Note
			DB::table('officer_allocate_note')->where('ticketno', $ticket_number)
											  ->where('ref', 'software_updation')
											  ->where('settle', 0)
											  ->where('cancel', 0)
											  ->update($oln);

			// Terminal Request Note
			$trn_settle_status = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
																   ->where('ref', 'software_updation')
																   ->where('settle', 1)
																   ->where('cancel', 0)
																   ->exists();
			if( $trn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") ){

					DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'software_updation')->update($trn);

				}else{

					$isExistTrnResult = DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'software_updation')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'software_updation')->update($trn);

					}else{

						DB::table('terminal_request_note')->insert($trn);
						DB::table('lastno')->increment('terminal_request');
					}
				}
			}

			// Terminal Programme Note
			$tpn_settle_status = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
																	 ->where('ref', 'software_updation')
																	 ->where('settle', 1)
																	 ->where('cancel', 0)
																	 ->exists();
			if( $tpn_settle_status == FALSE ){

				// Cancel Process
				if( ($input['courier'] == "Not") ){

					DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 'software_updation')->update($tpn);

				}else{

					$isExistTrnResult = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 'software_updation')->exists();
					if($isExistTrnResult == TRUE){

						DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 'software_updation')->update($tpn);

					}else{

						DB::table('terminal_programme_note')->insert($tpn);
						DB::table('lastno')->increment('terminal_programme');
					}
				}
			}

			// Status Updation Part
			DB::table('software_updation_fs_view')->where('ticketno', $ticket_number)->update($status);
			unset($status['status']);
			DB::table('software_updation_tp_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('software_updation_tmc_view')->where('ticketno', $ticket_number)->update($status);

			unset($status['edit_by']);
			unset($status['edit_on']);
			unset($status['edit_ip']);
			DB::table('software_updation_detail')->where('ticketno', $ticket_number)->update($status);

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


    public function getSoftwareUpdatingTicketForAllocate($orderby){

        $sql_query = " select	a.ticketno, s.tdate, s.bank, sd.tid, sd.merchant, ss.status
                       from		officer_allocate_note a
                                    inner join software_updation_detail sd on a.ticketno=sd.ticketno
                                    inner join software_updation s on s.batchno=sd.batchno
                                    inner join software_updation_sub_status ss on sd.sub_status=ss.id
                       where	settle=0 && a.cancel=0 && s.cancel=0 && sd.cancel = 0 && s.confirm = 1 &&  a.ref='software_updation' && ss.id <> 6
                       ". $orderby . " ";

        $result = DB::select($sql_query);

		return $result;
    }

    public function getSoftwareUpdationInfor($ticket_no){

        $sql_query="  select		sd.ticketno, s.tdate, s.bank, sd.tid, sd.model, sd.merchant, sd.vc_remark, s.remark as 'batch_remark', sd.vc_status, sd.sub_status, sd.status
                      from			software_updation s inner join software_updation_detail sd on s.batchno=sd.batchno
                      where			sd.ticketno = ? ";

        $result = DB::select($sql_query, [$ticket_no] );

        return $result;
    }

	public function getSoftwareUpdateHistory($ticket_number){

		$result = DB::table('software_updation_history')->where('ticketno', $ticket_number)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function getSoftwareUpdateDetailFTL($ticket_number){

		$result = DB::table('software_updation_ftl_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

    public function getSoftwareUpdateDetailTmc($ticket_number){

		$result = DB::table('software_updation_tmc_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function isAllocatedTicketNumber($ticket_number){

		$result = DB::table('software_updation_ftl_view')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function getSoftwareUpdateFieldService($ticket_number){

		$result = DB::table('software_updation_fs_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getSoftwareUpdateInquireResult($query_part){

		$sql_query = "  select		su.batchno, su.tdate, su.bank, su.os, su.eos, su.firmware, su.application, su.app_version, su.status, su.confirm_by as 'saved_by', su.confirm_on as 'saved_on',
									sud.ticketno, sud.tid, sud.mid, sud.model, sud.serialno, sud.merchant, sud.contactno, sud.contact_person, sud.cancel, sud.cancel_reason, sud.cancel_by,
									u.name as 'field_officer', sud.bank_officer, sud.courier, sud.remark, sud.vc_Remark,
									suss.status as 'sub_status', sud.status, update_date_time as 'done_date_time'
						from		software_updation su
										inner join software_updation_detail sud on su.batchno = sud.batchno
										left outer join users u on sud.officer = u.officer_id
										left outer join software_updation_sub_status suss on sud.sub_status = suss.id
						where		su.cancel = 0 ". $query_part ."
						order by	sud.ticketno desc, su.tdate ";

		$result = DB::select($sql_query);

		return $result;
	}

}
