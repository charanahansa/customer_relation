<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\ActionTaken;

class ActionTakenController extends Controller {

    public function index(){

        $actionTaken = session()->has('action_taken') ? session('action_taken') : $this->initializeActionTaken();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';
        return view('system_admin.master.action_taken', [
                                                            'actionTaken' => $actionTaken,
                                                            'processMessage' => $processMessage,
                                                        ]);
    }

    private function initializeActionTaken($request = null){

        $actionTaken = new ActionTaken();
        if (is_null($request)) {
            $actionTaken->action_taken = '';
        } else {
            $actionTaken->ano = $request->ano;
            $actionTaken->action_taken = $request->action_taken;
        }
        return $actionTaken;
    }

    public function saveActionTaken(Request $request){

        if ($request->submit === 'Reset') {

            return redirect()->route('action_taken');
        }

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'action_taken' => 'required|string|max:60',
            ]);

            if ($validator->fails()) {

                $actionTaken = $this->initializeActionTaken($request);
                $failMessage = $this->generateAlert('danger', 'Please check your inputs.');
                return redirect()->route('action_taken')->withErrors($validator)
                                                         ->with('action_taken', $actionTaken)
                                                         ->with('processMessage', $failMessage);
            }

            $actionTaken = ActionTaken::updateOrCreate(
                                        ['ano' => $request->ano],
                                        [
                                            'action_taken' => $request->action_taken,
                                            'active' => $request->active
                                        ]
                                  );

            DB::commit();
            $processMessage = $this->generateAlert('success', 'Action Taken saved successfully!');
            return redirect()->route('action_taken')->with('action_taken', $actionTaken)->with('processMessage', $processMessage);

        } catch (\Exception $e) {

            DB::rollback();

            $actionTaken = $this->initializeActionTaken($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine());
            Log::channel('master_bugs')->error('Action Taken Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('action_taken')->withErrors($validator)
                                                     ->with('action_taken', $actionTaken)
                                                     ->with('processMessage', $failMessage);
        }
    }

    public function updateActionTaken(Request $request){

        $actionTaken = ActionTaken::where('ano', $request->id)->first();
        return redirect()->route('action_taken')->with('action_taken', $actionTaken);
    }

    private function generateAlert($type, $message){

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }



}
