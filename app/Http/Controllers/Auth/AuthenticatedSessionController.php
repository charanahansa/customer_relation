<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller {

    public function create(){

        return view('auth.login');
    }

    public function store(LoginRequest $request){

        $request->authenticate();

        $request->session()->regenerate();

        //return redirect()->intended(RouteServiceProvider::HOME);

		if(Auth::user()->role == 3){

			return redirect()->to('tmc_dashboard');
		}

		if(Auth::user()->role == 5){

			return redirect()->to('hardware_dashboard');
		}

        if(Auth::user()->role == 4){

			return redirect()->to('field_service_dashboard');
		}

        if(Auth::user()->role == 7){

			return redirect()->to('system_admin_dashboard');
		}
    }


    public function destroy(Request $request){

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
