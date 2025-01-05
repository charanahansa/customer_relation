<?php

namespace App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class HardwareStatus extends Model {

    use HasFactory;

    public function get_status_list(){

		$result = DB::table('hw_status')->get();

		return $result;
	}



}
