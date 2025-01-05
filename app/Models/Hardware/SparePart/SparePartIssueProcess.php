<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SparePartIssueProcess extends Model {

    use HasFactory;

	public function save_spare_part_issue_note($data, $request){

		$spi_id = 0;

		DB::beginTransaction();

		//try{

			$spare_part_issue_note = $data['spare_part_issue_note'];
			$spare_part_request_note = $data['spare_part_request_note'];
			$hw_bin = $data['hw_bin'];

			unset($spare_part_issue_note['spi_id']);

			DB::table('spare_part_issue_note')->insert($spare_part_issue_note);
			$spi_id = DB::getPdo()->lastInsertId();

			// Reduce Spare Part Quantity from the Relevant Bin

			$hw_bin_update['out_id'] = $spi_id;
			$hw_bin_update['out_ref'] = 'SPI';

			DB::table('hw_bin')->where('bin_id', $spare_part_issue_note['from_bin_id'])
								->where('out_id', 0)
								->where('out_ref', '')
								->where('spare_part_id', $spare_part_issue_note['spare_part_id'])
								->orderBy('log_id', 'asc')
								->limit($spare_part_issue_note['quantity'])
								->update($hw_bin_update);

            if(isset($request->spare_part_request_id)){

				$spare_part_request_note['spi_id'] = $spi_id;
                DB::table('spare_part_request_note')->where('spr_id', $request->spare_part_request_id)->update($spare_part_request_note);
            }

			if($request->issue_type == 1){

				// Add Spare Part Quantity to the Relevant Bin
				$spi_resultset = DB::table('hw_bin')->where('bin_id', $spare_part_issue_note['from_bin_id'])
													->where('out_id', $spi_id)
													->where('spare_part_id', $spare_part_issue_note['spare_part_id'])
													->orderBy('log_id', 'asc')
													->get();
				foreach($spi_resultset as $row){

					$hw_bin['in_id'] = $spi_id;
					$hw_bin['in_ref'] = 'SPI';
					$hw_bin['spare_part_serial'] = $row->spare_part_serial;

					DB::table('hw_bin')->insert($hw_bin);
				}
			}


			DB::commit();

			$process_result['spi_id'] = $spi_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";


            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

		// 	$process_result['spi_id'] = $spi_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Saving Process -> Spare Part Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}

	public function get_spare_part_issue_note($spi_id){

		$result = DB::table('spare_part_issue_note')->where('spi_id', $spi_id)->get();

		return $result;
	}

	public function get_spare_part_issue_list($query_filter){

		$sql_query = " select		spi_id, spi_date, part_type, spin.issue_type, spit.sp_issue_type_name, b.bin_name,
									(case
										when issue_type = 1 then
											b2.bin_name
										else
											(case when issue_type = 2 then
												bank
											else
												(case when issue_type = 3 then
													u.name
												else
													''
												END)
											END)
										END) as 'TO',
									spare_part_id, spare_part_name, quantity
						from		spare_part_issue_note spin
									left outer join bin b on spin.from_bin_id = b.bin_id
									left outer join bin b2 on spin.to_bin_id = b2.bin_id
									left outer join users u on spin.user_id = u.id
									left outer join spare_part_issue_type spit on spin.issue_type = spit.sp_issue_type_id
						where		1=1 ". $query_filter ."
						order by	spi_id ";

		//echo $sql_query;
		// echo '<br>';

		$result = DB::connection('mysql')->select($sql_query);

		return $result;
	}

    public function getBasicSparepartUsageReport($query_filter){

		$sql_query = " select		spin.bank, t.model, sp.spare_part_no, sp.spare_part_name, part_type, count(quantity) as 'quantity'
					   from			spare_part_issue_note spin
										inner join hardwarelive.hw_terminals t on spin.workflow_referance_no = t.jobcard_no
										inner join spare_part sp on spin.spare_part_id = sp.spare_part_id
					   where		workflow_id = 12 ". $query_filter ."
					   group by		spin.bank, t.model, sp.spare_part_no, sp.spare_part_name, part_type
					   order by		spin.bank, t.model, sp.spare_part_name ";

		$result = DB::select($sql_query);

		return $result;
	}


}
