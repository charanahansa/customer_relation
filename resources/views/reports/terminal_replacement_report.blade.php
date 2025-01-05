@extends('layouts.mng')

@section('title')
    Terminal Replacement Report
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Terminal Replacement Workflow Report  - Under Construction </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('terminal_replacement_report_process')}}">

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
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Based TID</label>
								<div class="col-sm-3">
									<input type="text" name="from_based_tid" id="from_based_tid" class="form-control form-control-sm" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To Based TID</label>
								<div class="col-sm-3">
									<input type="text" name="to_based_tid" id="to_based_tid" class="form-control form-control-sm" value="">
								</div>	
							</div>	

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Replaced TID</label>
								<div class="col-sm-3">
									<input type="text" name="from_replaced_tid" id="from_replaced_tid" class="form-control form-control-sm" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To Replaced TID</label>
								<div class="col-sm-3">
									<input type="text" name="to_replaced_tid" id="to_replaced_tid" class="form-control form-control-sm" value="">
								</div>	
							</div>	

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Based Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="from_based_serialno" id="from_based_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Based Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="to_based_serialno" id="to_based_serialno" class="form-control form-control-sm" value="">
								</div>	
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Replaced Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="from_replaced_serialno" id="from_replaced_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Replaced Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="to_replaced_serialno" id="to_replaced_serialno" class="form-control form-control-sm" value="">
								</div>	
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Merchant</label>
								<div class="col-sm-9">
									<input type="text" name="merchant" id="merchant" class="form-control form-control-sm" value="">
								</div>	
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Contact No.</label>
								<div class="col-sm-9">
									<input type="text" name="contact_number" id="contact_number" class="form-control form-control-sm" value="">
								</div>	
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Sim No.</label>
								<div class="col-sm-9">
									<input type="text" name="sim_number" id="sim_number" class="form-control form-control-sm" value="">
								</div>	
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Pod No.</label>
								<div class="col-sm-9">
									<input type="text" name="pod_number" id="pod_number" class="form-control form-control-sm" value="">
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
										@foreach($TR['bank'] as $banks)
											<option value="{{$banks->bank}}"> {{$banks->bank}} </option>
										@endforeach		
									</select>
								</div>
								
							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Based Model -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Based Model
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="based_model[]" class="form-control form-control-sm" id="based_model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($TR['model'] as $models)
											<option value="{{$models->model}}"> {{$models->model}} </option>
										@endforeach		
									</select>
								</div>
								
							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Replaced Model -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Replaced Model
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="replaced_model[]" class="form-control form-control-sm" id="replaced_model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($TR['model'] as $models)
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
										@foreach($TR['officers'] as $officers)
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
										@foreach($TR['sub_status'] as $sub_status)
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
										@foreach($TR['status'] as $status)
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
										<option value='terminal_replacement' selected>Terminal Replacement</option>
										<option value='terminal_replacement_ftl_view'>Terminal Replacement Field Service Team Lead</option>
										<option value='terminal_replacement_tmc_view'>Terminal Replacement Tmc</option>
										<option value='terminal_replacement_tp_view'>Terminal Replacement Terminal Programmer</option> 
										<option value='terminal_replacement_fs_view'>Terminal Replacement Field Service Officer</option>
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


