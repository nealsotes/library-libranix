<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Update balance</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
	<div class="container">
		<div id="form-sign" class="row justify-content-center">
			<div class="col-md-6">
				<legend>Enter details</legend>

				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				<form method="POST" action="#">
					<div class="form-group">
						<input type="text" name="m_user" class="form-control" placeholder="Member username" required>
					</div>
					<div class="form-group">
						<input type="number" name="m_balance" class="form-control" placeholder="Enter balance" required>
					</div>
					<button type="submit" name="m_add" class="btn btn-primary">Update Balance</button>
				</form>
			</div>
		</div>
	</div>
</body>

<?php
if (isset($_POST['m_add'])) {
	$query = $con->prepare("SELECT username FROM memberrecords WHERE username = ?;");
	$query->bind_param("s", $_POST['m_user']);
	$query->execute();
	if (mysqli_num_rows($query->get_result()) != 1)
		echo error_with_field("Invalid username", "m_user");
	else {
		$query = $con->prepare("UPDATE memberrecords SET balance = balance + ? WHERE username = ?;");
		$query->bind_param("ds", $_POST['m_balance'], $_POST['m_user']);
		if (!$query->execute())
			die(error_without_field("ERROR: Couldn\'t add balance"));
		echo success("Balance successfully updated");
	}
}
?>

</html>