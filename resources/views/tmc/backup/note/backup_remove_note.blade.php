@extends('layouts.tmc')

@section('title')
    Backup Remove Note
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('backup_remove_note_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Backup Remove Note
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
								<input type="text" name="brn_no" class="form-control form-control-sm" id="brn_no" value="{{$BRN['attributes']['brn_no']}}" readonly>
								@if($BRN['attributes']['validation_messages']->has('brn_no'))
                                    <script>
                                            document.getElementById('brn_no').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("brn_no") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
							<div class="col-sm-4">
								<select name="bank" id="bank" class="form-select form-select-sm">
									@foreach($BRN['bank'] as $row)
										@if($BRN['attributes']['bank'] == $row->bank)
											<option value="{{$row->bank}}" selected>{{$row->bank}} </option>
										@else
											<option value="{{$row->bank}}"> {{$row->bank}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['bank'] === 0)
                                        <option value =0 selected>Select the bank</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('bank'))
                                    <script>
                                            document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("bank") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
							<div class="col-sm-4">
								<input type="date" name="brn_date" class="form-control form-control-sm" id="brn_date" value="{{$BRN['attributes']['brn_date']}}">
								@if($BRN['attributes']['validation_messages']->has('brn_date'))
                                    <script>
                                            document.getElementById('brn_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("brn_date") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm"> Tid</label>
							<div class="col-sm-4">
								<input type="text" name="tid" class="form-control form-control-sm" id="tid" value="{{$BRN['attributes']['tid']}}" onkeypress="tid_keydown(event)">
								@if($BRN['attributes']['validation_messages']->has('tid'))
                                    <script>
                                            document.getElementById('tid').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("tid") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-2 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Merchant</label>
							<div class="col-sm-10">
								<textarea name="merchant" class="form-control form-control-sm" id="merchant" style="resize:none" rows="3">{{$BRN['attributes']['merchant']}}</textarea>
								@if($BRN['attributes']['validation_messages']->has('merchant'))
                                    <script>
                                            document.getElementById('merchant').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("merchant") }}</div>
                                @endif
							</div>
						</div>

                        <div class="mb-1 row">
							<label for="zone_id" class="col-sm-2 col-form-label-sm">Zone</label>
							<div class="col-sm-10">
								<select name="zone_id" id="zone_id" class="form-select form-select-sm">
									@foreach($BRN['zone'] as $row)
										@if($BRN['attributes']['zone_id'] == $row->zone_id)
											<option value={{$row->zone_id}} selected>{{$row->zone_name}} </option>
										@else
											<option value={{$row->zone_id}}> {{$row->zone_name}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['zone_id'] === 0)
                                        <option value =0 selected>Select the Zone</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('zone_id'))
                                    <script>
                                            document.getElementById('zone_id').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("zone_id") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Backup SNo.</label>
							<div class="col-sm-4">
								<input type="text" name="backup_serialno" class="form-control form-control-sm" id="backup_serialno" value="{{$BRN['attributes']['backup_serialno']}}">
								@if($BRN['attributes']['validation_messages']->has('backup_serialno'))
                                    <script>
                                            document.getElementById('backup_serialno').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("backup_serialno") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
							<div class="col-sm-4">
								<select name="backup_model" id="backup_model" class="form-select form-select-sm">
									@foreach($BRN['model'] as $row)
										@if($BRN['attributes']['backup_model'] == $row->model)
											<option value={{$row->model}} selected>{{$row->model}} </option>
										@else
											<option value={{$row->model}}> {{$row->model}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['backup_model'] === 0)
                                        <option value =0 selected>Select the Model</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('backup_model'))
                                    <script>
                                            document.getElementById('backup_model').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("backup_model") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Replaced No.</label>
							<div class="col-sm-4">
								<input type="text" name="replaced_serialno" class="form-control form-control-sm" id="replaced_serialno" value="{{$BRN['attributes']['replaced_serialno']}}">
								@if($BRN['attributes']['validation_messages']->has('replaced_serialno'))
                                    <script>
                                            document.getElementById('replaced_serialno').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("replaced_serialno") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
							<div class="col-sm-4">
								<select name="replaced_model" id="replaced_model" class="form-select form-select-sm">
									@foreach($BRN['model'] as $row)
										@if($BRN['attributes']['replaced_model'] == $row->model)
											<option value={{$row->model}} selected>{{$row->model}} </option>
										@else
											<option value={{$row->model}}> {{$row->model}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['replaced_model'] === 0)
                                        <option value =0 selected>Select the Model</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('replaced_model'))
                                    <script>
                                            document.getElementById('replaced_model').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("replaced_model") }}</div>
                                @endif
							</div>
						</div>

                        <div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Received No.</label>
							<div class="col-sm-4">
								<input type="text" name="received_serialno" class="form-control form-control-sm" id="received_serialno" value="{{$BRN['attributes']['received_serialno']}}">
								@if($BRN['attributes']['validation_messages']->has('received_serialno'))
                                    <script>
                                            document.getElementById('received_serialno').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("received_serialno") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
							<div class="col-sm-4">
								<select name="received_model" id="received_model" class="form-select form-select-sm">
									@foreach($BRN['model'] as $row)
										@if($BRN['attributes']['received_model'] == $row->model)
											<option value={{$row->model}} selected>{{$row->model}} </option>
										@else
											<option value={{$row->model}}> {{$row->model}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['received_model'] == 0)
                                        <option value =0 selected>Select the Model</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('received_model'))
                                    <script>
                                            document.getElementById('received_model').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("received_model") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Sub Status</label>
							<div class="col-sm-10">
								<select name="sub_status" id="sub_status" class="form-select form-select-sm">
									@foreach($BRN['sub_status'] as $row)
										@if($BRN['attributes']['sub_status'] == $row->id)
											<option value={{$row->id}} selected>{{$row->status}} </option>
										@else
											<option value={{$row->id}}> {{$row->status}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['sub_status'] == 0)
                                        <option value =0 selected>Select the Sub Status</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('sub_status'))
                                    <script>
                                            document.getElementById('sub_status').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("sub_status") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Status</label>
							<div class="col-sm-4">
								<select name="status" id="status" class="form-select form-select-sm">
									@foreach($BRN['status'] as $row)
										@if($BRN['attributes']['status'] == $row->codeid)
											<option value={{$row->codeid}} selected>{{$row->description}} </option>
										@else
											<option value={{$row->codeid}}> {{$row->description}} </option>
										@endif
									@endforeach
									@if($BRN['attributes']['status'] === 0)
                                        <option value =0 selected>Select the Status</option>
                                    @endif
								</select>
								@if($BRN['attributes']['validation_messages']->has('status'))
                                    <script>
                                            document.getElementById('status').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("status") }}</div>
                                @endif
							</div>
							<label for="tid" class="col-sm-2 col-form-label-sm"> Done Date Time</label>
							<div class="col-sm-4">
								<input type="datetime-local" name="done_date_time" class="form-control form-control-sm" id="done_date_time" value="{{$BRN['attributes']['done_date_time']}}">
								@if($BRN['attributes']['validation_messages']->has('done_date_time'))
                                    <script>
                                            document.getElementById('done_date_time').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("done_date_time") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<div class="col-sm-8">
								<input type="text" name="cancel_reason" class="form-control form-control-sm" id="cancel_reason" value="" readonly>
							</div>
						</div>

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
								<input type="button" style="width: 100%;" class="btn btn-primary btn-sm" value="Ticket History" data-toggle="modal" data-target="#exampleModal">
							</div>
						</div>


					</div>
					</div>

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Contact No.</label>
							<div class="col-sm-10">
								<input type="text" name="contact_number" class="form-control form-control-sm" id="contact_number" value="{{$BRN['attributes']['contact_number']}}">
								@if($BRN['attributes']['validation_messages']->has('contact_number'))
                                    <script>
                                            document.getElementById('contact_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("contact_number") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Contact Per.</label>
							<div class="col-sm-10">
								<input type="text" name="contact_person" class="form-control form-control-sm" id="contact_person" value="{{$BRN['attributes']['contact_person']}}">
								@if($BRN['attributes']['validation_messages']->has('contact_person'))
                                    <script>
                                            document.getElementById('contact_person').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $BRN['attributes']['validation_messages']->first("contact_person") }}</div>
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

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Field Officer</label>
							<div class="col-sm-10">
								<select name="field_officer" id="field_officer" class="form-select form-select-sm">
									@foreach($BRN['officer'] as $row)
										@if($BRN['attributes']['officer'] == $row->officer_id)
											<option value={{$row->officer_id}}> {{$row->name}} </option>
										@endif
									@endforeach
								</select>
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Courier</label>
							<div class="col-sm-10">
								<select name="courier" id="courier" class="form-select form-select-sm">
									@foreach($BRN['courier'] as $row)
										@if($BRN['attributes']['courier'] == $row->id)
											<option value={{$row->id}}> {{$row->officer_name}} </option>
										@endif
									@endforeach
								</select>
							</div>
						</div>

						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">FS Remark</label>
							<div class="col-sm-10">
								<textarea name="fs_remark" class="form-control form-control-sm" id="fs_remark" style="resize:none" rows="3" readonly>{{$BRN['attributes']['fs_remark']}}</textarea>
							</div>
						</div>

					</div>
					</div>

				</div>

			</div>
		</div>

	</div>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ticket History</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					@foreach($BRN['attributes']['history']  as $row)

						Modified By {{$row->userid}} On {{$row->tdatetime}} {{$row->field_name}} Changed - Old Value <i><b> {{$row->old_value}} </b></i>  New Value <b> {{$row->new_value}} </b> <br>

					@endforeach
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>

		elementHide();
		function elementHide(){
			document.getElementById("cancel_reason").style.display='none';
		}

		function cancel_process(evt){

			var txt;

			var reason = prompt("Please enter Cancel Reason:", "");

			if (reason == null || reason == "") {
				txt = "??";
			} else {
				txt = reason;
			}

			document.getElementById("cancel_reason").value = txt;
		}

		function tid_keydown(e){

			if (e.keyCode == 13) {

				get_merchant_detail();
			}
		}

		function get_merchant_detail(){

			var tid = document.getElementById('tid').value;

			$.ajax({

				url:"brn_merchant_detail",
				data:{
					terminal_id: tid
				},
				error: function(xhr, status, error) {
					var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
					alert('Backup Removal Note Error -  ' + errorMessage);
				},
				success:function(response){

                    var result = response;
                    var result_detail = result.split("[]");
                    var merchant = result_detail[0];
                    var contact_number = result_detail[1];

					document.getElementById('merchant').value = merchant;
                    document.getElementById('contact_number').value = contact_number;
				}

        	});
		}

		$('#tid').keypress(function (e) {
			if (e.which == 13) {
				e.preventDefault();
			}
		});

		$('#backup_serialno').keypress(function (e) {
			if (e.which == 13) {
				e.preventDefault();
			}
		});

		$('#replaced_serialno').keypress(function (e) {
			if (e.which == 13) {
				e.preventDefault();
			}
		});

		$('#contact_number').keypress(function (e) {
			if (e.which == 13) {
				e.preventDefault();
			}
		});

		$('#contact_person').keypress(function (e) {
			if (e.which == 13) {
				e.preventDefault();
			}
		});

	</script>

</form>
</div>



@endsection
