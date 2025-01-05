<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Fault extends Model {

    use HasFactory;

    protected $table = 'error';

    protected $primaryKey = 'eno';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'error',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getErrorIdForDisplayAttribute() {
        return $this->attributes['eno'] ?? '#Auto#';
    }

    public function getFaults(){

        $result = DB::table('error')->get();
		return $result;
    }

    public function getFaultDescription($fault){

        $result = DB::table('error')->where('eno', $fault)->value('error');
		return $result;
    }
}
