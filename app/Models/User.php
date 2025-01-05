<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable {

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'officer_id',
        'team_lead',
        'active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'team_lead' => 'boolean',
        'hod' => 'boolean',
        'active' => 'boolean',
    ];


	public function password_reset_process($user_id, $password){

        $result['status']=FALSE;
        $result['user_message']="$$";
        $result['dev_message']="$$";

		try{

			DB::beginTransaction();

			$affected = DB::table('users')->where('id', $user_id)->update(['password' => $password]);

			if($affected == 1){

				DB::commit();

				$result['status'] = TRUE;
				$result['user_message'] = "Saving Process is Completed successfully.";
				$result['dev_message'] = "Commited.";

			}else{

				DB::rollback();

				$result['status'] = TRUE;
				$result['user_message'] = "Saving Process is Aborted.";
				$result['dev_message'] = "RollBacked.";
			}

		}catch (\Exception $ex) {

            $result['status'] = FALSE;
            $result['user_message'] = $ex->getMessage();
            $result['dev_message'] = $ex->getMessage();

        }finally{

            return $result;
        }
	}

    public function get_active_users(){

		$result = DB::table('users')->where('active', 1)->orderby('name', 'asc')->get();

		return $result;
	}

    public function getActiveFieldOfficers(){

		$result = DB::table('users')->where('active', 1)->where('role', 4)->orderby('name', 'asc')->get();

		return $result;
	}

    public function getActiveTmcAndFieldOfficers(){

		$result = DB::table('users')->where('active', 1)->whereIn('role', [3, 4])->orderby('name', 'asc')->get();

		return $result;
	}

    public function get_user_name($id){

        $user_name = DB::table('users')->where('id', $id)->value('name');

		return $user_name;
    }

    public function getOfficerName($officer_id){

        $user_name = DB::table('users')->where('officer_id', $officer_id)->value('name');

		return $user_name;
    }

    public function getOfficerMobileNumber($officer_id){

        $mobile_number = DB::table('officers')->where('id', $officer_id)->value('phone');

		return $mobile_number;
    }

	public function getBankOfficerName($bank_officer){

		$courier_name = DB::table('bank_officer')->where('id', $bank_officer)->value('officer_name');

		return $courier_name;
	}



}
