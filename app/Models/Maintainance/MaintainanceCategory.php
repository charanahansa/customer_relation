<?php

namespace App\Models\Maintainance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class MaintainanceCategory extends Model {

    use HasFactory;

    public function get_maintainance_category(){

        $result = DB::table('maintainance_category')->where('active', 1)->orderby('mc_id', 'asc')->get();

		return $result;
    }

}
