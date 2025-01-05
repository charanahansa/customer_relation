@extends('layouts.tmc')

@section('title')
    Backup Receive Note
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('backup_receive_note_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Backup Receive Note
			</div>

			<div class="card-body">

				<div class="col-sm-12">
					<?php echo $BRN['attributes']['process_message']; ?>
				</div>

				<div class="row no-gutters">

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-1 row">
							<label for="tid" class="col-sm-6 col-form-label-sm"></label>
							<label for="tid" class="col-sm-2 col-form-label-sm">BRN ID</label>
							<div class="col-sm-4">
								<input type="text" name="brn_id" class="form-control form-control-sm" id="brn_id" value="{{$BRN['attributes']['brn_id']}}" readonly>
								@if($BRN['attributes']['validation_messages']->has('brn_id'))
                                    <script>
                                            document.getElementById('brn_id').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("brn_id") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
							<div class="col-sm-4">
								<select name="model" id="model" class="form-select form-select-sm">
									@foreach($BRN['model'] as $row)
										@if($BRN['attributes']['model'] == $row->model)
											<option value={{$row->model}} selected>{{$row->model}} </option>
										@else
											<option value={{$row->model}}> {{$row->model}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['model'] == 0)
                                        <option value ="0" selected>Select the Model</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('model'))
                                    <script>
                                            document.getElementById('model').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("model") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
							<div class="col-sm-4">
								<input type="date" name="brn_date" class="form-control form-control-sm" id="brn_date" value="{{$BRN['attributes']['date']}}">
								@if($BRN['attributes']['validation_messages']->has('brn_date'))
                                    <script>
                                            document.getElementById('brn_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("brn_date") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Delivery No.</label>
							<div class="col-sm-4">
								<input type="text" name="delivery_number" class="form-control form-control-sm" id="delivery_number" value="{{$BRN['attributes']['delivery_no']}}">
								@if($BRN['attributes']['validation_messages']->has('delivery_number'))
                                    <script>
                                            document.getElementById('delivery_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("delivery_number") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
							<div class="col-sm-10">
								<textarea name="remark" class="form-control form-control-sm" id="remark" style="resize:none" rows="3">{{$BRN['attributes']['remark']}}</textarea>
								@if($BRN['attributes']['validation_messages']->has('remark'))
                                    <script>
                                            document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("remark") }}</div>
                                @endif
							</div>
						</div>
						<br>
						<hr>
						<div class="mb-2 row">

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
							</div>

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Reset">
							</div>

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Cancel" onclick="cancel_process(event)">
							</div>

							<div class="col-sm-3">
								<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Confirm">
							</div>
						</div>


					</div>
					</div>

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-2 row">
							<div class="col-sm-12">
								<textarea name="terminal_serial" class="form-control form-control-sm" id="terminal_serial" style="resize:none" col="7" rows="20">{{$BRN['attributes']['terminal_serial']}}</textarea>
							</div>
						</div>

					</div>
					</div>

				</div>

			</div>
		</div>

	</div>

</form>
</div>

@endsection
