<?php

namespace App\Models\Tmc\InOut;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalInProcess extends Model {

    use HasFactory;

	public function saveTerminalInNote($data){

		//DB::beginTransaction();

		//try{

			$terminal_in_note = $data['terminal_in_note'];
			$terminal_in_note_detail = $data['terminal_in_note_detail'];

			if($terminal_in_note['ticketno'] == '#Auto#'){

				unset($terminal_in_note['ticketno']);

				DB::table('terminal_in_process')->insert($terminal_in_note);
				$ticket_number = DB::getPdo()->lastInsertId();

			}else{

				$ticket_number = $terminal_in_note['ticketno'];
				DB::table('terminal_in_process')->where('ticketno', $ticket_number)->update($terminal_in_note);
			}

			DB::table('terminal_in_process_detail')->where('ticketno', '=', $ticket_number)->delete();
			foreach($terminal_in_note_detail as $row){

				$row['ticketno'] = $ticket_number;
				DB::table('terminal_in_process_detail')->insert($row);
			}

			DB::commit();

			$process_result['ticket_number'] = $ticket_number;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['ticket_number'] = $terminal_in_note['ticketno'];
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal In Model -> Terminal In Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

	}

	public function confirmTerminalOutNote($data, $ticket_number){

		DB::beginTransaction();

		try{

			$confirm_row = $data['confirm_row'];
			$terminal_log = $data['terminal_log'];
            $tmc_bin = $data['tmc_bin'];

			DB::table('terminal_in_process')->where('ticketno', $ticket_number)->update($confirm_row);

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

				if (DB::table('tmc_bin')->where('serial_number', $row['serial_number'])->exists()) {

					DB::table('tmc_bin')->where('serial_number', $row['serial_number'])->update($row);
				}else{

					DB::table('tmc_bin')->insert($row);
				}
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
            $process_result['back_end_message'] = 'Terminal In Process Model -> Terminal In Process Confirm Process <br> ' . $e->getLine();

            return $process_result;
		}

	}

	public function cancelTerminalInNote($data, $ticket_number){

		DB::beginTransaction();

		//try{

			$cancel_row = $data['cancel_row'];

			DB::table('terminal_in_process')->where('ticketno', $ticket_number)->update($cancel_row);

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
        //     $process_result['back_end_message'] = 'Terminal In Note Model -> Terminal In Note Cancel Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	public function getTerminalInNote($ticket_number){

		$result = DB::table('terminal_in_process')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function getTerminalInNoteDetail($ticket_number){

		$result = DB::table('terminal_in_process_detail')->where('ticketno', $ticket_number)->get();

		return $result;
	}

	public function isCancelTerminalInNoteNumber($ticket_number){

		$result = DB::table('terminal_in_process')->where('ticketno', $ticket_number)->where('cancel', 1)->exists();

		return $result;
	}

	public function isConfirmTerminalInNoteNumber($ticket_number){

		$result = DB::table('terminal_in_process')->where('ticketno', $ticket_number)->where('confirm', 1)->exists();

		return $result;
	}

	public function getModel($serial_number){

		$model = DB::table('terminal_log')->where('serialno', $serial_number)->value('model');

		return $model;
	}

	public function getSparePartIssueType(){

		$result = DB::table('spare_part_issue_type')->whereIn('sp_issue_type_id', [2, 3])->get();

		return $result;
	}

	public function getTerminalInNoteInquireResult($query_part){

		$sql_query = "  select		tip.ticketno, tip.tdate, tip.bank, receive_type, o.officer_name, c.officer_name as 'courier_name', podno, return_podno,
									count(serialno) as 'terminal_count'
						from		terminal_in_process tip
										inner join terminal_in_process_detail tid on tip.ticketno = tid.ticketno
										left outer join officers o on tip.officer = o.id
										left outer join courier c on tip.officer = c.id
						where		tip.cancel = 0 ". $query_part ."
						group by	tip.ticketno, tip.tdate, tip.bank, receive_type, o.officer_name, c.officer_name, podno, return_podno
						having		count(serialno) > 1
						order by	ticketno desc  ";

		$result = DB::select($sql_query);

		return $result;
	}

	public function getModelWiseTerminalCount($ticket_number){

		$sql_query = " select		model, count(serialno) as 'terminal_count'
					   from			terminal_in_process_detail
					   where		ticketno = ". $ticket_number ."
					   group by		model
					   order by     count(serialno)  ";

		$result = DB::select($sql_query);

		return $result;
	}

    public function getTmcBin($query_part){

		$sql_query = " select		*
					   from			tmc_bin
					   where		released = 0 ". $query_part ."
					   order by		serial_number ";

		$result = DB::select($sql_query);

		return $result;
	}
}
