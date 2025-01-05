@extends('layouts.hw')

@section('title')
    Spare Part Receive Note
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_receive_note_process')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Spare Part Receive Note
				</div>
	
				<div class="card-body">

					<div class="col-sm-12">
						<?php echo $SPR['attributes']['process_message']; ?>
					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Date</label>
						<div class="col-sm-2">
							<input type="text" name="spr_date" id="spr_date" class="form-control form-control-sm" value="{{$SPR['attributes']['spr_date']}}" readonly>
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Buyer</label>
						<div class="col-sm-3">
							<select name="buyer" id="buyer" class="form-control form-control-sm">
								@foreach($SPR['buyer'] as $row)
									@if($SPR['attributes']['buyer_id'] == $row->buyer_id)
										<option value ="{{$row->buyer_id}}" selected>{{$row->buyer_name}}</option>
									@else
										<option value ="{{$row->buyer_id}}">{{$row->buyer_name}}</option>
									@endif
								@endforeach
								@if($SPR['attributes']['buyer_id']== 0)
									<option value ="0" selected>Select the Buyer</option>
								@endif
							</select>
							@if($SPR['attributes']['validation_messages']->has('buyer'))
								<script>
										document.getElementById('buyer').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("buyer") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">SP Bin</label>
						<div class="col-sm-3">
							<select name="bin" id="bin" class="form-control form-control-sm">
								<option value ="1" selected>Main Bin</option>
							</select>
							@if($SPR['attributes']['validation_messages']->has('bin'))
								<script>
										document.getElementById('bin').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("bin") }}</div>
							@endif
						</div>
						
					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Part Type</label>
						<div class="col-sm-2">
							<select name="part_type" id="part_type" class="form-control form-control-sm">
								<option value ="New">New</option>
								<option value ="Used">Used</option>
							</select>
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">JobCard No.</label>
						<div class="col-sm-2">
							<input type="text" name="jobcard_no" id="jobcard_no" class="form-control form-control-sm" value="{{$SPR['attributes']['jobcard_no']}}" readonly>
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Remark</label>
						<div class="col-sm-10">
							<input type="text" name="remark" id="remark" class="form-control form-control-sm" value="{{$SPR['attributes']['remark']}}">
							@if($SPR['attributes']['validation_messages']->has('remark'))
								<script>
										document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("remark") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
						<div class="col-sm-7">
							<select name="spare_part" id="spare_part" class="form-control form-control-sm">
								@foreach($SPR['spare_part'] as $row)
									@if($SPR['attributes']['spare_part_id'] == $row->spare_part_id)
										<option value ="{{$row->spare_part_id}}" selected>{{$row->spare_part_name}}</option>
									@else
										<option value ="{{$row->spare_part_id}}">{{$row->spare_part_name}}</option>
									@endif
								@endforeach
								@if($SPR['attributes']['spare_part_id']== '')
									<option value ="0" selected>Select the Spare Part</option>
								@endif
							</select>
							@if($SPR['attributes']['validation_messages']->has('spare_part'))
								<script>
										document.getElementById('spare_part').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("spare_part") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Buying Price</label>
						<div class="col-sm-2">
							<input type="text" name="price" id="price" class="form-control form-control-sm" value="">
							@if($SPR['attributes']['validation_messages']->has('price'))
								<script>
										document.getElementById('price').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("price") }}</div>
							@endif
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Quantity</label>
						<div class="col-sm-2">
							<input type="text" name="quantity" id="quantity" class="form-control form-control-sm" value="">
							@if($SPR['attributes']['validation_messages']->has('quantity'))
								<script>
										document.getElementById('quantity').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("quantity") }}</div>
							@endif
						</div>

						<div class="col-sm-3">
							<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="ADD">
						</div>

					</div>

					<hr>
					
					@if($SPR['attributes']['spr_id'] !== '#Auto#')

						<table id="tblgrid1" class="table table-hover table-sm table-bordered">

							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="width: 10%; background-color: lightgreen;">SPR ID</td>
								<td style="width: 10%; background-color: lightgreen;">SPR Date</td>
								<td colspan="3" style="width: 80%; background-color: lightgreen;">Spare Part</td>
							</tr>
							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="width: 10%;">{{$SPR['attributes']['spr_id']}}</td>
								<td style="width: 10%;">{{$SPR['attributes']['spr_date']}}</td>
								<td colspan="3" style="width: 80%;">{{$SPR['attributes']['spare_part_name']}}</td>
							</tr>
							
							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="width: 10%; background-color: lightgreen;">Part Type </td>
								<td style="width: 10%; background-color: lightgreen;">JobCard No.</td>
								<td style="width: 10%; background-color: lightgreen;">Quantity</td>
							</tr>
							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="width: 10%;">{{$SPR['attributes']['part_type']}}</td>
								<td style="width: 10%;">{{$SPR['attributes']['jobcard_no']}}</td>
								<td style="width: 10%;">{{$SPR['attributes']['quantity']}}</td>
							</tr>

							<tr style="font-family: Consolas; font-size: 14px;">
								<td style="background-color: lightgreen;">Buyer</td>
								<td style="background-color: lightgreen;">Bin</td>
								<td colspan="3" style="width: 50%; background-color: lightgreen;">Remark</td>
							</tr>
							<tr style="font-family: Consolas; font-size: 14px;">
								<td>{{$SPR['attributes']['buyer_name']}}</td>
								<td>{{$SPR['attributes']['bin_name']}}</td>
								<td colspan="3">{{$SPR['attributes']['remark']}}</td>
							</tr>

						</table>
							
					@endif

				</div>
	
			</div>
	
		</div>
	
	</form>
	</div>



@endsection