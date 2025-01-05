<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;

use App\Models\User;
use Exception;
use Validator;


class PasswordResetController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$data['attributes'] = $this->get_password_reset(NULL);

        return view('admin.password_reset')->with('pr', $data);
    }

	private function get_password_reset($process_result){

		$attributes['process_message'] = '';
		$attributes['error_message'] = new MessageBag();

		if( (is_null($process_result) == TRUE) ){

            return $attributes;
        }

		$process_validation_result = $process_result['validation_result'];
        $process_message = $process_result['head_message'];
		$attributes['error_message'] = $process_result['error_message'];

		if($process_validation_result == TRUE){

            $attributes['process_message'] = '<div class="alert alert-success" role="alert"> '. $process_message .'. </div> ';
        }else{

            $attributes['process_message'] = '<div class="alert alert-danger" role="alert"> '. $process_message .'. </div> ';
        }

		return $attributes;
	}

	public function password_reset_process(Request $request){

		$process['validation_result'] = '';
        $process['head_message'] = '';
        $process['error_message'] = '';

		$input = $request->input();

		$validation_result_data = $this->password_reset_validation_process($request);
		if($validation_result_data['result'] == TRUE){

			$saving_process_result_data = $this->password_saving_process($request);
			if($saving_process_result_data['status'] == TRUE){

				$process['validation_result'] = TRUE;
				$process['head_message'] = $saving_process_result_data['message'];
				$process['error_message'] = new MessageBag();

			}else{

				$process['validation_result'] = FALSE;
				$process['head_message'] = $saving_process_result_data['message'];
				$process['error_message'] = new MessageBag();
			}

		}else{

			$process['validation_result'] = FALSE;
	        $process['head_message'] =  $validation_result_data['head_message'];
	        $process['error_message'] = $validation_result_data['error_message'];
		}

		// echo '<pre>';
		// print_r($process);
		// echo '</pre>';

		$data['attributes'] = $this->get_password_reset($process);

		return view('admin.password_reset')->with('pr', $data);

	}

	private function password_reset_validation_process($request){

		try{

            $input['current_password'] = $request->current_password;
            $input['new_password'] = $request->new_password;
            $input['again_password'] = $request->again_password;


            $rules['current_password'] = array('required', 'min:8');
            $rules['new_password'] = array('required', 'string', 'min:8', 'required_with:again_password', 'same:again_password');
            $rules['again_password'] = array('required', 'string', 'min:8');


            $validator = Validator::make($input, $rules);
			$validated_result = $validator->passes();

			$head_message = '';
			if($validated_result == FALSE){

				$head_message = 'Please Check Your Inputs.';
			}

            $data['result'] = $validated_result;
            $data['head_message'] = $head_message;
			$data['error_message'] = $validator->errors();

            return $data;

        }catch(\Exception $e){

            $data['result'] = FALSE;
            $data['head_message'] = $e->getMessage();
			$data['error_message'] = new MessageBag();

            return $data;
        }

	}

	private function password_saving_process($request){

		$result['status'] = FALSE;
        $result['message'] = '';

		try{

			$objUser = new User();

			$user_id =  Auth::user()->id;
			$password = Hash::make($request->new_password);

			// Call To Model
            $saving_process_result = $objUser->password_reset_process($user_id, $password);

            if($saving_process_result['status'] == FALSE){

                throw new Exception($saving_process_result['user_message']);
            }

			$result['status'] = TRUE;
            $result['message'] = $saving_process_result['user_message'];

		}catch(\Exception $e){

			$result['status'] = FALSE;
            $result['message'] = $e->getMessage();

        }finally{

			return $result;
		}

	}




}
