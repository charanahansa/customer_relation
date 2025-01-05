<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class HWBin extends Model {

    use HasFactory;

	public function get_bin_spare_part_quantity_total($bin_id, $spare_part_id){
 	
		$spare_part_quantity = DB::table('hw_bin')->where('bin_id', $bin_id)
												  ->where('spare_part_id', $spare_part_id)
												  ->where('out_id', 0)
												  ->where('out_ref', '')
												  ->sum('quantity');	
		return $spare_part_quantity;
	}

	public function get_bin_spare_part_quantity_total_bin_wise($spare_part_id){
 	
		$sql_query = " select			h.bin_id, b.bin_name, spare_part_id, spare_part_name, sum(quantity) as 'Total'
					   from				hw_bin h inner join bin b on h.bin_id = b.bin_id
					   where			spare_part_id = ? && out_id = 0 && out_ref = ''
					   group by			bin_id, b.bin_name, spare_part_id, spare_part_name
					   order by			bin_id ";

		$result = DB::select($sql_query, [$spare_part_id]);

		return $result;
	}

	public function get_spare_part_bin_report($query_filter){

		$sql_query = " select		b.bin_name, spare_part_id, spare_part_name, sum(quantity) as 'total_quantity'
					   from			hw_bin h inner join bin b on h.bin_id = b.bin_id
					   where		out_id = 0 && out_ref = '' ". $query_filter ."
					   group by		b.bin_name, spare_part_id, spare_part_name
					   order by		spare_part_name ";

		$result = DB::select($sql_query);

		return $result;

	}




}
