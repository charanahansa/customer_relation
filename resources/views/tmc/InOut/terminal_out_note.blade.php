@extends('layouts.tmc')

@section('title')
    Terminal Out Note
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('terminal_out_note_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Terminal Out Note
			</div>

			<div class="card-body">

				<div class="col-sm-12">
					<?php echo $TON['attributes']['process_message']; ?>
				</div>

				<div class="row no-gutters">

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-1 row">
							<label for="tid" class="col-sm-6 col-form-label-sm"></label>
							<label for="tid" class="col-sm-2 col-form-label-sm">Ticket No.</label>
							<div class="col-sm-4">
								<input type="text" name="ticket_number" class="form-control form-control-sm" id="ticket_number" value="{{$TON['attributes']['ticket_number']}}" readonly>
								@if($TON['attributes']['validation_messages']->has('ticket_number'))
                                    <script>
                                            document.getElementById('ticket_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("ticket_number") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">POD No.</label>
							<div class="col-sm-4">
								<input type="text" name="pod_number" class="form-control form-control-sm" id="pod_number" value="{{$TON['attributes']['pod_number']}}">
								@if($TON['attributes']['validation_messages']->has('pod_number'))
                                    <script>
                                            document.getElementById('pod_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("pod_number") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
							<div class="col-sm-4">
								<input type="date" name="ticket_date" class="form-control form-control-sm" id="ticket_date" value="{{$TON['attributes']['ticket_date']}}">
								@if($TON['attributes']['validation_messages']->has('ticket_date'))
                                    <script>
                                            document.getElementById('ticket_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("ticket_date") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Source</label>
							<div class="col-sm-4">
								<select name="source" id="source" class="form-select form-select-sm">
									@foreach($TON['source'] as $row)
										@if($TON['attributes']['source'] == $row->sp_issue_type_id)
											<option value ="{{$row->sp_issue_type_id}}" selected>{{$row->sp_issue_type_name}}</option>
										@else
											<option value ="{{$row->sp_issue_type_id}}">{{$row->sp_issue_type_name}}</option>
										@endif
									@endforeach
									@if($TON['attributes']['source'] == "0")
										<option value ="0" selected>Select the Source</option>
									@endif
								</select>
								@if($TON['attributes']['validation_messages']->has('source'))
                                    <script>
                                            document.getElementById('source').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("source") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Type</label>
							<div class="col-sm-4">
								<select name="type" id="type" class="form-select form-select-sm">
									@foreach($TON['type'] as $row)
										@if($TON['attributes']['type'] == $row)
											<option value ="{{$row}}" selected>{{$row}}</option>
										@else
											<option value ="{{$row}}">{{$row}}</option>
										@endif
									@endforeach
									@if($TON['attributes']['type'] === "0")
										<option value = "0" selected>Select the Type</option>
									@endif
								</select>
								@if($TON['attributes']['validation_messages']->has('type'))
                                    <script>
                                            document.getElementById('type').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("type") }}</div>
                                @endif
							</div>

						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Courier</label>
							<div class="col-sm-10">
								<select name="courier" id="courier" class="form-select form-select-sm">
									@foreach($TON['courier'] as $row)
										@if($TON['attributes']['courier'] == $row->id)
											<option value={{$row->id}} selected>{{$row->officer_name}} </option>
										@else
											<option value={{$row->id}}> {{$row->officer_name}} </option>
										@endif
									@endforeach
									@if($TON['attributes']['courier'] == "Not")
                                        <option value ="Not" selected>Select the Courier</option>
                                    @endif
								</select>
								@if($TON['attributes']['validation_messages']->has('courier'))
                                    <script>
                                            document.getElementById('courier').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("courier") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
							<div class="col-sm-10">
								<select name="bank" id="bank" class="form-select form-select-sm">
									@foreach($TON['bank'] as $row)
										@if($TON['attributes']['bank'] == $row->bank)
											<option value={{$row->bank}} selected>{{$row->bankname}} </option>
										@else
											<option value={{$row->bank}}> {{$row->bankname}} </option>
										@endif
									@endforeach
									@if( ($TON['attributes']['bank'] === "0") || (is_null($TON['attributes']['bank']) == TRUE) )
                                        <option value ="0" selected>Select the Bank</option>
                                    @endif
								</select>
								@if($TON['attributes']['validation_messages']->has('bank'))
                                    <script>
                                            document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("bank") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Officers</label>
							<div class="col-sm-10">
								<select name="officer" id="officer" class="form-select form-select-sm">
									@foreach($TON['officer'] as $row)
										@if($TON['attributes']['officer'] == $row->officer_id)
											<option value={{$row->officer_id}} selected>{{$row->name}} </option>
										@else
											<option value={{$row->officer_id}}> {{$row->name}} </option>
										@endif
									@endforeach
									@if( ($TON['attributes']['officer'] === "0") || (is_null($TON['attributes']['officer']) == TRUE) )
                                        <option value ="0" selected>Select the Officer</option>
                                    @endif
								</select>
								@if($TON['attributes']['validation_messages']->has('officer'))
                                    <script>
                                            document.getElementById('officer').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("officer") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
							<div class="col-sm-10">
								<textarea name="remark" class="form-control form-control-sm" id="remark" style="resize:none" rows="3">{{$TON['attributes']['remark']}}</textarea>
								@if($TON['attributes']['validation_messages']->has('remark'))
                                    <script>
                                            document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("remark") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Cancel Reason</label>
							<div class="col-sm-10">
								<input type="text" name="cancel_reason" class="form-control form-control-sm" id="cancel_reason" value="{{$TON['attributes']['cancel_reason']}}" style="color: red; font-style: oblique; font-weight: bold; " readonly>
								@if($TON['attributes']['validation_messages']->has('cancel_reason'))
                                    <script>
                                            document.getElementById('cancel_reason').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TON['attributes']['validation_messages']->first("cancel_reason") }}</div>
                                @endif
							</div>
						</div>
						<hr>

						<div class="mb-2 row">

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
							</div>

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Reset">
							</div>

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Cancel" onclick="getCancelReason()">
							</div>

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Confirm">
							</div>

						</div>

						<div class="mb-2 row">

							<div class="col-sm-3">
								<input type="button" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Print Document" onclick="printDocument('P')">
							</div>

							<div class="col-sm-3">
								<input type="button" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Courier Slip" onclick="printDocument('S')">
							</div>

						</div>


					</div>
					</div>

					<div class="col-12 col-sm-2 col-md-2">
					<div style="margin-left: 1%; margin-right: 1%;">

						<div class="mb-2 row">
							<div class="col-sm-10">
								<textarea name="terminal_serial" class="form-control form-control-sm" id="terminal_serial" style="resize:none" col="7" rows="20">{{$TON['attributes']['terminal_serial']}}</textarea>
							</div>
						</div>

					</div>
					</div>

					<div class="col-12 col-sm-4 col-md-4">
					<div style="margin-left: 1%; margin-right: 1%;">



					</div>
					</div>

				</div>

			</div>
		</div>

	</div>

</form>
</div>

<div id="hide_div">

    <form id="courierSlipForm" style="display: none;" method="post" target='_blank' action="{{route('terminal_out_note_courier_slip')}}">
        @csrf
        <input type="text" name="slip_ticket_no" id="slip_ticket_no" values="">
    </form>

	<form id="printForm" style="display: none;" method="post" target='_blank' action="{{route('terminal_out_note_print_document')}}">
        @csrf
        <input type="text" name="print_ticket_no" id="print_ticket_no" values="">
    </form>

</div>

<script>

	function getCancelReason() {

		var txt;
		var reason = prompt("Please enter Cancel Reason:", "");
		if (reason == null || reason == "") {
			txt = "";
		} else {
			txt = reason;
		}
		document.getElementById("cancel_reason").value = txt;
	}


	function printDocument(ref){

		var ticket_no = document.getElementById("ticket_number").value;

		document.getElementById("print_ticket_no").value = ticket_no;
		document.getElementById("slip_ticket_no").value = ticket_no;

		if( ref == 'S'){

			document.getElementById("courierSlipForm").submit();
		}

		if( ref == 'P'){

			document.getElementById("printForm").submit();
		}
	}


</script>

@endsection
