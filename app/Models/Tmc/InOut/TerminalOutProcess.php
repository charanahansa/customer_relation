<?php

namespace App\Models\Tmc\InOut;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalOutProcess extends Model {

    use HasFactory;

	public function saveTerminalOutNote($data){

		DB::beginTransaction();

		//try{

			$terminal_out_note = $data['terminal_out_note'];
			$terminal_out_note_detail = $data['terminal_out_note_detail'];

			if($terminal_out_note['ticketno'] == '#Auto#'){

				unset($terminal_out_note['ticketno']);

				DB::table('terminal_out_process')->insert($terminal_out_note);
				$ticket_number = DB::getPdo()->lastInsertId();

			}else{

				$ticket_number = $terminal_out_note['ticketno'];
				DB::table('terminal_out_process')->where('ticketno', $ticket_number)->update($terminal_out_note);
			}

			DB::table('terminal_out_process_detail')->where('ticketno', '=', $ticket_number)->delete();
			foreach($terminal_out_note_detail as $row){

				$row['ticketno'] = $ticket_number;
				DB::table('terminal_out_process_detail')->insert($row);
			}

			DB::commit();

			$process_result['ticket_number'] = $ticket_number;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['ticket_number'] = $terminal_out_note['ticketno'];
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal Out Model -> Terminal Out Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	public function confirmTerminalOutNote($data, $ticket_number){

		DB::beginTransaction();

		try{

			$confirm_row = $data['confirm_row'];
			$terminal_log = $data['terminal_log'];
            $tmc_bin = $data['tmc_bin'];

			DB::table('terminal_out_process')->where('ticketno', $ticket_number)->update($confirm_row);

			// Terminal Log
			foreach($terminal_log as $row){

				if (DB::table('terminal_log')->where('serialno', $row['serialno'])->exists()) {

					DB::table('terminal_log')->where('serialno', $row['serialno'])->update($row);
				}else{

					DB::table('terminal_log')->insert($row);
				}
			}

            // Tmc Bin
			foreach($tmc_bin as $row){

				DB::table('tmc_bin')->where('serial_number', $row['serial_number'])->update($row);
			}

			DB::commit();

			$process_result['ticket_number'] = $ticket_number;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Confirm Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['ticket_number'] = $ticket_number;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Terminal Out Process Model -> Terminal Out Process Confirm Process <br> ' . $e->getLine();

            return $process_result;
		}

	}

	public function cancelTerminalOutNote($data, $ticket_number){

		DB::beginTransaction();

		//try{

			$cancel_row = $data['cancel_row'];

			DB::table('terminal_out_process')->where('ticketno', $ticket_number)->update($cancel_row);

			DB::commit();

			$process_result['ticket_number'] = $ticket_number;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Cancel Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['ticket_number'] = $ticket_number;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal Out Note Model -> Terminal Out Note Cancel Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	public function getTerminalOutNote($ticket_number){

		$result = DB::table('terminal_out_process')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getTerminalOutNoteDetail($ticket_number){

		$result = DB::table('terminal_out_process_detail')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getTerminalOutNoteWithOfficerDetail($ticket_number){

		$sql_query = " 	select		t.ticketno, t.tdate, t.bank, t.remark, o.officer_name, o.phone, o.address
					 	from		terminal_out_process t inner join officers o  on t.officer = o.id
						where		ticketno = ?  ";
		$result = DB::select($sql_query, [$ticket_number]);

		return $result;
	}

	public function isExistTerminalOutNoteNumber($ticket_number){

		$result = DB::table('terminal_out_process')->where('ticketno', $ticket_number)->exists();

		return $result;
	}

	public function isCancelTerminalOutNoteNumber($ticket_number){

		$result = DB::table('terminal_out_process')->where('ticketno', $ticket_number)->where('cancel', 1)->exists();

		return $result;
	}

	public function isConfirmTerminalOutNoteNumber($ticket_number){

		$result = DB::table('terminal_out_process')->where('ticketno', $ticket_number)->where('confirm', 1)->exists();

		return $result;
	}

	public function getSparePartIssueType(){

		$result = DB::table('spare_part_issue_type')->whereIn('sp_issue_type_id', [2, 3])->get();

		return $result;
	}

	public function getModel($serial_number){

		$model = DB::table('terminal_log')->where('serialno', $serial_number)->value('model');

		return $model;
	}

	public function getTerminalOutNoteInquireResult($query_part){

		$sql_query = " select		top.ticketno, top.tdate, top.bank, s.sp_issue_type_name, top.type, o.officer_name, top.pod_no, c.officer_name as 'courier_name', top.remark,
									top.confirm, top.cancel, top.cancel_reason, count(tod.serialno) as 'terminal_count'
					   from			terminal_out_process top
					   					inner join terminal_out_process_detail tod on top.ticketno = tod.ticketno
										left outer join officers o on top.officer = o.id
										left outer join spare_part_issue_type s on top.source = s.sp_issue_type_id
										left outer join courier c on top.courier = c.id
					   where		cancel = 0 ". $query_part ."
					   group by		top.ticketno, top.tdate, top.bank, top.source, top.type, o.officer_name, top.pod_no, top.courier, top.remark,
									top.confirm, top.cancel, top.cancel_reason
					   order by		ticketno desc  limit 100;  ";

		$result = DB::select($sql_query);

		return $result;
	}

	public function getModelWiseTerminalCount($ticket_number){

		$sql_query = " select		model, count(serialno) as 'terminal_count'
					   from			terminal_out_process_detail
					   where		ticketno = ". $ticket_number ."
					   group by		model
					   order by     count(serialno)  ";

		$result = DB::select($sql_query);

		return $result;
	}
}
