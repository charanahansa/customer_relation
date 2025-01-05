@extends('layouts.hw')

@section('title')
    Spare Part Issue List
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_issue_list_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Spare Part Issue List
				</div>

				<div class="card-body">

					<div class="mb-2 row">
						<label for="tid" class="col-sm-1 col-form-label-sm">SP Type</label>
						<div class="col-sm-2">
							<select name="part_type" id="part_type" class="form-control form-control-sm">
								<option value ="New">New</option>
								<option value ="Used">Used</option>
							</select>
						</div>

						<label for="tid" class="col-sm-2 col-form-label-sm">SP Issue Type</label>
						<div class="col-sm-2">
							<select name="issue_type" id="issue_type" class="form-control form-control-sm">
								@foreach($SPIL['issue_type'] as $row)
									@if($SPIL['attributes']['issue_type'] == $row->sp_issue_type_id)
										<option value ="{{$row->sp_issue_type_id}}" selected>{{$row->sp_issue_type_name}}</option>
									@else
										<option value ="{{$row->sp_issue_type_id}}">{{$row->sp_issue_type_name}}</option>
									@endif
								@endforeach
								@if($SPIL['attributes']['issue_type']== 0)
									<option value ="0" selected>Select the Issue Type</option>
								@else
									<option value ="0">Select the Issue Type</option>
								@endif
							</select>
						</div>
					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">From Bin</label>
						<div class="col-sm-3">
							<select name="from_bin_id" id="from_bin_id" class="form-control form-control-sm">
								@foreach($SPIL['bin'] as $row)
									@if($SPIL['attributes']['from_bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SPIL['attributes']['from_bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@else
									<option value ="0">Select the Bin</option>
								@endif
							</select>
							@if($SPIL['attributes']['validation_messages']->has('from_bin_id'))
								<script>
										document.getElementById('from_bin_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPIL['attributes']['validation_messages']->first("from_bin_id") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">To Bin</label>
						<div class="col-sm-3">
							<select name="to_bin_id" id="to_bin_id" class="form-control form-control-sm">
								@foreach($SPIL['bin'] as $row)
									@if($SPIL['attributes']['to_bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SPIL['attributes']['to_bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@else
									<option value ="0">Select the Bin</option>
								@endif
							</select>
							@if($SPIL['attributes']['validation_messages']->has('to_bin_id'))
								<script>
										document.getElementById('to_bin_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPIL['attributes']['validation_messages']->first("to_bin_id") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-4 col-form-label-sm"></label>
						<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
						<div class="col-sm-3">
							<select name="bank" id="bank" class="form-control form-control-sm">
								@foreach($SPIL['bank'] as $row)
									@if($SPIL['attributes']['bank'] == $row->bank)
										<option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
									@else
										<option value ="{{$row->bank}}">{{$row->bank}}</option>
									@endif
								@endforeach
								@if($SPIL['attributes']['bank']== 0)
									<option value ="0" selected>Select the Bank</option>
								@else
									<option value ="0">Select the Bank</option>
								@endif
							</select>
							@if($SPIL['attributes']['validation_messages']->has('bank'))
								<script>
										document.getElementById('bank').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPIL['attributes']['validation_messages']->first("bank") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">						

						<label for="tid" class="col-sm-4 col-form-label-sm"></label>
						<label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
						<div class="col-sm-3">
							<select name="user_id" id="user_id" class="form-control form-control-sm">
								@foreach($SPIL['user'] as $row)
									@if($SPIL['attributes']['user_id'] == $row->id)
										<option value ="{{$row->id}}" selected>{{$row->name}}</option>
									@else
										<option value ="{{$row->id}}">{{$row->name}}</option>
									@endif
								@endforeach
								@if($SPIL['attributes']['user_id']== 0)
									<option value ="0" selected>Select the Officer</option>
								@else
									<option value ="0">Select the Officer</option>
								@endif
							</select>
							@if($SPIL['attributes']['validation_messages']->has('user_id'))
								<script>
										document.getElementById('user_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPIL['attributes']['validation_messages']->first("user_id") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
                        <div class="col-sm-7">
                            <select name="spare_part" id="spare_part" class="form-control form-control-sm">
                                @foreach($SPIL['spare_part'] as $row)
                                    @if($SPIL['attributes']['spare_part'] == $row->part_id)
                                        <option value ="{{$row->part_id}}" selected>{{$row->part_name}}</option>
                                    @else
                                        <option value ="{{$row->part_id}}">{{$row->part_name}}</option>
                                    @endif
                                @endforeach
                                @if($SPIL['attributes']['spare_part']== 0)
                                    <option value ="0" selected>Select the Spare Part</option>
								@else
									<option value ="0">Select the Spare Part</option>
                                @endif
                            </select>
                            @if($SPIL['attributes']['validation_messages']->has('spare_part'))
                                <script>
                                        document.getElementById('spare_part').className = 'form-select form-select-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPIL['attributes']['validation_messages']->first("spare_part") }}</div>
                            @endif
                        </div>
                    </div>

					<div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
						<div class="col-sm-2">
							<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$SPIL['attributes']['from_date']}}">
						</div>
						<label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
						<div class="col-sm-2">
							<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$SPIL['attributes']['to_date']}}">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width: 100%" value="Search">
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
									<th>Issue Id</th>
									<th>Issue Date</th>
									<th>Type</th>
									<th>Issue Type</th>
									<th>From Bin </th>
									<th>To</th>
									<th>Spare Part</th>
									<th>Quantity</th>
								</tr>
							</thead>
							@if(count($SPIL['data_table']))

								<tbody>
									@foreach($SPIL['data_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
											<td>{{$row->spi_id}}</td>
											<td>{{$row->spi_date}}</td>
											<td>{{$row->part_type}}</td>
											@if($row->issue_type == 1)
												<td>Bin</td>
											@elseif($row->issue_type == 2)
												<td>Bank</td>
											@elseif($row->issue_type == 3)
												<td>Officer</td>
											@else
												<td>-</td>
											@endif
											<td>{{$row->bin_name}}</td>
											<td>{{$row->TO}}</td>
											<td>{{$row->spare_part_name}}</td>
											<td>{{$row->quantity}}</td>
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


