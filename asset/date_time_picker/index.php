<!DOCTYPE html>
<html>
<head>
	<title></title>

    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.min.css">
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery.datetimepicker.full.js"></script>
    
</head>
<body>
    Date : <input type="text" id="date">

    
    <script type="text/javascript">
        $("#date").datetimepicker({
            format:'Y/m/d H:i',
            step: 5});
    </script>

</body>
</html>