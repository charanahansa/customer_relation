@extends('layouts.maintainance')

@section('title')
   Maintainance Add Inquire
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
    <form method="POST" action="{{route('maintainance_add_inquire_process')}}">

        @CSRF

        <div class="col-sm-12">

            <div class="card">

                <div class="card-header">
                   Maintainance Add Inquire
                </div>

                <div class="card-body">

                    <div class="mb-2 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Search</label>
                        <div class="col-sm-2">
                            <input type="text" name="search_value" id="search_value" class="form-control form-control-sm" value="{{$MAI['attributes']['search_value']}}">
                        </div>
                        <label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
                        <div class="col-sm-2">
                            <select name="bank" id="bank" class="form-select form-control-sm">
                                @foreach($MAI['bank'] as $row)
                                    @if($MAI['attributes']['bank'] == $row->bank)
                                        <option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
                                    @else
                                        <option value ="{{$row->bank}}">{{$row->bank}}</option>
                                    @endif
                                @endforeach
                                @if($MAI['attributes']['bank']== 0)
                                    <option value ="0" selected>Select the bank</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
                        <div class="col-sm-2">
                            <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$MAI['attributes']['from_date']}}">
                        </div>
                        <label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
                        <div class="col-sm-2">
                            <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$MAI['attributes']['to_date']}}">
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
                                <tr style="font-family: Consolas; font-size: 13px; background-color: yellowgreen;">
                                    <th>No</th>
                                    <th>MA No.</th>
                                    <th>MA Date</th>
                                    <th>Bank</th>
                                    <th>Category</th>
                                    <th>Ref No.</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>

                            @if(count($MAI['report_table']))

                                <tbody>

                                    @foreach($MAI['report_table'] as $row)

                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td>{{$icount}}</td>
                                            <td>{{$row->ma_id}}</td>
                                            <td>{{$row->ma_date}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->mc_name}}</td>
                                            <td>{{$row->referance_number}}</td>
                                            <td>{{$row->remark}}</td>
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





