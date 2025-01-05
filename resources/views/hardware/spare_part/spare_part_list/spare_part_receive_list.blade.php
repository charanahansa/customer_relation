@extends('layouts.hw')

@section('title')
    Spare Part Receive List
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_receive_list_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Spare Part Receive List
				</div>

				<div class="card-body">

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Buyer</label>
						<div class="col-sm-3">
							<select name="buyer" id="buyer" class="form-control form-control-sm">
								@foreach($SPRL['buyer'] as $row)
									@if($SPRL['attributes']['buyer_id'] == $row->buyer_id)
										<option value ="{{$row->buyer_id}}" selected>{{$row->buyer_name}}</option>
									@else
										<option value ="{{$row->buyer_id}}">{{$row->buyer_name}}</option>
									@endif
								@endforeach
								@if($SPRL['attributes']['buyer_id']== 0)
									<option value ="0" selected>Select the Buyer</option>
								@else
									<option value ="0">Select the Buyer</option>
								@endif
							</select>
							@if($SPRL['attributes']['validation_messages']->has('buyer'))
								<script>
										document.getElementById('buyer').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPRL['attributes']['validation_messages']->first("buyer") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Part Type</label>
						<div class="col-sm-2">
							<select name="part_type" id="part_type" class="form-control form-control-sm">
								<option value ="New">New</option>
								<option value ="Used">Used</option>
							</select>
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">SP Bin</label>
						<div class="col-sm-3">
							<select name="bin" id="bin" class="form-control form-control-sm">
								@foreach($SPRL['bin'] as $row)
									@if($SPRL['attributes']['bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SPRL['attributes']['bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@else
									<option value ="0">Select the Bin</option>
								@endif
							</select>
							@if($SPRL['attributes']['validation_messages']->has('bin'))
								<script>
										document.getElementById('bin').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPRL['attributes']['validation_messages']->first("bin") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
                        <div class="col-sm-5">
                            <select name="spare_part" id="spare_part" class="form-control form-control-sm">
                                @foreach($SPRL['spare_part'] as $row)
                                    @if($SPRL['attributes']['spare_part'] == $row->part_id)
                                        <option value ="{{$row->part_id}}" selected>{{$row->part_name}}</option>
                                    @else
                                        <option value ="{{$row->part_id}}">{{$row->part_name}}</option>
                                    @endif
                                @endforeach
                                @if($SPRL['attributes']['spare_part']== 0)
                                    <option value ="0" selected>Select the Spare Part</option>
								@else
									<option value ="0">Select the Spare Part</option>
                                @endif
                            </select>
                            @if($SPRL['attributes']['validation_messages']->has('spare_part'))
                                <script>
                                        document.getElementById('spare_part').className = 'form-select form-select-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPRL['attributes']['validation_messages']->first("spare_part") }}</div>
                            @endif
                        </div>
                    </div>

					<div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
						<div class="col-sm-2">
							<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$SPRL['attributes']['from_date']}}">
						</div>
						<label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
						<div class="col-sm-2">
							<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$SPRL['attributes']['to_date']}}">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width: 100%" value="Search">
						</div>
                    </div>

					<div class="table-responsive">
					<div id="tbldiv" style="width: 100%;">

						<table id="tblgrid1" class="table table-hover table-sm table-bordered">
							<?php $icount = 1; ?>
							<thead>
								<tr style="font-family: Consolas; font-size: 13px; background-color: yellowgreen;">
									<th>No</th>
									<th>Receive Id</th>
									<th>Receive Date </th>
									<th>Part Type</th>
									<th>Buyer</th>
									<th>Bin</th>
									<th>Spare Part </th>
									<th>Price</th>
									<th>Quantity</th>
								</tr>
							</thead>
							@if(count($SPRL['data_table']))

								<tbody>
									@foreach($SPRL['data_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
                                            <td>{{$row->spr_id}}</td>
											<td>{{$row->spr_date}}</td>
											<td>{{$row->part_type}}</td>
											<td>{{$row->buyer_name}}</td>
											<td>{{$row->bin_name}}</td>
											<td>{{$row->spare_part_name}}</td>
											<td style="text-align: right;">@money($row->price)</td>
											<td style="text-align: right;">{{$row->quantity}}</td>
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

@endsection


