<!DOCTYPE html>
<html>
<head>
	<title> Spare Part Request List </title>

	<link rel="stylesheet" href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css>

	<!-- Bootstrap 4.6 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href=".\asset\date_time_picker\jquery.datetimepicker.min.css">
    <script type="text/javascript" src=".\asset\date_time_picker\jquery.js"></script>
    <script type="text/javascript" src=".\asset\date_time_picker\jquery.datetimepicker.full.js"></script>

</head>
<body>
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%; background-color: gray;">
	<form method="POST" action="{{route('spare_part_request_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Spare Part Request
				</div>

				<div class="card-body">

					<div class="row no-gutters">

                        <div class="col-12 col-sm-6 col-md-6" >
                        <div style="margin-left: 1%; margin-right: 2%;">

							<?php echo $SPR['attributes']['process_message'] ?>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-6 col-form-label-sm"></label>
		                        <label for="tid" class="col-sm-2 col-form-label-sm">SP Issue ID</label>
		                        <div class="col-sm-4">
		                            <input type="text" name="issue_id" id="issue_id" class="form-control form-control-sm" value="{{$SPR['attributes']['issue_id']}}" readonly>
		                        </div>
		                    </div>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Request Id</label>
								<div class="col-sm-4">
		                            <input type="text" name="request_id" id="request_id" class="form-control form-control-sm" value="{{$SPR['attributes']['request_id']}}" readonly>
		                        </div>
		                        <label for="tid" class="col-sm-2 col-form-label-sm">Issue Date</label>
		                        <div class="col-sm-4">
		                            <input type="text" name="issue_date" id="issue_date" class="form-control form-control-sm" value="{{$SPR['attributes']['issue_date']}}" readonly>
		                        </div>
		                    </div>

							<div class="mb-1 row">
		                        <label for="tid" class="col-sm-2 col-form-label-sm">Spare Part</label>
		                        <div class="col-sm-10">
		                            <select name="spare_part" id="spare_part" class="form-control form-control-sm">
		                                @foreach($SPR['spare_part'] as $row)
		                                    @if($SPR['attributes']['spare_part'] == $row->part_id)
		                                        <option value ="{{$row->part_id}}" selected>{{$row->part_name}}</option>
		                                    @else
		                                        <option value ="{{$row->part_id}}">{{$row->part_name}}</option>
		                                    @endif
		                                @endforeach
		                                @if($SPR['attributes']['spare_part']== 0)
		                                        <option value ="0" selected>Select the Spare Part</option>
		                                @endif
		                            </select>
		                            @if($SPR['attributes']['validation_messages']->has('spare_part'))
		                                <script>
		                                        document.getElementById('spare_part').className = 'form-select form-select-sm is-invalid';
		                                </script>
		                                <div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("spare_part") }}</div>
		                            @endif
		                        </div>
		                    </div>

							<div class="mb-1 row">
		                        <label for="tid" class="col-sm-2 col-form-label-sm">Spare Part Bin</label>
		                        <div class="col-sm-10">
		                            <select name="spare_part_bin" id="spare_part_bin" class="form-control form-control-sm">
										@if(count($SPR['spare_part_bin']) >= 1)
											@foreach($SPR['spare_part_bin'] as $row)
												@if($SPR['attributes']['spare_part_bin'] == $row->No)
													<option value ="{{$row->No}}" selected>{{$row->description}}</option>
												@else
													<option value ="{{$row->No}}">{{$row->description}}</option>
												@endif
											@endforeach
										@else
											<option value ="0">Out Of Stock</option>
										@endif
										@if($SPR['attributes']['spare_part_bin']== 0)
											<option value ="0" selected>Select the Spare Part Bin</option>
		                                @endif
		                            </select>
		                        </div>
		                    </div>

							<div class="mb-1 row">
		                        <label for="tid" class="col-sm-2 col-form-label-sm">Get Old Part</label>
		                        <div class="col-sm-10">
		                            <select name="got_old_part" id="got_old_part" class="form-control form-control-sm">
										@if($SPR['attributes']['got_old_part'] == 1)
											<option value ="1" selected>Yes</option>
											<option value ="0">No</option>
										@else
											<option value ="1">Yes</option>
											<option value ="0" selected>No</option>
										@endif
		                            </select>
		                        </div>
		                    </div>



							<div class="mb-3 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Remark</label>
                                <div class="col-sm-10">
                                    <textarea  name="remark" id="remark" class="form-control" rows="2" style="resize:none">{{$SPR['attributes']['remark']}}</textarea>
									@if($SPR['attributes']['validation_messages']->has('remark'))
		                                <script>
		                                        document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
		                                </script>
		                                <div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("remark") }}</div>
		                            @endif
                                </div>
                            </div>

							<div class="mb-2 row">
								<div class="col-sm-3">
									<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-success btn-sm" value="Issue">
								</div>
								<div class="col-sm-3">
									<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-danger btn-sm" value="Reject">
								</div>
                                <div class="col-sm-3">
									<input type="button" name="button" id="button" style="width: 100%;" class="btn btn-secondary btn-sm" value="Close" onclick="javascript:window.close('','_parent','');">
								</div>
							</div>

						</div>
						</div>

                        <div class="col-12 col-sm-6 col-md-6" >
                        <div style="margin-left: 1%; margin-right: 2%;">

                            <div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Serial No.</label>
								<div class="col-sm-4">
		                            <input type="text" name="serial_number" id="serial_number" class="form-control form-control-sm" value="{{$SPR['attributes']['serial_number']}}" readonly>
		                        </div>
		                        <label for="tid" class="col-sm-2 col-form-label-sm">Ticket No.</label>
		                        <div class="col-sm-4">
		                            <input type="text" name="ticket_no" id="ticket_no" class="form-control form-control-sm" value="{{$SPR['attributes']['ticket_no']}}" readonly>
		                        </div>
		                    </div>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
								<div class="col-sm-4">
		                            <input type="text" name="model" id="model" class="form-control form-control-sm" value="{{$SPR['attributes']['model']}}" readonly>
		                        </div>
		                        <label for="tid" class="col-sm-2 col-form-label-sm">Ticket Date</label>
		                        <div class="col-sm-4">
		                            <input type="text" name="ticket_date" id="ticket_date" class="form-control form-control-sm" value="{{$SPR['attributes']['ticket_date']}}" readonly>
		                        </div>
		                    </div>

                            <div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Fault</label>
								<div class="col-sm-10">
		                            <input type="text" name="fault" id="fault" class="form-control form-control-sm" value="{{$SPR['attributes']['fault']}}" readonly>
		                        </div>
		                    </div>

							<div class="mb-2 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Merchant</label>
                                <div class="col-sm-10">
                                    <textarea  name="merchant" id="merchant" class="form-control" rows="2" style="resize:none" readonly>{{$SPR['attributes']['merchant']}}</textarea>
                                </div>
                            </div>


                        </div>
                        </div>

					</div>

				</div>

			</div>

		</div>

	</form>
</div>

</body>
</html>
