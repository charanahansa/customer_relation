@extends('layouts.hw')

@section('title')
	Jobcard Report
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Jobcard Report </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('jobcard_report_process')}}">

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
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Jobcard No.</label>
								<div class="col-sm-3">
									<input type="text" name="from_jobcard_no" id="from_jobcard_no" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Jobcard No.</label>
								<div class="col-sm-3">
									<input type="text" name="to_jobcard_no" id="to_jobcard_no" class="form-control form-control-sm" value="">
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
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Prod No.</label>
								<div class="col-sm-3">
									<input type="text" name="prod_number" id="prod_number" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">PTID</label>
								<div class="col-sm-3">
									<input type="text" name="ptid" id="ptid" class="form-control form-control-sm" value="">
								</div>
							</div>

                            <div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Rev No.</label>
								<div class="col-sm-3">
									<input type="text" name="revision_number" id="revision_number" class="form-control form-control-sm" value="">
								</div>
                                <label for="fromdate" class="col-sm-3 col-form-label-sm">User Negligent</label>
								<div class="col-sm-3">
									<select name="user_negligent" id="user_negligent" class="form-control form-control-sm">
                                        <option value =0>No</option>
                                        <option value =1>Yes</option>
                                    </select>
								</div>
							</div>

                            <div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Epic Warranty</label>
								<div class="col-sm-3">
									<select name="epic_warranty" id="epic_warranty" class="form-control form-control-sm">
                                        <option value =0>No</option>
                                        <option value =1>Yes</option>
                                    </select>
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">Seller Warranty</label>
								<div class="col-sm-3">
									<select name="seller_warranty" id="seller_warranty" class="form-control form-control-sm">
                                        <option value =0>No</option>
                                        <option value =1>Yes</option>
                                    </select>
								</div>
							</div>

							<div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Quotation</label>
								<div class="col-sm-3">
									<select name="quotation" id="quotation" class="form-control form-control-sm">
                                        <option value =0>No</option>
                                        <option value =1>Yes</option>
                                    </select>
								</div>
							</div>

						</div>

					</div>
					</div>

				</div>

				<!-- Bank -->
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
										@foreach($JR['bank'] as $banks)
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
										@foreach($JR['model'] as $models)
											<option value="{{$models->model}}"> {{$models->model}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Services -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Services
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="services[]" class="form-control form-control-sm" id="services" multiple style="height:300px; width:100%; " size="20" >
										@foreach($JR['services'] as $services)
											<option value="{{$services->service_id}}"> {{$services->service_name}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Status -->
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
										@foreach($JR['status'] as $status)
											<option value="{{$status->status_id}}"> {{$status->status_name}} </option>
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
										@foreach($JR['fault'] as $faults)
											<option value="{{$faults->fault_id}}"> {{$faults->fault_name}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

                 <!-- Spare Part -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Add Spare Part
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="spare_part[]" class="form-control form-control-sm" id="spare_part" multiple style="height:300px; width:100%; " size="20" >
										@foreach($JR['spare_part'] as $spare_parts)
											<option value="{{$spare_parts->spare_part_id}}"> {{$spare_parts->spare_part_name}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

                <!-- Spare Part Usage -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Spare Part Usage
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="spare_part_usage[]" class="form-control form-control-sm" id="spare_part_usage" multiple style="height:300px; width:100%; " size="20" >
										@foreach($JR['spare_part'] as $spare_parts)
											<option value="{{$spare_parts->spare_part_id}}"> {{$spare_parts->spare_part_name}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

                <!-- Removed Spare Part -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Remove Spare Part
						</div>

						<div class="card-body" >

							<div class="form-row">
								<div class="col-sm-12">
									<select  name="spare_part_removed[]" class="form-control form-control-sm" id="spare_part_removed" multiple style="height:300px; width:100%; " size="20" >
										@foreach($JR['spare_part'] as $spare_parts)
											<option value="{{$spare_parts->spare_part_id}}"> {{$spare_parts->spare_part_name}} </option>
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


