@extends('layouts.tmc')

@section('title')
    Backup Removal Workflow Report
@endsection

@section('body')

    <div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" name="form_name" action="{{route('backup_removal_report_process')}}">

        <br>
            <h5 style="width: 100%; text-align: center;"> Backup Removal Workflow Report </h5>
	    <br>

        @csrf

		<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" >

			<div class="carousel-inner">

				<!-- Basic Information -->
				<div class="carousel-item active">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Basic Information
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Date</label>
								<div class="col-sm-3">
									<input type="text" name="from_date" class="form-control form-control-sm" id="from_date" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To Date</label>
								<div class="col-sm-3">
									<input type="text" name="to_date" class="form-control form-control-sm" id="to_date" value="">
								</div>
							</div>

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From TID</label>
								<div class="col-sm-3">
									<input type="text" name="from_tid" id="from_tid" class="form-control form-control-sm" value="">
								</div>
								<label for="todate" class="col-sm-3 col-form-label-sm">To TID</label>
								<div class="col-sm-3">
									<input type="text" name="to_tid" id="to_tid" class="form-control form-control-sm" value="">
								</div>
							</div>

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Backup Serial No.</label>
								<div class="col-sm-3">
									<input type="text" name="from_backup_serialno" id="from_backup_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Backup Serial No.</label>
								<div class="col-sm-3">
									<input type="text" name="to_backup_serialno" id="to_backup_serialno" class="form-control form-control-sm" value="">
								</div>
							</div>

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-3 col-form-label-sm">From Replaced Serial No.</label>
								<div class="col-sm-3">
									<input type="text" name="from_replaced_serialno" id="from_replaced_serialno" class="form-control form-control-sm" value="">
								</div>

								<label for="todate" class="col-sm-3 col-form-label-sm">To Replaced Serial No.</label>
								<div class="col-sm-3">
									<input type="text" name="to_replaced_serialno" id="to_replaced_serialno" class="form-control form-control-sm" value="">
								</div>
							</div>

							<div class="mb-2 row">
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

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="bank[]" class="form-select form-select-sm" id="bank" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BR['bank'] as $banks)
											<option value="{{$banks->bank}}"> {{$banks->bank}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Backup Model -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Backup Model
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="backup_model[]" class="form-select form-select-sm" id="backup_model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BR['model'] as $models)
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

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="replaced_model[]" class="form-select form-select-sm" id="replaced_model" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BR['model'] as $models)
											<option value="{{$models->model}}"> {{$models->model}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Field Officers -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Field Officers
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="officer[]" class="form-select form-select-sm" id="officer" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BR['officers'] as $officers)
											<option value="{{$officers->officer_id}}"> {{$officers->name}} </option>
										@endforeach
									</select>
								</div>

							</div>
						</div>

					</div>
					</div>
				</div>

				<!-- Sub Status -->
				<div class="carousel-item">

					<div class="container col-md-8">
					<div class="card" >

						<div class="card-header">
							Sub Status
						</div>

						<div class="card-body" >

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="sub_status[]" class="form-select form-select-sm" id="sub_status" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BR['sub_status'] as $row)
											<option value="{{$row->id}}"> {{$row->status}} </option>
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

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="status[]" class="form-select form-select-sm" id="status" multiple style="height:300px; width:100%; " size="20" >
										@foreach($BR['status'] as $status)
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

							<div class="mb-2 row">
								<div class="col-sm-12">
									<select  name="tables[]" class="form-select form-select-sm" id="tables" multiple style="height:300px; width:100%; " size="20" >
										<option value='backup_remove' selected>Backup Remove</option>
										<option value='backup_remove_ftl_view'>Backup Remove Field Service Team Lead</option>
										<option value='backup_remove_tmc_view'>Backup Remove Tmc</option>
										<option value='backup_remove_tp_view'>Backup Remove Terminal Programmer</option>
										<option value='backup_remove_fs_view'>Backup Remove Field Service Officer</option>
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
    </div>



@endsection


