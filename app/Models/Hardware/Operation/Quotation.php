<?php

namespace App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Quotation extends Model {

    use HasFactory;

    public function get_quotation($qt_no){

        $quotation_result = DB::connection('mysql2')->table('quatation')->where('qt_no', $qt_no)->get();

        return $quotation_result;
    }

    public function get_hardware_officers(){

        $hardware_officer_result = DB::table('users')->where('role', 5)->get();

        return $hardware_officer_result;
    }

    public function update_email_request($email_request_id){

        $update_array['email_sent'] = 1;
        $update_array['sent_on'] = date("Y/m/d H:i:s");

        DB::table('email_request')->where('email_request_id', $email_request_id)->update($update_array);
    }

    public function get_quotation_inquire_process($query_string){

		$sql_query = " select       qt_no, qt_date, jobcard_no, bank, model, serial_no, user_neg, quotation_approved, cancel, price
					   from         quatation
					   where        1=1  ". $query_string ."
					   order by     qt_date desc, qt_no desc " ;

        //echo $sql_query;

		$result = DB::connection('mysql2')->select($sql_query);

		return $result;
	}

    public function get_quotation_list(){

        $sql_query = " select		qt_no, qt_date, jobcard_no, serial_no, model, quotation_approved, net_price
				   	   from			quatation
				   	   where		cancel = 0 && bank = 'SEYB' && qt_date < CURRENT_DATE
					   order by		qt_date desc limit 100 ";

        $result = DB::connection('mysql2')->select($sql_query);

        return $result;
    }

	public function get_quotation_list_search($qt_no){

        $sql_query = " select		qt_no, qt_date, jobcard_no, serial_no, model, quotation_approved, net_price
				   	   from			quatation
				   	   where		cancel = 0 && bank = 'SEYB' && jobcard_no = ? && qt_date < CURRENT_DATE
					   order by		qt_date desc ";

        $result = DB::connection('mysql2')->select($sql_query, [$qt_no]);

        return $result;
    }

	public function get_quotation_information($qt_no){

		$sql_query = " select		*
				   	   from			quatation
				   	   where		qt_no = ? && qt_date < CURRENT_DATE ";

        $result = DB::connection('mysql2')->select($sql_query, [$qt_no]);

        return $result;
	}

	public function get_quotation_detail($qt_no){

		$sql_query = " select		*
				   	   from			quatation_detail
				   	   where		qt_no = ?  ";

        $result = DB::connection('mysql2')->select($sql_query, [$qt_no]);

        return $result;
	}

	public function get_client_address($qt_no){

		$sql_query = " select		qt_no, b.ADDRESS
					   from			quatation q	inner join bank b on q.bank = b.BankCode
					   where		bank = 'SEYB' && qt_no = ? && q.QT_DATE < CURRENT_DATE ";

		$result = DB::connection('mysql2')->select($sql_query, [$qt_no]);

		return $result;
	}

	public function get_quotation_amount($qt_no){

		$sql_query = " select		qt_no, sum(price) as 'qt_amount'
					   from			quatation_detail
					   where		qt_no = ?
                       group by     qt_no ";
		$result = DB::connection('mysql2')->select($sql_query, [$qt_no]);

		return $result;
	}


}
