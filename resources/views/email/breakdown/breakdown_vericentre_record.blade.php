<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .center {
            margin-left: 20px;
            border: 3px solid green;
        }

        td {

            font-family: Cabin; font-size: 18px;
            color: maroon;
            border: 2px solid black;
        }

        tr{

            border: 2px solid black;
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

        <p style="font-family: Cabin; font-size: 16px; color: maroon;"> Dear Team, </p>
        <p style="font-family: Cabin; font-size: 16px; color: maroon;"> Please Convert This Vericentre Record. </p>

        <br>

        <table style="width: 100%; border-collapse: collapse;">

            <tr>
                <td style="width: 10%; background-color:yellow">Ticket No.</td>
                <td style="width: 10%; background-color:yellow">Ticket Date</td>
                <td style="width: 10%; background-color:yellow">Bank</td>
                <td style="width: 10%; background-color:yellow">Tid</td>
                <td style="width: 60%; background-color:yellow">Merchant</td>
            </tr>

            <tr>
                <td style="width: 10%;">{{$result['ticketno']}}</td>
                <td style="width: 10%;">{{$result['date']}}</td>
                <td style="width: 10%;">{{$result['bank']}}</td>
                <td style="width: 10%;">{{$result['tid']}}</td>
                <td style="width: 60%;">{{$result['merchant']}}</td>
            </tr>

        </table>


        <p style="font-family: Cabin; color: maroon;">(Auto Genarated Email.)</p>





    </div>

</body>
</html>
