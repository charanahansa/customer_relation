@extends('layouts.hw')

@section('title')
    Spare Part Add Note
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_request_list_process')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Spare Part Add Note
				</div>
	
				<div class="card-body">

                    <div class="row no-gutters">

                        <div class="col-12 col-sm-7 col-md-7" >
                        <div style="margin-left: 1%; margin-right: 2%;">

							<?php echo $SPA['attributes']['process_message'] ?> 

                            <div class="mb-2 row">
                                <label for="tid" class="col-sm-6 col-form-label-sm"></label>
                                <label for="tid" class="col-sm-2 col-form-label-sm">Spare Part ID</label>   
                                <div class="col-sm-4">
                                    <input type="text" name="spare_part_id" id="spare_part_id" class="form-control form-control-sm" value="{{$SPA['attributes']['spare_part_id']}}">
                                </div>              
                            </div>

                            <div class="mb-2 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Spare Part No.</label>
                                <div class="col-sm-4">
                                    <input type="text" name="spare_part_no" id="spare_part_no" class="form-control form-control-sm" value="{{$SPA['attributes']['spare_part_no']}}">
                                </div>
                                <label for="tid" class="col-sm-2 col-form-label-sm">Date</label>
                                <div class="col-sm-4">
                                    <input type="date" name="add_date" id="add_date" class="form-control form-control-sm" value="{{$SPA['attributes']['add_date']}}">
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Spare Part Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="spare_part_name" id="spare_part_name" class="form-control form-control-sm" value="{{$SPA['attributes']['spare_part_name']}}">
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Model</label>
                                <div class="col-sm-4">
                                    <select name="model" id="model" class="form-control form-control-sm">
                                        @foreach($SPA['model'] as $row)
                                            @if($SPA['attributes']['model'] == $row->model)
                                                <option value ="{{$row->model}}" selected>{{$row->model}}</option>
                                            @else
                                                <option value ="{{$row->model}}">{{$row->model}}</option>
                                            @endif
                                        @endforeach
										@if($SPA['attributes']['model']== 0)
											<option value ="0" selected>Select the Model</option>
		                                @endif
		                            </select>
                                </div>
                                <label for="tid" class="col-sm-2 col-form-label-sm">Part Category</label>
                                <div class="col-sm-4">
                                    <input type="text" name="spare_part_category" id="spare_part_category" class="form-control form-control-sm" value="{{$SPA['attributes']['spare_part_category']}}">
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Active</label>
                                <div class="col-sm-4">
                                    <select name="active" id="active" class="form-control form-control-sm">
										@if($SPA['attributes']['active'] == 1)
											<option value ="1" selected>Yes</option>
											<option value ="0">No</option>
										@else
											<option value ="1">Yes</option>
											<option value ="0" selected>No</option>
										@endif
		                            </select>
                                </div>
                                <label for="tid" class="col-sm-2 col-form-label-sm">Price</label>
                                <div class="col-sm-4">
                                    <input type="text" name="price" id="price" class="form-control form-control-sm" value="{{$SPA['attributes']['price']}}">
                                </div>
                            </div>

                            <div class="mb-5 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Remark</label>
                                <div class="col-sm-10">
                                    <textarea  name="remark" id="remark" class="form-control" rows="2" style="resize:none">{{$SPA['attributes']['remark']}}</textarea>
									@if($SPA['attributes']['validation_messages']->has('remark'))
		                                <script>
		                                        document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
		                                </script>
		                                <div class="invalid-feedback">{{ $SPA['attributes']['validation_messages']->first("remark") }}</div>
		                            @endif
                                </div>
                            </div>

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