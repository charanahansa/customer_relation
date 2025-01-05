<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TerminalModel extends Model {

    use HasFactory;

    protected $table = 'model';

    protected $primaryKey = 'model_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'model',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getModelIdForDisplayAttribute() {
        return $this->attributes['model_id'] ?? '#Auto#';
    }

    public function get_models(){

		$result = DB::table('model')->orderby('model', 'asc')->get();
		return $result;
	}

}
