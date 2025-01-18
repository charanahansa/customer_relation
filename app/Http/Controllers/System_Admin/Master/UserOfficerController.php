<?php

namespace App\Http\Controllers\System_Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserOfficerController extends Controller {

    public function index() {

        $user = session()->has('user') ? session('user') : $this->getUserAttributes();
        $action_station = DB::table("action_station")->get();
        $officer_role = DB::table("officer_roles")->get();

        return view('system_admin.master.user_officer',  compact('action_station', 'officer_role', 'user')  );
    }

    private function getUserAttributes(){

        $user = new \stdClass();

        $user->user_id = '#Auto#';
        $user->method = 'insert';
        $user->name = '';
        $user->email = '';
        $user->officer_id = '';
        $user->phone = '';
        $user->action_station = "Not";
        $user->officer_role = "Not";
        $user->team_lead = 0;
        $user->head_of_department = 0;
        $user->courier = 0;
        $user->courier_print = 0;
        $user->quotation_email = 0;
        $user->address = '';
        $user->active = 1;

        return $user;
    }

    public function saveData(Request $request) {

        DB::beginTransaction();

        try {

            if($request->submit == 'Reset'){

                return redirect()->route('user_officer');
            }

            $isUpdate = $request->method == 'update' ? true : false;

            $rules['first_name'] = array('required','string','max:200');
            $rules['last_name'] = array('required','string','max:200');
            $rules['email'] = array ('required', 'email', $isUpdate ? "unique:users,email, $request->user_id" : 'unique:users,email');
            $rules['officer_id'] = array('required','string','max:2','min:2', $isUpdate ? "unique:users,officer_id, $request->user_id" : 'unique:users,officer_id' );
            $rules['phone'] = array('required','max:35');
            $rules['action_station'] = array('required','not_in:Not');
            $rules['officer_role'] = array('required','not_in:Not');
            $rules['team_lead'] = array('required','boolean');
            $rules['head_of_department'] = array('required','boolean');
            $rules['active'] = array('required','boolean');

            if($request->method == 'insert'){

                $rules['password'] = array('required','string');
                $rules['confirm_password'] = array('required','string');
            }

            $officerRole = DB::table("officer_roles")->where('role_code', $request->officer_role)->first();
            if (!$officerRole) {

                return redirect()->route('user_officer')->with('error', 'Role not found.');
            }

            $tblUser['name'] = $request->first_name . ' ' . $request->last_name;
            $tblUser['email'] = $request->email;
            $tblUser['role'] = $officerRole->role_id;
            $tblUser['officer_id'] = $request->officer_id;
            $tblUser['team_lead'] = $request->team_lead;
            $tblUser['hod'] = $request->head_of_department;
            $tblUser['active'] = $request->active;

            if($request->method == 'insert'){

                $tblUser['password'] = bcrypt($request->password);
            }

            if ($request->user_id == '#Auto#') {

                DB::table('users')->insert($tblUser);
            } else {

                DB::table('users')->where('id', $request->user_id)->update($tblUser);
            }


            $tblOfficer['ID'] = $request->officer_id;
            $tblOfficer['officer_name'] = $request->first_name . ' ' . $request->last_name;;
            $tblOfficer['head_of_dept'] = $request->head_of_department;
            $tblOfficer['team_lead'] = $request->team_lead;
            $tblOfficer['action_station'] = $request->action_station;
            $tblOfficer['job_roles'] = $request->officer_role;
            $tblOfficer['courier'] = $request->courier;
            $tblOfficer['email'] = $request->email;
            $tblOfficer['phone'] = $request->phone;
            $tblOfficer['courier_print'] = $request->courier_print;
            $tblOfficer['quotation_email'] = $request->quotation_email;
            $tblOfficer['active'] = $request->active;
            $tblOfficer['address'] = $request->address;
            $tblOfficer['vc_name'] = $request->first_name;

            $userExistsResult = DB::table('officers')->where('ID', $request->officer_id)->exists();
            if ($userExistsResult) {

                DB::table('officers')->where('id', $request->officer_id)->update($tblOfficer);
            } else {

                DB::table('officers')->insert($tblOfficer);
            }

            $tblUserTwo['userid'] = strtolower($request->first_name);
            $tblUserTwo['uname'] = ucfirst($request->first_name);
            $tblUserTwo['password'] = md5($request->password);
            $tblUserTwo['pinno'] = '1111';
            $tblUserTwo['officer_id'] = $request->officer_id;
            $tblUserTwo['active'] = $request->active;
            $tblUserTwo['admin'] = 0;

            $userExistsResult = DB::table('users_two')->where('userid', $request->first_name)->exists();
            if ( $userExistsResult ) {

                DB::table('users_two')->where('userid', $request->officer_id)->update($tblUserTwo);
            } else {

                DB::table('users_two')->insert($tblUserTwo);
            }

            if( ($request->action_station == 'hw') && ( ($request->officer_role == 'htl') || ($request->officer_role == 'ho') ) ){

                $tblHwUser['name'] = strtolower($request->first_name);
                $tblHwUser['username'] = ($request->first_name);
                $tblHwUser['email'] = $request->email;
                $tblHwUser['password'] = bcrypt($request->password);

                $HwUserExistsResult = DB::connection('mysql2')->table('users')->where('email', $request->email)->exists();
                if ( $HwUserExistsResult ) {

                    DB::connection('mysql2')->table('users')->where('email', $request->email)->update($tblHwUser);
                } else {

                    DB::connection('mysql2')->table('users')->insert($tblHwUser);
                }
            }

            DB::commit();

            return redirect()->route('user_officer')->with('success', 'Data saved successfully!');

        } catch (\Exception $e) {

            DB::rollback();

            Log::channel('master_bugs')->error('User/Officer Saving Part :- ' . $e->getMessage() . ' ' . $e->getLine());
            return redirect()->route('user_officer')->with('error', $e->getMessage() . ' ' . $e->getLine());;
        }
    }

    public function updateUser(Request $request){

        $tblUser =  DB::table('users')->where('id', $request->id)->first();
        if($tblUser){

            $tblUser->user_id = $tblUser->id;
            $tblOfficer = DB::table('officers')->where('ID', $tblUser->officer_id)->first();
            if($tblOfficer){

                $tblUser->method = 'update';
                $tblUser->action_station = $tblOfficer->action_station;
                $tblUser->officer_role = $tblOfficer->job_roles;
                $tblUser->address = $tblOfficer->address;
                $tblUser->phone = $tblOfficer->phone;
                $tblUser->courier_print = $tblOfficer->courier_print;
                $tblUser->quotation_email = $tblOfficer->quotation_email;

                $names = explode(" ", $tblUser->name);

                $tblUser->first_name = $names[0];
                $tblUser->last_name = $names[1];
            }
        }

        return redirect()->route('user_officer')->with('user', $tblUser);
    }

}
