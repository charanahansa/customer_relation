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

        .column_three {

            float: left;
            width: 33%;
            padding: 10px;
            background-color: #ccc;
            height: 400px;
        }

        .column_three a {

            float: none;
            color: black;
            padding: 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .column_three a:hover {

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
                            <h5>Operation Dashboard </h5>
                            <a href={{route('repair_operation_dashboard_one')}}>Operational Dashboard # 1</a>
                            <a href={{route('repair_operation_dashboard_two')}}>Operational Dashboard # 2</a>
                            <a href=#>Dashboard # 3</a>
                        </div>

                        <div class="column">
                            <h5>Quotation Dashboard </h5>
                            <a href=#>Dashboard # 1</a>
                            <a href=#>Dashboard # 2</a>
                            <a href=#>Dashboard # 3</a>
                        </div>

                        <div class="column">
                            <h5>Spare Part Dashboard </h5>
                            <a href=#>Dashboard # 1</a>
                            <a href=#>Dashboard # 2</a>
                            <a href=#>Dashboard # 3</a>
                        </div>

                        <div class="column">
                            <h5>Warranty Dashboard </h5>
                            <a href=#>Dashboard # 1</a>
                            <a href=#>Dashboard # 2</a>
                            <a href=#>Dashboard # 3</a>
                        </div>

                    </div>

                </div>

            </div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Operation Process <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>Tickets </h5>
							<a href=#>Terminal In</a>
							<a href=#>Terminal Process</a>
							<a href={{route('terminal_release')}}>Terminal Release</a>
						</div>

						<div class="column_three">
							<h5>Inquire </h5>
							<a href={{route('jobcard_inquire')}}>Job Card Inquire</a>
							<a href={{route('jobcard_multi_inquire')}}>Job Card Multi Inquire</a>
						</div>

						<div class="column_three">
							<h5>Reports </h5>
							<a href={{route('terminal_in_report')}}>Terminal In Report</a>
							<a href={{route('jobcard_report')}}>Terminal Process Report</a>
							<a href={{route('terminal_out_report')}}>Terminal Out Report</a>
						</div>

					</div>

				</div>

			</div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Quotation Process <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>Inquire </h5>
							<a href={{route('quotation_inquire')}}>Quotation Inquire</a>
							<a href={{route('quotation_multi_inquire')}}>Quotation Multi Inquire</a>
						</div>

						<div class="column_three">
							<h5>Report </h5>
							<a href=#>Quotation Report - Wizard</a>
                            <a href={{route('insurance_claim_report')}}>Insurance Claim Report </a>
						</div>

						<div class="column_three">
							<h5>Option </h5>
							<a href=#>Date Change Option</a>
							<a href=#>Re Create Option</a>
						</div>

					</div>

				</div>

			</div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Spare Part <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>Spare Part </h5>
							<a href={{route('spare_part_add_note')}}>Spare Part - Individual</a>
							<a href={{route('spare_part_add_note_bulk')}}>Spare Part - Bulk</a>
							<a href={{route('spare_part_receive_note')}}>Spare Part Received Note</a>
							<a href={{route('spare_part_request_note')}}>Spare Part Request Note</a>
							<a href={{route('spare_part_issue_note')}}>Spare Part Issue Note</a>
						</div>

						<div class="column_three">
							<h5>List </h5>
							<a href={{route('spare_part_list')}}>Spare Part List</a>
							<a href={{route('spare_part_receive_list')}}>Spare Part Receive List</a>
							<a href={{route('spare_part_request_list')}}>Spare Part Request List</a>
							<a href={{route('spare_part_issue_list')}}>Spare Part Issue List</a>
						</div>

						<div class="column_three">
							<h5>Report </h5>
							<a href={{route('sp_bin_report')}}>Spare Part Bin Report</a>
							<a href={{route('sp_pending_report')}}>Spare Part Pending Report</a>
							<a href={{route('sp_usage_report')}}>Spare Part Usage Report</a>
						</div>

					</div>

				</div>

			</div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Warranty Process <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>Pool </h5>
						</div>

						<div class="column_three">
							<h5>Inquire </h5>
						</div>

						<div class="column_three">
							<h5>Report </h5>
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
                    <a href="{{route('hardware_dashboard')}}">Hardware Module</a>
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
