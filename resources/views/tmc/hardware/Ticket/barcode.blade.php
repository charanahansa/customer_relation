<!DOCTYPE html>
<html>
<head>
	<title> Jobcard Barcode </title>
	<style>
		p.inline {display: inline-block;}
		span { font-size: 9px;}
	</style>
	<style type="text/css" media="print">
		@page {
			size: height:30px !important;   /* Auto is the initial value */
			margin: 0mm;  					/* This affects the margin in the printer settings */
		}
	</style>
</head>
<body onload="window.print();" style="margin: 0;">

	@php

    	$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();

	@endphp

	<table style='margin-left: 3px;width:290px;height:98px'>
		<tr>
			<td>
				<span style='padding-left:6px'>S/N</span><br>
				<img style='width:150px; padding-left:6px' src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($JPD['serial_number'], $generatorPNG::TYPE_CODE_128)) }}"></b>
				<span style='display: block; padding-left:6px'> {{$JPD['serial_number']}} </span>

				<hr style='margin:0px'>

				<span  style='padding-left:6px'>J/N</span><br>
				<img style='width:135px; height: 15px; padding-left:6px' src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($JPD['jobcard_number'], $generatorPNG::TYPE_CODE_128)) }}"></b>
				<span style='display: block; padding-left:6px'>{{$JPD['jobcard_number']}}</span>

			</td>
			<td >
                @if($JPD['lot'] == 1)
                    <span><b>Lot No. {{$JPD['lot_number']}}  Box No. {{$JPD['box_number']}}</b></span><br>
                @else
                    <span><b>Merchant: {{$JPD['merchant']}} </b></span><br>
                @endif
				<hr style='margin:0px'>
				<span ><b>Fault: {{$JPD['fault']}} </b></span><br>
				<hr style='margin:0px'>
				<span ><b>Officer: {{$JPD['officer']}} </b></span><br>
				<hr style='margin:0px'>
				<span ><b>Date: {{$JPD['ticket_date']}} </b></span><br>
				<hr style='margin:0px'>
				<span ><b>Bank:  {{$JPD['bank']}} </b></span>
			</td>
		</tr>
	</table>

</body>
</html>
