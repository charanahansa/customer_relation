@extends('layouts.mng')

@section('title')
    Terminal In/Out Dashboard
@endsection
<?php

    $from_date = '';
    $to_date = '';

    $from_date = date("m/d/Y");
    $to_date = date("m/d/Y");

    if(isset($terminal_in_out['selected_date'])){

        $from_date = $terminal_in_out['selected_date'][0];
        $to_date = $terminal_in_out['selected_date'][1];
    }
?>

@section('body')

            <h3> Terminal In Out Workflow </h3>
            <hr>

            <form method="POST" action="{{route('terminal_in_out_management_process')}}">

                @csrf

                <div class="form-row">
            		<label for="fromdate" class="col-sm-1 col-form-label-sm">From Date</label>
            		<div class="col-sm-2">
            			<input type="text" name="from_date" class="form-control form-control-sm" id="from_date" value="<?php echo $from_date; ?>">
            			<small class="form-text text-muted">YYYY/MM/DD</small>
            		</div>
            		<label for="todate" class="col-sm-1 col-form-label-sm">To Date</label>
            		<div class="col-sm-2">
            			<input type="text" name="to_date" class="form-control form-control-sm" id="to_date" value="<?php echo $to_date; ?>">
            			<small class="form-text text-muted">YYYY/MM/DD</small>
            		</div>
                    <div class="col-sm-3">
            			<input type="submit" name="subDisplay"  class="btn btn-primary btn-sm" value="Display">
            		</div>
            	</div>

            </form>

            <hr>

            <div id="tbldiv" style="width: 100%;">

                <div class="row">

                    <div class="col-sm-4">
                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                            <div class="card-header">Total Terminal In Count</div>
                            <div class="card-body">
                                <h2 class="card-title" style="text-align: center;">
                                    @if( isset($terminal_in_out['terminal_in_count']) )
                                        @foreach($terminal_in_out['terminal_in_count'] as $row)
                                            {{$row->terminal_in_count}}
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

                    <div class="col-sm-4">
                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                            <div class="card-header">Total Terminal Out Count</div>
                            <div class="card-body">
                                <h2 class="card-title" style="text-align: center;">
                                    @if( isset($terminal_in_out['terminal_out_count']) )
                                        @foreach($terminal_in_out['terminal_out_count'] as $row)
                                            {{$row->terminal_out_count}}
                                        @endforeach
                                    @else
                                        0
                                    @endif
                                </h2>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-hover table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                    <tr class="table-info">
                                        <th>Bank</th>
                                        <th>Count</th>
                                    </tr>
                                    @if( isset($terminal_in_out['terminal_in_bank_count']) )
                                        @if(count($terminal_in_out['terminal_in_bank_count']) >= 1)
                                            @foreach($terminal_in_out['terminal_in_bank_count'] as $row)
                                                <tr>
                                                    <td>{{$row->bank}}</td>
                                                    <td>{{$row->count}}</td>
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
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-hover table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                    <tr class="table-info">
                                        <th>Status</th>
                                        <th>Count</th>
                                    </tr>
                                    @if( isset($terminal_in_out['terminal_out_bank_count']) )
                                        @if(count($terminal_in_out['terminal_out_bank_count']) >= 1)
                                            @foreach($terminal_in_out['terminal_out_bank_count'] as $row)
                                                <tr>
                                                    <td>{{ucfirst($row->bank)}}</td>
                                                    <td>{{$row->count}}</td>
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
            <hr>

            <div id="div_bank_status" style="width: 100%; table-layout: fixed;">
                <p id="h_bank_status" style="font-weight: bold;"> </p>
                <table id="tbl_bank_status" class="table table-hover table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                </table>
            </div>

            <div id="div_status_bank" style="width: 100%; table-layout: fixed;">
                <p id="p_status_bank" style="font-weight: bold;"> </p>
                <table id="tbl_status_bank" class="table table-hover table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                </table>
            </div>
            <hr>

            <div id="div_detail" style="width: 100%;">
                <p id="p_detail" style="font-weight: bold;"> </p>
                <table id="tbl_detail" class="table table-hover table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                </table>
            </div>

            <script>

                $("#from_date").datetimepicker(
                    {
                        format:'Y/m/d'
                    }
                );

                $("#to_date").datetimepicker(
                    {
                        format:'Y/m/d'
                    }
                );

            </script>


@endsection
