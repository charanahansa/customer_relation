@extends('layouts.mng')

@section('title')
    Base Software Installation Report
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Base Software Installation Workflow Report - Under Construction </h5>
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
										@foreach($BSI['bank'] as $banks)
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
										@foreach($BSI['model'] as $models)
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
							Status
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="status[]" class="form-control form-control-sm" id="status" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BSI['status'] as $status)
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
										<option value='base_software_install'>Base Software Install</option>
										<option value='base_software_install_ftl_view'>Base Software Install Field Service Team Lead</option>
										<option value='base_software_install_fs_view'>Base Software Install Field Service Officer</option>
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


