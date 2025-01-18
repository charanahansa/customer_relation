<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\Buyer;

class BuyerController extends Controller {

    public function index(){

        $buyer = session()->has('buyer') ? session('buyer') : $this->initializeBuyer();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';
        return view('system_admin.master.buyer', [
                                                    'buyer' => $buyer,
                                                    'processMessage' => $processMessage,
                                                ]);
    }

    private function initializeBuyer($request = null){

        $buyer = new Buyer();
        if (is_null($request)) {
            $buyer->buyer_name = '';
        } else {
            $buyer->buyer_id = $request->buyer_id;
            $buyer->buyer_name = $request->buyer_name;
        }
        return $buyer;
    }

    public function saveBuyer(Request $request){

        if ($request->submit === 'Reset') {

            return redirect()->route('buyer');
        }

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'buyer_name' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {

                $buyer = $this->initializeBuyer($request);
                $failMessage = $this->generateAlert('danger', 'Please check your inputs.');
                return redirect()->route('buyer')->withErrors($validator)->with('buyer', $buyer)->with('processMessage', $failMessage);
            }

            $buyer = Buyer::updateOrCreate(
                                            ['buyer_id' => $request->buyer_id],
                                            [
                                                'buyer_name' => $request->buyer_name,
                                                'active' => $request->active
                                            ]
                                         );

            DB::commit();
            $processMessage = $this->generateAlert('success', 'Action Taken saved successfully!');
            return redirect()->route('buyer')->with('buyer', $buyer)->with('processMessage', $processMessage);

        } catch (\Exception $e) {

            DB::rollback();

            $buyer = $this->initializeBuyer($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine());
            Log::channel('master_bugs')->error('Buyer Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('buyer')->withErrors($validator)->with('buyer', $buyer)->with('processMessage', $failMessage);
        }
    }

    public function updateBuyer(Request $request){

        $buyer = Buyer::where('buyer_id', $request->id)->first();
        return redirect()->route('buyer')->with('buyer', $buyer);
    }

    private function generateAlert($type, $message){

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }

}
