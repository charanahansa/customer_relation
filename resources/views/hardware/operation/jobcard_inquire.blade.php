@extends('layouts.hw')

@section('title')
    Job Card Inquire
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('jobcard_inquire_process')}}">
	
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
							<input type="text" name="search_value" id="search_value" class="form-control form-control-sm" value="">
						</div>
                        <div class="col-sm-3">
                            <select name="search_parameter" id="search_parameter" class="form-control form-control-sm">
                                <option value ="job_card">Job Card No.</option>
                                <option value ="quotation">Quotation No.</option>
                                <option value ="serial_number">Serial No.</option>
                            </select>
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
                    </div>
	
					<div class="table-responsive">
					<div id="tbldiv" style="width: 100%;">
	
						<table id="tblgrid1" class="table table-hover table-sm table-bordered">
							<?php $icount = 1; ?>
							<thead>
								<tr style="font-family: Consolas; font-size: 13px;">
									<th>No</th>
									<th>Job Card No.</th>
									<th>Job Card Date</th>
									<th>Bank</th>
									<th>Serial No. </th>
									<th>Model </th>
									<th>Quotation No.</th>
									<th>Status</th>
									<th>Released</th>
									<th>Released Date</th>
								</tr>
							</thead>

							@if(count($JI['report_table']))

								<tbody>
									@foreach($JI['report_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
											<td>{{$row->jobcard_no}}</td>
											<td>{{$row->jobcard_date}}</td>
											<td>{{$row->bank}}</td>
											<td>{{$row->serialno}}</td>
											<td>{{$row->model}}</td>
											<td>{{$row->qt_no}}</td>
											<td>{{$row->status}}</td>
											@if($row->Released == 1)
												<td>Yes</td>
											@else
												<td>No</td>
											@endif
											<td>{{$row->Released_Date}}</td>
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