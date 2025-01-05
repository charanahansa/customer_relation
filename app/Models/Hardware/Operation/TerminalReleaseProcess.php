<?php

namespace  App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalReleaseProcess extends Model {

    use HasFactory;

    public function saveJobcards($jobcards_information){

        DB::beginTransaction();

		//try{

            foreach($jobcards_information as $row){

                if( DB::table('tmp_release_jobcard')->where('jobcard_no', $row['jobcard_no'])->exists() ){

                    DB::table('tmp_release_jobcard')->where('jobcard_no', $row['jobcard_no'])->update($row);

                }else{

                    DB::table('tmp_release_jobcard')->insert($row);
                }
            }

			DB::commit();

            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Information reading process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		//}catch(\Exception $e){

		// 	DB::rollback();


        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Information reading process <br> ' . $e->getLine();

        //     return $process_result;
		//}
    }

    public function removeJobcards($jobcard_no){

		DB::beginTransaction();

		try{

			DB::table('tmp_release_jobcard')->where('jobcard_no', $jobcard_no)->delete();

			DB::commit();

			$process_result['jobcard_no'] = $jobcard_no;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['jobcard_no'] = $jobcard_no;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Terminal Out Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

    public function releaseJobcards($data){

        $jobcard_list = $data['list']['release_jobcard'];
        $jobcard_list_olddb = $data['list']['release_jobcards_for_old_table'];
        $tmc_bin = $data['list']['tmc_bin'];
        $update_jobcard = $data['update_jobcard'];

        DB::beginTransaction();

		//try{

            $icount = 1;
			foreach($jobcard_list as $row){

                DB::table('release_jobcard')->insert($row);
                $released_id = DB::getPdo()->lastInsertId();

                DB::table('tmp_release_jobcard')->where('jobcard_no', $row['jobcard_no'])->delete();

                DB::connection('mysql2')->table('hw_terminals')->where('jobcard_no', $row['jobcard_no'])->update($update_jobcard);

                // Tmc Bin
                $tmc_bin[$icount]['in_workflow_number'] = $row['jobcard_no'];
                if ( DB::table('tmc_bin')->where('serial_number', $tmc_bin[$icount]['serial_number'])->exists() ) {

					DB::table('tmc_bin')->where('serial_number', $tmc_bin[$icount]['serial_number'])->update($tmc_bin[$icount]);
				}else{

					DB::table('tmc_bin')->insert($tmc_bin[$icount]);
				}

                $icount++;
            }

            DB::connection('mysql2')->table('hw_release_terminal')->insert($jobcard_list_olddb);

			DB::commit();

            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		// }catch(\Exception $e){

		// 	DB::rollback();

        //     $process_result['process_status'] = FALSE;
        //     $process_result['front_end_message'] = $e->getMessage();
        //     $process_result['back_end_message'] = 'Terminal Out Process <br> ' . $e->getLine();

        //     return $process_result;
		// }

    }

    public function get_report_resultset($from_date, $to_date, $total_filter){

        $sql_query = " select		jobcard_no, tmc_jc_date, bank, serialno, model, R_Date, u.name, R_To, r.remark
                       from		    hw_terminals t
                                        inner join hw_release_terminal r on t.jobcard_no = r.Jobcardno
                                        inner join users u on r.R_By = u.id
                       where		tmc_jc_date between ? and ? " . $total_filter . "
                       order by     tmc_jc_date desc, jobcard_no desc; ";

		$result =  DB::connection('mysql2')->select($sql_query,[$from_date, $to_date]);

		return $result;
    }

    public function isReleaseJobcardNumber($jobcard_number){

        $result =  DB::connection('mysql2')->table('hw_release_terminal')->where('Jobcardno', $jobcard_number)->exists();

		return $result;
    }

    public function getTemporaryJobcardReleaseTable($user_id){

        $result =  DB::table('tmp_release_jobcard')->where('user_id', $user_id)->get();

		return $result;
    }

}
