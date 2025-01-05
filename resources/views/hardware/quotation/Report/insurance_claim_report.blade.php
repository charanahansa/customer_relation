@extends('layouts.hw')

@section('title')
    Insurance Claim Report
@endsection

@section('body')

	<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('insurance_claim_report_process')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Insurance Claim Report
				</div>
	
				<div class="card-body">

					<div class="row no-gutters">

						<div class="col-12 col-sm-6 col-md-6">
						<div style="margin-left: 2%; margin-right: 1%;">

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
								<div class="col-sm-4">
									<input type="date" name="icr_date" class="form-control form-control-sm" id="icr_date" value="{{$ICR['attributes']['icr_date']}}">
									@if($ICR['attributes']['validation_messages']->has('icr_date'))
										<script>
												document.getElementById('icr_date').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("icr_date") }}</div>
									@endif
								</div>
							</div>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Batch No.</label>
								<div class="col-sm-4">
									<input type="text" name="batch_no" class="form-control form-control-sm" id="batch_no" value="{{$ICR['attributes']['batch_no']}}">
									@if($ICR['attributes']['validation_messages']->has('batch_no'))
										<script>
												document.getElementById('batch_no').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("batch_no") }}</div>
									@endif
								</div>
							</div>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
								<div class="col-sm-10">
									<select name="bank" id="bank" class="form-control form-control-sm">
										@foreach($ICR['bank'] as $row)
											@if($ICR['attributes']['bank'] == $row->bank)
												<option value="{{$row->bank}}" selected>{{$row->bank}} </option>
											@else
												<option value="{{$row->bank}}"> {{$row->bank}} </option>
											@endif
										@endforeach
										@if($ICR['attributes']['bank'] == 0)
	                                        <option value ="0" selected>Select the bank</option>
	                                    @endif
									</select>
									@if($ICR['attributes']['validation_messages']->has('bank'))
	                                    <script>
	                                            document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
	                                    </script>
	                                    <div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("bank") }}</div>
	                                @endif
								</div>
							</div>

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-2 col-form-label-sm">Address To</label>
								<div class="col-sm-10">
									<textarea name="address_to" class="form-control form-control-sm" id="address_to" style="resize:none" rows="5">{{$ICR['attributes']['address_to']}}</textarea>
									@if($ICR['attributes']['validation_messages']->has('address_to'))
										<script>
												document.getElementById('address_to').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("address_to") }}</div>
									@endif
								</div>
							</div>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Attention</label>
								<div class="col-sm-10">
									<input type="text" name="attention" class="form-control form-control-sm" id="attention" value="{{$ICR['attributes']['attention']}}">
									@if($ICR['attributes']['validation_messages']->has('attention'))
										<script>
												document.getElementById('attention').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("attention") }}</div>
									@endif
								</div>
							</div>

							<div class="mb-1 row">
								<label for="tid" class="col-sm-2 col-form-label-sm">Subject</label>
								<div class="col-sm-10">
									<input type="text" name="subject" class="form-control form-control-sm" id="subject" value="{{$ICR['attributes']['subject']}}">
									@if($ICR['attributes']['validation_messages']->has('subject'))
										<script>
												document.getElementById('subject').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("subject") }}</div>
									@endif
								</div>
							</div>

							<hr>
							<div class="mb-2 row">
								<div class="col-sm-3">
									<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
								</div>
							</div>

						</div>
						</div>

						<div class="col-12 col-sm-6 col-md-6">
						<div style="margin-left: 2%; margin-right: 1%;">

							<div class="mb-2 row">
								<label for="fromdate" class="col-sm-2 col-form-label-sm">Jobcards</label>
								<div class="col-sm-10">
									<textarea name="jobcard" class="form-control form-control-sm" id="jobcard" style="resize:none" rows="15">{{$ICR['attributes']['jobcard']}}</textarea>
									@if($ICR['attributes']['validation_messages']->has('jobcard'))
										<script>
												document.getElementById('jobcard').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $ICR['attributes']['validation_messages']->first("jobcard") }}</div>
									@endif
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