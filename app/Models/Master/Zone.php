<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Zone extends Model {

    use HasFactory;

    protected $table = 'zones';

    protected $primaryKey = 'zone_id';

    protected $fillable = [
        'zone_name',
        'resolution_time',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getZoneIdForDisplayAttribute(){

        return $this->attributes['zone_id'] ?? '#Auto#';
    }

}
