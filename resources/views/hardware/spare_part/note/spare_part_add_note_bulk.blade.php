@extends('layouts.hw')

@section('title')
    Spare Part Add Note - Bulk
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_add_process_bulk')}}">
	
		@CSRF
	
		<div class="col-sm-12">
	
			<div class="card">
	
				<div class="card-header">
					Spare Part Add Note - Bulk
				</div>
	
				<div class="card-body">

                    <?php echo $SPA['attributes']['process_message'] ?> 

                    <div class="mb-2 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Remark</label>
                        <div class="col-sm-8">
                            <input type="text" name="remark" id="remark" class="form-control form-control-sm" value="{{$SPA['attributes']['remark']}}">
                            @if($SPA['attributes']['validation_messages']->has('remark'))
                                <script>
                                        document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPA['attributes']['validation_messages']->first("remark") }}</div>
                            @endif
                        </div>
                        <label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
                        <div class="col-sm-2">
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
                    </div>

                    <div class="mb-1 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Spare Part No.</label>
                        <label for="tid" class="col-sm-5 col-form-label-sm">Spare Part Name</label>
                        <label for="tid" class="col-sm-2 col-form-label-sm">Spare Part Category</label>
                        <label for="tid" class="col-sm-2 col-form-label-sm">Quantity</label>
                        <label for="tid" class="col-sm-2 col-form-label-sm">Price</label>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <textarea  name="spare_part_detail" id="spare_part_detail" class="form-control" rows="15" style="resize:none">{{$SPA['attributes']['spare_part_detail']}}</textarea>
                            @if($SPA['attributes']['validation_messages']->has('spare_part_detail'))
                                <script>
                                        document.getElementById('spare_part_detail').className = 'form-control form-control-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPA['attributes']['validation_messages']->first("spare_part_detail") }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <div class="col-sm-1">
                            <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Reset">
                        </div>
                    </div>
	
				</div>
	
			</div>
	
		</div>
	
	</form>
	</div>



@endsection