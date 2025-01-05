<?php

namespace App\Http\Controllers\Hardware\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App;
use App\Models\Hardware\Operation\Quotation;

class QuotationController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function load_quotation($qt_no){

		$objQuotation = new Quotation();

		$client = '';
		$quotation_amount = 0;

		$quotation_client_resultset = $objQuotation->get_client_address($qt_no);
		foreach($quotation_client_resultset as $row){

			$client = $row->ADDRESS;
		}

		$quotation_total_resultset = $objQuotation->get_quotation_amount($qt_no);
		foreach($quotation_total_resultset as $row){

			$quotation_amount = $row->qt_amount;
		}

		$data['information'] = $objQuotation->get_quotation_information($qt_no);
		$data['detail'] = $objQuotation->get_quotation_detail($qt_no);
		$data['client'] = $client;
		$data['quotation_amount'] = $quotation_amount;
		$data['process_message'] = "";

        if(Auth::user()->role == 3){

            return view('tmc.hardware.quotation')->with('Qt', $data);

        }elsE{

            return view('hardware.quotation.quotation')->with('Qt', $data);
        }

	}

	public function quotation_approve_process(Request $request){

		$qt_no = $request->qt_no;

		if($request->submit == 'PDF'){

			$html_code = $this->prepare_quotation_print_document($qt_no);

	        $pdf = App::make('dompdf.wrapper');
	        $pdf->loadHTML($html_code);
	        $pdf->setPaper('A4', 'portrait');

			return $pdf->download($qt_no .'.pdf');
		}


		$objQuotation = new Quotation();

		$client = '';
		$quotation_amount = 0;

		$quotation_client_resultset = $objQuotation->get_client_address($qt_no);
		foreach($quotation_client_resultset as $row){

			$client = $row->ADDRESS;
		}

		$quotation_total_resultset = $objQuotation->get_quotation_amount($qt_no);
		foreach($quotation_total_resultset as $row){

			$quotation_amount = $row->qt_amount;
		}

		$data['information'] = $objQuotation->get_quotation_information($qt_no);
		$data['detail'] = $objQuotation->get_quotation_detail($qt_no);
		$data['client'] = $client;
		$data['quotation_amount'] = $quotation_amount;

		return view('hardware.quotation.quotation')->with('Qt', $data);

	}

	private function prepare_quotation_print_document($qt_no){

		$objQuotation = new Quotation();

		$client = '';
		$quotation_amount = 0;

		$jobcard_no = '';
		$qt_date = '';
		$serial_no = '';
		$model = '';
		$quotation_detail = '';

		$quotation_client_resultset = $objQuotation->get_client_address($qt_no);
		foreach($quotation_client_resultset as $row){

			$client = $row->ADDRESS;
		}

		$quotation_total_resultset = $objQuotation->get_quotation_amount($qt_no);
		foreach($quotation_total_resultset as $row){

			$quotation_amount = $row->qt_amount;
		}

		$quotation_resultset = $objQuotation->get_quotation_information($qt_no);
		$quotation_detail_resultset = $objQuotation->get_quotation_detail($qt_no);

		foreach($quotation_resultset as $row){

			$jobcard_no = $row->JOBCARD_NO;
			$qt_date = $row->QT_DATE;
			$serial_no = $row->SERIAL_NO;
			$model = $row->MODEL;
		}

		foreach($quotation_detail_resultset as $row){

			$quotation_detail .= "
									<tr style='font-family: Consolas; font-size: 14px;'>
										<td> " . $row->DESCRIPTION . " </td>
										<td>LKR</td>
										<td>1</td>
										<td style='text-align: right;'> " . $this->retrieve_currency($row->PRICE) . "</td>
										<td style='text-align: right;'> " . $this->retrieve_currency($row->PRICE). "</td>
									</tr>

								 ";
		}

		$html_code = "  <!DOCTYPE html>
						<html>
						<head>
							<title>Print Document </title>

							<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>
							<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'></script>

							<style>
								table, th, td {
									border: 1px solid black;
									border-collapse: collapse;
									font-family:consolas;
								}
								th, td {
									padding: 1px;
									text-align: left;
									font-size: 11px
								}
							</style>
						</head>
						<body>

							<table style='width:100%'>

								<tr style='font-size: 20px;'>
									<td style='text-align: center;'  colspan='12'>
										QUOTATION
									</td>
								</tr>

								<tr style='font-family: Consolas; font-size: 14px;'>
									<td>Seller  : </td>
									<td colspan='6'></label>Epic Techno -Village,<br>158/1/A, Kaduwela Road,<br>Thalangama,Battaramulla 10120.</td>
									<td>
										Vat No. <br>
										Qt No. <br>
										Jobcard No.
									</td>
									<td colspan='4'>
										114212610 700 - Epic Lanka (Pvt) Ltd. <br>
										". $qt_no ." <br>
										". $jobcard_no ."
									</td>
								</tr>

								<tr style='font-family: Consolas; font-size: 14px;'>
									<td>Client (Buyer)  : </td>
									<td colspan='6'>". $client ."</td>
									<td>
										Qt Date <br>
										Qt Expire Date. <br>
										Serial No. <br>
										Model <br>
										Terms of Payment Course :
									</td>
									<td colspan='4'>
										" . date_format(date_create($qt_date),'Y/m/d') . " <br>
										" . date_format(date_add(date_create($qt_date), date_interval_create_from_date_string('30 days')),'Y/m/d') . " <br>
										". $serial_no ." <br>
										". $model ."
										<p>Full Payment Upon Delivery </p>
									</td>
								</tr>

							</table>

							<table class='table table-bordered'>

								<tr style='font-family: Consolas; font-size: 14px;'>
									<th>DESCRIPTION</th>
									<th>CURRENCY</th>
									<th>QTY<br></th>
									<th>UNIT PRICE</th>
									<th>TOTAL</th>
								</tr>

								<tr style='font-family: Consolas; font-size: 10px;'>
									<td  colspan='5'>Investigation &amp; troubleshooting,engineers charges, testing &amp; commissioning charges for the Following damaged item<br></td>
								</tr>

								". $quotation_detail ."

								<tr style='font-family: Consolas; font-size: 10px;'>
									<td  colspan='5'>Please Note : Spare Part prices are subjected to change periodically based on the rate of exchange of the US $ and supplier pricing.</td>
								</tr>

								<tr style='font-family: Consolas; font-size: 14px;'>
									<td  colspan='4'>Grand Total </td>
									<td style='text-align: right;'><b> ". $this->retrieve_currency($quotation_amount) ." </b></td>
								</tr>

								<tr style='font-family: Consolas; font-size: 11px;'>
									<td  colspan='5'>
										<p>Government taxes including VAT 8%  is applicable on above total</p>
										<p>Quotation validity period is 30 days for date of quotation</p>
										<p>A charge of Rs 1,500.00 will be applicable for a re-quotation the expiration of quotation </p>
									</td>
								</tr>

							</table>

						</div>

					</body>
					</html>

				";

		return $html_code;
	}

	public function retrieve_currency($number){

		$number = str_replace(",","", $number);

		if($number=='' || $number==0){
			return '0.00';
		}

		return number_format($number,2);
	}

}
