@extends('layouts.mng')

@section('title')
    Breakdown Dashboard
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Breakdown Workflow Report </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('breakdown_report_process')}}">

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
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Fault Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="from_fault_serialno" id="from_fault_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Fault Serial No</label>
								<div class="col-sm-3">
									<input type="text" name="to_fault_serialno" id="to_fault_serialno" class="form-control form-control-sm" value="">
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
										@foreach($breakdown['bank'] as $banks)
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
										@foreach($breakdown['model'] as $models)
											<option value="{{$models->model}}"> {{$models->model}} </option>
										@endforeach		
									</select>
								</div>
								
							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Fault -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Fault
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="fault[]" class="form-control form-control-sm" id="fault" multiple style="height:300px; width:100%; " size="20" >
										@foreach($breakdown['fault'] as $faults)
											<option value="{{$faults->eno}}"> {{$faults->error}} </option>
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
							Relevant Detals
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="relevant_detail[]" class="form-control form-control-sm" id="relevant_detail" multiple style="height:300px; width:100%; " size="20" >
										@foreach($breakdown['relevant_detail'] as $relevant_details)
											<option value="{{$relevant_details->rno}}"> {{$relevant_details->relevant_detail}} </option>
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
							Action Taken
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="action_taken[]" class="form-control form-control-sm" id="action_taken" multiple style="height:300px; width:100%; " size="20" >
										@foreach($breakdown['action_taken'] as $action_taken)
											<option value="{{$action_taken->ano}}"> {{$action_taken->action_taken}} </option>
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
										@foreach($breakdown['officers'] as $officers)
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
										@foreach($breakdown['sub_status'] as $sub_status)
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
										@foreach($breakdown['status'] as $status)
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
										<option value='breakdown' selected>Breakdown</option>
										<option value='breakdown_ftl_view'>Breakdown Field Service Team Lead</option>
										<option value='breakdown_tmc_view'>Breakdown Tmc</option>
										<option value='breakdown_tp_view'>Breakdown Terminal Programmer</option> 
										<option value='breakdown_fs_view'>Breakdown Field Service Officer</option>
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


