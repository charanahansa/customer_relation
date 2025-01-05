@extends('layouts.hw')

@section('title')
    Spare Part List
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_list_process')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Spare Part List
				</div>
	
				<div class="card-body">

					<div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
                        <div class="col-sm-5">
                            <select name="spare_part" id="spare_part" class="form-control form-control-sm">
                                @foreach($SPL['spare_part'] as $row)
                                    @if($SPL['attributes']['spare_part'] == $row->part_id)
                                        <option value ={{$row->part_id}} selected>{{$row->part_name}}</option>
                                    @else
                                        <option value ={{$row->part_id}}>{{$row->part_name}}</option>
                                    @endif
                                @endforeach
                                @if($SPL['attributes']['spare_part']== 0)
                                        <option value =0 selected>Select the Spare Part</option>
                                @endif
                            </select>
                            @if($SPL['attributes']['validation_messages']->has('spare_part'))
                                <script>
                                        document.getElementById('spare_part').className = 'form-select form-select-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPL['attributes']['validation_messages']->first("spare_part") }}</div>
                            @endif
                        </div>       
                        <label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
                        <div class="col-sm-3">
                            <select name="model" id="model" class="form-control form-control-sm">
                                @foreach($SPL['model'] as $row)
                                    @if($SPL['attributes']['model'] == $row->model)
                                        <option value ="{{$row->model}}" selected>{{$row->model}}</option>
                                    @else
                                        <option value ="{{$row->model}}">{{$row->model}}</option>
                                    @endif
                                @endforeach
                                @if($SPL['attributes']['model']== 0)
                                    <option value ="0" selected>Select the Model</option>
                                @endif
                            </select>
                        </div>
						<div class="col-sm-2">
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
									<th>SP Id</th>
									<th>SP No</th>
									<th>SP Name</th>
									<th>SP Category</th>
									<th>Model </th>
									<th>Price</th>
									<th>Active</th>
								</tr>
							</thead>
							@if(count($SPL['report_table']) >= 1)

								<tbody>
									@foreach($SPL['report_table'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$icount}}</td>
											<td>{{$row->Part_ID}} <a> </td>
											<td>{{$row->Part_No}}</td>
											<td>{{$row->part_name}}</td>
											<td>{{$row->Part_Catogory}}</td>
											<td>{{$row->Model}}</td>
											<td>{{$row->Price}}</td>
                                            @if($row->Active == 1)
											    <td>Yes</td>
                                            @else
                                                <td>No</td>
                                            @endif
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



@endsection