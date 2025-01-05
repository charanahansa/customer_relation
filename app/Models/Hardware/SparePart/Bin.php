<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Bin extends Model {

    use HasFactory;

	public function get_bin_active_list(){

		$result = DB::table('bin')->where('active', 1)->get();

		return $result;
	}

	public function get_bin_name($bin_id){

		$bin_name = DB::table('bin')->where('bin_id', $bin_id)->value('bin_name');

		return $bin_name;
	}


}
