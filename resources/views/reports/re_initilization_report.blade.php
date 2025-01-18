@extends('layouts.mng')

@section('title')
    Re Initilization Report
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Re Initilization Workflow Report </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('re_initilization_report_process')}}">

        @csrf

		<div id="carouselExampleIndicators" class="carousel slide" >

			<div class="carousel-inner">

				<!-- Basic Information -->
				<div class="carousel-item active">

					<div class="container col-md-7">
					<div class="card" >

						<div class="card-header">
							Basic Information
						</div>

						<div class="card-body" >

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Date</label>
								<div class="col-sm-3">
									<input type="text" name="from_date" class="form-control form-control-sm" id="from_date" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To Date</label>
								<div class="col-sm-3">
									<input type="text" name="to_date" class="form-control form-control-sm" id="to_date" value="">
								</div>
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From TID</label>
								<div class="col-sm-3">
									<input type="text" name="from_tid" id="from_tid" class="form-control form-control-sm" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To TID</label>
								<div class="col-sm-3">
									<input type="text" name="to_tid" id="to_tid" class="form-control form-control-sm" value="">
								</div>
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="from_serialno" id="from_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="to_serialno" id="to_serialno" class="form-control form-control-sm" value="">
								</div>
							</div>

							<div class="form-row">
								<label for="tid" class="col-sm-3 col-form-label-sm">Type</label>
								<div class="col-sm-9">
									<select name="type" class="form-control form-control-sm" id="type">
										<option value='phone'>Over the Phone</option>
										<option value='visit'>Visit</option>
										<option value='Not' selected>No Type</option>
									</select>
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

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="bank[]" class="form-control form-control-sm" id="bank" multiple style="height:300px; width:100%; " size="20" >
										@foreach($New['bank'] as $banks)
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

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="model[]" class="form-control form-control-sm" id="model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($New['model'] as $models)
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
							Officer
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="officer[]" class="form-control form-control-sm" id="officer" multiple style="height:300px; width:100%; " size="20" >
										@foreach($New['officers'] as $officers)
											<option value="{{$officers->ID}}"> {{$officers->officer_name}} </option>
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
							Sub Status
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="sub_status[]" class="form-control form-control-sm" id="sub_status" multiple style="height:300px; width:100%; " size="20" >
										@foreach($New['sub_status'] as $sub_status)
											<option value="{{$sub_status->id}}"> {{$sub_status->status}} </option>
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
							Status
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="status[]" class="form-control form-control-sm" id="status" multiple style="height:300px; width:100%; " size="20" >
										@foreach($New['status'] as $status)
											<option value="{{$status->codeid}}"> {{$status->description}} </option>
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
							Tables
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="tables[]" class="form-control form-control-sm" id="tables" multiple style="height:300px; width:100%; " size="20" >
										<option value='re_initialization' selected>Re Initialization</option>
										<option value='re_initialization_ftl_view'>Re Initialization Field Service Team Lead</option>
										<option value='re_initialization_tmc_view'>Re Initialization Tmc</option>
										<option value='re_initialization_tp_view'>Re Initialization Terminal Programmer</option>
										<option value='re_initialization_fs_view'>Re Initialization Field Service Officer</option>
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

							<div class="form-row">

								<div class="col-sm-12">
									<input type="submit" name="submit" id="submit" value="Generate" style="width: 100%" class="btn btn-success">
								</div>

							</div>
						</div>

					</div>
					</div>

			    </div>

			</div>

			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black;"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black;"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>

	</form>



    <script>

        $("#from_date").datetimepicker(
            {
                format:'Y/m/d'
            }
        );

        $("#to_date").datetimepicker(
            {
                format:'Y/m/d'
            }
        );

    </script>

@endsection


