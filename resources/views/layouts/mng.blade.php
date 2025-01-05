<!DOCTYPE html>
<html>
<head>
	<title> @yield('title') </title>

	<link rel="stylesheet" href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css>

	<!-- Bootstrap 4.6 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

      <link rel="stylesheet" type="text/css" href=".\asset\date_time_picker\jquery.datetimepicker.min.css">
      <script type="text/javascript" src=".\asset\date_time_picker\jquery.js"></script>
      <script type="text/javascript" src=".\asset\date_time_picker\jquery.datetimepicker.full.js"></script>

	<style>
		* {
	          box-sizing: border-box;
	    }

        body {

              margin: 0;
        }

        .navbar_mega_menu {

              overflow: hidden;
              background-color: #333;
              font-family: consolas;
        }

        .navbar_mega_menu_right_align {

              float: right;
        }

        .navbar_mega_menu a {

              float: left;
              font-size: 16px;
              color: white;
              text-align: center;
              padding: 14px 16px;
              text-decoration: none;
        }

        .dropdown_mega_menu {

              float: left;
              overflow: hidden;
        }

        .dropdown_mega_menu .dropbtn {

              font-size: 16px;
              border: none;
              outline: none;
              color: white;
              padding: 14px 16px;
              background-color: inherit;
              font: inherit;
              margin: 0;
        }

        .navbar_mega_menu a:hover, .dropdown_mega_menu:hover .dropbtn {

              background-color: red;
        }

        .dropdown_mega_menu_content {

              display: none;
              position: absolute;
              background-color: #f9f9f9;
              width: 98%;
              left: 1%;
              box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
              z-index: 1;
        }

        .dropdown_mega_menu_content .header {

              background: red;
              padding: 16px;
              color: white;
        }

        .dropdown_mega_menu:hover .dropdown_mega_menu_content  {

              display: block;
        }

        /* Create three equal columns that floats next to each other */
        .column {

              float: left;
              width: 25%;
              padding: 10px;
              background-color: #ccc;
              height: 400px;
        }

        .column a {

              float: none;
              color: black;
              padding: 16px;
              text-decoration: none;
              display: block;
              text-align: left;
        }

        .column a:hover {

              background-color: #ddd;
        }

        /* Clear floats after the columns */
        .row:after {

              content: "";
              display: table;
              clear: both;
        }

        .dropdown_single_menu_content {

              display: none;
              position: absolute;
              background-color: #f9f9f9;
              min-width: 160px;
              box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
              z-index: 1;
        }

        .dropdown_single_menu_content a {

              float: none;
              color: black;
              padding: 12px 16px;
              text-decoration: none;
              display: block;
              text-align: left;
        }

        .dropdown_single_menu_content a:hover {

              background-color: #ddd;
              color: black;
        }

        .dropdown_mega_menu:hover .dropdown_single_menu_content {

              display: block;
        }

	</style>
</head>
<body>

	<div id="tbldiv" style="width: 100%;">

		<div class="navbar_mega_menu">

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Dashboard <i class="fa fa-caret-down"></i>
				</button>

	    		      <div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column">
							<h5>Field Service </h5>
							<a href={{route('breakdown_management_dashboard')}}>Breakdown</a>
							<a href={{route('new_installation_managment_dashboard')}}>New Installation</a>
							<a href={{route('re_initialization_management_dashboard')}}>Re Initilization</a>
							<a href={{route('software_updation_management_dashboard')}}>Software Updation</a>
							<a href={{route('terminal_replacement_management_dashboard')}}>Terminal Replacement</a>
							<a href={{route('backup_removal_management_dashboard')}}>Backup Removal</a>
						</div>

						<div class="column">
							<h5>Vericentre </h5>
							<a href={{route('profile_sharing_management_dashboard')}}>Profile Sharing</a>
							<a href={{route('profile_updation_management_dashboard')}}>Profile Updation</a>
							<a href={{route('profile_conversion_management_dashboard')}}>Profile Conversion</a>
							<a href={{route('merchant_removal_management_dashboard')}}>Merchant Removal</a>
						</div>

						<div class="column">
							<h5>Hardware </h5>
							<a href={{route('Repair_management_Dashboard')}}>Repair Ticket</a>
						</div>

						<div class="column">
							<h5>TMC </h5>
							<a href={{route('terminal_in_out_management_dashboard')}}>Terminal In Out</a>
							<a href={{route('base_software_installation_management_dashboard')}}>Base Software Installation</a>
						</div>

					</div>

				</div>
			</div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Workflow Report <i class="fa fa-caret-down"></i>
				</button>

				<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column">
							<h5>Field Service </h5>
							<a href={{route('breakdown_report')}}>Breakdown</a>
							<a href={{route('new_installation_report')}}>New Installation</a>
							<a href={{route('re_initilization_report')}}>Re Initilization</a>
							<a href={{route('software_updation_report')}}>Software Updation</a>
							<a href={{route('terminal_replacement_report')}}>Terminal Replacement</a>
							<a href={{route('backup_removal_report')}}>Backup Removal</a>
						</div>

						<div class="column">
							<h5>Vericentre </h5>
							<a href={{route('profile_sharing_report')}}>Profile Sharing</a>
							<a href={{route('profile_updation_report')}}>Profile Updation</a>
							<a href={{route('profile_conversion_report')}}>Profile Conversion</a>
							<a href={{route('merchant_removal_report')}}>Merchant Removal</a>
						</div>

						<div class="column">
							<h5>Hardware </h5>
							<a href={{route('Repair_management_Dashboard')}}>Repair Ticket</a>
						</div>

						<div class="column">
							<h5>TMC </h5>
							<a href={{route('terminal_inout_report')}}>Terminal In Out</a>
							<a href={{route('base_software_installation_report')}}>Base Software Installation</a>
						</div>

					</div>

				</div>
			</div>

            <div class="dropdown_mega_menu">
                <button class="dropbtn">
                  Module
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href="{{route('management_dashboard')}}">Management Module</a>
                    <a href="{{route('tmc_dashboard')}}">Tmc Module</a>
                    <a href="{{route('hardware_dashboard')}}">Hardware Module</a>
                    <a href="{{route('maintainance_dashboard')}}">Maintainance Module</a>
                    <a href="{{route('vericentre_dashboard')}}">Vericentre Module</a>
					<a href="{{route('field_service_dashboard')}}">Field Service Module</a>
                    <a href="{{route('system_admin_dashboard')}}">System Admin Module</a>
                </div>
            </div>

			<div class="navbar_mega_menu_right_align">
                <div class="dropdown_mega_menu">
                    <button class="dropbtn" style="width: 100%;">
                        {{Auth::user()->email}}
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown_single_menu_content">
                        <a href="#">Profile</a>
                        <a href="{{route('password_reset')}}">Password Reset</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{route('logout')}}" onclick = "event.preventDefault(); this.closest('form').submit();">Logout</a>
                        </form>
                    </div>
                </div>

                <div class="dropdown_mega_menu">
                </div>

            </div>

		</div>


		<!-- Page Content -->
		<div id="content">

			@yield('body')

		</div>

	</div>

</body>
</html>
