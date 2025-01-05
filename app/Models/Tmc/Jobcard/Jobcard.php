<?php

namespace App\Models\Tmc\Jobcard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Jobcard extends Model {

    use HasFactory;

	public function saveTerminalInNoteAndJobcard($data){

		$ticket_number = 0;
		$jobcard_number = 0;

		//DB::beginTransaction();

		// ALTER TABLE `jobcard` CHANGE `jobcard_no` `jobcard_no` INT(11) NOT NULL AUTO_INCREMENT;

		//try{

			$terminal_in_note = $data['terminal_in_note'];
			$terminal_in_note_detail = $data['terminal_in_note_detail'];
			$jobcard = $data['jobcard'];
			$jobcard_detail = $data['jobcard_detail'];

			if($terminal_in_note['ticketno'] == '#Auto#'){

				// Terminal In Process
				unset($terminal_in_note['ticketno']);

				DB::table('terminal_in_process')->insert($terminal_in_note);
				$ticket_number = DB::getPdo()->lastInsertId();

				// Jobcard
				unset($jobcard['jobcard_no']);

				DB::table('jobcard')->insert($jobcard);
				$jobcard_number = DB::getPdo()->lastInsertId();

				// Update Jobcard Number
				$arr['jobcard_no'] = $jobcard_number;
				DB::table('terminal_in_process')->where('ticketno', $ticket_number)->update($arr);

			}else{

				// Terminal In Process
				$ticket_number = $terminal_in_note['ticketno'];
				DB::table('terminal_in_process')->where('ticketno', $ticket_number)->update($terminal_in_note);

				// Jobcard
				$jobcard_number = $jobcard['jobcard_no'];
				DB::table('jobcard')->where('jobcard_no', $jobcard_number)->update($jobcard);
			}

			// Terminal In Process Detail
			$terminal_in_note_detail['ticketno'] = $ticket_number;
			DB::table('terminal_in_process_detail')->insert($terminal_in_note_detail);

			// Jobcard Detail
			$jobcard_detail['jobcard_no'] = $jobcard_number;
			DB::table('jobcard_detail')->insert($jobcard_detail);

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
        //     $process_result['back_end_message'] = 'Jobcard Model -> Jobcard Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

	}

    public function cancelJobcard($data){

		//DB::beginTransaction();

		$jobcard_table = $data['jobcard'];
		$jobcard_number = $jobcard_table['jobcard_number'];
		unset($jobcard_table['jobcard_number']);

		$terminal_in_note = $data['terminal_in_note'];
		unset($terminal_in_note['jobcard_number']);


		//try{

			DB::table('jobcard')->where('jobcard_no', $jobcard_number)->update($jobcard_table);
			DB::table('terminal_in_process')->where('jobcard_no', $jobcard_number)->update($terminal_in_note);

			//DB::commit();

			$process_result['jobcard_number'] = $jobcard_number;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['jobcard_number'] = $jobcard_number;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Jobcard Model -> Jobcard Saving Process <br> ' . $e->getLine();

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

	public function getJobcardSetting(){

		$result = DB::table('jobcard_setting')->where('js_id', 1)->get();

		return $result;
	}

	public function saveJobcardSetting($data){

		//DB::beginTransaction();

		//try{

			$jobcard_setting = $data['jobcard_setting'];

			DB::table('jobcard_setting')->where('js_id', 1)->update($jobcard_setting);

			DB::commit();

            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

        //  $process_result['process_status'] = FALSE;
        //  $process_result['front_end_message'] = $e->getMessage();
        //  $process_result['back_end_message'] = 'Jobcard Model -> Jobcard Saving Process <br> ' . $e->getLine();

        //  return $process_result;
		// }

	}

    public function getJobcardInquireResult($query_part){

        $sql_query = " select		j.jobcard_no, j.jc_date, j.bank, j.serialno, j.model, j.lot_number, j.box_number,
                                    q.qt_no, q.qt_date, q.net_price,
                                    s.status_name as 'status', t.Released_Date
                       from		    jobcard j
                                        left outer join hardwarelive.hw_terminals t on t.jobcard_no = j.jobcard_no
                                        left outer join hardwarelive.quatation q on t.jobcard_no = q.jobcard_no
                                        left outer join hardwarelive.hw_status s on t.status = s.status_id
                       where		1=1 ". $query_part ."
                       order by	    j.jc_date desc, j.jobcard_no desc;  ";

		$result = DB::select($sql_query);

		return $result;
    }

    public function getJobcardCancellingResult($jobcard_number){

		$sql_query = " select		t.jobcard_no, t.tdate, t.bank, t.lot_number, t.box_number, t.podno, t.return_podno,
									td.serialno, td.model, td.printer_serial,
									j.tid, j.merchant,
									t.confirm, t.cancel, t.status
						from		terminal_in_process t
										inner join terminal_in_process_detail td on t.ticketno = td.ticketno
										inner join jobcard j on j.jobcard_no = t.jobcard_no
						where		t.jobcard_no = ? ; ";

		$result = DB::select($sql_query, [$jobcard_number]);

		return $result;
	}

    public function isCancelJobcardNumber($jobcard_number){

		$result = DB::table('jobcard')->where('jobcard_no', $jobcard_number)->where('cancel', 1)->exists();

		return $result;
	}


}
