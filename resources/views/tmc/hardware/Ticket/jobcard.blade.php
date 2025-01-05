@extends('layouts.tmc')

@section('title')
    Job Card - Card Center
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('jobcard_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Job Card - Card Center
				</div>

				<div class="card-body">

					<div class="col-sm-12">
						<?php echo $JC['attributes']['process_message']; ?>
					</div>

					<div class="row no-gutters">

						<div class="col-12 col-sm-6 col-md-6">
						<div style="margin-left: 2%; margin-right: 1%;">

							<div class="mb-1 row">
								<label for="tid" class="col-sm-6 col-form-label-sm"></label>
								<label for="tid" class="col-sm-2 col-form-label-sm">Ticket No.</label>
								<div class="col-sm-4">
									<input type="text" name="ticket_number" class="form-control form-control-sm" id="ticket_number" value="{{$JC['attributes']['ticket_number']}}" readonly>
									@if($JC['attributes']['validation_messages']->has('ticket_number'))
										<script>
												document.getElementById('ticket_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("ticket_number") }}</div>
									@endif
								</div>
							</div>

							<div class="mb-1 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Lot No.</label>
								<div class="col-sm-4">
									<input type="text" name="lot_number" class="form-control form-control-sm" id="lot_number" value="{{$JC['attributes']['lot_number']}}">
									@if($JC['attributes']['validation_messages']->has('Lot Number'))
										<script>
												document.getElementById('lot_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Lot Number") }}</div>
									@endif
								</div>

								<label for="tid" class="col-sm-2 col-form-label-sm">Jobcard No.</label>
								<div class="col-sm-4">
									<input type="text" name="jobcard_number" class="form-control form-control-sm" id="jobcard_number" value="{{$JC['attributes']['jobcard_number']}}" readonly>
									@if($JC['attributes']['validation_messages']->has('jobcard_number'))
										<script>
												document.getElementById('jobcard_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("jobcard_number") }}</div>
									@endif
								</div>

							</div>

							<div class="mb-1 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
		                        <div class="col-sm-4">
		                            <select name="bank" id="bank" class="form-select form-select-sm">
		                                @foreach($JC['bank'] as $row)
		                                    @if($JC['attributes']['bank'] == $row->bank)
		                                        <option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
		                                    @else
		                                        <option value ="{{$row->bank}}">{{$row->bank}}</option>
		                                    @endif
		                                @endforeach
		                                @if($JC['attributes']['bank']== "0")
		                                    <option value ="0" selected>Select the Bank</option>
		                                @endif
		                            </select>
									@if($JC['attributes']['validation_messages']->has('Bank'))
										<script>
												document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Bank") }}</div>
									@endif
		                        </div>

								<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
								<div class="col-sm-4">
									<input type="text" name="ticket_date" id="ticket_date" class="form-control form-control-sm" value="{{$JC['attributes']['ticket_date']}}" readonly>
									@if($JC['attributes']['validation_messages']->has('Date'))
										<script>
												document.getElementById('ticket_date').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Date") }}</div>
									@endif
								</div>

							</div>

							<div class="mb-2 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Serial No.</label>
								<div class="col-sm-4">
									<input type="text" name="serial_number" id="serial_number" class="form-control form-control-sm" value="{{$JC['attributes']['serial_number']}}">
									@if($JC['attributes']['validation_messages']->has('Serial Number'))
										<script>
												document.getElementById('serial_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Serial Number") }}</div>
									@endif
		                        </div>

		                        <label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
		                        <div class="col-sm-4">
		                            <select name="model" id="model" class="form-select form-select-sm">
		                                @foreach($JC['model'] as $row)
		                                    @if($JC['attributes']['model'] == $row->model)
		                                        <option value ="{{$row->model}}" selected>{{$row->model}}</option>
		                                    @else
		                                        <option value ="{{$row->model}}">{{$row->model}}</option>
		                                    @endif
		                                @endforeach
		                                @if($JC['attributes']['model'] == "0")
		                                    <option value ="0" selected>Select the Model</option>
		                                @endif
		                            </select>
									@if($JC['attributes']['validation_messages']->has('Model'))
										<script>
												document.getElementById('model').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Model") }}</div>
									@endif
		                        </div>

		                    </div>

                            <div class="mb-2 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Box No.</label>
								<div class="col-sm-4">
									<input type="text" name="box_number" id="box_number" class="form-control form-control-sm" value="{{$JC['attributes']['box_number']}}">
									@if($JC['attributes']['validation_messages']->has('Box Number'))
										<script>
												document.getElementById('box_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Box Number") }}</div>
									@endif
		                        </div>

                            </div>

							<div class="mb-1 row">
								<label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
								<div class="col-sm-10">
									<textarea name="remark" class="form-control form-control-sm" id="remark" style="resize:none" rows="2">{{$JC['attributes']['remark']}}</textarea>
									@if($JC['attributes']['validation_messages']->has('remark'))
										<script>
												document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("remark") }}</div>
									@endif
								</div>
							</div>

							<br>
							<hr>
							<div class="mb-2 row">

								<div class="col-sm-3">
									<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
								</div>

								<div class="col-sm-3">
									<input type="button" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Print" onclick="openTicket()">
								</div>

								<div class="col-sm-3">
									<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Reset">
								</div>

								<div class="col-sm-3">
									<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Cancel" onclick="cancel_process(event)">
								</div>

							</div>

						</div>
						</div>

					</div>

				</div>

			</div>

		</div>

	</form>

	<div id="hide_div">

		<form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('jobcard_print_document')}}">
			@csrf
			<input type="text" name="ticket_no" id="ticket_no" values="">
		</form>

	</div>

	<script>

		function openTicket(){

			var ticket_no = document.getElementById("ticket_number").value;

			document.getElementById("ticket_no").value = ticket_no;

			document.getElementById("myForm").submit();
		}

	</script>

</div>

@endsection
