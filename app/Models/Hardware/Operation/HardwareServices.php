<?php

namespace App\Models\Hardware\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class HardwareServices extends Model {

    use HasFactory;

    public function get_service_list(){

		$result = DB::table('hw_services')->where('active', 1)->get();

		return $result;
	}


}
