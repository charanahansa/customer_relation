<!DOCTYPE html>
<html>
<head>
	<title> @yield('title') </title>

	<link rel="stylesheet" href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css>

	<!-- Bootstrap 5.0 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <!-- Date Time Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

            <div class="dropdown_mega_menu">
                <button class="dropbtn">
                    Dashboard
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href='#'>Add Dashboard </a>
                    <a href='#'>Removal Dashboard</a>
                </div>
            </div>

            <div class="dropdown_mega_menu">
                <button class="dropbtn">
                    Notes
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href={{route('maintainance_add')}}>Add Note </a>
                    <a href={{route('maintainance_remove')}}>Remove Note</a>
                </div>
            </div>

            <div class="dropdown_mega_menu">
                <button class="dropbtn">
                    Inquire
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href={{route('maintainance_add_inquire')}}>Add Inquire</a>
                    <a href={{route('maintainance_remove_inquire')}}>Remove Inquire</a>
                </div>
            </div>

            <div class="dropdown_mega_menu">
                  <button class="dropbtn">
                        Reports
                        <i class="fa fa-caret-down"></i>
                  </button>
                  <div class="dropdown_single_menu_content">
                        <a href="#">Add Report</a>
                        <a href="#">Remove Report</a>
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
