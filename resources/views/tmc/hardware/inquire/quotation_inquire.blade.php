@extends('layouts.tmc')

@section('title')
    Quotation Inquire
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
    <form method="POST" action="{{route('tmc_quotation_inquire_process')}}">

        @CSRF

        <div class="col-sm-12">

            <div class="card">

                <div class="card-header">
                    Quotation Inquire
                </div>

                <div class="card-body">

                    <div class="mb-2 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Search</label>
                        <div class="col-sm-2">
                            <input type="text" name="search_value" id="search_value" class="form-control form-control-sm" value="{{$QI['attributes']['search_value']}}">
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
                                @foreach($QI['bank'] as $row)
                                    @if($QI['attributes']['bank'] == $row->bank)
                                        <option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
                                    @else
                                        <option value ="{{$row->bank}}">{{$row->bank}}</option>
                                    @endif
                                @endforeach
                                @if($QI['attributes']['bank']== 0)
                                    <option value ="0" selected>Select the bank</option>
                                @endif
                            </select>
                        </div>
                        <label for="tid" class="col-sm-1 col-form-label-sm">Model</label>
                        <div class="col-sm-2">
                            <select name="model" id="model" class="form-select form-select-sm">
                                @foreach($QI['model'] as $row)
                                    @if($QI['attributes']['model'] == $row->model)
                                        <option value ="{{$row->model}}" selected>{{$row->model}}</option>
                                    @else
                                        <option value ="{{$row->model}}">{{$row->model}}</option>
                                    @endif
                                @endforeach
                                @if($QI['attributes']['model']== 0)
                                    <option value ="0" selected>Select the Model</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
                        <div class="col-sm-2">
                            <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$QI['attributes']['from_date']}}">
                        </div>
                        <label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
                        <div class="col-sm-2">
                            <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$QI['attributes']['to_date']}}">
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
                                    <th>Qt No.</th>
                                    <th>Qt Date</th>
                                    <th>Jobcard No.</th>
                                    <th>Bank</th>
                                    <th>Model </th>
                                    <th>Serial No. </th>
                                    <th>User Negligant</th>
                                    <th>Qt Approved</th>
                                    <th>Cancel</th>
                                    <th>Price</th>
                                </tr>
                            </thead>

                            @if(count($QI['report_table']))

                                <tbody>
                                    @foreach($QI['report_table'] as $row)

                                        @if($row->cancel == 1)
                                            <tr style="font-family: Consolas; font-size: 13px; background-color: Tomato; font-weight: bold;">
                                        @else
                                            <tr style="font-family: Consolas; font-size: 13px;">
                                        @endif


                                            <td>{{$icount}}</td>
                                            <td><a href={{route('Quotation.quotation_number', $row->qt_no) }} class="text-primary" target="_blank"> {{$row->qt_no}}</a></td>
                                            <td>{{$row->qt_date}}</td>
                                            <td>{{$row->jobcard_no}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->model}}</td>
                                            <td>{{$row->serial_no}}</td>
                                            @if($row->user_neg == 1)
                                                <td>Yes</td>
                                            @else
                                                <td>No</td>
                                            @endif
                                            @if($row->quotation_approved == 1)
                                                <td>Yes</td>
                                            @else
                                                <td>No</td>
                                            @endif
                                            @if($row->cancel == 1)
                                                <td>Yes</td>
                                            @else
                                                <td>No</td>
                                            @endif
                                            <td style = "text-align: right;">@money($row->price)</td>
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





