@extends('layouts.tmc')

@section('title')
    Job Card Multi Inquire
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('jobcard_multi_inquire_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Job Card Multi Inquire
			</div>

			<div class="card-body">

				<div class="col-sm-12">

				</div>

				<div class="row no-gutters">

					<div class="col-12 col-sm-8 col-md-8">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-2 row">
							<div class="col-sm-8">
								<select name="search_parameter" id="search_parameter" class="form-select form-select-sm">
									<option value ="job_card">Job Card No.</option>
								</select>
							</div>
							<div class="col-sm-4">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-success btn-sm" value="Excel">
							</div>
						</div>

						<div class="mb-2 row">
							<div class="col-sm-12">
								<textarea name="jobcard_numbers" class="form-control form-control-sm" id="jobcard_numbers" style="resize:none" col="7" rows="20"></textarea>
							</div>
						</div>

					</div>
					</div>

					<div class="col-12 col-sm-4 col-md-4">
					<div style="margin-left: 2%; margin-right: 1%;">



					</div>
					</div>

				</div>

			</div>
		</div>

	</div>

</form>
</div>



@endsection
