@extends('layouts.hw')

@section('title')
    Spare Part Issue Note
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_issue_note_process')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Spare Part Issue Note
				</div>
	
				<div class="card-body">

					<div class="col-sm-12">
						<?php echo $SPI['attributes']['process_message']; ?>
					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Date</label>
						<div class="col-sm-2">
							<input type="date" name="spi_date" id="spi_date" class="form-control form-control-sm" value="{{$SPI['attributes']['spi_date']}}">
							@if($SPI['attributes']['validation_messages']->has('spi_date'))
								<script>
										document.getElementById('spi_date').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("spi_date") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-2 col-form-label-sm">Spare Part Type</label>
						<div class="col-sm-2">
							<select name="part_type" id="part_type" class="form-control form-control-sm">
								<option value ="New">New</option>
								<option value ="Used">Used</option>
							</select>
						</div>

						<label for="tid" class="col-sm-2 col-form-label-sm">Spare Part Issue Type</label>
						<div class="col-sm-2">
							<select name="issue_type" id="issue_type" class="form-control form-control-sm">
								@foreach($SPI['issue_type'] as $row)
									@if($SPI['attributes']['issue_type'] == $row->sp_issue_type_id)
										<option value ="{{$row->sp_issue_type_id}}" selected>{{$row->sp_issue_type_name}}</option>
									@else
										<option value ="{{$row->sp_issue_type_id}}">{{$row->sp_issue_type_name}}</option>
									@endif
								@endforeach
								@if($SPI['attributes']['issue_type']== 0)
									<option value ="0" selected>Select the Issue Type</option>
								@endif
							</select>
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">From Bin</label>
						<div class="col-sm-2">
							<select name="from_bin_id" id="from_bin_id" class="form-control form-control-sm">
								@foreach($SPI['bin'] as $row)
									@if($SPI['attributes']['from_bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SPI['attributes']['from_bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@else
									<option value ="0">Select the Bin</option>
								@endif
							</select>
							@if($SPI['attributes']['validation_messages']->has('from_bin_id'))
								<script>
										document.getElementById('from_bin_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("from_bin_id") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">To Bin</label>
						<div class="col-sm-3">
							<select name="to_bin_id" id="to_bin_id" class="form-control form-control-sm">
								@foreach($SPI['bin'] as $row)
									@if($SPI['attributes']['to_bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SPI['attributes']['to_bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@else
									<option value ="0">Select the Bin</option>
								@endif
							</select>
							@if($SPI['attributes']['validation_messages']->has('to_bin_id'))
								<script>
										document.getElementById('to_bin_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("to_bin_id") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-3 col-form-label-sm"></label>
						<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
						<div class="col-sm-3">
							<select name="bank" id="bank" class="form-control form-control-sm">
								@foreach($SPI['bank'] as $row)
									@if($SPI['attributes']['bank'] == $row->bank)
										<option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
									@else
										<option value ="{{$row->bank}}">{{$row->bank}}</option>
									@endif
								@endforeach
								@if($SPI['attributes']['bank']== 0)
									<option value ="0" selected>Select the Bank</option>
								@else
									<option value ="0">Select the Bank</option>
								@endif
							</select>
							@if($SPI['attributes']['validation_messages']->has('bank'))
								<script>
										document.getElementById('bank').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("bank") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">						

						<label for="tid" class="col-sm-3 col-form-label-sm"></label>
						<label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
						<div class="col-sm-3">
							<select name="user_id" id="user_id" class="form-control form-control-sm">
								@foreach($SPI['user'] as $row)
									@if($SPI['attributes']['user_id'] == $row->id)
										<option value ="{{$row->id}}" selected>{{$row->name}}</option>
									@else
										<option value ="{{$row->id}}">{{$row->name}}</option>
									@endif
								@endforeach
								@if($SPI['attributes']['user_id']== 0)
									<option value ="0" selected>Select the Officer</option>
								@else
									<option value ="0">Select the Officer</option>
								@endif
							</select>
							@if($SPI['attributes']['validation_messages']->has('user_id'))
								<script>
										document.getElementById('user_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("user_id") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">
						<label for="tid" class="col-sm-1 col-form-label-sm">Remark</label>
						<div class="col-sm-10">
							<input type="text" name="remark" id="remark" class="form-control form-control-sm" value="{{$SPI['attributes']['remark']}}">
							@if($SPI['attributes']['validation_messages']->has('remark'))
								<script>
										document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("remark") }}</div>
							@endif
						</div>
					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
						<div class="col-sm-6">
							<select name="spare_part_id" id="spare_part_id" class="form-control form-control-sm">
								@foreach($SPI['spare_part'] as $row)
									@if($SPI['attributes']['spare_part_id'] == $row->spare_part_id)
										<option value ="{{$row->spare_part_id}}" selected>{{$row->spare_part_name}}</option>
									@else
										<option value ="{{$row->spare_part_id}}">{{$row->spare_part_name}}</option>
									@endif
								@endforeach
								@if($SPI['attributes']['spare_part_id']== 0)
									<option value ="0" selected>Select the Spare Part</option>
								@else
									<option value ="0">Select the Spare Part</option>
								@endif
							</select>
							@if($SPI['attributes']['validation_messages']->has('spare_part'))
								<script>
										document.getElementById('spare_part_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("spare_part") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Quantity</label>
						<div class="col-sm-2">
							<input type="text" name="quantity" id="quantity" class="form-control form-control-sm" value="{{$SPI['attributes']['quantity']}}">
							@if($SPI['attributes']['validation_messages']->has('quantity'))
								<script>
										document.getElementById('quantity').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPI['attributes']['validation_messages']->first("quantity") }}</div>
							@endif
						</div>

						<div class="col-sm-2">
							<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="ADD">
						</div>

					</div>

					<hr>

					@if($SPI['attributes']['spi_id'] !== '#Auto#')

						<table id="tblgrid1" class="table table-hover table-sm table-bordered">

							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="width: 10%; background-color: lightgreen;">SPI ID</td>
								<td style="width: 10%; background-color: lightgreen;">SPI Date</td>
								<td style="width: 10%; background-color: lightgreen;">Type</td>
								<td colspan="3" style="width: 70%; background-color: lightgreen;">Spare Part</td>
							</tr>

							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="width: 10%;">{{$SPI['attributes']['spi_id']}}</td>
								<td style="width: 10%;">{{$SPI['attributes']['spi_date']}}</td>
								<td style="width: 10%;">{{$SPI['attributes']['part_type']}}</td>
								<td colspan="3" style="width: 70%;">{{$SPI['attributes']['spare_part_name']}}</td>
							</tr>

						</table>
							
					@endif

	
				</div>
	
			</div>
	
		</div>
	
	</form>
	</div>



@endsection
