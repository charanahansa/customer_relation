<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .center {
            margin-left: 20px;
            border: 3px solid green;
        }

        table, tr, td{

            font-family: Calibri;
            font-size: 16px;
            border: 3px solid black;
            color: black;
        }

        p {

            font-family: Calibri;
            font-size: 16px;
            color: black;
        }

    </style>
    <meta charset="UTF-8">

	<!-- Bootstrap 4.6 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</head>
<body>

    <div style="margin-left: 50px;">

        <p> Dear HW Team & Anurudda, </p>
        <p> This Quotation No. {{$result['qt_no']}}  is approved. </p>

        <p>(Auto Genarated Email.)</p>

        <br>

        <table style="width: 100%; border-collapse: collapse;">

            <tr>
                <td style="width: 15%; background-color:yellow">Quotation No.</td>
                <td style="width: 15%; background-color:yellow">Quotation Date</td>
                <td style="width: 15%; background-color:yellow">Job Card No.</td>
                <td style="width: 15%; background-color:yellow">Bank</td>
                <td style="width: 15%; background-color:yellow">Serial No.</td>
                <td style="width: 20%; background-color:yellow">Quotation Amount</td>
            </tr>

            <tr>
                <td style="width: 15%;">{{$result['qt_no']}}</td>
                <td style="width: 15%;">{{$result['qt_date']}}</td>
                <td style="width: 15%;">{{$result['jobcard_no']}}</td>
                <td style="width: 15%;">{{$result['bank']}}</td>
                <td style="width: 15%;">{{$result['serial_no']}}</td>
                <td style="width: 15%; text-align: right;">@money($result['price'])</td>
            </tr>

        </table>

    </div>

</body>
</html>
