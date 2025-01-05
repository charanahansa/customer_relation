@extends('layouts.tmc')

@section('title')
    Backup Remove Note Inquire
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('backup_remove_note_inquire_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Backup Remove Note Inquire
			</div>

			<div class="card-body">

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
					<div class="col-sm-2">
						<select name="bank" id="bank" class="form-select form-select-sm">
							@foreach($BRN['bank'] as $row)
								<option value="{{$row->bank}}"> {{$row->bank}} </option>
							@endforeach
							<option value =0 selected>Select the bank</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
					<div class="col-sm-2">
						<select name="model" id="model" class="form-select form-select-sm">
							@foreach($BRN['model'] as $row)
								<option value={{$row->model}}> {{$row->model}} </option>
							@endforeach
							<option value =0 selected>Select the Model</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
					<div class="col-sm-2">
						<select name="officer" id="officer" class="form-select form-select-sm">
							@foreach($BRN['officer'] as $row)
								<option value={{$row->officer_id}}> {{$row->name}} </option>
							@endforeach
							<option value =0 selected>Select the Officer</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Status</label>
					<div class="col-sm-2">
						<select name="status" id="status" class="form-select form-select-sm">
							@foreach($BRN['status'] as $row)
								<option value={{$row->codeid}}> {{$row->description}} </option>
							@endforeach
							<option value =0 selected>Select the Status</option>
						</select>
					</div>

				</div>

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Sub Status</label>
					<div class="col-sm-8">
						<select name="sub_status" id="sub_status" class="form-select form-select-sm">
							@foreach($BRN['sub_status'] as $row)
								<option value={{$row->id}}> {{$row->status}} </option>
							@endforeach
							<option value =0 selected>Select the Sub Status</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Serial No.</label>
					<div class="col-sm-2">
						<input type="text" name="serial_number" class="form-control form-control-sm" id="serial_number" value="">
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

					<label for="tid" class="col-sm-1 col-form-label-sm">Terminal ID</label>
					<div class="col-sm-2">
						<input type="text" name="tid" class="form-control form-control-sm" id="tid" value="">
					</div>

					<div class="col-sm-1">
						<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Inquire">
					</div>

					<div class="col-sm-1">
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
									<th>BRN No.</th>
									<th>BRN Date</th>
									<th>TID</th>
									<th>Bank</th>
									<th>BK Serial No. </th>
									<th>BK Model </th>
									<th>RP Serial No. </th>
									<th>RP Model </th>
								</tr>
							</thead>

							@if(count($BRN['report_table']))

								<tbody>
									@foreach($BRN['report_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
											<td>{{$row->brn_no}}</td>
											<td>{{$row->brn_date}}</td>
											<td>{{$row->tid}}</td>
											<td>{{$row->bank}}</td>
											<td>{{$row->backup_serialno}}</td>
											<td>{{$row->backup_model}}</td>
											<td>{{$row->replaced_serialno}}</td>
											<td>{{$row->replaced_model}}</td>
										</tr>
										<tr style="font-family: Consolas; font-size: 13px;">
											<td style="background-color:gray">Merchant</td>
											<td colspan="4">{{$row->merchant}}</td>
											<td style="background-color:gray">Officer</td>
											<td colspan="2">{{$row->officer_name}}</td>
											<td><input type="button" name="submit" id="submit" style="width: 100%;" class="btn btn-info btn-sm" value="Open" onclick='openTicket({{$row->brn_no}})'></td>
										</tr>
										<tr style="font-family: Consolas; font-size: 12px;">
											<td style="background-color:gray">Sub Status</td>
											<td colspan="4">{{$row->sub_status}}</td>
											<td style="background-color:gray">Status</td>
											<td>{{ucfirst($row->status)}}</td>
											<td style="background-color:gray">Done Date Time</td>
											<td>{{$row->done_date_time}}</td>
										</tr>
										<tr style="font-family: Consolas; font-size: 12px;">
											<td colspan="11" style="background-color: #007bff"></td>
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
									</tr>
									<tr style="font-family: Consolas; font-size: 12px;">
										<td>Merchant</td>
										<td colspan="7">-</td>
									</tr>
									<tr style="font-family: Consolas; font-size: 12px;">
										<td>Sub Status</td>
										<td colspan="4">-</td>
										<td>Status</td>
										<td>-</td>
										<td>Done Date Time</td>
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

    <form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('get_backup_remove_note')}}">
        @csrf
        <input type="text" name="ticket_number" id="ticket_number" values="">
        <input type="text" name="workflow_id" id="workflow_id" values="">
    </form>

</div>

<script>

    function openTicket(ticket_number){

        document.getElementById("ticket_number").value = ticket_number;
        document.getElementById("myForm").submit();
    }

</script>



@endsection
