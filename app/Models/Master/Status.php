<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Status extends Model {

    use HasFactory;

    public function getBreakdownStatus(){

        $result = DB::table('status')->where('ref', 'breakdown')->whereNotIn('codeid', ['awaiting'])->get();

		return $result;
    }

    public function getBackupRemoveStatus(){

        $result = DB::table('status')->where('ref', 'breakdown')->whereNotIn('codeid', ['awaiting'])->get();

		return $result;
    }
}
