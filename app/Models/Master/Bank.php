<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Bank extends Model {

    use HasFactory;

    protected $table = 'bank';

    protected $primaryKey = 'bank_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'bank',
        'bankname',
        'tnms_maintain',
        'repair_maintain',
        'full_maintain',
        'printname',
        'sc_code',
        'address',
        'lotno',
        'qasp',
        'qt_no',
        'bd_eml',
        'bd_report',
        'warrant_month'
    ];

    protected $casts = [
        'tnms_maintain' => 'boolean',
        'repair_maintain' => 'boolean',
        'full_maintain' => 'boolean',
        'qasp' => 'boolean',
        'lotno' => 'integer',
        'qt_no' => 'integer',
        'bd_eml' => 'integer',
        'bd_report' => 'integer',
        'warrant_month' => 'integer',
    ];

	public function get_bank(){

		$result = DB::table('bank')->where('repair_maintain', 1)->orderby('bank', 'asc')->get();
		return $result;
	}

	public function get_workflow(){

		$result = DB::table('workflow')->orderby('workflow_id', 'asc')->get();
		return $result;
	}

    public function getBankName($bank){

		$bank_name = DB::table('bank')->where('bank', $bank)->value('bankname');
		return $bank_name;
	}
}
