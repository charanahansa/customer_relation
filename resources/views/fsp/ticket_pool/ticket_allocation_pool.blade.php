@extends('layouts.fsp')

@section('title')
    Ticket Allocation Pool
@endsection

@section('refresh_second')120 @endsection
@section('self_route')route('ticket_allocation_pool')@endsection

@section('body')

<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<div class="col-sm-12">

    <div class="card">

        <div class="card-header">
           Ticket Allocation Pool
        </div>

        <div class="card-body">

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="breakdown_tab" data-bs-toggle="pill" data-bs-target="#pills_breakdown" type="button" role="tab" aria-controls="pills_breakdown" aria-selected="true">Breakdown</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="new_installation_tab" data-bs-toggle="pill" data-bs-target="#pills_new_installation" type="button" role="tab" aria-controls="pills_new_installation" aria-selected="false">New Installation </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="re_initialization" data-bs-toggle="pill" data-bs-target="#pills_re_initialization" type="button" role="tab" aria-controls="pills_re_initialization" aria-selected="false">Re Initialization</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="software_updating" data-bs-toggle="pill" data-bs-target="#pills_software_updating" type="button" role="tab" aria-controls="pills_software_updating" aria-selected="false">Software Updating</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="terminal_replacement" data-bs-toggle="pill" data-bs-target="#pills_terminal_replacement" type="button" role="tab" aria-controls="pills_terminal_replacement" aria-selected="false">Terminal Replacement</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="backup_remove" data-bs-toggle="pill" data-bs-target="#pills_backup_remove" type="button" role="tab" aria-controls="pills_backup_remove" aria-selected="false">Backup Remove Note</button>
                </li>

            </ul>

            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade show active" id="pills_breakdown" role="tabpanel" aria-labelledby="breakdown_tab">

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tbl_breakdown" class="table table-hover table-sm table-bordered">

                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort_b" id="ticketno" data-order="desc">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_b" id="tdate" data-order="desc">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_b" id="bank" data-order="desc">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_b" id="tid" data-order="desc">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_b" id="merchant" data-order="desc">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_b" id="status" data-order="desc">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>

                            @if(count($TAP['breakdown']))

								<tbody>

									@foreach($TAP['breakdown'] as $row)
										<tr style="font-family: Consolas; font-size: 13px;">
											<td>{{$row->ticketno}}</td>
											<td>{{$row->tdate}}</td>
											<td>{{$row->bank}}</td>
											<td>{{$row->tid}}</td>
											<td>{{$row->merchant}}</td>
											<td>{{ucfirst($row->status)}}</td>
                                            <td><input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick='openTicket(this.parentNode.parentNode.rowIndex, 1)'></td>
										</tr>
									@endforeach

								</tbody>

							@else

								<tbody>

									<tr style="font-family: Consolas; font-size: 13px;">
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

                <div class="tab-pane fade" id="pills_new_installation" role="tabpanel" aria-labelledby="new_installation_tab">

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tbl_new" class="table table-hover table-sm table-bordered">

                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort_n" id="ticketno" data-order="desc">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="tdate" data-order="desc">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="bank" data-order="desc">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="tid" data-order="desc">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_n" id="merchant" data-order="desc">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="status" data-order="desc">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>

                            @if(count($TAP['new']))

                                <tbody>

                                    @foreach($TAP['new'] as $row)
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td>{{$row->ticketno}}</td>
                                            <td>{{$row->tdate}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->tid}}</td>
                                            <td>{{$row->merchant}}</td>
                                            <td>{{ucfirst($row->status)}}</td>
                                            <td><input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick='openTicket(this.parentNode.parentNode.rowIndex, 2)'></td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            @else

                                <tbody>

                                    <tr style="font-family: Consolas; font-size: 13px;">
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

                <div class="tab-pane fade" id="pills_re_initialization" role="tabpanel" aria-labelledby="re_initialization">

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tbl_reinitialization" class="table table-hover table-sm table-bordered">

                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort_r" id="ticketno" data-order="desc">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="tdate" data-order="desc">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="bank" data-order="desc">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="tid" data-order="desc">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_r" id="merchant" data-order="desc">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="status" data-order="desc">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>

                            @if(count($TAP['re_initialization']))

                                <tbody>

                                    @foreach($TAP['re_initialization'] as $row)
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td>{{$row->ticketno}}</td>
                                            <td>{{$row->tdate}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->tid}}</td>
                                            <td>{{$row->merchant}}</td>
                                            <td>{{ucfirst($row->status)}}</td>
                                            <td><input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick='openTicket(this.parentNode.parentNode.rowIndex, 3)'></td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            @else

                                <tbody>

                                    <tr style="font-family: Consolas; font-size: 13px;">
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

                <div class="tab-pane fade" id="pills_software_updating" role="tabpanel" aria-labelledby="software_updating">

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tbl_software_updating" class="table table-hover table-sm table-bordered">

                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort" id="a.ticketno" data-order="desc"> Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 6%;" class="column_sort" id="s.tdate" data-order="desc"> Date <span>&#8597;</span> </th>
                                    <th style="width: 6%;" class="column_sort" id="s.bank" data-order="desc"> Bank <span>&#8597;</span> </th>
                                    <th style="width: 6%;" class="column_sort" id="sd.tid" data-order="desc"> Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort" id="sd.merchant" data-order="desc"> Merchant <span>&#8597;</span> </th>
                                    <th style="width: 14%;" class="column_sort" id="ss.status" data-order="desc"> Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>

                            @if(count($TAP['software_update']))

                                <tbody>

                                    @foreach($TAP['software_update'] as $row)
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td>{{$row->ticketno}}</td>
                                            <td>{{$row->tdate}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->tid}}</td>
                                            <td>{{$row->merchant}}</td>
                                            <td>{{ucfirst($row->status)}}</td>
                                            <td><input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick='openTicket(this.parentNode.parentNode.rowIndex, 4)'></td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            @else

                                <tbody>

                                    <tr style="font-family: Consolas; font-size: 13px;">
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

                <div class="tab-pane fade" id="pills_terminal_replacement" role="tabpanel" aria-labelledby="terminal_replacement">

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tbl_terminal_replacement" class="table table-hover table-sm table-bordered">

                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort_tr" id="ticketno" data-order="desc">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="tdate" data-order="desc">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="bank" data-order="desc">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="based_tid" data-order="desc">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_tr" id="merchant" data-order="desc">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="status" data-order="desc">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>

                            @if(count($TAP['terminal_replacemnt']))

                                <tbody>

                                    @foreach($TAP['terminal_replacemnt'] as $row)
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td>{{$row->ticketno}}</td>
                                            <td>{{$row->tdate}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->based_tid}}</td>
                                            <td>{{$row->merchant}}</td>
                                            <td>{{ucfirst($row->status)}}</td>
                                            <td><input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick='openTicket(this.parentNode.parentNode.rowIndex, 5)'></td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            @else

                                <tbody>

                                    <tr style="font-family: Consolas; font-size: 13px;">
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

                <div class="tab-pane fade" id="pills_backup_remove" role="tabpanel" aria-labelledby="backup_remove">

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tbl_backup_remove" class="table table-hover table-sm table-bordered">

                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort_br" id="b.brn_no" data-order="desc">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.brn_date" data-order="desc">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.bank" data-order="desc">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.tid" data-order="desc">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_br" id="b.merchant" data-order="desc">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.status" data-order="desc">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>

                            @if(count($TAP['backup_remove']))

                                <tbody>

                                    @foreach($TAP['backup_remove'] as $row)
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td>{{$row->brn_no}}</td>
                                            <td>{{$row->brn_date}}</td>
                                            <td>{{$row->bank}}</td>
                                            <td>{{$row->tid}}</td>
                                            <td>{{$row->merchant}}</td>
                                            <td>{{ucfirst($row->status)}}</td>
                                            <td><input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick='openTicket(this.parentNode.parentNode.rowIndex, 6)'></td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            @else

                                <tbody>

                                    <tr style="font-family: Consolas; font-size: 13px;">
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


    </div>

