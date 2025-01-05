<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class CourierProvider extends Model {

    use HasFactory;

    public function getActiveCourierProviders(){

		$result = DB::table('courier')->where('active', 1)->get();

		return $result;
	}

    public function getCourierName($courier_provider){

		$courier_name = DB::table('courier')->where('id', $courier_provider)->value('officer_name');

		return $courier_name;
	}


}
