@extends('layouts.maintainance')

@section('title')
    Maintainance Add Note
@endsection

@section('body')

    <form method="post"  action="{{route('maintainance_add_process')}}">

        @csrf

        <br>
        <div class="col-sm-12">
            
            <div class="card">

                <div class="card-header">
                    Maintainance Add Note
                </div>

                <div class="card-body">

                    <div class="col-sm-12">
                        <?php echo $MAN['attributes']['process_message']; ?>
                    </div>

                    <div class="row no-gutters">

                        <div class="col-12 col-sm-6 col-md-6">
                        <div style="margin-left: 2%; margin-right: 1%;">

                            <div class="mb-1 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
                                <div class="col-sm-4">
									<select name="bank" id="bank" class="form-select form-control-sm">
                                        @foreach($MAN['bank'] as $row)
                                            @if($MAN['attributes']['bank'] == $row->bank)
                                                <option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
                                            @else
                                                <option value ="{{$row->bank}}">{{$row->bank}}</option>
                                            @endif
                                        @endforeach
                                        @if($MAN['attributes']['bank']== 0)
                                            <option value =0 selected>Select the Bank</option>
                                        @else
                                            <option value =0>Select the Bank</option>
                                        @endif
                                    </select>
                                    @if($MAN['attributes']['validation_messages']->has('bank'))
                                        <script>
                                                document.getElementById('bank').className = 'form-select form-control-sm is-invalid';
                                        </script>
                                        <div class="invalid-feedback">{{ $MAN['attributes']['validation_messages']->first("bank") }}</div>
                                    @endif
								</div>
                                <label for="tid" class="col-sm-2 col-form-label-sm">MA Date</label>
                                <div class="col-sm-4">
                                    <input type="date" name="ma_date" id="ma_date" class="form-control form-control-sm" value="{{$MAN['attributes']['ma_date']}}">
                                    @if($MAN['attributes']['validation_messages']->has('ma_date'))
                                        <script>
                                                document.getElementById('ma_date').className = 'form-control form-control-sm is-invalid';
                                        </script>
                                        <div class="invalid-feedback">{{ $MAN['attributes']['validation_messages']->first("ma_date") }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-1 row">
                                <label for="tid" class="col-sm-2 col-form-label-sm">Category</label>
                                <div class="col-sm-4">
									<select name="category" id="category" class="form-select form-control-sm">
                                        @foreach($MAN['maintainance_category'] as $row)
                                            @if($MAN['attributes']['category'] == $row->mc_id)
                                                <option value ="{{$row->mc_id}}" selected>{{$row->mc_name}}</option>
                                            @else
                                                <option value ="{{$row->mc_id}}">{{$row->mc_name}}</option>
                                            @endif
                                        @endforeach
                                        @if($MAN['attributes']['category']== 0)
                                            <option value =0 selected>Select the Category</option>
                                        @else
                                            <option value =0>Select the Category</option>
                                        @endif
                                    </select>
                                    @if($MAN['attributes']['validation_messages']->has('category'))
                                        <script>
                                                document.getElementById('category').className = 'form-select form-control-sm is-invalid';
                                        </script>
                                        <div class="invalid-feedback">{{ $MAN['attributes']['validation_messages']->first("category") }}</div>
                                    @endif
								</div>
                                <label for="tid" class="col-sm-2 col-form-label-sm">Delivary No.</label>
                                <div class="col-sm-4">
                                    <input type="text" name="delivary_no" id="delivary_no" class="form-control form-control-sm" value="{{$MAN['attributes']['delivary_no']}}">
                                </div>
                            </div>
        
                            <div class="mb-1 row">
                                <label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
                                <div class="col-sm-10">
                                    <textarea name="remark" class="form-control form-control-sm" id="remark" style="resize:none" rows="3">{{$MAN['attributes']['remark']}}</textarea>
                                    @if($MAN['attributes']['validation_messages']->has('remark'))
                                        <script>
                                                document.getElementById('remark').className = 'form-control form-control-sm is-invalid';
                                        </script>
                                        <div class="invalid-feedback">{{ $MAN['attributes']['validation_messages']->first("remark") }}</div>
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

                        <div class="col-12 col-sm-6 col-md-6">
                        <div style="margin-left: 2%; margin-right: 1%;">

                            <div class="mb-2 row">
                                <div class="col-sm-12">
                                    <textarea name="terminal_serial" class="form-control form-control-sm" id="terminal_serial" style="resize:none" col="7" rows="20">{{$MAN['attributes']['terminal_serial']}}</textarea>
                                </div>
                            </div>

                        </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>


    </form>

@endsection