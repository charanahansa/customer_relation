@extends('layouts.tmc')

@section('title')
    Job Card Inquire
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('tmc_jobcard_inquire_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Job Card Inquire
				</div>

				<div class="card-body">

					<div class="mb-2 row">

                        <label for="tid" class="col-sm-1 col-form-label-sm">Search</label>
                        <div class="col-sm-5">
							<input type="text" name="search_value" id="search_value" class="form-control form-control-sm" value="@">
						</div>

                        <div class="col-sm-3">
                            <select name="search_parameter" id="search_parameter" class="form-select form-select-sm">
                                <option value ="job_card">Job Card No.</option>
                                <option value ="quotation">Quotation No.</option>
                                <option value ="serial_number">Serial No.</option>
                            </select>
                        </div>

                    </div>

                    <div class="mb-2 row">

                        <label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
                        <div class="col-sm-2">
                            <select name="bank" id="bank" class="form-select form-select-sm">
                                @foreach($JI['bank'] as $row)
                                    <option value ="{{$row->bank}}">{{$row->bank}}</option>
                                @endforeach
                                <option value ="0" selected>Select the bank</option>
                            </select>
                        </div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Lot No.</label>
                        <div class="col-sm-2">
							<input type="text" name="lot_number" id="lot_number" class="form-control form-control-sm" value="">
						</div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Box No.</label>
                        <div class="col-sm-2">
							<input type="text" name="box_number" id="box_number" class="form-control form-control-sm" value="">
						</div>

                    </div>

					<div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
						<div class="col-sm-2">
							<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="">
						</div>
						<label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
						<div class="col-sm-2">
							<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width: 100%" value="Search">
						</div>
                        <div class="col-sm-3">
							<input type="submit" name="submit" id="submit" class="btn btn-success btn-sm" style="width: 100%" value="Excel">
						</div>
                    </div>

					<div class="table-responsive">
					<div id="tbldiv" style="width: 100%;">

						<table id="tblgrid1" class="table table-hover table-sm table-bordered">
							<?php $icount = 1; ?>
							<thead>
								<tr style="font-family: Consolas; font-size: 13px;">
									<th>No</th>
									<th>Job Card No.</th>
									<th>JC Date</th>
									<th>Bank</th>
									<th>Serial No. </th>
									<th>Model </th>
                                    <th>Lot No. </th>
                                    <th>Box No. </th>
									<th>Qt No.</th>
                                    <th>Qt Date</th>
                                    <th>Qt Amt</th>
									<th>Status</th>
                                    <th>Hw Released Date</th>
									<th>Tmc Released Date</th>
								</tr>
							</thead>

                            @if(count($JI['report_table']))

								<tbody>
									@foreach($JI['report_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
											<td>{{$row['jobcard_no']}}</td>
											<td>{{$row['jc_date']}}</td>
											<td>{{$row['bank']}}</td>
											<td>{{$row['serialno']}}</td>
											<td>{{$row['model']}}</td>
                                            <td>{{$row['lot_number']}}</td>
                                            <td>{{$row['box_number']}}</td>
											<td>{{$row['qt_no']}}</td>
                                            <td>{{$row['qt_date']}}</td>
                                            <td>{{$row['net_price']}}</td>
											<td>{{$row['status']}}</td>
											<td>{{$row['Released_Date']}}</td>
                                            <td>{{$row['Out_Date']}}</td>
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

@endsection
