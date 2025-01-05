<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class HardwareFault extends Model {

    use HasFactory;

    public function get_faults(){

		$result = DB::table('hw_fault')->orderBy('fault_name')->get();
        
		return $result;
	}



}
