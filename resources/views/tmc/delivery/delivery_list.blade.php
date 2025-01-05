@extends('layouts.tmc')

@section('title')
    Delivery List
@endsection

@section('body')

<div style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('delivery_list_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Delivery List
			</div>

			<div class="card-body">

				<div class="mb-2 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
					<div class="col-sm-2">
						<select name="bank" id="bank" class="form-select form-select-sm">
							@foreach($DI['bank'] as $row)
								<option value="{{$row->BankCode}}"> {{$row->BankCode}} </option>
							@endforeach
							<option value ="0" selected>Select the bank</option>
						</select>
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
					<div class="col-sm-2">
						<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$DI['attributes']['from_date']}}">
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
					<div class="col-sm-2">
						<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$DI['attributes']['to_date']}}">
					</div>

				</div>

                <div class="mb-2 row">

                    <label for="tid" class="col-sm-1 col-form-label-sm">Invoice No.</label>
					<div class="col-sm-2">
						<input type="text" name="invoice_number" id="invoice_number" class="form-control form-control-sm" value="{{$DI['attributes']['invoice_number']}}">
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Delivery No. </label>
					<div class="col-sm-2">
						<input type="text" name="delivery_number" id="delivery_number" class="form-control form-control-sm" value="{{$DI['attributes']['delivery_number']}}">
					</div>

					<label for="tid" class="col-sm-1 col-form-label-sm">Serial No.</label>
					<div class="col-sm-2">
						<input type="text" name="serial_number" id="serial_number" class="form-control form-control-sm" value="{{$DI['attributes']['serial_number']}}">
					</div>

					<div class="col-sm-1">
						<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Inquire">
					</div>

				</div>

				<hr>

				<div class="table-responsive">
				<div id="tbldiv" style="width: 100%;">

					<table id="tblgrid1" class="table table-hover table-sm table-bordered">
						<?php $icount = 1; ?>
						<thead>
							<tr style="font-family: Consolas; font-size: 13px;">
								<th>No</th>
								<th>Delivery No.</th>
								<th>Date</th>
								<th>Bank</th>
								<th>Invoice No.</th>
								<th>Sales Order No.</th>
								<th>Cancel</th>
                                <th>Model</th>
								<th>Terminal Count </th>
							</tr>
						</thead>

						@if(count($DI['report_table']))

							<tbody>
								@foreach($DI['report_table'] as $row)
									<tr style="font-family: Consolas; font-size: 13px;">
										<td>{{$icount}}</td>
										<td>{{$row->DocNo}}</td>
										<td>{{$row->Deliver_Date}}</td>
										<td>{{$row->BankCode}}</td>
										<td>{{$row->invoiceNo}}</td>
										<td>{{$row->salesorderNo}}</td>
										<td>{{$row->Cancel}}</td>
                                        <td>{{$row->ItemCode}}</td>
										<td>{{$row->Terminal_Count}}</td>
										<td> <input type="button" name="open" id="open" style="width: 100%;" class="btn btn-success btn-sm" value="Excel" onclick='openTicket("{{$row->DocNo}}", "{{$row->ItemCode}}" )'></td>
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

<div id="hide_div">

    <form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('delivery_report')}}">
        @csrf
        <input type="text" name="delivery_no" id="delivery_no" values="">
        <input type="text" name="model" id="model" values="">
    </form>

</div>

<script>

    function openTicket(delivery_no, model){

        document.getElementById("delivery_no").value = delivery_no;
        document.getElementById("model").value = model;
        document.getElementById("myForm").submit();
    }

</script>



@endsection
