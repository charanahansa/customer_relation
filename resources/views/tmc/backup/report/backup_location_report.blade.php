@extends('layouts.tmc')

@section('title')
    Backup Location Report
@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('backup_location_report_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Backup Location Report
			</div>

			<div class="card-body">

                <div class="card-body">

                    <div class="mb-2 row">

                        <label for="tid" class="col-sm-1 col-form-label-sm">Serial No.</label>
                        <div class="col-sm-2">
                            <input type="text" name="serial_number" class="form-control form-control-sm" id="serial_number" value="">
                        </div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Terminal ID</label>
                        <div class="col-sm-2">
                            <input type="text" name="tid" class="form-control form-control-sm" id="tid" value="">
                        </div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
                        <div class="col-sm-2">
                            <select name="bank" id="bank" class="form-select form-select-sm">
                                @foreach($BLR['bank'] as $row)
                                    <option value="{{$row->bank}}"> {{$row->bank}} </option>
                                @endforeach
                                <option value =0 selected>Select the bank</option>
                            </select>
                        </div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
                        <div class="col-sm-2">
                            <select name="model" id="model" class="form-select form-select-sm">
                                @foreach($BLR['model'] as $row)
                                    <option value={{$row->model}}> {{$row->model}} </option>
                                @endforeach
                                <option value =0 selected>Select the Model</option>
                            </select>
                        </div>

                    </div>

                    <div class="mb-2 row">

                        <label for="tid" class="col-sm-1 col-form-label-sm">Location</label>
                        <div class="col-sm-5">
                            <input type="text" name="location" id="location" class="form-select form-select-sm" value="">
                        </div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
                        <div class="col-sm-2">
                            <select name="officer" id="officer" class="form-select form-select-sm">
                                @foreach($BLR['officer'] as $row)
                                    <option value={{$row->officer_id}}> {{$row->name}} </option>
                                @endforeach
                                <option value =0 selected>Select the Officer</option>
                            </select>
                        </div>

                        <div class="col-sm-1">
                            <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Inquire">
                        </div>

                        <div class="col-sm-1">
                            <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-success btn-sm" value="Excell">
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
                                        <th>Serial No. </th>
                                        <th>Model </th>
                                        <th>Active </th>
                                        <th>Bank</th>
                                        <th>TID</th>
                                        <th>Location</th>
                                        <th>Officer</th>
                                        <th>Workflow</th>
                                        <th>Ticket No.</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                @if(count($BLR['report_table']))

                                    <tbody>
                                        @foreach($BLR['report_table'] as $row)
                                            <tr style="font-family: Consolas; font-size: 13px;">
                                                <td>{{$icount}}</td>
                                                <td>{{$row->serialno}}</td>
                                                <td>{{$row->model}}</td>
                                                <td>{{$row->active}}</td>
                                                <td>{{$row->bank}}</td>
                                                <td>{{$row->tid}}</td>
                                                <td>{{$row->location}}</td>
                                                <td>{{$row->officer_id}}</td>
                                                <td>{{$row->workflow_id}}</td>
                                                <td>{{$row->workflow_ref_no}}</td>
                                                <td>{{$row->workflow_ref_date}}</td>
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

	</div>

</form>



@endsection
