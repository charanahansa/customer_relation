@extends('layouts.hw')

@section('title')
    Terminal Release Process
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
    <form method="POST" action="{{route('terminal_release_process')}}">

        @CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Terminal Release Process
				</div>

				<div class="card-body">

                    <div class="col-sm-12">
						<?php echo $TRP['attributes']['process_message']; ?>
					</div>

                    <div class="row no-gutters">

                        <div class="col-12 col-sm-1 col-md-1">
                        <div style="margin-left: 2%; margin-right: 1%;">

                            <div class="mb-1 row">
                                <label for="fromdate" class="col-sm-12 col-form-label-sm">Jobcard No.</label>
                            </div>

                            <div class="mb-1 row">
                                <div class="col-sm-12">
                                    <textarea name="jobcard_numbers" id="jobcard_numbers" class="form-control form-control-sm"  style="resize:none" rows="20">{{$TRP['attributes']['jobcard_numbers']}}</textarea>
                                    @if($TRP['attributes']['validation_messages']->has('jobcard_numbers'))
                                        <script>
                                                document.getElementById('jobcard_numbers').className = 'form-control form-control-sm is-invalid';
                                        </script>
                                    @endif
                                </div>
                            </div>


                        </div>
                        </div>

                        <div class="col-12 col-sm-11 col-md-11">
                        <div style="margin-left: 2%; margin-right: 1%;">

                            <div class="mb-1 row">
                                <label for="tid" class="col-sm-1 col-form-label-sm">Officer</label>
                                <div class="col-sm-2">
									<select name="officer" id="officer" class="form-control form-control-sm">
                                        @foreach($TRP['officer'] as $row)
                                            @if($TRP['attributes']['officer'] == $row->id)
                                                <option value ="{{$row->id}}" selected>{{$row->name}}</option>
                                            @else
                                                <option value ="{{$row->id}}">{{$row->name}}</option>
                                            @endif
                                        @endforeach
                                        @if($TRP['attributes']['officer'] == 0)
                                            <option value =0 selected>Select the Officer</option>
                                        @else
                                            <option value =0>Select the Officer</option>
                                        @endif
                                    </select>
                                    @if($TRP['attributes']['validation_messages']->has('officer'))
                                        <script>
                                                document.getElementById('officer').className = 'form-control form-control-sm is-invalid';
                                        </script>
                                        <div class="invalid-feedback">{{ $TRP['attributes']['validation_messages']->first("officer") }}</div>
                                    @endif
								</div>

                                <label for="tid" class="col-sm-1 col-form-label-sm">Remark</label>
                                <div class="col-sm-6">
									<input type="text" name='remark' id='remark' class="form-control form-control-sm" value=''>
                                    @if($TRP['attributes']['validation_messages']->has('officer'))
                                        <script>
                                                document.getElementById('officer').className = 'form-control form-control-sm is-invalid';
                                        </script>
                                        <div class="invalid-feedback">{{ $TRP['attributes']['validation_messages']->first("officer") }}</div>
                                    @endif
								</div>

                                <div class="col-sm-1">
                                    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width: 100%" value="Get Infor">
                                </div>

                                <div class="col-sm-1">
                                    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width: 100%" value="Release">
                                </div>

                            </div>
                            <hr>

                            <div class="table-responsive">
                                <div id="tbldiv" style="width: 100%;">

                                    <table id="tbl_jobcards" class="table table-hover table-sm table-bordered">
                                        <?php $icount = 1; ?>
                                        <thead>
                                            <tr style="font-family: Consolas; font-size: 13px; background-color: skyblue;">
                                                <th>No</th>
                                                <th>Job Card No.</th>
                                                <th>Job Card Date</th>
                                                <th>Bank</th>
                                                <th>Serial No. </th>
                                                <th>Model </th>
                                                <th>Qt No.</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        @if(count($TRP['jobcard_table']))

                                            <tbody>
                                                @foreach($TRP['jobcard_table'] as $row)
                                                    <tr style="font-family: Consolas; font-size: 13px;">
                                                        <td>{{$icount}}</td>
                                                        <td>{{$row->jobcard_no}}</td>
                                                        <td>{{$row->jobcard_date}}</td>
                                                        <td>{{$row->bank}}</td>
                                                        <td>{{$row->serialno}}</td>
                                                        <td>{{$row->model}}</td>
                                                        <td>{{$row->qt_no}}</td>
                                                        <td>{{$row->status}}</td>
                                                        <td><input type="button" id='remove' name='remove' class="btn btn-danger btn-sm" style="width: 100%" value='Remove' onclick='reomveJobcard(this.parentNode.parentNode.rowIndex)'></td>
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

                </div>

            </div>

        </div>

    </form>
</div>
<script>

    function reomveJobcard(rowIndex){

        var jobcard_no = document.getElementById("tbl_jobcards").rows[rowIndex].cells[1].innerHTML;

        if(jobcard_no == '-'){

            return;
        }

        $.ajax({
            url : "remove_jobcards",
            type: 'post',
            dataType: 'json',
            data:{
                _token : "{{ csrf_token() }}",
                jobcard_no : jobcard_no
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText + ': ' + xhr.responseText
                alert('Remove Jobcards Error :- ' + errorMessage);
            },
            success:function(response){

                if(response['process_status'] == true){

                    var rowcount = document.getElementById('tbl_jobcards').rows.length;
                    document.getElementById("tbl_jobcards").deleteRow(rowIndex);

                    if(rowcount==2){

                        var table = document.getElementById("tbl_jobcards");
                        var row = table.insertRow(1);

                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4);
                        var cell6 = row.insertCell(5);
                        var cell7 = row.insertCell(6);
                        var cell8 = row.insertCell(7);
                        var cell9 = row.insertCell(8);

                        cell1.innerHTML = '-';
                        cell2.innerHTML = '-';
                        cell3.innerHTML = '-';
                        cell4.innerHTML = '-';
                        cell5.innerHTML = '-';
                        cell6.innerHTML = '-';
                        cell7.innerHTML = '-';
                        cell8.innerHTML = '-';
                        cell9.innerHTML = "<input type='button' value='Remove' class='btn btn-danger btn-sm' style='width: 100%' value='Remove' onclick='reomveJobcard(this.parentNode.parentNode.rowIndex)'>";
                    }

                }else{

                    alert('FALSE');
                }
            }

        });
    }

</script>


@endsection
