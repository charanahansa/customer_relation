<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\TerminalModel;

class ModelController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $model = session()->has('model') ? session('model') : $this->initializeModel();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';

        return view('system_admin.master.model', [
                                                    'model' => $model,
                                                    'processMessage' => $processMessage,
                                                ]);
    }

    private function initializeModel($request = NULL) {

        $model = new TerminalModel;
        if (is_null($request)) {
            $model->active = 1;
        } else {
            $model->fill($request->all());
        }
        return $model;
    }

    public function saveModel(Request $request) {

        if ($request->submit == 'Reset') {
            return redirect()->route('model');
        }

        DB::beginTransaction();

        try {

            $rules = [
                'model' => 'required|string|max:15',
                'active' => 'required|in:0,1',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {

                $model = $this->initializeModel($request);
                $failMessage = $this->generateAlert('danger', 'Please Check Your Inputs');
                return redirect()->route('model')->withErrors($validator)->with('model', $model)->with('processMessage', $failMessage);
            }

            $model = TerminalModel::updateOrCreate(
                                                    ['model_id' => $request->model_id],
                                                    $request->only(['model', 'active'])
                                                  );

            DB::commit();

            $successMessage = $this->generateAlert('success', 'Saving Process is Completed successfully.');
            return redirect()->route('model')->with('model', $model)->with('processMessage', $successMessage);

        } catch (\Exception $e) {

            DB::rollback();

            $model = $this->initializeModel($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine());
            Log::channel('master_bugs')->error('Model Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('model')->withErrors($validator)->with('model', $model)->with('processMessage', $failMessage);
        }
    }

    public function updateModel(Request $request) {

        $model = TerminalModel::where('model_id', $request->id)->first();
        return redirect()->route('model')->with('model', $model);
    }

    private function generateAlert($type, $message) {

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }

}
