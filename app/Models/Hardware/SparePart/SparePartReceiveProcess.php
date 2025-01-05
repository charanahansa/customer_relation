<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SparePartReceiveProcess extends Model {

    use HasFactory;

	public function save_spare_part_receive_note($data){

		$spr_id = 0;

		DB::beginTransaction();

		//try{

			$spare_part_receive_note = $data['spare_part_receive_note'];
			$hw_bin = $data['hw_bin'];

			unset($spare_part_receive_note['spr_id']);

			DB::table('spare_part_receive_note')->insert($spare_part_receive_note);
			$spr_id = DB::getPdo()->lastInsertId();

			$quantity_counter = $spare_part_receive_note['quantity'];
			for($int_i=1; $int_i <= $quantity_counter; $int_i++){

				$hw_bin['in_id'] = $spr_id;
				$hw_bin['in_ref'] = 'SPR';
				$hw_bin['spare_part_serial'] =  DB::table('spare_part')->where('spare_part_id', $spare_part_receive_note['spare_part_id'])->value('spare_part_serial');
				

				DB::table('hw_bin')->insert($hw_bin);

				DB::table('spare_part')->where('spare_part_id', $spare_part_receive_note['spare_part_id'])->increment('spare_part_serial');
			}

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

	public function get_spare_part_receive_note($spr_id){

		$result = DB::table('spare_part_receive_note')->where('spr_id', $spr_id)->get();

		return $result;
	}

	public function get_spare_part_receive_list($query_filter){

		$sql_query = " select		spr_id, spr_date, part_type, b.buyer_name, bn.bin_name, sp.spare_part_name, sprn.price, quantity
					   from			spare_part_receive_note sprn
										inner join buyer b on sprn.buyer_id = b.buyer_id
										inner join bin bn on sprn.bin_id = bn.bin_id
										inner join spare_part sp on sprn.spare_part_id = sp.spare_part_id
						where		1=1 ". $query_filter ."
					   order by		spr_id desc, spr_id ";
					   
		$result = DB::connection('mysql')->select($sql_query);

		return $result;
	}
	

}
