<?php

namespace App\Models\Tmc\Delivery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Delivery extends Model {

    use HasFactory;

    public function saveDeliveryOrder($data){

        DB::beginTransaction();

		try{

			$delivery_order = $data['delivery_order'];
			$delivery_order_detail = $data['delivery_order_detail'];

            // Delivery Order
			if( $this->isExistsDeliveryOrderNumber($delivery_order['delivery_id']) ){

				$delivery_id = $delivery_order['delivery_id'];
				DB::table('delivery_order')->where('delivery_id', $delivery_id)->update($delivery_order);

			}else{

                DB::table('delivery_order')->insert($delivery_order);
				$delivery_id = DB::getPdo()->lastInsertId();
			}

            // Delivery Order Detail
            $icount = 1;
			DB::table('delivery_order_detail')->where('delivery_id', '=', $delivery_id)->delete();
			foreach($delivery_order_detail as $row){

				$delivery_detail['delivery_id'] = $delivery_id;
                $delivery_detail['ono'] = $icount;
				$delivery_detail['serial_number'] = $row['serial_number'];
                $delivery_detail['model'] = $row['model'];

				DB::table('delivery_order_detail')->insert($delivery_detail);

                $icount ++;
			}

            // Terminal Log
            $terminal_log_resultset = $data['terminal_log'];
			foreach($terminal_log_resultset as $row){

				if (DB::table('terminal_log')->where('serialno', $row['serialno'])->exists()) {

					DB::table('terminal_log')->where('serialno', $row['serialno'])->update($row);
				}else{

					DB::table('terminal_log')->insert($row);
				}
			}

			DB::commit();

			$process_result['delivery_id'] = $delivery_id;
            $process_result['process_status'] = TRUE;
            $process_result['front_end_message'] = "Saving Process is Completed successfully.";
            $process_result['back_end_message'] = "Commited.";

            return $process_result;

		}catch(\Exception $e){

			DB::rollback();

			$process_result['delivery_id'] = $delivery_order['delivery_id'];
            $process_result['process_status'] = FALSE;
            $process_result['front_end_message'] = $e->getMessage();
            $process_result['back_end_message'] = 'Deivery Order Model -> Deivery Order Saving Process <br> ' . $e->getLine();

            return $process_result;
		}
    }

    public function getDeliveryOrder($delivery_id){

		$result = DB::table('delivery_order')->where('delivery_id', $delivery_id)->get();
		return $result;
	}

	public function getDeliveryOrderDetail($delivery_id){

		$result = DB::table('delivery_order_detail')->where('delivery_id', $delivery_id)->get();
		return $result;
	}

    public function isExistsDeliveryOrderNumber($delivery_id){

		$result = DB::table('delivery_order')->where('delivery_id', $delivery_id)->exists();

        return $result;
	}

    public function getDeliveryBankList(){

        $sql_query = "  SELECT		BankCode
                        FROM		Deliverheader
                        GROUP BY	BankCode
                        ORDER BY	BankCode  ";

		$result = DB::connection('sqlsrv_two')->select($sql_query);

		return $result;
    }

    public function getDeliveryModelList(){

        $sql_query = "  SELECT		ItemCode
                        FROM		DeliverSerial
                        GROUP BY	ItemCode
                        ORDER BY	ItemCode  ";

		$result = DB::connection('sqlsrv_two')->select($sql_query);

		return $result;
    }

    public function getDeliveryList($query_part = ''){

        $sql_query = " SELECT		DH.DocNo, invoiceNo, salesorderNo, CONVERT(DATE, sysdate, 102) as 'Deliver_Date', BankCode, Cancel,
                                    DS.ItemCode, COUNT(DS.serial) as 'Terminal_Count'
                       FROM		    Deliverheader DH inner join DeliverSerial DS on DH.DocNo = DS.DocNo
                       WHERE		DH.Cancel = 0  ". $query_part ."
                       GROUP BY 	DH.DocNo, invoiceNo, salesorderNo, sysdate, BankCode, Cancel, DS.ItemCode
                       ORDER BY	    sysdate DESC ";

        // -- And  ( (LEN(LTRIM(RTRIM(DS.serial))) = 11) OR (LEN(LTRIM(RTRIM(DS.serial))) = 10) )

        $result = DB::connection('sqlsrv_two')->select($sql_query);

        return $result;

    }

    public function getDeliveryReport($delivery_number, $model){

        $sql_query = " SELECT		DH.DocNo, invoiceNo, salesorderNo, CONVERT(DATE, sysdate, 102) as 'Deliver_Date', BankCode, Cancel,
                                    DS.ItemCode, DS.serial
                       FROM		    Deliverheader DH inner join DeliverSerial DS on DH.DocNo = DS.DocNo
                       WHERE		DH.Cancel = 0 And  ( (LEN(LTRIM(RTRIM(DS.serial))) = 11) OR (LEN(LTRIM(RTRIM(DS.serial))) = 10) ) And
                                    DH.DocNo = ? And DS.ItemCode = ?
                       GROUP BY 	DH.DocNo, invoiceNo, salesorderNo, sysdate, BankCode, Cancel, DS.ItemCode, DS.serial
                       ORDER BY	    sysdate DESC, DS.serial ";

        $result = DB::connection('sqlsrv_two')->select($sql_query, [$delivery_number, $model]);

        return $result;
    }

    public function getEpicDeliveryDetail($serial_number){

        $sql_query = " select		d.delivery_id, d.delivery_date, d.bank, dd.model, dd.serial_number, d.sales_order_number, d.invoice_number
                       from		    delivery_order d  inner join delivery_order_detail dd on d.delivery_id = dd.delivery_id
                       where        dd.serial_number = ?
                       order by	    d.delivery_date desc ";

        $result = DB::select($sql_query, [$serial_number]);

        return $result;
    }

    public function getScienterDeliveryDetail($serial_number){

        $sql_query = " SELECT		DH.DocNo, invoiceNo, salesorderNo, CONVERT(DATE, sysdate, 102) as 'Deliver_Date', BankCode, Cancel,
                                    DS.ItemCode, DS.serial
                       FROM		    Deliverheader DH inner join DeliverSerial DS on DH.DocNo = DS.DocNo
                       WHERE		DH.Cancel = 0  And DS.serial = ?
                       ORDER BY	    sysdate DESC ";

        $result = DB::connection('sqlsrv_two')->select($sql_query, [$serial_number]);

        return $result;
    }


}
