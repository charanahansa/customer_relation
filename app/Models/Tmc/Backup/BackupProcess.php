<?php

namespace App\Models\Tmc\Backup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class BackupProcess extends Model{

    use HasFactory;

	public function get_sub_status(){

		$result = DB::table('backup_removal_sub_status')->get();

		return $result;
	}

	public function get_status(){

		$result = DB::table('status')->where('ref', 'breakdown')->where('codeid', '<>', 'awaiting')->get();

		return $result;
	}


	public function save_backup_receive_note($data){

		DB::beginTransaction();

		try{

			$backup_receive_note = $data['backup_receive_note'];
			$backup_receive_note_detail = $data['backup_receive_note_detail'];

			if($backup_receive_note['brn_id'] == '#Auto#'){

				unset($backup_receive_note['brn_id']);

				DB::table('backup_receive_note')->insert($backup_receive_note);
				$brn_id = DB::getPdo()->lastInsertId();

			}else{

				$brn_id = $backup_receive_note['brn_id'];
				DB::table('backup_receive_note')->where('brn_id', $brn_id)->update($backup_receive_note);
			}

			DB::table('backup_receive_note_detail')->where('brn_id', '=', $brn_id)->delete();
			foreach($backup_receive_note_detail as $row){

				$arr['brn_id'] = $brn_id;
				$arr['serial_no'] = $row['serial_no'];
				DB::table('backup_receive_note_detail')->insert($arr);
			}

			DB::commit();

			$process_result['brn_id'] = $brn_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['brn_id'] = $backup_receive_note['brn_id'];
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Process Model -> Backup Process Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	public function confirm_backup_receive_note($data, $brn_id){

		DB::beginTransaction();

		try{

			$confirm_row = $data['confirm_row'];

			DB::table('backup_receive_note')->where('brn_id', $brn_id)->update($confirm_row);

			$terminal_log_resultset = $data['terminal_log'];
			foreach($terminal_log_resultset as $row){

				if (DB::table('terminal_log')->where('serialno', $row['serialno'])->exists()) {

					DB::table('terminal_log')->where('serialno', $row['serialno'])->update($row);
				}else{

					DB::table('terminal_log')->insert($row);
				}
			}

			DB::commit();

			$process_result['brn_id'] = $brn_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Confirm Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['brn_id'] = $brn_id;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Process Model -> Backup Process Confirm Process <br> ' . $e->getLine();

            return $process_result;
		}

	}

	public function cancel_backup_receive_note($data, $brn_id){

		DB::beginTransaction();

		//try{

			$cancel_row = $data['cancel_row'];

			DB::table('backup_receive_note')->where('brn_id', $brn_id)->update($cancel_row);

			DB::commit();

			$process_result['brn_id'] = $brn_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Cancel Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['brn_id'] = $brn_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Backup Process Model -> Backup Process Cancel Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	public function get_backup_receive_note($brn_id){

		$result = DB::table('backup_receive_note')->where('brn_id', $brn_id)->get();
		return $result;
	}

	public function get_backup_receive_note_detail($brn_id){

		$result = DB::table('backup_receive_note_detail')->where('brn_id', $brn_id)->get();
		return $result;
	}

	public function is_cancel_backup_receive_note($brn_id){

		$result = DB::table('backup_receive_note')->where('brn_id', $brn_id)
												  ->where('cancel', 1)
												  ->exists();
		return $result;
	}

	public function is_confirm_backup_receive_note($brn_id){

		$result = DB::table('backup_receive_note')->where('brn_id', $brn_id)
												  ->where('confirm', 1)
												  ->exists();
		return $result;
	}

	public function get_backup_serial_records($serial_number){

		DB::beginTransaction();

		DB::select('DROP TABLE IF EXISTS `backup_info`;');

		$sql_command= " CREATE TABLE `backup_info` (
							`info_id` int(11) NOT NULL AUTO_INCREMENT,
							`workflow_id` int(11) NOT NULL,
							`ticketno` int(11) NOT NULL,
							`tdate` datetime NOT NULL,
							`bank` varchar(10) NOT NULL,
							`tid` varchar(10) NOT NULL,
							`model` varchar(20) NOT NULL,
							`merchant` varchar(150) NOT NULL,
							`e_in_serialno` varchar(20) DEFAULT NULL,
							`e_out_serialno` varchar(20) DEFAULT NULL,
							`saved_on` datetime NOT NULL,
							`contact_no` varchar(200) NOT NULL,
							`contact_person` varchar(200) NOT NULL,
							PRIMARY KEY (`info_id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";

		DB::select($sql_command);


		// Get Old Data
		//DB::connection('mysql2')->reconnect();
		//DB::connection('mysql2')->beginTransaction();
		$my_sql_old_resultset = DB::connection('mysql2')->select('call terminal_inquire(?)', array($serial_number));
		//DB::connection('mysql2')->commit();

		foreach($my_sql_old_resultset as $row){

			$arr['ticketno'] = $row->REF_NO;
            $arr['ref'] = $row->REF;
            $arr['subref'] = $row->IN_OUT;
            $arr['tdate'] = $row->REF_DATE;
            $arr['bank'] = $row->BANK;
            $arr['tid'] = $row->TID;
            $arr['merchant'] = $row->MERCHANT;
            $arr['serialno'] = $row->SERIAL_NO;
            $arr['courier'] = $row->COURIER;
            $arr['officer'] = $row->OFFICER;

			DB::table('tmp_terminal_inquire')->insert($arr);
		}

		// Get New Data
		$my_sql_resultset = DB::select(' call terminal_inquire(?)', array($serial_number));
		foreach($my_sql_resultset as $row){

			$arr['ticketno'] = $row->ticketno;
            $arr['ref'] = $row->ref;
            $arr['subref'] = $row->subref;
            $arr['tdate'] = $row->tdate;
            $arr['bank'] = $row->bank;
            $arr['tid'] = $row->tid;
            $arr['merchant'] = $row->merchant;
            $arr['serialno'] = $row->serialno;
            $arr['courier'] = $row->courier;
            $arr['officer'] = $row->officer_name;

			DB::table('tmp_terminal_inquire')->insert($arr);
		}

		DB::commit();


		//Final Part
		$resultset = DB::table('tmp_terminal_inquire')->orderBy('tdate', 'desc')->limit(1)->get();
		if(count($resultset) >= 1){

			foreach($resultset as $row){

				$return_array['ticketno'] = $row->ticketno;
				$return_array['ref'] = $row->ref;
				$return_array['subref'] = $row->subref;
				$return_array['tdate'] = $row->tdate;
				$return_array['bank'] = $row->bank;
				$return_array['tid'] = $row->tid;
				$return_array['serialno'] = $row->serialno;
				$return_array['merchant'] = $row->merchant;
				$return_array['officer'] = $row->officer;
				$return_array['courier'] = $row->courier;

				return $return_array;
			}

		}else{

			$return_array['ticketno'] = "";
            $return_array['ref'] = "";
            $return_array['subref'] = "";
            $return_array['tdate'] = "";
            $return_array['bank'] = "";
            $return_array['tid'] = "";
            $return_array['serialno'] = "";
            $return_array['merchant'] = "";
            $return_array['officer'] = "";
            $return_array['courier'] = "";

            return $return_array;
		}

	}

	public function get_courier_inquire_detail($search){

		$sql_query = " 	select 		b.brn_no as 'ticketno', t.tdate, 'sent' as 'sent', t.pod_no, b.bank, b.tid, t.serialno, t.model, t.collect_from_courier, t.merchant, t.contactno, t.remark
						from 		backup_remove_note_tmc_view t inner join backup_remove_note b ON t.ticketno = b.brn_no
						where       t.cancel = 0 && t.courier <> 'Not' && t.pod_no <> '' && t.pod_no like ?  ";

		$result = DB::select($sql_query, [$search]);

		return $result;
	}

	public function create_tmp_table(){

		//DB::beginTransaction();

		try{

			DB::select('DROP TABLE IF EXISTS `backup_info`;');

			$sql_command= " CREATE TABLE `backup_info` (
								`workflow_id` int(11) NOT NULL,
								`ticketno` int(11) NOT NULL,
								`tdate` datetime NOT NULL,
								`bank` varchar(10) NOT NULL,
								`tid` varchar(10) DEFAULT NULL,
								`model` varchar(20) NOT NULL,
								`merchant` varchar(150) NOT NULL,
								`contact_no` varchar(200) DEFAULT NULL,
								`contact_person` varchar(200) DEFAULT NULL,
								`officer` varchar(5) DEFAULT NULL,
								`courier` varchar(5) DEFAULT NULL,
								`e_in_serialno` varchar(20) DEFAULT NULL,
								`e_out_serialno` varchar(20) DEFAULT NULL,
								`saved_on` datetime NOT NULL,
								`saved_by` varchar(20) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;  ";

			DB::select($sql_command);


			//DB::commit();

		}catch(\Exception $e){


			//DB::rollBack();

		}

	}

	public function get_backup_info_detail(){

		$result = DB::table('backup_info')->orderBy('tdate', 'asc')->orderBy('ticketno', 'desc')->get();

		return $result;
	}

	public function isBackup($serial_number){

		$result = DB::table('backup_log')->where('serialno', $serial_number)->exists();

		return $result;
	}

	public function hasBackupIssueNote($serial_number){

		$result = DB::table('backup_issue_note')->where('backup_serialno', $serial_number)->where('removed', 0)->exists();

		return $result;
	}

	public function backupProcess($data){

        $process_information = $data['process_information'];
		$backup_in_process = $data['backup_in_process'];
		$backup_out_process = $data['backup_out_process'];

		echo 'Model Start <br>';

		//DB::beginTransaction();

		//try{

			// Backup In Process
			if( $this->isBackup($process_information->e_in_serialno) == TRUE ){

				if( $this->hasBackupIssueNote($process_information->e_in_serialno) == TRUE ){

					$update_backup_issue_note = $backup_in_process['update_backup_issue_note'];
					$genarate_backup_remove_note = $backup_in_process['genarate_backup_remove_note'];
					$genarate_backup_remove_note_fs = $backup_in_process['genarate_backup_remove_note_fs'];


					// Genarate Backup Remove Note
					unset($genarate_backup_remove_note['brn_no']);
					DB::table('backup_remove_note')->insert($genarate_backup_remove_note);
					$brn_id = DB::getPdo()->lastInsertId();
					echo 'backup_remove_note - Model <br>';

					// Update Backup Issue Note
					$update_backup_issue_note['brn_id'] = $brn_id;
					DB::table('backup_issue_note')->where('backup_serialno', $process_information->e_in_serialno)->update($update_backup_issue_note);
					echo 'Update backup_issue_note - Model <br>';

				}else{

					$genarate_backup_remove_note = $backup_in_process['genarate_backup_remove_note'];
					$genarate_backup_remove_note_fs = $backup_in_process['genarate_backup_remove_note_fs'];

					// Genarate Backup Remove Note
					unset($genarate_backup_remove_note['brn_no']);
					DB::table('backup_remove_note')->insert($genarate_backup_remove_note);
					$brn_id = DB::getPdo()->lastInsertId();
					echo 'backup_remove_note - Model <br>';

					// Genarate Backup Remove Note Fs View
					$genarate_backup_remove_note_fs['ticketno'] = $brn_id;
					DB::table('backup_remove_note_fs_view')->insert($genarate_backup_remove_note_fs);

				}

			}

			// Backup Out Process
			if( $this->isBackup($process_information->e_out_serialno) == TRUE ){

				if( $this->hasBackupIssueNote($process_information->e_out_serialno) == TRUE){

					$update_backup_issue_note = $backup_out_process['update_backup_issue_note'];
					$genarate_backup_remove_note = $backup_out_process['genarate_backup_remove_note'];
					$genarate_backup_issue_note = $backup_out_process['genarate_backup_issue_note'];
					$genarate_backup_remove_note_fs = $backup_out_process['genarate_backup_remove_note_fs'];

					// Genarate Backup Remove Note
					unset($genarate_backup_remove_note['brn_no']);
					DB::table('backup_remove_note')->insert($genarate_backup_remove_note);
					$brn_id = DB::getPdo()->lastInsertId();
					echo 'backup_remove_note - Model <br>';

					// Genarate Backup Remove Note Fs View
					$genarate_backup_remove_note_fs['ticketno'] = $brn_id;
					DB::table('backup_remove_note_fs_view')->insert($genarate_backup_remove_note_fs);

					// Update Backup Issue Note
					$update_backup_issue_note['brn_id'] = $brn_id;
					DB::table('backup_issue_note')->where('backup_serialno', $process_information->e_out_serialno)->update($update_backup_issue_note);
					echo 'Update backup_issue_note - Model <br>';

					// Genarate Backup Issue Note
					$genarate_backup_issue_note = $backup_out_process['genarate_backup_issue_note'];
					unset($genarate_backup_issue_note['bin_no']);
					$genarate_backup_issue_note['brn_id'] = $brn_id;
					DB::table('backup_issue_note')->insert($genarate_backup_issue_note);
					$bin_id = DB::getPdo()->lastInsertId();
					echo 'Genarate backup_issue_note - Model <br>';

					// Update Backup Remove Note
					$brn_update['bin_no'] = $bin_id;
					DB::table('backup_remove_note')->where('brn_no', $brn_id)->update($brn_update);
					echo 'Update backup_remove_note - Model <br>';

				}else{

					// Genarate Backup Issue Note
					$genarate_backup_issue_note = $backup_out_process['genarate_backup_issue_note'];
					unset($genarate_backup_issue_note['bin_no']);
					DB::table('backup_issue_note')->insert($genarate_backup_issue_note);
					echo 'Genarate backup_issue_note - Model** <br>';

				}

			}


			//DB::commit();

            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

			echo '<hr>';
			echo '<pre>';
            print_r($process_result);
            echo '</pre>';

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Saving Process -> Spare Part Saving Process <br> ' . $e->getLine();

		// 	echo '<hr>';
		// 	echo '<pre>';
        //     print_r($process_result);
        //     echo '</pre>';

        //     return $process_result;
		// }

	}

	public function save_backup_remove_note($data){

		DB::beginTransaction();

		$brn_no = 0;
		$ol_no = 0;

		//try{

			$backup_remove_note = $data['backup_remove_note'];
			$officer_allocate_note = $data['officer_allocate_note'];

			// Backup Remove Note
			if ( DB::table('backup_remove_note')->where('brn_no', $backup_remove_note['brn_no'])->exists() ) {

				DB::table('backup_remove_note')->where('brn_no', $backup_remove_note['brn_no'])->update($backup_remove_note);
				$brn_no = $backup_remove_note['brn_no'];

			}else{

				unset($backup_remove_note['brn_no']);
				DB::table('backup_remove_note')->insert($backup_remove_note);
				$brn_no = DB::getPdo()->lastInsertId();
			}

			$ol_no = DB::table('lastno')->increment('officer_allocate');
			$officer_allocate_note['ticketno'] = $brn_no;
			$officer_allocate_note['allocate_no'] = $ol_no;

			$officer_allocate_result = $this->exists_offcier_allocate_note($brn_no);

			// Officer Allocation Note
			if( $officer_allocate_result ){

				DB::table('officer_allocate_note')->where('allocate_no', $backup_remove_note['brn_no'])
												  ->where('ref', 'backup_removal')
												  ->update($officer_allocate_note);
			}else{

				DB::table('officer_allocate_note')->insert($officer_allocate_note);
			}

			// Backup Remove History
			for ($x = 1; $x <= count($data['backup_remove_note_history']); $x++) {

				$data['backup_remove_note_history'][$x]['ticketno'] = $brn_no;
                DB::table('backup_remove_note_history')->insert($data['backup_remove_note_history'][$x]);
            }

			DB::commit();

			$process_result['brn_no'] = $brn_no;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['brn_no'] = $brn_no;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Backup Process Model -> Backup Process Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

	}

	public function get_backup_remove_note($brn_no){

		$result = DB::table('backup_remove_note')->where('brn_no', $brn_no)->get();

		return $result;
	}

	public function exists_backup_remove_note($brn_no){

		$result = DB::table('backup_remove_note')->where('brn_no', $brn_no)->exists();

		return $result;
	}

	public function get_backup_remove_note_history($brn_no){

		$result = DB::table('backup_remove_note_history')->where('ticketno', $brn_no)->orderBy('tdatetime', 'desc')->get();

		return $result;
	}

	public function is_cancel_backup_remove_note($brn_no){

		$result = DB::table('backup_remove_note')->where('brn_no', $brn_no)
												 ->where('cancel', 1)
												 ->exists();
		return $result;
	}

	public function cancel_backup_remove_note($data){

		DB::beginTransaction();

		$brn_no = 0;

		try{

			DB::table('backup_remove_note')->where('brn_no', $data['brn_no'])->update($data);

			DB::commit();

			$process_result['brn_no'] = $brn_no;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Cancel Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['brn_no'] = $brn_no;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Backup Process Model -> Backup Process Cancel Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

	public function exists_offcier_allocate_note($brn_no){

		$result = DB::table('officer_allocate_note')->where('ticketno', $brn_no)
													->where('ref', 'backup_removal')
													->where('cancel', 0)
													->exists();
		return $result;
	}

	public function get_officer_allocate_number($brn_no){

		$allocate_number = DB::table('officer_allocate_note')->where('ticketno', $brn_no)
															 ->where('ref', 'backup_removal')
															 ->where('cancel', 0)
															 ->value('allocate_no');
		return $allocate_number;
	}

	public function getBackupInquireResult($query_part){

		$sql_query = " select		brn.brn_no, brn.brn_date, brn.bin_no, brn.bank, brn.tid, brn.merchant,
                                    brn.backup_serialno, brn.backup_model, brn.replaced_serialno, brn.replaced_model, brn.received_serialno, brn.received_model,
									brn.contact_number, brn.contact_person, brn.remark, o.officer_name, co.officer_name as 'courier_name', ss.status as 'sub_status', brn.status, brn.done_date_time,
									brn.cancel, brn.cancel_reason, brn.cancel_on, brn.cancel_by,
									brn.saved_by, brn.saved_on, brn.edit_by, brn.edit_on
					   from			backup_remove_note brn
										left outer join officers o on brn.officer = o.id
										left outer join courier co on brn.courier = co.id
										inner join backup_removal_sub_status ss on brn.sub_status = ss.id
						where		brn_no > 180 && brn.cancel = 0 ". $query_part ."
						order by 	brn.brn_date desc, brn.brn_no desc  ";

		$result = DB::select($sql_query);

		return $result;
	}

	public function getBackupRemovalFieldService($brn_no){

		$result = DB::table('backup_remove_note_fs_view')->where('ticketno', $brn_no)->get()														;

		return $result;

	}

    public function getLastTerminalIdRecord($tid){

        $result = DB::select('call last_tid_record( ? )', [$tid]);

        return $result;
    }

    public function getBackupRemovalSubStatusDescription($sub_status_id){

        $sub_status = DB::table('backup_removal_sub_status')->where('id', $sub_status_id)->value('status');

        return $sub_status;
    }

    public function getBackupLog(){

        $result = DB::table('backup_log')->get();

        return $result;
    }



}
