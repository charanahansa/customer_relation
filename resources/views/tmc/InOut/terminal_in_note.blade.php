@extends('layouts.tmc')

@section('title')
    Terminal In Note
@endsection

@section('body')


<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('terminal_in_note_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Terminal In Note
			</div>

			<div class="card-body">

				<div class="col-sm-12">
					<?php echo $TIN['attributes']['process_message']; ?>
				</div>

				<div class="row no-gutters">

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-1 row">
							<label for="tid" class="col-sm-6 col-form-label-sm"></label>
							<label for="tid" class="col-sm-2 col-form-label-sm">Ticket No.</label>
							<div class="col-sm-4">
								<input type="text" name="ticket_number" class="form-control form-control-sm" id="ticket_number" value="{{$TIN['attributes']['ticket_number']}}" readonly>
								@if($TIN['attributes']['validation_messages']->has('ticket_number'))
                                    <script>
                                            document.getElementById('ticket_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TIN['attributes']['validation_messages']->first("ticket_number") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-6 col-form-label-sm"></label>
							<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
							<div class="col-sm-4">
								<input type="date" name="ticket_date" class="form-control form-control-sm" id="ticket_date" value="{{$TIN['attributes']['ticket_date']}}">
								@if($TIN['attributes']['validation_messages']->has('ticket_date'))
                                    <script>
                                            document.getElementById('ticket_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TIN['attributes']['validation_messages']->first("ticket_date") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
							<div class="col-sm-10">
								<select name="bank" id="bank" class="form-select form-select-sm">
									@foreach($TIN['bank'] as $row)
										@if($TIN['attributes']['bank'] == $row->bank)
											<option value={{$row->bank}} selected>{{$row->bankname}} </option>
										@else
											<option value={{$row->bank}}> {{$row->bankname}} </option>
										@endif
									@endforeach
									@if( ($TIN['attributes']['bank'] === "0") || (is_null($TIN['attributes']['bank']) == TRUE) )
                                        <option value ="0" selected>Select the Bank</option>
                                    @endif
								</select>
								@if($TIN['attributes']['validation_messages']->has('bank'))
                                    <script>
                                            document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TIN['attributes']['validation_messages']->first("bank") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
							<div class="col-sm-10">
								<textarea name="remark" class="form-control form-control-sm" id="remark" style="resize:none" rows="3">{{$TIN['attributes']['remark']}}</textarea>
								@if($TIN['attributes']['validation_messages']->has('remark'))
                                    <script>
                                            document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TIN['attributes']['validation_messages']->first("remark") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Cancel Reason</label>
							<div class="col-sm-10">
								<input type="text" name="cancel_reason" class="form-control form-control-sm" id="cancel_reason" value="{{$TIN['attributes']['cancel_reason']}}" style="color: red; font-style: oblique; font-weight: bold; " readonly>
								@if($TIN['attributes']['validation_messages']->has('cancel_reason'))
                                    <script>
                                            document.getElementById('cancel_reason').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $TIN['attributes']['validation_messages']->first("cancel_reason") }}</div>
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

						</div>


					</div>
					</div>

					<div class="col-12 col-sm-2 col-md-2">
					<div style="margin-left: 1%; margin-right: 1%;">

						<div class="mb-2 row">
							<div class="col-sm-10">
								<textarea name="terminal_serial" class="form-control form-control-sm" id="terminal_serial" style="resize:none" col="7" rows="20">{{$TIN['attributes']['terminal_serial']}}</textarea>
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

	<form id="printForm" style="display: none;" method="post" target='_blank' action="{{route('terminal_in_note_print_document')}}">
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
		document.getElementById("printForm").submit();
	}


</script>

@endsection
