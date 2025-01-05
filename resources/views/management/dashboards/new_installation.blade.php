@extends('layouts.mng')

@section('title')
    New Installation Dashboard
@endsection
<?php

    $from_date = '';
    $to_date = '';

    $from_date = date("Y/m/d");
    $to_date = date("Y/m/d");

    if(isset($new_installation['selected_date'])){

        $from_date = $new_installation['selected_date'][0];
        $to_date = $new_installation['selected_date'][1];
    }

?>

@section('body')

            <h3> New Installation Workflow </h3>
            <hr>

            <form method="POST" action="{{route('new_installation_managment_process')}}">

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

                    </div>

                    <div class="col-sm-4">
                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                            <div class="card-header">Total New Installation Count</div>
                            <div class="card-body">
                                <h2 class="card-title" style="text-align: center;">
                                    @if( isset($new_installation['new_installation_count']) )
                                        @foreach($new_installation['new_installation_count'] as $row)
                                            {{$row->new_installation_count}}
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
                                <table class="table table-hover table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                    <tr class="table-info">
                                        <th>Bank</th>
                                        <th>Count</th>
                                    </tr>
                                    @if( isset($new_installation['new_installation_bank_count']) )
                                        @if(count($new_installation['new_installation_status_count']) >= 1)
                                            @foreach($new_installation['new_installation_bank_count'] as $row)
                                                <tr onclick=getBank('{{$row->bank}}')>
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
                                    @if( isset($new_installation['new_installation_status_count']) )
                                        @if(count($new_installation['new_installation_status_count']) >= 1)
                                            @foreach($new_installation['new_installation_status_count'] as $row)
                                                <tr onclick=getStatus('{{$row->status}}')>
                                                    <td>{{ucfirst($row->status)}}</td>
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
<script>

    function getBank(bank){

        var from_date =  document.getElementById("from_date").value;
		var to_date = document.getElementById("to_date").value;

        $.ajax({

            url:  "{{ route('get_bank_wise_status_wise_new_installation_managment_count') }}",
            type: 'GET',
            data: {bank:bank, from_date:from_date, to_date:to_date },
            error: function(xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
				alert('Error - ' + errorMessage);
			},
            success: function(data) {

                //alert(data);

                var div_bank_sttaus = document.getElementById("div_bank_status");
                var div_status_bank = document.getElementById("div_status_bank");
                var div_detail = document.getElementById("div_detail");
                div_bank_sttaus.style.display = "block";
                div_status_bank.style.display = "none";
                div_detail.style.display = "none";

                document.getElementById("h_bank_status").innerHTML  = "Bank : " + bank;

                $("#tbl_bank_status tr").remove();

                myObj = JSON.parse(data);
                document_table = myObj['bank_status_count'];

                if((Object.keys(document_table).length) >= 1){

                    // Header
                    var row_header = "<tr class='table-info'>";
					for (var i = 0; i < (Object.keys(document_table).length); i++) {

                        var bnk = document_table[i]['status'];
                        row_header += "<td>" + (bnk.charAt(0).toUpperCase() + bnk.slice(1)) + "</td>";
                    }
                    row_header +=  "</tr>";
                    $("#tbl_bank_status").append(row_header);

                    // Detail
                    var row_detail = "<tr>";
                    for (var i = 0; i < (Object.keys(document_table).length); i++) {

                        var para = bank + "_" + document_table[i]['status'];
                        row_detail += "<td onclick=detail('"+para+"')>" + document_table[i]['count'] + "</td>";
                    }
                    row_detail +=  "</tr>";
                    $("#tbl_bank_status").append(row_detail);

                }
            }
        });
    }


    function getStatus(status){

        var from_date =  document.getElementById("from_date").value;
		var to_date = document.getElementById("to_date").value;

        $.ajax({

            url:  "{{ route('get_status_wise_bank_wise_new_installation_managment_count') }}",
            type: 'GET',
            data: {status:status, from_date:from_date, to_date:to_date },
            error: function(xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
				alert('Error - ' + errorMessage);
			},
            success: function(data) {

                //alert(data);

                var div_bank_sttaus = document.getElementById("div_bank_status");
                var div_status_bank = document.getElementById("div_status_bank");
                var div_detail = document.getElementById("div_detail");
                div_bank_sttaus.style.display = "none";
                div_status_bank.style.display = "block";
                div_detail.style.display = "none";

                document.getElementById("p_status_bank").innerHTML  = "Status : " + (status.charAt(0).toUpperCase() + status.slice(1));

                $("#tbl_status_bank tr").remove();

                myObj = JSON.parse(data);
                document_table = myObj['status_bank_count'];

                if((Object.keys(document_table).length) >= 1){

                    // Header
                    var row_header = "<tr class='table-info'>";
					for (var i = 0; i < (Object.keys(document_table).length); i++) {

                        var sts = document_table[i]['bank'];
                        row_header += "<td>" + (sts.charAt(0).toUpperCase() + sts.slice(1)) + "</td>";
                    }
                    row_header +=  "</tr>";
                    $("#tbl_status_bank").append(row_header);

                    // Detail
                    var row_detail = "<tr>";
                    for (var i = 0; i < (Object.keys(document_table).length); i++) {

                        var para = document_table[i]['bank'] + "_" + status;
                        row_detail += "<td onclick=detail('"+para+"')>" + document_table[i]['count'] + "</td>";
                    }
                    row_detail +=  "</tr>";
                    $("#tbl_status_bank").append(row_detail);

                }
            }
        });
    }

    function detail(text){

        var arr = text.split("_");
        var bank = arr[0];
        var status = arr[1];
        var from_date =  document.getElementById("from_date").value;
        var to_date = document.getElementById("to_date").value;

        var div_detail = document.getElementById("div_detail");
        div_detail.style.display = "block";

        $.ajax({

            url:  "{{route('get_new_installation_managment_detail')}}",
            type: 'GET',
            data: {bank:bank, status:status, from_date:from_date, to_date:to_date },
            error: function(xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
				alert('Error - ' + errorMessage);
			},
            success: function(data) {

                //alert(data);

                document.getElementById("p_detail").innerHTML  = "Bank : " + bank + "<br> Status : " + (status.charAt(0).toUpperCase() + status.slice(1));

                $("#tbl_detail tr").remove();

                myObj = JSON.parse(data);
                document_table = myObj['new_installation_detail'];

                // Header
                var row_header = "<tr class='table-info'>";
                row_header += "<td style='width: 3%;'>No.</td>";
                row_header += "<td style='width: 4%;'>Ticket No.</td>";
                row_header += "<td style='width: 7%;'>Date & Time</td>";
                row_header += "<td style='width: 4%;'>Bank</td>";
                row_header += "<td style='width: 6%;'>Tid</td>";
                row_header += "<td style='width: 10%;'>Model</td>";
                row_header += "<td style='width: 35%;'>Merchant</td>";
                row_header += "<td style='width: 15%;'>Field Officer</td>";
                row_header += "<td style='width: 10%;'>status</td>";
                row_header += "<td style='width: 12%;'>Done Date Time</td>";
                row_header +=  "</tr>";

                $("#tbl_detail").append(row_header);

                if((Object.keys(document_table).length) >= 1){

                    // Detail
                    for (var i = 0; i < (Object.keys(document_table).length); i++) {

                        new_installation_officer = document_table[i]['officer_name'];
                        if(new_installation_officer == null){
                            new_installation_officer = '-';
                        }

                        done_date_time = document_table[i]['done_date_time'];
                        if(done_date_time == null){
                            done_date_time = '-';
                        }

                        new_installation_status = document_table[i]['status'];
                        new_installation_status = (new_installation_status.charAt(0).toUpperCase() + new_installation_status.slice(1));

                        var row_detail = "<tr>";
                        row_detail += "<td >" + (i+1) + "</td>";
                        row_detail += "<td >" + document_table[i]['ticketno'] + "</td>";
                        row_detail += "<td >" + document_table[i]['tdate'] + "</td>";
                        row_detail += "<td >" + document_table[i]['bank'] + "</td>";
                        row_detail += "<td >" + document_table[i]['tid'] + "</td>";
                        row_detail += "<td >" + document_table[i]['model'] + "</td>";
                        row_detail += "<td >" + document_table[i]['merchant'] + "</td>";
                        row_detail += "<td >" + new_installation_officer + "</td>";
                        row_detail += "<td >" + new_installation_status  + "</td>";
                        row_detail += "<td >" + done_date_time  + "</td>";
                        row_detail +=  "</tr>";
                        $("#tbl_detail").append(row_detail);
                    }

                }
            }
        });



    }



</script>
