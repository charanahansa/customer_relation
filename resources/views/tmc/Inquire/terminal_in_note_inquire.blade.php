@extends('layouts.tmc')

@section('title')
    Terminal In Note Inquire
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('terminal_in_note_inquire_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Terminal In Note Inquire
			</div>

			<div class="card-body">

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
					<div class="col-sm-2">
						<select name="bank" id="bank" class="form-select form-select-sm">
							@foreach($TII['bank'] as $row)
								<option value="{{$row->bank}}"> {{$row->bank}} </option>
							@endforeach
							<option value ="0" selected>Select the bank</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
					<div class="col-sm-2">
						<select name="model" id="model" class="form-select form-select-sm">
							@foreach($TII['model'] as $row)
								<option value={{$row->model}}> {{$row->model}} </option>
							@endforeach
							<option value ="0" selected>Select the Model</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Receive Type</label>
					<div class="col-sm-2">
						<select name="receive_type" id="receive_type" class="form-select form-select-sm">
							<option value='printer'>Printer</option>
							<option value='terminal'>Terminal</option>
							<option value='item'>Item</option>
							<option value ="0" selected>Select the Receive Type</option>
						</select>
					</div>

				</div>

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
					<div class="col-sm-2">
						<select name="officer" id="officer" class="form-select form-select-sm">
							@foreach($TII['officer'] as $row)
								<option value={{$row->officer_id}}> {{$row->name}} </option>
							@endforeach
							<option value ="0" selected>Select the Officer</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Courier</label>
					<div class="col-sm-2">
						<select name="courier" id="courier" class="form-select form-select-sm">
							@foreach($TII['courier'] as $row)
								<option value={{$row->id}}> {{$row->officer_name}} </option>
							@endforeach
							<option value ="Not" selected>Select the Courier</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Serial No.</label>
					<div class="col-sm-2">
						<input type="text" name="serial_number" class="form-control form-control-sm" id="serial_number" value="">
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">POD No.</label>
					<div class="col-sm-2">
						<input type="text" name="pod_number" class="form-control form-control-sm" id="pod_number" value="">
					</div>

				</div>

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
					<div class="col-sm-2">
						<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="">
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
					<div class="col-sm-2">
						<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="">
					</div>

					<div class="col-sm-2">
						<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Inquire">
					</div>

					<div class="col-sm-2">
						<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-success btn-sm" value="Excell">
					</div>

				</div>

				<hr>

				<div class="table-responsive">
				<div id="tbldiv" style="width: 100%;">

					<table id="tblgrid1" class="table table-hover table-sm table-bordered">
						<?php $icount = 1; ?>
						<thead>
							<tr style="font-family: Consolas; font-size: 13px;">
								<th>No</th>
								<th>Ticket No.</th>
								<th>Date</th>
								<th>Bank</th>
								<th>Type</th>
								<th>Officer</th>
								<th>Courier</th>
								<th>POD No. </th>
								<th>Terminal Count </th>
							</tr>
						</thead>

						@if(count($TII['report_table']))

							<tbody>
								@foreach($TII['report_table'] as $row)
									<tr style="font-family: Consolas; font-size: 13px;">
										<td>{{$icount}}</td>
										<td>{{$row->ticketno}}</td>
										<td>{{$row->tdate}}</td>
										<td>{{$row->bank}}</td>
										<td>{{$row->receive_type}}</td>
										<td>{{$row->officer_name}}</td>
										<td>{{$row->courier_name}}</td>
										<td>{{$row->podno}}</td>
										<td>{{$row->terminal_count}}</td>
										<td> <input type="button" name="open" id="open" style="width: 100%;" class="btn btn-info btn-sm" value="Open" onclick='openTicket({{$row->ticketno}})'></td>
									</tr>
									<?php $icount++; ?>
								@endforeach
							</tbody>

						@else

							<tbody>
								<tr style="font-family: Consolas; font-size: 12px;">
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
								</tr>
							</tbody>

						@endif

					</table>

				</div>
				</div>

			</div>

		</div>

	</div>

</form>
</div>

<div id="hide_div">

    <form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('get_terminal_in_note')}}">
        @csrf
        <input type="text" name="ticket_number" id="ticket_number" values="">
    </form>

</div>

<script>

    function openTicket(ticket_number){

        document.getElementById("ticket_number").value = ticket_number;
        document.getElementById("myForm").submit();
    }

</script>



@endsection
