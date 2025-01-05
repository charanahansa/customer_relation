@extends('layouts.tmc')

@section('title')
    Terminal In Report
@endsection

@section('body')


	<br>
    <h5 style="width: 100%; text-align: center;"> Terminal In Report </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('terminal_in_report_process')}}">

        @csrf

		<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">

			<div class="carousel-inner">

				<!-- Basic Information -->
				<div class="carousel-item active">

					<div class="container col-md-7">
					<div class="card" >

						<div class="card-header">
							Basic Information
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Date</label>
								<div class="col-sm-3">
									<input type="date" name="from_date" class="form-control form-control-sm" id="from_date" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To Date</label>
								<div class="col-sm-3">
									<input type="date" name="to_date" class="form-control form-control-sm" id="to_date" value="">
								</div>
							</div>

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="from_serialno" id="from_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="to_serialno" id="to_serialno" class="form-control form-control-sm" value="">
								</div>
							</div>

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Jobcard No</label>
								<div class="col-sm-3">
									<input type="text" name="from_jobcardno" id="from_jobcardno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Jobcard No</label>
								<div class="col-sm-3">
									<input type="text" name="to_jobcardno" id="to_jobcardno" class="form-control form-control-sm" value="">
								</div>
							</div>

						</div>

					</div>
					</div>

				</div>

				<!-- bank -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Bank
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="bank[]" class="form-select form-select-sm" id="bank" multiple style="height:300px; width:100%; " size="20" >
										@foreach($tip['bank'] as $banks)
											<option value="{{$banks->bank}}"> {{$banks->bank}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Model -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Model
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="model[]" class="form-select form-select-sm" id="model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($tip['model'] as $models)
											<option value="{{$models->model}}"> {{$models->model}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Generate
						</div>

						<div class="card-body" >

							<div class="mb-2 row">

								<div class="col-sm-12">
									<input type="submit" name="submit" id="submit" value="Generate" style="width: 100%" class="btn btn-success">
								</div>

							</div>
						</div>

					</div>
					</div>

			    </div>
			</div>



            <button class="carousel-control-prev btn-dark" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next btn-dark" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>


		</div>

	</form>

@endsection


