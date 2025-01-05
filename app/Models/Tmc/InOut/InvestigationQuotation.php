<?php

namespace App\Models\Tmc\InOut;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class InvestigationQuotation extends Model {

    use HasFactory;

    public function getQuotation($quotation_number){

        $quotation_result = DB::table('investigation_quotation')->where('qt_no', $quotation_number)->get();

        return $quotation_result;
    }

}
