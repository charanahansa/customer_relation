<?php

namespace App\Http\Controllers\Hardware\Quotation\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware\Operation\JobCardProcess;

use App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Models\Master\Bank;

class InsuaranceClaimReportController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

	public function index(){

		$objBank = new Bank();

        $data['bank'] = $objBank->get_bank();
        $data['attributes'] = $this->get_insurance_claim_report_attributes(NULL, NULL);

        return view('hardware.quotation.report.insurance_claim_report')->with('ICR', $data);
    }

	private function get_insurance_claim_report_attributes($process, $request){

		$attributes['icr_date'] = '';
		$attributes['bank'] = '';
		$attributes['batch_no'] = '';
		$attributes['address_to'] = 'Gayan Gunawardena <br>
		Product Head - Merchant Services & Digital pay products <br>
		HNB Card Centre <br>
		Hatton National Bank PLC <br>
		Level 1.5, HNB Towers , No: 479, T.B. Jayah Mawatha, Colombo LO, Sri Lanka.<br>';
		$attributes['attention'] = 'Attention Dear Mr. Lalith Dharmapriya';
		$attributes['subject'] = 'Technical Report for cause of Damages of Machines';
		$attributes['jobcard'] = '';

		$attributes['process_message'] = '';
        $attributes['validation_messages'] = new MessageBag();

		return $attributes;
	}

	public function insurance_claim_report_process(Request $request){

		$batch_no = $request->batch_no;

		$html_code = $this->prepare_quotation_print_document($request);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html_code);
        $pdf->setPaper('A4', 'portrait');

		return $pdf->download('Insurance_Claim_Report_Batch_No._' . $batch_no .'.pdf');

		// $objBank = new Bank();

		// $data['bank'] = $objBank->get_bank();
        // $data['attributes'] = $this->get_insurance_claim_report_attributes(NULL, NULL);

		// return view('hardware.quotation.report.insurance_claim_report')->with('ICR', $data);
	}

	private function prepare_quotation_print_document($request){


		$html_code = "  <!DOCTYPE html>
						<html>
						<head>
							<title>Insurance Claim Report </title>

							<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>
							<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'></script>

							<style>
								p{
									font-family: 'Calibri';
									font-size: 12px;
								}
								h2{
									font-size: 20px;
								}
							</style>

						</head>
						<body>

							<p> ". $request->icr_date ." </p>

							<table style='width:100%'>
								<tr>
									<td  style='width:80%'><p>". $request->address_to ."</p></td>
									<td  style='width:20%'></td>
								</tr>
							</table>

							<p> ". $request->attention ." </p>

							<h2> ". $request->subject ." </h2>

							<p> Please find the details below </p>

							<table class='table table-hover table-sm table-bordered'>
								<tr style='font-family: Consolas; font-size: 11px; border: 1px solid black;'>
									<td style='width: 10%; border: 2px solid black;'><b>Jobcard No.</b></td>
                                    <td style='width: 10%; border: 2px solid black;'><b>QT Date</b></td>
									<td style='width: 20%; border: 2px solid black;'><b>Serial No.</b></td>
									<td style='width: 20%; border: 2px solid black;'><b>Model</b></td>
									<td style='width: 40%; border: 2px solid black;'><b>Cause of Damage</b></td>
								</tr>
								". $this->get_job_card_information($request) ."
							</table>
						</body>
					</html>

				";

		return $html_code;
	}

	private function get_job_card_information($request){

		$table_row = ' ';
		$jobcard_detail = explode(chr(13), $request->jobcard);

		foreach($jobcard_detail as $jobcard){

			$table_row .= $jobcard . ' > ';

			$jobcard_result = NULL;
			$objJobCardProcess = new JobCardProcess();
			$jobcard_result = $objJobCardProcess->get_jobcard_detail_for_insurance_claim_report(rtrim(ltrim($jobcard)));

			foreach($jobcard_result as $row){

				$table_row .= "<tr style='font-family: Consolas; font-size: 11px; border: 2px solid black;'>";
				$table_row .= "<td style='width: 12%; border: 2px solid black;'>". $row->jobcard_no ."</td>";
                $table_row .= "<td style='width: 12%; border: 2px solid black;'>". $row->qt_date ."</td>";
				$table_row .= "<td style='width: 12%; border: 2px solid black;'>". $row->serialno ."</td>";
				$table_row .= "<td style='width: 12%; border: 2px solid black;'>". $row->model ."</td>";

				if($row->reason <> 'High Voltage'){

					$table_row .= "<td style='width: 12%; border: 2px solid black;'>". 'User Negligence' ."</td>";

				}else{

					$table_row .= "<td style='width: 12%; border: 2px solid black;'>". $row->reason ."</td>";
				}


				$table_row .= "</tr>";
			}
		}

		//echo $table_row;

		return $table_row;

	}


}
