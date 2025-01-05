@extends('layouts.mng')

@section('title')
    Software Updating Report
@endsection

@section('body')

	<br>
    <h5 style="width: 100%; text-align: center;"> Software Updating Workflow Report </h5>
	<br>

	<form method="POST" name="form_name" action="{{route('software_updation_report_process')}}">

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
								<label for="fromdate" class="col-sm-3 col-form-label-sm">OS</label>
								<div class="col-sm-3">
									<input type="text" name="os" id="os" class="form-control form-control-sm" value="">
								</div>
                                <label for="fromdate" class="col-sm-3 col-form-label-sm">EOS</label>
								<div class="col-sm-3">
									<input type="text" name="eos" id="eos" class="form-control form-control-sm" value="">
								</div>
							</div>

                            <div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">Firmware</label>
								<div class="col-sm-3">
									<input type="text" name="firmware" id="firmware" class="form-control form-control-sm" value="">
								</div>
                                <label for="fromdate" class="col-sm-3 col-form-label-sm">Application</label>
								<div class="col-sm-3">
									<input type="text" name="application" id="application" class="form-control form-control-sm" value="">
								</div>
							</div>

                            <div class="form-row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">App Version</label>
								<div class="col-sm-3">
									<input type="text" name="app_version" id="app_version" class="form-control form-control-sm" value="">
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
										@foreach($SU['bank'] as $banks)
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
										@foreach($SU['model'] as $models)
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
										@foreach($SU['officers'] as $officers)
											<option value="{{$officers->officer_id}}"> {{$officers->name}} </option>
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
										@foreach($SU['sub_status'] as $sub_status)
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
										@foreach($SU['status'] as $status)
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
										<option value='software_updating' selected>Software Updating</option>
										<option value='software_updating_ftl_view'>Software Updating Field Service Team Lead</option>
										<option value='software_updating_tmc_view'>Software Updating Tmc</option>
										<option value='software_updating_tp_view'>Software Updating Terminal Programmer</option>
										<option value='software_updating_fs_view'>Software Updating Field Service Officer</option>
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


