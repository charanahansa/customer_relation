<?php

namespace App\Models\FieldService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class NewInstallation extends Model {

    use HasFactory;


	public function saveNewInstallationFTL($data){

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

			// New Installation History
			for ($x = 1; $x <= count($history); $x++) {

                DB::table('newinstall_history')->insert($history[$x]);
            }

			// New Installation FTL
			if(  DB::table('newinstall_ftl_view')->where('ticketno', $ticket_number)->exists() ){

				DB::table('newinstall_ftl_view')->where('ticketno', $ticket_number)->update($ftl);
			}else{

				DB::table('newinstall_ftl_view')->insert($ftl);
			}

			// New Installation
			DB::table('newinstall')->where('ticketno', $ticket_number)->update($main);

			// Officer Allocate Note
			DB::table('officer_allocate_note')->where('ticketno', $ticket_number)->where('ref', 'new')->where('settle', 0)->where('cancel', 0)->update($oln);

			// Terminal Programme Note
			DB::table('terminal_programme_note')->where('ticketno', $ticket_number)->where('ref', 'new')->update($tpn);

			// Terminal Request Note
			DB::table('terminal_request_note')->where('ticketno', $ticket_number)->where('ref', 'new')->update($trn);

			// Status Updation Part
			DB::table('newinstall')->where('ticketno', $ticket_number)->update($status);
			DB::table('newinstall_fs_view')->where('ticketno', $ticket_number)->update($status);
			unset($status['status']);
			DB::table('newinstall_tp_view')->where('ticketno', $ticket_number)->update($status);
			DB::table('newinstall_tmc_view')->where('ticketno', $ticket_number)->update($status);

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


    public function getNewInstallationTicketForAllocate($orderby){

        $sql_query = " select		a.ticketno, n.tdate, n.bank, n.tid, n.merchant, n.status
                       from		    officer_allocate_note a inner join newinstall n on a.ticketno=n.ticketno
                       where		settle=0 && a.ref='new' && a.cancel=0 && n.cancel=0 && n.status <> 'done'
                       ". $orderby ." ";

        $result = DB::select($sql_query);

		return $result;
    }

	public function getNewInstallationDetail($ticket_number){

        $result = DB::table('newinstall')->where('ticketno', $ticket_number)->get();

		return $result;
    }

	public function getNewInstallationDetailFTL($ticket_number){

		$result = DB::table('newinstall_ftl_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

    public function getNewInstallationDetailTmc($ticket_number){

		$result = DB::table('newinstall_tmc_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function isAllocatedTicketNumber($ticket_number){

		$result = DB::table('newinstall_ftl_view')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function getNewInstallationFieldService($ticket_number){

		$result = DB::table('newinstall_fs_view')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getNewInstallationHistory($ticket_number){

		$result = DB::table('newinstall_history')->where('ticketno', $ticket_number)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function getNewInquireResult($query_part){

		$sql_query = "  select		n.ticketno, n.tdate, n.bank, n.tid, n.mid, n.model, n.serialno, n.merchant, n.replacement_type, n.replacement_no, n.contactno, n.contact_person,
                                    o.officer_name, co.officer_name as 'courier', bo.officer_name as 'bank_officer',
                                    n.remark, ss.status as 'sub_status', n.status, n.done_date_time,
                                    n.email, n.email_on, n.cancel, n.cancel_reason, n.cancel_by, n.cancel_on,
                                    n.saved_by, n.saved_on, n.edit_by, n.edit_on
                        from		newinstall n
                                        left outer join officers o on n.officer = o.id
                                        left outer join courier co on co.id = n.courier
                                        left outer join newinstall_sub_status ss on n.sub_status = ss.id
                                        left outer join bank_officer bo on n.bank_officer = bo.id
						where		n.cancel = 0 ". $query_part ."
						order by    n.tdate desc, n.ticketno desc ";

		$result = DB::select($sql_query);

		return $result;
	}

}
