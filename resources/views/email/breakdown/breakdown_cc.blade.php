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
    <div id="tbldiv" style="width: 98%;  margin-right: 10%; margin-top: 1%;">

		<form name='frmterminal' id="frmterminal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

			<div class="form-row">
				<label for="deliverno" class="col-sm-1 col-form-label-sm"></label>
				<label for="deliverno" class="col-sm-10 col-form-label-sm">

				</label>
				<label for="deliverno" class="col-sm-1 col-form-label-sm"></label>
			</div>

			<div class="row no-gutters">

				<div class="col-12 col-sm-6 col-md-6" >
				<div style="margin-left: 1%; margin-right: 2%;">



					<div class="form-row">
						<label for="tid" class="col-sm-6 col-form-label-sm"></label>
						<label for="tid" class="col-sm-2 col-form-label-sm">Ticket No.</label>
						<div class="col-sm-4">
							<input type="text" name="txtticketno" class="form-control form-control-sm" id="txtticketno" value="" readonly>
						</div>
					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Bank</label>
						<div class="col-sm-4">
							<select name='cbobank' id="cbobank" class="form-control form-control-sm">

							</select>

						</div>
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Date</label>
						<div class="col-sm-4">
							<input type="text" name="txtdate" class="form-control form-control-sm" id="txtdate" value="" readonly>

						</div>
					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">TID</label>
						<div class="col-sm-4">
							<input type="text" name="txttid" class="form-control form-control-sm" id="txttid" value="" >

						</div>
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Model</label>
						<div class="col-sm-4">
							<select name="cbomodel" class="form-control form-control-sm" id="cbomodel">

							</select>

						</div>
					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Caller</label>
						<div class="col-sm-4">
							<input type="text" name="txtcaller" class="form-control form-control-sm" id="txtcaller" value="">

						</div>
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Receiver</label>
						<div class="col-sm-4">
							<input type="text" name="txtreceiver" class="form-control form-control-sm" id="txtreceiver" value="" >

						</div>
					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Calle Handle By</label>
						<div class="col-sm-10">
							<select name="cbocallhandler" class="form-control form-control-sm" id="cbocallhandler">

							</select>

						</div>
					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Fault Serial No.</label>
						<div class="col-sm-4">
							<input type="text" name="txtfaultserialno" class="form-control form-control-sm" id="txtfaultserialno" value="">

						</div>
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Replaced SNo.</label>
						<div class="col-sm-4">
							<input type="text" name="txtreplacedserialno" class="form-control form-control-sm" id="txtreplacedserialno" value="">

						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Merchant</label>
						<div class="col-sm-10">
							<textarea name="textmerchant" class="form-control form-control-sm" id="textmerchant" style="resize:none"  rows="3">
							</textarea>

						</div>
					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Contact No.</label>
						<div class="col-sm-10">
							<input type="text" name="txtcontactno" class="form-control form-control-sm" id="txtcontactno" value="">
						</div>

					</div>
					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Contact Person</label>
						<div class="col-sm-10">
							<input type="text" name="txtcontactperson" class="form-control form-control-sm" id="txtcontactperson" value="">
						</div>

					</div>

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Error</label>
						<div class="col-sm-10">
							<select name="cboerror" class="form-control form-control-sm" id="cboerror">

							</select>

						</div>
					</div>

				</div>
				</div>

				<div class="col-12 col-sm-6 col-md-6">
				<div style="margin-left: 2%; margin-right: 1%;">

					<div class="form-row">
						<label for="tid" class="col-sm-2 col-form-label-sm">Relevent Detail</label>
						<div class="col-sm-10">
							<select name="cborelevantdetail" class="form-control form-control-sm" id="cborelevantdetail">

							</select>

						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Remark</label>
						<div class="col-sm-10">
							<textarea name="textremark" class="form-control form-control-sm" id="textremark" style="resize:none" rows="2" >
							</textarea>

						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Officer</label>
						<div class="col-sm-10">
							<select name="cboofficer" class="form-control form-control-sm" id="cboofficer">

							</select>
						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Courier Provider</label>
						<div class="col-sm-10">
							<select name="cbocourier" class="form-control form-control-sm" id="cbocourier">

								?>
							</select>
						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Confirm By</label>
						<div class="col-sm-4">
							<input type="text" name="txtconfirmby" class="form-control form-control-sm" id="txtconfirmby" value="">
						</div>
						<label for="tid" class="col-sm-2 col-form-label-sm">Call Merchant</label>
						<div class="col-sm-4">
							<select name="cbocalltomerchant" class="form-control form-control-sm" id="cbocalltomerchant">

							</select>

						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Action Taken</label>
						<div class="col-sm-10">
							<select name="cboactiontaken" class="form-control form-control-sm" id="cboactiontaken">

							</select>
						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">FTL Remark</label>
						<div class="col-sm-10">
							<textarea name="textftl_remark" class="form-control form-control-sm" id="textftl_remark" style="resize:none" rows="2" readonly >
							</textarea>
						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">FS Remark</label>
						<div class="col-sm-10">
							<textarea name="textfs_remark" class="form-control form-control-sm" id="textfs_remark" style="resize:none" rows="2" readonly >
							</textarea>
						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Pod No.</label>
						<div class="col-sm-4">
							<input type="text" name="txtpodno" class="form-control form-control-sm" id="txtpodno" value="" readonly>
						</div>
						<label for="tid" class="col-sm-2 col-form-label-sm">Pod Date</label>
						<div class="col-sm-4">
							<input type="text" name="txtpoddate" class="form-control form-control-sm" id="txtpoddate" value="" readonly>
						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Sub Status</label>
						<div class="col-sm-10">
							<select name="cbosub_status" class="form-control form-control-sm" id="cbosub_status">

							</select>

						</div>
					</div>

					<div class="form-row">
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Status</label>
						<div class="col-sm-4">
						<select name="cbostatus" class="form-control form-control-sm" id="cbostatus">

							</select>
						</div>
						<label for="fromdate" class="col-sm-2 col-form-label-sm">Done Date Time</label>
						<div class="col-sm-4">
							<input type="text" name="txtdonedatetime" id="txtdonedatetime" class="form-control form-control-sm" value="">

						</div>
					</div>

					<div class="form-row">
						<div class="col-sm-8">
							<input type="text" name="txtcancelreason" class="form-control form-control-sm" id="txtcancelreason" value="" readonly>
						</div>
					</div>

				</div>
				</div>
			</div>
			<hr>
			<div  class="form-row">
				<div class="col-12">
					<input type="button" name="button" id="button" class="btn btn-primary btn-sm" value="Today" data-toggle="modal" data-target="#myModal">
					<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" value="Save" >
					<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" value="Email" >
					<input type="submit" name="submit" id="submit"  class="btn btn-primary btn-sm" value="Reset">
					<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" value="Cancel" >
					<input type="text" name="txttemplate" id="txttemplate" class="btn btn-outline-success btn-sm" value="">
					<input type="submit" name="submit" id="subDisplay"  class="btn btn-primary btn-sm" value="Display">
				</div>
			</div>
		</form>

		<script type="text/javascript">

			$("#txtdate").datetimepicker(
				{
					format:'d/m/Y'
				}
			);

			$("#txttoday").datetimepicker(
				{
					format:'Y/m/d'
				}
			);

			$("#txtdonedatetime").datetimepicker(
				{
					format:'d/m/Y H:i:s',
					step: 5
				}
			);

		</script>

	</div>

</body>
</html>
