@extends('layouts.tmc')

@section('title')
    Job Card - Cancellation Process
@endsection

@section('body')
<div id="tbldiv" style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
	<form method="POST" action="{{route('jobcard_cancellation_process')}}">

		@CSRF

		<div class="col-sm-12">

			<div class="card">

				<div class="card-header">
					Job Card - Cancellation Process
				</div>

				<div class="card-body">

					<div class="col-sm-12">
						<?php echo $JC['attributes']['process_message']; ?>
					</div>

                    <div class="mb-2 row">

                        <label for="tid" class="col-sm-1 col-form-label-sm">Jobcard No.</label>
                        <div class="col-sm-2">
                            <input type="text" name="jobcard_number" class="form-control form-control-sm" id="jobcard_number" value="{{$JC['attributes']['jobcard_number']}}">
                            @if($JC['attributes']['validation_messages']->has('Jobcard Number'))
                                <script>
                                        document.getElementById('jobcard_number').className = 'form-control form-control-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Jobcard Number") }}</div>
                            @endif
                        </div>

                        <div class="col-sm-1">
                            <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Display">
                        </div>

                    </div>

					<div class="mb-4 row">

                        <label for="tid" class="col-sm-1 col-form-label-sm">Reason</label>
                        <div class="col-sm-8">
                            <input type="text" name="cancel_reason" class="form-control form-control-sm" id="cancel_reason" value="{{$JC['attributes']['cancel_reason']}}">
                            @if($JC['attributes']['validation_messages']->has('Cancel Reason'))
                                <script>
                                        document.getElementById('cancel_reason').className = 'form-control form-control-sm is-invalid';
                                </script>
                                <div class="invalid-feedback">{{ $JC['attributes']['validation_messages']->first("Cancel Reason") }}</div>
                            @endif
                        </div>

                        <div class="col-sm-1">
                            <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Cancel">
                        </div>

                    </div>

                    <hr>

                    <div class="table-responsive">
                    <div id="tbldiv" style="width: 100%;">

                        <table id="tblgrid1" class="table table-hover table-sm table-bordered">

                            <tr style="font-family: Consolas; font-size: 13px;">
                                <td> <b> JobCard No. </b> </td>
                                <td> <b> JC Date </b> </td>
                                <td> <b> Serial No. </b> </td>
                                <td> <b> Model </b> </td>
                                <td> <b> Bank </b> </td>
                                <td> <b> Lot No. </b> </td>
                                <td> <b> Box No. </b> </td>
                                <td> <b> Tid </b> </td>
                                <td> <b> Merchant </b> </td>
                                <td> <b> Pod No. </b> </td>
                                <td> <b> Return Pod No. </b> </td>
                            </tr>

                            @if(count($JC['list']))

                                <tbody>

                                    @foreach($JC['list'] as $row)

                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <td> {{$row->jobcard_no}} </td>
                                            <td> {{$row->tdate}} </td>
                                            <td> {{$row->serialno}} </td>
                                            <td> {{$row->model}} </td>
                                            <td> {{$row->bank}} </td>
                                            <td> {{$row->lot_number}} </td>
                                            <td> {{$row->box_number}} </td>
                                            <td> {{$row->tid}} </td>
                                            <td> {{$row->merchant}} </td>
                                            <td> {{$row->podno}} </td>
                                            <td> {{$row->return_podno}} </td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            @else

                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td> - </td>
                                </tr>

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
