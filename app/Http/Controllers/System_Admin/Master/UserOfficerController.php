<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Models\User;
use App\Models\Master\Officer;

class UserOfficerController extends Controller {

    public function index() {

        $data['action_station'] = DB::table("action_station")->get();
        $data['attributes'] = $this->getUserAttributes(NULL, NULL);

        return view('system_admin.master.user_officer')->with('User', $data);
    }

    private function getUserAttributes($process, $request){

        $attributes['user_name'] = '';
        $attributes['email'] = 0;
        $attributes['action_station'] = "Not";
        $attributes['delivary_no'] = "";
        $attributes['terminal_serial'] = '';
        $attributes['address'] = '';

		$attributes['process_message'] = '';
		$attributes['validation_messages'] = new MessageBag();

		if((is_null($process) == TRUE) && (is_null($request) == TRUE)){

            return $attributes;
        }
    }

    public function saveData(Request $request) {

        // Validate and save User data
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'nullable|integer',
            'officer_id' => 'nullable|string|max:5',
            'team_lead' => 'nullable|boolean',
            'active' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'officer_id' => $request->officer_id,
            'team_lead' => $request->team_lead,
            'active' => $request->active,
            'password' => Hash::make($request->password),
        ]);

        // Validate and save Officer data
        $request->validate([
            'officer_name' => 'required|string|max:40',
            'action_station' => 'nullable|string|max:5',
            'email_officer' => 'nullable|email|max:50',
            'phone' => 'nullable|string|max:35',
            'address' => 'nullable|string|max:100',
        ]);

        Officer::create([
            'officer_name' => $request->officer_name,
            'action_station' => $request->action_station,
            'email' => $request->email_officer,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('user_officer')->with('success', 'Data saved successfully!');
    }

}
