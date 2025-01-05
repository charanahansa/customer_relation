<?php

namespace App\Http\Controllers\Vericentre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vericentre\Vericentre;

class VericentreController extends Controller {

	public function getMerchantDetail($tid){

		$objVericentre = new Vericentre();
		
		$latestTMA = $objVericentre->getLatestDownloadTidAppModel($tid);
		foreach($latestTMA as $row){

			$terminal_id = $row->TERMID;
			$model = $row->FAMNM;
			$application = $row->APPNM;

			$merchant_detail['merchant'] = '';
			$merchant_detail['address_line_one'] = '';
			$merchant_detail['address_line_two'] = '';

			$result = $objVericentre->getMerchantParameter($terminal_id, $model, $application);
			foreach($result as $row){

				$merchant_detail['merchant'] = $row->value;
			}

			$result = $objVericentre->getAddressLineOne($terminal_id, $model, $application);
			foreach($result as $row){

				$merchant_detail['address_line_one'] = $row->value;
			}

			$result = $objVericentre->getAddressLineTwo($terminal_id, $model, $application, $merchant_detail['address_line_one']);
			foreach($result as $row){

				$merchant_detail['address_line_two'] = $row->value;
			}

			return $merchant_detail;
		}
		
		return '';
	}

	private function getLatestDownloadTidAppModel($tid){

		$objVericentre = new Vericentre();

		$result = $objVericentre->getLatestDownloadTidAppModel($tid);

		return $result;
	}
    
}
