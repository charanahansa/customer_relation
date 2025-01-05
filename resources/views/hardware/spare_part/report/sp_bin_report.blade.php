@extends('layouts.hw')

@section('title')
    Spare Part Bin Report
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">

	<form method="POST" action="{{route('sp_bin_report_process')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Spare Part Bin Report
				</div>
	
				<div class="card-body">

                    <div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Bin</label>
						<div class="col-sm-3">
							<select name="bin_id" id="bin_id" class="form-control form-control-sm">
								@foreach($SBR['bin'] as $row)
									@if($SBR['attributes']['bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SBR['attributes']['bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@else
									<option value ="0">Select the Bin</option>
								@endif
							</select>
							@if($SBR['attributes']['validation_messages']->has('bin_id'))
								<script>
										document.getElementById('bin_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SBR['attributes']['validation_messages']->first("bin_id") }}</div>
							@endif
						</div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
						<div class="col-sm-4">
							<select name="spare_part_id" id="spare_part_id" class="form-control form-control-sm">
								@foreach($SBR['spare_part'] as $row)
									@if($SBR['attributes']['spare_part_id'] == $row->spare_part_id)
										<option value ="{{$row->spare_part_id}}" selected>{{$row->spare_part_name}}</option>
									@else
										<option value ="{{$row->spare_part_id}}">{{$row->spare_part_name}}</option>
									@endif
								@endforeach
								@if($SBR['attributes']['spare_part_id']== 0)
									<option value ="0" selected>Select the Spare Part</option>
								@else
									<option value ="0">Select the Spare Part</option>
								@endif
							</select>
							@if($SBR['attributes']['validation_messages']->has('spare_part_id'))
								<script>
										document.getElementById('spare_part_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SBR['attributes']['validation_messages']->first("spare_part_id") }}</div>
							@endif
						</div>

                        <div class="col-sm-3">
							<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Genarate">
						</div>

                    </div>

					<hr>

					<div class="table-responsive">
					<div id="tbldiv" style="width: 100%;">

						<table id="sp_request" class="table table-hover table-sm table-bordered">
							<?php $icount = 1; ?>
							<thead>
								
								<tr style="font-family: Consolas; font-size: 13px;">
									<th>No</th>
									<th>Bin</th>
									<th>Spare Part</th>
									<th>Total</th>
								</tr>

							</thead>
							@if(count($SBR['data_table']))

								<tbody>
									
									@foreach($SBR['data_table'] as $row)

										<tr style="font-family: Consolas; font-size: 14px;">
											<td>{{$icount}}</td>
											<td>{{$row->bin_name}}</td>
											<td>{{$row->spare_part_name}}</td>
											<td>{{$row->total_quantity}}</td>
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