</div>
</div>
<div id="hide_div">

    <form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('field_service_allocation')}}">
        @csrf
        <input type="text" name="ticket_number" id="ticket_number" values="">
		<input type="text" name="relevant_bank" id="relevant_bank" values="">
        <input type="text" name="workflow_id" id="workflow_id" values="">
    </form>

</div>
<script>

    function openTicket(rowIndex, workflowId){

        if(workflowId == 1){

            var ticket_number = tbl_breakdown.rows.item(rowIndex).cells.item(0).innerHTML;
			var bank = tbl_breakdown.rows.item(rowIndex).cells.item(2).innerHTML; 
        }

        if(workflowId == 2){

            var ticket_number = tbl_new.rows.item(rowIndex).cells.item(0).innerHTML;
			var bank = tbl_new.rows.item(rowIndex).cells.item(2).innerHTML; 
        }

        if(workflowId == 3){

            var ticket_number = tbl_reinitialization.rows.item(rowIndex).cells.item(0).innerHTML;
			var bank = tbl_reinitialization.rows.item(rowIndex).cells.item(2).innerHTML; 
        }

        if(workflowId == 4){

            var ticket_number = tbl_software_updating.rows.item(rowIndex).cells.item(0).innerHTML;
			var bank = tbl_software_updating.rows.item(rowIndex).cells.item(2).innerHTML; 
        }

        if(workflowId == 5){

            var ticket_number = tbl_terminal_replacement.rows.item(rowIndex).cells.item(0).innerHTML;
			var bank = tbl_terminal_replacement.rows.item(rowIndex).cells.item(2).innerHTML; 
        }

        if(workflowId == 6){

            var ticket_number = tbl_backup_remove.rows.item(rowIndex).cells.item(0).innerHTML;
			var bank = tbl_backup_remove.rows.item(rowIndex).cells.item(2).innerHTML; 
        }

        document.getElementById("ticket_number").value = ticket_number;
		document.getElementById("relevant_bank").value = bank;
        document.getElementById("workflow_id").value = workflowId;

        document.getElementById("myForm").submit();
    }


    $(document).ready(

        function(){

			// Breakdown
            $(document).on('click', '.column_sort_b', function(){

				var column_name = $(this).attr("id");
				var order = $(this).data("order");
				var arrow = '';

				if(order == 'desc'){

					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';

				}else{
					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';
				}

				$.ajax({
					url:"{{ route('get_ticket_allocation_data') }}",
					method:"GET",
					data:{
						column_name:column_name,
						order:order,
						workflow: 'breakdown'
					},
					error: function(xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
						alert('Error - ' + errorMessage);
					},
					success:function(data){

						$('#tbl_breakdown').html(data);
						$('#'+column_name+'').append(arrow);
					}
				})
			});

			// New Installation
            $(document).on('click', '.column_sort_n', function(){

				var column_name = $(this).attr("id");
				var order = $(this).data("order");
				var arrow = '';

				if(order == 'desc'){

					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';

				}else{
					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';
				}

				$.ajax({
					url:"{{ route('get_ticket_allocation_data') }}",
					method:"GET",
					data:{
						column_name:column_name,
						order:order,
						workflow: 'new'
					},
					error: function(xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
						alert('Error - ' + errorMessage);
					},
					success:function(data){

						$('#tbl_new').html(data);
						$('#'+column_name+'').append(arrow);
					}
				})

			});

			// Re Initialization
            $(document).on('click', '.column_sort_r', function(){

				var column_name = $(this).attr("id");
				var order = $(this).data("order");
				var arrow = '';

				if(order == 'desc'){

					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';

				}else{
					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';
				}

				$.ajax({
					url:"{{ route('get_ticket_allocation_data') }}",
					method:"GET",
					data:{
						column_name:column_name,
						order:order,
						workflow: 're_initialization'
					},
					error: function(xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
						alert('Error - ' + errorMessage);
					},
					success:function(data){

						$('#tbl_reinitialization').html(data);
						$('#'+column_name+'').append(arrow);
					}
				})

			});

            //Software Update
            $(document).on('click', '.column_sort', function(){

                var column_name = $(this).attr("id");
                var order = $(this).data("order");
                var arrow = '';

                if(order == 'desc'){

                    arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';

                }else{
                    arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';
                }

                $.ajax({
                    url:"{{ route('get_ticket_allocation_data') }}",
                    method:"GET",
                    data:{
                        column_name:column_name,
                        order:order,
                        workflow: 'software_update'
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
                        alert('Error - ' + errorMessage);
                    },
                    success:function(data){

                        $('#tbl_software_updating').html(data);
                        $('#'+column_name+'').append(arrow);
                    }
                })
            });

			//Terminal Replacement
            $(document).on('click', '.column_sort_tr', function(){

				var column_name = $(this).attr("id");
				var order = $(this).data("order");
				var arrow = '';

				if(order == 'desc'){

					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';

				}else{
					arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';
				}

				$.ajax({
					url:"{{ route('get_ticket_allocation_data') }}",
					method:"GET",
					data:{
						column_name:column_name,
						order:order,
						workflow: 'terminal_replacement'
					},
					error: function(xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
						alert('Error - ' + errorMessage);
					},
					success:function(data){

						$('#tbl_terminal_replacement').html(data);
						$('#'+column_name+'').append(arrow);
					}
				})

			});

            // Backup Removal
            $(document).on('click', '.column_sort_br', function(){

                var column_name = $(this).attr("id");
                var order = $(this).data("order");
                var arrow = '';

                if(order == 'desc'){

                    arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';

                }else{
                    arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';
                }

                $.ajax({
                    url:"{{ route('get_ticket_allocation_data') }}",
                    method:"GET",
                    data:{
                        column_name:column_name,
                        order:order,
                        workflow: 'backup_removal'
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
                        alert('Error - ' + errorMessage);
                    },
                    success:function(data){

                        $('#tbl_backup_remove').html(data);
                        $('#'+column_name+'').append(arrow);
                    }
                })

            });
        }
    );


</script>
<link rel="stylesheet" href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css>

@endsection
