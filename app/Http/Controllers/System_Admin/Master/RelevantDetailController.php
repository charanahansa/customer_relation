<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Master\RelevantDetail;

class RelevantDetailController extends Controller {

    public function index(){

        $relevantDetail = session()->has('relevant_detail') ? session('relevant_detail') : $this->initializeRelevantDetail();
        $processMessage = session()->has('processMessage') ? session('processMessage') : '';
        return view('system_admin.master.relevant_detail', [
                                                                'relevantDetail' => $relevantDetail,
                                                                'processMessage' => $processMessage,
                                                            ]);
    }

    private function initializeRelevantDetail($request = null){

        $relevantDetail = new RelevantDetail();
        if (is_null($request)) {
            $relevantDetail->relevant_detail = '';
        } else {
            $relevantDetail->rno = $request->rno;
            $relevantDetail->relevant_detail = $request->relevant_detail;
        }
        return $relevantDetail;
    }

    public function saveRelevantDetail(Request $request){

        if ($request->submit === 'Reset') {

            return redirect()->route('relevant_detail');
        }

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'relevant_detail' => 'required|string',
            ]);

            if ($validator->fails()) {

                $relevantDetail = $this->initializeRelevantDetail($request);
                $failMessage = $this->generateAlert('danger', 'Please check your inputs.');
                return redirect()->route('relevant_detail')->withErrors($validator)
                                                           ->with('relevant_detail', $relevantDetail)
                                                           ->with('processMessage', $failMessage);
            }

            $relevantDetail = RelevantDetail::updateOrCreate(
                                                ['rno' => $request->rno],
                                                [
                                                    'relevant_detail' => $request->relevant_detail,
                                                    'active' => $request->active
                                                ]
                                          );

            DB::commit();
            $processMessage = $this->generateAlert('success', 'Relevant Detail saved successfully!');
            return redirect()->route('relevant_detail')->with('relevant_detail', $relevantDetail)->with('processMessage', $processMessage);

        } catch (\Exception $e) {

            DB::rollback();

            $relevantDetail = $this->initializeRelevantDetail($request);
            $failMessage = $this->generateAlert('danger', $e->getMessage() . ' ' . $e->getLine());
            Log::channel('master_bugs')->error('Relevant Detail Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('relevant_detail')->withErrors($validator)
                                                       ->with('relevant_detail', $relevantDetail)
                                                       ->with('processMessage', $failMessage);
        }
    }

    public function updateRelevantDetail(Request $request){

        $relevantDetail = RelevantDetail::where('rno', $request->id)->first();
        return redirect()->route('relevant_detail')->with('relevant_detail', $relevantDetail);
    }

    private function generateAlert($type, $message){

        return "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
    }

}
