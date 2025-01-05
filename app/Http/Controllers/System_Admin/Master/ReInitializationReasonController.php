<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\ReinitializationReason;

class ReInitializationReasonController extends Controller {

    public function index(){

        $reinitializationReason = session()->has('reinitialization_reason') ? session('reinitialization_reason') : $this->initializeReason();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';
        return view('system_admin.master.re_initialization_reason', [
                                                                        'reinitializationReason' => $reinitializationReason,
                                                                        'processMessage' => $processMessage,
                                                                    ]);
    }

    private function initializeReason($request = null){

        $reinitializationReason = new ReinitializationReason();
        if (is_null($request)) {
            $reinitializationReason->reason = '';
        } else {
            $reinitializationReason->ono = $request->ono;
            $reinitializationReason->reason = $request->reason;
        }
        return $reinitializationReason;
    }

    public function saveReason(Request $request){

        if ($request->submit === 'Reset') {
            return redirect()->route('reinitialization_reason');
        }

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:60',
            ]);

            if ($validator->fails()) {
                $reinitializationReason = $this->initializeReason($request);
                $failMessage = $this->generateAlert('danger', 'Please check your inputs.');
                return redirect()->route('reinitialization_reason')->withErrors($validator)
                                                                  ->with('reinitialization_reason', $reinitializationReason)
                                                                  ->with('processMessage', $failMessage);
            }

            $reinitializationReason = ReinitializationReason::updateOrCreate(
                ['ono' => $request->ono],
                [
                    'reason' => $request->reason,
                    'active' => $request->active
                ]
            );

            DB::commit();
            $processMessage = $this->generateAlert('success', 'Reinitialization Reason saved successfully!');
            return redirect()->route('reinitialization_reason')->with('reinitialization_reason', $reinitializationReason)->with('processMessage', $processMessage);

        } catch (\Exception $e) {

            DB::rollback();

            $reinitializationReason = $this->initializeReason($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine());
            Log::channel('master_bugs')->error('Reinitialization Reason Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('reinitialization_reason')->withErrors($validator)
                                                              ->with('reinitialization_reason', $reinitializationReason)
                                                              ->with('processMessage', $failMessage);
        }
    }

    public function updateReason(Request $request){

        $reinitializationReason = ReinitializationReason::where('ono', $request->id)->first();
        return redirect()->route('reinitialization_reason')->with('reinitialization_reason', $reinitializationReason);
    }

    private function generateAlert($type, $message){

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }


}
