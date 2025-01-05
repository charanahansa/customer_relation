<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SparePartProcess extends Model {

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
						where		Active = 1
						order by	part_name  ";

		$result = DB::connection('mysql2')->select($sql_query);

		return $result;
	}

	public function get_spare_part_active_list(){

		$result = DB::table('spare_part')->where('active', 1)->orderby('spare_part_name', 'asc')->get();

		return $result;
	}

	public function get_spare_part_issue_type(){

		$result = DB::table('spare_part_issue_type')->get();

		return $result;
	}


	public function get_models(){

		$sql_query = " 	select		model
						from		model
						order by	model  ";

		$result = DB::select($sql_query);

		return $result;
	}

	public function get_spare_part_list(){

		$result = DB::connection('mysql2')->table('hw_parts')
										  ->orderBy('model')
										  ->orderBy('part_name')->get();

		return $result;
	}

	public function get_spare_part_list_filter_by_model($model){

		$result = DB::connection('mysql2')->table('hw_parts')
										  ->where('model', $model)
										  ->orderBy('model')
										  ->orderBy('part_name')->get();

		return $result;
	}

	public function get_spare_part_list_filter_by_spare_part($spare_part_id){

		$result = DB::connection('mysql2')->table('hw_parts')
										  ->where('Part_ID', $spare_part_id)
										  ->orderBy('model')
										  ->orderBy('part_name')->get();

		return $result;
	}

	public function get_spare_part_name($spare_part_id){

		$spare_part_name = DB::table('spare_part')->where('spare_part_id', $spare_part_id)->value('spare_part_name');

		return $spare_part_name;
	}


	public function spare_part_add_process($data){

		$spare_part_id = 0;

		//try{


			$exists_result  = DB::connection('mysql2')->table('hw_parts')->where('part_no', $data['part_no'])->exists();
			if($exists_result == TRUE){

				DB::connection('mysql2')->table('hw_parts')->where('part_no', $data['part_no'])->update($data);
				echo '<B> Updated <br> </b>';

			}else{

				DB::connection('mysql2')->table('hw_parts')->insert($data);
				echo '<B> Insert </b>';
			}

			$process_result['spare_part_id'] = $spare_part_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){


        //     $process_result['spare_part_id'] = $spare_part_id;
        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Spare Part Request Process -> Saving Process <br> ' . $e->getLine();

        //     return $process_result;
		// }
	}






}
