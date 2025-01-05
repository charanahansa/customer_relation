<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class RelevantDetail extends Model {

    use HasFactory;

    protected $table = 'relevent_detail';

    protected $primaryKey = 'rno';

    public $timestamps = true;

    protected $fillable = [
        'relevant_detail',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getRelevantDetailIdForDisplayAttribute() {

        return $this->attributes['rno'] ?? '#Auto#';
    }


    public function getRelevantDetail(){

        $result = DB::table('relevent_detail')->get();
		return $result;
    }

    public function getRelevantDetailDescription($relevent_detail){

        $result = DB::table('relevent_detail')->where('rno', $relevent_detail)->value('relevant_detail');
		return $result;
    }
}
