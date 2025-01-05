@extends('layouts.tmc')

@section('title')
    Quotation
@endsection
@section('body')

	@if(session('error'))
	   <div class="alert alert-danger">
	       {{ session('error') }}
	   </div>
	@endif

    <form method="post"  action="{{route('quotation_approve_process')}}">

        {{csrf_field()}}

		<div class="table-responsive">
			<table class="table table-bordered">

				@foreach ($Qt['information'] as $row)

					<input type="hidden" name="qt_no" value="{{$row->QT_NO}}">

					@if(! ($Qt['process_message'] == ''))
						<tr style="font-family: Consolas;">
							<td style="text-align: left;"  colspan="12">
								<?php echo $Qt['process_message'];  ?>
							</td>
						</tr>
					@endif

					<tr style="font-family: Consolas; font-size: 16px;">
						<td style="text-align: center;"  colspan="11"><h3>QUOTATION </h3></td>
					</tr>

					<tr style="font-family: Consolas; font-size: 14px;">
						<td>Seller  : </td>
						<td colspan="5"></label>Epic Techno -Village,<br>158/1/A, Kaduwela Road,<br>Thalangama,Battaramulla 10120.</td>
						<td>
							Vat No. <br>
							Qt No. <br>
							Jobcard No.
						</td>
						<td colspan="5">
							114212610 700 - Epic Lanka (Pvt) Ltd. <br>
							{{$row->QT_NO}} <br>
							{{$row->JOBCARD_NO}}
						</td>
					</tr>
					<tr style="font-family: Consolas; font-size: 14px;">
						<td>Client (Buyer)  : </td>
						<td colspan="5"><?php echo $Qt['client']; ?></td>
						<td>
							Qt Date <br>
							Qt Expire Date. <br>
							Serial No. <br>
							Model <br>
							Terms of Payment Course :
						</td>
						<td colspan="5">
							<?php echo date_format(date_create($row->QT_DATE),"Y/m/d"); ?> <br>
							<?php echo date_format(date_add(date_create($row->QT_DATE), date_interval_create_from_date_string("30 days")),'Y/m/d'); ?> <br>
							{{$row->SERIAL_NO}} <br>
							{{$row->MODEL}}
							<p>Full Payment Upon Delivery </p>
						</td>
					</tr>

				@endforeach
			</table>

			<table class="table table-bordered">
				<tr style="font-family: Consolas; font-size: 14px;">
					<th>DESCRIPTION</th>
					<th>CURRENCY</th>
					<th>QTY<br></th>
					<th>UNIT PRICE</th>
					<th>TOTAL</th>
				</tr>
				<tr style="font-family: Consolas; font-size: 10px;">
					<td  colspan="5">Investigation &amp; troubleshooting,engineers charges, testing &amp; commissioning charges for the Following damaged item<br></td>
				</tr>
				@foreach ($Qt['detail'] as $row)
					<tr style="font-family: Consolas; font-size: 14px;">
						<td>{{$row->DESCRIPTION}}</td>
						<td>LKR</td>
						<td>1</td>
						<td style="text-align: right;">@money($row->PRICE)</td>
						<td style="text-align: right;">@money($row->PRICE)</td>
					</tr>
				@endforeach
				<tr style="font-family: Consolas; font-size: 10px;">
					<td  colspan="5">Please Note : Spare Part prices are subjected to change periodically based on the rate of exchange of the US $ and supplier pricing.</td>
				</tr>
				<tr style="font-family: Consolas; font-size: 14px;">
					<td  colspan="4">Grand Total </td>
					<td style="text-align: right;"><b> @money($Qt['quotation_amount']) </b></td>
				</tr>
				<tr style="font-family: Consolas; font-size: 11px;">
					<td  colspan="5">
						<p>Government taxes including VAT 8%  is applicable on above total</p>
						<p>Quotation validity period is 30 days for date of quotation</p>
						<p>A charge of Rs 1,500.00 will be applicable for a re-quotation the expiration of quotation </p>
					</td>
				</tr>
			</table>
		</div>


    </form>



@endsection
