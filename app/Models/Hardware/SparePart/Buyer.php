<?php

namespace App\Models\Hardware\SparePart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Buyer extends Model {

    use HasFactory;

	public function get_buyers_active_list(){

		$result = DB::table('buyer')->where('active', 1)->get();

		return $result;
	}

	public function get_buyer_name($buyer_id){

		$buyer_name = DB::table('buyer')->where('buyer_id', $buyer_id)->value('buyer_name');

		return $buyer_name;
	}


}
