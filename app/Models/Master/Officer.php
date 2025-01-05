<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Officer extends Model {

    protected $table = 'officers';

    protected $primaryKey = 'ID';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ID',
        'officer_name',
        'head_of_dept',
        'team_lead',
        'action_station',
        'job_roles',
        'courier',
        'email',
        'phone',
        'courier_print',
        'quotation_email',
        'active',
        'address',
        'vc_name',
        'job_roll_change_ability',
        'jobrolls',
    ];

    protected $casts = [
        'head_of_dept' => 'boolean',
        'team_lead' => 'boolean',
        'courier' => 'boolean',
        'courier_print' => 'boolean',
        'quotation_email' => 'boolean',
        'active' => 'boolean',
    ];
}
