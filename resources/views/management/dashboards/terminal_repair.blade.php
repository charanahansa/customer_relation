@extends('layouts.mng')

@section('title')
    Terminal Repair Dashboard
@endsection
<?php

    $from_date = '';
    $to_date = '';

    $from_date = date("Y/m/d");
    $to_date = date("Y/m/d");

    if(isset($terminal_in_out['selected_date'])){

        $from_date = $terminal_in_out['selected_date'][0];
        $to_date = $terminal_in_out['selected_date'][1];
    }
?>

@section('body')

    <h3> Repair Workflow </h3>
    <hr>
    <div style = "display: block; text-align: center;">

        <form method="POST" name="form_name" action="{{route('repair_management_process')}}" style = " margin-left: auto; margin-right: auto; text-align: left;">

            @csrf

            <div class="row">
                <label for="fromdate" class="col-sm-1 col-form-label-sm">From Date</label>
                <div class="col-sm-2">
                    <input type="date" name="from_date" class="form-control form-control-sm" id="from_date" value="<?php echo $from_date; ?>" >
                    <small class="form-text text-muted">DD/MM/YYYY</small>
                </div>
                <label for="todate" class="col-sm-1 col-form-label-sm">To Date</label>
                <div class="col-sm-2">
                    <input type="date" name="to_date" class="form-control form-control-sm" id="to_date" value="<?php echo $to_date; ?>">
                    <small class="form-text text-muted">DD/MM/YYYY</small>
                </div>
                <div class="col-sm-3">
                    <input type="submit" name="subDisplay"  class="btn btn-primary btn-sm" value="Display">
                </div>
            </div>

        </form>

    </div>

    <hr>

    <div id="tbldiv" style="width: 100%;">

        <div class="row">

            <div class="col-sm-4">

            </div>

            <div class="col-sm-4">
                <div class="card bg-light mb-3" style="max-width: 18rem;">
                    <div class="card-header">Total Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            @if( isset($repair['repair_count']) )
                                @foreach($repair['repair_count'] as $row)
                                    {{$row->repair_count}}
                                @endforeach
                            @else
                                0
                            @endif
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">

            </div>

        </div>


        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                <tr class="table-info">
                                    <th>Bank</th>
                                    <th>Count</th>
                                </tr>
                                @if( isset($repair['repair_bank_count']) )
                                    @if(count($repair['repair_status_count']) >= 1)
                                        @foreach($repair['repair_bank_count'] as $row)
                                            <tr onclick=getBank('{{$row->bank}}')>
                                                <td>{{$row->bank}}</td>
                                                <td style="text-align: right;">{{$row->count}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                <tr class="table-info">
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                                @if( isset($repair['repair_status_count']) )
                                    @if(count($repair['repair_status_count']) >= 1)
                                        @foreach($repair['repair_status_count'] as $row)
                                            <tr onclick=getStatus('{{$row->status}}')>
                                                <td>{{ucfirst($row->status)}}</td>
                                                <td style="text-align: right;">{{$row->count}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <hr>

    <div id="div_bank_status" style="width: 100%; table-layout: fixed;">
        <p id="h_bank_status" style="font-weight: bold;"> </p>
        <div class="table-responsive">
            <table id="tbl_bank_status" class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
            </table>
        </div>
    </div>

    <div id="div_status_bank" style="width: 100%; table-layout: fixed;">
        <p id="p_status_bank" style="font-weight: bold;"> </p>
        <div class="table-responsive">
            <table id="tbl_status_bank" class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
            </table>
        </div>
    </div>
    <hr>

    <div id="div_detail" style="width: 100%;">
        <p id="p_detail" style="font-weight: bold;"> </p>
        <div class="table-responsive">
            <table id="tbl_detail" class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
            </table>
        </div>
    </div>

@endsection
