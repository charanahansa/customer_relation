<!DOCTYPE html>
<html>
<head>
	<title> @yield('title') </title>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                    Ticket
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href={{route('terminal_in_note')}}>Terminal In Process</a>
                    <a href={{route('terminal_out_note')}}>Terminal Out Process</a>
                </div>
            </div>


			<div class="dropdown_mega_menu">
                <button class="dropbtn">
                    Inquire
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
					<a href={{route('terminal_in_note_inquire')}}>Terminal In Inquire</a>
                    <a href={{route('terminal_out_note_inquire')}}>Terminal Out Inquire</a>
                    <a href="#">Terminal Inquire</a>
                    <a href="#">Workflow Inquire </a>
                    <a href={{route('tmc_bin_inquire')}}>Tmc Bin Inquire</a>
                </div>
            </div>

            <div class="dropdown_mega_menu">
                <button class="dropbtn">
                    Delivery Process
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown_single_menu_content">
                    <a href={{route('delivery_order')}}>Delivery Order</a>
                    <a href={{route('delivery_list')}}>Delivery List - Scienter</a>
					<a href={{route('delivery_inquire')}}>Delivery Inquire</a>
                </div>
            </div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Hardware Operation <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>Tickets </h5>
							<a href=#>JobCard - Field Service</a>
							<a href={{route('jobcard')}}>JobCard - Card Center</a>
							<a href={{route('jobcard_setting')}}>JobCard Setting</a>
                            <a href={{route('jobcard_cancellation')}}>JobCard Cancellation</a>
						</div>

						<div class="column_three">
							<h5>Inquire </h5>
							<a href={{route('tmc_jobcard_inquire')}}>Job Card Inquire</a>
							<a href={{route('tmc_jobcard_multi_inquire')}}>Job Card Multi Inquire</a>
							<a href={{route('tmc_quotation_inquire')}}>Quotation Inquire</a>
							<a href={{route('tmc_quotation_multi_inquire')}}>Quotation Multi Inquire</a>
						</div>

						<div class="column_three">
							<h5>Reports </h5>
							<a href={{route('tmc_terminal_in_report')}}>Terminal In Report</a>
							<a href={{route('tmc_jobcard_report')}}>Job Card Report</a>
							<a href={{route('tmc_terminal_out_report')}}>Terminal Out Report</a>
							<a href=#>Quotation Report</a>
						</div>

					</div>

				</div>

			</div>

            <div class="dropdown_mega_menu">

				<button class="dropbtn">
					Backup Process <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>Note </h5>
							<a href={{route('backup_receive_note')}}>Backup Receive Note</a>
							<a href={{route('backup_remove_note')}}>Backup Remove Note</a>
						</div>

						<div class="column_three">
							<h5>Inquire </h5>
							<a href="#">Backup Terminal Inquire</a>
							<a href="#">Backup Receive Note Inquire</a>
							<a href={{route('backup_remove_note_inquire')}}>Backup Remove Note Inquire</a>
							<a href="#">Backup Issue Note Inquire</a>
						</div>

						<div class="column_three">
							<h5>Reports </h5>
							<a href={{route('brn_report')}}>Backup Remove Note Report</a>
							<a href={{route('backup_location_report')}}>Backup & Location Report</a>
						</div>

					</div>

				</div>

			</div>

			<div class="dropdown_mega_menu">

				<button class="dropbtn">
					Courier Process <i class="fa fa-caret-down"></i>
				</button>

	    		<div class="dropdown_mega_menu_content">

					<div class="row">

						<div class="column_three">
							<h5>List </h5>
							<a href=#>Courier In List</a>
							<a href=#>Courier Out List</a>
						</div>

						<div class="column_three">
							<h5>Inquire </h5>
							<a href={{route('courier_inquire')}}>Courier Inquire</a>
						</div>

						<div class="column_three">
							<h5>Report </h5>
							<a href=#>Courier In Report</a>
							<a href=#>Courier Out Report</a>
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
                    <a href="{{route('maintainance_dashboard')}}">Maintainance Module</a>
                    <a href="{{route('system_admin_dashboard')}}">System Admin Module</a>
                </div>
            </div>

			<div class="navbar_mega_menu_right_align">
                <div class="dropdown_mega_menu">
                	<button class="dropbtn" style="width: 100%;">
		                {{Auth::user()->name}}
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
