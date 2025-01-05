<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\Zone;

class ZoneController extends Controller {

    public function __construct() {
        
        $this->middleware('auth');
    }

    public function index() {

        $zone = session()->has('zone') ? session('zone') : $this->initializeZone();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';
        return view('system_admin.master.zone', [
                                                    'zone' => $zone,
                                                    'processMessage' => $processMessage,
                                                ]);
    }

    private function initializeZone($request = NULL) {

        $zone = new Zone();
        if (is_null($request)) {
            $zone->active = 1;
        } else {
            $zone->fill($request->all());
        }
        return $zone;
    }

    public function saveZone(Request $request) {

        if($request->submit == 'Reset'){

            return redirect()->route('zone');
        }

        DB::beginTransaction();

        try {

            $rules = [
                'zone_name' => 'required|string|max:20',
                'resolution_time' => 'required|integer',
                'active' => 'required|in:0,1',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {

                $zone = $this->initializeZone($request);
                $failMessage = $this->generateAlert('danger', 'Please Check Your Inputs');
                return redirect()->route('zone')->withErrors($validator)->with('zone', $zone)->with('processMessage', $failMessage);
            }

            $zone = Zone::updateOrCreate(
                ['zone_id' => $request->zone_id],
                $request->only(['zone_name', 'resolution_time', 'active'])
            );

            DB::commit();

            $successMessage = $this->generateAlert('success', 'Saving Process is Completed successfully.');
            return redirect()->route('zone')->with('zone', $zone)->with('processMessage', $successMessage);

        } catch (\Exception $e) {

            DB::rollback();
            $zone = $this->initializeZone($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine() );
            Log::channel('master_bugs')->error('Product Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('zone')->withErrors($validator)->with('zone', $zone)->with('processMessage', $failMessage);
        }
    }

    public function updateZone(Request $request) {

        $zone = Zone::where('zone_id', $request->id)->first();
        return redirect()->route('zone')->with('zone', $zone);
    }

    private function generateAlert($type, $message) {

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }

}
