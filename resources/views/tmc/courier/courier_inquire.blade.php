@extends('layouts.tmc')

@section('title')
    Courier Inquire
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('courier_inquire_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Courier Inquire
			</div>

			<div class="card-body">

				<div class="col-sm-12">
					<?php echo $CI['attributes']['process_message']; ?>
				</div>

				<div class="mb-3 row">
					<label for="tid" class="col-sm-1 col-form-label-sm">Search</label>
					<div class="col-sm-10">
						<input type="text" name="search" class="form-control form-control-sm" id="search" value="{{$CI['attributes']['search']}}">
						@if($CI['attributes']['validation_messages']->has('search'))
							<script>
									document.getElementById('search').className = 'form-select form-select-sm is-invalid';
							</script>
							<div class="invalid-feedback">{{$CI['attributes']['validation_messages']->first("search")}}</div>
						@endif
					</div>
					<div class="col-sm-1">
						<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Search">
					</div>
				</div>

				@if( isset($CI['sent_table']) )

					<hr>
					<h2>Sent Information</h2>

					<table id='tblgrid' class='table table-hover table-sm table-bordered'>

						<tr style='font-family: Consolas; font-size: 13px; background-color: yellowgreen;'>
							<th style='width: 5%;'>Ticket No. </th>
							<th style='width: 7%;'>Date </th>
							<th style='width: 4%;'>Ref</th>
							<th style='width: 7%;'>Pod No.</th>
							<th style='width: 5%;'>Bank</th>
							<th style='width: 4%;'>Tid</th>
							<th style='width: 7%;'>Serial No.</th>
							<th style='width: 5%;'>Collect From Courier </th>
							<th style='width: 30%;'>Merchant</th>
							<th style='width: 10%;'>Contact No.</th>
							<th style='width: 10%;'>Remark</th>
						</tr>

						@if( count($CI['sent_table']) >= 1)

							@foreach($CI['sent_table'] as $row)

								<tr style='font-family: Consolas; font-size: 13px;'>
									<td> {{$row->ticketno}} </td>
									<td> {{$row->tdate}} </td>
									<td> {{$row->ref}} </td>
									<td> {{$row->pod_no}} </td>
									<td> {{$row->bank}} </td>
									<td> {{$row->tid}} </td>
									<td> {{$row->serial_number}} </td>
									<td> {{$row->collect_from_courier}} </td>
									<td> {{$row->merchant}} </td>
									<td> {{$row->contact_number}} </td>
									<td> {{$row->remark}} </td>
								</tr>

							@endforeach

						@else

							<tr style='font-family: Consolas; font-size: 13px;'>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
								<td> - </td>
							</tr>

						@endif

					</table>

				@else

				@endif

				@if( isset($CI['receive_table']) )

					<hr>
					<h2>Receive Information</h2>

					<table id='tblgrid' class='table table-hover table-sm table-bordered'>
						<tr style='font-family: Consolas; font-size: 13px; background-color: yellowgreen;'>
						<th style='width: 10%;'>Ticket No. </th>
						<th style='width: 10%;'>Date </th>
						<th style='width: 10%;'>Bank</th>
						<th style='width: 10%;'>Receive</th>
						<th style='width: 10%;'>Ref No. </th>
						<th style='width: 10%;'>Pod No. </th>
						<th style='width: 10%;'>Return Pod No. </th>
					</tr>

					@if( count($CI['receive_table']) >= 1)

						@foreach($CI['receive_table'] as $row)

							<tr style='font-family: Consolas; font-size: 13px;'>
								<td>{{$row->ticketno}} </td>
								<td>{{$row->tdate}} </td>
								<td>{{$row->bank}} </td>
								<td>{{$row->receive_type}} </td>
								<td>{{$row->refno}} </td>
								<td>{{$row->podno}} </td>
								<td>{{$row->return_podno}} </td>
							</tr>

						@endforeach

					@else

						<tr style='font-family: Consolas; font-size: 13px;'>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
						</tr>

					@endif

				@else

				@endif


			</div>
		</div>

	</div>

</form>
</div>



@endsection
