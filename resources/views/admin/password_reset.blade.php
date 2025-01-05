@extends('layouts.mng')

@section('title')
    Password Reset
@endsection

@section('body')

	<div id="tbldiv" style="width: 100%; padding-left: 1%; padding-top: 1%; padding-right: 1%; padding-bottom: 1%; ">

		<div class="card">
			<div class="card-header">
				Password Reset Process
			</div>
			<div class="card-body">

				<form method="POST" name="form_name" action="{{route('password_reset_process')}}" style = " margin-left: auto; margin-right: auto; text-align: left;">

					@csrf
					
					<?php echo $pr['attributes']['process_message'] ?> 

					<div class="mb-1 row">
						<label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
						<label for="staticEmail" class="col-sm-2 col-form-label">{{Auth::user()->email}}</label>
					</div>
					
					<div class="mb-1 row">
						<label for="inputPassword" class="col-sm-2 col-form-label">Current Password</label>
						<div class="col-sm-3">
							<input type="password" name="current_password" id="current_password" class="form-control form-control-sm">
							@if($pr['attributes']['error_message']->has('current_password'))
								<script>
									document.getElementById('current_password').className += ' form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $pr['attributes']['error_message']->first("current_password") }}</div>
							@endif
						</div>
					</div>

					<div class="mb-1 row">
						<label for="inputPassword" class="col-sm-2 col-form-label">New Password</label>
						<div class="col-sm-3">
							<input type="password" name="new_password" id="new_password" class="form-control form-control-sm">
							@if($pr['attributes']['error_message']->has('new_password'))
								<script>
									document.getElementById('new_password').className += ' form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $pr['attributes']['error_message']->first("new_password") }}</div>
							@endif
						</div>
					</div>

					<div class="mb-4 row">
						<label for="inputPassword" class="col-sm-2 col-form-label">Confirm Password</label>
						<div class="col-sm-3">
							<input type="password" name="again_password" id="again_password" class="form-control form-control-sm">
							@if($pr['attributes']['error_message']->has('again_password'))
								<script>
									document.getElementById('again_password').className += ' form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $pr['attributes']['error_message']->first("again_password") }}</div>
							@endif
						</div>
					</div>

					<div class="mb-1 row">
						<div class="col-sm-5">
							<input type="submit" name="submit" id="submit" class="btn btn-sm btn-primary mb-3" style="width: 100%;" value="Save">
						</div>
					</div>

				</form>

			</div>
		</div>

	</div>

@endsection