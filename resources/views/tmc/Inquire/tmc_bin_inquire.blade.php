@extends('layouts.tmc')

@section('title')
    Tmc Bin Inquire
@endsection

@section('body')

<div id="tbldiv" style="width: 99%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('tmc_bin_inquire_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Tmc Bin Inquire
			</div>

			<div class="card-body">

				<div class="mb-2 row">

					<label for="fromdate" class="col-sm-1 col-form-label-sm">Workflow</label>
					<div class="col-sm-2">
						<select name="workflow_id" id="workflow_id" class="form-select form-select-sm">
							@foreach($TBI['workflow'] as $row)
								<option value ="{{$row->workflow_id}}" selected>{{$row->workflow_name}}</option>
							@endforeach
							<option value ="0" selected>Select the Workflow</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
					<div class="col-sm-2">
						<select name="bank" id="bank" class="form-select form-select-sm">
							@foreach($TBI['bank'] as $row)
								<option value="{{$row->bank}}"> {{$row->bank}} </option>
							@endforeach
							<option value ="0" selected>Select the bank</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
					<div class="col-sm-2">
						<select name="model" id="model" class="form-select form-select-sm">
							@foreach($TBI['model'] as $row)
								<option value={{$row->model}}> {{$row->model}} </option>
							@endforeach
							<option value ="0" selected>Select the Model</option>
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
								<th>Serial No.</th>
                                <th>Model</th>
                                <th>Bank</th>
                                <th>In Workflow</th>
                                <th>In Workflow No.</th>
                                <th>In Workflow Date</th>
                                <th>Released</th>
                                <th>Released Workflow</th>
                                <th>Released Workflow No.</th>
                                <th>Released Workflow Date</th>
							</tr>
						</thead>

						@if(count($TBI['report_table']))

							<tbody>
								@foreach($TBI['report_table'] as $row)
									<tr style="font-family: Consolas; font-size: 13px;">
                                        <td>{{$icount}}</td>
                                        <td>{{$row->serial_number}}</td>
                                        <td>{{$row->model}}</td>
                                        <td>{{$row->bank}}</td>
                                        <td>{{$row->in_workflow_name}}</td>
                                        <td>{{$row->in_workflow_number}}</td>
                                        <td>{{$row->in_workflow_date}}</td>
                                        @if( $row->released == 1 )
                                            <td>Yes</td>
                                        @else
                                            <td>No</td>
                                        @endif
                                        <td>{{$row->released_workflow_name}}</td>
                                        <td>{{$row->released_workflow_number}}</td>
                                        <td>{{$row->released_workflow_date}}</td>
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
