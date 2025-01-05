@extends('layouts.tmc')

@section('title')
    Delivery Inquire
@endsection

@section('body')

<div style="width: 98%;  margin-right: 1%; margin-left: 1%; margin-top: 1%;">
<form method="POST" name="form_name" action="{{route('delivery_inquire_process')}}">

	@csrf

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header">
				Delivery Inquire
			</div>

            <div class="card-body">

                <div class="mb-4 row">

					<label for="tid" class="col-sm-1 col-form-label-sm">Serial Number</label>
					<div class="col-sm-4">
						<input type="text" name="serial_number" id="serial_number" class="form-control form-control-sm" value="">
					</div>

                    <div class="col-sm-1">
						<input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Inquire">
					</div>

				</div>

                <div class="row no-gutters">

                    <div class="col-12 col-sm-6 col-md-6">
                    <div style="margin-left: 2%; margin-right: 1%;">

                        <div class="card">

                            <div class="card-header">
                                Epic Delivery Inquire Result
                            </div>

                            <div class="card-body">

                                <table id="tbl_epic" class="table table-hover table-sm table-bordered">
                                    <thead>
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <th>No</th>
                                            <th>Delivery No.</th>
                                            <th>Date</th>
                                            <th>Bank</th>
                                            <th>Model</th>
                                            <th>Serial No. </th>
                                            <th>Invoice No.</th>
                                            <th>Sales Order No.</th>
                                        </tr>
                                    </thead>
                                    @if(count($DI['epic_delivery']))
                                        <?php $icount = 1; ?>
                                        <tbody>
                                            @foreach($DI['epic_delivery'] as $row)
                                                <tr style="font-family: Consolas; font-size: 13px;">
                                                    <td>{{$icount}}</td>
                                                    <td>{{$row->delivery_id}}</td>
                                                    <td>{{$row->delivery_date}}</td>
                                                    <td>{{$row->bank}}</td>
                                                    <td>{{$row->model}}</td>
                                                    <td>{{$row->serial_number}}</td>
                                                    <td>{{$row->invoice_number}}</td>
                                                    <td>{{$row->sales_order_number}}</td>
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

                    <div class="col-12 col-sm-6 col-md-6">
                    <div style="margin-left: 2%; margin-right: 1%;">

                        <div class="card">

                            <div class="card-header">
                                Scienter Delivery Inquire Result
                            </div>

                            <div class="card-body">

                                <table id="tbl_epic" class="table table-hover table-sm table-bordered">
                                    <thead>
                                        <tr style="font-family: Consolas; font-size: 13px;">
                                            <th>No</th>
                                            <th>Delivery No.</th>
                                            <th>Date</th>
                                            <th>Bank</th>
                                            <th>Model</th>
                                            <th>Serial No. </th>
                                            <th>Invoice No.</th>
                                            <th>Sales Order No.</th>
                                        </tr>
                                    </thead>
                                    @if(count($DI['scienter_delivery']))
                                        <?php $icount = 1; ?>
                                        <tbody>
                                            @foreach($DI['scienter_delivery'] as $row)
                                                <tr style="font-family: Consolas; font-size: 13px;">
                                                    <td>{{$icount}}</td>
                                                    <td>{{$row->DocNo}}</td>
                                                    <td>{{$row->Deliver_Date}}</td>
                                                    <td>{{$row->BankCode}}</td>
                                                    <td>{{$row->ItemCode}}</td>
                                                    <td>{{$row->serial}}</td>
                                                    <td>{{$row->invoiceNo}}</td>
                                                    <td>{{$row->salesorderNo}}</td>
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

<div id="hide_div">

    <form id="myForm" style="display: none;" method="post" target='_blank' action="{{route('get_terminal_out_note')}}">
        @csrf
        <input type="text" name="ticket_number" id="ticket_number" values="">
    </form>

</div>

<script>

    function openTicket(ticket_number){

        document.getElementById("ticket_number").value = ticket_number;
        document.getElementById("myForm").submit();
    }

</script>



@endsection
