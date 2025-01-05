@extends('layouts.hw')

@section('title')
    Spare Part Request List
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('spare_part_request_list_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Spare Part Request List
				</div>

				<div class="card-body">

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">Type</label>
						<div class="col-sm-2">
							<select name="part_type" id="part_type" class="form-control form-control-sm">
								<option value ="New">New</option>
								<option value ="Used">Used</option>
							</select>
						</div>

						<label for="tid" class="col-sm-q col-form-label-sm">Issue Type</label>
						<div class="col-sm-2">
							<select name="issue_type" id="issue_type" class="form-control form-control-sm">
								@foreach($SPR['spare_part_issue_type'] as $row)
									@if($SPR['attributes']['issue_type'] == $row->sp_issue_type_id )
										<option value ="{{$row->sp_issue_type_id }}" selected>{{$row->sp_issue_type_name}}</option>
									@else
										<option value ="{{$row->sp_issue_type_id }}">{{$row->sp_issue_type_name}}</option>
									@endif
								@endforeach
								@if($SPR['attributes']['issue_type']== 0)
									<option value ="0" selected>Select the Issue Type</option>
								@endif
							</select>
						</div>

					</div>

					<div class="mb-2 row">

						<label for="tid" class="col-sm-1 col-form-label-sm">To Bin</label>
						<div class="col-sm-3">
							<select name="bin_id" id="bin_id" class="form-control form-control-sm">
								@foreach($SPR['bin'] as $row)
									@if($SPR['attributes']['bin_id'] == $row->bin_id)
										<option value ="{{$row->bin_id}}" selected>{{$row->bin_name}}</option>
									@else
										<option value ="{{$row->bin_id}}">{{$row->bin_name}}</option>
									@endif
								@endforeach
								@if($SPR['attributes']['bin_id']== 0)
									<option value ="0" selected>Select the Bin</option>
								@endif
							</select>
							@if($SPR['attributes']['validation_messages']->has('bin_id'))
								<script>
										document.getElementById('bin_id').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("bin_id") }}</div>
							@endif
						</div>

						<label for="tid" class="col-sm-1 col-form-label-sm">Bank</label>
						<div class="col-sm-3">
							<select name="bank" id="bank" class="form-control form-control-sm">
								@foreach($SPR['bank'] as $row)
									@if($SPR['attributes']['bank'] == $row->bank)
										<option value ="{{$row->bank}}" selected>{{$row->bank}}</option>
									@else
										<option value ="{{$row->bank}}">{{$row->bank}}</option>
									@endif
								@endforeach
								@if($SPR['attributes']['bank']== '0')
									<option value ="0" selected>Select the Bank</option>
								@else
									<option value ="0">Select the Bank</option>
								@endif
							</select>
							@if($SPR['attributes']['validation_messages']->has('bank'))
								<script>
										document.getElementById('bank').className = 'form-control form-control-sm is-invalid';
								</script>
								<div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("bank") }}</div>
							@endif
						</div>

                        <label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
                        <div class="col-sm-3">
                            <select name="officer_id" id="officer_id" class="form-control form-control-sm">
                                @foreach($SPR['officer'] as $row)
                                    @if($SPR['attributes']['officer_id'] === $row->id)
                                        <option value ={{$row->id}} selected>{{$row->name}}</option>
                                    @else
                                        <option value ={{$row->id}}>{{$row->name}}</option>
                                    @endif
                                @endforeach
                                @if($SPR['attributes']['officer_id']== 0)
                                    <option value =0 selected>Select the Officer</option>
								@else
									<option value ="0">Select the Officer</option>
                                @endif
                            </select>
                            @if($SPR['attributes']['validation_messages']->has('officer_id'))
                                <script>
                                        document.getElementById('officer_id').className = 'form-select form-select-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("officer_id") }}</div>
                            @endif
                        </div>

                    </div>

					<div class="mb-2 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">Spare Part</label>
                        <div class="col-sm-5">
                            <select name="spare_part" id="spare_part" class="form-control form-control-sm">
                                @foreach($SPR['spare_part'] as $row)
                                    @if($SPR['attributes']['spare_part'] == $row->part_id)
                                        <option value ="{{$row->part_id}}" selected>{{$row->part_name}}</option>
                                    @else
                                        <option value ="{{$row->part_id}}">{{$row->part_name}}</option>
                                    @endif
                                @endforeach
                                @if($SPR['attributes']['spare_part']== 0)
                                    <option value ="0" selected>Select the Spare Part</option>
                                @endif
                            </select>
                            @if($SPR['attributes']['validation_messages']->has('spare_part'))
                                <script>
                                        document.getElementById('spare_part').className = 'form-select form-select-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $SPR['attributes']['validation_messages']->first("spare_part") }}</div>
                            @endif
                        </div>
                    </div>

					<div class="mb-4 row">
                        <label for="tid" class="col-sm-1 col-form-label-sm">From Date</label>
						<div class="col-sm-2">
							<input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{$SPR['attributes']['from_date']}}">
						</div>
						<label for="tid" class="col-sm-1 col-form-label-sm">To Date</label>
						<div class="col-sm-2">
							<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{$SPR['attributes']['to_date']}}">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width: 100%" value="Search">
						</div>
                    </div>

					<div class="table-responsive">
					<div id="tbldiv" style="width: 100%;">

						<table id="sp_request" class="table table-hover table-sm table-bordered">
							<?php $icount = 1; ?>
							<thead>

								<tr style="font-family: Consolas; font-size: 13px;">
									<th>No</th>
									<th>Requested Id</th>
									<th>Requested Date & Time</th>
									<th>Part Type</th>
									<th>Issue Type</th>
									<th>Issue Referance </th>
									<th>Bank</th>
									<th>Officer</th>
									<th>Spare Part</th>
									<th>Quantity</th>
								</tr>

							</thead>
							@if(count($SPR['data_table']))

								<tbody>

									@foreach($SPR['data_table'] as $row)

										<tr style="font-family: Consolas; font-size: 14px;">
											<td>{{$icount}}</td>
											<td>{{$row->spr_id}}</td>
											<td>{{$row->spr_date}}</td>
											<td>{{$row->part_type}}</td>
											<td>{{$row->sp_issue_type_name}}</td>
											<td>{{$row->referance}}</td>
											<td>{{$row->bank}}</td>
											<td>{{$row->officer_name}}</td>
											<td>{{$row->spare_part_name}}</td>
											<td>{{$row->quantity}}</td>
											<td><input type="button" class="btn btn-info btn-sm" style="width: 100%;" value="Process" data-toggle="modal" data-target="#staticBackdrop" onclick="item_load(this.parentNode.parentNode.rowIndex)"></td>
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
									</tr>
								</tbody>

							@endif



						</table>

					</div>
					</div>

				</div>

			</div>

		</div>

		<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

			<div class="modal-dialog modal-lg">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="staticBackdropLabel">Spare Part Issue Process</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>

					</div>

					<div class="modal-body">

                        <div class="col-sm-12">
                            <div id="main_alert"></div>
                        </div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-3 col-form-label-sm">SPR ID</label>
							<div class="col-sm-3">
								<input type="text" name="spr_id" id="spr_id" value="" readonly>
                                <div id="span_spr_id"></div>
							</div>
							<div class="col-sm-3">
								<input type="text" name="tbl_row_id" id="tbl_row_id" value="" readonly>
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-3 col-form-label-sm">From Bin & Quantity</label>
							<div class="col-sm-9">
								<select name="from_bin_id" id="from_bin_id" class="form-control form-control-sm">
									<option value =0>Select the Bin Quantity</option>
								</select>
                                <div id="span_from_bin_id"></div>
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-3 col-form-label-sm">Quantity</label>
							<div class="col-sm-9">
								<input type="text" name="quantity" id="quantity" value="" readonly>
                                <div id="span_quantity"></div>
							</div>
						</div>

						<div class="mb-2 row">
							<label for="tid" class="col-sm-3 col-form-label-sm">Remark</label>
							<div class="col-sm-9">
								<input type="text" name="remark" id="remark" class="form-control form-control-sm" value="">
                                <div id="span_remark"></div>
							</div>
						</div>

						<div class="mb-1 row">
							<label for="tid" class="col-sm-3 col-form-label-sm">Got Old Spare Part</label>
							<div class="col-sm-3">
								<select name="got_old_spare_part" id="got_old_spare_part" class="form-control form-control-sm">
									<option value =1>Yes</option>
									<option value =0 selected>No</option>
								</select>
                                <div id="span_from_bin_id"></div>
							</div>
						</div>

					</div>

					<div class="modal-footer">

						<div class="col-sm-3">
							<button type="button" class="btn btn-secondary" style="width: 100%" data-dismiss="modal">Close</button>
						</div>

						<div class="col-sm-3">
							<button type="button" class="btn btn-success" style="width: 100%" onclick="spare_part_issue_process()">Issue</button>
						</div>

						<div class="col-sm-3">
							<button type="button" class="btn btn-danger" style="width: 100%" onclick="spare_part_reject_process()">Reject</button>
						</div>

					</div>

				</div>
			</div>
		</div>

	</form>
	</div>

	<script>

		document.getElementById('tbl_row_id').style.display = 'none'

		function item_load(rowIndex){

			var spr_id = document.getElementById("sp_request").rows[rowIndex].cells[1].innerHTML;
			var quantity = document.getElementById("sp_request").rows[rowIndex].cells[9].innerHTML;

			document.getElementById('from_bin_id').className = 'form-control form-control-sm';
			document.getElementById('quantity').className = 'form-control form-control-sm';
			document.getElementById('spr_id').className = 'form-control form-control-sm';
			document.getElementById('remark').className = 'form-control form-control-sm';

			$('#from_bin_id').empty();
			$("#from_bin_id").append("<option value=0>Select the Bin Quantity</option>");

            document.getElementById('main_alert').innerHTML = '';
            var element = document.getElementById('main_alert');
            element.classList.remove('alert-danger');

			$.ajax({

				type:'GET',
				url:"{{ route('get_sp_bin_qty') }}",
				dataType: 'json',
				data:{
					spare_part_request_id: spr_id,
				},
                error: function(xhr, status, error) {

                    var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
                    alert('Item Load Error -  ' + errorMessage);
                },
				success:function(response){

					var response_length = 0;


					if (response != null) {
						response_length = response.length;
					}

					if (response_length > 0) {

						document.getElementById("spr_id").value = spr_id;
						document.getElementById("quantity").value = quantity;
						document.getElementById("tbl_row_id").value = rowIndex;

						for (var i = 0; i < response_length; i++) {

							var option_name = response[i].bin_name + " -> " + response[i].spare_part_name + " -> " + response[i].Total;

							var option = "<option value='" +  response[i].bin_id + "'>" + option_name + "</option>";

							$("#from_bin_id").append(option);

						}
					}
				}

			});

		}

		function spare_part_issue_process(){

			var spr_id = document.getElementById("spr_id").value;
			var spr_date = '';
			var spr_part_type = '';
			var spr_issue_type = '';
			var spare_part_id = 0;
            var spare_part_name = 0;
			var from_bin_id = 0;
			var to_bin_id = 0;
			var bank = 0;
			var user_id = 0;
			var workflow_id = 0;
			var referance_number = 0;
			var quantity = 0;
			var remark = '';
			var got_old_spare_part = 0;

            document.getElementById('from_bin_id').className = 'form-control form-control-sm';
			document.getElementById('quantity').className = 'form-control form-control-sm';
			document.getElementById('spr_id').className = 'form-control form-control-sm';
			document.getElementById('remark').className = 'form-control form-control-sm';

			document.getElementById('main_alert').innerHTML = '';
            var element = document.getElementById('main_alert');
            element.classList.remove('alert-danger');
			element.classList.remove('alert2');

			$.ajax({

				type:'GET',
				url:"{{ route('get_spare_part_request_note') }}",
				dataType: 'json',
				data:{
					spare_part_request_id: spr_id,
				},
				error: function(xhr, status, error) {

					var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
					alert('Spare Part Request Error :-  ' + errorMessage);
				},
				success:function(response){

					var response_length = 0;

					if (response != null) {

						response_length = response.length;
					}

					if (response_length > 0) {

						for (var i = 0; i < response_length; i++) {

							spr_part_type = response[i].part_type;
							spr_issue_type = response[i].issue_type;
							spare_part_id = response[i].spare_part_id;
							from_bin_id = response[i].from_bin_id;
							to_bin_id = response[i].to_bin_id;
							bank = response[i].bank;
							user_id = response[i].user_id;
							workflow_id = response[i].workflow_id;
							referance_number = response[i].referance_number;
							quantity = response[i].quantity;
							remark = response[i].remark;

                            spare_part_name = response[i].spare_part_name;
						}


                        let confirm_message = " Do you want to issue this quantity ?? ";
                        if (confirm(confirm_message) == true) {

                            var today = new Date();
                            var dd = String(today.getDate()).padStart(2, '0');
                            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy = today.getFullYear();

                            var spi_date = yyyy + '/' + mm + '/' + dd;

                            from_bin_id = document.getElementById('from_bin_id').value;
                            remark =  document.getElementById('remark').value;
                            got_old_spare_part = document.getElementById('got_old_spare_part').value;

                            $.ajax({

                                type:'POST',
                                url:"{{ route('spare_part_issue_note_process') }}",
                                dataType: 'json',
                                data:{
                                    _token : "{{ csrf_token() }}",
                                    spare_part_request_id: spr_id,
                                    spi_date : spi_date ,
                                    part_type : spr_part_type ,
                                    issue_type : spr_issue_type ,
                                    spare_part_id : spare_part_id ,
                                    from_bin_id : from_bin_id ,
                                    to_bin_id : to_bin_id ,
                                    bank : bank ,
                                    user_id : user_id ,
                                    quantity : quantity ,
                                    remark : remark ,
                                    got_old_spare_part : got_old_spare_part,
                                },
                                error: function(xhr, status, error) {

                                    var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
                                    alert('Spare Part Issue Error :- ' + errorMessage);
                                },
                                success:function(response){

                                    validation_messages_array = response['data']['attributes']['validation_messages'];

                                    if(response['data']['attributes']['process_status'] == false){

                                        $.each( validation_messages_array, function( key, value ) {

                                            document.getElementById(key).className = 'form-control form-control-sm is-invalid';
                                            document.getElementById('span_'+key).innerHTML = value;
                                            document.getElementById('span_'+key).className = 'invalid-feedback';
                                        });

                                        document.getElementById('main_alert').className = 'alert alert-danger';
                                        document.getElementById('main_alert').innerHTML = response['data']['attributes']['process_message'];

                                    }else if(response['data']['attributes']['process_status'] == true){

                                        document.getElementById('from_bin_id').className = 'form-control form-control-sm is-valid';
                                        document.getElementById('quantity').className = 'form-control form-control-sm is-valid';
                                        document.getElementById('spr_id').className = 'form-control form-control-sm is-valid';
                                        document.getElementById('remark').className = 'form-control form-control-sm is-valid';

                                        var rowIndex = document.getElementById("tbl_row_id").value;
                                        document.getElementById("sp_request").deleteRow(rowIndex);

                                        alert('Isssue Note Genarated successfully.');
                                    }
                                }

                            });


                        } else {

                        }

					}
				}

			});





		}

		function spare_part_reject_process(){

			var spr_id = document.getElementById("spr_id").value;

			document.getElementById('from_bin_id').className = 'form-control form-control-sm';
			document.getElementById('quantity').className = 'form-control form-control-sm';
			document.getElementById('spr_id').className = 'form-control form-control-sm';
			document.getElementById('remark').className = 'form-control form-control-sm';

			document.getElementById('main_alert').innerHTML = '';
            var element = document.getElementById('main_alert');
            element.classList.remove('alert-danger');

			let confirm_message = " Do you want to Reject this spare part request note ? ";
            if (confirm(confirm_message) == true) {

				$.ajax({

					type:'POST',
					url:"{{ route('spare_part_request_note_reject_process') }}",
					dataType: 'json',
					data:{
						_token : "{{ csrf_token() }}",
						spare_part_request_id: spr_id
					},
					error: function(xhr, status, error) {

						var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
						alert('Spare Part Reject Error :- ' + errorMessage);
					},
					success:function(response){

						validation_messages_array = response['validation_messages'];

						if(response['validation_result'] == false){

							$.each( validation_messages_array, function( key, value ) {

								document.getElementById(key).className = 'form-control form-control-sm is-invalid';
								document.getElementById('span_'+key).innerHTML = value;
								document.getElementById('span_'+key).className = 'invalid-feedback';
							});

							document.getElementById('main_alert').className = 'alert alert-danger';
							document.getElementById('main_alert').innerHTML = response['front_end_message'];

						}else if(response['process_status'] == false){

							$.each( validation_messages_array, function( key, value ) {

								document.getElementById(key).className = 'form-control form-control-sm is-invalid';
								document.getElementById('span_'+key).innerHTML = value;
								document.getElementById('span_'+key).className = 'invalid-feedback';
							});

							document.getElementById('main_alert').className = 'alert alert-danger';
							document.getElementById('main_alert').innerHTML = '@@';

						}else if(response['process_status'] == true){

							document.getElementById('spr_id').className = 'form-control form-control-sm is-valid';

							var rowIndex = document.getElementById("tbl_row_id").value;
							//alert('Row Index' + rowIndex)
							document.getElementById("sp_request").deleteRow(rowIndex);

							document.getElementById('main_alert').className = 'alert alert-success';
							document.getElementById('main_alert').innerHTML = 'Spare Part Request is Rejected successfully';
						}
					}

				});

			}else{


			}
		}

		$('#staticBackdrop').on('hidden.bs.modal', function () {

			alert('AAA');
		});

	</script>

@endsection



