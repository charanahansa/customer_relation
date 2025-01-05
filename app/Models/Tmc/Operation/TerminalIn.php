<?php

namespace App\Models\Tmc\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalIn extends Model {

    use HasFactory;

	public function get_courier_inquire_detail($search){

		$result = 	DB::table('terminal_in_process')
                    	->where('podno', 'like', $search)
        				->orWhere('refno', 'like', $search)
						->orWhere('return_podno', 'like', $search)
						->orderBy('tdate', 'desc')
                    	->get();
		return $result;
	}

	
}
