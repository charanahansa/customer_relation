<!DOCTYPE html>
<html>
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

<style>

	body {
	  background: #0072B5;
	}

	.content {

		border-radius: 30px;
		max-width: 60%;
		margin: auto;
		background: white;
		padding: 10px;
		height: 325px;
	}

	.content2 {

		border-radius: 15px;
		max-width: 60%;
		margin: auto;
		background: white;
		padding: 10px;
		height: 165px;
	}

	.content3 {

		background: #0072B5;
		height: 50px;
	}

</style>
</head>
<body>

	<div class="content3">

	</div>
	
	<div class="content">

		<div style="float: left; width: 50%;">
			<img src="asset/images/epic-lanka-logo.png" height="270" width="300">
		</div>

		<div style="float: left; width: 50%;">

			<h2>Sign in to start your session </h2><br>

			@if ($errors->any())
				@foreach ($errors->all() as $error)
	                <h5 style="color: red;">{{ $error }}</h5>
	            @endforeach
			@else
				<h5></h5>
			@endif

			<form method="POST" action="{{route('login')}}" >

				@csrf

				<div class="form-row">
					<label for="fromdate" class="col-sm-4 col-form-label-sm">Email</label>
					<div class="col-sm-8">
						<input type="email" name="email" id="email" class="form-control form-control-sm"  value="" required>
					</div>
				</div>

				<div class="form-row">
					<label for="fromdate" class="col-sm-4 col-form-label-sm">Password</label>
					<div class="col-sm-8">
						<input type="password" name="password" id="password" class="form-control form-control-sm"  value="">
					</div>
				</div>

				<br>
				<div class="form-row">
					<div class="col-sm-8">
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
				</div>

			</form>
			
		</div>

	</div>
	
	<div class="content3">

	</div>

	<div class="content2">

		<div class="table-responsive">

			<table class='table table-hover table-sm table-bordered' style=" border: 2px solid black;">
				<tr style=" border: 2px solid black;">
					<td>Application Name</td>
					<td>Technical Job Ticketing System</td>
				</tr>
				<tr style=" border: 2px solid black;">
					<td>Version No.</td>
					<td>1.1</td>
				</tr>
				<tr style=" border: 2px solid black;">
					<td>Approved By</td>
					<td>-</td>
				</tr>
				<tr style=" border: 2px solid black;">
					<td>Reviewed By</td>
					<td>-</td>
				</tr>
			</table>

		</div>

	</div>

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

​

