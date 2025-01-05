<?php

namespace App\Http\Controllers\System_Admin\List;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Master\Bank;
use App\Models\Master\TerminalModel;
use App\Models\Master\Fault;
use App\Models\Master\RelevantDetail;
use App\Models\Master\ActionTaken;
use App\Models\Master\ReinitializationReason;
use App\Models\Master\Zone;

class MasterListController extends Controller {

    public function index(Request $request){

        $type = $request->input('type', 'zone');
        $search = $request->input('search', '');

        switch ($type) {
            case 'bank':
                $data = Bank::where('bankname', 'like', "%$search%")->orderBy('bank_id', 'asc')->get();
                break;
            case 'model':
                $data = TerminalModel::where('model', 'like', "%$search%")->orderBy('model_id', 'asc')->get();
                break;
            case 'fault':
                $data = Fault::where('error', 'like', "%$search%")->orderBy('eno', 'asc')->get();
                break;
            case 'relevant_detail':
                $data = RelevantDetail::where('relevant_detail', 'like', "%$search%")->orderBy('rno', 'asc')->get();
                break;
            case 'action_taken':
                $data = ActionTaken::where('action_taken', 'like', "%$search%")->orderBy('ano', 'asc')->get();
                break;
            case 're_initialization_reason':
                $data = ReinitializationReason::where('reason', 'like', "%$search%")->orderBy('ono', 'asc')->get();
                break;
            case 'zone':
                $data = Zone::where('zone_name', 'like', "%$search%")->orderBy('zone_id', 'asc')->get();
                break;
            default:
                $data = Zone::all();
        }

        return view('system_admin.List.MasterList', compact('data', 'type', 'search'));
    }

}
