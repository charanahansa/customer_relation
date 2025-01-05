<?php

namespace App\Models\Tmc\Courier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class CourierProcess extends Model {

    use HasFactory;

	public function courier_inquire_process($data){

		try{

			DB::select('DROP TABLE IF EXISTS `tmp_courier_inquire`;');

			$sql_command= " CREATE TEMPORARY TABLE `tmp_courier_inquire` (
								`ticketno` varchar(20) NOT NULL,
								`tdate` date NOT NULL,
								`ref` varchar(10) NOT NULL,
								`pod_no` varchar(10) NOT NULL,
								`bank` varchar(10) NOT NULL,
								`tid` varchar(10) DEFAULT NULL,
								`serial_number` varchar(12) DEFAULT NULL,
								`collect_from_courier` varchar(5) DEFAULT NULL,
								`merchant` varchar(130) DEFAULT NULL,
								`contact_number` varchar(100) DEFAULT NULL,
								`remark` varchar(100) DEFAULT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=latin1; ";

			DB::select($sql_command);

			foreach($data as $table){

				foreach($table as $row){

					$arr['ticketno'] = $row->ticketno;
					$arr['tdate'] = $row->tdate;
					$arr['ref'] = $row->sent;
					$arr['pod_no'] = $row->pod_no;
					$arr['bank'] = $row->bank;
					$arr['tid'] = $row->tid;
					$arr['serial_number'] = $row->serialno;
					$arr['collect_from_courier'] = $row->collect_from_courier;
					$arr['merchant'] = $row->merchant;
					$arr['contact_number'] = $row->contactno;
					$arr['remark'] = $row->remark;

					DB::table('tmp_courier_inquire')->insert($arr);
				}
			}

			//Final Part
			$resultset = DB::table('tmp_courier_inquire')->orderBy('tdate', 'desc')->get();

			$process_result['dataset'] = $resultset;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Inquire Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			$process_result['dataset'] = NULL;
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Courier Model -> Courier Process <br> ' . $e->getLine();

            return $process_result;
		}
	}

    public function getActiveCourierProviderList(){

		$result = DB::table('courier')->where('active', 1)->get();

		return $result;
	}

}
