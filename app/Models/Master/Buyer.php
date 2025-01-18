<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model {

    use HasFactory;

    protected $table = 'buyer';

    protected $primaryKey = 'buyer_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'buyer_name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getBuyerIdForDisplayAttribute() {
        return $this->attributes['buyer_id'] ?? '#Auto#';
    }

}
