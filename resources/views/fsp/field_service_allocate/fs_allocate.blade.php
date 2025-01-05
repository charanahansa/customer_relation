@extends('layouts.fsp')

@section('title')
    {{$FSA['attributes']['workflow_name']}} - Field Service Team Lead
@endsection

@section('body')

<div id="tbldiv" style="width: 99%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<div class="col-sm-12">
<form method="POST" action="{{route('field_service_allocation_process')}}">

    @CSRF

    <div class="card">

        <div class="card-header">
            {{$FSA['attributes']['workflow_name']}} Allocation Process
        </div>

        <div class="card-body">

            <div class="col-sm-12">
                <?php echo $FSA['attributes']['process_message']; ?>
            </div>

            <div class="row no-gutters">

                <div class="col-12 col-sm-6 col-md-6">
                <div style="margin-left: 1%; margin-right: 1%;">

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-6 col-form-label-sm"></label>
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Ticket No.</label>
                        <div class="col-sm-4">
							<input type="text" name="ticket_number" id="ticket_number" class="form-control form-control-sm" value="{{$FSA['attributes']['ticket_no']}}" readonly>
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Bank</label>
                        <div class="col-sm-4">
							<input type="text" name="bank" id="bank" class="form-control form-control-sm" value="{{$FSA['attributes']['bank']}}" readonly>
						</div>
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Date</label>
                        <div class="col-sm-4">
							<input type="text" name="report_date" id="report_date" class="form-control form-control-sm" value="{{$FSA['attributes']['date']}}" readonly>
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Workflow</label>
                        <div class="col-sm-10">
                            <select name="workflow_id" id="workflow_id" class="form-select form-select-sm">
                                @foreach($FSA['workflow'] as $row)
                                    @if($FSA['attributes']['workflow_id'] == $row->workflow_id)
                                        <option value ="{{$row->workflow_id}}" selected>{{$row->workflow_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Tid</label>
                        <div class="col-sm-4">
							<input type="text" name="tid" id="tid" class="form-control form-control-sm" value="{{$FSA['attributes']['tid']}}" readonly>
						</div>
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Model</label>
                        <div class="col-sm-4">
							<input type="text" name="model" id="model" class="form-control form-control-sm" value="{{$FSA['attributes']['model']}}" readonly>
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Merchant</label>
                        <div class="col-sm-10">
                            <textarea  name="merchant" id="merchant" class="form-control" rows="3" style="resize:none" readonly>{{$FSA['attributes']['merchant']}}</textarea>
						</div>
                    </div>

                    @if($FSA['attributes']['workflow_id'] == 1)

                        <div class="mb-1 row">
                            <label for="fromdate" class="col-sm-2 col-form-label-sm">Error</label>
                            <div class="col-sm-10">
                                <input type="text" name="fault_description" id="fault_description" class="form-control form-control-sm" value="{{$FSA['attributes']['fault_description']}}" readonly>
                            </div>
                        </div>

                        <div class="mb-1 row">
                            <label for="fromdate" class="col-sm-2 col-form-label-sm">Relavant Dtl</label>
                            <div class="col-sm-10">
                                <input type="text" name="relevant_detail_description" id="relevant_detail_description" class="form-control form-control-sm" value="{{$FSA['attributes']['relevant_detail_description']}}" readonly>
                            </div>
                        </div>

                    @endif

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
                        <div class="col-sm-10">
                            <textarea  name="cc_remark" id="cc_remark" class="form-control" rows="2" style="resize:none" readonly>{{$FSA['attributes']['cc_remark']}}</textarea>
                        </div>
                    </div>

                    @if($FSA['attributes']['workflow_id'] == 1)

                        <div class="mb-1 row">
                            <label for="fromdate" class="col-sm-2 col-form-label-sm">Call Handler</label>
                            <div class="col-sm-10">
                                <input type="text" name="call_handle_by" id="call_handle_by" class="form-control form-control-sm" value="{{$FSA['attributes']['call_handle_by']}}" readonly>
                            </div>
                        </div>

                    @endif

                </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6">
                <div style="margin-left: 1%; margin-right: 1%;">

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-6 col-form-label-sm"></label>
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Date</label>
                        <div class="col-sm-4">
							<input type="date" name="ftl_date" id="ftl_date" class="form-control form-control-sm" value="{{$FSA['attributes']['ftl_date']}}">
                            @if($FSA['attributes']['validation_messages']->has('Allocated Date'))
								<script>
										document.getElementById('ftl_date').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Allocated Date") }}</div>
							@endif
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Contact No.</label>
                        <div class="col-sm-10">
							<input type="text" name="contact_number" id="contact_number" class="form-control form-control-sm" value="{{$FSA['attributes']['contact_number']}}">
                            @if($FSA['attributes']['validation_messages']->has('Contact Number'))
								<script>
										document.getElementById('contact_number').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Contact Number") }}</div>
							@endif
						</div>
                    </div>

                    @if($FSA['attributes']['workflow_id'] == 1)

                        <div class="mb-1 row">
                            <label for="fromdate" class="col-sm-2 col-form-label-sm">Fault</label>
                            <div class="col-sm-10">
                                <select name="fault" id="fault" class="form-select form-select-sm">
                                    @foreach($FSA['fault'] as $row)
                                        @if($FSA['attributes']['fault'] == $row->eno)
                                            <option value ="{{$row->eno}}" selected>{{$row->error}}</option>
                                        @else
                                            <option value ="{{$row->eno}}">{{$row->error}}</option>
                                        @endif
                                    @endforeach
                                    @if($FSA['attributes']['fault'] == "Not")
                                        <option value ="Not" selected>Select the Fault</option>
                                    @else
                                        <option value ="Not">Select the Fault</option>
                                    @endif
                                </select>
                                @if($FSA['attributes']['validation_messages']->has('Fault'))
                                    <script>
                                            document.getElementById('fault').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Fault") }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-1 row">
                            <label for="fromdate" class="col-sm-2 col-form-label-sm">Relavant Dtl</label>
                            <div class="col-sm-10">
                                <select name="relevant_detail" id="relevant_detail" class="form-select form-select-sm">
                                    @foreach($FSA['relevant_detail'] as $row)
                                        @if($FSA['attributes']['relevant_detail'] == $row->rno)
                                            <option value ={{$row->rno}} selected>{{$row->relevant_detail}}</option>
                                        @else
                                            <option value ={{$row->rno}}>{{$row->relevant_detail}}</option>
                                        @endif
                                    @endforeach
                                    @if($FSA['attributes']['relevant_detail' ]== "Not")
                                        <option value ="Not" selected>Select the Relevant Detail</option>
                                    @else
                                        <option value ="Not">Select the Relevant Detail</option>
                                    @endif
                                </select>
                                @if($FSA['attributes']['validation_messages']->has('relevant_detail'))
                                    <script>
                                            document.getElementById('relevant_detail').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("relevant_detail") }}</div>
                                @endif
                            </div>
                        </div>

                    @endif

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
                        <div class="col-sm-10">
                            <textarea  name="ftl_remark" id="ftl_remark" class="form-control" rows="2" style="resize:none">{{$FSA['attributes']['ftl_remark']}}</textarea>
							@if($FSA['attributes']['validation_messages']->has('Remark'))
								<script>
										document.getElementById('ftl_remark').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Remark") }}</div>
							@endif
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Officer</label>
                        <div class="col-sm-10">
                            <select name="officer" id="officer" class="form-select form-select-sm">
								@foreach($FSA['officer'] as $row)
									@if($FSA['attributes']['officer'] == $row->officer_id)
										<option value ={{$row->officer_id}} selected>{{$row->name}}</option>
									@else
										<option value ={{$row->officer_id}}>{{$row->name}}</option>
									@endif
								@endforeach
								@if($FSA['attributes']['officer'] == "Not")
									<option value ="Not" selected>Select the Officer</option>
								@else
									<option value ="Not">Select the Officer</option>
								@endif
							</select>
							@if($FSA['attributes']['validation_messages']->has('field_officer'))
								<script>
										document.getElementById('officer').className = 'form-select form-select-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("field_officer") }}</div>
							@endif
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Courier</label>
                        <div class="col-sm-10">
                            <select name="courier" id="courier" class="form-select form-select-sm">
								@foreach($FSA['courier_provider'] as $row)
									@if($FSA['attributes']['courier_provider'] == $row->id)
										<option value ={{$row->id}} selected>{{$row->officer_name}}</option>
									@else
										<option value ={{$row->id}}>{{$row->officer_name}}</option>
									@endif
								@endforeach
								@if($FSA['attributes']['courier_provider'] == "Not")
									<option value ="Not" selected>Select the Courier</option>
								@else
									<option value ="Not" >Select the Courier</option>
								@endif
							</select>
							@if($FSA['attributes']['validation_messages']->has('courier_provider'))
								<script>
										document.getElementById('courier').className = 'form-select form-select-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("courier_provider") }}</div>
							@endif
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Bank Officer</label>
                        <div class="col-sm-10">
                            <select name="bank_officer" id="bank_officer" class="form-select form-select-sm">
								@foreach($FSA['bank_officer'] as $row)
									@if($FSA['attributes']['bank_officer'] == $row->id)
										<option value ="{{$row->id}}" selected>{{$row->officer_name}}</option>
									@else
										<option value ="{{$row->id}}">{{$row->officer_name}}</option>
									@endif
								@endforeach
								@if($FSA['attributes']['bank_officer'] == "Not")
									<option value ="Not" selected>Select the Bank Officer</option>
								@else
									<option value ="Not">Select the Bank Officer</option>
								@endif
							</select>
							@if($FSA['attributes']['validation_messages']->has('bank_officer'))
								<script>
										document.getElementById('bank_officer').className = 'form-select form-select-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("bank_officer") }}</div>
							@endif
						</div>
                    </div>

                    @if($FSA['attributes']['workflow_id'] == 1)

                        <div class="mb-1 row">
                            <label for="fromdate" class="col-sm-2 col-form-label-sm">ActionTaken</label>
                            <div class="col-sm-10">
                                <select name="action_taken" id="action_taken" class="form-select form-select-sm">
                                    @foreach($FSA['action_taken'] as $row)
                                        @if($FSA['attributes']['action_taken'] == $row->ano)
                                            <option value ="{{$row->ano}}" selected>{{$row->action_taken}}</option>
                                        @else
                                            <option value ="{{$row->ano}}">{{$row->action_taken}}</option>
                                        @endif
                                    @endforeach
                                    @if($FSA['attributes']['action_taken'] == "Not")
                                        <option value ="Not" selected>Select the Action Taken</option>
                                    @else
                                        <option value ="Not">Select the Action Taken</option>
                                    @endif
                                </select>
                                @if($FSA['attributes']['validation_messages']->has('Action Taken'))
                                    <script>
                                            document.getElementById('action_taken').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Action Taken") }}</div>
                                @endif
                            </div>
                        </div>

                    @endif

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Pod No.</label>
                        <div class="col-sm-4">
							<input type="text" name="pod_number" id="pod_number" class="form-control form-control-sm" value="{{$FSA['attributes']['pod_number']}}" readonly>
						</div>
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Pod Date</label>
                        <div class="col-sm-4">
							<input type="text" name="pod_date" id="pod_date" class="form-control form-control-sm" value="{{$FSA['attributes']['pod_date']}}" readonly>
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Sub Status</label>
                        <div class="col-sm-10">
                            <select name="sub_status" id="sub_status" class="form-select form-select-sm">
								@foreach($FSA['sub_status'] as $row)
									@if( strpos($row->jobrole, 'ftl') !== false )
										@if($FSA['attributes']['sub_status'] == $row->id)
											<option value ="{{$row->id}}" selected>{{$row->status}}</option>
										@else
											<option value ="{{$row->id}}">{{$row->status}}</option>
										@endif
									@endif
								@endforeach
								@if($FSA['attributes']['sub_status'] == "Not")
									<option value ="Not" selected>Select the Sub Status</option>
								@else
									<option value ="Not">Select the Sub Status</option>
								@endif
							</select>
							@if($FSA['attributes']['validation_messages']->has('Sub Status'))
								<script>
										document.getElementById('sub_status').className = 'form-select form-select-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Sub Status") }}</div>
							@endif
						</div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Status</label>
                        <div class="col-sm-4">
							<select name="status" id="status" class="form-select form-select-sm">
								@foreach($FSA['status'] as $row)
									@if($FSA['attributes']['status'] == $row->codeid)
										<option value ="{{$row->codeid}}" selected>{{$row->description}}</option>
									@else
										@if( ( ($FSA['attributes']['workflow_id'] == 6) || ($FSA['attributes']['workflow_id'] == 5) ) && ( ($row->codeid == 'done') || ($row->codeid == 'closed') ) )
										@else
											<option value ="{{$row->codeid}}">{{$row->description}}</option>
										@endif
									@endif
								@endforeach
								@if($FSA['attributes']['status'] == "Not")
									<option value ="Not" selected>Select the Status</option>
								@else
									<option value ="Not">Select the Status</option>
								@endif
							</select>
							@if($FSA['attributes']['validation_messages']->has('status'))
								<script>
										document.getElementById('status').className = 'form-select form-select-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("status") }}</div>
							@endif
						</div>
                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Done Date Time</label>
                        <div class="col-sm-4">
							<input type="text" name="done_date_time" id="done_date_time" class="form-control form-control-sm" value="{{$FSA['attributes']['done_date_time']}}">
							@if($FSA['attributes']['validation_messages']->has('Done Date Time'))
								<script>
										document.getElementById('done_date_time').className = 'form-select form-select-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $FSA['attributes']['validation_messages']->first("Done Date Time") }}</div>
							@endif
						</div>
                    </div>

                </div>
                </div>

                <hr>

                <div class="mb-1 row">
                    <div class="col-sm-2">
                        <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Email">
                    </div>
                    <div class="col-sm-2">
                        <input type="button" name="field_service" id="field_service" style="width: 100%;" class="btn btn-primary btn-sm" value="Field Service" data-bs-toggle="modal" data-bs-target="#FieldServiceModal">
                    </div>
                    <div class="col-sm-2">
                        <input type="button" style="width: 100%;" class="btn btn-primary btn-sm" value="History" data-bs-toggle="modal" data-bs-target="#HistoryModal">
                    </div>
                </div>

            </div>

        </div>

		<!-- History Modal -->
		<div class="modal fade" id="HistoryModal" tabindex="-1" aria-labelledby="HistoryModalLabel" aria-hidden="true">
			<div class="modal-xl modal-dialog">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title" id="HistoryModalLabel">Breakdown Ticket History</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					
					<div class="modal-body">
						@foreach($FSA['history']  as $row)

							Modified By {{$row->userid}} On {{$row->tdatetime}} {{$row->field_name}} Changed - Old Value <i><b> {{$row->old_value}} </b></i>  New Value <b> {{$row->new_value}} </b> <br>

						@endforeach
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>

				</div>
			</div>
		</div>

		<!-- Field Service Modal -->
		<div class="modal fade" id="FieldServiceModal" tabindex="-1" aria-labelledby="FieldServiceModalLabel" aria-hidden="true">
			
			<div class="modal-dialog modal-fullscreen">

				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title" id="FieldServiceModalLabel"> Field Service </h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					
					<div class="modal-body">

						<div class="row no-gutters">

							<div class="col-12 col-sm-6 col-md-6">
							<div style="margin-left: 1%; margin-right: 1%;">

								@foreach( $FSA['field_service']  as $row )

									<div class="mb-1 row">
				                        <label for="fromdate" class="col-sm-6 col-form-label-sm"></label>
										<label for="fromdate" class="col-sm-2 col-form-label-sm">FS Date</label>
				                        <div class="col-sm-4">
											<input type="text" name="fs_date" id="fs_date" class="form-control form-control-sm" value="{{$row->tdate}}" readonly>
										</div>
				                    </div>

									<div class="mb-1 row">
				                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Contact No.</label>
				                        <div class="col-sm-10">
											<input type="text" name="fs_contact_number" id="fs_contact_number" class="form-control form-control-sm" value="{{$row->contactno}}" readonly>
										</div>
				                    </div>

									<div class="mb-1 row">
				                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Contact Per</label>
				                        <div class="col-sm-10">
											<input type="text" name="fs_contact_person" id="fs_contact_person" class="form-control form-control-sm" value="{{$row->contact_person}}" readonly>
										</div>
				                    </div>

									<div class="mb-1 row">
				                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Merchant</label>
				                        <div class="col-sm-10">
				                            <textarea  name="fs_merchant" id="fs_merchant" class="form-control" rows="2" style="resize:none" readonly>{{$row->merchant}}</textarea>
										</div>
				                    </div>

									@if( ($FSA['attributes']['workflow_id'] == 1) || ($FSA['attributes']['workflow_id'] == 3) )

										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Type</label>
					                        <div class="col-sm-4">
												@if( $row->type == 'visit')
													<input type="text" name="fs_type" id="fs_type" class="form-control form-control-sm" value="Visit" readonly>
												@elseif( $row->type == 'phone' )
													<input type="text" name="fs_type" id="fs_type" class="form-control form-control-sm" value="Visit" readonly>
												@else
													<input type="text" name="fs_type" id="fs_type" class="form-control form-control-sm" value="" readonly>
												@endif
											</div>
											<label for="fromdate" class="col-sm-2 col-form-label-sm">Model</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_model" id="fs_model" class="form-control form-control-sm" value="{{$row->model}}" readonly>
											</div>
					                    </div>

									@endif

									@if( ($FSA['attributes']['workflow_id'] == 1) )

										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Replaced Sno</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_replaced_sno" id="fs_replaced_sno" class="form-control form-control-sm" value="{{$row->replaced_serialno}}" readonly>
											</div>
											<label for="fromdate" class="col-sm-2 col-form-label-sm">Fault Sno</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_fault_sno" id="fs_fault_sno" class="form-control form-control-sm" value="{{$row->fault_serialno}}" readonly>
											</div>
					                    </div>

										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Sim No.</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_simno" id="fs_simno" class="form-control form-control-sm" value="{{$row->simno}}" readonly>
											</div>
					                    </div>

										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Backup Request</label>
					                        <div class="col-sm-4">
												@if( $row->backup_request == 1)
													<input type="text" name="fs_backup_request" id="fs_backup_request" class="form-control form-control-sm" value="Yes" readonly>
												@else
													<input type="text" name="fs_backup_request" id="fs_backup_request" class="form-control form-control-sm" value="No" readonly>
												@endif
											</div>
					                    </div>

									@endif

									@if( ($FSA['attributes']['workflow_id'] == 2) || ($FSA['attributes']['workflow_id'] == 3) || ($FSA['attributes']['workflow_id'] == 4) || ($FSA['attributes']['workflow_id'] == 6) )

										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Serial No.</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_serialno" id="fs_serialno" class="form-control form-control-sm" value="{{$row->serialno}}" readonly>
											</div>
											<label for="fromdate" class="col-sm-2 col-form-label-sm">Model</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_model" id="fs_model" class="form-control form-control-sm" value="{{$row->model}}" readonly>
											</div>
					                    </div>

									@endif

									@if( ($FSA['attributes']['workflow_id'] == 6) )

										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Removed Sno</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_serialno" id="fs_serialno" class="form-control form-control-sm" value="{{$row->removed_serialno}}" readonly>
											</div>
											<label for="fromdate" class="col-sm-2 col-form-label-sm">Removed Model</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_model" id="fs_model" class="form-control form-control-sm" value="{{$row->removed_model}}" readonly>
											</div>
					                    </div>

									@endif


									<div class="mb-1 row">
				                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Reamrk</label>
				                        <div class="col-sm-10">
				                            <textarea  name="fs_remark" id="fs_remark" class="form-control" rows="2" style="resize:none" readonly>{{$row->remark}}</textarea>
										</div>
				                    </div>									

									<div class="mb-1 row">
				                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Sub Status</label>
				                        <div class="col-sm-10">
											@foreach($FSA['sub_status'] as $status_row)
												@if($status_row->id == $row->sub_status)
													<input type="text" name="fs_sub_status" id="fs_sub_status" class="form-control form-control-sm" value="{{$status_row->status}}" readonly>
												@endif
											@endforeach
										</div>
				                    </div>

									@if( ($FSA['attributes']['workflow_id'] != 5))
										<div class="mb-1 row">
					                        <label for="fromdate" class="col-sm-2 col-form-label-sm">Status</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_status" id="fs_status" class="form-control form-control-sm" value="{{ucfirst($row->status)}}" readonly>
											</div>
											<label for="fromdate" class="col-sm-2 col-form-label-sm">Done Date Time</label>
					                        <div class="col-sm-4">
												<input type="text" name="fs_done_date_time" id="fs_done_date_time" class="form-control form-control-sm" value="{{$row->done_date_time}}" readonly>
											</div>
					                    </div>
									@endif


								@endforeach


							</div>
							</div>

							<div class="col-12 col-sm-6 col-md-6">
							<div style="margin-left: 1%; margin-right: 1%;">								
								
								@if( $FSA['attributes']['workflow_id'] == 1 )

									<h5 style='font-family: Consolas; font-size: 16px;'> <u> Faults </u></h5>
									<ul>
									@foreach( $FSA['field_fault'] as $row )

										<li style='font-family: Consolas; font-size: 14px;'> {{$row->faults}} </li>

									@endforeach
									</ul>

									<h5 style='font-family: Consolas; font-size: 16px;'> <u> Action Taken </u></h5>
									<ul>
									@foreach( $FSA['field_action_taken'] as $row )

										<li style='font-family: Consolas; font-size: 14px;'> {{$row->action_taken}} </li>

									@endforeach
									</ul>

									<h5 style='font-family: Consolas; font-size: 16px;'> <u> Requsted Spare Part </u></h5>
									<ul>
									@foreach( $FSA['field_spare_part'] as $row )

										<li style='font-family: Consolas; font-size: 14px;'> {{$row->spare_part_name}} </li>

									@endforeach
									</ul>

								@endif

							</div>
							</div>

						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>

				</div>

			</div>
		</div>

    </div>

</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
	
	$("#done_date_time").datetimepicker(
		{
			format:'Y/m/d H:i:s',
			step: 5
		}
	);

</script>
	
</div>


@endsection
