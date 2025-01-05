@extends('layouts.tmc')

@section('title')
    Delivery Order Note
@endsection

@section('body')

<div style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('delivery_order_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Delivery Order Note
			</div>

			<div class="card-body">

				<div class="col-sm-12">
					<?php echo $DON['attributes']['process_message']; ?>
				</div>

				<div class="row no-gutters">

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

						<div class="mb-1 row">
							<label for="tid" class="col-sm-6 col-form-label-sm"></label>
							<label for="tid" class="col-sm-2 col-form-label-sm">Delivery No.</label>
							<div class="col-sm-4">
								<input type="text" name="delivery_id" class="form-control form-control-sm" id="delivery_id" value="{{$DON['attributes']['delivery_id']}}">
								@if($DON['attributes']['validation_messages']->has('delivery_id'))
                                    <script>
                                            document.getElementById('delivery_id').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("delivery_id") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
							<div class="col-sm-4">
								<select name="bank" id="bank" class="form-select form-select-sm">
									@foreach($DON['bank'] as $row)
										@if($DON['attributes']['bank'] == $row->bank)
											<option value={{$row->bank}} selected>{{$row->bank}} </option>
										@else
											<option value={{$row->bank}}> {{$row->bank}} </option>
										@endif
									@endforeach
									@if($DON['attributes']['bank'] == "Not")
                                        <option value ="Not" selected>Select the Bank</option>
                                    @endif
								</select>
								@if($DON['attributes']['validation_messages']->has('bank'))
                                    <script>
                                            document.getElementById('bank').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("bank") }}</div>
                                @endif
							</div>

							<label for="tid" class="col-sm-2 col-form-label-sm">Delivery Date</label>
							<div class="col-sm-4">
								<input type="date" name="delivery_date" class="form-control form-control-sm" id="delivery_date" value="{{$DON['attributes']['delivery_date']}}">
								@if($DON['attributes']['validation_messages']->has('delivery_date'))
                                    <script>
                                            document.getElementById('delivery_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("delivery_date") }}</div>
                                @endif
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Invoice No.</label>
							<div class="col-sm-4">
								<input type="text" name="invoice_number" class="form-control form-control-sm" id="invoice_number" value="{{$DON['attributes']['invoice_number']}}">
								@if($DON['attributes']['validation_messages']->has('invoice_number'))
                                    <script>
                                            document.getElementById('invoice_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("invoice_number") }}</div>
                                @endif
							</div>

                            <label for="tid" class="col-sm-2 col-form-label-sm">Invoice Date</label>
							<div class="col-sm-4">
								<input type="date" name="invoice_date" class="form-control form-control-sm" id="invoice_date" value="{{$DON['attributes']['invoice_date']}}">
								@if($DON['attributes']['validation_messages']->has('invoice_date'))
                                    <script>
                                            document.getElementById('invoice_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("invoice_date") }}</div>
                                @endif
							</div>
						</div>

                        <div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Sales Order No.</label>
							<div class="col-sm-4">
								<input type="text" name="sales_order_number" class="form-control form-control-sm" id="sales_order_number" value="{{$DON['attributes']['sales_order_number']}}">
								@if($DON['attributes']['validation_messages']->has('sales_order_number'))
                                    <script>
                                            document.getElementById('sales_order_number').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("sales_order_number") }}</div>
                                @endif
							</div>

                            <label for="tid" class="col-sm-2 col-form-label-sm">SO Date</label>
							<div class="col-sm-4">
								<input type="date" name="sales_order_date" class="form-control form-control-sm" id="sales_order_date" value="{{$DON['attributes']['sales_order_date']}}">
								@if($DON['attributes']['validation_messages']->has('sales_order_date'))
                                    <script>
                                            document.getElementById('sales_order_date').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("sales_order_date") }}</div>
                                @endif
							</div>
						</div>

                        <div class="mb-1 row">
							<label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
							<div class="col-sm-4">
								<select name="model" id="model" class="form-select form-select-sm">
									@foreach($DON['model'] as $row)
										@if($DON['attributes']['model'] == $row->model)
											<option value={{$row->model}} selected>{{$row->model}} </option>
										@else
											<option value={{$row->model}}> {{$row->model}} </option>
										@endif
									@endforeach
									@if($DON['attributes']['model'] == "Not")
                                        <option value ="Not" selected>Select the Model</option>
                                    @endif
								</select>
								@if($DON['attributes']['validation_messages']->has('model'))
                                    <script>
                                            document.getElementById('model').className = 'form-select form-select-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("model") }}</div>
                                @endif
							</div>
                        </div>


						<div class="mb-1 row">
							<label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
							<div class="col-sm-10">
								<textarea name="remark" class="form-control form-control-sm" id="remark" style="resize:none" rows="3">{{$DON['attributes']['remark']}}</textarea>
								@if($DON['attributes']['validation_messages']->has('remark'))
                                    <script>
                                            document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("remark") }}</div>
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

						</div>

					</div>
					</div>

					<div class="col-12 col-sm-6 col-md-6">
					<div style="margin-left: 2%; margin-right: 1%;">

                        <div class="mb-1 row">
							<label for="fromdate" class="col-sm-3 col-form-label-sm">Serial Numbers</label>
						</div>

						<div class="mb-2 row">
							<div class="col-sm-12">
								<textarea name="terminal_serial" class="form-control form-control-sm" id="terminal_serial" style="resize:none" col="7" rows="20">{{$DON['attributes']['terminal_serial']}}</textarea>
                                @if($DON['attributes']['validation_messages']->has('terminal_serial'))
                                    <script>
                                            document.getElementById('terminal_serial').className = 'form-control form-control-sm is-invalid';
                                    </script>
                                    <div class="invalid-feedback">{{ $DON['attributes']['validation_messages']->first("terminal_serial") }}</div>
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
