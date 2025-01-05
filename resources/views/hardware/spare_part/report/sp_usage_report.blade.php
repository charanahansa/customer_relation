@extends('layouts.hw')

@section('title')
    Spare Part Usage Report
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">

	<form method="POST" action="{{route('sp_usage_report_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Spare Part Usage Report
				</div>

				<div class="card-body">

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Date</label>
						<div class="col-sm-2">
							<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$SPUR['attributes']['from_date']}}">
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Date</label>
						<div class="col-sm-2">
							<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$SPUR['attributes']['to_date']}}">
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
						<div class="col-sm-5">
							<select name="spare_part_id" id="spare_part_id" class="form-control form-control-sm">
								@foreach($SPUR['spare_part'] as $row)
									@if($SPUR['attributes']['spare_part_id'] == $row->spare_part_id)
										<option value ="{{$row->spare_part_id}}" selected>{{$row->spare_part_name}}</option>
									@else
										<option value ="{{$row->spare_part_id}}">{{$row->spare_part_name}}</option>
									@endif
								@endforeach
								@if($SPUR['attributes']['spare_part_id']== 0)
									<option value ="0" selected>Select the Spare Part</option>
								@else
									<option value ="0">Select the Spare Part</option>
								@endif
							</select>
						</div>


					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
						<div class="col-sm-2">
							<select name="bank" id="bank" class="form-control form-control-sm">
								@foreach($SPUR['bank'] as $row)
									@if($SPUR['attributes']['bank'] == $row->bank)
										<option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
									@else
										<option value ="{{$row->bank}}">{{$row->bank}}</option>
									@endif
								@endforeach
								@if($SPUR['attributes']['bank']== '0')
									<option value ="0" selected>Select the Bank</option>
								@else
									<option value ="0">Select the Bank</option>
								@endif
							</select>
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
						<div class="col-sm-2">
							<select name="model" id="model" class="form-control form-control-sm">
								@foreach($SPUR['model'] as $row)
									@if($SPUR['attributes']['model'] == $row->model)
										<option value ="{{$row->model}}" selected>{{$row->model}}</option>
									@else
										<option value ="{{$row->model}}">{{$row->model}}</option>
									@endif
								@endforeach
								@if($SPUR['attributes']['model']== '0')
									<option value ="0" selected>Select the Model</option>
								@else
									<option value ="0">Select the Model</option>
								@endif
							</select>
						</div>

						<div class="col-sm-2">
							<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Report">
						</div>

					</div>


				</div>

			</div>

		</div>

	</form>

</div>

@endsection
