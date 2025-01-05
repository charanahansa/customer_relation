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
            color: blue;
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

        <p style="font-family: Cabin; font-size: 16px; color: blue;"> Dear Team, </p>
        <p style="font-family: Cabin; font-size: 16px; color: blue;"> FYI </p>

        <p style="font-family: Cabin; color: blue;">(Auto Genarated Email.)</p>

        <br>

        <table style="width: 100%;">

            <tr>
                <td style="width: 15%;">Ticket No.</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['ticketno']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Date</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['date']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Bank</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['bank']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Tid</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['tid']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Model</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['model']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Merchant</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['merchant']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Fault</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['fault']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Contact No.</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['contact_number']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Contact Person</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['contact_person']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Relevent Detail</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['relevent_detail']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Remark</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['remark']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Action Taken</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['action_taken']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Fault Serial No.</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['fault_serialno']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Replaced Serial No.</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{$result['replaced_serialno']}}</td>
            </tr>

            <tr>
                <td style="width: 15%;">Status</td>
                <td style="width: 2%"> : </td>
                <td style="width: 78%;">{{ucfirst($result['status'])}}</td>
            </tr>

        </table>

    </div>

</body>
</html>
