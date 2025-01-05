<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReinitializationReason extends Model {

    use HasFactory;

    protected $table = 'reinitilization_reason';

    protected $primaryKey = 'ono';

    public $timestamps = true;

    protected $fillable = [
        'reason',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getReasonIdForDisplayAttribute() {
        return $this->attributes['ono'] ?? '#Auto#';
    }
}

