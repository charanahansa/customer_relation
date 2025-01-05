<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\Bank;

class BankController extends Controller{

    public function index() {

        $bank = session()->has('bank') ? session('bank') : $this->initializeBank();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';

        return view('system_admin.master.bank', [
                                                    'bank' => $bank,
                                                    'processMessage' => $processMessage
                                                ]);
    }

    private function initializeBank($request = null) {

        $bank = new Bank();
        if (is_null($request)) {

            $bank->tnms_maintain = 0;
            $bank->repair_maintain = 0;
            $bank->full_maintain = 0;
            $bank->qasp = null;
            $bank->lotno = null;
            $bank->qt_no = null;
            $bank->bd_eml = null;
            $bank->bd_report = null;
            $bank->warrant_month = 0;
            $bank->printname = '';
            $bank->sc_code = '';
            $bank->address = '';
        } else {

            $bank->fill($request->all());
        }
        return $bank;
    }

    public function saveBank(Request $request) {

        if ($request->submit == 'Reset') {
            return redirect()->route('bank');
        }

        DB::beginTransaction();

        try {

            $rules = [
                'bank' => 'required|string|max:10',
                'bankname' => 'required|string|max:40',
                'tnms_maintain' => 'required|boolean',
                'repair_maintain' => 'required|boolean',
                'full_maintain' => 'required|boolean',
                'printname' => 'required|string|max:150',
                'sc_code' => 'nullable|string|max:10',
                'address' => 'required|string|max:100',
                'lotno' => 'nullable|integer',
                'qasp' => 'nullable|boolean',
                'qt_no' => 'nullable|integer',
                'bd_eml' => 'nullable|integer',
                'bd_report' => 'nullable|integer',
                'warrant_month' => 'required|integer|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {

                $bank = $this->initializeBank($request);
                $failMessage = $this->generateAlert('danger', 'Please Check Your Inputs');
                return redirect()->route('bank')->withErrors($validator)->with('bank', $bank)->with('processMessage', $failMessage);
            }

            $bank = Bank::updateOrCreate(
                                            ['bank' => $request->bank],
                                            $request->only([
                                                'bankname',
                                                'tnms_maintain',
                                                'repair_maintain',
                                                'full_maintain',
                                                'printname',
                                                'sc_code',
                                                'address',
                                                'lotno',
                                                'qasp',
                                                'qt_no',
                                                'bd_eml',
                                                'bd_report',
                                                'warrant_month'
                                            ])
                                        );

            DB::commit();

            $successMessage = $this->generateAlert('success', 'Bank created successfully.');
            return redirect()->route('bank')->with('bank', $bank)->with('processMessage', $successMessage);

        } catch (\Exception $e) {

            DB::rollback();
            $bank = $this->initializeBank($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage());
            Log::channel('master_bugs')->error('Bank Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('bank')->withErrors($validator)->with('bank', $bank)->with('processMessage', $failMessage);
        }
    }

    public function updateBank(Request $request) {

        $bank = Bank::where('bank_id', $request->id)->first();
        return redirect()->route('bank')->with('bank', $bank);
    }

    private function generateAlert($type, $message) {
        
        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }

}
