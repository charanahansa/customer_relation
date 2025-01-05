@extends('layouts.mng')

@section('title')
    Profile Conversion Report
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Profile Conversion Workflow Report </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('profile_conversion_report_process')}}">

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
								<label for="fromdate" class="col-sm-3 col-form-label-sm">FROM From TID</label>
								<div class="col-sm-3">
									<input type="text" name="from_from_tid" id="from_from_tid" class="form-control form-control-sm" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">FROM To TID</label>
								<div class="col-sm-3">
									<input type="text" name="from_to_tid" id="from_to_tid" class="form-control form-control-sm" value="">
								</div>	
							</div>

                            <div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">TO From TID</label>
								<div class="col-sm-3">
									<input type="text" name="to_from_tid" id="to_from_tid" class="form-control form-control-sm" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">TO To TID</label>
								<div class="col-sm-3">
									<input type="text" name="to_to_tid" id="to_to_tid" class="form-control form-control-sm" value="">
								</div>	
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Merchant</label>
								<div class="col-sm-9">
									<input type="text" name="merchant" id="merchant" class="form-control form-control-sm" value="">
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
										@foreach($PC['bank'] as $banks)
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
							From Model
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="from_model[]" class="form-control form-control-sm" id="from_model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($PC['model'] as $models)
											<option value="{{$models->model}}"> {{$models->model}} </option>
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
							To Model
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="to_model[]" class="form-control form-control-sm" id="to_model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($PC['model'] as $models)
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
							From Application
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="from_application[]" class="form-control form-control-sm" id="application" multiple style="height:300px; width:100%; " size="20" >
										@foreach($PC['from_applications'] as $app)
											<option value="{{$app->from_application}}"> {{$app->from_application}} </option>
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
							To Application
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="to_application[]" class="form-control form-control-sm" id="application" multiple style="height:300px; width:100%; " size="20" >
										@foreach($PC['to_applications'] as $app)
											<option value="{{$app->to_application}}"> {{$app->to_application}} </option>
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
										@foreach($PC['officers'] as $officers)
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
							Status
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="status[]" class="form-control form-control-sm" id="status" multiple style="height:300px; width:100%; " size="20" >
										@foreach($PC['status'] as $status)
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


