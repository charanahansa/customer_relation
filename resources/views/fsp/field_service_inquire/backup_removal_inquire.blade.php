@extends('layouts.fsp')

@section('title')
    Backup Removal Inquire
@endsection

@section('body')

<form method="POST" name="form_name" action="{{route('fs_backup_removal_inquire_process')}}">

	@csrf

    <div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<div class="col-sm-12">
            
		<div class="card">

			<div class="card-header">
				Backup Removal Inquire
			</div>

			<div class="card-body">

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
					<div class="col-sm-2">
						<select name="bank" id="bank" class="form-select form-select-sm">
							@foreach($BR['bank'] as $row)
								<option value="{{$row->bank}}"> {{$row->bank}} </option>
							@endforeach
							<option value ="0" selected>Select the bank</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
					<div class="col-sm-2">
						<select name="model" id="model" class="form-select form-select-sm">
							@foreach($BR['model'] as $row)
								<option value={{$row->model}}> {{$row->model}} </option>
							@endforeach
							<option value ="0" selected>Select the Model</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
					<div class="col-sm-2">
						<select name="officer" id="officer" class="form-select form-select-sm">
							@foreach($BR['officer'] as $row)
								<option value={{$row->officer_id}}> {{$row->name}} </option>
							@endforeach
							<option value ="0" selected>Select the Officer</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Status</label>
					<div class="col-sm-2">
						<select name="status" id="status" class="form-select form-select-sm">
							@foreach($BR['status'] as $row)
								<option value={{$row->codeid}}> {{$row->description}} </option>
							@endforeach
							<option value ="0" selected>Select the Status</option>
						</select>
					</div>

				</div>
	
				<div class="mb-2 row">

                    <label for="tid" class="col-sm-1 col-form-label-sm">Merchant</label>
					<div class="col-sm-5">
						<input type="text" name="merchant" id="merchant" class="form-control form-control-sm" value="">
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Sub Status</label>
					<div class="col-sm-5">
						<select name="sub_status" id="sub_status" class="form-select form-select-sm">
							@foreach($BR['sub_status'] as $row)
								<option value={{$row->id}}> {{$row->status}} </option>
							@endforeach
							<option value ="0" selected>Select the Sub Status</option>
						</select>
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

					<label for="tid" class="col-sm-1 col-form-label-sm">Serial No.</label>
					<div class="col-sm-2">
						<input type="text" name="serial_number" class="form-control form-control-sm" id="serial_number" value="">
					</div>

				</div>

				<div class="mb-2 row">

					<div class="col-sm-8">
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
								<tr style="font-family: Consolas; font-size: 13px; background-color: #9999ff;">
									<th>No</th>
									<th>Ticket No.</th>
									<th>Ticket Date</th>
									<th>BIN NO.</th>
									<th>Bank</th>
									<th>TID</th>
									<th>Backup Serial No. </th>
                                    <th>Backup Model</th>
									<th>Replaced Serial No. </th>
									<th>Replaced Model </th>
								</tr>
							</thead>

							@if(count($BR['report_table']))

								<tbody>
									@foreach($BR['report_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
											<td>{{$row->brn_no}}</td>
											<td>{{$row->brn_date}}</td>
											<td>{{$row->bin_no}}</td>
											<td>{{$row->bank}}</td>
											<td>{{$row->tid}}</td>
											<td>{{$row->backup_serialno}}</td>
											<td>{{$row->backup_model}}</td>
											<td>{{$row->replaced_serialno}}</td>
											<td>{{$row->replaced_model}}</td>
										</tr>
										<tr style="font-family: Consolas; font-size: 13px;">
											<td style="background-color:  #acddde">Merchant</td>
											<td colspan="8"><b> {{$row->merchant}} </b> </td>
											<td><input type="button" name="submit" id="submit" style="width: 100%;" class="btn btn-info btn-sm" value="Open" onclick='openTicket({{$row->brn_no}}, "{{$row->bank}}")'></td>
										</tr>
                                        <tr style="font-family: Consolas; font-size: 12px;">
											<td style="background-color:  #acddde">Officer</td>
											<td colspan="3"><b> {{$row->field_officer}} </b> </td>
											<td style="background-color:  #acddde">Courier</td>
											<td><b> {{ucfirst($row->courier)}} </b> </td>
											<td style="background-color:  #acddde">Contact No.</td>
											<td><b> {{$row->contact_number}} </b> </td>
                                            <td style="background-color:  #acddde">Contact Person</td>
											<td colspan="2"><b> {{$row->contact_person}} </b> </td>
										</tr>
										<tr style="font-family: Consolas; font-size: 12px;">
											<td style="background-color:  #acddde">Sub Status</td>
											<td colspan="5"><b> {{$row->sub_status}} </b> </td>
											<td style="background-color:  #acddde">Status</td>
											<td><b> {{ucfirst($row->status)}} </b> </td>
											<td style="background-color:  #acddde">Done Date Time</td>
											<td><b> {{$row->done_date_time}} </b> </td>
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
										<td>-</td>
									</tr>
									<tr style="font-family: Consolas; font-size: 12px;">
										<td>Merchant</td>
										<td colspan="8">-</td>
										<td>-</td>
									</tr>
                                    <tr style="font-family: Consolas; font-size: 12px;">
                                        <td>Officer</td>
                                        <td colspan="3">-</td>
                                        <td>Courier</td>
                                        <td>-</td>
                                        <td>Contact No.</td>
                                        <td>-</td>
                                        <td>Contact Person</td>
                                        <td>-</td>
                                    </tr>
									<tr style="font-family: Consolas; font-size: 12px;">
										<td>Sub Status</td>
										<td colspan="5">-</td>
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
    </div>

</form>

<div id="hide_div">

    <form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('field_service_allocation')}}">
        @csrf
        <input type="text" name="ticket_number" id="ticket_number" values="">
        <input type="text" name="ticket_bank" id="ticket_bank" values="">
        <input type="text" name="workflow_id" id="workflow_id" values="">
    </form>

</div>

<script>

    function openTicket(ticket_number, bank){

        document.getElementById("ticket_number").value = ticket_number;
		document.getElementById("ticket_bank").value = bank;
        document.getElementById("workflow_id").value = 6;

        document.getElementById("myForm").submit();
    }

</script>



@endsection