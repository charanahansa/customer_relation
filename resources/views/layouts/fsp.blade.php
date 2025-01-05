<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:title" content="one breath">
	<meta property="og:description" content="This moment is your life. Breathe. Notice it.">
	<meta property="og:image" content="http://i61.tinypic.com/2yjzpsz.png">
	<meta property="og:url" content="http://www.onebreath.io">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content=@yield('refresh_second'); URL=@yield('self_route')>

	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- Date Time Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<title> @yield('title') </title>

	<!-- Bootstrap 5.0 -->
	<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


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

        .dropdown_user_menu{

            float:right;
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

            <a href="">Home</a>

            <a href="">My Ticket</a>

            <div class="dropdown_mega_menu">
                  <button class="dropbtn">
                      Ticketing Pool
                      <i class="fa fa-caret-down"></i>
                  </button>
                  <div class="dropdown_single_menu_content">
                        <a href="{{route('ticket_allocation_pool')}}">Ticket Allocation Pool</a>
                        <a href="#">Terminal Programming pool</a>
                  </div>
            </div>

            <div class="dropdown_mega_menu">
                  <button class="dropbtn">
                    Ticket List
                      <i class="fa fa-caret-down"></i>
                  </button>
                  <div class="dropdown_single_menu_content">
                    <a href="{{route('fs_breakdown_inquire')}}">Breakdown List</a>
                    <a href="{{route('fs_new_inquire')}}">New Installation List</a>
                    <a href="{{route('fs_re_initialization_inquire')}}">Re Initialization List</a>
                    <a href="{{route('fs_software_update_inquire')}}">Software Updating List</a>
                    <a href="{{route('fs_terminal_replacement_inquire')}}">Terminal Replacement List</a>
                    <a href="{{route('fs_backup_removal_inquire')}}">Backup Removal List</a>
                </div>
            </div>

            <div class="dropdown_mega_menu">
                <button class="dropbtn">
                  Inquire
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href="{{route('maintainance_add')}}">Workflow Inquire</a>
                    <a href="{{route('maintainance_add')}}">Terminal Inquire</a>
                    <a href="{{route('maintainance_add')}}">Multi Terminal Inquire</a>
                    <a href="{{route('maintainance_add')}}">Delivery Note Inquire</a>
                    <a href="{{route('maintainance_add')}}">Multi Delivery Note Inquire</a>
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
                </div>
            </div>

            <div class="dropdown_user_menu">
                <div class="dropdown_mega_menu">

                    <button class="dropbtn">
                        {{Auth::user()->name}}
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown_single_menu_content">
                        <a href="#">User Register</a>
                        <a href="#">User List</a>
                        <a href="{{route('password_reset')}}">Password Reset</a>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

		<!-- Page Content -->
		<div id="content">

			@yield('body')

		</div>

	</div>

    <!-- Bootstrap 5.0 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</body>
</html>
