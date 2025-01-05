<?php

namespace App\Http\Controllers\Tmc\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tmc\Delivery\Delivery;

class DeliveryInquireController extends Controller {

    public function index(){

        $objDelivery = new Delivery();

		$data['epic_delivery'] = array();
        $data['scienter_delivery'] = array();

		return view('tmc.delivery.delivery_inquire')->with('DI', $data);
	}

    public function deliveryInquireProcess(Request $request){

        $objDelivery = new Delivery();

        $data['epic_delivery'] = $objDelivery->getEpicDeliveryDetail($request->serial_number);
        $data['scienter_delivery'] =$objDelivery->getScienterDeliveryDetail($request->serial_number);

		return view('tmc.delivery.delivery_inquire')->with('DI', $data);
    }

}
