@extends('layouts.tmc')

@section('title')
    Job Card - Card Center
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('jobcard_setting_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Job Card - Card Center
				</div>

				<div class="card-body">

					<div class="col-sm-12">
						<?php echo $JS['attributes']['process_message']; ?>
					</div>

					<div class="row no-gutters">

						<div class="col-12 col-sm-6 col-md-6">
						<div style="margin-left: 2%; margin-right: 1%;">

							<div class="mb-1 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Lot No.</label>
								<div class="col-sm-6">
									<input type="text" name="lot_number" class="form-control form-control-sm" id="lot_number" value="{{$JS['attributes']['lot_number']}}">
									@if($JS['attributes']['validation_messages']->has('Lot Number'))
										<script>
												document.getElementById('lot_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JS['attributes']['validation_messages']->first("Lot Number") }}</div>
									@endif
								</div>

							</div>

                            <div class="mb-1 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Box No.</label>
								<div class="col-sm-6">
									<input type="text" name="box_number" class="form-control form-control-sm" id="box_number" value="{{$JS['attributes']['box_number']}}">
									@if($JS['attributes']['validation_messages']->has('Box Number'))
										<script>
												document.getElementById('box_number').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JS['attributes']['validation_messages']->first("Box Number") }}</div>
									@endif
								</div>

							</div>

                            <div class="mb-1 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
								<div class="col-sm-6">
									<input type="date" name="jc_date" class="form-control form-control-sm" id="jc_date" value="{{$JS['attributes']['jc_date']}}">
									@if($JS['attributes']['validation_messages']->has('Date'))
										<script>
												document.getElementById('jc_date').className = 'form-control form-control-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JS['attributes']['validation_messages']->first("Date") }}</div>
									@endif
								</div>

							</div>


							<div class="mb-1 row">

								<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
		                        <div class="col-sm-6">
		                            <select name="bank" id="bank" class="form-select form-select-sm">
		                                @foreach($JS['bank'] as $row)
		                                    @if($JS['attributes']['bank'] == $row->bank)
		                                        <option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
		                                    @else
		                                        <option value ="{{$row->bank}}">{{$row->bank}}</option>
		                                    @endif
		                                @endforeach
		                                @if($JS['attributes']['bank']== "0")
		                                    <option value ="0" selected>Select the Bank</option>
		                                @endif
		                            </select>
									@if($JS['attributes']['validation_messages']->has('Bank'))
										<script>
												document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JS['attributes']['validation_messages']->first("Bank") }}</div>
									@endif
		                        </div>

							</div>

							<div class="mb-2 row">

		                        <label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
		                        <div class="col-sm-6">
		                            <select name="model" id="model" class="form-select form-select-sm">
		                                @foreach($JS['model'] as $row)
		                                    @if($JS['attributes']['model'] == $row->model)
		                                        <option value ="{{$row->model}}" selected>{{$row->model}}</option>
		                                    @else
		                                        <option value ="{{$row->model}}">{{$row->model}}</option>
		                                    @endif
		                                @endforeach
		                                @if($JS['attributes']['model'] == "0")
		                                    <option value ="0" selected>Select the Model</option>
		                                @endif
		                            </select>
									@if($JS['attributes']['validation_messages']->has('Model'))
										<script>
												document.getElementById('model').className = 'form-select form-select-sm is-invalid';
										</script>
										<div class="invalid-feedback">{{ $JS['attributes']['validation_messages']->first("Model") }}</div>
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
