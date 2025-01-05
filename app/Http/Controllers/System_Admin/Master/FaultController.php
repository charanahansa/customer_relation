<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\Fault;

class FaultController extends Controller {

    public function index() {

        $error = session()->has('error') ? session('error') : $this->initializeError();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';

        return view('system_admin.master.fault', [
                                                    'error' => $error,
                                                    'processMessage' => $processMessage,
                                                ]);
    }

    private function initializeError($request = NULL) {

        $error = new Fault();
        if (is_null($request)) {
            $error->active = 1; // Default value
        } else {
            $error->fill($request->all());
        }
        return $error;
    }

    public function saveFault(Request $request) {

        if ($request->submit == 'Reset') {
            return redirect()->route('fault');
        }

        DB::beginTransaction();

        try {

            $rules = [
                'error' => 'required|string|max:50',
                'active' => 'required|in:0,1',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {

                $error = $this->initializeError($request);
                $failMessage = $this->generateAlert('danger', 'Please Check Your Inputs');
                return redirect()->route('fault')->withErrors($validator)->with('error', $error)->with('processMessage', $failMessage);
            }

            $error = Fault::updateOrCreate(
                                                ['eno' => $request->eno],
                                                $request->only(['error', 'active'])
                                          );

            DB::commit();

            $successMessage = $this->generateAlert('success', 'Saving Process is Completed successfully.');
            return redirect()->route('fault')->with('error', $error)->with('processMessage', $successMessage);

        } catch (\Exception $e) {

            DB::rollback();
            $error = $this->initializeError($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine());
            Log::channel('master_bugs')->error('Error Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('fault')->withErrors($validator)->with('error', $error)->with('processMessage', $failMessage);
        }
    }

    public function updateFault(Request $request) {

        $fault = Fault::where('eno', $request->id)->first();
        return redirect()->route('fault')->with('error', $fault);
    }

    private function generateAlert($type, $message) {

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }

}
