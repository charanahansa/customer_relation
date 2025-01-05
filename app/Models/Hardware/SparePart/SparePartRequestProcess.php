<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SparePartRequestProcess extends Model {

    use HasFactory;

	public function get_field_officers(){

		$sql_query = " 	select		requested_by
						from		spare_part_request
						group by	requested_by
						order by	requested_by  ";

		$result = DB::connection('mysql2')->select($sql_query);

		return $result;
	}

	public function get_spare_part(){

		$sql_query = " 	select		part_id, part_name
						from		hw_parts
						where		active = 1
						order by	part_name  ";

		$result = DB::connection('mysql2')->select($sql_query);

		return $result;
	}

	public function get_spare_part_request_list($query_filter, $issue_type){

		/*

		if($issue_type == 4){

			$sql_query = " 	select		request_id, ticket_no, serial_number, requested_by, fault, requested_date, hp.Part_Name
							from		spare_part_request pr inner join hw_parts hp on pr.requested_part_id = hp.part_id
							where		part_issued = 0  && part_rejected = 0 ". $query_filter ."
							order by	requested_date desc, request_id  ";

			$result = DB::connection('mysql2')->select($sql_query);

		}else{

			$sql_query = " 	select		spr_id, spr_date, part_type, spit.sp_issue_type_name,
										case when sprn.issue_type = 1 then b.bin_name
											when sprn.issue_type = 2 then bank
											when sprn.issue_type = 3 then u.name
										end as 'referance', spare_part_name, quantity, remark
							from		spare_part_request_note sprn
											left outer join spare_part_issue_type spit on sprn.issue_type = spit.sp_issue_type_id
											left outer join bin b on sprn.to_bin_id = b.bin_id
											left outer join users u on sprn.user_id = u.id
							where		sprn.spi_id = 0 && sprn.reject = 0 && sprn.cancel = 0 && sprn.issue_type in (1, 2, 3, 4) ". $query_filter ."
							order by	spr_id desc;  ";

			//echo $sql_query;

			$result = DB::connection('mysql')->select($sql_query);

		}

		*/


		$sql_query = " 	select		spr_id, spr_date, part_type, spit.sp_issue_type_name, u.name as 'officer_name', sprn.bank,
									case when sprn.issue_type = 1 then b.bin_name
										 when sprn.issue_type = 2 then bank
										 when sprn.issue_type = 3 then u.name
										 when sprn.issue_type = 4 then w.workflow_name
									end as 'referance', spare_part_name, quantity, remark
						from		spare_part_request_note sprn
										left outer join spare_part_issue_type spit on sprn.issue_type = spit.sp_issue_type_id
										left outer join bin b on sprn.to_bin_id = b.bin_id
										left outer join users u on sprn.user_id = u.id
										left outer join workflow w on sprn.workflow_id = w.workflow_id
						where		sprn.spi_id = 0 && sprn.reject = 0 && sprn.cancel = 0 ". $query_filter ."
						order by	spr_id desc;  ";

		//echo $sql_query;

		$result = DB::connection('mysql')->select($sql_query);

		return $result;
	}

	public function get_spare_part_bin($request_id){

		$sql_query = " 	select		pr.request_id, pr.requested_part_id, hp.Part_ID, hp.No, hp.Qty, concat(hp.Stork_Location, ' - ', IFNULL(hp.Description, '@@')) as 'description'
						from		spare_part_request pr inner join hw_parts_details hp on pr.requested_part_id = hp.Part_ID
						where		request_id = ? && hp.Stork_Location ='MAIN' && hp.Qty > 0
						order by	hp.No  ";

		$result = DB::connection('mysql2')->select($sql_query, [$request_id]);

		return $result;
	}

	public function get_spare_part_request_detail($request_id){

		$result =  DB::connection('mysql2')->table('spare_part_request')->where('request_id', $request_id)->get();

        return $result;
	}

	public function get_breakdown_ticket_detail($ticket_no){

		$result = DB::table('breakdown')->where('ticketno', $ticket_no)->get();

        return $result;
	}

	public function spare_part_request_updating_process($data){

		$spare_part_issue_id = '#Auto#';

		//try{

			$process = $data['process'];
			$part_exchange_table = $data['part_exchange'];
			$part_request_table = $data['part_request'];

			$request_id = $data['part_exchange']['techrequest_no'];
			$spare_part_bin_no = $data['part_exchange']['issuedfrom'];

			if($process == 'Issue'){

				if( $part_exchange_table['PeNo'] == '#Auto#' ){

					unset($part_exchange_table['PeNo']);

					DB::connection('mysql2')->table('part_exchange')->insert($part_exchange_table);
					$spare_part_issue_id = DB::connection('mysql2')->getPdo()->lastInsertId();

				}else{

					$spare_part_issue_id = $part_exchange_table['PeNo'];
					DB::connection('mysql2')->table('part_exchange')->where('PeNo', '=', $spare_part_issue_id)->update($part_exchange_table);
				}

				// Reduce Spare Part
				DB::connection('mysql2')->table('hw_parts_details')->where('No', $spare_part_bin_no)->decrement('Qty', 1);
			}

			DB::connection('mysql2')->table('spare_part_request')
		                            ->where('request_id', $request_id)
		                            ->update($part_request_table);

			DB::commit();

			$process_result['issue_id'] = $spare_part_issue_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

        //     $process_result['issue_id'] = $spare_part_issue_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Request Process -> Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

	}

	public function save_spare_part_request_note($data){

		$spr_id = 0;

		DB::beginTransaction();

		//try{

			$spare_part_request_note = $data['spare_part_request_note'];

			unset($spare_part_request_note['spr_id']);

			DB::table('spare_part_request_note')->insert($spare_part_request_note);
			$spr_id = DB::getPdo()->lastInsertId();

			DB::commit();

			$process_result['spr_id'] = $spr_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";


            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['spr_id'] = $spr_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Saving Process -> Spare Part Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

	}

	public function get_spare_part_request_note($spr_id){

		$result = DB::table('spare_part_request_note')->where('spr_id', $spr_id)->get();

		return $result;
	}

    public function is_settle_spare_part_request_number($spr_id){

        $result = DB::table('spare_part_request_note')->where('spr_id', $spr_id)
													  ->where('spi_id', 0)->exists();

        return $result;
    }

	public function is_reject_spare_part_request_number($spr_id){

        $result = DB::table('spare_part_request_note')->where('spr_id', $spr_id)
													  ->where('reject', 0)->exists();

        return $result;
    }

	public function update_spare_part_reject_request_numbers($sqlarr){

		DB::beginTransaction();

		try{

			$spr_id = $sqlarr['spr_id'];

			DB::table('spare_part_request_note')->where('spr_id', $spr_id)->update($sqlarr);

			DB::commit();

			$process_result['spr_id'] = $spr_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";


            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['spr_id'] = $spr_id;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Spare Part Request Process -> Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

    public function getSparepartPendingReport($query_filter){

		$sql_query = " 	select		reference_number, t.serialno, t.model, t.bank
						from		spare_part_request_note sprn inner join hardwarelive.hw_terminals t on sprn.reference_number = t.jobcard_no
						where		workflow_id = 12 && sprn.cancel = 0 ". $query_filter ."
						group by	reference_number, t.serialno, t.model, t.bank
						order by	reference_number  ";

		$result = DB::connection('mysql')->select($sql_query);

		return $result;
	}

	public function getSparepartRequestForJobcard($jobcard_number){

		$result = DB::table('spare_part_request_note')->where('workflow_id', 12)
													  ->where('cancel', 0)
													  ->where('reference_number', $jobcard_number)
													  ->get();

        return $result;
	}




}
