@extends('layouts.hw')

@section('title')
    Operation Dashboard # 2
@endsection

@section('body')

    <div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">

        <h3> Repair Workflow </h3>
        <hr>

        <div class="row">

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(1)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Jobcard Not Accepted Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['jobcard_not_accepted_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(2)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">To Be Investigated Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['to_be_investigated_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(3)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Today Accepted Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['today_accepted_terminal_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(4)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Today Released Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['today_released_terminal_count']}}
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(5)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Quoted Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['quoted_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(6)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Spare Part Pending Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['spare_part_pending_terminal_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(7)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Testing Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['testing_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(8)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Done Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['done_count']}}
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;" onclick="getInformation(9)" data-toggle="modal" data-target="#exampleModal">
                    <div class="card-header">Dismantal Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            {{$repair['dismantal_terminal_count']}}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card bg-light mb-3" style="max-width: 18rem;">
                    <div class="card-header">Disposed Terminal Count</div>
                    <div class="card-body">
                        <h2 class="card-title" style="text-align: center;">
                            0
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="headTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tblBank" class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                                <tr class="table-info">
                                                    <th>Bank</th>
                                                    <th>Count</th>
                                                </tr>
                                                <tbody>
                                                    <tr>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tblModel" class="table table-striped  table-sm table-bordered" style='font-family: Consolas; font-size: 13px;'>
                                                <tr class="table-info">
                                                    <th>Model</th>
                                                    <th>Count</th>
                                                </tr>
                                                <tr>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <hr>

    <script>

        function getInformation(paraMeter){

            //alert(paraMeter);

            $.ajax({

                url:  "{{ route('repair_operation_dashboard_two_get_infor') }}",
                type: 'GET',
                data: {
                    paraMeter:paraMeter
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
                    alert('Error - ' + errorMessage);
                },

                success: function(data) {

                    //alert(data);

                    myObj = JSON.parse(data);

                    document.getElementById("headTitle").innerHTML  =  myObj['headTitle'];

                    bankTable = myObj['bankWise'];
                    if((Object.keys(bankTable).length) >= 1){

                        var rowCount = tblBank.rows.length;
                        for (var i = rowCount - 1; i > 0; i--) {

                            tblBank.deleteRow(i);
                        }

                        for (var i = 0; i < (Object.keys(bankTable).length); i++) {

                            var row_detail = "";
                            row_detail += '<tr>'
                            row_detail += '<td>' + bankTable[i]['bank'] + '</td>';
                            row_detail += '<td>' + bankTable[i]['jcount'] + '</td>';
                            row_detail +=  '</tr>';

                            $("#tblBank").append(row_detail);

                            // var para = bank + "_" + bankTable[i]['status'];
                            // row_detail += "<td onclick=detail('"+para+"')>" + bankTable[i]['count'] + "</td>";
                        }
                    }

                    modelTable = myObj['modelWise'];
                    if((Object.keys(modelTable).length) >= 1){

                        var rowCount = tblModel.rows.length;
                        for (var i = rowCount - 1; i > 0; i--) {

                            tblModel.deleteRow(i);
                        }

                        for (var i = 0; i < (Object.keys(modelTable).length); i++) {

                            var row_detail = "";
                            row_detail += '<tr>'
                            row_detail += '<td>' + modelTable[i]['model'] + '</td>';
                            row_detail += '<td>' + modelTable[i]['jcount'] + '</td>';
                            row_detail +=  '</tr>';

                            $("#tblModel").append(row_detail);

                            // var para = model + "_" + modelTable[i]['status'];
                            // row_detail += "<td onclick=detail('"+para+"')>" + modelTable[i]['count'] + "</td>";
                        }
                    }
                }

            });
        }

    </script>

@endsection
