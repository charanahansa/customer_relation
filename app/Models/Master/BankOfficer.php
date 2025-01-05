<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class BankOfficer extends Model {

    use HasFactory;

    public function getBankOfficer(){

		$result = DB::table('bank_officer')->where('active', 1)->get();

		return $result;
	}

}
