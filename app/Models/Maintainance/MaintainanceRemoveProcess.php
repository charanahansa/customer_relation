<?php

namespace App\Models\Maintainance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class MaintainanceRemoveProcess extends Model {

    use HasFactory;

    public function save_maintainance_remove_notes($data){

        DB::beginTransaction();

		try{

			$maintainance_remove_notes = $data['maintainance_remove_note'];
            $terminal_log = $data['terminal_log'];

			foreach($maintainance_remove_notes as $row){

				DB::table('maintainance_remove_note')->insert($row);
                $man_id = DB::getPdo()->lastInsertId();
			}
            
            foreach($terminal_log as $row){

                if( DB::table('terminal_log')->where('serialno', $row['serialno'])->exists() ){

                    DB::table('terminal_log')->where('serialno', $row['serialno'])->update($row);
                    
                }else{

                    DB::table('terminal_log')->insert($row);
                }
            }

			DB::commit();

            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Maintainance Add Process Model -> Maintainance Add Process Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
    }

    public function get_maintainance_remove_inquire_process($query_string){

        $sql_query = " select		mr_id, mr_date, bank, mc.mc_name, referance_number, mrn.remark
                       from		    maintainance_remove_note mrn 
                                        inner join maintainance_category mc on mrn.maintainance_category_id = mc.mc_id
                       where        1=1 ". $query_string ."; " ;

		$result = DB::select($sql_query);

		return $result;

    }



}
