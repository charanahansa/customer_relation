<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class ActionTaken extends Model {

    use HasFactory;

    use HasFactory;

    protected $table = 'action_taken';

    protected $primaryKey = 'ano';

    public $timestamps = true;

    protected $fillable = [
        'action_taken',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getActionTakenIdForDisplayAttribute() {
        return $this->attributes['ano'] ?? '#Auto#';
    }

    public function getActionTaken(){

        $result = DB::table('action_taken')->get();
		return $result;
    }

    public function getActionTakenDescription($action_taken_id){

        $result = DB::table('action_taken')->where('ano', $action_taken_id)->value('action_taken');
		return $result;
    }
}
