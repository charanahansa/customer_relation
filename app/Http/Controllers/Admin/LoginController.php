<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class LoginController extends Controller{

    public function __construct(){

        $this->middleware('auth');
    }

    public function Management_Dashboard(){

        return view('management.management_dashboard');
    }

    public function Tmc_dashboard(){

		return view('tmc.tmc_dashboard');
	}

	public function Hardware_dashboard(){

		return view('hardware.hardware_dashboard');
	}

    public function System_Admin_Dashboard(){

        return view('system_admin.system_admin_dashboard');
    }

    public function Maintainance_Dashboard(){

        return view('maintainance.maintainance_dashboard');
    }

    public function Field_Service_Dashboard(){

		return view('fsp.field_service_dashboard');
	}

    public function Vericentre_Dashboard(){

		return view('vericentre.vericentre_dashboard');
	}
}
