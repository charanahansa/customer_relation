<?php

namespace App\Models\Vericentre;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Vericentre extends Model {

    use HasFactory;

	public function getLatestDownloadTidAppModel($tid){

		// $result = DB::connection('sqlsrv')->table('epicvc.RELATION')->where('TERMID', $tid)
		// 															->where('DLD_STATUS', 'SUCCESS')
		// 															->orderby('LAST_ATTEMPTED_DLD_DATE', 'DESC')->get();

		$sql_query = " SELECT		TOP 1 *
					   FROM			epicvc.RELATION
					   WHERE		TERMID = ? AND DLD_STATUS = 'SUCCESS'
					   ORDER BY		LAST_ATTEMPTED_DLD_DATE DESC ";

		$result = DB::connection('sqlsrv')->select($sql_query, [$tid]);

		return $result;
	}

	public function getMerchantParameter($tid, $model, $app){

		// $result = DB::connection('sqlsrv')->table('epicvc.PARAMETER')->where('partid', $tid)
		// 															 ->where('famnm', $model)
		// 															 ->where('appnm', $app)
		// 															 ->whereIn('parnameloc', ['3RH','1RH1','R1HDR01','3RH1','R1HDR1','R3HDR1'])
		// 															 ->value('value');
		// return $result;

		$sql_query = " select	value
					   from		epicvc.PARAMETER
					   where	partid = ? and famnm = ? and appnm = ? and 
								parnameloc in ('3RH','1RH1','R1HDR01','3RH1','R1HDR1','R3HDR1')  ";

		$result = DB::connection('sqlsrv')->select($sql_query, [$tid, $model, $app]);

		return $result;
	}

	public function getAddressLineOne($tid, $model, $app){

		// $result = DB::connection('sqlsrv')->table('epicvc.PARAMETER')->where('partid', $tid)
		// 															 ->where('famnm', $model)
		// 															 ->where('appnm', $app)
		// 															 ->whereIn('parnameloc', ['4RH','R4FDR01','R4FDR1','4RF1','4RH1'])
		// 															 ->value('value');
		// return $result;

		$sql_query = " select	value
					   from		epicvc.PARAMETER
					   where	partid = ? and famnm = ? and appnm = ? and 
								parnameloc in ('4RH','R4FDR01','R4FDR1','4RF1','4RH1')  ";

		$result = DB::connection('sqlsrv')->select($sql_query, [$tid, $model, $app]);

		return $result;
	}

	public function getAddressLineTwo($tid, $model, $app, $address_line_one){

		// $result = DB::connection('sqlsrv')->table('epicvc.PARAMETER')->where('partid', $tid)
		// 															 ->where('famnm', $model)
		// 															 ->where('appnm', $app)
		// 															 ->whereIn('parnameloc', ['5RH','6RH1','R6HDR01','5RH1','R3HDR1','4RH','R4FDR01'])
		// 															 ->where('value', '<>', $address_line_one)
		// 															 ->value('value');
		// return $result;

		$sql_query = " select	value
					   from		epicvc.PARAMETER
					   where	partid = ? and famnm = ? and appnm = ? and value <> ? and 
								parnameloc in ('5RH','6RH1','R6HDR01','5RH1','R3HDR1','4RH','R4FDR01')  ";

		$result = DB::connection('sqlsrv')->select($sql_query, [$tid, $model, $app, $address_line_one]);

		return $result;
	}


}
